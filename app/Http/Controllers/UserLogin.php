<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use Auth;
use Illuminate\Http\Request;

class UserLogin extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UserLoginRequest $request)
    {
        if(!Auth::attempt($request->validated())){
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }
        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['message'=>'User logged in successfully.','token'=>$token]);
    }
}
