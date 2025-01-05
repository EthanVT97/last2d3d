<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{
    HomeController,
    LotteryController,
    ProfileController,
    TransactionController,
    DepositController,
    WithdrawController,
    NotificationController,
    AgentController,
    PlayController,
    DashboardController,
    WithdrawalController
};
use App\Http\Controllers\Admin\{
    DashboardController as AdminDashboardController,
    ProfileController as AdminProfileController,
    AgentController as AdminAgentController,
    PlayController as AdminPlayController,
    UserController as AdminUserController,
    ResultController as AdminResultController,
    TransactionController as AdminTransactionController,
    DepositAccountController,
    ReportController as AdminReportController,
    SettingController,
    PaymentMethodController,
    WithdrawalController as AdminWithdrawalController
};
use App\Http\Controllers\User\{
    DashboardController as UserDashboardController,
    ProfileController as UserProfileController,
    PlayController as UserPlayController,
    WithdrawalController as UserWithdrawalController,
    TransactionController as UserTransactionController
};
use App\Http\Controllers\Auth\{
    LoginController,
    RegisterController,
    ForgotPasswordController,
    ResetPasswordController,
    VerificationController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Health Check Route for Render
Route::get('/health', function() {
    return response()->json(['status' => 'healthy'], 200);
});

// Home Route
Route::get('/home', [HomeController::class, 'index']);

// Dashboard Route
Route::get('/dashboard', [UserDashboardController::class, 'index'])->middleware('auth')->name('dashboard');

// Customer Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Authenticated User Routes
Route::middleware('auth')->group(function () {
    // Deposit Routes
    Route::get('/deposit', [DepositController::class, 'index'])->name('deposit.index');
    Route::get('/deposit/create', [DepositController::class, 'create'])->name('deposit.create');
    Route::post('/deposit', [DepositController::class, 'store'])->name('deposit.store');
    
    // Profile Routes
    Route::get('/profile', [UserProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [UserProfileController::class, 'password'])->name('profile.password');
    Route::get('/profile/referrals', [UserProfileController::class, 'referrals'])->name('profile.referrals');
    
    // Transaction Routes
    Route::get('/transactions', [UserTransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [UserTransactionController::class, 'show'])->name('transactions.show');
    
    // Withdraw Routes
    Route::get('/withdraw', [WithdrawController::class, 'create'])->name('withdraw.create');
    Route::post('/withdraw', [WithdrawController::class, 'store'])->name('withdraw.store');
    Route::get('/withdraws', [WithdrawController::class, 'index'])->name('withdraw.index');
    
    // Lottery Routes
    Route::get('/lottery', [LotteryController::class, 'index'])->name('lottery.index');
    Route::get('/lottery/2d', [LotteryController::class, 'twoD'])->name('lottery.2d');
    Route::get('/lottery/3d', [LotteryController::class, 'threeD'])->name('lottery.3d');
    Route::prefix('lottery')->name('lottery.')->group(function () {
        Route::get('/thai', [LotteryController::class, 'thai'])->name('thai');
        Route::get('/laos', [LotteryController::class, 'laos'])->name('laos');
        Route::post('/store', [LotteryController::class, 'store'])->name('store');
        Route::get('/results', [LotteryController::class, 'results'])->name('results');

        // Store route - POST only
        Route::post('/{type}/bet', [LotteryController::class, 'store'])
            ->name('store')
            ->where('type', '2d|3d|thai|laos');

        // Show route - must be last
        Route::get('/{type}', [LotteryController::class, 'show'])
            ->name('show')
            ->where('type', '2d|3d|thai|laos');
    });

    // User Transaction Routes
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/transactions', [UserTransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/{transaction}', [UserTransactionController::class, 'show'])->name('transactions.show');
    });
    
    // Results
    Route::get('/results', [AdminResultController::class, 'index'])->name('results');
    
    // Transaction History
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [UserTransactionController::class, 'index'])->name('index');
        Route::get('/deposit', [UserTransactionController::class, 'deposit'])->name('deposit');
        Route::post('/deposit', [UserTransactionController::class, 'storeDeposit'])->name('deposit.store');
        Route::get('/withdraw', [UserTransactionController::class, 'withdraw'])->name('withdraw');
        Route::post('/withdraw', [UserTransactionController::class, 'storeWithdraw'])->name('withdraw.store');
    });
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
});

