<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\LoginUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function store(StoreUserRequest $request){
        try {
            DB::beginTransaction();

            $createUser = User::create([
                "uuid" => Str::uuid(),
                "first_name" => $request->firstName,
                "last_name" => $request->lastName,
                "name" => $request->firstName. " " .$request->lastName,
                "username" => $request->userName,
                "email" => $request->email,
                "password" => Hash::make($request->password),
            ]);

            // Send the email verification notification
            $createUser->sendEmailVerificationNotification();

            DB::commit();

            return response()->json([
                "message" => "The user is created successfully",
                "data" => $createUser
            ], 201);
        }
        catch(Exception $e){
            DB::rollBack();

            return response()->json([
                "message" => "There was an error while creating the user",
                "code" => $e->getCode(),
                "error" => $e->getMessage(),
                "file" => $e->getFile(),
                "line" => $e->getLine(),
            ], 500);
        }
    }

    public function login(LoginUserRequest $request){
        try {
            $loginUser = User::where("email", $request->email)->first();

            if ($loginUser && Hash::check($request->password, $loginUser->password)) {
                $token = $loginUser->createToken('user-token')->plainTextToken;
            } else {
                throw new Exception("Invalid credentials", 401);
            }

            return response()->json([
                "message" => "The user is logged in successfully",
                "token" => $token,
                "data" => $loginUser
            ], 201);
        }
        catch(Exception $e){
            return response()->json([
                "message" => "There was an error while logging the user",
                "code" => $e->getCode(),
                "error" => $e->getMessage(),
                "file" => $e->getFile(),
                "line" => $e->getLine(),
            ], 500);
        }
    }

}
