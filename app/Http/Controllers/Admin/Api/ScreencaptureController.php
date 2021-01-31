<?php


namespace App\Http\Controllers\Admin\Api;


use App\Events\ScreencaptureReceive;
use App\Http\Controllers\Controller;
use App\Models\AccountScreenshot;
use App\Models\User;
use App\Services\MTAService;
use App\Services\StatisticService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ScreencaptureController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return string
     */
    public function store(Request $request)
    {
        $token = $request->get('token');
        $data = $request->get('data');

        if(!Storage::disk('screencaptures')->exists($token)) {
            Storage::disk('screencaptures')->makeDirectory($token);
        }
        $id = count(Storage::disk('screencaptures')->allFiles($token)) + 1;
        Storage::disk('screencaptures')->put(
            $token . '/' . $id . '.jpeg', $data
        );

        event(new ScreencaptureReceive($token, 'data:image/jpeg;base64,' . base64_encode($data)));

        return 'OK';
    }
}
