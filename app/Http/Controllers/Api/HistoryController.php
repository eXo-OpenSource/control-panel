<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\PlayerHistory;
use App\Services\StatisticService;
use Carbon\Carbon;

class HistoryController extends Controller
{
    public function show(PlayerHistory $history)
    {
        abort_unless(auth()->user()->can('history', $history->user), 403);

        $data = $history->toArray();

        unset($data['user']);

        $data['JoinDateText'] = $history->JoinDate->format('d.m.Y H:i:s');
        $data['LeaveDateText'] = $history->LeaveDate ? $history->LeaveDate->format('d.m.Y H:i:s') : '-';
        $data['Inviter'] = $history->getInviter();
        $data['Uninviter'] = $history->getUninviter();
        $data['InviterUrl'] = route('users.show', $history->InviterId);
        $data['UninviterUrl'] = route('users.show', $history->UninviterId);
        $data['Uninviter'] = $history->getUninviter();
        $data['Element'] = $history->element->Name;
        $data['Duration'] = $history->getDuration();
        $data['ElementTypeName'] = $data['ElementType'] === 'faction' ? 'Fraktion' : 'Unternehmen';
        $data['ElementUrl'] = $data['ElementType'] === 'faction' ? route('factions.show', $history->ElementId) : route('companies.show', $history->ElementId);

        return $data;
    }
}
