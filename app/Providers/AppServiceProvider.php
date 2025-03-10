<?php

namespace App\Providers;

use App\Repositories\CandidateRepository;
use App\Repositories\CandidateRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            CandidateRepositoryInterface::class,
            CandidateRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
