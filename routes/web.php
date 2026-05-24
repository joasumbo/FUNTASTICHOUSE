<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Public site routes — placeholders until TASK-04 wires up the controllers
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('welcome'))->name('home');
Route::get('/porque-nos',          fn () => view('welcome'))->name('porque-nos');
Route::get('/galeria',             fn () => view('welcome'))->name('galeria');
Route::get('/o-que-fazer',         fn () => view('welcome'))->name('o-que-fazer');
Route::get('/experiencia/{slug}',  fn () => view('welcome'))->name('experiencia.show');
Route::get('/reservas',            fn () => view('welcome'))->name('reservas');
Route::get('/contactos',           fn () => view('welcome'))->name('contactos');

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
