<form action="{{ route('surveys.store_question', $survey) }}" method="POST">
    @csrf

    <div class="mb-4">
        <label for="title">Titre de la question</label>
        <input type="text" name="title" id="title" class="border p-2 w-full" required>
    </div>

    <div class="mb-4">
        <label for="question_type">Type de question</label>
        <select name="question_type" id="question_type" class="border p-2 w-full">
            <option value="text">Texte</option>
            <option value="radio">Choix unique</option>
            <option value="checkbox">Choix multiple</option>
            <option value="scale">Échelle 1-10</option>
        </select>
    </div>

    <div class="mb-4" id="options-container">
        <label>Options (pour radio ou checkbox, séparées par une virgule)</label>
        <input type="text" name="options" class="border p-2 w-full">
    </div>

    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Ajouter</button>
</form>
