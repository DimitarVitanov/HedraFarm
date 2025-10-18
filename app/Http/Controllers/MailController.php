<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderMail;
use App\Mail\ContactMail;
class MailController extends Controller
{
    protected $MAIL_TO = 'info@hederafarmplus.mk';
    public function sendContactEmail(Request $request)
    {
        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'message' => $request->message,
        ];

        Mail::to($data['email'])->send(new OrderMail($data));

        return response()->json(['status' => 'success', 'message' => 'Email sent successfully']);
    }

    public function sendSupportEmail(Request $request)
    {
        $data = [
            'subject' => $request->subject,
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
        ];

        Mail::to($this->MAIL_TO)->send(new ContactMail($data));

        return response()->json(['status' => 'success', 'message' => 'Вашето барање е испратено.']);
    }
}
