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
                    $user->Name = Auth::user()->Name;
                    $user->Time = Carbon::now();
                    if($request->method() === 'GET' && !$request->ajax()) {
                        $user->Url = $request->path() === '/' ? '/' : '/' . $request->path();
                    }
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
                    'Url' => $request->method() === 'GET' && !$request->ajax() ? '/' . $request->path() : '/'
                ]);
            }

            usort($users, function($a, $b) {
                return $a->Name < $b->Name ? -1 : 1;
            });

            Cache::forever('users-online', $users);
        }
        return $next($request);
    }
}
