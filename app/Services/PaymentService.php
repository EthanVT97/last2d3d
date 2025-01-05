<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use App\Models\PaymentMethod;
use App\Models\DepositAccount;
use App\Events\BalanceUpdated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentService
{
    /**
     * Create a deposit request
     */
    public function createDepositRequest(User $user, array $data): Transaction
    {
        return DB::transaction(function () use ($user, $data) {
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'type' => 'deposit',
                'amount' => $data['amount'],
                'payment_method' => $data['payment_method'],
                'status' => 'pending',
                'reference' => $this->generateReference('DEP'),
                'payment_details' => [
                    'account_name' => $data['account_name'] ?? null,
                    'account_number' => $data['account_number'] ?? null,
                    'bank_name' => $data['bank_name'] ?? null,
                    'screenshot' => $data['screenshot'] ?? null,
                ],
            ]);

            // Create notification for admin
            $user->notifications()->create([
                'type' => 'deposit_request',
                'title' => 'ငွေသွင်း တောင်းဆိုချက်',
                'message' => "ငွေပမာဏ: {$data['amount']} ကျပ်\nငွေပေးချေမှု: {$data['payment_method']}",
                'data' => [
                    'transaction_id' => $transaction->id,
                    'amount' => $data['amount'],
                    'payment_method' => $data['payment_method'],
                ]
            ]);

            return $transaction;
        });
    }

    /**
     * Create a withdrawal request
     */
    public function createWithdrawalRequest(User $user, array $data): Transaction
    {
        return DB::transaction(function () use ($user, $data) {
            // Validate balance
            if ($user->balance < $data['amount']) {
                throw new \Exception('လက်ကျန်ငွေ မလုံလောက်ပါ။');
            }

            // Create transaction
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'type' => 'withdraw',
                'amount' => $data['amount'],
                'payment_method' => $data['payment_method'],
                'status' => 'pending',
                'reference' => $this->generateReference('WD'),
                'payment_details' => [
                    'account_name' => $data['account_name'],
                    'account_number' => $data['account_number'],
                    'bank_name' => $data['bank_name'] ?? null,
                ],
            ]);

            // Hold the amount
            $user->decrement('balance', $data['amount']);
            $user->increment('pending_withdrawal', $data['amount']);

            // Create notification for admin
            $user->notifications()->create([
                'type' => 'withdrawal_request',
                'title' => 'ငွေထုတ် တောင်းဆိုချက်',
                'message' => "ငွေပမာဏ: {$data['amount']} ကျပ်\nငွေထုတ်ယူမှု: {$data['payment_method']}",
                'data' => [
                    'transaction_id' => $transaction->id,
                    'amount' => $data['amount'],
                    'payment_method' => $data['payment_method'],
                ]
            ]);

            return $transaction;
        });
    }

    /**
     * Approve a deposit
     */
    public function approveDeposit(Transaction $transaction): void
    {
        DB::transaction(function () use ($transaction) {
            if ($transaction->status !== 'pending') {
                throw new \Exception('Invalid transaction status');
            }

            $transaction->update(['status' => 'completed']);
            
            // Update user balance
            $user = $transaction->user;
            $user->increment('balance', $transaction->amount);

            // Create notification for user
            $user->notifications()->create([
                'type' => 'deposit_approved',
                'title' => 'ငွေသွင်းခြင်း အတည်ပြုပြီး',
                'message' => "သင့်၏ ငွေသွင်းခြင်း {$transaction->amount} ကျပ် အတည်ပြုပြီးပါပြီ။",
                'data' => [
                    'transaction_id' => $transaction->id,
                    'amount' => $transaction->amount,
                ]
            ]);

            // Broadcast balance update
            event(new BalanceUpdated($user));
        });
    }

    /**
     * Approve a withdrawal
     */
    public function approveWithdrawal(Transaction $transaction): void
    {
        DB::transaction(function () use ($transaction) {
            if ($transaction->status !== 'pending') {
                throw new \Exception('Invalid transaction status');
            }

            $transaction->update(['status' => 'completed']);
            
            // Update user balance
            $user = $transaction->user;
            $user->decrement('pending_withdrawal', $transaction->amount);

            // Create notification for user
            $user->notifications()->create([
                'type' => 'withdrawal_approved',
                'title' => 'ငွေထုတ်ခြင်း အတည်ပြုပြီး',
                'message' => "သင့်၏ ငွေထုတ်ခြင်း {$transaction->amount} ကျပ် အတည်ပြုပြီးပါပြီ။",
                'data' => [
                    'transaction_id' => $transaction->id,
                    'amount' => $transaction->amount,
                ]
            ]);

            // Broadcast balance update
            event(new BalanceUpdated($user));
        });
    }

    /**
     * Reject a transaction
     */
    public function rejectTransaction(Transaction $transaction, string $reason): void
    {
        DB::transaction(function () use ($transaction, $reason) {
            if ($transaction->status !== 'pending') {
                throw new \Exception('Invalid transaction status');
            }

            $transaction->update([
                'status' => 'rejected',
                'rejection_reason' => $reason
            ]);

            $user = $transaction->user;

            // If withdrawal, return the held amount
            if ($transaction->type === 'withdraw') {
                $user->increment('balance', $transaction->amount);
                $user->decrement('pending_withdrawal', $transaction->amount);
            }

            // Create notification for user
            $title = $transaction->type === 'deposit' ? 'ငွေသွင်းခြင်း ငြင်းပယ်ခံရပါသည်' : 'ငွေထုတ်ခြင်း ငြင်းပယ်ခံရပါသည်';
            $type = $transaction->type === 'deposit' ? 'ငွေသွင်း' : 'ငွေထုတ်';
            
            $user->notifications()->create([
                'type' => $transaction->type . '_rejected',
                'title' => $title,
                'message' => "သင့်၏ {$type}ခြင်း {$transaction->amount} ကျပ် ငြင်းပယ်ခံရပါသည်။\n\nအကြောင်းပြချက်: {$reason}",
                'data' => [
                    'transaction_id' => $transaction->id,
                    'amount' => $transaction->amount,
                    'reason' => $reason
                ]
            ]);

            // Broadcast balance update
            event(new BalanceUpdated($user));
        });
    }

    /**
     * Get active deposit accounts grouped by bank
     */
    public function getDepositAccounts()
    {
        return DepositAccount::where('status', true)
            ->get()
            ->groupBy('bank_name')
            ->map(function ($accounts) {
                return $accounts->map(function ($account) {
                    return [
                        'id' => $account->id,
                        'account_name' => $account->account_name,
                        'account_number' => $account->account_number,
                        'bank_name' => $account->bank_name,
                        'remarks' => $account->remarks,
                    ];
                });
            });
    }

    /**
     * Get available payment methods
     */
    public function getPaymentMethods(): array
    {
        // For now, return hardcoded payment methods
        // TODO: Move this to database configuration
        return [
            'kpay' => [
                'name' => 'KBZ Pay',
                'icon' => 'kpay.png',
                'account_name' => 'John Doe',
                'account_number' => '09123456789'
            ],
            'wavepay' => [
                'name' => 'Wave Pay',
                'icon' => 'wavepay.png',
                'account_name' => 'John Doe',
                'account_number' => '09987654321'
            ],
            'cbpay' => [
                'name' => 'CB Pay',
                'icon' => 'cbpay.png',
                'account_name' => 'John Doe',
                'account_number' => '09456789123'
            ]
        ];
    }

    /**
     * Generate a unique reference number
     */
    private function generateReference(string $prefix): string
    {
        return $prefix . date('ymd') . strtoupper(Str::random(6));
    }
}
