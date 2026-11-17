<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Bio Farm Trading - Fruits & Jus 100% Bio du Togo')</title>
    <meta name="description" content="Leader dans la transformation et la commercialisation de fruits séchés et purs jus d'ananas 100% Bio au Togo. Certifié ECOCERT & HACCP.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- PWA Support -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#059669">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Bio Farm Trading">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/pwa_icon.png') }}">
    <link rel="apple-touch-icon" href="/images/pwa_icon.png">

    <!-- Core Scripts -->
    <script src="{{ asset('js/alpine.js') }}" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass-header {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }
        .btn-premium {
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 20px -5px rgba(5, 150, 105, 0.25);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-slate-50 text-slate-800 antialiased selection:bg-emerald-500 selection:text-white">

    <!-- Top Announcement Bar (Trust Banner) -->
    <aside aria-label="Bannière d'information" class="bg-gradient-to-r from-emerald-950 via-teal-950 to-slate-900 text-emerald-100 text-[11px] font-bold py-2 px-4 text-center tracking-wide flex items-center justify-center gap-2 select-none border-b border-emerald-800/40">
        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 text-[10px] uppercase font-black tracking-wider">
            Terroir du Togo
        </span>
        <span>Produits 100% Bio Certifiés ECOCERT &bull; Zéro Conservateur &bull; Expédition & Boutiques à Lomé</span>
    </aside>

    <!-- Top Navigation Sticky -->
    <nav class="sticky top-0 z-50 glass-header shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center gap-2 group">
                        <img src="{{ asset('images/joossi_kinu_final.png') }}" alt="Bio Farm - Joossi & Kinu" class="h-14 sm:h-16 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden sm:flex sm:items-center sm:space-x-8">
                    <a href="{{ route('home') }}" class="text-sm font-bold text-slate-700 hover:text-emerald-700 px-3 py-2 rounded-xl hover:bg-emerald-50/60 transition-all flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                        <span>Nos Produits</span>
                    </a>

                    <a href="{{ route('home') }}#boutiques" class="text-sm font-bold text-slate-700 hover:text-emerald-700 px-3 py-2 rounded-xl hover:bg-emerald-50/60 transition-all flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        <span>Boutiques Physiques</span>
                    </a>

                    <a href="{{ route('contact.index') }}" class="text-sm font-bold text-slate-700 hover:text-emerald-700 px-3 py-2 rounded-xl hover:bg-emerald-50/60 transition-all flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        <span>Contact</span>
                    </a>
                    
                    <!-- Cart Button -->
                    <a href="{{ route('cart.index') }}" class="relative p-2.5 rounded-2xl bg-slate-100 hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-all border border-slate-200 hover:border-emerald-200 flex items-center gap-2 group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="text-xs font-black uppercase tracking-wider pr-1">Panier</span>
                        @if(session('cart') && count(session('cart')) > 0)
                            <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-black leading-none text-white bg-emerald-600 rounded-full shadow-sm animate-pulse">
                                {{ count(session('cart')) }}
                            </span>
                        @endif
                    </a>

                    <!-- User Account / Login Button -->
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-2xl bg-emerald-700 hover:bg-emerald-800 text-white font-black text-xs shadow-md shadow-emerald-700/20 transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            <span>Espace Pro / Compte</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-black text-xs shadow-md transition-all flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                            <span>Connexion</span>
                        </a>
                    @endauth
                </div>

                <!-- Mobile Cart Icon -->
                <div class="flex sm:hidden items-center gap-3">
                    <a href="{{ route('cart.index') }}" class="relative p-2.5 rounded-2xl bg-emerald-50 text-emerald-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        @if(session('cart') && count(session('cart')) > 0)
                            <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-black text-white bg-rose-500 rounded-full shadow-sm">
                                {{ count(session('cart')) }}
                            </span>
                        @endif
                    </a>
                </div>

            </div>
        </div>
    </nav>

    <!-- Main Page Content -->
    <main class="flex-grow">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl shadow-xs flex items-center gap-3 text-sm font-bold">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl shadow-xs flex items-center gap-3 text-sm font-bold">
                    <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer Ligne Claire & Élégante -->
    <footer class="bg-gradient-to-b from-white via-slate-50 to-slate-100/70 text-slate-600 border-t border-slate-200 pt-16 pb-12 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 pb-12 border-b border-slate-200">
                
                <!-- Col 1: Bio Farm Description -->
                <div class="col-span-1 md:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-2xl bg-white border border-slate-200/80 shadow-xs inline-block">
                            <img src="{{ asset('images/joossi_kinu_final.png') }}" alt="Joossi x Kinu Logo" class="h-14 w-auto object-contain">
                        </div>
                    </div>
                    <p class="text-slate-600 text-sm max-w-md leading-relaxed font-normal">
                        <strong class="text-slate-900">Bio Farm Trading SARL</strong> est le pionnier togolais de la transformation fruitière 100% Biologique. À travers nos marques officielles <strong class="text-slate-900">JOOSSI</strong> (purs jus naturels) et <strong class="text-slate-900">KINU</strong> (fruits séchés au soleil), nous valorisons l'excellence du terroir togolais sans aucun conservateur ni sucre ajouté.
                    </p>
                    <div class="flex flex-wrap items-center gap-2.5 pt-1">
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                            Certifié ECOCERT
                        </span>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                            Normes HACCP
                        </span>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-teal-50 text-teal-800 border border-teal-200">
                            Terroir du Togo
                        </span>
                    </div>
                </div>

                <!-- Col 2: Nos Boutiques Physiques -->
                <div class="space-y-4">
                    <h3 class="font-black text-slate-900 text-sm uppercase tracking-wider">Nos Boutiques à Lomé</h3>
                    <ul class="space-y-3.5 text-xs">
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <div>
                                <strong class="text-slate-900 font-bold block">Boutique Cacaveli</strong>
                                <span class="text-slate-500">Face carrefour principal, Lomé</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-teal-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <div>
                                <strong class="text-slate-900 font-bold block">Boutique Hedzranawoe</strong>
                                <span class="text-slate-500">Marché moderne de Hedzranawoe</span>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Col 3: Contact & Commandes -->
                <div class="space-y-4">
                    <h3 class="font-black text-slate-900 text-sm uppercase tracking-wider">Commandes & Support</h3>
                    <ul class="space-y-3 text-xs">
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <span class="text-slate-900 font-bold">+228 90 00 00 01 / 02</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <a href="mailto:contact@biofarmtrading.com" class="text-slate-700 hover:text-emerald-700 transition-colors">contact@biofarmtrading.com</a>
                        </li>
                        <li class="pt-1.5">
                            <a href="{{ route('contact.index') }}" class="inline-block px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-xs transition-colors">
                                Formulaire de Contact & Devis
                            </a>
                        </li>
                    </ul>
                </div>

            </div>

            <!-- Copyright Bar -->
            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} Bio Farm Trading SARL &bull; Marques JOOSSI & KINU. Tous droits réservés.</p>
                <div class="flex items-center gap-6">
                    <a href="{{ route('home') }}" class="text-slate-600 hover:text-emerald-700 font-medium transition-colors">Accueil</a>
                    <a href="{{ route('home') }}#catalogue" class="text-slate-600 hover:text-emerald-700 font-medium transition-colors">Catalogue</a>
                    <a href="{{ route('contact.index') }}" class="text-slate-600 hover:text-emerald-700 font-medium transition-colors">Contact</a>
                    <a href="{{ route('login') }}" class="text-slate-600 hover:text-emerald-700 font-medium transition-colors">Espace Pro</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Floating Action Button & Modal -->
    <div x-data="{ open: false }" class="fixed bottom-6 right-6 z-50 flex flex-col items-end">
        <!-- Modal -->
        <div x-show="open" 
             @click.away="open = false" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 scale-95"
             class="mb-4 w-80 bg-white rounded-3xl shadow-2xl border border-slate-200 overflow-hidden text-slate-900" 
             style="display: none;">
             
             <div class="bg-gradient-to-r from-emerald-800 to-teal-900 p-4 text-white flex justify-between items-center">
                 <div>
                    <h4 class="font-black text-sm uppercase tracking-wide">Commander via WhatsApp</h4>
                    <p class="text-[11px] text-emerald-200">Choisissez votre boutique à Lomé</p>
                 </div>
                 <button @click="open = false" class="text-emerald-200 hover:text-white p-1 rounded-lg">
                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                 </button>
            </div>
            
            <div class="p-3 space-y-2">
                <a href="https://wa.me/22890000001?text=Bonjour%20Bio%20Farm%20Cacaveli%2C%20je%20souhaite%20commander%20des%20produits%20JOOSSI%20%2F%20KINU." target="_blank" class="flex items-center space-x-3 p-3 hover:bg-emerald-50 rounded-2xl transition-all group border border-slate-100 hover:border-emerald-200">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white flex-shrink-0 flex items-center justify-center font-black text-base shadow-md shadow-emerald-600/20">C</div>
                    <div class="flex-1 min-w-0">
                        <p class="font-black text-slate-900 group-hover:text-emerald-700 text-xs uppercase truncate">Boutique Cacaveli</p>
                        <p class="text-[11px] text-slate-500 font-semibold truncate">+228 90 00 00 01</p>
                    </div>
                    <div class="text-slate-300 group-hover:text-emerald-600 transform group-hover:translate-x-1 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                    </div>
                </a>

                <a href="https://wa.me/22890000002?text=Bonjour%20Bio%20Farm%20Hedzranawoe%2C%20je%20souhaite%20commander%20des%20produits%20JOOSSI%20%2F%20KINU." target="_blank" class="flex items-center space-x-3 p-3 hover:bg-emerald-50 rounded-2xl transition-all group border border-slate-100 hover:border-emerald-200">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-teal-500 to-cyan-600 text-white flex-shrink-0 flex items-center justify-center font-black text-base shadow-md shadow-teal-600/20">H</div>
                    <div class="flex-1 min-w-0">
                        <p class="font-black text-slate-900 group-hover:text-emerald-700 text-xs uppercase truncate">Boutique Hedzranawoe</p>
                        <p class="text-[11px] text-slate-500 font-semibold truncate">+228 90 00 00 02</p>
                    </div>
                    <div class="text-slate-300 group-hover:text-emerald-600 transform group-hover:translate-x-1 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                    </div>
                </a>
            </div>
        </div>

        <!-- Float Button -->
        <button @click="open = !open" 
                class="text-white p-4 rounded-full shadow-2xl hover:scale-110 transition-all duration-300 flex items-center justify-center w-16 h-16 bg-gradient-to-br from-emerald-600 to-teal-700 hover:from-emerald-700 hover:to-teal-800 z-50 mb-16 sm:mb-0 shadow-emerald-900/30">
             <svg x-show="!open" class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.038 3.284l-.569 2.103 2.201-.588c.959.566 1.931.946 3.097.947h.001c3.181 0 5.767-2.586 5.768-5.766 0-3.18-2.587-5.766-5.768-5.766zm3.434 8.272c-.117.33-.679.624-1.121.724-.316.071-.734.127-1.137-.035-.245-.098-.553-.228-2.731-1.139-.938-.403-1.547-1.353-1.637-1.472-.09-.12-1.077-1.357-1.077-2.588s.642-1.838.871-2.083c.23-.245.501-.306.668-.306s.334.004.481.012c.153.008.358-.058.56.43s.688 1.674.75 1.8c.063.126.104.272.021.439-.083.167-.125.272-.25.417-.125.146-.262.326-.375.438-.125.125-.255.261-.11.512.146.251.646 1.066 1.388 1.725.954.847 1.758 1.11 2.012 1.235.253.125.402.104.551-.063.149-.167.643-.751.815-1.002.172-.25.344-.209.58-.125s1.493.704 1.751.83c.258.125.43.188.492.292.063.104.063.604-.054.914zm-3.434-11.272c-5.14 0-9.324 4.183-9.327 9.326-.001 1.68.455 3.313 1.32 4.74l-.993 3.633 3.734-.979c1.52.907 3.013 1.385 4.624 1.386 5.14 0 9.324-4.183 9.327-9.325.002-2.489-.965-4.831-2.724-6.592-1.758-1.759-4.101-2.727-6.591-2.729z"/>
             </svg>
             <svg x-show="open" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
             </svg>
        </button>
    </div>

    <!-- Mobile Bottom Navigation Bar -->
    <div class="sm:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200 px-4 py-2 flex justify-between items-center shadow-lg" style="padding-bottom: env(safe-area-inset-bottom, 0.75rem);">
        <a href="{{ route('home') }}" class="flex flex-col items-center p-1.5 {{ request()->routeIs('home') ? 'text-emerald-700 font-bold' : 'text-slate-400' }}">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="text-[10px] mt-0.5">Accueil</span>
        </a>
        <a href="{{ route('home') }}#catalogue" class="flex flex-col items-center p-1.5 text-slate-400">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <span class="text-[10px] mt-0.5">Produits</span>
        </a>
        <a href="{{ route('cart.index') }}" class="flex flex-col items-center p-1.5 text-slate-400 relative">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            @if(session('cart') && count(session('cart')) > 0)
                <span class="absolute top-1 right-2 w-2 h-2 rounded-full bg-emerald-600"></span>
            @endif
            <span class="text-[10px] mt-0.5">Panier</span>
        </a>
        <a href="{{ route('home') }}#boutiques" class="flex flex-col items-center p-1.5 text-slate-400">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
            </svg>
            <span class="text-[10px] mt-0.5">Boutiques</span>
        </a>
        @auth
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center p-1.5 text-slate-400">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-[10px] mt-0.5">Compte</span>
            </a>
        @else
            <a href="{{ route('login') }}" class="flex flex-col items-center p-1.5 text-slate-400">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span class="text-[10px] mt-0.5">Connexion</span>
            </a>
        @endauth
    </div>

    <!-- PWA Registration -->
    <script src="{{ asset('js/pwa-install.js') }}" defer></script>
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register("{{ asset('sw.js') }}")
                    .then(reg => console.log('Service worker registered.', reg))
                    .catch(err => console.log('Service worker registration failed:', err));
            });
        }
    </script>
</body>
</html>
