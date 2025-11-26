@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10">
    <h1 class="text-3xl font-bold mb-6">✏️ Modifier les questions du sondage : {{ $survey->title }}</h1>

    <form action="{{ route('surveys.questions.update', $survey) }}" method="POST">
        @csrf
        @method('PUT')

        @foreach($questions as $question)
            <div class="mb-6 p-4 border rounded bg-gray-50">
                <label class="font-semibold">Question #{{ $loop->iteration }}</label>
                <input type="text"
                       name="questions[{{ $question->id }}][title]"
                       value="{{ $question->title }}"
                       class="border p-2 w-full mt-2"
                       required>

                <label class="mt-2 block">Type</label>
                <select name="questions[{{ $question->id }}][question_type]" class="border p-2 w-full mt-1">
                    <option value="text" {{ $question->question_type=='text'?'selected':'' }}>Texte</option>
                    <option value="radio" {{ $question->question_type=='radio'?'selected':'' }}>Choix unique</option>
                    <option value="checkbox" {{ $question->question_type=='checkbox'?'selected':'' }}>Choix multiple</option>
                    <option value="scale" {{ $question->question_type=='scale'?'selected':'' }}>Échelle 1-10</option>
                </select>

                <label class="mt-2 block">Options (pour radio/checkbox, séparées par une virgule)</label>
                <input type="text"
                       name="questions[{{ $question->id }}][options]"
                       value="{{ is_array($question->options) ? implode(',', $question->options) : $question->options }}"
                       class="border p-2 w-full mt-1">
            </div>
        @endforeach

        <button type="submit" class="bg-blue-500 text-black px-4 py-2 rounded mt-4">
            Sauvegarder toutes les questions
        </button>
    </form>
</div>
@endsection
