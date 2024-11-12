<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SubscriptionPlanController;
use App\Http\Controllers\OpenAIController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(RegisterController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login-api', 'login');
});

///rutas APIFirst
Route::middleware(['auth:sanctum', 'api-auth'])->group(function () {
    //rutas para las task
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::get('/tasks/{task}', [TaskController::class, 'show']);
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
    Route::patch('/tasks/{task}', [TaskController::class, 'changeStatus']);

    //ruta extra para usar OpenAI API
    Route::post('/suggest', [OpenAIController::class, 'suggest']);
    Route::post('/suggest/desc', [OpenAIController::class, 'suggestDescription']);
    
    Route::controller(RegisterController::class)->group(function () {
        Route::get('user-data', 'userData');
    });
    
});

//rutas para la plane y subciptions
Route::middleware('auth:sanctum')->group(function () {
    Route::get('subscription-plans', [SubscriptionPlanController::class, 'index']);
    Route::get('subscription-plans/{id}', [SubscriptionPlanController::class, 'show']);

    //Rutas para las subcripciones del usuario
    // Listar suscripciones del usuario actual
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    // Suscribirse a un plan (maneja tanto PayPal como los planes internos)
    Route::post('/subscriptions/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscriptions.subscribe');
    // Cancelar suscripción (maneja tanto PayPal como los planes internos)
    Route::post('/subscriptions/cancel', [SubscriptionController::class, 'cancelSubscription'])->name('subscriptions.cancel');
    // Cambiar de plan (maneja tanto PayPal como los planes internos)
    Route::post('/subscriptions/change-plan/{newPlanId}', [SubscriptionController::class, 'changePlan'])->name('subscriptions.changePlan');

    Route::post('subscriptions/success', [SubscriptionController::class, 'handleSuccess'])->name('subscription.success');

    //rutas para el payment
    Route::post('/paypal/payment', [PaymentController::class, 'createPayment'])->name('paypal.payment');
    Route::get('/paypal/success', [PaymentController::class, 'success'])->name('paypal.success');
    Route::get('/paypal/cancel', [PaymentController::class, 'cancel'])->name('paypal.cancel');

    Route::get('/payments/history', [PaymentController::class, 'payments'])->name('paypal.payment');
    
});
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('/subscription-plans', [SubscriptionPlanController::class, 'store']);
    Route::put('/subscription-plans/{plan}', [SubscriptionPlanController::class, 'update']);
    Route::delete('/subscription-plans/{plan}', [SubscriptionPlanController::class, 'destroy']);
});

//ruta para checktoken
Route::middleware('api-auth')->get('/protected-route', function () {
    
    return response()->json(['message' => 'You have access to this protected route.'], 200);
});



Route::get('subscriptions/cancel', [SubscriptionController::class, 'handleCancel'])->name('subscription.cancel');



