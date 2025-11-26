<div class="w-64 py-6 pr-4">
    <div class="bg-white shadow sm:rounded-lg overflow-y-auto p-3 sticky top-20 max-h-[calc(100vh-8rem)]">
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
</div>