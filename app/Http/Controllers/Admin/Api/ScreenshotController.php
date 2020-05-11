<?php


namespace App\Http\Controllers\Admin\Api;


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

class ScreenshotController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return string
     */
    public function store(Request $request)
    {
        $tag = $request->get('tag');
        $status = $request->get('status');

        $entry = AccountScreenshot::query()->where('Tag', $tag)->where('Status', 'Processing')->first();

        if($status === 'SUCCESS') {
            $entry->Status = 'Success';
            $entry->Image = $entry->Id . '.jpeg';
            $entry->save();

            Storage::disk('screenshots')->put(
                $entry->Id . '.jpeg', $request->get('data')
            );
        } else {
            $error = $request->get('error');
            $entry->Status = $error === 'MINIMIZED' ? 'Minimized' : 'Disabled';
            $entry->save();
        }

        return 'OK';
    }
}
