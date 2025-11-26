@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">📊 Liste des sondages</h1>

        <a href="{{ route('surveys.create', ['organization_id' => request('organization_id')]) }}"
           class="bg-green-600 text-black px-4 py-2 rounded hover:bg-green-700">
            ➕ Nouveau sondage
        </a>
    </div>

    @foreach($surveys as $survey)
        @php
            // Vérifie si l'utilisateur connecté a déjà répondu
            $hasAnswered = $survey->answers->where('user_id', auth()->id())->count() > 0;
        @endphp

        <div class="shadow-md rounded-lg p-6 mb-5 border {{ $hasAnswered ? 'bg-green-100' : 'bg-white' }}">

            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-semibold">{{ $survey->title }}</h2>
                    <p class="text-gray-500 text-sm">
                        Créé le : {{ $survey->created_at->format('d/m/Y') }}
                    </p>
                </div>

                <span class="px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded">
                    Organisation #{{ $survey->organization_id }}
                </span>
            </div>

            <p class="mt-3 text-gray-700">
                {{ Str::limit($survey->description, 120) }}
            </p>

            <div class="mt-5 flex gap-3 flex-wrap">

                {{-- Bouton Répondre --}}
                @if(!$survey->hasAnswered)
                    <a href="{{ route('surveys.take', $survey) }}"
                       class="px-4 py-2 bg-indigo-600 text-black rounded hover:bg-indigo-700">
                        📝 Répondre
                    </a>
                @else
                    <span class="px-4 py-2 bg-gray-400 text-black rounded">
                        ✅ Déjà répondu
                    </span>
                @endif

                {{-- Ajouter des questions --}}
                @can('addQuestion', $survey)
                    <a href="{{ route('surveys.add_question', $survey) }}"
                       class="px-4 py-2 bg-purple-600 text-black rounded hover:bg-purple-700">
                        ➕ Ajouter question
                    </a>
                @endcan

                {{-- Modifier toutes les questions (un seul bouton) --}}
                @if($survey->questions && $survey->questions->count())
                    @can('update', $survey)
                        <a href="{{ route('surveys.questions.edit_question', $survey) }}"
                           class="px-4 py-2 bg-purple-700 text-black rounded hover:bg-purple-800">
                            🛠 Modifier les questions
                        </a>
                    @endcan
                @endif

                {{-- Modifier le sondage --}}
                @can('update', $survey)
                    <a href="{{ route('surveys.edit', $survey) }}"
                       class="px-4 py-2 bg-yellow-500 text-black rounded hover:bg-yellow-600">
                        ✏️ Modifier
                    </a>
                @endcan

                {{-- Supprimer --}}
                @can('delete', $survey)
                    <form action="{{ route('surveys.destroy', $survey) }}" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button class="px-4 py-2 bg-red-600 text-black rounded hover:bg-red-700"
                                onclick="return confirm('Supprimer ce sondage ?')">
                            🗑 Supprimer
                        </button>
                    </form>
                @endcan

                @php
                    $token = Crypt::encryptString($survey->id);
                @endphp

                <a href="{{ route('surveys.public', $token) }}"
                   class="px-4 py-2 bg-blue-600 text-black rounded hover:bg-blue-700">
                    🔗 Partager
                </a>
            </div>
        </div>
    @endforeach
</div>
@endsection
