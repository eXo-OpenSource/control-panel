<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyStatisticController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Company $company, $statistic = '')
    {
        $page = 'statistics';

        if($statistic === '') {
            $statistic = 'money';
        }

        return view('companies.show', compact('company', 'page', 'statistic'));
    }
}
