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
 
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */

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
        
        // return redirect('/login');
        return redirect('/register')->with('success', 'account successfully created');
        // return response()->json($user, 201);
    }
  
  
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

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
  
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $token = JWTAuth::getToken();
        JWTAuth::invalidate($token);
  
        return redirect('/');
    }
  
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
  
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}