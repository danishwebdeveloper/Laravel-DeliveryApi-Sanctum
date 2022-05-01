<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponser;

    public function index()
    {
        $users = User::all();
        return $this->successResponse($users);
    }

    public function register(Request $request)
    {
        $rules = [
            'name' => 'required|alpha',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ];
        $this->validate($request, $rules);
        $fields = $request->all();
        $fields['password'] = Hash::make($request->password);
        $user = User::create($fields);

        // Create Token
        $token = $user->createToken('simplydeliverytoken')->plainTextToken;

        $reponse = [
            'user' => $user,
            'token' => $token,
        ];

        return $this->successResponse($reponse, Response::HTTP_CREATED);
    }

    /**
     * Obtains and show one user
     * @return Illuminate\Http\Response
     */
    public function show($user)
    {
        $user = User::findOrFail($user);
        return $this->successResponse($user);
    }

    public function login(Request $request)
    {

        $fields = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return $this->errorResponse("Entered wrong Credentials", Response::HTTP_UNAUTHORIZED);
        }

        // Create Token
        $token = $user->createToken('simplydeliverytoken')->plainTextToken;

        $reponse = [
            'user' => $user,
            'token' => $token,
        ];

        return $this->successResponse($reponse, 201);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return $this->successResponse("SuccessFully Logout!");
    }
}