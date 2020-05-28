<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{

    public function register(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6'
        ];

        $validator = Validator::make(request()->json()->all(), $rules);

        if($validator->fails())
        {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $user = User::create([
            'name' => $request->json()->get('name'),
            'email' => $request->json()->get('email'),
            'password' => Hash::make($request->json()->get('password'))
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'success' => true,
            'user' => $user,
            'token' => $token
        ], 200);
    }

    public function login(Request $request)
    {
        $creds = $request->json()->all();

        if(! $token = JWTAuth::attempt($creds))
        {
            return response()->json([
                'error' => 'Unauthorized',
                'success' => false
            ], 401);
        }

        return response()->json([
            'token' => $token,
            'success' => true
        ], 200);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function getAuthentificatedUser()
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'user' => $user,
            'token' => $user->getJWTIdentifier()
        ]);
    }
}
