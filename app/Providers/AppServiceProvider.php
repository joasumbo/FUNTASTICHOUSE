<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::composer('*', function ($view) {
            try {
                $view->with('settings', Setting::pluck('value', 'key'));
            } catch (\Throwable) {
                $view->with('settings', collect());
            }
        });
    }
}
