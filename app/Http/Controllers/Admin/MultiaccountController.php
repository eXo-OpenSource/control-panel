<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MultiAccount;
use Illuminate\Support\Facades\Gate;

class MultiaccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_unless(Gate::allows('admin-rank-3'), 403);

        $multiaccounts = MultiAccount::with(['admin'])->paginate(50);

        return view('admin.users.multiaccounts.index', ['multiaccounts' => $multiaccounts]);
    }
}
