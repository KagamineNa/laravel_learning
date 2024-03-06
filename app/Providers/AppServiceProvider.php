<?php

namespace App\Providers;

use App\View\Components\Alert;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('datetime', function ($expression) {
            $expression = trim($expression, '\'');
            $expression = trim($expression, '"');
            $dataObject = date_create($expression);
            if (!empty ($dataObject)) {
                $dateFormat = $dataObject->format('d/m/Y H:i:s');
                return $dateFormat;
            }
            return false;
        });

        Blade::if('env', function ($value) {
            if (config('app.env') === $value) {
                return true;
            }
            return false;
        });
        //use for special if 

        Blade::component('package-alert', Alert::class);
    }
}
