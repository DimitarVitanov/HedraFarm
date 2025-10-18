<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Mail\NewsLetterMail;
use App\Models\NewsLetter;

class NewsLetterController extends Controller
{
    public function sendNewsletterEmail(Request $request)
    {
        $data = [
            'subject' => 'Newsletter Subscription Confirmation',
            'email' => $request->email,
        ];

        Mail::to($request->email)->send(new NewsLetterMail($data));

        NewsLetter::create($data);

        return response()->json(['status' => 'success', 'message' => 'You have successfully subscribed to the newsletter.']);
    }

    public function unsubscribe($email)
    {
        $subscriber = NewsLetter::where('email', $email)->first();

        if ($subscriber) {
            $subscriber->delete();
            return view('emails.unsubscribed', ['email' => $email]);
        }

        return redirect('/')->with('message', 'Email not found or already unsubscribed.');
    }
}
