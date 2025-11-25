@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Mes sondages</h1>

    <a href="{{ route('surveys.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">
        Créer un nouveau sondage
    </a>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($surveys->isEmpty())
        <p>Aucun sondage trouvé.</p>
    @else
        <table class="w-full border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2 border">Titre</th>
                    <th class="p-2 border">Début</th>
                    <th class="p-2 border">Fin</th>
                    <th class="p-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($surveys as $survey)
                    <tr>
                        <td class="p-2 border">{{ $survey->title }}</td>
                        <td class="p-2 border">{{ $survey->start_date }}</td>
                        <td class="p-2 border">{{ $survey->end_date }}</td>
                        <td class="p-2 border flex gap-2">
                            <a href="{{ route('surveys.add_question', $survey) }}" class="text-blue-500">ajouter une question</a>
                            <a href="{{ route('surveys.edit', $survey) }}" class="text-yellow-500">Modifier</a>
                            <form action="{{ route('surveys.destroy', $survey) }}" method="POST" onsubmit="return confirm('Supprimer ce sondage ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
