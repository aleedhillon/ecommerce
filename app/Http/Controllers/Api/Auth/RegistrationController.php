<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\Api\Auth\UserRegistrationRequest;

class RegistrationController extends Controller
{
    public function register(UserRegistrationRequest $request)
    {
        $userRequest = $request->validated();
        $userRequest['password'] = Hash::make($userRequest['password']);

        $user = User::create($userRequest);
        $user->sendEmailVerificationNotification();

        // event(new Registered($user));

        return response()->json([
            'data' => $user,
            'message' => 'Registration successful,please confirm your email'
        ], Response::HTTP_CREATED);
    }
}
