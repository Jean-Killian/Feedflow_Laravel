@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-8">
    <div class="bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Ajouter une nouvelle question</h2>

        <form action="{{ route('surveys.store_question', $survey) }}" method="POST" class="space-y-5">
            @csrf

            <!-- Titre de la question -->
            <div>
                <label for="title" class="block text-gray-700 font-semibold mb-2">Titre de la question</label>
                <input type="text" name="title" id="title" placeholder="Ex: Quelle est votre couleur préférée ?"
                       class="border border-gray-300 rounded-md p-3 w-full focus:outline-none focus:ring-2 focus:ring-blue-400" required>
            </div>

            <!-- Type de question -->
            <div>
                <label for="question_type" class="block text-gray-700 font-semibold mb-2">Type de question</label>
                <select name="question_type" id="question_type"
                        class="border border-gray-300 rounded-md p-3 w-full focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="text">Texte</option>
                    <option value="radio">Choix unique</option>
                    <option value="checkbox">Choix multiple</option>
                    <option value="scale">Échelle 1-10</option>
                </select>
            </div>

            <!-- Options -->
            <div id="options-container">
                <label class="block text-gray-700 font-semibold mb-2">Options (pour radio ou checkbox, séparées par une virgule)</label>
                <input type="text" name="options" placeholder="Ex: Rouge, Bleu, Vert"
                       class="border border-gray-300 rounded-md p-3 w-full focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <!-- Bouton -->
            <button type="submit"
                    class="bg-green-500 hover:bg-green-600 text-black font-bold px-6 py-3 rounded-md shadow-md w-full transition">
                Ajouter la question
            </button>
        </form>
    </div>
</div>
@endsection