<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\HtmlString;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class ResetPassword extends Notification implements ShouldQueue
{
    use Queueable;

    protected $token;
    protected $email;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        $view_file = 'emails.notification';
        $view = View::make($view_file, [
            'greeting' => 'Hello!',
            'level' => '',
            'introLines' => [
                'You are receiving this email because we received a password reset request for your account.',
            ],
            'outroLines' => [ 'Thank you for using AudioVerse!' ],
            'actionText' => 'Reset Password',
            'actionUrl' => url(config('avorg.password_reset_page_url').$this->token . '&email=' . $this->email),
        ]);

        $view =  new HtmlString(with(new CssToInlineStyles)->convert($view));

        return (new MailMessage)
            ->view('emails.blank', ['bodyContent' => $view]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
