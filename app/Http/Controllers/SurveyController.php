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
        $user = auth()->user();

        // Vérifie si l'utilisateur a déjà répondu à ce sondage
        $hasAnswered = $survey->answers()->where('user_id', $user->id)->exists();

        if ($hasAnswered) {
            return redirect()->route('surveys.index')
                ->with('info', 'Vous avez déjà répondu à ce sondage.');
        }

        return view('surveys.take', compact('survey'));
    }


   public function submitSurvey(Request $request, Survey $survey)
{
    $user = $request->user();

    // debug log
    Log::info('submitSurvey called', [
        'survey_id' => $survey->id,
        'user_id' => $user?->id,
        'payload' => $request->all(),
    ]);

    // charge relations
    $survey->load('questions');

    // userId null si anonyme
    $userId = $survey->is_anonymous ? null : ($user?->id ?? null);

    // empêcher double réponse pour sondage non-anonyme
    if (!$survey->is_anonymous && $userId !== null) {
        $exists = SurveyAnswer::where('survey_id', $survey->id)
            ->where('user_id', $userId)
            ->exists();

        if ($exists) {
            return redirect()->route('surveys.index')->with('info', 'Vous avez déjà répondu à ce sondage.');
        }
    }

    $answers = $request->input('answers', []);

    DB::beginTransaction();
    try {
        $inserted = 0;

        foreach ($survey->questions as $question) {
            $raw = $answers[$question->id] ?? null;
            if ($raw === null || $raw === '') {
                // skip empty answers
                continue;
            }

            $value = is_array($raw) ? json_encode($raw) : (string)$raw;

            $answer = SurveyAnswer::create([
                'user_id' => $userId,
                'survey_question_id' => $question->id,
                'answer' => $value,
                'survey_id' => $survey->id,
            ]);

            if ($answer) $inserted++;
        }

        DB::commit();

        Log::info('submitSurvey completed', ['inserted' => $inserted]);

        if ($inserted === 0) {
            return redirect()->route('surveys.take', $survey)->with('warning', 'Aucune réponse enregistrée (rien de sélectionné).');
        }

        return redirect()->route('surveys.index')->with('success', 'Sondage répondu !');
    } catch (\Throwable $e) {
        DB::rollBack();
        Log::error('submitSurvey failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        return redirect()->route('surveys.take', $survey)->with('error', 'Une erreur est survenue lors de l’enregistrement.');
    }
}


}

