<?php

namespace Codewiser\Journalism;

use Illuminate\Support\ServiceProvider;

class JournalServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../database/migrations/' => base_path('/database/migrations')
        ], 'journalism-migrations');

    }

}
