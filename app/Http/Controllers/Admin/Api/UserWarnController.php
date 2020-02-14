<?php

namespace App\Http\Controllers\Admin\Api;

use App\Models\Warn;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserWarnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\User  $user
     * @return array
     */
    public function index(User $user)
    {
        $warns = $user->warns;

        $result = [];

        foreach($warns as $warn) {
            array_push($result, [
                'Id' => $warn->Id,
                'UserId' => $warn->userId,
                'Reason' => $warn->reason,
                'AdminId' => $warn->adminId,
                'Admin' => $warn->admin->Name,
                'Created' => Carbon::createFromTimestamp($warn->created)->format('d.m.Y H:i:s'),
                'Expires' => Carbon::createFromTimestamp($warn->expires)->format('d.m.Y H:i:s'),
            ]);
        }

        return $result;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Warn  $warn
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, Warn $warn)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Warn  $warn
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user, Warn $warn)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @param  \App\Models\Warn  $warn
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user, Warn $warn)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Warn  $warn
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, Warn $warn)
    {
        //
    }
}
