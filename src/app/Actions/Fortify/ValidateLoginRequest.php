<?php

namespace App\Actions\Fortify;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Validator;

class ValidateLoginRequest
{
    public function handle($request, $next)
    {
        $loginRequest = app(LoginRequest::class);

        Validator::make(
            $request->all(),
            $loginRequest->rules(),
            $loginRequest->messages()
        )->validate();

        return $next($request);
    }
}
