<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UserOnlineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index()
    {
        $usersOnline = Cache::get('users-online');
        $result = [];

        foreach($usersOnline as $user) {
            $data = [
                'Id' => $user->Id,
                'Name' => $user->Name,
            ];

            if(auth()->user() && auth()->user()->Rank >= 3) {
                $data['Time'] = $user->Time->format('d.m.Y H:i:s');
            }

            if(auth()->user() && auth()->user()->Rank >= 5) {
                $data['Url'] = $user->Url === env('APP_URL') ? '/' : str_replace(env('APP_URL'), '', $user->Url);
            }

            array_push($result, $data);
        }

        return $result;
    }
}
