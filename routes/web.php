<?php

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
//
//Route::get('/', function () {
//    return redirect('admin');
//});



use App\Http\Controllers\StudentController;

Route::prefix('api')->group(function (){

//    Route::get('/sts', [StudentController::class, 'index']);      // Get all students
//    Route::get('/sts/{id}', [StudentController::class, 'show']);  // Get a single student by ID
//    Route::post('/sts', [StudentController::class, 'store']);     // Create a new student
//    Route::put('/sts/{id}', [StudentController::class, 'update']); // Update an existing student
//    Route::delete('/sts/{id}', [StudentController::class, 'destroy']); // Delete a student by ID

});
Route::get('/hello/test', function () {
    // Define the path to the SQLite database file
    $filePath = database_path('database.sqlite');

    // Check if the file exists
    if (!file_exists($filePath)) {
        abort(500, 'Database file not found.');
    }

    // Return the file for download with proper headers
    return Response::download($filePath, 'database.sqlite', [
        'Content-Type' => 'application/octet-stream',
    ]);
});
