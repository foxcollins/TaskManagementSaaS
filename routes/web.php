<?php

use Illuminate\Support\Facades\Route;

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
});
