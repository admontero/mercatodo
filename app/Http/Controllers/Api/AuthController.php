<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomerProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:60'],
            'last_name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users'],
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers(),
                'confirmed',
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $profile = CustomerProfile::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
        ]);

        $profile->user()->save($user);

        $user->assignRole('customer');

        $token = $user->createToken('Token')->accessToken;

        return response()->json(['token' => $token], 200);
    }

    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('Token')->accessToken;

            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Credenciales erróneas']);
        }
    }

    public function logout()
    {
        $token = auth()->user()->token();

        $token->revoke();

        return response()->json(['success' => 'Logout Succesfully']);
    }
}
