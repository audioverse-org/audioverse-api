<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\View;
use Illuminate\Support\HtmlString;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class DonationReceived extends Mailable
{
    use Queueable, SerializesModels;

    protected $amount, $is_recurring, $recurring_frequency, $recurring_start;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($amount, $is_recurring, $recurring_frequency, $recurring_start)
    {
        $this->amount = $amount;
        $this->is_recurring = $is_recurring;
        $this->recurring_frequency = $recurring_frequency;
        $this->recurring_start = $recurring_start;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $view_file = 'emails.notification';
        $view = View::make($view_file, [
            'greeting' => 'Thank You!',
            'level' => '',
            'introLines' => [],
            'details' => [
                'amount' => $this->amount,
                'is_recurring' => $this->is_recurring,
                'recurring_frequency' => $this->recurring_frequency,
                'recurring_start' => $this->recurring_start,
                'recurring_note_1' => 'To update or cancel your recurring payment information, please contact us.',
                'recurring_note_2' => 'This email is a donation confirmation only. A receipt suitable for tax deduction purposes will be sent in January.'
            ],
            'outroLines' => [
                'Thank you for your contribution to AudioVerse! Your gift will enable thousands of people around the world to access life-changing, Bible-based, Christ-centered messages online for free.',
                'We believe that Jesus is coming soon and there’s no time to lose in getting the word out to as many as possible, as quickly as possible.  Your gift will help propel this mission forward.  Thousands are listening to AudioVerse every day but there are millions who still need to hear. Your financial contribution will enable the Everlasting Gospel to go to many more.',
                'Thank you for joining hands with us in this work as a financial partner, you are an essential part of the AudioVerse team.  And by the grace of God, may we see His work finished in this generation.',
            ],
            'signature' => [
                'signoff' => 'In Him',
                'name' => 'Alistair Huong, Executive Director',
                'image' => 'https://s.audioverse.org/images/template/AlistairSignature1.png'
            ]
        ]);

        $view =  new HtmlString(with(new CssToInlineStyles)->convert($view));

        return $this->subject('Your donation was received')
            ->view('emails.blank', ['bodyContent' => $view]);
    }
}
