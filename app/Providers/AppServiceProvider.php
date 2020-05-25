<?php

namespace App\Providers;

use App\Models\VehicleShop;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Queue\Events\JobFailed;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

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

        Blade::directive('factionColor', function ($value) {
            $value = explode(', ', $value);

            $factionId = $value[0];
            $alpha = isset($value[1]) ? $value[1] : 1;

            $color = config('constants.factionColors')[0];

            if (config('constants.factionColors')[$factionId]) {
                $color = config('constants.factionColors')[$factionId];
            }

            return "<?php echo 'rgba(".$color[0].", ".$color[1].", ".$color[2].", ".$alpha.")'; ?>";
        });


        Blade::directive('playTime', function ($playTime) {
            return "<?php \$tmp = intval($playTime); \$hours = floor(\$tmp / 60); \$minutes = \$tmp % 60; if(\$minutes < 10) { \$minutes = '0' . \$minutes; } echo \$hours . ':' . \$minutes; ?>";
        });

        Blade::directive('money', function ($money) {
            return "<?php echo '$ ' . number_format($money, 0, ',','.' ); ?>";
        });

        //

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


        Relation::morphMap([
            1 => \App\Models\User::class,
            2 => \App\Models\Faction::class,
            3 => \App\Models\Company::class,
            4 => \App\Models\AdminBank::class,
            5 => \App\Models\ServerBankAccount::class,
            6 => \App\Models\Shop::class,
            7 => \App\Models\House::class,
            8 => \App\Models\Group::class,
            9 => \App\Models\VehicleShop::class,
        ]);

        Paginator::defaultView('pagination.default');

        Paginator::defaultSimpleView('pagination.simple-default');

        Queue::failing(function(JobFailed $event) {
            if (app()->bound('sentry')) {
                app('sentry')->captureException($event->exception);
            }
        });
    }
}
