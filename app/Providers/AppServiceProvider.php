<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('money', function ($amount) {
            return "<?php echo 'R$ ' . number_format($amount, 2, ','); ?>";
        });

        // Em produção atrás de proxy (ex.: Hostinger), forçar HTTPS para evitar loop de redirect
        if ($this->app->environment('production') || config('app.force_https')) {
            URL::forceScheme('https');
        }
    }
}
