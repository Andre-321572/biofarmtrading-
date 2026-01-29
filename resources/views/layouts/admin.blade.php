<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-72 bg-gray-900 text-white flex-shrink-0 flex flex-col transition-all duration-300 shadow-2xl z-20 static h-screen">
        <!-- Brand -->
        <div class="h-20 flex items-center px-8 border-b border-gray-800 bg-gray-900/50 backdrop-blur-sm">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
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
                    <x-nav-link-custom href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')" icon="dashboard">
                        {{ __('Tableau de bord') }}
                    </x-nav-link-custom>
                </li>

                <!-- Products Section -->
                <li class="pt-6 pb-2 px-6 text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em]">Catalogue</li>
                <li>
                    <x-nav-link-custom href="{{ route('admin.products.index') }}" :active="request()->routeIs('admin.products.index')" icon="products">
                        {{ __('Produits') }}
                    </x-nav-link-custom>
                </li>
                <li>
                    <x-nav-link-custom href="{{ route('admin.categories.index') }}" :active="request()->routeIs('admin.categories.index')" icon="categories">
                        {{ __('Catégories') }}
                    </x-nav-link-custom>
                </li>
                 <li>
                    <x-nav-link-custom href="{{ route('admin.orders.index') }}" :active="request()->routeIs('admin.orders.*')" icon="orders">
                        {{ __('Commandes') }}
                    </x-nav-link-custom>
                </li>

                <!-- Stock Section -->
                <li class="pt-6 pb-2 px-6 text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em]">Gestion de Stocks</li>
                
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

                <!-- HR Section -->
                <li class="pt-6 pb-2 px-6 text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em]">Ressources Humaines</li>
                <li>
                    <x-nav-link-custom href="{{ route('admin.attendance.index') }}" :active="request()->routeIs('admin.attendance.index')" icon="attendance">
                        {{ __('Pointage') }}
                    </x-nav-link-custom>
                </li>
            </ul>
        </nav>
        
        <!-- Bottom Logout -->
        <div class="p-6 border-t border-gray-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center justify-center gap-3 w-full px-4 py-3 rounded-xl bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-all duration-200 font-medium group">
                    <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    Déconnexion
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 bg-[#F8FAFC]">
        <!-- Top Navbar -->
        <header class="h-20 bg-white border-b border-gray-200 flex items-center justify-between px-8 sticky top-0 z-10">
            <div class="flex items-center gap-4">
                <h1 class="text-xl font-semibold text-gray-800">
                    @yield('header_title', 'Tableau de bord')
                </h1>
            </div>

            <div class="flex items-center gap-6">
                <!-- Notifications -->
                <button class="relative p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-all duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="absolute top-2 right-2.5 w-2 h-2 bg-red-500 border-2 border-white rounded-full"></span>
                </button>

                <div class="h-8 w-[1px] bg-gray-200"></div>

                <!-- User Profile -->
                <div class="flex items-center gap-3 pl-2">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-gray-900 leading-none">{{ Auth::user()->name }}</p>
                        <p class="text-[11px] text-gray-500 mt-1 uppercase tracking-wider font-semibold">Administrateur</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-green-100 border border-green-200 flex items-center justify-center text-green-700 font-bold shadow-sm">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto scroll-smooth">
            <div class="p-4 md:p-8">
                <div class="max-w-[1600px] mx-auto w-full">
                    {{ $slot }}
                </div>
            </div>
        </main>
    </div>

</body>
</html>
