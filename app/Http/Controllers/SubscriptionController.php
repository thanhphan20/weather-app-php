<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubscriptionConfirmation;
use App\Mail\UnsubscriptionConfirmation;
use App\Models\Subscription;

class SubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        $email = $request->input('email');

        $subscription = Subscription::where('email', $email)->first();

        if (!$subscription) {
            Mail::to($email)->send(new SubscriptionConfirmation($email));
            return redirect()->route('dashboard')->with('sub', 'Your confirm subscribe email has been sent');
        }

        return redirect()->route('dashboard')->with('error-sub', 'Your email already confirmed and subscribed');
    }

    public function unsubscribe(Request $request)
    {
        $email = $request->input('email');

        $subscription = Subscription::where('email', $email)->first();

        if ($subscription) {
            Mail::to($email)->send(new UnsubscriptionConfirmation($email));
            return redirect()->route('dashboard')->with('unsubscribed', 'An email has been sent to you to confirm your unsubscription');
        }

        return redirect()->route('dashboard')->with('error-un', 'This email is not subscribed yet');
    }

    public function confirmSubscription(Request $request)
    {
        $email = $request->input('email');

        $subscription = Subscription::where('email', $email)->first();

        if (!$subscription) {

            Subscription::create([
                'email' => $email,
                'status' => 'confirmed',
                'email_verified_at' => now()
            ]);

            return redirect()->route('dashboard')->with('confirmed', 'Your email has been confirmed and subscribed to the subscription');
        } else {
            return redirect()->route('dashboard')->with('error-confirmed', 'An error has been happend during confirming your email');
        }
    }

    public function confirmUnsubscription(Request $request)
    {
        $email = $request->input('email');

        $subscription = Subscription::where('email', $email)->first();

        if ($subscription) {

            $subscription->delete();

            return redirect()->route('dashboard')->with('unsubscribed', 'You have been successfully unsubscribed from the newsletter.');
        } else {
            return redirect()->route('dashboard')->with('error-unsubscribed', 'This email is not subscribed to the newsletter.');
        }
    }
}
