<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class UserListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return array|\Illuminate\Support\Collection
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
        } elseif($type === 'admin') {
            $users = DB::table('account');
            $users->where('Rank', '>=', $id);
            $users->orderBy('Rank', 'DESC');

            return $users->select(['Id', 'Name', 'Rank'])->get();
        }

        return [];
    }
}
