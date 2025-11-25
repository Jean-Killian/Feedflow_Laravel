@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">

    <h1 class="text-2xl font-bold mb-4">{{ $survey->title }}</h1>

    <form action="{{ route('surveys.submit', $survey) }}" method="POST">
        @csrf

        @foreach($survey->questions as $question)
    <div class="mb-6 p-4 border rounded">
        <h3 class="font-semibold">{{ $question->title }}</h3>

        @if($question->question_type === 'text')
            <textarea name="answers[{{ $question->id }}]" class="border p-2 w-full mt-2"></textarea>
        @elseif(in_array($question->question_type, ['radio', 'checkbox']))
            @foreach($question->options as $option)
                <label class="block mt-2">
                    <input type="{{ $question->question_type }}" 
                           name="answers[{{ $question->id }}]{{ $question->question_type === 'checkbox' ? '[]' : '' }}"
                           value="{{ $option }}" class="mr-2">
                    {{ $option }}
                </label>
            @endforeach
        @elseif($question->question_type === 'scale')
            <input type="range" name="answers[{{ $question->id }}]" min="1" max="10">
        @endif
    </div>
@endforeach


        <button class="bg-blue-500 text-black px-4 py-2 rounded">
            Envoyer mes réponses
        </button>
    </form>

</div>
@endsection
