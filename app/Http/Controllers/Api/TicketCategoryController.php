<?php

namespace App\Http\Controllers\Api;

use App\Models\TicketCategory;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TicketCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        $query = TicketCategory::query();

        if(auth()->user()->isBanned() !== false) {
            $query->where('IsAllowedForBannedUsers', 1);
        }

        return $query->orderBy('Order', 'ASC')->orderBy('Id', 'ASC')->with('fields')->get();
    }
}
