<?php

use App\Facades\Piwapi;
use App\Http\Controllers\DashboardController;
use App\Http\Services\TxImageDetectService;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::group(['prefix' => 'dashboard', 'middleware' => 'auth'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/duit-notes', [DashboardController::class, 'duitNotes'])->name('dashboard.duit-notes');
    Route::post('/duit-notes', [DashboardController::class, 'storeDuitNote'])->name('dashboard.duit-notes.store');
    Route::put('/duit-notes/{transaction}', [DashboardController::class, 'updateDuitNote'])->name('dashboard.duit-notes.update');
    Route::delete('/duit-notes/{transaction}', [DashboardController::class, 'destroyDuitNote'])->name('dashboard.duit-notes.destroy');


    Route::group(['prefix' => 'category'], function () {
        Route::get('/', [CategoryController::class, 'index'])->name('dashboard.category.index');
        Route::post('/', [CategoryController::class, 'store'])->name('dashboard.category.store');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('dashboard.category.update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('dashboard.category.destroy');
    });
});

require __DIR__ . '/settings.php';
