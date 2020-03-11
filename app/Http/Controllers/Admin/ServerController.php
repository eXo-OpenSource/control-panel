<?php

namespace App\Http\Controllers\Admin;

use App\Models\Logs\AdminActionOther;
use App\Models\SettingTest;
use App\Models\User;
use App\Services\MTAWorkerService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class ServerController extends Controller
{
    public function show()
    {
        abort_unless(Gate::allows('admin-rank-3'), 403);

        $setting = SettingTest::query()->where('Index', 'ServerPassword')->first();

        $logs = (new MTAWorkerService('prod'))->logs();
        $testLogs = (new MTAWorkerService('test'))->logs();

        return view('admin.servers.show', compact('setting', 'logs', 'testLogs'));
    }

    public function action()
    {
        abort_unless(Gate::allows('admin-rank-5'), 403);

        $env = request()->get('env');
        $action = request()->get('action');

        $server = new MTAWorkerService($env);

        $adminAction = new AdminActionOther();
        $adminAction->UserId = auth()->user()->Id;
        $adminAction->Type = 'ServerAction';
        $adminAction->Arg = $action . ' ' . $env;
        $adminAction->Date = new \Carbon\Carbon();
        $adminAction->save();


        switch($action) {
            case 'start':
                $server->start();
                break;

            case 'restart':
                $server->restart();
                break;

            case 'stop':
                $server->stop();
                break;
        }


        return redirect()->route('admin.server.show');
    }

    public function editPassword()
    {
        abort_unless(Gate::allows('admin-rank-5'), 403);

        $setting = SettingTest::query()->where('Index', 'ServerPassword')->first();

        return view('admin.servers.edit-password', compact('setting'));
    }

    public function updatePassword(Request $request)
    {
        abort_unless(Gate::allows('admin-rank-5'), 403);

        $data = $request->validate([
            'testPw' => 'required|min:8|max:32',
        ]);


        $password = SettingTest::query()->where('Index', 'ServerPassword')->first();

        $password->Value = $data['testPw'];

        $password->save();

        return redirect()->route('admin.server.show');
    }
}
