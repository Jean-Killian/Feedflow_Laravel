<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SurveyController;
use Illuminate\Support\Facades\Route;

// Page d'accueil
Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes profil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes surveys
Route::middleware('auth')->prefix('surveys')->group(function () {

    // CRUD Sondages
    Route::get('/', [SurveyController::class, 'index'])->name('surveys.index');
    Route::get('/create', [SurveyController::class, 'create'])->name('surveys.create');
    Route::post('/', [SurveyController::class, 'store'])->name('surveys.store');
    Route::get('/{survey}/edit', [SurveyController::class, 'edit'])->name('surveys.edit');
    Route::put('/{survey}', [SurveyController::class, 'update'])->name('surveys.update');
    Route::delete('/{survey}', [SurveyController::class, 'destroy'])->name('surveys.destroy');
    Route::get('/{survey}', [SurveyController::class, 'show'])->name('surveys.show');

    // Gestion des questions
    Route::get('/{survey}/questions/create', [SurveyController::class, 'addQuestionForm'])
        ->name('surveys.add_question');
    Route::post('/{survey}/questions', [SurveyController::class, 'addQuestion'])
        ->name('surveys.store_question');

    // Participer / répondre au sondage
    Route::get('/{survey}/take', [SurveyController::class, 'takeSurvey'])
        ->name('surveys.take');
    Route::post('/{survey}/submit', [SurveyController::class, 'submitSurvey'])
        ->name('surveys.submit');
});

require __DIR__.'/auth.php';
