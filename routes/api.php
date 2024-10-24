<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SubscriptionPlanController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(RegisterController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

///rutas APIFirst
Route::middleware(['auth:sanctum', 'api-auth'])->group(function () {
    //rutas para las task
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::get('/tasks/{task}', [TaskController::class, 'show']);
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
   
    
});

//rutas para la plane y subciptions
Route::middleware('auth:sanctum')->group(function () {
    Route::get('subscription-plans', [SubscriptionPlanController::class, 'index']);
    Route::get('subscription-plans/{id}', [SubscriptionPlanController::class, 'show']);
});
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('/subscription-plans', [SubscriptionPlanController::class, 'store']);
    Route::put('/subscription-plans/{plan}', [SubscriptionPlanController::class, 'update']);
    Route::delete('/subscription-plans/{plan}', [SubscriptionPlanController::class, 'destroy']);
});

//rutas para las subcripciones dle usuario
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/subscriptions', [SubscriptionController::class, 'index']);
    Route::post('/subscriptions', [SubscriptionController::class, 'subscribe']);
});


