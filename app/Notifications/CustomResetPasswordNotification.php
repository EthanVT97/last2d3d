<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class CustomResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(Lang::get('စကားဝှက်ပြန်လည်သတ်မှတ်ရန် တောင်းဆိုချက်'))
            ->line(Lang::get('သင်၏စကားဝှက် ပြန်လည်သတ်မှတ်ရန် တောင်းဆိုချက်ကို လက်ခံရရှိပါသည်။'))
            ->action(Lang::get('စကားဝှက်ပြန်လည်သတ်မှတ်ရန်'), url(config('app.url').route('password.reset', ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()], false)))
            ->line(Lang::get('ဤလင့်ခ်သည် :count မိနစ်အတွင်း သက်တမ်းကုန်ဆုံးမည်ဖြစ်ပါသည်။', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
            ->line(Lang::get('စကားဝှက်ပြန်လည်သတ်မှတ်ရန် မတောင်းဆိုထားပါက ဤအီးမေးလ်ကို လျစ်လျူရှုနိုင်ပါသည်။'));
    }
}
