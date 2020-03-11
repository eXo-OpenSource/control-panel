<?php

namespace App\Http\Controllers\Admin;

use App\Models\SettingTest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class TestServerController extends Controller
{
    public function show()
    {
        abort_unless(Gate::allows('admin-rank-3'), 403);

        $setting = SettingTest::query()->where('Index', 'ServerPassword')->first();

        return view('admin.test-servers.show', compact('setting'));
    }

    public function editPassword()
    {
        abort_unless(Gate::allows('admin-rank-5'), 403);

        $setting = SettingTest::query()->where('Index', 'ServerPassword')->first();

        return view('admin.test-servers.edit-password', compact('setting'));
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

        return redirect()->route('admin.test-server.show');
    }
}
