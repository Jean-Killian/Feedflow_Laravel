<form action="{{ route('surveys.questions.store', $survey) }}" method="POST">
    @csrf
    <div>
        <label>Question :</label>
        <input type="text" name="question" required>
    </div>
    <div>
        <label>Type :</label>
        <select name="type" required>
            <option value="single_choice">Choix unique</option>
            <option value="multiple_choice">Choix multiple</option>
            <option value="text">Texte</option>
            <option value="scale">Échelle 1-10</option>
        </select>
    </div>
    <div>
        <label>Options (pour choix multiple, séparées par une virgule) :</label>
        <input type="text" name="data">
    </div>
    <button type="submit">Ajouter la question</button>
</form>
