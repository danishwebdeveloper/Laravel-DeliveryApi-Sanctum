<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // User Register
    public function register(Request $request){

        // Validation while Registration
        $fields = $request->validate([
            'name' => 'required|alpha',                       // Name should be aplhabetic
            'email' => 'required|email|unique:users,email',  // Email should be unique and Valid Email Address
            'password' => 'required|min:6|confirmed',       // Minimum 6 digits(Bases on Requirment)
        ]);

        // Create the User
        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        // Create Token
        $token = $user->createToken('simplydeliverytoken')->plainTextToken;

        // Response
        $reponse = [
            'user' => $user,
            'token' => $token,
        ];

        // Return the response after register with status code of 201
        return response($reponse, 201);
    }

    // User Login and Validations
    public function login(Request $request){
        $fields = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
        ]);

        // Check Email
        $user = User::where('email', $fields['email'])->first();

        // Password Check, if it's wrong display message with status code of 401
        if(!$user || !Hash::check($fields['password'], $user->password)){
            return response([
                'message' => 'Entered wrong Credentials'
            ], 401);
        }

        // Create Token
        $token = $user->createToken('simplydeliverytoken')->plainTextToken;

        // Response
        $reponse = [
            'user' => $user,
            'token' => $token,
        ];

        // Return response after successfull login
        return response($reponse, 201);
    }

    // logout User
    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'Successfully Logged Out!'
        ]);
    }
}
