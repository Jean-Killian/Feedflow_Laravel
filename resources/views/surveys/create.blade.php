@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Créer un nouveau sondage</h1>

    <form action="{{ route('surveys.store') }}" method="POST">
        @csrf
        <input type="hidden" name="organization_id" value="{{ request('organization_id') }}">
        <div class="mb-4">
            <label for="title" class="block font-medium">Titre</label>
            <input type="text" name="title" id="title" class="border p-2 w-full" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block font-medium">Description</label>
            <textarea name="description" id="description" class="border p-2 w-full"></textarea>
        </div>
        <div class="mb-4">
            <label for="start_date" class="block font-medium">Date de début</label>
            <input type="date" name="start_date" id="start_date" class="border p-2 w-full" required>
        </div>
        <div class="mb-4">
            <label for="end_date" class="block font-medium">Date de fin</label>
            <input type="date" name="end_date" id="end_date" class="border p-2 w-full" required>
        </div>
        <div class="mb-4">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_anonymous" value="1">
                Sondage anonyme
            </label>

        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Créer</button>
    </form>
</div>
@endsection
