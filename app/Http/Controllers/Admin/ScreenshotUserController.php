<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccountScreenshot;
use App\Models\User;
use App\Services\MTAService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

class ScreenshotUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(User $user)
    {
        abort_unless(Gate::allows('admin-rank-3'), 403);
        return view('admin.users.screenshots.index', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, User $user)
    {
        abort_unless(Gate::allows('admin-rank-3'), 403);

        $response = (new MTAService())->takeScreenShot($user->Id);
        if (!empty($response)) {
            $data = json_decode($response[0]);
            if($data->status === 'SUCCESS') {
                $screenshot = new \App\Models\AccountScreenshot();
                $screenshot->UserId = $user->Id;
                $screenshot->AdminId = auth()->user()->Id;
                $screenshot->Tag = $data->tag;
                $screenshot->Status = 'Processing';
                $screenshot->save();

                Session::flash('alert-success', 'Screenshot wurde angefordert.');
                return redirect()->route('admin.users.screenshots.index', [$user]);
            }
            Session::flash('alert-danger', 'Spieler ist offline.');
            return redirect()->route('admin.users.screenshots.index', [$user]);
        }

        Session::flash('alert-danger', 'Server ist nicht erreichbar.');
        return redirect()->route('users.show', [$user]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AccountScreenshot  $accountScreenshot
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, AccountScreenshot $accountScreenshot)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AccountScreenshot  $accountScreenshot
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user, AccountScreenshot $accountScreenshot)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @param  \App\Models\AccountScreenshot  $accountScreenshot
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user, AccountScreenshot $accountScreenshot)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AccountScreenshot  $accountScreenshot
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, AccountScreenshot $accountScreenshot)
    {
        //
    }
}
