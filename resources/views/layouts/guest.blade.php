<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Bio Farm Trading') }} - Connexion</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body { font-family: 'Outfit', sans-serif; }

            /* Keyframes pour l'animation des 4 fruits Bio Farm */
            @keyframes float-ananas {
                0%, 100% {
                    transform: translateY(0px) rotate(-3deg) scale(1);
                }
                50% {
                    transform: translateY(-20px) rotate(4deg) scale(1.04);
                }
            }

            @keyframes float-papaye {
                0%, 100% {
                    transform: translateY(0px) rotate(4deg) scale(1);
                }
                50% {
                    transform: translateY(-18px) rotate(-3deg) scale(1.03);
                }
            }

            @keyframes float-mangue {
                0%, 100% {
                    transform: translateY(0px) rotate(-4deg) scale(1);
                }
                50% {
                    transform: translateY(-22px) rotate(3deg) scale(1.04);
                }
            }

            @keyframes float-banane {
                0%, 100% {
                    transform: translateY(0px) rotate(4deg) scale(1);
                }
                50% {
                    transform: translateY(-19px) rotate(-4deg) scale(1.03);
                }
            }

            @keyframes float-ambient {
                0%, 100% {
                    transform: translateY(0px) translateX(0px) rotate(0deg);
                }
                50% {
                    transform: translateY(-15px) translateX(10px) rotate(8deg);
                }
            }

            @keyframes soft-pulse {
                0%, 100% { opacity: 0.35; transform: scale(1); }
                50% { opacity: 0.6; transform: scale(1.08); }
            }

            .anim-ananas {
                animation: float-ananas 6s ease-in-out infinite;
            }

            .anim-papaye {
                animation: float-papaye 6.5s ease-in-out infinite 0.7s;
            }

            .anim-mangue {
                animation: float-mangue 7s ease-in-out infinite 1.2s;
            }

            .anim-banane {
                animation: float-banane 6.2s ease-in-out infinite 1.8s;
            }

            .anim-ambient-1 {
                animation: float-ambient 9s ease-in-out infinite;
            }

            .anim-ambient-2 {
                animation: float-ambient 11s ease-in-out infinite 2s;
            }

            .fruit-card {
                transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            }
            .fruit-card:hover {
                transform: scale(1.12) rotate(0deg) !important;
                filter: drop-shadow(0 20px 25px rgba(16, 185, 129, 0.25));
                z-index: 30;
            }
        </style>
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-800 antialiased overflow-x-hidden selection:bg-emerald-500 selection:text-white relative">
        
        <!-- Fond Naturel & Doux Bio Farm -->
        <div class="fixed inset-0 pointer-events-none z-0">
            <!-- Dégradé organique subtil -->
            <div class="absolute inset-0 bg-gradient-to-br from-amber-50/80 via-emerald-50/50 to-teal-50/70"></div>
            
            <!-- Halos lumineux doux -->
            <div class="absolute top-10 left-10 w-[500px] h-[500px] bg-emerald-200/30 rounded-full blur-3xl pointer-events-none" style="animation: soft-pulse 8s infinite ease-in-out;"></div>
            <div class="absolute bottom-10 right-10 w-[500px] h-[500px] bg-amber-200/30 rounded-full blur-3xl pointer-events-none" style="animation: soft-pulse 9s infinite ease-in-out 3s;"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] bg-emerald-100/20 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Petites particules flottantes ambiantes (Feuilles et étincelles de soleil) -->
            <div class="absolute top-[18%] left-[22%] anim-ambient-1 opacity-40 hidden md:block">
                <svg class="w-6 h-6 text-emerald-500" fill="currentColor" viewBox="0 0 24 24"><path d="M17 8C8 10 5.9 16.17 3.82 21.34L5.71 22l1-2.3A4.49 4.49 0 008 20C19 20 22 3 22 3c-1 2-8 2.25-13 3.25S2 11.5 2 13.5s1.75 3.75 1.75 3.75C7 8 17 8 17 8z"/></svg>
            </div>
            <div class="absolute bottom-[20%] right-[24%] anim-ambient-2 opacity-40 hidden md:block">
                <svg class="w-7 h-7 text-amber-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l2.4 7.4h7.6l-6.2 4.5 2.4 7.4-6.2-4.5-6.2 4.5 2.4-7.4-6.2-4.5h7.6z"/></svg>
            </div>
            <div class="absolute top-[65%] left-[18%] anim-ambient-1 opacity-30 hidden md:block">
                <svg class="w-5 h-5 text-teal-600" fill="currentColor" viewBox="0 0 24 24"><path d="M17 8C8 10 5.9 16.17 3.82 21.34L5.71 22l1-2.3A4.49 4.49 0 008 20C19 20 22 3 22 3c-1 2-8 2.25-13 3.25S2 11.5 2 13.5s1.75 3.75 1.75 3.75C7 8 17 8 17 8z"/></svg>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- ANIMATIONS DES 4 FRUITS TROPICAUX (ANANAS, PAPAYE, MANGUE, BANANE)         -->
        <!-- ========================================================================= -->
        
        <!-- 1. ANANAS (Haut Gauche) -->
        <div class="fixed top-8 sm:top-14 left-4 sm:left-12 lg:left-24 z-10 anim-ananas fruit-card cursor-pointer select-none">
            <div class="flex items-center gap-3 bg-white/90 backdrop-blur-md p-3 sm:p-4 rounded-3xl border border-amber-200/90 shadow-xl shadow-amber-900/10">
                <div class="w-16 h-16 sm:w-20 sm:h-20 flex items-center justify-center bg-white rounded-2xl p-1 shadow-inner flex-shrink-0 overflow-hidden border border-amber-100">
                    <img src="{{ asset('images/fruits/ananas.png') }}" alt="Ananas Bio" class="w-full h-full object-contain mix-blend-multiply drop-shadow-sm">
                </div>
                <div class="hidden sm:block pr-2">
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-amber-100 text-amber-800">
                        100% Biologique
                    </span>
                    <h4 class="text-xs font-black text-slate-800 uppercase tracking-tight mt-1">Ananas Bio</h4>
                    <p class="text-[10px] font-semibold text-slate-400">Cayenne Lisse & Pain de Sucre</p>
                </div>
            </div>
        </div>

        <!-- 2. PAPAYE (Haut Droite) -->
        <div class="fixed top-8 sm:top-14 right-4 sm:right-12 lg:right-24 z-10 anim-papaye fruit-card cursor-pointer select-none">
            <div class="flex items-center gap-3 bg-white/90 backdrop-blur-md p-3 sm:p-4 rounded-3xl border border-orange-200/90 shadow-xl shadow-orange-900/10">
                <div class="w-16 h-16 sm:w-20 sm:h-20 flex items-center justify-center bg-white rounded-2xl p-1 shadow-inner flex-shrink-0 overflow-hidden border border-orange-100">
                    <img src="{{ asset('images/fruits/papaye.png') }}" alt="Papaye Solo Bio" class="w-full h-full object-contain mix-blend-multiply drop-shadow-sm">
                </div>
                <div class="hidden sm:block pr-2">
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-orange-100 text-orange-800">
                        Qualité Premium
                    </span>
                    <h4 class="text-xs font-black text-slate-800 uppercase tracking-tight mt-1">Papaye Solo</h4>
                    <p class="text-[10px] font-semibold text-slate-400">Pulpe riche & fruitée</p>
                </div>
            </div>
        </div>

        <!-- 3. MANGUE (Bas Gauche) -->
        <div class="fixed bottom-8 sm:bottom-12 left-4 sm:left-12 lg:left-24 z-10 anim-mangue fruit-card cursor-pointer select-none">
            <div class="flex items-center gap-3 bg-white/90 backdrop-blur-md p-3 sm:p-4 rounded-3xl border border-rose-200/90 shadow-xl shadow-rose-900/10">
                <div class="w-16 h-16 sm:w-20 sm:h-20 flex items-center justify-center bg-white rounded-2xl p-1 shadow-inner flex-shrink-0 overflow-hidden border border-rose-100">
                    <img src="{{ asset('images/fruits/mangue.png') }}" alt="Mangue Kent Bio" class="w-full h-full object-contain mix-blend-multiply drop-shadow-sm">
                </div>
                <div class="hidden sm:block pr-2">
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-rose-100 text-rose-800">
                        Variété Solaire
                    </span>
                    <h4 class="text-xs font-black text-slate-800 uppercase tracking-tight mt-1">Mangue Kent</h4>
                    <p class="text-[10px] font-semibold text-slate-400">Saveur parfumée & douce</p>
                </div>
            </div>
        </div>

        <!-- 4. BANANE (Bas Droite) -->
        <div class="fixed bottom-8 sm:bottom-12 right-4 sm:right-12 lg:right-24 z-10 anim-banane fruit-card cursor-pointer select-none">
            <div class="flex items-center gap-3 bg-white/90 backdrop-blur-md p-3 sm:p-4 rounded-3xl border border-yellow-200/90 shadow-xl shadow-yellow-900/10">
                <div class="w-16 h-16 sm:w-20 sm:h-20 flex items-center justify-center bg-white rounded-2xl p-1 shadow-inner flex-shrink-0 overflow-hidden border border-yellow-100">
                    <img src="{{ asset('images/fruits/banane.png') }}" alt="Banane Douce Bio" class="w-full h-full object-contain mix-blend-multiply drop-shadow-sm">
                </div>
                <div class="hidden sm:block pr-2">
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-yellow-100 text-yellow-800">
                        Douceur Naturelle
                    </span>
                    <h4 class="text-xs font-black text-slate-800 uppercase tracking-tight mt-1">Banane Douce</h4>
                    <p class="text-[10px] font-semibold text-slate-400">Énergie 100% naturelle</p>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- SECTION CENTRALE : CARTE DE CONNEXION ÉLÉGANTE                            -->
        <!-- ========================================================================= -->
        <main class="min-h-screen flex flex-col items-center justify-center p-4 sm:p-6 relative z-20">
            <div class="w-full max-w-md my-auto">
                
                <!-- Carte Blanche Moderne Glassmorphic -->
                <div class="bg-white/95 backdrop-blur-xl p-8 sm:p-10 rounded-[2.5rem] shadow-2xl shadow-emerald-950/10 border border-slate-200/90 relative overflow-hidden">
                    
                    <!-- Ligne d'accent supérieure verte Bio Farm -->
                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-emerald-500 via-teal-500 to-amber-400"></div>

                    <!-- Logo de l'entreprise -->
                    <div class="flex flex-col items-center justify-center mb-6 text-center">
                        <div class="p-2.5 rounded-2xl bg-slate-50 border border-slate-100 shadow-inner mb-3 transition-transform hover:scale-105 duration-300">
                            <x-application-logo class="w-24 sm:w-28 h-auto object-contain rounded-xl" />
                        </div>
                        <h1 class="text-xl font-black text-slate-900 tracking-tight">Bio Farm Trading</h1>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-700 mt-0.5">Agriculture Biologique &bull; Togo</p>
                    </div>

                    <!-- Formulaire de connexion -->
                    {{ $slot }}

                </div>

                <!-- Footer Copyright & Mentions Légales -->
                <div class="text-center mt-6 text-xs font-semibold text-slate-400 select-none">
                    &copy; {{ date('Y') }} Bio Farm Trading SARL. Tous droits réservés.
                </div>

            </div>
        </main>

    </body>
</html>
