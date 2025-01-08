<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\UserLoginRequest;
use App\Http\Response\ApiResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(UserLoginRequest $request)
    {
        $credentials = $request->validated();
        $user = User::where('email', $credentials['email'])->first();
        $isVerified = $user->hasVerifiedEmail();
        if ($isVerified && Auth::attempt($credentials)) {
            $token = auth()->user()->createToken('login-token')->plainTextToken;

            return response()->json([
                'data' => auth()->user(),
                'token' => $token,
            ]);
        }

        if (! $isVerified) {
            return ApiResponse::error(null, 'Your account is not verfied yet. Please verify in order to login.');
        }

        return ApiResponse::error(null, 'Login failed, please try again with correct credentials.');
    }
}
