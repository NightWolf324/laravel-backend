<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\BaseController;

class AuthController extends BaseController
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password'=> 'required|min:5'
        ]);

        if(!Auth::attempt($request->only('email','password')))
        {
            return $this->sendError('Email or Password incorrect', [], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->sendResponse([
            'name' => $user->name,
            'email' => $user->email,
            'accessToken' => $token
        ], 'Login Success');
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        $user->tokens()->delete();

        return response()->json(['message' => 'Logout Success'], 200);
    }
}
