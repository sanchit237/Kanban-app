<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(StoreUserRequest $request){
        // dd($request);
        return "hello";
    }
}
