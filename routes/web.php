<?php

use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ProfileController;
use App\Models\Organization;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SurveyController;
use Illuminate\Support\Facades\Route;

// Page d'accueil
Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', function () {
    $organizations = Organization::all();
    return view('dashboard', compact('organizations'));
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/organization/store', [OrganizationController::class, 'store'])->name('organization.store');
    Route::patch('/organization/update/{organization}', action: [OrganizationController::class, 'store'])->name('organization.update');
    Route::delete('/organization/delete/{organization}', action: [OrganizationController::class, 'delete'])->name('organization.delete');
});

Route::middleware('auth')->group(function () {
    Route::get('/surveys', [SurveyController::class, 'index'])->name('surveys.index');
    Route::get('/surveys/create', [SurveyController::class, 'create'])->name('surveys.create');
    Route::post('/surveys', [SurveyController::class, 'store'])->name('surveys.store');
    Route::get('/surveys/{survey}', [SurveyController::class, 'show'])->name('surveys.show');
    Route::get('/surveys/{survey}/edit', [SurveyController::class, 'edit'])->name('surveys.edit');
    Route::put('/surveys/{survey}', [SurveyController::class, 'update'])->name('surveys.update');
    Route::delete('/surveys/{survey}', [SurveyController::class, 'destroy'])->name('surveys.destroy');
});

Route::post('/surveys/{survey}/questions', [SurveyController::class, 'addQuestion'])->name('surveys.questions.store');

Route::middleware('auth')->group(function () {
    Route::resource('surveys', SurveyController::class);
});

Route::get('/surveys/create', [SurveyController::class, 'create'])->name('surveys.create');
Route::post('/surveys', [SurveyController::class, 'store'])->name('surveys.store');

require __DIR__.'/auth.php';
