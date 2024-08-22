<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Validator;


class UserApiController extends Controller
{
/**
 * @OA\Get(
 *     path="/users",
 *     summary="Search users by username",
 *     tags={"User"},
 *     @OA\Parameter(
 *         name="q",
 *         in="query",
 *         required=true,
 *         description="Search query to filter users by username",
 *         @OA\Schema(type="string", example="johndoe")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful search",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="message", type="string", example="Users found"),
 *             @OA\Property(property="data", type="array",
 *                 @OA\Items(type="object",
 *                     @OA\Property(property="id", type="string", example="1"),
 *                     @OA\Property(property="username", type="string", example="johndoe"),
 *                     @OA\Property(property="email", type="string", example="johndoe@example.com"),
 *                     @OA\Property(property="balance", type="integer", example=100)
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No users found",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="No users found"),
 *             @OA\Property(property="data", type="null", example=null)
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
    public function getUserByUsername(Request $request){
        if ((!$request->has('q'))||$request->query('q')=="") {
            $users = User::all();
            return response()->json([
                'status' => 'success',
                'message' => 'Users found',
                'data' => UserResource::collection($users),
            ]); 
        }

        $username = $request->query('q');
        $user = User::where('username', $username)->first();
        if ($user) {
            return response()->json([
                'status' => 'success',
                'message' => 'Users found',
                'data' => [new UserResource($user)],
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No users found',
            'data' => null,
        ], 404);
    }

/**
 * @OA\Get(
 *     path="/users/{id}",
 *     summary="Get user details by ID",
 *     tags={"User"},
 *     @OA\Response(
 *         response=200,
 *         description="Successfully retrieved user details",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="message", type="string", example="User details retrieved successfully"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="id", type="string", example="1"),
 *                 @OA\Property(property="username", type="string", example="johndoe"),
 *                 @OA\Property(property="email", type="string", example="johndoe@example.com"),
 *                 @OA\Property(property="balance", type="integer", example=100)
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
 *     @OA\Response(
 *         response=404,
 *         description="User not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="User not found"),
 *             @OA\Property(property="data", type="null", example=null)
 *         )
 *     ),
 *     security={{"bearerAuth": {}}}
 * )
 */

    public function getUserById($id){
        if ($user = User::find($id)) {
            return response()->json([
                'status' => 'success',
                'message' => 'User details retrieved successfully',
                'data' => new UserResource($user),
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'User not found',
            'data' => null,
        ], 404);
    }

/**
 * @OA\Post(
 *     path="/users/{id}/balance",
 *     summary="Update user's balance by incrementing it",
 *     tags={"User"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the user whose balance will be updated",
 *         @OA\Schema(type="string", example="1")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="increment", type="integer", example=50, description="Amount to increment the user's balance by")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successfully updated the user's balance",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="message", type="string", example="Balance updated successfully"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="id", type="string", example="1"),
 *                 @OA\Property(property="username", type="string", example="johndoe"),
 *                 @OA\Property(property="email", type="string", example="johndoe@example.com"),
 *                 @OA\Property(property="balance", type="integer", example=150)
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid request data",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Invalid data provided"),
 *             @OA\Property(property="data", type="null", example=null)
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
 *     @OA\Response(
 *         response=404,
 *         description="User not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="User not found"),
 *             @OA\Property(property="data", type="null", example=null)
 *         )
 *     ),
 *     security={{"bearerAuth": {}}}
 * )
 */

    public function updateBalance(Request $request,$id){
        $validator = Validator::make(request()->all(),[
            'increment' => 'required|integer',
        ]);
        
        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid request data',
                'data' => null,
            ], 400);
        }

        if ($user = User::find($id)) {
            $user->balance += $request->input('increment');
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Balance updated successfully',
                'data' => new UserResource($user),
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'User not found',
            'data' => null,
        ], 404);
    }

    /**
 * @OA\Delete(
 *     path="/users/{id}",
 *     summary="Delete a user by ID",
 *     tags={"User"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the user to delete",
 *         @OA\Schema(type="string", example="1")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successfully deleted the user",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="message", type="string", example="User deleted successfully"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="id", type="string", example="1"),
 *                 @OA\Property(property="username", type="string", example="johndoe"),
 *                 @OA\Property(property="email", type="string", example="johndoe@example.com"),
 *                 @OA\Property(property="balance", type="integer", example=0)
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
 *     @OA\Response(
 *         response=404,
 *         description="User not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="User not found"),
 *             @OA\Property(property="data", type="null", example=null)
 *         )
 *     ),
 *     security={{"bearerAuth": {}}}
 * )
 */

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
