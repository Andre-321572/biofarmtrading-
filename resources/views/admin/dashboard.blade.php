<x-app-layout>
    <x-slot name="header">
        <div class="flex-1 flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                {{ __('Tableau de Bord Administration') }}
            </h2>
            <button onclick="window.adminNotifications.enableAudio()" class="px-5 py-2.5 bg-green-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-green-900/20 hover:bg-green-700 hover:scale-105 transition-all duration-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" /></svg>
                Alertes sonores
            </button>
        </div>
    </x-slot>

    <div class="space-y-6 sm:space-y-8">

        <!-- Flash Success Notification -->
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between shadow-sm animate-fade-in" x-data="{ show: true }" x-show="show">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold shadow-sm shadow-emerald-500/20 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-black uppercase tracking-wider text-emerald-900">Paramétrage actualisé</p>
                        <p class="text-xs font-semibold text-emerald-700 mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
                <button @click="show = false" class="text-emerald-500 hover:text-emerald-800 p-1.5 rounded-lg hover:bg-emerald-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 flex items-center justify-between shadow-sm" x-data="{ show: true }" x-show="show">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-red-500 text-white flex items-center justify-center font-bold shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-black uppercase tracking-wider text-red-900">Erreur de saisie</p>
                        <ul class="text-xs font-medium text-red-700 mt-0.5 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button @click="show = false" class="text-red-500 hover:text-red-800 p-1.5 rounded-lg hover:bg-red-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        <!-- Card Paramétrage Taux Horaire des Ouvriers (Admin) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden" 
             x-data="{ rateInput: {{ $hourlyRate ?? 275 }} }">
            
            <!-- Header de la section avec bandeau vert clair délicat -->
            <div class="px-6 py-4 bg-gradient-to-r from-emerald-50/70 via-slate-50/40 to-white border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-black shadow-sm shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-base font-extrabold text-gray-900 uppercase tracking-tight">
                                Rémunération & Taux Horaire des Ouvriers
                            </h3>
                            <span class="hidden sm:inline-block px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-200">
                                Direction Bio Farm
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 font-medium mt-0.5">
                            Paramètre officiel appliqué aux fiches de paie et aux calculs des salaires mensuels
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Taux actif : <strong class="text-emerald-950 font-black">{{ number_format($hourlyRate ?? 275, 0, ',', ' ') }} FCFA / h</strong>
                    </span>
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600">
                        {{ $workersCount ?? 0 }} ouvriers
                    </span>
                </div>
            </div>

            <!-- Corps de la carte en 2 colonnes équilibrées -->
            <div class="p-6 grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                
                <!-- Colonne Gauche : Taux Actuel & Navigation rapide -->
                <div class="md:col-span-5 flex flex-col justify-between space-y-4 pr-0 md:pr-4 md:border-r md:border-gray-100">
                    <div>
                        <span class="text-[11px] font-black uppercase tracking-wider text-gray-400">Taux Horaire Officiel en Vigueur</span>
                        <div class="flex items-baseline gap-2 mt-1.5">
                            <span class="text-4xl sm:text-5xl font-black text-gray-900 tracking-tight">
                                {{ number_format($hourlyRate ?? 275, 0, ',', ' ') }}
                            </span>
                            <span class="text-base font-bold text-emerald-600">
                                FCFA / heure
                            </span>
                        </div>
                        <p class="text-xs text-gray-600 mt-2 leading-relaxed">
                            Toute modification enregistrée ici par l'administrateur s'applique <strong>automatiquement</strong>. Le compte <strong>RH</strong> édite l'état des salaires et génère les fiches PDF sur cette base exacte.
                        </p>
                    </div>

                    <!-- Boutons d'accès directs -->
                    <div class="pt-1 flex flex-wrap gap-2.5">
                        <a href="{{ Route::has('admin.salaries.index') ? route('admin.salaries.index') : url('/admin/salaires') }}" 
                           class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-md shadow-emerald-600/20 transition-all active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Consulter l'État des Salaires</span>
                        </a>
                        <a href="{{ route('admin.attendance.index') }}" 
                           class="inline-flex items-center gap-2 px-3.5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition-all border border-gray-200">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Pointage</span>
                        </a>
                    </div>
                </div>

                <!-- Colonne Droite : Formulaire interactif de modification du taux -->
                <div class="md:col-span-7 bg-gray-50 rounded-2xl p-5 border border-gray-200/80 space-y-4">
                    <form method="POST" action="{{ route('admin.salaries.update_rate') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-gray-700 mb-2">
                                Modifier le taux horaire (en FCFA / h)
                            </label>
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
                                <div class="relative flex-1">
                                    <input type="number" 
                                           name="hourly_rate" 
                                           x-model.number="rateInput" 
                                           min="1" 
                                           max="100000" 
                                           step="1" 
                                           required 
                                           class="w-full text-lg sm:text-xl font-black text-gray-900 pl-4 pr-16 py-2.5 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all text-left">
                                    <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-xs font-black text-gray-400 pointer-events-none">
                                        FCFA / h
                                    </span>
                                </div>
                                <button type="submit" 
                                        class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs uppercase tracking-wider rounded-xl shadow-md shadow-emerald-600/20 transition-all flex items-center justify-center gap-2 active:scale-95 shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span>Appliquer le taux</span>
                                </button>
                            </div>
                            @error('hourly_rate')
                                <p class="text-xs text-red-600 mt-1.5 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Suggestions rapides (Presets) -->
                        <div>
                            <span class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                                Valeurs prédéfinies :
                            </span>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach([250, 275, 300, 325, 350, 400, 500] as $preset)
                                    <button type="button" 
                                            @click="rateInput = {{ $preset }}"
                                            class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all border"
                                            :class="rateInput === {{ $preset }} ? 'bg-emerald-600 text-white border-emerald-600 shadow-sm' : 'bg-white hover:bg-gray-100 text-gray-700 border-gray-300'">
                                        {{ $preset }} F
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Aperçu dynamique de simulation -->
                        <div class="bg-white rounded-xl p-3 border border-gray-200/80 grid grid-cols-2 gap-3 text-xs">
                            <div>
                                <span class="text-gray-500 font-medium block text-[11px]">Pour 100h travaillées :</span>
                                <span class="font-extrabold text-gray-900 text-sm" x-text="new Intl.NumberFormat('fr-FR').format(rateInput * 100) + ' FCFA'"></span>
                            </div>
                            <div>
                                <span class="text-gray-500 font-medium block text-[11px]">Mois équipe nuit (120h) :</span>
                                <span class="font-extrabold text-emerald-700 text-sm" x-text="new Intl.NumberFormat('fr-FR').format(rateInput * 120) + ' FCFA'"></span>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
            <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-3 sm:gap-4 text-center sm:text-left">
                    <div class="p-3 bg-green-100 text-green-600 rounded-xl">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div class="truncate w-full">
                        <p class="text-[10px] sm:text-sm font-medium text-gray-500 uppercase tracking-wider truncate">Revenu Total</p>
                        <p class="text-lg sm:text-2xl font-bold text-gray-900 truncate">{{ number_format($stats['total_revenue'], 0, ',', ' ') }} <span class="text-[10px] sm:text-sm font-semibold">FCFA</span></p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-3 sm:gap-4 text-center sm:text-left">
                    <div class="p-3 bg-blue-100 text-blue-600 rounded-xl">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    </div>
                    <div class="truncate w-full">
                        <p class="text-[10px] sm:text-sm font-medium text-gray-500 uppercase tracking-wider truncate">Commandes</p>
                        <p class="text-lg sm:text-2xl font-bold text-gray-900">{{ $stats['orders_count'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-3 sm:gap-4 text-center sm:text-left">
                    <div class="p-3 bg-purple-100 text-purple-600 rounded-xl">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                    <div class="truncate w-full">
                        <p class="text-[10px] sm:text-sm font-medium text-gray-500 uppercase tracking-wider truncate">Clients</p>
                        <p class="text-lg sm:text-2xl font-bold text-gray-900">{{ $stats['customers_count'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-3 sm:gap-4 text-center sm:text-left">
                    <div class="p-3 bg-red-100 text-red-600 rounded-xl">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </div>
                    <div class="truncate w-full">
                        <p class="text-[10px] sm:text-sm font-medium text-gray-500 uppercase tracking-wider truncate">Stock Faible</p>
                        <p class="text-lg sm:text-2xl font-bold text-red-600">{{ $stats['low_stock_count'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($lowStockProducts->count() > 0)
            <div class="bg-red-50 border border-red-100 p-4 sm:p-6 rounded-2xl">
                <div class="flex items-center gap-3 mb-4">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    <h3 class="text-xs sm:text-sm font-bold text-red-800 uppercase tracking-wider">Alerte Stock Critique</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4">
                    @foreach($lowStockProducts as $product)
                        <div class="bg-white p-3 sm:p-4 rounded-xl border border-red-50 flex justify-between items-center text-xs sm:text-sm shadow-sm">
                            <span class="font-bold text-gray-700 truncate mr-2">{{ $product->name }}</span>
                            <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full font-bold whitespace-nowrap">{{ $product->stock }} restant(s)</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Recent Orders -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 sm:p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 uppercase tracking-wider text-xs sm:text-sm">Dernières Commandes</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-[10px] sm:text-xs font-bold text-green-600 hover:text-green-700 uppercase tracking-widest">Voir tout →</a>
            </div>
            <div class="overflow-x-auto">
                {{-- Mobile view: Card list --}}
                <div class="md:hidden divide-y divide-gray-50 font-sans">
                    @foreach($recentOrders as $order)
                        <div class="p-4 flex flex-col gap-2">
                            <div class="flex justify-between items-start">
                                <span class="text-xs font-medium text-gray-400">#{{ $order->id }}</span>
                                <span class="px-2 py-0.5 text-[9px] font-bold uppercase rounded-full {{ $order->status === 'delivered' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $order->status }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-gray-900 text-sm tracking-tight uppercase">{{ $order->customer_name }}</span>
                                <span class="font-bold text-gray-900">{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Desktop view: Table --}}
                <table class="hidden md:table w-full text-left">
                    <thead class="bg-gray-50/50 text-[11px] font-bold text-gray-400 uppercase tracking-[0.1em]">
                        <tr>
                            <th class="px-8 py-4">Référence</th>
                            <th class="px-8 py-4">Client</th>
                            <th class="px-8 py-4">Statut</th>
                            <th class="px-8 py-4 text-right">Montant</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($recentOrders as $order)
                            <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                                <td class="px-8 py-5 text-sm font-medium text-gray-400">#{{ $order->id }}</td>
                                <td class="px-8 py-5">
                                    <span class="font-bold text-gray-900 text-sm tracking-tight uppercase">{{ $order->customer_name }}</span>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="px-3 py-1 text-[10px] font-bold uppercase rounded-full {{ $order->status === 'delivered' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right font-bold text-gray-900">{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
