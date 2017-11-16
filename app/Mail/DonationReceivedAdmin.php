<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\View;
use Illuminate\Support\HtmlString;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class DonationReceivedAdmin extends Mailable
{
    use Queueable, SerializesModels;

    protected $amount, $is_recurring, $recurring_frequency, $recurring_start;
    protected $transaction_id, $plan_id, $comment;
    protected $first_name, $last_name, $address, $phone, $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($amount,
                                $is_recurring,
                                $recurring_frequency,
                                $recurring_start,
                                $transaction_id,
                                $plan_id,
                                $comment,
                                $first_name,
                                $last_name,
                                $address,
                                $phone,
                                $email)
    {
        $this->amount = $amount;
        $this->is_recurring = $is_recurring;
        $this->recurring_frequency = $recurring_frequency;
        $this->recurring_start = $recurring_start;
        $this->transaction_id = $transaction_id;
        $this->plan_id = $plan_id;
        $this->comment = $comment;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->address = $address;
        $this->phone = $phone;
        $this->email = $email;
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
            'greeting' => 'Donation Alert!',
            'level' => '',
            'introLines' => [
                'Donor Information',
            ],
            'details' => [
                'amount' => $this->amount,
                'is_recurring' => $this->is_recurring,
                'recurring_frequency' => $this->recurring_frequency,
                'recurring_start' => $this->recurring_start,
                'transaction_id' => $this->transaction_id,
                'plan_id' => $this->plan_id,
                'comment' => $this->comment,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'address' => $this->address,
                'phone' => $this->phone,
                'email' => $this->email,
                'admin' => 1
            ],
            'outroLines' => [ ],
        ]);

        $view =  new HtmlString(with(new CssToInlineStyles)->convert($view));

        return $this->subject('Donation was received')
            ->view('emails.blank', ['bodyContent' => $view]);
    }
}
