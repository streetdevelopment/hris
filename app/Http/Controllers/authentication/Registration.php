<?php

namespace App\Http\Controllers\authentication;

use App\Models\User;
use App\Models\company\Company;
use App\Models\profiling\CI;
use App\Models\profiling\PI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Registration extends Controller
{
    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'co_name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'province' => 'required',
            'postal_code' => 'required',
            'industry' => 'required',
            'tin' => 'required',
            'date_of_birth' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'contact_number' => 'required',
            'email_address' => 'required',
            'sex' => 'required',
            'permanent_address' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'messages' => $validator->errors()->toArray()
            ], 422);
        } else {
            $company = Company::create([
                'co_name' => $request->input('co_name'),
                'address' => $request->input('address'),
                'city' => $request->input('city'),
                'province' => $request->input('province'),
                'postal_code' => $request->input('postal_code'),
                'industry' => $request->input('industry'),
                'tin' => $request->input('tin')
            ]);
            $user = User::create([
                'username' => strtolower(str_replace(' ', '', $request->input('first_name'))) . '.' . strtolower(str_replace(' ', '', $request->input('last_name'))),
                'email' => $request->input('email_address'),
                'password' => $request->input('password'),
                'co_id' => $company->co_id,
                'role' => 'admin'
            ]);
            $pi = PI::create([
                'user_id' => $user->id,
                'first_name' => $request->input('first_name'),
                'middle_name' => $request->input('middle_name'),
                'last_name' => $request->input('last_name'),
                'sex' => $request->input('sex'),
                'date_of_birth' => $request->input('date_of_birth'),
                'nationality' => $request->input('nationality'),
            ]);
            $ci = CI::create([
                'user_id' => $user->id,
                'contact_number' => $request->input('contact_number'),
                'email_address' => $request->input('email_address'),
                'permanent_address' => $request->input('permanent_address')
            ]);
            $credentials = ['email' => $request->input('email_address'), 'password' => $request->input('password')];

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                return response()->json([
                    'message' => 'Registration successful',
                    'user' => $user,
                    'url' => route('dashboard')
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Authentication failed'
                ], 401);
            }

            return response();
        }
    }
    public function verification($user_id)
    {
        return view('company_setup.verification');
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('welcome');
    }
}
