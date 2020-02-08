<?php


namespace App\Http\Middleware;


use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class LastUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check()) {
            $users = Cache::get('users-online', []);
            $diff = Carbon::now()->subMinutes(30);
            $found = false;

            foreach($users as $key => $user) {
                if($user->Id === Auth::user()->Id) {
                    $user->Time = Carbon::now();
                    $found = true;
                }

                if($user->Time < $diff) {
                    unset($users[$key]);
                }
            }
            if(!$found) {
                array_push($users, (object)[
                    'Id' => Auth::user()->Id,
                    'Time' => Carbon::now(),
                    'Name' => Auth::user()->Name,
                ]);
            }

            usort($users, function($a, $b) {
                return $a->Time < $b->Time;
            });

            Cache::forever('users-online', $users);
        }
        return $next($request);
    }
}
