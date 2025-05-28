<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderMail;
class MailController extends Controller
{
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
}
