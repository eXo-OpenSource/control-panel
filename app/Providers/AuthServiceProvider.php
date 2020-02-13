<?php

namespace App\Providers;

use App\Extensions\ExoUserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Faction'    => \App\Policies\FactionPolicy::class,
        'App\Models\Company'    => \App\Policies\CompanyPolicy::class,
        'App\Models\Group'      => \App\Policies\GroupPolicy::class,
        'App\Models\Texture'    => \App\Policies\TexturePolicy::class,
        'App\Models\User'       => \App\Policies\UserPolicy::class,
        'App\Models\Vehicle'    => \App\Policies\VehiclePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::viaRequest('exo-user-token', function ($request) {
            if ($request->token && $request->token !== '') {
                return \App\Models\User::where('ApiToken', $request->token)->first();
            }
            return null;
        });

        Auth::provider('exo', function ($app, array $config) {
            return new ExoUserProvider();
        });
    }

}
