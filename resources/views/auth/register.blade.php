<x-guest-layout>
    <div class="mb-8 text-center">
        <h3 class="text-2xl font-bold text-gray-900 mb-2">Rejoignez la famille Bio Farm !</h3>
        <p class="text-gray-500">Créez votre compte en quelques secondes</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nom Complet')" class="mb-2 block font-bold text-gray-700" />
            <x-text-input id="name" class="block w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm py-3 px-4" 
                        type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="John Doe" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Adresse Email')" class="mb-2 block font-bold text-gray-700" />
            <x-text-input id="email" class="block w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm py-3 px-4" 
                        type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="exemple@biofarm.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Mot de passe')" class="mb-2 block font-bold text-gray-700" />

            <x-text-input id="password" class="block w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm py-3 px-4"
                            type="password"
                            name="password"
                            required autocomplete="new-password" placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" class="mb-2 block font-bold text-gray-700" />

            <x-text-input id="password_confirmation" class="block w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm py-3 px-4"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-8">
            <x-primary-button class="w-full justify-center py-4 bg-green-600 hover:bg-green-700 text-lg rounded-xl shadow-lg shadow-green-200 transition-all font-bold tracking-wide">
                {{ __('Créer mon compte') }}
            </x-primary-button>
        </div>
        
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-500">
                Déjà inscrit ? 
                <a href="{{ route('login') }}" class="text-green-600 font-bold hover:text-green-800 transition-colors">Se connecter</a>
            </p>
        </div>
    </form>
</x-guest-layout>
