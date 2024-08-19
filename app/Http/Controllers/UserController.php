<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function self(){
        $user = auth()->user();
        $data = [
            'username' => $user['username'],
            'token' => request()->bearerToken(),
        ];
        return response()->json([
            'status' => 'success',
            'message' =>'self',
            'data'=>$data,
        ]);
    }    
}
