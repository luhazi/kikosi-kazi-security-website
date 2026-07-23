<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\AboutController;
use App\Http\Controllers\Public\ServiceController;
use App\Http\Controllers\Public\CareersController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\GalleryController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Fallback dashboard route — used by Breeze's guest middleware redirect
Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user && $user->hasAnyRole(['super_admin', 'hr_manager', 'recruitment_officer', 'content_editor'])) {
        return redirect()->route('admin.dashboard');
    }
    if ($user && $user->hasRole('candidate')) {
        return redirect()->route('candidate.dashboard');
    }
    return redirect()->route('home');
})->middleware('auth')->name('dashboard');

Route::get('/about/{section?}', [AboutController::class, 'index'])->name('about')
    ->whereIn('section', ['story', 'mission', 'partner']);

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{category}', [ServiceController::class, 'show'])
    ->name('services.show')
    ->where('category', 'security|hr|insurance|cleaning');

Route::get('/industries', fn () => view('public.industries'))->name('industries');

Route::get('/careers', [CareersController::class, 'index'])->name('careers.index');
Route::get('/careers/{job}', [CareersController::class, 'show'])->name('careers.show');

Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');

Route::get('/clients', function () {
    return view('public.clients', [
        'clients' => \App\Models\Client::active()->orderBy('sort_order')->orderBy('name')->get(),
    ]);
})->name('clients');

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

require __DIR__ . '/auth.php';
require __DIR__ . '/candidate.php';
require __DIR__ . '/admin.php';
