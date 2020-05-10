<?php

namespace App\Http\Controllers\Training;

use App\Models\Character;
use App\Http\Controllers\Controller;
use App\Models\Training\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class TrainingTemplateController extends Controller
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

        $templates = Template::query();

        if(in_array('faction', $targets)) {
            $templates->where('ElementType', 2)->where('ElementId', $character->FactionId);
        }

        if(in_array('company', $targets)) {
            $templates->orWhere('ElementType', 3)->where('ElementId', $character->CompanyId);
        }

        $templates = $templates->get();

        return view('trainings.templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        Gate::authorize('create', Template::class);

        /** @var Character $character */
        $character = auth()->user()->character;
        $targets = $character->getTrainingTargets();

        return view('trainings.templates.create', compact('targets'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Template::class);

        /** @var Character $character */
        $character = auth()->user()->character;
        $targets = $character->getTrainingTargets();

        $request->validate([
            'name' => 'required|max:255',
        ]);


        $type = $targets[0];

        if(count($targets) > 1) {
             $request->validate([
                'type' => 'required|in:faction,company',
            ]);

            $type = $request->get('type');
        }


        $elementType = $type === 'faction' ? 2 : ($type === 'company' ? 3 : -1);
        $elementId = $type === 'faction' ? $character->FactionId : ($type === 'company' ? $character->CompanyId : -1);

        $template = new Template();
        $template->Name = $request->get('name');
        $template->UserId = auth()->user()->Id;
        $template->ElementId = $elementId;
        $template->ElementType = $elementType;
        $template->save();

        return redirect()->route('trainings.templates.show', [$template]);
    }

    /**
     * Display the specified resource.
     *
     * @param Template $template
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Template $template)
    {
        Gate::authorize('show', $template);

        return view('trainings.templates.show', compact('template'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Template $template
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Template $template)
    {
        Gate::authorize('edit', $template);

        return view('trainings.templates.edit', compact('template'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Template $template
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Template $template)
    {
        Gate::authorize('update', $template);

        $request->validate([
            'name' => 'required|max:255',
        ]);

        $template->Name = $request->get('name');
        $template->save();

        return redirect()->route('trainings.templates.show', [$template]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Template $template
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delete(Template $template)
    {
        Gate::authorize('delete', $template);

        return view('trainings.templates.delete', compact('template'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Template $template
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Template $template)
    {
        Gate::authorize('delete', $template);

        $template->delete();

        return redirect()->route('trainings.templates.index');
    }
}
