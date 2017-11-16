<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\HtmlString;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class VerifyEmail extends Notification implements ShouldQueue
{
    use Queueable;

    protected $token;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
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
            'greeting' => 'Greetings!',
            'level' => '',
            'introLines' => [
                'To activate your account, please verify your email.',

            ],
            'outroLines' => [ 'Thank you for using AudioVerse!' ],
            'actionText' => 'Verify Email',
            'actionUrl' => url(config('avorg.verify_email_page_url').$this->token),
        ]);

        $view =  new HtmlString(with(new CssToInlineStyles)->convert($view));

        return (new MailMessage)
            ->subject('Complete your Signup')
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
