<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriptionController;
use App\Livewire\SubscriptionSuccess;
use App\Livewire\SubscriptionCompleted;
use App\Livewire\Logout;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware([
    'authenticated'
])->group(function () {
    Route::get('/home', function () {
        return view('front.home');
    })->name('home');
    Route::get('/plans', function () {
        return view('front.plans');
    })->name('plans');
    Route::get('/plans/{plan}', function () {
        return view('front.plan-show');
    })->name('plans.show');
    Route::get('/about-us', function () {
        return view('front.about');
    })->name('about-us');
    Route::get('/payments', function () {
        return view('front.payments');
    })->name('payments');
});

Route::get('/subscriptions/success', SubscriptionSuccess::class)->name('subscription.success');
Route::get('subscriptions/cancel', [SubscriptionController::class, 'handleCancel'])->name('subscription.cancel');
Route::get('/subscriptions/completed', SubscriptionCompleted::class)->name('subscription.completed');

Route::get('logout', Logout::class)->name('logout');
