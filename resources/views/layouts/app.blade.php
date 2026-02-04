<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @if(Auth::check() && Auth::user()->role === 'admin')
            <script src="{{ asset('js/admin-notifications.js') }}" defer></script>
        @endif
    </head>
    <body class="font-sans antialiased bg-[#F8FAFC] flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">
        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-30 lg:hidden"
             x-cloak></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               class="fixed lg:static w-72 h-full bg-gray-900 text-white flex-shrink-0 flex flex-col transition-all duration-300 shadow-2xl z-40">
            <!-- Brand -->
            <div class="h-20 flex items-center px-8 border-b border-gray-800 bg-gray-900/50 backdrop-blur-sm">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <div class="p-2 bg-green-500 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18 18.246 18.477 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    </div>
                    <span class="text-xl font-bold text-white tracking-tight uppercase">Bio Farm <span class="text-green-500">Trading</span></span>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 overflow-y-auto py-6 custom-scrollbar">
                <ul class="space-y-2 px-4">
                    <li>
                        <x-nav-link-custom href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="dashboard">
                            {{ __('Tableau de bord') }}
                        </x-nav-link-custom>
                    </li>

                    @if(Auth::user()->role === 'admin')
                        <li class="pt-6 pb-2 px-6 text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em]">Administration</li>
                        <li>
                            <x-nav-link-custom href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')" icon="dashboard">
                                {{ __('Tableau de bord') }}
                            </x-nav-link-custom>
                        </li>
                        <li>
                            <x-nav-link-custom href="{{ route('admin.products.index') }}" :active="request()->routeIs('admin.products.*')" icon="products">
                                {{ __('Produits') }}
                            </x-nav-link-custom>
                        </li>
                        <li>
                            <x-nav-link-custom href="{{ route('admin.categories.index') }}" :active="request()->routeIs('admin.categories.*')" icon="categories">
                                {{ __('Catégories') }}
                            </x-nav-link-custom>
                        </li>
                        <li>
                            <x-nav-link-custom href="{{ route('admin.orders.index') }}" :active="request()->routeIs('admin.orders.*')" icon="orders">
                                {{ __('Commandes') }}
                            </x-nav-link-custom>
                        </li>

                        <li class="pt-6 pb-2 px-6 text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em]">Stocks & Pointage</li>
                        <li>
                            <x-nav-link-custom href="{{ route('admin.stock.index') }}" :active="request()->routeIs('admin.stock.index')" icon="stock">
                                {{ __('État du Stock') }}
                            </x-nav-link-custom>
                        </li>
                        <li>
                            <x-nav-link-custom href="{{ route('admin.stock.supply_sheet') }}" :active="request()->routeIs('admin.stock.supply_sheet')" icon="reports">
                                {{ __('Bons de Sortie') }}
                            </x-nav-link-custom>
                        </li>
                        <li>
                            <x-nav-link-custom href="{{ route('admin.stock.monthly') }}" :active="request()->routeIs('admin.stock.monthly')" icon="reports">
                                {{ __('Rapports Mensuels') }}
                            </x-nav-link-custom>
                        </li>
                        <li>
                            <x-nav-link-custom href="{{ route('admin.attendance.index') }}" :active="request()->routeIs('admin.attendance.index')" icon="attendance">
                                {{ __('Pointage') }}
                            </x-nav-link-custom>
                        </li>
                    @endif

                    @if(Auth::user()->role === 'manager')
                        <li class="pt-6 pb-2 px-6 text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em]">Gestion Magasin</li>
                        <li>
                            <x-nav-link-custom href="{{ route('manager.sales.index') }}" :active="request()->routeIs('manager.sales.index')" icon="sales">
                                {{ __('Ventes') }}
                            </x-nav-link-custom>
                        </li>
                        <li>
                            <x-nav-link-custom href="{{ route('manager.sales.report') }}" :active="request()->routeIs('manager.sales.report')" icon="reports">
                                {{ __('Rapport Ventes') }}
                            </x-nav-link-custom>
                        </li>
                        <li>
                            <x-nav-link-custom href="{{ route('manager.stock.index') }}" :active="request()->routeIs('manager.stock.index')" icon="stock">
                                {{ __('Inventaire Stock') }}
                            </x-nav-link-custom>
                        </li>
                    @endif

                    @if(Auth::user()->role === 'rh')
                        <li class="pt-6 pb-2 px-6 text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em]">Ressources Humaines</li>
                        <li>
                            <x-nav-link-custom href="{{ route('rh.attendance.index') }}" :active="request()->routeIs('rh.attendance.index')" icon="attendance">
                                {{ __('Pointage RH') }}
                            </x-nav-link-custom>
                        </li>
                    @endif
                </ul>
            </nav>
            
            <!-- Bottom Logout -->
            <div class="p-6 border-t border-gray-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center justify-center gap-3 w-full px-4 py-3 rounded-xl bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-all duration-200 font-medium group text-sm">
                        <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        Déconnexion
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-h-0 min-w-0 bg-[#F8FAFC]">
            <!-- Top Navbar -->
            <header class="h-20 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-8 sticky top-0 z-10">
                <div class="flex-1 flex items-center gap-4">
                    <!-- Mobile Toggle -->
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-500 hover:bg-gray-100 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>

                    @if(isset($header))
                        {{ $header }}
                    @else
                        <h1 class="text-lg sm:text-xl font-semibold text-gray-800 truncate">
                            @yield('header_title', 'Tableau de bord')
                        </h1>
                    @endif
                </div>

                <div class="flex items-center gap-3 sm:gap-6">
                    <!-- Notifications -->
                    <button class="relative p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-all duration-200">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="absolute top-2 right-2.5 w-2 h-2 bg-red-500 border-2 border-white rounded-full"></span>
                    </button>

                    <div class="h-8 w-[1px] bg-gray-200 hidden sm:block"></div>

                    <!-- User Profile -->
                    <div class="flex items-center gap-3 pl-2">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-bold text-gray-900 leading-none">{{ Auth::user()->name }}</p>
                            <p class="text-[11px] text-gray-500 mt-1 uppercase tracking-wider font-semibold">{{ Auth::user()->role }}</p>
                        </div>
                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-green-100 border border-green-200 flex items-center justify-center text-green-700 font-bold shadow-sm flex-shrink-0">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto scroll-smooth">
                <div class="p-4 sm:p-6 md:p-8">
                    <div class="max-w-[1600px] mx-auto w-full">
                        {{ $slot }}
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>
