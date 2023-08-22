<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\PersonalAccessToken;
use App\Models\Role;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Laravel\Sanctum\Sanctum;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Gate::before(function($user, $ability) {
        //    if($user->type == 'super-admin') {
        //         return true;
        //    }
        //    if($user->type == 'user') {
        //         return false;
        //    }
        // });

        // foreach(config('abilities') as $key => $value) {
        //     Gate::define($key, function($user) use ($key) {
        //         $user->hasAbility($key);
        //     });
        // }

        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
