<?php

namespace App\Http\Controllers;

use App\Mail\GreetingMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class greetingcontroller extends Controller
{
    //

    public function sendGreeting(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
            'attachments.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $htmlContent = $request->message;
        $files = $request->file('attachments', []);
        $subject = $request->filled('subject') ? trim($request->subject) : 'Greeting Mail';

        try {
            Mail::to($request->email)->send(
                new GreetingMail($htmlContent, $files, $subject)
            );

            return back()->with('success', 'Greeting email sent successfully to ' . $request->email . '!');
        } catch (\Throwable $e) {
            Log::error('Greeting Mail Error: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Failed to send greeting email: ' . $e->getMessage());
        }
    }

    public function sendmail()
    {
        return view('Mails/greetings');
    }
}
