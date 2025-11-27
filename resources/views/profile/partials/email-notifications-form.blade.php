<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Email Notifications') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Manage your email notification preferences.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update-notifications') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <div class="flex items-center">
                <input 
                    type="checkbox" 
                    name="email_notifications_enabled" 
                    id="email_notifications_enabled"
                    value="1"
                    {{ old('email_notifications_enabled', $user->email_notifications_enabled) ? 'checked' : '' }}
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                >
                <label for="email_notifications_enabled" class="ml-2 text-sm text-gray-700">
                    {{ __(' Receive an email for each new answer on my surveys') }}
                </label>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'notifications-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
