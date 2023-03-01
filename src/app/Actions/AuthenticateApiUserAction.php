<?php

namespace App\Actions;

use App\Exceptions\InvalidCredentialException;
use App\Exceptions\UserAlreadyLoggedInException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthenticateApiUserAction
{
    public function execute(string $username, string $password): string
    {
        $user = User::where('username',$username)->first();

        if( ! $user) {
            throw new InvalidCredentialException("Invalid credentials");
        }

        if(! Hash::check($password, $user->password)) {
            throw new InvalidCredentialException("Invalid credentials");
        }

        if($this->userAlreadyLoggedIn($user)) {
            throw new UserAlreadyLoggedInException("There is already an active session using your account");
        }

        $token = Str::random(80);

        $user->forceFill([
            'api_token' => hash('sha256', $token),
        ])->save();

        Auth::guard()->setUser($user);

        return $token;
    }

    private function userAlreadyLoggedIn($user): bool
    {
        return !!$user->api_token;
    }
}
