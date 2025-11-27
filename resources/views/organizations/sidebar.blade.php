<div class="w-64 h-screen sticky top-0 bg-gradient-to-b from-indigo-50 to-white border-r border-gray-200 shadow-lg">
    <div class="h-full flex flex-col">
        <!-- Header -->
        <div class="px-6 py-8 border-b border-gray-200 bg-white">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                Organisations
            </h3>
        </div>
        
        <!-- Organizations List -->
        <div class="flex-1 overflow-y-auto px-4 py-4">
            <ul class="space-y-2">
                @forelse($organizations ?? [] as $org)
                    <li>
                        <a href="{{ route('surveys.index', ['organization_id' => $org->id]) }}" 
                           class="group flex items-center px-4 py-3 rounded-lg hover:bg-indigo-100 hover:shadow-md transition-all duration-200 ease-in-out text-sm font-medium text-gray-700 hover:text-indigo-700 border border-transparent hover:border-indigo-200">
                            <svg class="w-4 h-4 mr-3 text-gray-400 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span class="flex-1 truncate">{{ $org->name }}</span>
                        </a>
                    </li>
                @empty
                    <li class="px-4 py-8 text-center">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p class="text-sm text-gray-500 font-medium">Aucune organisation</p>
                        <p class="text-xs text-gray-400 mt-1">Créez votre première organisation</p>
                    </li>
                @endforelse
            </ul>
        </div>
        
        <!-- Footer -->
        <div class="px-6 py-4 border-t border-gray-200 bg-white">
            <p class="text-xs text-gray-500 text-center">
                {{ count($organizations ?? []) }} organisation{{ count($organizations ?? []) > 1 ? 's' : '' }}
            </p>
        </div>
    </div>
</div>