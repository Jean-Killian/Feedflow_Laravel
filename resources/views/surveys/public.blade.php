@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto mt-10">
        <h1 class="text-3xl font-bold mb-4">{{ $survey->title }}</h1>
        <p class="mb-6 text-gray-600">{{ $survey->description }}</p>

        <a href="{{ route('surveys.take', $survey) }}" class="px-4 py-2 bg-green-600 text-black rounded">
            📝 Répondre
        </a>

        <button
            onclick="copyPublicLink()"
            class="px-4 py-2 bg-blue-600 text-black rounded ml-3"
        >
            📋 Copier le lien
        </button>
    </div>
@endsection

@section('scripts')
<script>
    function copyPublicLink() {
        const url = window.location.href;

        navigator.clipboard.writeText(url)
            .then(() => alert("Lien copié !"))
            .catch(() => alert("Impossible de copier le lien."));
    }
</script>
@endsection
