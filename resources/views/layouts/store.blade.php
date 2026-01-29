<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Bio Farm Trading'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind & Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Custom Style for Premium Look -->
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .gradient-brand {
            background: linear-gradient(135deg, #4ade80 0%, #22c55e 100%);
        }
        .btn-premium {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased h-screen overflow-hidden flex flex-col">
    <!-- Top Navigation -->
    <nav class="flex-shrink-0 z-50 glass">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo: Always Visible -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center space-x-3">
                        <img src="{{ asset('images/logo.jpg') }}" alt="Bio Farm Trading Logo" class="h-10 sm:h-12 w-auto">
                        <span class="text-lg sm:text-xl font-bold tracking-tight text-gray-900">Bio Farm <span class="text-green-600">Trading</span></span>
                    </a>
                </div>

                <!-- Desktop Menu: Hidden on Mobile -->
                <div class="hidden sm:flex sm:items-center sm:space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-green-600 px-3 py-2 text-sm font-medium transition-colors">Produits</a>
                    <a href="{{ route('home') }}#boutiques" class="text-gray-600 hover:text-green-600 px-3 py-2 text-sm font-medium transition-colors">Nos Boutiques</a>
                    <a href="{{ route('contact.index') }}" class="text-gray-600 hover:text-green-600 px-3 py-2 text-sm font-medium transition-colors">Contact</a>
                    
                    <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-600 hover:text-green-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        @if(session('cart') && count(session('cart')) > 0)
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full">
                                {{ count(session('cart')) }}
                            </span>
                        @endif
                    </a>

                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-green-600 px-3 py-2 text-sm font-medium transition-colors text-right">Mon Compte</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-green-600 px-3 py-2 text-sm font-medium transition-colors text-right">Connexion</a>
                    @endauth
                </div>

                <!-- Mobile Cart Icon: Only Visible on Mobile -->
                <div class="flex sm:hidden items-center">
                    <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-600 hover:text-green-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        @if(session('cart') && count(session('cart')) > 0)
                            <span class="absolute top-1 right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full">
                                {{ count(session('cart')) }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </nav>


    <!-- Main Content: The only scrollable area -->
    <main class="flex-1 overflow-y-auto overflow-x-hidden scroll-smooth pb-32 sm:pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-8 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-lg shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-8 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-lg shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
            
            <!-- Footer -->
            <footer class="bg-white border-t border-gray-100 py-12 mt-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <div class="col-span-1 md:col-span-2">
                            <div class="flex items-center space-x-3 mb-4">
                                <img src="{{ asset('images/logo.jpg') }}" alt="Bio Farm Trading Logo" class="h-10 w-auto">
                                <span class="text-lg font-bold tracking-tight text-gray-900">Bio Farm <span class="text-green-600">Trading</span></span>
                            </div>
                            <p class="text-gray-500 max-w-sm">
                                Produits 100% naturels, sans conservateurs ni additifs. Transformés avec passion au Togo.
                            </p>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 mb-4">Nos Boutiques</h3>
                            <ul class="space-y-2 text-gray-500 text-sm">
                                <li>📍 Boutique Cacaveli</li>
                                <li>📍 Boutique Hedzranawoe</li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 mb-4">Qualité & Trust</h3>
                            <ul class="space-y-2 text-gray-500 text-sm">
                                <li class="flex items-center gap-2">
                                     <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                     Certifié ECOCERT
                                </li>
                                <li class="flex items-center gap-2">
                                     <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                     Normes HACCP
                                </li>
                                <li class="flex items-center gap-2">
                                     <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                     100% Organique
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 mb-4">Contact</h3>
                            <ul class="space-y-2 text-gray-500 text-sm">
                                <li>📞 +228 90 00 00 00</li>
                                <li>✉️ kinu.joossi@gmail.com</li>
                            </ul>
                        </div>
                    </div>
                    <div class="border-t border-gray-100 mt-12 pt-8 text-center text-gray-400 text-sm">
                        &copy; {{ date('Y') }} Bio Farm Trading. Tous droits réservés.
                    </div>
                </div>
            </footer>
        </div>
    </main>

    <!-- WhatsApp Floating Button & Modal -->
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
             class="mb-4 w-80 bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-gray-100 overflow-hidden" 
             style="display: none;">
             
             <div class="bg-gray-50 p-4 border-b border-gray-100 flex justify-between items-center">
                 <div>
                    <h4 class="font-bold text-gray-900">Contactez-nous</h4>
                    <p class="text-xs text-gray-500">Choisissez votre boutique</p>
                 </div>
                 <button @click="open = false" class="text-gray-400 hover:text-red-500 transition-colors">
                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                 </button>
            </div>
            
            <div class="p-2 space-y-2">
                <a href="https://wa.me/22890000001?text=Bonjour%20Cacaveli%2C%20je%20souhaite%20commander%20un%20produit." target="_blank" class="flex items-center space-x-3 p-3 hover:bg-green-50 rounded-2xl transition-all group border border-transparent hover:border-green-100">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-green-400 to-green-600 text-white flex-shrink-0 flex items-center justify-center font-bold text-lg shadow-lg shadow-green-200">C</div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-gray-900 group-hover:text-green-700 truncate">Cacaveli</p>
                        <p class="text-xs text-gray-500 truncate">+228 90 00 00 01</p>
                    </div>
                    <div class="text-gray-300 group-hover:text-green-600 transform group-hover:translate-x-1 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </div>
                </a>

                <a href="https://wa.me/22890000002?text=Bonjour%20Hedzranawoe%2C%20je%20souhaite%20commander%20un%20produit." target="_blank" class="flex items-center space-x-3 p-3 hover:bg-green-50 rounded-2xl transition-all group border border-transparent hover:border-green-100">
                     <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 text-white flex-shrink-0 flex items-center justify-center font-bold text-lg shadow-lg shadow-blue-200">H</div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-gray-900 group-hover:text-blue-700 truncate">Hedzranawoe</p>
                        <p class="text-xs text-gray-500 truncate">+228 90 00 00 02</p>
                    </div>
                    <div class="text-gray-300 group-hover:text-blue-600 transform group-hover:translate-x-1 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </div>
                </a>
            </div>
        </div>

        <!-- Button -->
        <button @click="open = !open" 
                style="display: flex !important;"
                class="text-white p-4 rounded-full shadow-2xl hover:scale-110 transition-all duration-300 flex items-center justify-center w-16 h-16 bg-green-600 hover:bg-green-700 z-[9999] relative mb-16 sm:mb-0"
                :class="{'!bg-red-500 rotate-180': open}">
             <!-- Single Icon that transforms -->
             <svg x-show="!open" class="w-8 h-8 absolute transition-opacity duration-200" fill="currentColor" viewBox="0 0 24 24">
                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.484 8.412-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.309 1.656zm6.29-4.139c1.52.907 3.013 1.385 4.624 1.386 5.14 0 9.324-4.183 9.327-9.325.002-2.489-.965-4.831-2.724-6.592-1.758-1.759-4.101-2.727-6.591-2.729-5.141 0-9.324 4.183-9.327 9.326-.001 1.68.455 3.313 1.32 4.74l-.993 3.633 3.734-.979zm11.367-5.684c-.312-.156-1.848-.912-2.134-1.017-.286-.104-.494-.156-.703.156s-.807 1.017-1.003 1.24c-.195.223-.39.25-.703.093-.312-.156-1.317-.485-2.51-1.549-.928-.827-1.554-1.849-1.736-2.161-.182-.312-.019-.481.137-.636.141-.139.312-.364.469-.546.156-.182.208-.312.312-.52.104-.208.052-.39-.026-.546-.078-.156-.703-1.693-.963-2.319-.253-.611-.51-.528-.703-.537-.181-.009-.39-.01-.599-.01s-.547.078-.833.39c-.286.312-1.093 1.069-1.093 2.606s1.119 3.018 1.275 3.226c.156.208 2.201 3.361 5.333 4.715.745.322 1.327.515 1.779.659.749.238 1.43.205 1.969.125.599-.089 1.848-.756 2.108-1.485.26-.729.26-1.355.182-1.485-.078-.13-.286-.208-.599-.364z"/>
            </svg>
            <svg x-show="open" class="w-8 h-8 absolute transition-opacity duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <!-- Mobile Bottom Navigation -->
    <div class="sm:hidden fixed bottom-0 left-0 right-0 z-[100] bg-white border-t border-gray-100 px-4 py-2 pb-safe flex justify-between items-center shadow-[0_-4px_20px_rgba(0,0,0,0.08)]" style="padding-bottom: env(safe-area-inset-bottom, 1rem);">
        <a href="{{ route('home') }}" class="flex flex-col items-center p-2 {{ request()->routeIs('home') ? 'text-green-600' : 'text-gray-400' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="text-[10px] font-medium mt-1">Accueil</span>
        </a>
        <a href="{{ route('home') }}#boutiques" class="flex flex-col items-center p-2 text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span class="text-[10px] font-medium mt-1">Boutiques</span>
        </a>
        <a href="{{ route('contact.index') }}" class="flex flex-col items-center p-2 {{ request()->routeIs('contact.index') ? 'text-green-600' : 'text-gray-400' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <span class="text-[10px] font-medium mt-1">Contact</span>
        </a>
        @auth
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center p-2 {{ request()->routeIs('dashboard') ? 'text-green-600' : 'text-gray-400' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-[10px] font-medium mt-1">Compte</span>
            </a>
        @else
            <a href="{{ route('login') }}" class="flex flex-col items-center p-2 {{ request()->routeIs('login') ? 'text-green-600' : 'text-gray-400' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span class="text-[10px] font-medium mt-1">Connexion</span>
            </a>
        @endauth
    </div>
</body>
</html>
