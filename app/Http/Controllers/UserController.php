<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(StoreUserRequest $request){
        $createUser = User::create([
            "first_name" => $request->firstName,
            "last_name" => $request->lastName,
            "name" => $request->firstName. " " .$request->lastName,
            "username" => $request->userName,
            "email" => $request->email,
            "password" => Hash::make($request->password),
        ]);

        if ($createUser) {
            return response()->json([
                "message" => "The user is created successfully",
                "data" => $createUser
            ], 201);
        }
        else {
            return response()->json([
                "message" => "There was an error while creating the user",
            ], 422);
        }
    }
}
