<?php

namespace App\Http\Requests\Survey;

use Illuminate\Foundation\Http\FormRequest;

class StoreSurveyQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $survey = $this->route('survey'); // si tu passes le survey via route
        return $survey && $this->user()->can('update', $survey);
    }

    public function rules(): array
    {
        return [
            'survey_id' => 'required|exists:surveys,id',
            'question' => 'required|string|max:255',
            'type' => 'required|in:single_choice,multiple_choice,text,scale',
            'data' => 'nullable|array',
        ];
    }
}
