<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Models\User;
use App\Notifications\UserLoginNotification;
use Auth;
use Exception;
use Illuminate\Database\QueryException;
use Log;

class UserLogin extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UserLoginRequest $request)
    {
        try{
            if(!Auth::attempt($request->validated())){
                return response()->json(['message' => 'Invalid credentials.'], 401);
            }
            $user = Auth::user();
            if(!$user instanceof User){
                //user not found but authenticated
                Log::error('User not found but authenticated', ['email'=>$request->only('email')]);
                return response()->json(['message' => 'Something went wrong.'], 500);
            }
            $user->tokens()->delete();
            $token = $user->createToken('auth_token')->plainTextToken;
            $user->notify(new UserLoginNotification());
            return response()->json(['message'=>'User logged in successfully.','token'=>$token]);
        }catch (Exception|QueryException $e){
            Log::error($e->getMessage());
            return response()->json(['message' => 'Something went wrong.'], 500);
        }finally{
            Log::info('User login attempt', ['email'=>$request->only('email'),'status'=>Auth::check()?'success':'failed']);
        }
    }
}
