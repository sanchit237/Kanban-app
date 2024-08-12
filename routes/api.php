<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TodoController;


/*k
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/create-user', [UserController::class, 'store']);

Route::post('/login-user', [UserController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/create-todo', [TodoController::class, 'createTodo']);
    Route::post('/todos', [TodoController::class, 'getTodos']);
});


