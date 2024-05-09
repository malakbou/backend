<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Gate;
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
    ];

    /**
     * Register any authentication / authorization services.
     */


    // public function boot(): void
    // {
    //     Gate::define('create-project', function ($user) {
    //         return $user->role === 'CHEF_PROJET'; // Adjust according to how roles are stored and checked
    //     });
    // }

    public function boot()
    {
        $this->registerPolicies();
    
        // Gate::define('create-project', function ($user) {
        //     return $user->privileges === 'CHEF_PROJET';
        // });
    }


    




}
