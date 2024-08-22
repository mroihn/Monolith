<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class AuthApiController extends Controller
{
    public function login(Request $request){
        $validator = Validator::make(request()->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);
  
        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' =>'Gagal Login',
                'data'=>null,
            ]);
        }
       
        $credentials = request(['username', 'password']);

        if ((! $token = auth()->attempt($credentials)) || ($request->username !== 'admin')) {
            return response()->json([
                'status' => 'error',
                'message' =>'Gagal Login',
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
                'message' => 'Berhasil Get Self',
                'data' => $data,
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Gagal get self',
            'data' => null,
        ]); 
    }

    public function logout(Request $request)
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

}
