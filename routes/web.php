<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/quiz', [QuizController::class, 'show']);
Route::post('/quiz/submit', [QuizController::class, 'submit']);
Route::get('/quiz/success', function () {
    return view('quiz.success');
})->name('quiz.success');