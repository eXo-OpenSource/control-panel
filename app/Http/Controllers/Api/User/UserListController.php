<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use App\Http\Controllers\Controller;

class UserListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index()
    {
        $type = request()->get('type');
        $id = request()->get('id');

        if($type === 'faction') {
            $users = User::join('character', 'account.Id', '=', 'character.Id');
            $users->where('FactionId', $id);
            $users->orderBy('FactionRank', 'DESC');

            return $users->get(['account.Id', 'Name', 'FactionRank AS Rank']);
        } elseif($type === 'company') {
            $users = User::join('character', 'account.Id', '=', 'character.Id');
            $users->where('CompanyId', $id);
            $users->orderBy('CompanyRank', 'DESC');

            return $users->get(['account.Id', 'Name', 'CompanyRank AS Rank']);
        } elseif($type === 'group') {
            $users = User::join('character', 'account.Id', '=', 'character.Id');
            $users->where('GroupId', $id);
            $users->orderBy('GroupRank', 'DESC');

            return $users->get(['account.Id', 'Name', 'GroupRank AS Rank']);
        }

        return [];
    }
}
