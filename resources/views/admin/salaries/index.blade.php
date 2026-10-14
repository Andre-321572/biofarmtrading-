<x-app-layout>
    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>

    <div x-data="{
        selectedWorker: null,
        showModal: false,
        showRateModal: false,
        newHourlyRate: {{ $hourly_rate }},
        openDetail(item) {
            this.selectedWorker = item;
            this.showModal = true;
        }
    }" class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Flash Success Notification -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-3xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between shadow-sm" x-data="{ show: true }" x-show="show">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-2xl bg-emerald-500 text-white flex items-center justify-center font-bold shadow-sm shadow-emerald-500/20 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-black uppercase tracking-wider text-emerald-900">Paramétrage actualisé</p>
                            <p class="text-xs font-semibold text-emerald-700 mt-0.5">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button @click="show = false" class="text-emerald-500 hover:text-emerald-800 p-1.5 rounded-xl hover:bg-emerald-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endif

            <!-- Flash Error Notification -->
            @if($errors->any())
                <div class="mb-6 p-4 rounded-3xl bg-red-50 border border-red-200 text-red-800 flex items-center justify-between shadow-sm" x-data="{ show: true }" x-show="show">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-2xl bg-red-500 text-white flex items-center justify-center font-bold shrink-0">
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
                    <button @click="show = false" class="text-red-500 hover:text-red-800 p-1.5 rounded-xl hover:bg-red-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endif
            
            <!-- Controls & Header -->
            <div class="mb-6 flex flex-col md:flex-row justify-between items-center bg-white p-6 rounded-3xl shadow-sm border border-slate-200 gap-4">
                <div class="flex items-center gap-4">
                    <div class="p-2.5 bg-emerald-50 border border-emerald-100 rounded-2xl">
                        <img src="{{ asset('images/biofarm_logo.jpg') }}" class="w-12 h-12 object-contain" onerror="this.src='/images/biofarm_logo.jpg'">
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-200">
                                Gestion Paie Ouvriers
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                Taux : <strong class="text-slate-900">{{ number_format($hourly_rate, 0, ',', ' ') }} FCFA</strong> / h
                            </span>
                            @if($canEditRate ?? (Auth::check() && Auth::user()->isAdmin()))
                                <button type="button" 
                                        @click="showRateModal = true"
                                        class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-100 hover:bg-amber-200 text-amber-800 border border-amber-300 transition-all flex items-center gap-1 shadow-xs active:scale-95"
                                        title="Modifier le taux horaire appliqué aux ouvriers">
                                    <svg class="w-3 h-3 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    <span>Modifier</span>
                                </button>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold text-slate-400 bg-slate-50 border border-slate-200 flex items-center gap-1">
                                    <svg class="w-2.5 h-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    Fixé par Direction
                                </span>
                            @endif
                        </div>
                        <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tight mt-1">
                            État des Salaires <span class="text-emerald-600">Bio Farm</span>
                        </h2>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-0.5">
                            Période : <span class="text-slate-700">{{ $month->locale('fr')->translatedFormat('F Y') }}</span> (du {{ \Carbon\Carbon::parse($start_of_month)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($end_of_month)->format('d/m/Y') }})
                        </p>
                    </div>
                </div>
                
                <div class="flex flex-wrap items-center gap-3">
                    <!-- Navigation entre les mois -->
                    <div class="flex items-center bg-slate-100 rounded-2xl p-1 shadow-inner border border-slate-200">
                        <a href="{{ route($prefix . 'salaries.index', ['month' => $prevMonth, 'shift' => $selectedShift]) }}" 
                           class="p-2 hover:bg-white hover:shadow-sm rounded-xl transition-all text-slate-600"
                           title="Mois précédent">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
                        </a>
                        
                        <form method="GET" action="{{ route($prefix . 'salaries.index') }}" class="inline-flex items-center px-2">
                            <input type="hidden" name="shift" value="{{ $selectedShift }}">
                            <input type="month" 
                                   name="month" 
                                   value="{{ $selectedMonth }}" 
                                   onchange="this.form.submit()" 
                                   class="bg-transparent border-0 font-black text-slate-800 text-xs sm:text-sm uppercase tracking-tight focus:ring-0 cursor-pointer py-1">
                        </form>

                        <a href="{{ route($prefix . 'salaries.index', ['month' => $nextMonth, 'shift' => $selectedShift]) }}" 
                           class="p-2 hover:bg-white hover:shadow-sm rounded-xl transition-all text-slate-600"
                           title="Mois suivant">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>

                    <!-- Retour au Pointage -->
                    <a href="{{ route($prefix . 'attendance.index') }}" 
                       class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2.5 rounded-2xl font-bold text-xs transition-all flex items-center gap-2 border border-slate-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>Pointage</span>
                    </a>

                    <!-- Bouton Paramétrer Taux Horaire (Admin) -->
                    @if($canEditRate ?? (Auth::check() && Auth::user()->isAdmin()))
                        <button type="button" 
                                @click="showRateModal = true"
                                class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2.5 rounded-2xl font-black text-xs shadow-lg shadow-amber-500/20 transition-all flex items-center gap-2 active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>PARAMÉTRER TAUX</span>
                        </button>
                    @endif

                    <!-- Bouton Télécharger PDF -->
                    <a href="{{ route($prefix . 'salaries.pdf', ['month' => $selectedMonth, 'shift' => $selectedShift]) }}" 
                       class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-2xl font-black text-xs shadow-lg shadow-emerald-600/25 transition-all flex items-center gap-2 active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        <span>TÉLÉCHARGER PDF PAIE</span>
                    </a>
                </div>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
                <!-- Total Masse Salariale -->
                <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-3xl p-6 text-white shadow-lg shadow-emerald-600/15 relative overflow-hidden">
                    <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black uppercase tracking-wider text-emerald-100">Masse Salariale Nette</span>
                        <div class="p-2.5 bg-white/15 rounded-2xl backdrop-blur-sm">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="text-3xl font-black tracking-tight">
                            {{ number_format($summary['total_salary'], 0, ',', ' ') }} <span class="text-lg font-bold text-emerald-200">FCFA</span>
                        </div>
                        <p class="text-[11px] font-semibold text-emerald-100/90 mt-1">
                            À décaisser auprès de la comptabilité
                        </p>
                    </div>
                </div>

                <!-- Total Heures Travaillées -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black uppercase tracking-wider text-slate-400">Volume Horaire Travaillé</span>
                        <div class="p-2.5 bg-blue-50 text-blue-600 rounded-2xl border border-blue-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="text-3xl font-black text-slate-800 tracking-tight">
                            {{ $summary['total_hours_formatted'] }}
                        </div>
                        <p class="text-[11px] font-semibold text-slate-400 mt-1">
                            Soit {{ number_format($summary['total_hours_decimal'], 1, ',', ' ') }} heures effectives
                        </p>
                    </div>
                </div>

                <!-- Effectif Ouvriers Rémunérés -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black uppercase tracking-wider text-slate-400">Ouvriers Concernés</span>
                        <div class="p-2.5 bg-amber-50 text-amber-600 rounded-2xl border border-amber-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="text-3xl font-black text-slate-800 tracking-tight">
                            {{ $summary['active_workers'] }} <span class="text-lg font-bold text-slate-400">/ {{ $summary['total_workers'] }}</span>
                        </div>
                        <p class="text-[11px] font-semibold text-slate-400 mt-1">
                            Ouvriers ayant effectué des heures ce mois
                        </p>
                    </div>
                </div>

                <!-- Répartition Équipes (Jour / Nuit) -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black uppercase tracking-wider text-slate-400">Répartition Équipes</span>
                        <div class="p-2.5 bg-purple-50 text-purple-600 rounded-2xl border border-purple-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                        </div>
                    </div>
                    <div class="mt-3 space-y-2">
                        <div class="flex items-center justify-between text-xs font-bold">
                            <span class="text-amber-700 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span> Jour ({{ $summary['day_workers_count'] }})
                            </span>
                            <span class="text-slate-800">{{ number_format($summary['day_salary'], 0, ',', ' ') }} F</span>
                        </div>
                        <div class="flex items-center justify-between text-xs font-bold">
                            <span class="text-indigo-700 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-indigo-500"></span> Nuit ({{ $summary['night_workers_count'] }})
                            </span>
                            <span class="text-slate-800">{{ number_format($summary['night_salary'], 0, ',', ' ') }} F</span>
                        </div>
                        <div class="pt-1 border-t border-slate-100 flex items-center justify-between text-[11px] font-semibold text-slate-400">
                            <span>Moyenne / ouvrier</span>
                            <span class="font-bold text-slate-600">{{ number_format($summary['average_salary'], 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters & Search Bar -->
            <div class="mb-6 bg-white p-5 rounded-3xl border border-slate-200 shadow-sm flex flex-col md:flex-row justify-between items-center gap-4">
                <!-- Shift Filter Pills -->
                <div class="flex items-center bg-slate-100 p-1 rounded-2xl w-full md:w-auto overflow-x-auto">
                    <a href="{{ route($prefix . 'salaries.index', ['month' => $selectedMonth, 'shift' => 'all', 'search' => $search]) }}"
                       class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all whitespace-nowrap {{ $selectedShift === 'all' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">
                        Tous les ouvriers ({{ $summary['total_workers'] }})
                    </a>
                    <a href="{{ route($prefix . 'salaries.index', ['month' => $selectedMonth, 'shift' => 'day', 'search' => $search]) }}"
                       class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all whitespace-nowrap {{ $selectedShift === 'day' ? 'bg-white text-amber-700 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">
                        Équipe Jour ({{ $summary['day_workers_count'] }})
                    </a>
                    <a href="{{ route($prefix . 'salaries.index', ['month' => $selectedMonth, 'shift' => 'night', 'search' => $search]) }}"
                       class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all whitespace-nowrap {{ $selectedShift === 'night' ? 'bg-white text-indigo-700 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">
                        Équipe Nuit ({{ $summary['night_workers_count'] }})
                    </a>
                </div>

                <!-- Search form -->
                <form method="GET" action="{{ route($prefix . 'salaries.index') }}" class="w-full md:w-80 flex items-center gap-2">
                    <input type="hidden" name="month" value="{{ $selectedMonth }}">
                    <input type="hidden" name="shift" value="{{ $selectedShift }}">
                    
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                        <input type="text" 
                               name="search" 
                               value="{{ $search }}" 
                               placeholder="Rechercher un ouvrier..." 
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                    </div>

                    @if($search)
                        <a href="{{ route($prefix . 'salaries.index', ['month' => $selectedMonth, 'shift' => $selectedShift]) }}" 
                           class="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-2xl transition-all"
                           title="Réinitialiser la recherche">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </a>
                    @endif
                </form>
            </div>

            <!-- Main Salary Table -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/75 border-b border-slate-200 text-slate-500 uppercase tracking-widest text-[10px] font-black">
                                <th class="py-4 px-4 text-center w-14">N°</th>
                                <th class="py-4 px-6">Ouvrier & Équipe</th>
                                <th class="py-4 px-4 text-center">Jours Présents</th>
                                <th class="py-4 px-4 text-center">Volume Horaire</th>
                                <th class="py-4 px-4 text-center">Taux Horaire</th>
                                <th class="py-4 px-6 text-right">Salaire Net à Payer</th>
                                <th class="py-4 px-4 text-center w-28">Détails</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($items as $item)
                                @php
                                    $w = $item['worker'];
                                    $isDay = $item['shift'] === 'day';
                                @endphp
                                <tr class="hover:bg-slate-50/75 transition-colors group">
                                    <!-- N° -->
                                    <td class="py-4 px-4 text-center font-bold text-slate-400 text-xs">
                                        {{ $loop->iteration }}
                                    </td>

                                    <!-- Worker identity & shift -->
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-black text-sm uppercase shadow-sm {{ $isDay ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-indigo-100 text-indigo-800 border border-indigo-200' }}">
                                                {{ substr($w->last_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-black text-slate-800 uppercase text-sm tracking-tight group-hover:text-emerald-700 transition-colors">
                                                    {{ $w->last_name }}
                                                </div>
                                                <div class="text-xs font-bold text-slate-500 italic">
                                                    {{ $w->first_name }}
                                                </div>
                                                <div class="mt-1">
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider {{ $isDay ? 'bg-amber-50 text-amber-700 border border-amber-200/60' : 'bg-indigo-50 text-indigo-700 border border-indigo-200/60' }}">
                                                        <span class="w-1.5 h-1.5 rounded-full {{ $isDay ? 'bg-amber-500' : 'bg-indigo-500' }}"></span>
                                                        {{ $isDay ? 'Équipe Jour' : 'Équipe Nuit' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Jours Présents -->
                                    <td class="py-4 px-4 text-center">
                                        <span class="inline-flex items-center justify-center px-3 py-1 rounded-xl text-xs font-black {{ $item['days_present'] > 0 ? 'bg-slate-100 text-slate-800' : 'bg-slate-50 text-slate-300' }}">
                                            {{ $item['days_present'] }} j
                                        </span>
                                    </td>

                                    <!-- Heures Travaillées -->
                                    <td class="py-4 px-4 text-center">
                                        @if($item['total_minutes'] > 0)
                                            <div class="inline-flex flex-col items-center">
                                                <span class="px-3 py-1 rounded-xl bg-blue-50 text-blue-700 font-black text-xs border border-blue-100 flex items-center gap-1.5 shadow-sm">
                                                    <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                    {{ $item['hours_formatted'] }}
                                                </span>
                                                <span class="text-[10px] font-semibold text-slate-400 mt-0.5">
                                                    ({{ number_format($item['hours_decimal'], 1, ',', ' ') }} h)
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-xs font-bold text-slate-300">0h00</span>
                                        @endif
                                    </td>

                                    <!-- Taux Horaire -->
                                    <td class="py-4 px-4 text-center">
                                        <span class="font-bold text-xs text-slate-500">
                                            {{ number_format($item['hourly_rate'], 0, ',', ' ') }} F
                                        </span>
                                    </td>

                                    <!-- Salaire Net -->
                                    <td class="py-4 px-6 text-right">
                                        <div class="inline-flex flex-col items-end">
                                            <span class="font-black text-base {{ $item['salary'] > 0 ? 'text-emerald-600' : 'text-slate-400' }}">
                                                {{ number_format($item['salary'], 0, ',', ' ') }} <span class="text-xs font-bold">FCFA</span>
                                            </span>
                                            @if($item['salary'] > 0)
                                                <span class="text-[9px] font-black uppercase tracking-wider text-emerald-500 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">
                                                    À régler
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Actions (Détails) -->
                                    <td class="py-4 px-4 text-center">
                                        <button @click="openDetail({{ json_encode($item) }})" 
                                                class="px-3 py-1.5 bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 text-slate-600 font-bold text-xs rounded-xl transition-all border border-slate-200 hover:border-emerald-200">
                                            Détails
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-16 text-center">
                                        <div class="max-w-md mx-auto flex flex-col items-center">
                                            <div class="w-16 h-16 rounded-3xl bg-slate-100 flex items-center justify-center text-slate-400 mb-4">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                            </div>
                                            <h4 class="text-base font-black text-slate-700 uppercase">Aucun ouvrier trouvé</h4>
                                            <p class="text-xs text-slate-400 font-medium mt-1">
                                                Aucun ouvrier ne correspond aux critères de recherche pour le mois de {{ $month->locale('fr')->translatedFormat('F Y') }}.
                                            </p>
                                            <a href="{{ route($prefix . 'attendance.index') }}" class="mt-4 px-4 py-2 bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-md">
                                                Accéder au Pointage
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <!-- Table Footer Summary -->
                        @if($items->count() > 0)
                            <tfoot class="bg-slate-100/80 border-t-2 border-slate-200 font-black text-slate-800 text-xs">
                                <tr>
                                    <td colspan="2" class="py-4 px-6 uppercase tracking-wider">
                                        Total Général ({{ $summary['total_workers'] }} ouvriers)
                                    </td>
                                    <td class="py-4 px-4 text-center text-slate-600">
                                        {{ $summary['active_workers'] }} actifs
                                    </td>
                                    <td class="py-4 px-4 text-center text-blue-700 font-black">
                                        {{ $summary['total_hours_formatted'] }}
                                    </td>
                                    <td class="py-4 px-4 text-center text-slate-500">
                                        {{ number_format($hourly_rate, 0, ',', ' ') }} F/h
                                    </td>
                                    <td class="py-4 px-6 text-right text-emerald-700 text-base font-black">
                                        {{ number_format($summary['total_salary'], 0, ',', ' ') }} FCFA
                                    </td>
                                    <td class="py-4 px-4 text-center">
                                        <a href="{{ route($prefix . 'salaries.pdf', ['month' => $selectedMonth, 'shift' => $selectedShift]) }}" class="text-[10px] font-black uppercase text-emerald-600 hover:underline">
                                            PDF
                                        </a>
                                    </td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Notes & Instructions for Accounting / RH -->
            <div class="mt-6 p-6 bg-white rounded-3xl border border-slate-200 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-slate-100 rounded-2xl text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-black uppercase text-slate-800 tracking-wider">Note de calcul et procédure de décaissement</h4>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">
                            Rémunération horaire : <strong>{{ number_format($hourly_rate, 0, ',', ' ') }} FCFA / heure</strong> (paramétrable par l'administrateur). Équipe de jour : déduction automatique des 2 heures de pause journalière. Équipe de nuit : forfait de 4 heures par jour de présence.
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <a href="{{ route($prefix . 'salaries.pdf', ['month' => $selectedMonth, 'shift' => $selectedShift]) }}" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-black text-xs shadow-md transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                        <span>Imprimer l'état de paie pour la comptabilité</span>
                    </a>
                </div>
            </div>

        </div>

        <!-- Worker Breakdown Modal -->
        <div x-cloak x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div x-show="showModal" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
                     @click="showModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal Panel -->
                <div x-show="showModal" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-slate-200">
                    
                    <template x-if="selectedWorker">
                        <div>
                            <!-- Modal Header -->
                            <div class="bg-slate-50 px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-black text-sm uppercase bg-emerald-100 text-emerald-800 border border-emerald-200"
                                         x-text="selectedWorker.worker.last_name.substring(0, 1)">
                                    </div>
                                    <div>
                                        <h3 class="text-base font-black text-slate-800 uppercase tracking-tight" 
                                            x-text="selectedWorker.worker.last_name + ' ' + selectedWorker.worker.first_name"></h3>
                                        <p class="text-xs font-bold text-slate-400">
                                            Relevé individuel du mois de {{ $month->locale('fr')->translatedFormat('F Y') }} • 
                                            <span class="uppercase text-emerald-600 font-black" x-text="selectedWorker.shift === 'day' ? 'Équipe Jour' : 'Équipe Nuit'"></span>
                                        </p>
                                    </div>
                                </div>
                                <button @click="showModal = false" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-200 rounded-xl transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>

                            <!-- Modal Body Stats -->
                            <div class="p-6">
                                <div class="grid grid-cols-3 gap-3 mb-6">
                                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-center">
                                        <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Jours Travaillés</span>
                                        <div class="text-xl font-black text-slate-800 mt-1" x-text="selectedWorker.days_present + ' j'"></div>
                                    </div>
                                    <div class="p-4 bg-blue-50/70 rounded-2xl border border-blue-100 text-center">
                                        <span class="text-[10px] font-black uppercase text-blue-600 tracking-wider">Total Heures</span>
                                        <div class="text-xl font-black text-blue-700 mt-1" x-text="selectedWorker.hours_formatted"></div>
                                    </div>
                                    <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 text-center">
                                        <span class="text-[10px] font-black uppercase text-emerald-700 tracking-wider">Salaire Dû</span>
                                        <div class="text-xl font-black text-emerald-700 mt-1" x-text="new Intl.NumberFormat('fr-FR').format(selectedWorker.salary) + ' F'"></div>
                                    </div>
                                </div>

                                <h4 class="text-xs font-black uppercase tracking-wider text-slate-500 mb-3 flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    Détail journalier des présences et pointages
                                </h4>

                                <!-- Daily records table -->
                                <div class="max-h-64 overflow-y-auto custom-scrollbar border border-slate-200 rounded-2xl">
                                    <table class="w-full text-xs text-left">
                                        <thead class="bg-slate-50 sticky top-0 text-[10px] font-black uppercase tracking-wider text-slate-400 border-b border-slate-200">
                                            <tr>
                                                <th class="py-2.5 px-3">Date</th>
                                                <th class="py-2.5 px-3 text-center">Arrivée</th>
                                                <th class="py-2.5 px-3 text-center">Départ</th>
                                                <th class="py-2.5 px-3 text-center">Pause</th>
                                                <th class="py-2.5 px-3 text-right">Heures Net</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 font-bold">
                                            <template x-for="day in selectedWorker.daily_details" :key="day.date">
                                                <tr class="hover:bg-slate-50">
                                                    <td class="py-2.5 px-3 text-slate-700" x-text="day.date"></td>
                                                    <td class="py-2.5 px-3 text-center text-slate-600" x-text="day.arrival_time ? day.arrival_time : '-'"></td>
                                                    <td class="py-2.5 px-3 text-center text-slate-600" x-text="day.departure_time ? day.departure_time : '-'"></td>
                                                    <td class="py-2.5 px-3 text-center text-slate-400" x-text="day.pause_minutes > 0 ? '-2h' : '-'"></td>
                                                    <td class="py-2.5 px-3 text-right font-black" 
                                                        :class="day.net_minutes > 0 ? 'text-emerald-600' : 'text-slate-300'"
                                                        x-text="day.net_hours_text"></td>
                                                </tr>
                                            </template>
                                            <template x-if="!selectedWorker.daily_details || selectedWorker.daily_details.length === 0">
                                                <tr>
                                                    <td colspan="5" class="py-6 text-center text-slate-400 font-medium">
                                                        Aucun pointage enregistré pour cet ouvrier durant ce mois.
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Modal Footer -->
                            <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex justify-between items-center">
                                <div class="text-[11px] font-bold text-slate-400">
                                    Calcul : <span class="text-slate-700" x-text="selectedWorker.hours_formatted"></span> × <span class="text-slate-900 font-black" x-text="selectedWorker.hourly_rate"></span> FCFA
                                </div>
                                <button @click="showModal = false" class="px-5 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-black rounded-xl">
                                    Fermer
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Admin Rate Setting Modal -->
        @if($canEditRate ?? (Auth::check() && Auth::user()->isAdmin()))
        <div x-cloak x-show="showRateModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div x-show="showRateModal" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
                     @click="showRateModal = false"></div>

                <div x-show="showRateModal" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block w-full max-w-lg my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl border border-slate-200 relative z-10">
                    
                    <form method="POST" action="{{ route('admin.salaries.update_rate') }}">
                        @csrf
                        <!-- Modal Header -->
                        <div class="px-6 py-5 bg-gradient-to-r from-slate-900 to-slate-800 text-white flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-emerald-500/20 border border-emerald-400/30 flex items-center justify-center text-emerald-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-black uppercase tracking-tight">Paramétrer le Taux Horaire</h3>
                                    <p class="text-[11px] font-bold text-slate-400">Rémunération horaire des ouvriers Bio Farm Trading</p>
                                </div>
                            </div>
                            <button type="button" @click="showRateModal = false" class="text-slate-400 hover:text-white p-1 rounded-xl hover:bg-white/10 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <!-- Modal Body -->
                        <div class="p-6 space-y-5">
                            <div>
                                <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">
                                    Taux Horaire Officiel (FCFA / Heure)
                                </label>
                                <div class="relative">
                                    <input type="number" 
                                           name="hourly_rate" 
                                           x-model.number="newHourlyRate" 
                                           min="1" 
                                           max="100000" 
                                           step="1" 
                                           required 
                                           class="w-full text-2xl font-black text-slate-900 px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-2xl focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/20 transition-all text-center">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-black uppercase text-slate-400">
                                        FCFA / h
                                    </span>
                                </div>
                                @error('hourly_rate')
                                    <p class="text-xs text-red-600 mt-1 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Quick preset pills -->
                            <div>
                                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">
                                    Suggestions rapides
                                </span>
                                <div class="flex flex-wrap gap-2">
                                    @foreach([250, 275, 300, 325, 350, 400, 500] as $preset)
                                        <button type="button" 
                                                @click="newHourlyRate = {{ $preset }}"
                                                class="px-3 py-1.5 rounded-xl text-xs font-black transition-all border"
                                                :class="newHourlyRate === {{ $preset }} ? 'bg-emerald-600 text-white border-emerald-600 shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-700 border-slate-200'">
                                            {{ $preset }} F
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Simulation Preview Box -->
                            <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200 space-y-2">
                                <div class="text-[10px] font-black uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Simulation de l'impact en temps réel</span>
                                </div>
                                <div class="grid grid-cols-2 gap-3 pt-1">
                                    <div class="bg-white p-2.5 rounded-xl border border-slate-100">
                                        <span class="text-[10px] text-slate-500 font-semibold block">Pour 100 heures :</span>
                                        <span class="text-sm font-black text-slate-900" x-text="new Intl.NumberFormat('fr-FR').format(newHourlyRate * 100) + ' FCFA'"></span>
                                    </div>
                                    <div class="bg-white p-2.5 rounded-xl border border-slate-100">
                                        <span class="text-[10px] text-slate-500 font-semibold block">Pour 1 mois nuit (120h) :</span>
                                        <span class="text-sm font-black text-slate-900" x-text="new Intl.NumberFormat('fr-FR').format(newHourlyRate * 120) + ' FCFA'"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Impact Information Alert -->
                            <div class="p-3.5 bg-emerald-50 rounded-2xl border border-emerald-200/70 text-emerald-950 flex items-start gap-2.5">
                                <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p class="text-[11px] leading-relaxed">
                                    <strong>Application automatique immédiate :</strong> Dès enregistrement, tous les calculs des salaires des ouvriers, les totaux de paie et les bordereaux PDF générés pour la comptabilité s'ajusteront automatiquement avec ce nouveau taux.
                                </p>
                            </div>
                        </div>

                        <!-- Modal Actions -->
                        <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3">
                            <button type="button" 
                                    @click="showRateModal = false" 
                                    class="px-4 py-2.5 text-xs font-black text-slate-600 hover:text-slate-800 rounded-xl hover:bg-slate-200 transition-colors">
                                Annuler
                            </button>
                            <button type="submit" 
                                    class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black rounded-xl shadow-lg shadow-emerald-600/25 transition-all flex items-center gap-2 active:scale-95">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span>Enregistrer et Appliquer</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

    </div>
</x-app-layout>
