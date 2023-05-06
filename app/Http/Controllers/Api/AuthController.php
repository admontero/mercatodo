<?php

namespace App\Http\Controllers\Api;

use App\DataTransferObjects\CustomerProfileDTO;
use App\DataTransferObjects\UserDTO;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function __construct(
        protected UserService $userService,
    ) {}

    public function register(Request $request): JsonResponse
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

        $user = $this->userService->createUser(
            UserDTO::fromHttpRequest($request)
        );

        $this->userService->createCustomerProfile(
            CustomerProfileDTO::fromHttpRequest($request),
            $user
        );

        $this->userService->assignRole($user, 'customer');

        $token = $user->createToken('Token')->accessToken;

        return response()->json(['token' => $token], 200);
    }

    public function login(Request $request): JsonResponse
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (auth()->attempt($credentials)) {
            /** @var User $user */
            $user = auth()->user();

            $token = $user->createToken('Token')->accessToken;

            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Credenciales erróneas']);
        }
    }

    public function logout(): JsonResponse
    {
        auth()->user()?->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return response()->json(['success' => 'Logout Succesfully']);
    }
}
