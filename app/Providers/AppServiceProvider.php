<?php

namespace App\Providers;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request; // Importar la clase Request
 class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Contracts\ProductRepositoryInterface::class,
            \App\Repositories\ProductRepository::class
        );

        $this->app->alias(Excel::class, 'excel');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
        $this->app->make(Request::class)->setTrustedProxies(
            // Confía en todas las IP (necesario cuando Cloudflare usa IP's dinámicas)
            ['*'], 
            
            // Especifica los encabezados en los que confía (HTTPS, Host, IP de Cliente)
            Request::HEADER_X_FORWARDED_FOR | 
            Request::HEADER_X_FORWARDED_HOST | 
            Request::HEADER_X_FORWARDED_PORT | 
            Request::HEADER_X_FORWARDED_PROTO
        );
    }
}
