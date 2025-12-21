<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;


class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        // if ($user->is_first_login) {
        //     $user->update(['is_first_login' => false]);
        //     $user->sendEmailVerificationNotification();
        //     return redirect('/verify');
        // } else {
        //     return redirect('/');
        // }
        return redirect('/');
    }
}
