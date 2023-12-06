<?php

namespace App\Providers;

use App\Policies\AdminPolicy;
use App\Policies\RevisorPolicy;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
            //
        User::class => AdminPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
        $this->registerPolicies();
        Gate::define('admin', [AdminPolicy::class, 'admin']);

        $this->registerPolicies();
        Gate::define('revisor', [RevisorPolicy::class, 'revisor']);
    }
}
