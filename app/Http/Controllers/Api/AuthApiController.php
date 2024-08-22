<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class AuthApiController extends Controller
{
/**
 * @OA\Post(
 *     path="/login",
 *     summary="Login a user",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="username", type="string", example="johndoe", description="Username of the user"),
 *             @OA\Property(property="password", type="string", example="password123", description="Password of the user")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successfully logged in",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="message", type="string", example="Login successful"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="username", type="string", example="johndoe"),
 *                 @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Invalid credentials",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Invalid username or password"),
 *             @OA\Property(property="data", type="null", example=null)
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad Request",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Missing username or password"),
 *             @OA\Property(property="data", type="null", example=null)
 *         )
 *     )
 * )
 */
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
            ],400);
        }
       
        $credentials = request(['username', 'password']);

        if ((! $token = auth()->attempt($credentials)) || ($request->username !== 'admin')) {
            return response()->json([
                'status' => 'error',
                'message' =>'Gagal Login',
                'data'=>null,
            ],401);
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

/**
 * @OA\Get(
 *     path="/self",
 *     summary="Get the current authenticated user's details",
 *     tags={"Authentication"},
 *     @OA\Response(
 *         response=200,
 *         description="Successfully retrieved user details",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="message", type="string", example="Successfully retrieved user details"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="username", type="string", example="johndoe"),
 *                 @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Unauthorized"),
 *             @OA\Property(property="data", type="null", example=null)
 *         )
 *     ),
 *     security={{"bearerAuth": {}}}
 * )
 */

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
        ],401); 
    }

    public function logout(Request $request)
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

}
