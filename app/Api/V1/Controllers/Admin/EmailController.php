<?php

namespace App\Api\v1\Controllers\Admin;

use App\Api\V1\Requests\DonationEmailRequest;
use App\Http\Controllers\Controller;
use App\Mail\DonationReceived;
use App\Mail\DonationReceivedAdmin;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    use Helpers;

    public function donation_confirmation(DonationEmailRequest $request) {

        //  to donor
        Mail::to($request->input('email', 'ki@audioverse.org'))
            ->queue(new DonationReceived(
                $request->input('amount', 0),
                $request->input('is_recurring', 0),
                $request->input('recurring_frequency', 0),
                $request->input('recurring_start', 0)
            ));
        // to admin

        Mail::to(config('avorg.contact_email'))
            ->queue(new DonationReceivedAdmin(
                $request->input('amount', 0),
                $request->input('is_recurring', 0),
                $request->input('recurring_frequency', 0),
                $request->input('recurring_start', 0),
                $request->input('transaction_id',0),
                $request->input('plan_id',0),
                $request->input('comment', ''),
                $request->input('first_name',''),
                $request->input('last_name',''),
                $request->input('address',''),
                $request->input('phone',''),
                $request->input('email','')
            ));

        return response()->json([
            'message' => 'Message Sent',
            'status_code' => 201
        ], 201);
    }
}