<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PerfilController as AdminPerfilController;
use App\Http\Controllers\Admin\CalendarioController as AdminCalendarioController;
use App\Http\Controllers\Admin\GaleriaController as AdminGaleriaController;
use App\Http\Controllers\Admin\PoiController as AdminPoiController;
use App\Http\Controllers\Admin\TestemunhoController as AdminTestemunhoController;
use App\Http\Controllers\Admin\ConfiguracaoController as AdminConfiguracaoController;
use App\Http\Controllers\Admin\PrecarioController as AdminPrecarioController;
use App\Http\Controllers\Admin\RegraController as AdminRegraController;
use App\Http\Controllers\Admin\ReservaController as AdminReservaController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\ContactosController;
use App\Http\Controllers\ExperienciaController;
use App\Http\Controllers\GaleriaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OQueFazerController;
use App\Http\Controllers\PorqueNosController;
use App\Http\Controllers\ReservasController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Public site routes
|--------------------------------------------------------------------------
*/
Route::get('/sitemap.xml',        [SitemapController::class,    'index'])->name('sitemap');
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

    Route::middleware(['admin'])->group(function () {
        Route::get('/',          fn () => redirect()->route('admin.dashboard'));
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Perfil
        Route::get('/perfil',              [AdminPerfilController::class, 'index'])->name('perfil');
        Route::post('/perfil',             [AdminPerfilController::class, 'update'])->name('perfil.update');
        Route::post('/perfil/password',    [AdminPerfilController::class, 'updatePassword'])->name('perfil.password');

        // Reservas
        Route::get('/reservas',                        [AdminReservaController::class, 'index'])->name('reservas');
        Route::get('/reservas/{reservation}',          [AdminReservaController::class, 'show'])->name('reservas.show');
        Route::patch('/reservas/{reservation}/status', [AdminReservaController::class, 'updateStatus'])->name('reservas.status');
        // Calendário
        Route::get('/calendario',                 [AdminCalendarioController::class, 'index'])->name('calendario');
        Route::get('/api/calendar',               [AdminCalendarioController::class, 'data'])->name('api.calendar');
        Route::post('/api/calendar/block',        [AdminCalendarioController::class, 'block'])->name('api.calendar.block');
        Route::delete('/api/calendar/unblock',    [AdminCalendarioController::class, 'unblock'])->name('api.calendar.unblock');
        // Preçário
        Route::get('/precario',                             [AdminPrecarioController::class, 'index'])->name('precario');
        Route::patch('/precario/{experience}/prices',       [AdminPrecarioController::class, 'updatePrices'])->name('precario.prices');
        Route::post('/precario/rules',                      [AdminPrecarioController::class, 'storeRule'])->name('precario.rules.store');
        Route::patch('/precario/rules/{rule}',              [AdminPrecarioController::class, 'updateRule'])->name('precario.rules.update');
        Route::delete('/precario/rules/{rule}',             [AdminPrecarioController::class, 'destroyRule'])->name('precario.rules.destroy');
        // Regras
        Route::get('/regras',                  [AdminRegraController::class, 'index'])->name('regras');
        Route::post('/regras',                 [AdminRegraController::class, 'store'])->name('regras.store');
        Route::patch('/regras/{rule}',         [AdminRegraController::class, 'update'])->name('regras.update');
        Route::delete('/regras/{rule}',        [AdminRegraController::class, 'destroy'])->name('regras.destroy');
        Route::patch('/regras/{rule}/toggle',  [AdminRegraController::class, 'toggle'])->name('regras.toggle');

        // Galeria
        Route::get('/galeria',                     [AdminGaleriaController::class, 'index'])->name('galeria');
        Route::post('/galeria',                    [AdminGaleriaController::class, 'store'])->name('galeria.store');
        Route::patch('/galeria/{image}',           [AdminGaleriaController::class, 'update'])->name('galeria.update');
        Route::patch('/galeria/{image}/toggle',    [AdminGaleriaController::class, 'toggle'])->name('galeria.toggle');
        Route::delete('/galeria/{image}',          [AdminGaleriaController::class, 'destroy'])->name('galeria.destroy');
        // Pontos de Interesse
        Route::get('/pois',                              [AdminPoiController::class, 'index'])->name('pois');
        Route::post('/pois',                             [AdminPoiController::class, 'store'])->name('pois.store');
        Route::post('/pois/categories',                  [AdminPoiController::class, 'storeCategory'])->name('pois.categories.store');
        Route::patch('/pois/categories/{category}',      [AdminPoiController::class, 'updateCategory'])->name('pois.categories.update');
        Route::delete('/pois/categories/{category}',     [AdminPoiController::class, 'destroyCategory'])->name('pois.categories.destroy');
        Route::patch('/pois/{poi}/toggle',               [AdminPoiController::class, 'toggle'])->name('pois.toggle');
        Route::patch('/pois/{poi}',                      [AdminPoiController::class, 'update'])->name('pois.update');
        Route::delete('/pois/{poi}',                     [AdminPoiController::class, 'destroy'])->name('pois.destroy');
        // Testemunhos
        Route::get('/testemunhos',                         [AdminTestemunhoController::class, 'index'])->name('testemunhos');
        Route::post('/testemunhos',                        [AdminTestemunhoController::class, 'store'])->name('testemunhos.store');
        Route::post('/testemunhos/reorder',                [AdminTestemunhoController::class, 'reorder'])->name('testemunhos.reorder');
        Route::patch('/testemunhos/{testimonial}/toggle',  [AdminTestemunhoController::class, 'toggle'])->name('testemunhos.toggle');
        Route::patch('/testemunhos/{testimonial}',         [AdminTestemunhoController::class, 'update'])->name('testemunhos.update');
        Route::delete('/testemunhos/{testimonial}',        [AdminTestemunhoController::class, 'destroy'])->name('testemunhos.destroy');
        // Configurações
        Route::get('/configuracoes',                   [AdminConfiguracaoController::class, 'index'])->name('configuracoes');
        Route::post('/configuracoes/geral',             [AdminConfiguracaoController::class, 'updateGeral'])->name('configuracoes.geral');
        Route::post('/configuracoes/contactos',         [AdminConfiguracaoController::class, 'updateContactos'])->name('configuracoes.contactos');
        Route::post('/configuracoes/social',            [AdminConfiguracaoController::class, 'updateSocial'])->name('configuracoes.social');
        Route::post('/configuracoes/seo',               [AdminConfiguracaoController::class, 'updateSeo'])->name('configuracoes.seo');
    });
});

// Fallback: qualquer /login vai para o admin
Route::get('/login', fn () => redirect()->route('admin.login'))->name('login');
