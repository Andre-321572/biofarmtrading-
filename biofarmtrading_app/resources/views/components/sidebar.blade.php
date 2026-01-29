@props(['active' => request()->route()->getName()])

<div x-data="{ open: false }" class="z-50">
    <!-- Mobile Backdrop -->
    <div x-show="open" 
         x-cloak 
         @click="open = false" 
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm md:hidden transition-opacity"
         x-transition:enter="duration-300 ease-out"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="duration-200 ease-in"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
    </div>

    <!-- Sidebar Wrapper -->
    <aside 
        :class="open ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
        class="fixed inset-y-0 left-0 w-64 bg-white border-r border-slate-200 transform transition-transform duration-300 ease-in-out md:static md:h-screen flex flex-col shadow-sm"
    >
        <!-- Logo Section -->
        <div class="h-20 flex items-center px-6 border-b border-slate-100">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <div class="w-9 h-9 bg-green-600 rounded-lg flex items-center justify-center shadow-md">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-lg font-black text-slate-900 tracking-tight leading-none uppercase">BioFarm</span>
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Application RH</span>
                </div>
            </a>
        </div>

        <!-- Navigation Section -->
        <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-7 scrollbar-hide">
            
            <!-- Menu Group -->
            <div>
                <p class="px-2 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Tableau de bord</p>
                <ul class="space-y-1">
                    @php
                        $dashRoute = Auth::user()->role === 'admin' ? 'admin.dashboard' : (Auth::user()->role === 'rh' ? 'rh.attendance.index' : 'dashboard');
                    @endphp
                    <li>
                        <a href="{{ route($dashRoute) }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs($dashRoute) ? 'bg-green-50 text-green-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                            <span>Vue d'ensemble</span>
                        </a>
                    </li>
                </ul>
            </div>

            @if(Auth::user()->role === 'admin')
            <!-- Admin Settings -->
            <div>
                <p class="px-2 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Logistique & Stocks</p>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold {{ request()->routeIs('admin.products.*') ? 'bg-green-50 text-green-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                            <span>Produits</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.stock.supply_sheet') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold {{ request()->routeIs('admin.stock.supply_sheet') ? 'bg-green-50 text-green-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            <span>Bons de sortie</span>
                        </a>
                    </li>
                </ul>
            </div>
            @endif

            @if(Auth::user()->role === 'rh')
            <div>
                <p class="px-2 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Gestion RH</p>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('rh.attendance.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold {{ request()->routeIs('rh.attendance.*') ? 'bg-green-50 text-green-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>Liste de Pointage</span>
                        </a>
                    </li>
                </ul>
            </div>
            @endif

        </nav>

        <!-- Profile / Logout -->
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            <div class="flex items-center gap-3 p-2 mb-3">
                <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-black text-sm uppercase">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="text-xs font-black text-slate-900 truncate uppercase">{{ Auth::user()->name }}</span>
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ Auth::user()->role }}</span>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 text-[10px] font-black text-red-600 hover:bg-red-50 rounded-xl transition-colors uppercase tracking-widest border border-red-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    Déconnexion
                </button>
            </form>
        </div>
    </aside>

    <!-- Mobile Button Hook (Floating) -->
    <button @click="open = true" 
            class="md:hidden fixed bottom-6 right-6 w-14 h-14 bg-slate-900 text-white rounded-full shadow-2xl flex items-center justify-center z-50">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
    </button>
</div>
