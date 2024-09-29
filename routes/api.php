<?php

use App\Http\Controllers\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

    Route::get('/sts', [StudentController::class, 'index']);      // Get all students
    Route::get('/sts/{id}', [StudentController::class, 'show']);  // Get a single student by ID
    Route::post('/stss', [StudentController::class, 'store']);     // Create a new student
    Route::put('/sts/{id}', [StudentController::class, 'update']); // Update an existing student
    Route::delete('/sts/{id}', [StudentController::class, 'destroy']); // Delete a student by ID
