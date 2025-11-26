<?php

use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/organization/store', [OrganizationController::class, 'store'])->name('organization.store');
    Route::patch('/organization/update/{organization}', action: [OrganizationController::class, 'update'])->name('organization.update');
    Route::delete('/organization/delete/{organization}', action: [OrganizationController::class, 'delete'])->name('organization.delete');
    Route::createOrganizationUser('/organization/user/{organization}', action: [OrganizationController::class, 'createOrganizationUser'])->name('organization.createUser');
});

require __DIR__.'/auth.php';
