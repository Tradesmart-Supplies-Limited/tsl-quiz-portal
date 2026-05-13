<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\OperationsQuizController;
use App\Http\Controllers\ItQuizController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hr/quiz', [QuizController::class, 'show']);
Route::post('/hr/quiz/submit', [QuizController::class, 'submit'])->name('hr.quiz.submit');
Route::get('/quiz/success', function () {
    return view('quiz.success');
})->name('quiz.success');


Route::get('/operations/quiz', [OperationsQuizController::class, 'show']);
Route::post('/operations/quiz/submit', [OperationsQuizController::class, 'submit'])->name('operations.quiz.submit');
Route::get('/quiz/success', function () {
    return view('quiz.success');
})->name('operations.quiz.success');

Route::get('/it/quiz', [ItQuizController::class, 'show']);
Route::post('/it/quiz/submit', [ItQuizController::class, 'submit'])->name('it.quiz.submit');
Route::get('/quiz/success', function () {
    return view('quiz.success');
})->name('it.quiz.success');