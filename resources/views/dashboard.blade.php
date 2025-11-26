<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="flex">
        <!-- Main Content -->
        <div class="flex-1 py-6">
            <div class="sm:px-6 lg:px-8">
                <div class="max-w-xs">
                    <details class="bg-white overflow-hidden shadow-sm sm:rounded-lg cursor-pointer w-48">
                        <summary class="p-3 text-gray-900 font-semibold hover:bg-gray-50 transition">
                            Créer une organisation
                        </summary>
                        <div class="border-t border-gray-200 p-4">
                            <form action="{{ route('organization.store') }}" method="POST" class="space-y-3">
                                @csrf
                                <div>
                                    <label for="nom" class="block text-gray-700 font-medium mb-1">Entrez un nom</label>
                                    <input type="text" name="name" id="nom" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                </div>
                                <button type="submit" class="w-full bg-blue-600 text-gray-700 font-medium py-2 px-3 rounded-lg hover:bg-blue-700 transition">
                                    Envoyer
                                </button>
                            </form>
                        </div>
                    </details>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        @include('organizations.sidebar', ['organizations' => $organizations ?? []])
    </div>

    <div class="py-6">
        <div class="sm:px-6 lg:px-8">
            <a 
                href="{{ route('surveys.index') }}"
                class="inline-block bg-indigo-600 text-black font-medium px-4 py-2 rounded-lg hover:bg-indigo-700 transition"
            >
                Voir mes sondages
            </a>
        </div>
    </div>

</x-app-layout>