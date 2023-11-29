<?php

namespace Marifhasan\PrintPDF;

use Illuminate\Support\ServiceProvider;
use Marifhasan\PrintPDF\Console\Commands\MakePrint;

class PrintPDFServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        if ($this->app->runningInConsole()) {
            $this->commands([
                MakePrint::class,
            ]);
		}
	}
}
