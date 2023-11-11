<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegistrationRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserRegister extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UserRegistrationRequest $request)
    {
        $user = User::create($request->validated());
        return response()->json(['message' => 'User registered successfully.'],201);
    }
}
