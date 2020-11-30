<?php

namespace App\Http\Controllers\Api;

use App\Services\ForumService;
use App\Services\MTAService;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Ticket;
use App\Models\TicketAnswer;
use Exo\TeamSpeak\Responses\TeamSpeakResponse;
use Exo\TeamSpeak\Services\TeamSpeakService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\TicketCategory;
use App\Http\Controllers\Controller;

class TicketSettingController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Http\JsonResponse|object
     */
    public function update(Request $request)
    {
        $display = $request->get('display');

        if(!in_array($display, [0, 1])) {
            $display = 0;
        }

        $user = User::find(auth()->user()->Id);
        $user->TicketDisplay = $display;
        $user->save();

        return response()->json(['Status' => 'Successful', 'Message' => __('Deine Einstellungen wurden aktualisiert.')]);
    }
}