// User Routes
Route::middleware(['auth', 'user'])->name('user.')->group(function () {
    // Withdrawals
    Route::get('/withdrawals/create', [UserWithdrawalController::class, 'create'])->name('withdrawals.create');
    Route::post('/withdrawals', [UserWithdrawalController::class, 'store'])->name('withdrawals.store');

    // Plays
    Route::get('/plays', [UserPlayController::class, 'index'])->name('plays.index');
    Route::get('/plays/{play}', [UserPlayController::class, 'show'])->name('plays.show');
});

// Admin Auth Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [LoginController::class, 'showAdminLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'adminLogin'])->name('login.submit');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('index');
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Users Management
    Route::resource('users', AdminUserController::class);
    Route::post('/users/{user}/ban', [AdminUserController::class, 'ban'])->name('users.ban');
    Route::post('/users/{user}/unban', [AdminUserController::class, 'unban'])->name('users.unban');
    
    // Agents Management
    Route::resource('agents', AdminAgentController::class);
    Route::post('/agents/{agent}/toggle-status', [AdminAgentController::class, 'toggleStatus'])->name('agents.toggle-status');
    
    // Plays Management
    Route::prefix('plays')->name('plays.')->group(function () {
        Route::get('/', [AdminPlayController::class, 'index'])->name('index');
        Route::get('/{play}', [AdminPlayController::class, 'show'])->name('show');
        Route::put('/{play}', [AdminPlayController::class, 'update'])->name('update');
        Route::post('/approve-all', [AdminPlayController::class, 'approveAll'])->name('approve-all');
    });
    
    // Transactions Management
    Route::resource('transactions', AdminTransactionController::class)->only(['index', 'show', 'update']);
    Route::post('/transactions/{transaction}/approve', [AdminTransactionController::class, 'approve'])->name('transactions.approve');
    Route::post('/transactions/{transaction}/reject', [AdminTransactionController::class, 'reject'])->name('transactions.reject');
    
    // Results Management
    Route::resource('results', AdminResultController::class)->except(['show']);
    
    // Deposit Accounts
    Route::resource('deposit-accounts', DepositAccountController::class);
    
    // Reports
    Route::get('/reports/profit', [AdminReportController::class, 'profit'])->name('reports.profit');
    Route::get('/reports/expense', [AdminReportController::class, 'expense'])->name('reports.expense');
    Route::get('/reports/users', [AdminReportController::class, 'userReport'])->name('reports.users');
    Route::get('/reports/agents', [AdminReportController::class, 'agents'])->name('reports.agents');
    Route::get('/reports/transactions', [AdminReportController::class, 'transactions'])->name('reports.transactions');
    Route::get('/reports/transactions/export', [AdminReportController::class, 'export'])->name('reports.transactions.export');

    // Admin Profile
    Route::get('/profile', [AdminProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');

    // Payment Methods Management
    Route::get('/payment-methods', [PaymentMethodController::class, 'index'])->name('payment-methods.index');
    Route::get('/payment-methods/create', [PaymentMethodController::class, 'create'])->name('payment-methods.create');
    Route::post('/payment-methods', [PaymentMethodController::class, 'store'])->name('payment-methods.store');
    Route::get('/payment-methods/{paymentMethod}/edit', [PaymentMethodController::class, 'edit'])->name('payment-methods.edit');
    Route::put('/payment-methods/{paymentMethod}', [PaymentMethodController::class, 'update'])->name('payment-methods.update');
    Route::delete('/payment-methods/{paymentMethod}', [PaymentMethodController::class, 'destroy'])->name('payment-methods.destroy');

    // Withdrawals
    Route::get('/withdrawals', [AdminWithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::post('/withdrawals/{withdrawal}/approve', [AdminWithdrawalController::class, 'approve'])->name('withdrawals.approve');
    Route::post('/withdrawals/{withdrawal}/reject', [AdminWithdrawalController::class, 'reject'])->name('withdrawals.reject');

    // Settings Management
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::get('/edit/{type}', [SettingController::class, 'edit'])->name('edit');
        Route::put('/lottery/{type}', [SettingController::class, 'updateLotterySettings'])->name('update.lottery');
        Route::put('/time', [SettingController::class, 'updateTimeSettings'])->name('time.update');
    });
});

// Auth Routes
Auth::routes(['verify' => true]);

// Static Pages
Route::get('/help', function () {
    return view('help');
})->name('help');

Route::get('/terms', function () {
    return view('terms');
})->name('terms');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
