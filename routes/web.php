<?php

use App\Facades\Piwapi;
use App\Http\Services\TxImageDetectService;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/settings.php';


Route::get('/send-text', function () {
    $path = public_path('doc.pdf');
    return Piwapi::sendDocumentFile('6287857580910', $path, 'ini doc mu');
});

Route::get('/test-struk', function () {
    $image = public_path('struk.png');
    return (new TxImageDetectService)->detect($image);
});
