<?php


namespace App\Providers;

use App\Models\Candidate;
use App\Models\User;
use App\Policies\CandidatePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    protected $policies = [
        Candidate::class => CandidatePolicy::class
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('view-trashed-candidates', function (User $user) {
            return $user->isAdmin();
        });
    }
}
