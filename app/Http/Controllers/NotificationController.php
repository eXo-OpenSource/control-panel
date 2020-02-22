<?php

namespace App\Http\Controllers;

use App\Models\FcmNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();

        if (!FcmNotification::where('Token', '=', $request->Token)->exists()) {

            $data = $request->only(['Token', 'Device']);
            $data["Ip"] = request()->ip();

            $notification = new FcmNotification($data);

            $notification->save();

            return response()->json('success');
        }
        return response()->json('already added');
    }
}
