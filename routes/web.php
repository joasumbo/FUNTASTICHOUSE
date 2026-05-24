<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PerfilController as AdminPerfilController;
use App\Http\Controllers\Admin\ReservaController as AdminReservaController;
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
Route::post('/reservas',          [ReservasController::class,   'store'])->name('reservas.store');
Route::get('/reservas/sucesso',   [ReservasController::class,   'sucesso'])->name('reservas.sucesso');
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
| Admin Panel
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login',  [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout',[AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/',          fn () => redirect()->route('admin.dashboard'));
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Perfil
        Route::get('/perfil',              [AdminPerfilController::class, 'index'])->name('perfil');
        Route::post('/perfil',             [AdminPerfilController::class, 'update'])->name('perfil.update');
        Route::post('/perfil/password',    [AdminPerfilController::class, 'updatePassword'])->name('perfil.password');

        // Reserva detail
        Route::get('/reservas/{reservation}', [AdminReservaController::class, 'show'])->name('reservas.show');

        // Stub routes for future tasks (nav links won't 500)
        Route::get('/reservas',    fn () => abort(404))->name('reservas');
        Route::get('/calendario',  fn () => abort(404))->name('calendario');
        Route::get('/precario',    fn () => abort(404))->name('precario');
        Route::get('/galeria',     fn () => abort(404))->name('galeria');
        Route::get('/pois',        fn () => abort(404))->name('pois');
        Route::get('/testemunhos', fn () => abort(404))->name('testemunhos');
        Route::get('/configuracoes',fn () => abort(404))->name('configuracoes');
    });
});

/*
|--------------------------------------------------------------------------
| Breeze Auth (legacy, kept for profile management)
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
