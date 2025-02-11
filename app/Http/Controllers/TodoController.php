<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\DeleteTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Http\Resources\TodoResource;
use App\Jobs\Testing;


class TodoController extends Controller
{
    /**
     * Create a new Todo item.
     *
     * @param StoreTodoRequest $request Validated request data for creating a todo.
     * @return \Illuminate\Http\JsonResponse
     */
    public function createTodo(StoreTodoRequest $request)
    {
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


    /**
     * Retrieve a paginated list of Todo items based on their status.
     *
     * @param Request $request Contains the status filter for todos.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTodos(Request $request)
    {
        try {
            $id = Auth::id();
            $status = $request->status;

            $todos = Todo::where('created_by', $id)
            ->status($status)
            ->paginate();

            Testing::dispatch();

            return response()->json([
                "data" => TodoResource::collection($todos)->response()->getData(true),
            ], 200);

        }
        catch(Exception $e){
            return response()->json([
                "message" => "There was an error while fetching the todo",
                "code" => $e->getCode(),
                "error" => $e->getMessage(),
                "file" => $e->getFile(),
                "line" => $e->getLine(),
            ], 500);
        }
    }


    /**
     * Delete a Todo item by its UUID.
     *
     * @param Request $request Contains the UUID of the todo to delete.
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteTodo(DeleteTodoRequest $request)
    {
        DB::beginTransaction();

        try {
            $uuid = $request->uuid;

            Todo::where('uuid', $uuid)->delete();

            DB::commit();

            return response()->json([
                "message" => "The Todo is deleted successfully",
            ], 200);
        }
        catch(Exception $e) {
            DB::rollBack();

            return response()->json([
                "message" => "There was an error while deleting the todo",
                "code" => $e->getCode(),
                "error" => $e->getMessage(),
                "file" => $e->getFile(),
                "line" => $e->getLine(),
            ], 500);
        }
    }


    /**
     * Update an existing Todo item by its UUID.
     *
     * @param Request $request Contains updated data for the todo.
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTodo(UpdateTodoRequest $request)
    {
        DB::beginTransaction();

        try{
            $id = Auth::id();
            $uuid = $request->uuid;
            $shortcode = $request->shortcode;
            $title = $request->title;
            $description = $request->description;
            $status = $request->status;
            $created_by = $id;

            $todo = Todo::where('created_by', $id)
            ->where('uuid', $uuid)
            ->first();

            if(!$todo){
                return response()->json([
                    "message" => "Todo not found",
                ], 404);
            }

            $todo->update([
                "shortcode" => $shortcode,
                "title" => $title,
                "description" => $description,
                "status" => $status,
            ]);

            DB::commit();

            return response()->json([
                "message" => "The Todo is updated successfully",
            ], 200);
        }
        catch(Exception $e){
            DB::rollback();

            return response()->json([
                "message" => "There was an error while updating the todo",
                "code" => $e->getCode(),
                "error" => $e->getMessage(),
                "file" => $e->getFile(),
                "line" => $e->getLine(),
            ], 500);
        }
    }
}
