<?php

use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ProfileController;
use App\Models\Organization;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SurveyController;
//use Illuminate\Support\Facades\Route;

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
    Route::patch('/profile/notifications', [ProfileController::class, 'updateNotifications'])->name('profile.update-notifications');

    Route::post('/organization/store', [OrganizationController::class, 'store'])->name('organization.store');
    Route::patch('/organization/update/{organization}', action: [OrganizationController::class, 'update'])->name('organization.update');
    Route::delete('/organization/delete/{organization}', action: [OrganizationController::class, 'delete'])->name('organization.delete');
    Route::post('/organization/user/{organization}', action: [OrganizationController::class, 'createOrganizationUser'])->name('organization.createUser');
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
    
    // Éditer toutes les questions d’un sondage
    Route::get('/{survey}/questions/edit', [SurveyController::class, 'editQuestions'])
    ->name('surveys.questions.edit_question');

    Route::put('/{survey}/questions', [SurveyController::class, 'updateQuestions'])
        ->name('surveys.questions.update');
});

// Public access with token
Route::get('/survey/{token}', [SurveyController::class, 'showPublicSurvey'])
->name('surveys.public');

require __DIR__.'/auth.php';
