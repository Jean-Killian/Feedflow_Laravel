@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">

    <h1 class="text-3xl font-bold mb-6">{{ $survey->title }}</h1>

    {{-- BOUTON POUR RÉPONDRE AU SONDAGE --}}
    <div class="mb-6">
        <a href="{{ route('surveys.take', $survey) }}"
           class="bg-green-600 text-white px-4 py-2 rounded shadow">
            ➤ Répondre au sondage
        </a>
    </div>

    {{-- LISTE DES QUESTIONS --}}
    <div class="space-y-4">
        @foreach($survey->questions as $question)
            <div class="p-4 border rounded bg-gray-50">
                <h3 class="font-semibold">{{ $question->title }}</h3>

                @if($question->question_type === 'multiple_choice')
                    <p class="text-sm text-gray-600 mt-2">Type : QCM</p>
                    <ul class="list-disc ml-6 mt-2">
                        @foreach($question->options as $option)
                            <li>{{ $option }}</li>
                        @endforeach
                    </ul>
                @endif

                @if($question->question_type === 'text')
                    <p class="text-sm text-gray-600 mt-2">Type : Réponse écrite</p>
                @endif
            </div>
        @endforeach
    </div>

    {{-- RETOUR --}}
    <div class="mt-8">
        <a href="{{ route('surveys.index') }}"
           class="text-blue-600 underline">
            ← Retour à la liste des sondages
        </a>
    </div>

</div>
@endsection
