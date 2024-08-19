<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class AuthApiController extends Controller
{
    public function login(Request $request){
       
        $credentials = request(['username', 'password']);

        if ((! $token = auth()->attempt($credentials)) || ($request->username !== 'admin')) {
            return response()->json([
                'status' => 'error',
                'message' =>'The provided credentials are incorrect.',
                'data'=>null,
            ]);
        }
        
        $user = User::where('username',$request->username)->first();

        $data = [
            'username' => $user->username,
            'token' => $token,
        ];

        return response()->json([
            'status' => 'success',
            'message' =>'login success',
            'data'=>$data,
        ]);
    }

    public function self(Request $request)
    {
        if (auth()->check()) {
            $user = auth()->user();
            $token = $request->bearerToken();

            $data = [
                'username' => $user->username,
                'token' => $token,
            ];

            return response()->json([
                'status' => 'success',
                'message' => 'User information retrieved successfully.',
                'data' => $data,
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'User is unauthenticated.',
            'data' => null,
        ]); 
    }

    public function logout(Request $request)
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

}
