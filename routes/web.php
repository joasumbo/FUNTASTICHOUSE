<?php

use App\Http\Controllers\ContactosController;
use App\Http\Controllers\ExperienciaController;
use App\Http\Controllers\GaleriaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OQueFazerController;
use App\Http\Controllers\PorqueNosController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservasController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Public site routes
|--------------------------------------------------------------------------
*/
Route::get('/',                   [HomeController::class,       'index'])->name('home');
Route::get('/porque-nos',         [PorqueNosController::class,  'index'])->name('porque-nos');
Route::get('/galeria',            [GaleriaController::class,    'index'])->name('galeria');
Route::get('/o-que-fazer',        [OQueFazerController::class,  'index'])->name('o-que-fazer');
Route::get('/experiencia/{slug}', [ExperienciaController::class,'show']) ->name('experiencia.show');
Route::get('/reservas',           [ReservasController::class,   'index'])->name('reservas');
Route::get('/contactos',          [ContactosController::class,  'index'])->name('contactos');

// Locale switcher
Route::get('/lang/{locale}', function (string $locale) {
    if (in_array($locale, ['pt', 'en'])) {
        Session::put('locale', $locale);
        App::setLocale($locale);
    }
    return redirect()->back();
})->name('locale.switch');

/*
|--------------------------------------------------------------------------
| Admin / Auth
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', fn () => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
