<?php


namespace App\Http\Controllers\Api;


use App\Company;
use App\Faction;
use App\Http\Controllers\Controller;
use App\Services\StatisticService;
use Carbon\Carbon;

class ChartController extends Controller
{
    public function show($name)
    {
        $from = Carbon::now()->subDays(13);
        $to = Carbon::now();


        if ($name === 'factions') {
            return StatisticService::getFactionsActivity($from, $to);
        } elseif ($name === 'companies') {
            return StatisticService::getCompaniesActivity($from, $to);
        } elseif (substr($name, 0, 7) === 'faction') {
            $faction = explode(':', $name);
            $faction = Faction::find($faction[1]);

            abort_unless(auth()->user()->can('activityTotal', $faction), 403);

            return StatisticService::getFactionActivity($faction, $from, $to);
        } elseif (substr($name, 0, 7) === 'company') {
            $company = explode(':', $name);
            $company = Company::find($company[1]);

            abort_unless(auth()->user()->can('activityTotal', $company), 403);

            return StatisticService::getCompanyActivity($company, $from, $to);
        }

        return ['status' => 'Error'];
    }
}
