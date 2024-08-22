<?php
  
namespace App\Http\Controllers;
  
use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
  
  
class AuthController extends Controller
{

    public function register() {
        $validator = Validator::make(request()->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
        ]);
  
        if($validator->fails()){
            $errors = $validator->errors()->all();
            $errorString = implode(' ', $errors);
            return redirect('/register')->withErrors(['register' => $errorString]);
        }
  
        $user = new User;
        $user->first_name = request()->first_name;
        $user->last_name = request()->last_name;
        $user->username = request()->username;
        $user->email = request()->email;
        $user->password = Hash::make(request()->password);
        $user->save();
        
        return redirect('/register')->with('success', 'account successfully created');
    }
  
    public function login(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);
  
        if($validator->fails()){
            return redirect('/login')->withErrors(['login' => 'Incorrect username or password']);
        }

        $credentials = request(['username', 'password']);
  
        if (! $token = JWTAuth::attempt($credentials)) {
            $credentials = request(['username', 'password']);
            $credentials['email'] = $credentials['username'];
            unset($credentials['username']);
            if (! $token = JWTAuth::attempt($credentials)) {
                return redirect('/login')->withErrors(['login' => 'Incorrect username or password']);
            }
        }
        
        return redirect('/')-> withCookie(cookie('token', $token));
    }
  
    public function logout()
    {
        $token = JWTAuth::getToken();
        JWTAuth::invalidate($token);
  
        return redirect('/');
    }
  
}