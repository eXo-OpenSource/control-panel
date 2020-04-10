<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyLogController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Company $company, $log = '')
    {
        $page = 'logs';

        if($log === '') {
            $log = 'company';
        }

        return view('companies.show', compact('company', 'page', 'log'));
    }
}
