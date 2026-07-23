<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\ApplicantController;
use App\Http\Controllers\Admin\CmsController;

Route::middleware(['auth', 'role:super_admin|hr_manager|recruitment_officer|content_editor'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Bare /admin → send to the dashboard (auth/role middleware still applies)
        Route::redirect('/', '/admin/dashboard')->name('home');

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('jobs', JobController::class);

        Route::get('applicants', [ApplicantController::class, 'index'])->name('applicants.index');
        Route::get('applicants/{candidateProfile}', [ApplicantController::class, 'show'])->name('applicants.show');
        Route::post('applicants/{application}/status', [ApplicantController::class, 'updateStatus'])->name('applicants.status');
        Route::get('documents/{document}/download', [ApplicantController::class, 'downloadDocument'])->name('documents.download');

        Route::get('cms', [CmsController::class, 'index'])->name('cms.index');
        Route::post('cms/services', [CmsController::class, 'storeService'])->name('cms.services.store');
        Route::put('cms/services/{service}', [CmsController::class, 'updateService'])->name('cms.services.update');
        Route::delete('cms/services/{service}', [CmsController::class, 'destroyService'])->name('cms.services.destroy');
        Route::post('cms/testimonials', [CmsController::class, 'storeTestimonial'])->name('cms.testimonials.store');
        Route::put('cms/testimonials/{testimonial}', [CmsController::class, 'updateTestimonial'])->name('cms.testimonials.update');
        Route::delete('cms/testimonials/{testimonial}', [CmsController::class, 'destroyTestimonial'])->name('cms.testimonials.destroy');

        Route::post('cms/team', [CmsController::class, 'storeTeamMember'])->name('cms.team.store');
        Route::post('cms/team/{teamMember}', [CmsController::class, 'updateTeamMember'])->name('cms.team.update');
        Route::delete('cms/team/{teamMember}', [CmsController::class, 'destroyTeamMember'])->name('cms.team.destroy');

        Route::post('cms/gallery', [CmsController::class, 'storeGalleryPhoto'])->name('cms.gallery.store');
        Route::post('cms/gallery/{galleryPhoto}', [CmsController::class, 'updateGalleryPhoto'])->name('cms.gallery.update');
        Route::delete('cms/gallery/{galleryPhoto}', [CmsController::class, 'destroyGalleryPhoto'])->name('cms.gallery.destroy');

        Route::post('cms/clients', [CmsController::class, 'storeClient'])->name('cms.clients.store');
        Route::post('cms/clients/{client}', [CmsController::class, 'updateClient'])->name('cms.clients.update');
        Route::delete('cms/clients/{client}', [CmsController::class, 'destroyClient'])->name('cms.clients.destroy');
    });
