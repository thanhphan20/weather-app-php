<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Subscription;
use App\Models\User;
use App\Notifications\SubscriptionConfirmation;
use App\Notifications\UnsubscriptionConfirmation;

class SubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        $email = $request->input('email');

        $user  = User::where('email', $email)->first();

        $name = explode('@', $email)[0];

        if (!$user) {
            $user = User::create([
                'name' => $name,
                'email' => $email,
            ]);
        }

        if (!$user->subscription) {
            Subscription::create([
                'user_id' => $user->id,
                'status' => 'pending',
            ]);

            $user->notify(new SubscriptionConfirmation($email));
            return redirect()->route('dashboard')->with('subscribed', 'Your confirm subscribe email has been sent');
        }

        return redirect()->route('dashboard')->with('error-sub', 'Your email already confirmed and subscribed');
    }

    public function unsubscribe(Request $request)
    {
        $email = $request->input('email');

        $user = User::where('email', $email)->first();

        if ($user && $user->subscription) {
            $user->notify(new UnsubscriptionConfirmation($email));
            return redirect()->route('dashboard')->with('unsubscribed', 'An email has been sent to you to confirm your unsubscription');
        }
        return redirect()->route('dashboard')->with('error-un', 'This email is not subscribed yet');
    }

    public function confirmSubscription(Request $request)
    {
        $email = $request->input('email');

        $user = User::where('email', $email)->first();

        if(!$user){
            return redirect()->route('dashboard')->with('subagain', 'You need to register again to confirm your subscription');
        }

        $subscription = Subscription::where('user_id', $user->id)->first();

        if ($subscription) {
            if ($subscription->status === 'pending') {
                $subscription->update([
                    'status' => 'confirmed',
                    'email_verified_at' => now()
                ]);

                return redirect()->route('dashboard')->with('confirmed', 'Your email has been confirmed and subscribed to the subscription');
            } else {
                return redirect()->route('dashboard')->with('error-confirmed', 'Your email has already been confirmed');
            }
        } else {
            return redirect()->route('dashboard')->with('error-confirmed', 'This email is not registered for subscription');
        }
    }

    public function confirmUnsubscription(Request $request)
    {
        $email = $request->input('email');

        $user = User::where('email', $email)->first();

        if(!$user){
            return redirect()->route('dashboard')->with('unsubagain', 'You need to unsubscribed again to confirm your unsubscription');
        }

        $subscription = Subscription::where('user_id', $user->id)->first();

        if ($subscription) {
            $subscription->delete();
            $user->delete();
            return redirect()->route('dashboard')->with('unsubscribed', 'You have been successfully unsubscribed from the newsletter.');
        } else {
            return redirect()->route('dashboard')->with('error-unsubscribed', 'This email is not subscribed to the newsletter.');
        }
    }
}
