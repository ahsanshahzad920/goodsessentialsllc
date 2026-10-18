<?php
namespace App\Listeners;

use App\Mail\WelcomeUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail
{
    public function handle(Registered $event): void
    {
        $user = $event->user;

        if (empty($user->email)) {
            return;
        }

        Mail::to($user->email)->send(new WelcomeUser($user));
    }
}
