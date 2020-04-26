<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training\Exercise;
use App\Models\Training\Lesson;
use App\Models\Training\Template;
use App\Models\Training\TemplateContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TrainingTemplateContentController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param Template $template
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Template $template)
    {
        Gate::authorize('edit', $template);
        return view('trainings.templates.contents.create', compact('template'));
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
        Gate::authorize('edit', $template);

        $request->validate([
            'name' => 'required|max:255',
            'description' => 'max:65536',
        ]);

        $templateContent = new TemplateContent();
        $templateContent->TrainingTemplateId = $template->Id;
        $templateContent->Name = $request->get('name');
        $templateContent->Description = $request->get('description') ?? '';
        $templateContent->UserId = auth()->user()->Id;
        $templateContent->Order = $request->get('order') ?? 0;
        $templateContent->save();

        return redirect()->route('trainings.templates.show', [$template]);
    }

    ////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////

    /**
     * Show the form for editing the specified resource.
     *
     * @param TemplateContent $content
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(TemplateContent $content)
    {
        Gate::authorize('edit', $content);
        return view('trainings.templates.contents.edit', compact('content'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param TemplateContent $content
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, TemplateContent $content)
    {
        Gate::authorize('edit', $content);

        $request->validate([
            'name' => 'required|max:255',
            'description' => 'max:65536',
        ]);

        $content->Name = $request->get('name');
        $content->Order = $request->get('order') ?? 0;
        $content->Description = $request->get('description');
        $content->save();

        return redirect()->route('trainings.templates.show', [$content->template]);
    }

    /**
     * @param TemplateContent $content
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delete(TemplateContent $content)
    {
        Gate::authorize('delete', $content);

        return view('trainings.templates.contents.delete', compact('content'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param TemplateContent $content
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(TemplateContent $content)
    {
        Gate::authorize('delete', $content);

        $content->delete();

        return redirect()->route('trainings.templates.show', [$content->template]);
    }
}
