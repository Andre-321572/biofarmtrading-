<x-guest-layout>
    <div class="mb-6 text-center">
        <h3 class="text-2xl font-black text-slate-900 tracking-tight mb-1.5">Bon retour parmi nous !</h3>
        <p class="text-xs font-semibold text-slate-400">Connectez-vous à votre espace de gestion Bio Farm</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Adresse Email')" class="mb-1.5 block font-black text-xs uppercase tracking-wider text-slate-700" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                </div>
                <x-text-input id="email" class="block w-full pl-10 rounded-2xl border-slate-200 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10 text-slate-800 text-sm font-semibold shadow-xs py-3 px-4 bg-slate-50/70 focus:bg-white transition-all" 
                            type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="exemple@biofarm.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Mot de passe')" class="mb-1.5 block font-black text-xs uppercase tracking-wider text-slate-700" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                </div>
                <x-text-input id="password" class="block w-full pl-10 rounded-2xl border-slate-200 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10 text-slate-800 text-sm font-semibold shadow-xs py-3 px-4 bg-slate-50/70 focus:bg-white transition-all"
                                type="password"
                                name="password"
                                required autocomplete="current-password" placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                <input id="remember_me" type="checkbox" class="rounded-lg border-slate-300 text-emerald-600 shadow-xs focus:ring-emerald-500 h-4 w-4" name="remember">
                <span class="ms-2 text-xs font-semibold text-slate-600">{{ __('Se souvenir de moi') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-xs text-emerald-700 hover:text-emerald-900 font-bold transition-colors" href="{{ route('password.request') }}">
                    {{ __('Mot de passe oublié ?') }}
                </a>
            @endif
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full justify-center py-3.5 bg-gradient-to-r from-emerald-600 to-teal-700 hover:from-emerald-700 hover:to-teal-800 text-white text-sm font-black rounded-2xl shadow-lg shadow-emerald-700/25 transition-all transform active:scale-[0.98] flex items-center gap-2">
                <span>{{ __('Se Connecter') }}</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </div>

        <!-- Mentions sécurisées (remplace le lien d'inscription publique supprimé) -->
        <div class="pt-4 border-t border-slate-100 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-50 text-slate-500 text-[11px] font-semibold border border-slate-200">
                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <span>Accès réservé au personnel & administrateurs Bio Farm</span>
            </div>
        </div>
    </form>
</x-guest-layout>
