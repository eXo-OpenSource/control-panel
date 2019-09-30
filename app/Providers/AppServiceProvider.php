<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
        }

        Blade::directive('vehicleName', function ($model) {
            return "<?php echo config('constants.vehicleNames')[$model]; ?>";
        });

        Gate::define('admin-rank-1', function ($user) { // Ticketsupporter
            return $user->Rank >= 1;
        });

        Gate::define('admin-rank-2', function ($user) { // Clanmember
            return $user->Rank >= 2;
        });

        Gate::define('admin-rank-3', function ($user) { // Supporter
            return $user->Rank >= 3;
        });

        Gate::define('admin-rank-4', function ($user) { // Moderator
            return $user->Rank >= 4;
        });

        Gate::define('admin-rank-5', function ($user) { // Administrator
            return $user->Rank >= 5;
        });

        Gate::define('admin-rank-6', function ($user) { // Servermanager
            return $user->Rank >= 6;
        });

        Gate::define('admin-rank-7', function ($user) { // Scripter
            return $user->Rank >= 7;
        });

        Gate::define('admin-rank-8', function ($user) { // Stellv. Projektleiter
            return $user->Rank >= 8;
        });

        Gate::define('admin-rank-9', function ($user) { // Projektleiter
            return $user->Rank >= 9;
        });
    }
}
