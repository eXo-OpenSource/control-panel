<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Character;
use App\Models\Training\Practice;
use App\Models\Training\Template;
use App\Models\Training\Training;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OverviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        /** @var Character $character */
        $character = auth()->user()->character;
        $targets = $character->getTrainingTargets();

        abort_if(count($targets) === 0, 403);

        $currentTarget = $targets[0];

        if(request()->has('target')) {
            if(in_array(request()->get('target'), $targets)) {
                $currentTarget = request()->get('target');
            }
        }

        $templates = Template::query();

        $role = request()->get('role') == 1 ? 1 : 0;

        $dateFrom = request()->get('fromDate') ? Carbon::parse(request()->get('fromDate')) : Carbon::now()->startOfWeek()->subDay();
        $dateTo = request()->get('toDate') ? Carbon::parse(request()->get('toDate')) : $dateFrom->copy()->addDays(6)->endOfDay();

        if($currentTarget === 'faction') {
            $templates->where('ElementType', 2)->where('ElementId', $character->FactionId);
        }

        if($currentTarget === 'company') {
            $templates->orWhere('ElementType', 3)->where('ElementId', $character->CompanyId);
        }

        $templates = $templates->get();
        $members = [];

        if($currentTarget === 'faction') {
            $members = $character->faction->members()->with('user')->orderBy('FactionRank', 'DESC')->get();
        } elseif($currentTarget === 'company') {
            $members = $character->company->members()->with('user')->orderBy('CompanyRank', 'DESC')->get();
        }

        $ids = $templates->pluck('Id');

        $query = DB::table('trainings');
        $query->join('training_users', 'training_users.TrainingId', '=', 'trainings.Id');
        $query->where('trainings.State', 1);
        $query->whereIn('trainings.TemplateId', $ids);
        $query->whereBetween('trainings.CreatedAt', [$dateFrom, $dateTo]);
        $query->groupBy('training_users.UserId');
        $query->groupBy('training_users.Role');
        $query->groupBy('trainings.TemplateId');
        $query->select('trainings.TemplateId', 'training_users.UserId', 'training_users.Role', DB::raw('COUNT(vrp_training_users.UserId) AS Count'));

        $result = $query->get();

        $matrixInfo = [
            'title' => [],
            'rows' => []
        ];

        $row = [
            [
                'Value' => '',
                'TemplateId' => null,
                'UserId' => null,
                'Rank' => false,
                'Sum' => false
            ],
            [
                'Value' => __('Rang'),
                'TemplateId' => null,
                'UserId' => null,
                'Rank' => true,
                'Sum' => false
            ]
        ];
        foreach($templates as $template) {
            array_push($row, [
                'Value' => $template->Name,
                'TemplateId' => $template->Id,
                'UserId' => null,
                'Rank' => false,
                'Sum' => false
            ]);
        }
        array_push($row, [
            'Value' => __('Summe'),
            'TemplateId' => null,
            'UserId' => null,
            'Rank' => false,
            'Sum' => true
        ]);

        $matrixInfo['title'] = $row;

        foreach($members as $member) {
            $row = [
                [
                    'Value' => $member->user->Name,
                    'TemplateId' => null,
                    'UserId' => $member->Id,
                    'Rank' => false,
                    'Sum' => false
                ],
                [
                    'Value' => $member->FactionRank,
                    'TemplateId' => null,
                    'UserId' => null,
                    'Rank' => true,
                    'Sum' => false
                ]
            ];

            $count = 0;

            foreach($templates as $template) {
                $found = false;
                foreach($result as $entry) {
                    if($entry->Role === $role && $template->Id === $entry->TemplateId && $entry->UserId === $member->Id) {
                        $found = true;

                        if($role === 0) {
                            $count++;
                        } else {
                            $count += $entry->Count;
                        }

                        array_push($row, [
                            'Value' => $entry->Count,
                            'TemplateId' => $template->Id,
                            'UserId' => null,
                            'Rank' => false,
                            'Sum' => false
                        ]);
                        break;
                    }
                }
                if(!$found) {
                    array_push($row, [
                        'Value' => 0,
                        'TemplateId' => $template->Id,
                        'UserId' => null,
                        'Rank' => false,
                        'Sum' => false
                    ]);
                }
            }

            array_push($row, [
                'Value' => $count,
                'TemplateId' => $template->Id,
                'UserId' => null,
                'Rank' => false,
                'Sum' => true
            ]);

            array_push($matrixInfo['rows'], $row);
        }


        return view('trainings.overview.index', compact('targets', 'currentTarget', 'matrixInfo', 'dateTo', 'dateFrom', 'role'));
    }
}
