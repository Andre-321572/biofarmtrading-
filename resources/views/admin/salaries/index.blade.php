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
        showBonusModal: false,
        newHourlyRate: {{ $hourly_rate }},
        newRecruitsRate: {{ $new_hourly_rate ?? \App\Services\PayrollService::getNewHourlyRate() }},
        selectedWorkersForBonus: [],
        bonusAmount: 5000,
        bonusReason: 'Prime de rendement',
        workerFilterQuery: '',
        openDetail(item) {
            this.selectedWorker = item;
            this.showModal = true;
        },
        openBonusForWorker(workerId) {
            this.selectedWorkersForBonus = [workerId];
            this.showBonusModal = true;
        },
        toggleAllBonus(workerIds) {
            if (this.selectedWorkersForBonus.length === workerIds.length) {
                this.selectedWorkersForBonus = [];
            } else {
                this.selectedWorkersForBonus = [...workerIds];
            }
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
                            <p class="text-xs font-black uppercase tracking-wider text-emerald-900">Succès</p>
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

            <!-- Controls & Header Section -->
            <div class="mb-8 flex flex-col xl:flex-row justify-between items-start xl:items-center bg-white p-6 rounded-3xl shadow-sm border border-slate-200 gap-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-emerald-50 border border-emerald-100 rounded-2xl shadow-sm">
                        <img src="{{ asset('images/biofarm_logo.jpg') }}" class="w-12 h-12 object-contain rounded-xl" onerror="this.src='/images/biofarm_logo.jpg'">
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-200">
                                Gestion Salaires &bull; {{ number_format($hourly_rate, 2, ',', ' ') }} F/h (Anciens) &bull; {{ number_format($new_hourly_rate ?? 218.75, 2, ',', ' ') }} F/h (Essai 14j)
                            </span>
                            @if($canEditRate ?? true)
                                <button type="button" 
                                        @click="showRateModal = true"
                                        class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-100 hover:bg-amber-200 text-amber-800 border border-amber-300 transition-all flex items-center gap-1 shadow-xs active:scale-95"
                                        title="Modifier les taux horaires appliqués aux ouvriers">
                                    <svg class="w-3 h-3 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    <span>Modifier Taux</span>
                                </button>
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
                
                <div class="flex flex-wrap items-center gap-2.5">
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
                       class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3.5 py-2.5 rounded-2xl font-bold text-xs transition-all flex items-center gap-2 border border-slate-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span class="hidden sm:inline">Pointage</span>
                    </a>

                    <!-- Bouton Nouveaux Ouvriers -->
                    <a href="{{ route($prefix . 'workers.new') }}" 
                       class="bg-purple-50 hover:bg-purple-100 text-purple-700 px-3.5 py-2.5 rounded-2xl font-black text-xs border border-purple-200 transition-all flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        <span>NOUVEAUX OUVRIERS</span>
                    </a>

                    <!-- Bouton Gérer les Primes -->
                    <button type="button" 
                            @click="showBonusModal = true"
                            class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2.5 rounded-2xl font-black text-xs shadow-lg shadow-amber-500/20 transition-all flex items-center gap-2 active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        <span>ATTRIBUER PRIMES</span>
                    </button>

                    <!-- Bouton Paramétrer Taux Horaire -->
                    @if($canEditRate ?? true)
                        <button type="button" 
                                @click="showRateModal = true"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-2xl font-black text-xs shadow-lg shadow-indigo-600/20 transition-all flex items-center gap-2 active:scale-95">
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
                <!-- Total Masse Salariale Nette -->
                <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-3xl p-6 text-white shadow-lg shadow-emerald-600/15 relative overflow-hidden" style="background-color: #059669; color: #ffffff;">
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
                            Dont {{ number_format($summary['total_base_salary'], 0, ',', ' ') }} F (Base) + {{ number_format($summary['total_bonuses'], 0, ',', ' ') }} F (Primes)
                        </p>
                    </div>
                </div>

                <!-- Total Primes Distribuées -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black uppercase tracking-wider text-slate-400">Total Primes Attribuées</span>
                        <div class="p-2.5 bg-amber-50 text-amber-600 rounded-2xl border border-amber-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="text-3xl font-black text-amber-600 tracking-tight">
                            {{ number_format($summary['total_bonuses'], 0, ',', ' ') }} <span class="text-lg font-bold text-slate-400">FCFA</span>
                        </div>
                        <p class="text-[11px] font-semibold text-slate-400 mt-1">
                            Accordées à {{ $summary['workers_with_bonuses_count'] }} ouvrier(s) ce mois
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

                <!-- Effectif & Ancienneté -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black uppercase tracking-wider text-slate-400">Effectif & Recrues</span>
                        <div class="p-2.5 bg-purple-50 text-purple-600 rounded-2xl border border-purple-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        </div>
                    </div>
                    <div class="mt-3 space-y-1.5">
                        <div class="flex items-center justify-between text-xs font-bold">
                            <span class="text-purple-700 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-purple-500"></span> Nouveaux ouvriers :
                            </span>
                            <span class="text-slate-800 font-black">{{ $summary['new_workers_count'] }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs font-bold">
                            <span class="text-slate-600 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-slate-400"></span> Anciens ouvriers :
                            </span>
                            <span class="text-slate-800 font-black">{{ $summary['regular_workers_count'] }}</span>
                        </div>
                        <div class="pt-1 border-t border-slate-100 flex items-center justify-between text-[11px] font-semibold text-slate-400">
                            <span>Actifs rémunérés</span>
                            <span class="font-bold text-emerald-600">{{ $summary['active_workers'] }} / {{ $summary['total_workers'] }}</span>
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
                                <th class="py-4 px-4 text-center w-12">N°</th>
                                <th class="py-4 px-6">Ouvrier & Équipe</th>
                                <th class="py-4 px-3 text-center">Statut</th>
                                <th class="py-4 px-4 text-center">Jours Présents</th>
                                <th class="py-4 px-4 text-center">Volume Horaire</th>
                                <th class="py-4 px-4 text-center">Taux Appliqué</th>
                                <th class="py-4 px-4 text-right">Salaire Base</th>
                                <th class="py-4 px-4 text-right">Prime (FCFA)</th>
                                <th class="py-4 px-6 text-right">Net à Payer</th>
                                <th class="py-4 px-4 text-center w-28">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($items as $item)
                                @php
                                    $w = $item['worker'];
                                    $isDay = $item['shift'] === 'day';
                                    $isNew = $item['is_new'];
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

                                    <!-- Statut Nouveau / Ancien -->
                                    <td class="py-4 px-3 text-center">
                                        @if($isNew)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-purple-100 text-purple-800 border border-purple-200">
                                                Nouveau
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200">
                                                Ancien
                                            </span>
                                        @endif
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

                                    <!-- Taux Appliqué -->
                                    <td class="py-4 px-4 text-center">
                                        @if(!empty($item['is_split_rate']))
                                            <div class="inline-flex flex-col items-center gap-1">
                                                <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider bg-purple-100 text-purple-800 border border-purple-200" title="Heures effectuées pendant la période d'essai de 14 jours">
                                                    {{ $item['trial_hours_formatted'] }} &bull; {{ number_format($item['trial_rate'], 2, ',', ' ') }} F
                                                </span>
                                                <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200" title="Heures effectuées après titularisation">
                                                    {{ $item['regular_hours_formatted'] }} &bull; {{ number_format($item['regular_rate'], 2, ',', ' ') }} F
                                                </span>
                                            </div>
                                        @else
                                            <span class="font-black text-xs {{ $isNew ? 'text-purple-700' : 'text-slate-700' }}">
                                                {{ number_format($item['hourly_rate'], 2, ',', ' ') }} F
                                            </span>
                                            <span class="block text-[9px] font-bold text-slate-400">
                                                {{ $isNew ? '(Essai 14j)' : '/ heure' }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Salaire de Base -->
                                    <td class="py-4 px-4 text-right font-bold text-xs text-slate-600">
                                        {{ number_format($item['base_salary'], 0, ',', ' ') }} F
                                    </td>

                                    <!-- Prime (FCFA) -->
                                    <td class="py-4 px-4 text-right">
                                        @if($item['bonus_amount'] > 0)
                                            <button type="button" 
                                                    @click="openBonusForWorker({{ $w->id }})"
                                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-xs font-black bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100 transition-colors shadow-xs"
                                                    title="Modifier la prime de {{ $w->last_name }}">
                                                +{{ number_format($item['bonus_amount'], 0, ',', ' ') }} F
                                            </button>
                                        @else
                                            <button type="button" 
                                                    @click="openBonusForWorker({{ $w->id }})"
                                                    class="text-slate-300 hover:text-amber-600 text-xs font-bold px-2 py-1 rounded-lg hover:bg-amber-50 transition-colors"
                                                    title="Attribuer une prime à {{ $w->last_name }}">
                                                + Prime
                                            </button>
                                        @endif
                                    </td>

                                    <!-- Salaire Net à Payer (Base + Prime) -->
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
                                    <td colspan="10" class="py-16 text-center">
                                        <div class="max-w-md mx-auto flex flex-col items-center">
                                            <div class="w-16 h-16 rounded-3xl bg-slate-100 flex items-center justify-center text-slate-400 mb-4">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                            </div>
                                            <h4 class="text-base font-black text-slate-700 uppercase">Aucun ouvrier trouvé</h4>
                                            <p class="text-xs text-slate-400 font-medium mt-1">
                                                Aucun ouvrier ne correspond aux critères de recherche pour le mois de {{ $month->locale('fr')->translatedFormat('F Y') }}.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <!-- Table Footer Summary -->
                        @if($items->count() > 0)
                            <tfoot class="bg-slate-100/90 border-t-2 border-slate-200 font-black text-slate-800 text-xs">
                                <tr>
                                    <td colspan="3" class="py-4 px-6 uppercase tracking-wider">
                                        Total Général ({{ $summary['total_workers'] }} ouvriers)
                                    </td>
                                    <td class="py-4 px-4 text-center text-slate-600">
                                        {{ $summary['active_workers'] }} actifs
                                    </td>
                                    <td class="py-4 px-4 text-center text-blue-700 font-black">
                                        {{ $summary['total_hours_formatted'] }}
                                    </td>
                                    <td class="py-4 px-4 text-center text-slate-500">
                                        -
                                    </td>
                                    <td class="py-4 px-4 text-right text-slate-700 font-black">
                                        {{ number_format($summary['total_base_salary'], 0, ',', ' ') }} F
                                    </td>
                                    <td class="py-4 px-4 text-right text-amber-700 font-black">
                                        {{ number_format($summary['total_bonuses'], 0, ',', ' ') }} F
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
                        <h4 class="text-xs font-black uppercase text-slate-800 tracking-wider">Note de calcul salarial et primes</h4>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">
                            Le salaire net est composé de la rémunération horaire effective selon le statut (<strong>{{ number_format($hourly_rate, 0, ',', ' ') }} FCFA/h</strong> anciens &bull; <strong>{{ number_format($new_hourly_rate ?? 250, 0, ',', ' ') }} FCFA/h</strong> nouveaux) additionnée aux primes attribuées pour le mois sélectionné.
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <a href="{{ route($prefix . 'salaries.pdf', ['month' => $selectedMonth, 'shift' => $selectedShift]) }}" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-black text-xs shadow-md transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Fiche d'Émargement PDF</span>
                    </a>
                </div>
            </div>

        </div>

        <!-- ========================================== -->
        <!-- MODAL 1 : PARAMÉTRAGE DES TAUX HORAIRES    -->
        <!-- (Accessible Admin & RH)                    -->
        <!-- ========================================== -->
        <div x-show="showRateModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showRateModal" 
                     class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
                     @click="showRateModal = false"></div>

                <div x-show="showRateModal" 
                     class="inline-block w-full max-w-lg my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl border border-slate-200 relative z-10">
                    
                    <form method="POST" action="{{ route($prefix . 'salaries.update_rate') }}">
                        @csrf
                        <!-- Modal Header -->
                        <div class="px-6 py-5 bg-gradient-to-r from-slate-900 to-slate-800 text-white flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-emerald-500/20 border border-emerald-400/30 flex items-center justify-center text-emerald-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-black uppercase tracking-tight">Paramétrer les Taux Horaires</h3>
                                    <p class="text-[11px] font-bold text-slate-400">Rémunération horaire (Anciens & Nouveaux Ouvriers)</p>
                                </div>
                            </div>
                            <button type="button" @click="showRateModal = false" class="text-slate-400 hover:text-white p-1 rounded-xl hover:bg-white/10 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <!-- Modal Body -->
                        <div class="p-6 space-y-5">
                            <!-- Taux Anciens Ouvriers -->
                            <div>
                                <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">
                                    Taux Horaire des ANCIENS Ouvriers (FCFA / h)
                                </label>
                                <div class="relative">
                                    <input type="number" 
                                           name="hourly_rate" 
                                           x-model.number="newHourlyRate" 
                                           min="1" 
                                           max="100000" 
                                           step="0.01" 
                                           required 
                                           class="w-full text-2xl font-black text-slate-900 px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-2xl focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/20 transition-all text-center">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-black uppercase text-slate-400">
                                        FCFA / h
                                    </span>
                                </div>
                            </div>

                            <!-- Taux Nouveaux Ouvriers (Période d'essai 14 jours) -->
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-xs font-black uppercase tracking-wider text-purple-700">
                                        Taux Horaire d'ESSAI Nouveaux Ouvriers (FCFA / h)
                                    </label>
                                    <span class="text-[10px] font-bold text-purple-600 bg-purple-50 px-2 py-0.5 rounded-full border border-purple-200">
                                        Période d'essai de 14 jours
                                    </span>
                                </div>
                                <div class="relative">
                                    <input type="number" 
                                           name="new_hourly_rate" 
                                           x-model.number="newRecruitsRate" 
                                           min="1" 
                                           max="100000" 
                                           step="0.01" 
                                           required 
                                           class="w-full text-2xl font-black text-purple-800 px-4 py-3 bg-purple-50 border-2 border-purple-200 rounded-2xl focus:bg-white focus:border-purple-500 focus:ring-4 focus:ring-purple-500/20 transition-all text-center">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-black uppercase text-purple-400">
                                        FCFA / h
                                    </span>
                                </div>
                                <p class="text-[11px] text-slate-500 mt-1.5">
                                    Appliqué automatiquement durant les 14 premiers jours d'essai. Dès le 15ème jour, le taux bascule automatiquement au taux standard (275 FCFA/h).
                                </p>
                            </div>

                            <!-- Quick preset pills -->
                            <div>
                                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">
                                    Suggestions rapides (Nouveaux / Anciens)
                                </span>
                                <div class="flex flex-wrap gap-2">
                                    <button type="button" 
                                            @click="newRecruitsRate = 218.75; newHourlyRate = 275.00" 
                                            class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all border bg-purple-50 text-purple-700 border-purple-200 hover:bg-purple-100">
                                        Standard Bio Farm : 218.75 F / 275 F
                                    </button>
                                    <template x-for="preset in [200, 218.75, 250, 275, 300, 350]" :key="preset">
                                        <button type="button" 
                                                @click="newHourlyRate = preset" 
                                                class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all border"
                                                :class="newHourlyRate === preset ? 'bg-emerald-600 text-white border-emerald-600 shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-700 border-slate-200'">
                                            <span x-text="preset + ' F'"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <!-- Footer Actions -->
                            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                                <button type="button" 
                                        @click="showRateModal = false"
                                        class="px-5 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all">
                                    Annuler
                                </button>
                                <button type="submit" 
                                        class="px-6 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs shadow-lg shadow-emerald-600/20 transition-all active:scale-95 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Enregistrer les Taux</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- MODAL 2 : ATTRIBUTION DES PRIMES (BONUSES) -->
        <!-- (Cocher ouvriers & fixer le montant)       -->
        <!-- ========================================== -->
        <div x-show="showBonusModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
                     @click="showBonusModal = false"></div>

                <div class="inline-block w-full max-w-2xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl border border-slate-200 relative z-10">
                    <form method="POST" action="{{ route($prefix . 'salaries.bonuses.store') }}">
                        @csrf
                        <input type="hidden" name="month" value="{{ $selectedMonth }}">

                        <!-- Modal Header -->
                        <div class="px-6 py-5 bg-gradient-to-r from-amber-600 to-amber-700 text-white flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-white/20 flex items-center justify-center text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-black uppercase tracking-tight">Attribuer une Prime aux Ouvriers</h3>
                                    <p class="text-[11px] font-bold text-amber-100">Période : {{ $month->locale('fr')->translatedFormat('F Y') }}</p>
                                </div>
                            </div>
                            <button type="button" @click="showBonusModal = false" class="text-amber-200 hover:text-white p-1 rounded-xl hover:bg-white/10 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <!-- Modal Body -->
                        <div class="p-6 space-y-5">
                            <!-- Montant de la prime -->
                            <div>
                                <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">
                                    Montant de la Prime (en FCFA)
                                </label>
                                <div class="relative">
                                    <input type="number" 
                                           name="amount" 
                                           x-model.number="bonusAmount" 
                                           min="1" 
                                           max="1000000" 
                                           step="100" 
                                           required 
                                           class="w-full text-2xl font-black text-amber-700 px-4 py-3 bg-amber-50/50 border-2 border-amber-200 rounded-2xl focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/20 text-center">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-black uppercase text-amber-500">FCFA</span>
                                </div>

                                <!-- Preset pills -->
                                <div class="flex flex-wrap gap-2 mt-2.5">
                                    <template x-for="preset in [2500, 5000, 7500, 10000, 15000, 20000, 50000]" :key="preset">
                                        <button type="button" 
                                                @click="bonusAmount = preset"
                                                class="px-2.5 py-1 rounded-xl text-xs font-bold border transition-all"
                                                :class="bonusAmount === preset ? 'bg-amber-500 text-white border-amber-500 shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700 border-slate-200'">
                                            <span x-text="preset.toLocaleString('fr-FR') + ' F'"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <!-- Motif de la prime -->
                            <div>
                                <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">
                                    Motif de la prime (optionnel)
                                </label>
                                <input type="text" 
                                       name="reason" 
                                       x-model="bonusReason" 
                                       placeholder="Ex : Prime de rendement, Prime d'assiduité..." 
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold text-slate-800 focus:bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">
                            </div>

                            <!-- Sélection des ouvriers avec cases à cocher -->
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-xs font-black uppercase tracking-wider text-slate-700">
                                        Cocher les ouvriers bénéficiaires (<span x-text="selectedWorkersForBonus.length"></span> sélectionné(s))
                                    </label>
                                    <button type="button" 
                                            @click="toggleAllBonus([{{ $allWorkers->pluck('id')->implode(',') }}])"
                                            class="text-xs font-bold text-amber-600 hover:text-amber-800 underline">
                                        Tout cocher / décocher
                                    </button>
                                </div>

                                <!-- Recherche rapide dans la liste -->
                                <div class="mb-2">
                                    <input type="text" 
                                           x-model="workerFilterQuery" 
                                           placeholder="Filtrer un ouvrier dans la liste..." 
                                           class="w-full px-3 py-1.5 text-xs bg-slate-50 border border-slate-200 rounded-xl">
                                </div>

                                <div class="max-h-60 overflow-y-auto border border-slate-200 rounded-2xl p-2 space-y-1 bg-slate-50/50 custom-scrollbar">
                                    @foreach($allWorkers as $worker)
                                        <label x-show="!workerFilterQuery || '{{ strtolower($worker->last_name . ' ' . $worker->first_name) }}'.includes(workerFilterQuery.toLowerCase())"
                                               class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-white transition-all cursor-pointer border border-transparent hover:border-slate-200">
                                            <input type="checkbox" 
                                                   name="worker_ids[]" 
                                                   value="{{ $worker->id }}" 
                                                   x-model="selectedWorkersForBonus" 
                                                   class="w-4 h-4 rounded text-amber-600 focus:ring-amber-500 border-slate-300">
                                            <div class="flex-1 min-w-0 flex items-center justify-between">
                                                <span class="font-bold text-xs text-slate-800 uppercase truncate">
                                                    {{ $worker->last_name }} <span class="font-normal italic normal-case text-slate-500">{{ $worker->first_name }}</span>
                                                </span>
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-md {{ $worker->shift === 'night' ? 'bg-indigo-50 text-indigo-700' : 'bg-amber-50 text-amber-700' }}">
                                                    {{ $worker->shift === 'night' ? 'Nuit' : 'Jour' }}
                                                </span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Footer Actions -->
                            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                                <button type="button" @click="showBonusModal = false" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">
                                    Annuler
                                </button>
                                <button type="submit" 
                                        :disabled="selectedWorkersForBonus.length === 0"
                                        class="px-6 py-2.5 rounded-2xl bg-amber-600 hover:bg-amber-700 disabled:opacity-50 text-white font-black text-xs shadow-lg shadow-amber-600/20 transition-all flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Attribuer la Prime</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- MODAL 3 : DÉTAIL JOURNALIER D'UN OUVRIER   -->
        <!-- ========================================== -->
        <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showModal = false"></div>

                <div class="inline-block w-full max-w-3xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl border border-slate-200 relative z-10">
                    <template x-if="selectedWorker">
                        <div>
                            <!-- Header -->
                            <div class="px-6 py-5 bg-gradient-to-r from-slate-900 to-slate-800 text-white flex justify-between items-center">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 border border-emerald-400/30 flex items-center justify-center text-emerald-400 font-black text-lg uppercase">
                                        <span x-text="selectedWorker.worker.last_name.substring(0, 1)"></span>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-black uppercase tracking-tight" x-text="selectedWorker.worker.last_name + ' ' + selectedWorker.worker.first_name"></h3>
                                        <p class="text-xs text-slate-400">
                                            Équipe : <span class="text-emerald-400 font-bold" x-text="selectedWorker.shift === 'night' ? 'Équipe de Nuit (Forfait 4h)' : 'Équipe de Jour (Pause -2h)'"></span> &bull;
                                            Taux : <span class="text-white font-bold" x-text="selectedWorker.hourly_rate.toLocaleString('fr-FR') + ' FCFA/h'"></span>
                                        </p>
                                    </div>
                                </div>
                                <button type="button" @click="showModal = false" class="text-slate-400 hover:text-white p-1 rounded-xl hover:bg-white/10 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>

                            <!-- Summary KPIs in Modal -->
                            <div class="p-6 bg-slate-50 border-b border-slate-200 grid grid-cols-4 gap-4 text-center">
                                <div class="bg-white p-3 rounded-2xl border border-slate-200">
                                    <span class="text-[10px] font-black uppercase text-slate-400">Jours Présents</span>
                                    <div class="text-lg font-black text-slate-800 mt-0.5" x-text="selectedWorker.days_present + ' j'"></div>
                                </div>
                                <div class="bg-white p-3 rounded-2xl border border-slate-200">
                                    <span class="text-[10px] font-black uppercase text-slate-400">Total Heures</span>
                                    <div class="text-lg font-black text-blue-600 mt-0.5" x-text="selectedWorker.hours_formatted"></div>
                                </div>
                                <div class="bg-white p-3 rounded-2xl border border-slate-200">
                                    <span class="text-[10px] font-black uppercase text-slate-400">Prime Reçue</span>
                                    <div class="text-lg font-black text-amber-600 mt-0.5" x-text="'+' + (selectedWorker.bonus_amount || 0).toLocaleString('fr-FR') + ' F'"></div>
                                </div>
                                <div class="bg-white p-3 rounded-2xl border border-slate-200">
                                    <span class="text-[10px] font-black uppercase text-slate-400">Net Total</span>
                                    <div class="text-lg font-black text-emerald-600 mt-0.5" x-text="selectedWorker.salary.toLocaleString('fr-FR') + ' F'"></div>
                                </div>
                            </div>

                            <!-- Daily Breakdown List -->
                            <div class="p-6 max-h-96 overflow-y-auto custom-scrollbar">
                                <h4 class="text-xs font-black uppercase text-slate-500 tracking-wider mb-3">Relevé quotidien des pointages du mois</h4>
                                <div class="space-y-2">
                                    <template x-for="(day, idx) in selectedWorker.daily_details" :key="idx">
                                        <div class="p-3 rounded-2xl border border-slate-200 flex items-center justify-between text-xs"
                                             :class="day.status === 'present' ? 'bg-white' : 'bg-slate-50 opacity-60'">
                                            <div class="flex items-center gap-3">
                                                <span class="w-7 h-7 rounded-xl flex items-center justify-center font-black text-[11px]"
                                                      :class="day.status === 'present' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-600'"
                                                      x-text="idx + 1"></span>
                                                <div>
                                                    <span class="font-bold text-slate-800" x-text="day.date"></span>
                                                    <span class="text-[11px] text-slate-400 block" 
                                                          x-text="day.arrival_time ? (day.arrival_time + ' → ' + day.departure_time + ' (Pause -' + day.pause_minutes + 'm)') : (selectedWorker.shift === 'night' ? 'Forfait Nuit 4h' : 'Pointage standard')"></span>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <span class="font-black text-sm text-slate-900" x-text="day.net_hours_text"></span>
                                                <span class="block text-[10px] font-bold text-emerald-600" x-show="day.status === 'present'">Présent</span>
                                                <span class="block text-[10px] font-bold text-red-500" x-show="day.status !== 'present'">Absent</span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end">
                                <button type="button" @click="showModal = false" class="px-5 py-2.5 rounded-2xl bg-slate-900 text-white font-bold text-xs">
                                    Fermer
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
