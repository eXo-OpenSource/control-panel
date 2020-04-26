<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training\Exercise;
use App\Models\Training\Practice;
use App\Models\Training\PracticeLesson;
use App\Models\Training\Template;
use App\Models\Training\Training;
use App\Models\Training\TrainingContent;
use App\Models\Training\TrainingUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TrainingTemplateTrainingController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param Template $template
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Template $template)
    {
        Gate::authorize('show', $template);
        return view('trainings.create', compact('template'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Template $template
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Template $template)
    {
        Gate::authorize('show', $template);

        $request->validate([
            'notes' => 'max:65536',
        ]);

        $training = new Training();
        $training->TemplateId = $template->Id;
        $training->ElementId = $template->ElementId;
        $training->ElementType = $template->ElementType;
        $training->Name = $template->Name;
        $training->UserId = auth()->user()->Id;
        $training->Notes = $request->get('notes') ?? '';
        $training->save();

        foreach($template->contents()->orderBy('Order', 'ASC')->orderBy('Id', 'ASC')->get() as $content) {
            $trainingContent = new TrainingContent();
            $trainingContent->TrainingId = $training->Id;
            $trainingContent->TrainingContentId = $content->Id;
            $trainingContent->UserId = auth()->user()->Id;
            $trainingContent->Order = $content->Order;
            $trainingContent->State = false;
            $trainingContent->Name = $content->Name;
            $trainingContent->Description = $content->Description;
            $trainingContent->save();
        }

        $training->users()->attach(auth()->user()->Id, ['Role' => 1]);

        return redirect()->route('trainings.show', [$training]);
    }
}
