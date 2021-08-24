<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IpHub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class IpHubController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        abort_unless(Gate::allows('admin-rank-3'), 403);

        $sortBy = request()->has('sortBy') ? request()->get('sortBy') : null;
        $direction = request()->has('direction') ? request()->get('direction') : null;

        $appends = [];

        $limit = 50;

        if(request()->has('limit') && is_numeric(request()->get('limit'))) {
            if (request()->get('limit') < 0) {
                $limit = 1;
            } else if (request()->get('limit') > 500) {
                $limit = 500;
            } else {
                $limit = request()->get('limit');
            }
            $appends['limit'] = $limit;
        }

        $query = IpHub::query();

        if (!empty(request()->get('ip')) || !empty(request()->get('country')) || request()->get('block') !== null) {
            if(!empty(request()->get('ip'))) {
                $query->where('Ip', 'LIKE', '%'.request()->get('ip').'%');
                $appends['ip'] = request()->get('ip');
            }

            if(!empty(request()->get('country'))) {
                $query->where('CountryName', 'LIKE', '%'.request()->get('country').'%');
                $appends['country'] = request()->get('country');
            }

            if(request()->get('block') !== null) {
                $query->where('Block', request()->get('block'));
                $appends['block'] = request()->get('block');
            }

            $hasFilter = true;
        }

        if($sortBy && in_array($sortBy, ['ip', 'country', 'block'])) {
            if($sortBy === 'ip') {
                $query->orderBy('Ip', $direction === 'desc' ? 'DESC' : 'ASC');
            } elseif($sortBy === 'country') {
                $query->orderBy('CountryName', $direction === 'desc' ? 'DESC' : 'ASC');
            } elseif($sortBy === 'block') {
                $query->orderBy('Block', $direction === 'desc' ? 'DESC' : 'ASC');
            } else {
                $query->orderBy('Block', 'DESC');
            }
        } else {
            $query->orderBy('Block', 'DESC');
        }

        $ips = $query->paginate($limit);

        return view('admin.iphub.index', ['ips' => $ips, 'limit' => $limit, 'appends' => $appends, 'sortBy' => $sortBy, 'direction' => $direction]);
    }

    public function show($ip)
    {
        $ipHub = IpHub::query()->with('logins')->where('Ip', $ip)->first();

        if ($ipHub === null)
        {
            abort(404);
        }

        return view('admin.iphub.show', compact('ipHub'));
    }
}
