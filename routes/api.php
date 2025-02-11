<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TodoController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

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
    Route::post('/delete-todo', [TodoController::class, 'deleteTodo']);
    Route::post('/update-todo', [TodoController::class, 'updateTodo']);

    // Email verification
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return response()->json(['message' => 'Email successfully verified.']);
    })->middleware(['signed'])->name('verification.verify');

    // Resend verification email
    Route::post('/email/resend', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return response()->json(['message' => 'Verification email resent.']);
    })->name('verification.resend');

});


