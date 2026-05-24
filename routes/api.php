<?php

use App\Http\Controllers\Api\AvailabilityController;
use Illuminate\Support\Facades\Route;

Route::get('/availability/{slug}', [AvailabilityController::class, 'show'])
    ->name('api.availability');
