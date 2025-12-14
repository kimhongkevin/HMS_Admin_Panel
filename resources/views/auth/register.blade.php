<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Admin Authorization Section -->
        <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="mb-4">
                <p class="text-sm font-semibold text-gray-700">{{ __('Admin Authorization Required') }}</p>
                <p class="text-xs text-gray-600 mt-1">{{ __('Contact your administrator to authorize this registration.') }}</p>
            </div>

            <!-- Admin Email -->
            <div class="mt-4">
                <x-input-label for="admin_email" :value="__('Admin Email')" />
                <x-text-input id="admin_email" class="block mt-1 w-full"
                                type="email"
                                name="admin_email"
                                :value="old('admin_email')"
                                required
                                placeholder="admin@example.com" />
                <x-input-error :messages="$errors->get('admin_email')" class="mt-2" />
            </div>

            <!-- Admin Password -->
            <div class="mt-4">
                <x-input-label for="admin_password" :value="__('Admin Password')" />
                <x-text-input id="admin_password" class="block mt-1 w-full"
                                type="password"
                                name="admin_password"
                                required
                                placeholder="Enter admin's password" />
                <x-input-error :messages="$errors->get('admin_password')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
