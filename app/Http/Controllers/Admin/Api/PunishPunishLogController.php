<?php


namespace App\Http\Controllers\Admin\Api;


use App\Http\Controllers\Controller;
use App\Models\Logs\Punish;
use App\Models\Logs\PunishLog;
use Illuminate\Support\Facades\Gate;

class PunishPunishLogController extends Controller
{
    public function index(Punish $punish)
    {
        abort_unless(Gate::allows('admin-rank-3'), 403);

        $result = [];

        foreach($punish->log()->with('admin')->orderBy('Id', 'DESC')->get() as $log)
        {
            $entry = $log->toArray();
            $entry['Admin'] = $log->admin ? $log->admin->Name : 'Unbekannt';
            unset($entry['admin']);
            array_push($result, $entry);
        }

        return $result;
    }
}
