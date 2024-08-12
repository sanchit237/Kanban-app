<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class TodoController extends Controller
{
    public function createTodo(Request $request){

        DB::beginTransaction();

        try {

            $id = Auth::id();

            $createTodo = Todo::create([
                "uuid" => Str::uuid(),
                "shortcode" => $request->shortcode,
                "title" => $request->title,
                "description" => $request->description,
                "status" => 0,
                "created_by" => $id
            ]);

            DB::commit();

            return response()->json([
                "message" => "The Todo is created successfully",
                "data" => $createTodo
            ], 201);
        }
        catch(Exception $e){

            DB::rollBack();

            return response()->json([
                "message" => "There was an error while creating the todo",
                "code" => $e->getCode(),
                "error" => $e->getMessage(),
                "file" => $e->getFile(),
                "line" => $e->getLine(),
            ], 500);
        }
    }

    public function getTodos(Request $request){

        try {

            $id = Auth::id();
            $status = $request->status;

            $todos = Todo::where('created_by', $id)
            ->where('status', $status)
            ->paginate();

            return response()->json([
                "data" => $todos
            ], 200);

        }
        catch(Exception $e) {
            return response()->json([
                "message" => "There was an error while fetching the todo",
                "code" => $e->getCode(),
                "error" => $e->getMessage(),
                "file" => $e->getFile(),
                "line" => $e->getLine(),
            ], 500);
        }
    }
}
