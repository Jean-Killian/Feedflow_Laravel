@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Modifier le sondage</h1>

    <form action="{{ route('surveys.update', $survey) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="title" class="block font-medium">Titre</label>
            <input type="text" name="title" id="title" 
                   class="border p-2 w-full"
                   value="{{ old('title', $survey->title) }}" required>
        </div>

        <div class="mb-4">
            <label for="description" class="block font-medium">Description</label>
            <textarea name="description" id="description" 
                      class="border p-2 w-full">{{ old('description', $survey->description) }}</textarea>
        </div>

        <div class="mb-4">
            <label for="start_date" class="block font-medium">Date de début</label>
            <input type="date" name="start_date" id="start_date" 
                   class="border p-2 w-full"
                   value="{{ old('start_date', $survey->start_date) }}" required>
        </div>

        <div class="mb-4">
            <label for="end_date" class="block font-medium">Date de fin</label>
            <input type="date" name="end_date" id="end_date" 
                   class="border p-2 w-full"
                   value="{{ old('end_date', $survey->end_date) }}" required>
        </div>

        <div class="mb-4">
            <label class="inline-flex items-center">
                <input type="checkbox" 
                       name="is_anonymous" 
                       class="form-checkbox"
                       {{ old('is_anonymous', $survey->is_anonymous) ? 'checked' : '' }}>
                <span class="ml-2">Sondage anonyme</span>
            </label>
        </div>

        <button type="submit" 
                class="bg-blue-500 text-white px-4 py-2 rounded">
            Enregistrer les modifications
        </button>
    </form>
</div>
@endsection
