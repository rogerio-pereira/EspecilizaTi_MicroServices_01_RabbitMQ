<?php

use App\Http\Controllers\Api\{
    CategoryController
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function() {
    return response()->json(['message' => 'success']);
});

Route::apiResource('categories', CategoryController::class);