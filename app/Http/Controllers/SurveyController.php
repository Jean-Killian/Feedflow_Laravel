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
use Illuminate\Http\Request; 

class SurveyController extends Controller
{
    public function index()
    {

        $organizationId = 1;
        $surveys = Survey::where('organization_id', $organizationId)->get();

        return view('surveys.index', compact('surveys'));
    }

    public function create()
    {
        return view('surveys.create');
    }

    /**
     * 
     */
    public function store(StoreSurveyRequest $request)
    {
        $this->authorize('create', Survey::class);

        // Crée le DTO avec organisation 1
        $dto = SurveyDTO::fromRequest($request);
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
        app(UpdateSurveyAction::class)->execute($dto, $survey);

        return redirect()->route('surveys.index')->with('success', 'Sondage mis à jour !');
    }


    public function destroy(Survey $survey)
    {
        $this->authorize('delete', $survey);

        $survey->delete();

        return redirect()->route('surveys.index')->with('success', 'Sondage supprimé !');
    }

    public function addQuestion(Request $request, Survey $survey)
    {
        $this->authorize('update', $survey);

        $dto = SurveyQuestionDTO::fromRequest($request, $survey->id);
        app(StoreSurveyQuestionAction::class)->execute($dto);

        return redirect()->route('surveys.index', $survey)
            ->with('success', 'Question ajoutée !');
    }


    public function addQuestionForm(Survey $survey)
    {
        $this->authorize('update', $survey);

        return view('surveys.add_question', compact('survey'));
    }

    public function takeSurvey(Survey $survey)
    {
        $this->authorize('view', $survey);

        // Vérifie si  déjà répondu
        $answered = $survey->answers()
                        ->where('user_id', auth()->id())
                        ->exists();

        return view('surveys.take', compact('survey', 'answered'));
    }

    public function submitSurvey(Request $request, Survey $survey)
    {
        $answers = $request->input('answers', []);

        foreach ($survey->questions as $question) {
            $answerValue = $answers[$question->id] ?? null;

            // Ignore si aucune réponse (ou gérer comme tu veux)
            if ($answerValue === null) continue;

            // Si c'est un tableau (checkbox)
            if (is_array($answerValue)) {
                $answerValue = json_encode($answerValue);
            }

            \DB::table('survey_answers')->insert([
                'user_id' => $request->user()->id,
                'survey_question_id' => $question->id,
                'answer' => $answerValue,
                'survey_id' => $survey->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('surveys.index')->with('success', 'Sondage répondu !');
    }


}

