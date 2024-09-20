<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\User;

class UpdateLastLoginAt { 

    /** 
     * Create the event listener. 
    */ 
    public function __construct() { 
        // 
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = User::find($event->user->id);

        if ($user) {
            $user->last_login_at = now();
            $user->save();
        }
    }

}