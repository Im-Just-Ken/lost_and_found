<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        return match (true) {
            $user->hasRole('super-admin') || $user->hasRole('moderator')
                => redirect()->route('dashboard'),

            $user->hasRole('member')
                => redirect()->route('member.dashboard'),

            default
                => redirect()->route('dashboard'),
        };
    }
}