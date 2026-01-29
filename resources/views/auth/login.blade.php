<x-guest-layout>
    <div class="mb-8 text-center">
        <h3 class="text-2xl font-bold text-gray-900 mb-2">Bon retour parmi nous !</h3>
        <p class="text-gray-500">Connectez-vous à votre compte</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Adresse Email')" class="mb-2 block font-bold text-gray-700" />
            <x-text-input id="email" class="block w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm py-3 px-4" 
                        type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="exemple@biofarm.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Mot de passe')" class="mb-2 block font-bold text-gray-700" />

            <x-text-input id="password" class="block w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm py-3 px-4"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500 h-5 w-5" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Se souvenir de moi') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="underline text-sm text-green-600 hover:text-green-800 font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" href="{{ route('password.request') }}">
                    {{ __('Mot de passe oublié ?') }}
                </a>
            @endif
        </div>

        <div class="mt-8">
            <x-primary-button class="w-full justify-center py-4 bg-green-600 hover:bg-green-700 text-lg rounded-xl shadow-lg shadow-green-200 transition-all font-bold tracking-wide">
                {{ __('Se Connecter') }}
            </x-primary-button>
        </div>
        
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-500">
                Pas encore de compte ? 
                <a href="{{ route('register') }}" class="text-green-600 font-bold hover:text-green-800 transition-colors">Créer un compte</a>
            </p>
        </div>
    </form>
</x-guest-layout>
