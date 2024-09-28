<?php

namespace App\Http\Controllers\authentication;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class Login extends Controller
{
    public function login_user(Request $request)
    {
        $credentials = ['username' => $request->input('login_username'), 'password' => $request->input('login_password')];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return response()->json([
                'message' => 'Registration successful'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Authentication failed'
            ], 401);
        }

        return response();
    }
}
