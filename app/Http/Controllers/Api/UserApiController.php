<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Validator;

class UserApiController extends Controller
{
    public function getUserByUsername(Request $request){
        if ((!$request->has('q'))||$request->query('q')=="") {
            $users = User::all();
            return response()->json([
                'status' => 'success',
                'message' => 'user found',
                'data' => UserResource::collection($users),
            ]); 
        }

        $username = $request->query('q');
        $user = User::where('username', $username)->first();
        if ($user) {
            return response()->json([
                'status' => 'success',
                'message' => 'user found',
                'data' => [new UserResource($user)],
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'User not found',
            'data' => null,
        ], 404);
    }

    public function getUserById($id){
        if ($user = User::find($id)) {
            return response()->json([
                'status' => 'success',
                'message' => 'user found',
                'data' => new UserResource($user),
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'User not found',
            'data' => null,
        ], 404);
    }

    public function updateBalance(Request $request,$id){
        $validator = Validator::make(request()->all(),[
            'increment' => 'required|integer',
        ]);
        
        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => 'gagal update balance',
                'data' => null,
            ]);
        }

        if ($user = User::find($id)) {
            $user->balance += $request->input('increment');
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'user found',
                'data' => new UserResource($user),
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'User not found',
            'data' => null,
        ], 404);
    }

    public function delete($id){
        if ($user = User::find($id)) {
            $deletedUser = new UserResource($user);
            $user->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'User deleted successfully',
                'data' => new UserResource($deletedUser),
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'User not found',
            'data' => null,
        ], 404);
    }
}
