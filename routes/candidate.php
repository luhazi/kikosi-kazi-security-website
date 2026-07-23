<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Candidate\DashboardController;
use App\Http\Controllers\Candidate\ProfileController;
use App\Http\Controllers\Candidate\ApplicationController;
use App\Http\Controllers\Candidate\DocumentController;
use App\Http\Controllers\Candidate\CvController;
use App\Http\Controllers\Candidate\CvParserController;

Route::middleware(['auth', 'role:candidate'])->prefix('candidate')->name('candidate.')->group(function () {

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('profile/education', [ProfileController::class, 'storeEducation'])->name('profile.education.store');
    Route::delete('profile/education/{id}', [ProfileController::class, 'destroyEducation'])->name('profile.education.destroy');
    Route::post('profile/experience', [ProfileController::class, 'storeExperience'])->name('profile.experience.store');
    Route::delete('profile/experience/{id}', [ProfileController::class, 'destroyExperience'])->name('profile.experience.destroy');

    Route::get('documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::post('documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('documents/{document}/preview', [DocumentController::class, 'preview'])->name('documents.preview');
    Route::get('documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::delete('documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

    Route::get('applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::post('applications', [ApplicationController::class, 'store'])->name('applications.store');
    Route::get('applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::delete('applications/{application}', [ApplicationController::class, 'destroy'])->name('applications.destroy');

    Route::get('cv', [CvController::class, 'show'])->name('cv.show');
    Route::post('cv/parse',  [CvParserController::class, 'parse'])->name('cv.parse');
    Route::post('cv/import', [CvParserController::class, 'import'])->name('cv.import');
});
