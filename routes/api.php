<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {

    Route::middleware('auth:sanctum')->group(function () {});

    Route::group(['prefix' => 'student'], function () {
        Route::get('/getallstudents', [StudentController::class, 'getAllStudents'])->name('get-allstudents');
        Route::get('/getsinglestudent', [StudentController::class, 'getSingleStudent'])->name('get-singlestudent');
        Route::post('/addstudent', [StudentController::class, 'addStudents'])->name('add-students');
        Route::post('/editstudent', [StudentController::class, 'editStudents'])->name('edit-students');
        Route::post('/deletestudent', [StudentController::class, 'softDeleteStudent'])->name('delete-students');
    });
});
