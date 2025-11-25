<div class="fixed left-4 top-20 h-[calc(100vh-5rem)] w-56 bg-white shadow sm:rounded-lg overflow-y-auto p-3 z-40">
    <h3 class="text-sm font-semibold mb-2">Organisations</h3>
    <ul class="space-y-1">
        @forelse($organizations ?? [] as $org)
            <li>
                <a href="{{ url('organizations/'.$org->id) }}" class="block px-2 py-2 rounded hover:bg-gray-100 text-sm">{{ $org->name }}</a>
            </li>
        @empty
            <li class="text-sm text-gray-500">Aucune organisation</li>
        @endforelse
    </ul>
</div>
