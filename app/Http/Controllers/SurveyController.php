<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Http\Requests\Survey\StoreSurveyRequest;
use App\Http\Requests\Survey\UpdateSurveyRequest;
use App\DTOs\SurveyDTO;
use App\Actions\Survey\StoreSurveyAction;
use App\Actions\Survey\UpdateSurveyAction;
use App\Http\Requests\Survey\StoreSurveyQuestionRequest;
use App\DTOs\SurveyQuestionDTO;
use App\Actions\Survey\StoreSurveyQuestionAction;
use App\Http\Controllers\Controller;


class SurveyController extends Controller
{
    public function index()
    {
        $organizationId = session('organization_id');
        $surveys = Survey::where('organization_id', $organizationId)->get();

        return view('surveys.index', compact('surveys'));
    }

    public function create()
    {
        return view('surveys.create');
    }

    public function store(StoreSurveyRequest $request)
    {
        
    $this->authorize('create', Survey::class);

    // Récupère l'organisation de l'utilisateur connecté
    $organizationId = $request->user()->organization_id; // <-- Ici

    // Crée le DTO avec l'organisation correcte
    $dto = new SurveyDTO(
        $request->title,
        $request->description,
        $request->start_date,
        $request->end_date,
        $request->boolean('is_anonymous'),
        $organizationId,
        $request->user()->id
    );

    // Crée le sondage
    $survey = app(StoreSurveyAction::class)->execute($dto);

    return redirect()->route('surveys.index')->with('success', 'Sondage créé !');
}

    public function show(Survey $survey)
    {
        $this->authorize('view', $survey);

        return view('surveys.show', compact('survey'));
    }

    public function edit(Survey $survey)
    {
        $this->authorize('update', $survey);

        return view('surveys.edit', compact('survey'));
    }

    public function update(UpdateSurveyRequest $request, Survey $survey)
    {
        $this->authorize('update', $survey);

        $dto = SurveyDTO::fromRequest($request);
        $survey = app(UpdateSurveyAction::class)->execute($dto);

        return redirect()->route('surveys.index')->with('success', 'Sondage mis à jour !');
    }

    public function destroy(Survey $survey)
    {
        $this->authorize('delete', $survey);

        $survey->delete();

        return redirect()->route('surveys.index')->with('success', 'Sondage supprimé !');
    }

    public function addQuestion(StoreSurveyQuestionRequest $request, Survey $survey)
{
    $this->authorize('update', $survey);

    $dto = SurveyQuestionDTO::fromRequest($request);
    app(StoreSurveyQuestionAction::class)->execute($dto);

    return redirect()->route('surveys.show', $survey)->with('success', 'Question ajoutée !');
}
}
