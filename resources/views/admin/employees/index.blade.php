<x-app-layout>
    <x-slot name="header"></x-slot>

    <style>
        [x-cloak] { display: none !important; }
        .gradient-card-blue { background: linear-gradient(135deg, #ffffff 0%, #f0f7ff 100%); }
        .gradient-card-green { background: linear-gradient(135deg, #ffffff 0%, #ecfdf5 100%); }
        .gradient-card-amber { background: linear-gradient(135deg, #ffffff 0%, #fffbeb 100%); }
        .gradient-card-purple { background: linear-gradient(135deg, #ffffff 0%, #f5f3ff 100%); }
    </style>

    <div x-data="{
        viewMode: 'table', // 'table' or 'grid'
        showPosteModal: false,
        selectedEmployeeId: null,
        selectedEmployeeName: '',
        selectedPositionId: '',
        updatePositionUrl: '',
        openPosteModal(id, name, currentPosId, url) {
            this.selectedEmployeeId = id;
            this.selectedEmployeeName = name;
            this.selectedPositionId = currentPosId ? currentPosId.toString() : '';
            this.updatePositionUrl = url;
            this.showPosteModal = true;
        }
    }" class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-6">

        <!-- Flash alerts -->
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center justify-between text-emerald-800 shadow-sm animate-fade-in">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold text-sm shadow-sm">✓</div>
                    <span class="text-sm font-bold">{{ session('success') }}</span>
                </div>
                <button type="button" @click="$el.closest('div').remove()" class="text-emerald-500 hover:text-emerald-700 font-bold p-1">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl flex items-center justify-between text-rose-800 shadow-sm animate-fade-in">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center font-bold text-sm shadow-sm">!</div>
                    <span class="text-sm font-bold">{{ session('error') }}</span>
                </div>
                <button type="button" @click="$el.closest('div').remove()" class="text-rose-500 hover:text-rose-700 font-bold p-1">&times;</button>
            </div>
        @endif

        <!-- En-tête Principal de Page -->
        <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200/80 shadow-sm relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-indigo-500/5 rounded-full blur-2xl pointer-events-none"></div>

            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 relative z-10">
                <div class="flex items-start sm:items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-700 text-white flex items-center justify-center shadow-lg shadow-indigo-500/25 flex-shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Personnel de l'Entreprise</h1>
                            <span class="px-3 py-1 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 border border-emerald-200 uppercase tracking-wider">
                                Effectif Global ({{ $totalStaff }})
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm font-medium text-slate-500 mt-1">
                            Répertoire complet de tout le personnel de Bio Farm Trading avec affectation et suivi des postes
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 flex-wrap">
                    <a href="{{ route($prefix . 'job_positions.index') }}" class="px-4 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all flex items-center gap-2 shadow-sm">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        <span>Gérer les Postes</span>
                    </a>

                    {{-- Bouton Télécharger PDF Personnel --}}
                    <a href="{{ route($prefix . 'employees.pdf') }}" target="_blank"
                       class="px-4 py-2.5 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-lg shadow-rose-600/25 transition-all flex items-center gap-2 active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                        </svg>
                        <span>Télécharger PDF</span>
                    </a>

                    <a href="{{ route($prefix . 'employees.create') }}" class="px-5 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-lg shadow-emerald-600/25 transition-all flex items-center gap-2 active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
                        <span>Nouveau Collaborateur</span>
                    </a>
                </div>
            </div>

            <!-- Filtres rapides par Postes et Statuts -->
            <div class="mt-6 pt-5 border-t border-slate-100 flex items-center gap-2 flex-wrap text-xs">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mr-1">Filtrer par poste :</span>
                
                <a href="{{ route($prefix . 'employees.index') }}" class="px-3 py-1.5 rounded-xl {{ empty($positionId) && empty($status) ? 'bg-slate-900 text-white font-bold' : 'bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold' }} transition-all text-[11px]">
                    Tous ({{ $totalStaff }})
                </a>

                @foreach($positions as $p)
                    <a href="{{ route($prefix . 'employees.index', ['position_id' => $p->id]) }}" 
                       class="px-3 py-1.5 rounded-xl {{ $positionId == $p->id ? 'bg-emerald-600 text-white font-bold shadow-sm' : 'bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 text-slate-700 font-semibold' }} transition-all text-[11px] flex items-center gap-1.5">
                        <span>{{ $p->title }}</span>
                        <span class="text-[10px] opacity-75">({{ $p->employees_count ?? 0 }})</span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Statistiques Clés avec design moderne -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="gradient-card-blue p-6 rounded-3xl border border-blue-100/80 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-black text-slate-400 uppercase tracking-wider">Effectif Global</span>
                        <p class="text-3xl font-black text-slate-900 mt-1">{{ $totalStaff }}</p>
                        <span class="text-[11px] font-semibold text-blue-600 mt-1 block">Personnel répertorié</span>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-blue-500/10 text-blue-600 flex items-center justify-center font-bold text-2xl group-hover:scale-110 transition-transform">
                        👥
                    </div>
                </div>
            </div>

            <div class="gradient-card-green p-6 rounded-3xl border border-emerald-100/80 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-black text-slate-400 uppercase tracking-wider">En Activité</span>
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        </div>
                        <p class="text-3xl font-black text-emerald-600 mt-1">{{ $activeStaff }}</p>
                        <span class="text-[11px] font-semibold text-emerald-700 mt-1 block">Collaborateurs actifs</span>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center font-bold text-2xl group-hover:scale-110 transition-transform">
                        ✅
                    </div>
                </div>
            </div>

            <div class="gradient-card-amber p-6 rounded-3xl border border-amber-100/80 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-black text-slate-400 uppercase tracking-wider">Nomenclature Postes</span>
                        <p class="text-3xl font-black text-amber-600 mt-1">{{ $positions->count() }}</p>
                        <a href="{{ route($prefix . 'job_positions.index') }}" class="text-[11px] font-semibold text-amber-700 mt-1 block hover:underline">
                            Voir les descriptions &rarr;
                        </a>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-amber-500/10 text-amber-600 flex items-center justify-center font-bold text-2xl group-hover:scale-110 transition-transform">
                        💼
                    </div>
                </div>
            </div>

            <div class="gradient-card-purple p-6 rounded-3xl border border-purple-100/80 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-black text-slate-400 uppercase tracking-wider">Masse Mensuelle Fixe</span>
                        <p class="text-2xl font-black text-purple-700 mt-1">{{ number_format($totalMonthlyPayroll, 0, ',', ' ') }} <span class="text-xs font-bold text-purple-500">FCFA</span></p>
                        <span class="text-[11px] font-semibold text-purple-600 mt-1 block">Salaires mensuels fixes</span>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-purple-500/10 text-purple-600 flex items-center justify-center font-bold text-2xl group-hover:scale-110 transition-transform">
                        💰
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres et Recherche Détaillés avec Switch de Vue -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <form method="GET" action="{{ route($prefix . 'employees.index') }}" class="flex-1 grid grid-cols-1 sm:grid-cols-4 gap-3">
                    <div class="sm:col-span-2 relative flex items-center">
                        <svg class="w-4 h-4 text-slate-400 absolute left-4 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Rechercher par nom, matricule, téléphone..." class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                    </div>

                    <div>
                        <select name="position_id" class="w-full py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-emerald-500">
                            <option value="">Tous les postes ({{ $positions->count() }})</option>
                            @foreach($positions as $pos)
                                <option value="{{ $pos->id }}" {{ $positionId == $pos->id ? 'selected' : '' }}>
                                    {{ $pos->title }} ({{ $pos->employees_count ?? 0 }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <select name="status" class="flex-1 py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-emerald-500">
                            <option value="">Tous statuts</option>
                            <option value="actif" {{ $status === 'actif' ? 'selected' : '' }}>Actif</option>
                            <option value="conge" {{ $status === 'conge' ? 'selected' : '' }}>En congé</option>
                            <option value="suspendu" {{ $status === 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                            <option value="demissionnaire" {{ $status === 'demissionnaire' ? 'selected' : '' }}>Démissionnaire</option>
                        </select>

                        <button type="submit" class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl text-xs font-bold transition-all shadow-sm">
                            Filtrer
                        </button>
                        @if($search || $positionId || $status || $contractType)
                            <a href="{{ route($prefix . 'employees.index') }}" class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl text-xs font-bold" title="Réinitialiser">
                                ✕
                            </a>
                        @endif
                    </div>
                </form>

                <!-- Switch de Vue : Tableau ou Trombinoscope -->
                <div class="flex items-center gap-1.5 p-1 bg-slate-100 rounded-2xl border border-slate-200/60 self-end lg:self-center">
                    <button type="button" @click="viewMode = 'table'" :class="viewMode === 'table' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900'" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                        <span>Tableau</span>
                    </button>
                    <button type="button" @click="viewMode = 'grid'" :class="viewMode === 'grid' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900'" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                        <span>Cartes</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- 1. VUE TABLEAU DU PERSONNEL AVEC POSTES -->
        <div x-show="viewMode === 'table'" class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            <th class="py-4 px-6">Personnel & Matricule</th>
                            <th class="py-4 px-6">Poste / Fonction</th>
                            <th class="py-4 px-6 text-center">Contrat</th>
                            <th class="py-4 px-6 text-center">Date Embauche</th>
                            <th class="py-4 px-6 text-right">Salaire Mensuel</th>
                            <th class="py-4 px-6 text-center">Statut</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                        @forelse($employees as $emp)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <!-- Personnel & Matricule -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-700 border border-indigo-200 flex items-center justify-center font-black text-sm shadow-sm flex-shrink-0">
                                            {{ substr($emp->first_name, 0, 1) }}{{ substr($emp->last_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route($prefix . 'employees.show', $emp->id) }}" class="font-bold text-slate-900 text-sm hover:text-emerald-600 transition-colors">
                                                    {{ $emp->full_name }}
                                                </a>
                                                @if($emp->gender)
                                                    <span class="text-[10px] font-bold text-slate-400">({{ $emp->gender }})</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2 mt-0.5">
                                                <span class="text-[10px] font-black font-mono text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">{{ $emp->matricule }}</span>
                                                @if($emp->phone)
                                                    <a href="tel:{{ $emp->phone }}" class="text-[11px] text-slate-500 hover:text-emerald-600">📞 {{ $emp->phone }}</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- POSTE / FONCTION (MODIFIABLE EN 1 CLIC) -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-2">
                                        <div>
                                            @if($emp->jobPosition)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-black bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-2xs">
                                                    {{ $emp->jobPosition->title }}
                                                </span>
                                                @if($emp->jobPosition->department)
                                                    <span class="text-[10px] text-slate-400 block font-medium mt-0.5">{{ $emp->jobPosition->department }}</span>
                                                @endif
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">Non affecté</span>
                                            @endif
                                        </div>
                                        <button type="button" 
                                                @click="openPosteModal({{ $emp->id }}, '{{ addslashes($emp->full_name) }}', {{ $emp->job_position_id ?? 'null' }}, '{{ route($prefix . 'employees.update_position', $emp->id) }}')" 
                                                class="p-1.5 text-slate-400 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-all" 
                                                title="Modifier le poste de {{ $emp->full_name }}">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                        </button>
                                    </div>
                                </td>

                                <!-- Contrat -->
                                <td class="py-4 px-6 text-center">
                                    @php $cb = $emp->contract_badge; @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-[11px] font-bold {{ $cb['class'] }}">
                                        {{ $cb['label'] }}
                                    </span>
                                </td>

                                <!-- Date Embauche -->
                                <td class="py-4 px-6 text-center text-slate-500">
                                    @if($emp->hired_at)
                                        <p class="font-bold text-slate-800">{{ $emp->hired_at->format('d/m/Y') }}</p>
                                        <span class="text-[10px] text-slate-400">{{ $emp->hired_at->diffForHumans(null, true) }}</span>
                                    @else
                                        <span class="text-slate-400 italic">-</span>
                                    @endif
                                </td>

                                <!-- Rémunération Mensuelle -->
                                <td class="py-4 px-6 text-right">
                                    @if($emp->monthly_salary)
                                        <span class="font-black text-slate-900 text-sm">{{ number_format($emp->monthly_salary, 0, ',', ' ') }}</span>
                                        <span class="text-[10px] font-bold text-slate-400">F/mois</span>
                                    @else
                                        <span class="text-slate-400 italic">Non renseigné</span>
                                    @endif
                                </td>

                                <!-- Statut -->
                                <td class="py-4 px-6 text-center">
                                    @php $sb = $emp->status_badge; @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $sb['class'] }}">
                                        {{ $sb['label'] }}
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route($prefix . 'employees.show', $emp->id) }}" class="p-2 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all" title="Voir la fiche complète">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </a>

                                        <a href="{{ route($prefix . 'employees.edit', $emp->id) }}" class="p-2 text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all" title="Modifier le collaborateur">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </a>

                                        <form action="{{ route($prefix . 'employees.destroy', $emp->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer {{ addslashes($emp->full_name) }} du personnel ?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all" title="Supprimer le dossier">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-16 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center max-w-md mx-auto">
                                        <div class="w-16 h-16 rounded-3xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-3xl mb-4 border border-indigo-100 shadow-sm">
                                            👥
                                        </div>
                                        <p class="text-base font-black text-slate-800">Aucun personnel trouvé</p>
                                        <p class="text-xs text-slate-400 mt-1.5 leading-relaxed">
                                            Modifiez vos filtres de recherche ou enregistrez un nouveau collaborateur.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($employees->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $employees->links() }}
                </div>
            @endif
        </div>

        <!-- 2. VUE TROMBINOSCOPE / CARTES DU PERSONNEL -->
        <div x-show="viewMode === 'grid'" x-cloak class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($employees as $emp)
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
                    <div>
                        <!-- Header Carte -->
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-700 border border-indigo-200 flex items-center justify-center font-black text-base shadow-sm">
                                    {{ substr($emp->first_name, 0, 1) }}{{ substr($emp->last_name, 0, 1) }}
                                </div>
                                <div>
                                    <a href="{{ route($prefix . 'employees.show', $emp->id) }}" class="font-black text-slate-900 text-sm hover:text-emerald-600 transition-colors block">
                                        {{ $emp->full_name }}
                                    </a>
                                    <span class="font-mono text-[10px] text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200 font-bold">
                                        {{ $emp->matricule }}
                                    </span>
                                </div>
                            </div>

                            @php $sb = $emp->status_badge; @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $sb['class'] }}">
                                {{ $sb['label'] }}
                            </span>
                        </div>

                        <!-- Poste & Contrat -->
                        <div class="mt-4 p-3 bg-slate-50 rounded-2xl border border-slate-100 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-slate-400 uppercase">Poste</span>
                                <div class="flex items-center gap-1.5">
                                    <span class="text-xs font-black text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded-lg border border-emerald-200">
                                        {{ $emp->jobPosition ? $emp->jobPosition->title : 'Non affecté' }}
                                    </span>
                                    <button type="button" 
                                            @click="openPosteModal({{ $emp->id }}, '{{ addslashes($emp->full_name) }}', {{ $emp->job_position_id ?? 'null' }}, '{{ route($prefix . 'employees.update_position', $emp->id) }}')" 
                                            class="text-slate-400 hover:text-emerald-700 p-0.5">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    </button>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-slate-400 uppercase">Contrat</span>
                                @php $cb = $emp->contract_badge; @endphp
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-lg {{ $cb['class'] }}">{{ $cb['label'] }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-slate-400 uppercase">Salaire Mensuel</span>
                                <span class="text-xs font-black text-slate-800">
                                    @if($emp->monthly_salary)
                                        {{ number_format($emp->monthly_salary, 0, ',', ' ') }} FCFA/m
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- Contacts -->
                        <div class="mt-3 space-y-1 text-xs text-slate-600">
                            @if($emp->phone)
                                <div class="flex items-center gap-2">
                                    <span class="text-slate-400">📞</span>
                                    <a href="tel:{{ $emp->phone }}" class="hover:text-emerald-600 font-semibold">{{ $emp->phone }}</a>
                                </div>
                            @endif
                            @if($emp->email)
                                <div class="flex items-center gap-2 truncate">
                                    <span class="text-slate-400">✉️</span>
                                    <a href="mailto:{{ $emp->email }}" class="hover:text-emerald-600 font-semibold truncate">{{ $emp->email }}</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <a href="{{ route($prefix . 'employees.show', $emp->id) }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                            <span>Voir le dossier</span> &rarr;
                        </a>

                        <div class="flex items-center gap-1">
                            <a href="{{ route($prefix . 'employees.edit', $emp->id) }}" class="p-2 text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all" title="Modifier">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </a>

                            <form action="{{ route($prefix . 'employees.destroy', $emp->id) }}" method="POST" onsubmit="return confirm('Confirmez-vous la suppression de {{ addslashes($emp->full_name) }} ?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all" title="Supprimer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center text-slate-400">
                    <p class="font-bold">Aucun personnel trouvé</p>
                </div>
            @endforelse
        </div>

        <!-- ========================================================= -->
        <!-- MODAL D'AFFECTATION RAPIDE DU POSTE (RH / DIRECTION)     -->
        <!-- ========================================================= -->
        <div x-show="showPosteModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showPosteModal = false"></div>

                <div class="inline-block w-full max-w-md my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl border border-slate-200 relative z-10">
                    <form method="POST" :action="updatePositionUrl">
                        @csrf
                        @method('PATCH')

                        <div class="px-6 py-5 bg-gradient-to-r from-emerald-700 to-teal-800 text-white flex justify-between items-center">
                            <div>
                                <h3 class="text-base font-black uppercase tracking-tight">Affecter un Poste</h3>
                                <p class="text-xs text-emerald-100 mt-0.5" x-text="selectedEmployeeName"></p>
                            </div>
                            <button type="button" @click="showPosteModal = false" class="text-emerald-200 hover:text-white p-1 rounded-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <div class="p-6 space-y-4">
                            <div>
                                <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">
                                    Sélectionnez le poste / fonction :
                                </label>
                                <select name="job_position_id" x-model="selectedPositionId" required class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-2xl text-xs font-bold text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/20 transition-all">
                                    <option value="" disabled>-- Choisir un poste --</option>
                                    @foreach($positions as $p)
                                        <option value="{{ $p->id }}">
                                            {{ $p->title }} ({{ $p->department }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <p class="text-[11px] text-slate-400 leading-relaxed">
                                Le poste sera immédiatement actualisé sur la fiche du collaborateur, son historique et les registres opérationnels.
                            </p>

                            <div class="pt-2 flex items-center justify-end gap-3">
                                <button type="button" @click="showPosteModal = false" class="px-4 py-2.5 rounded-2xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-all">
                                    Annuler
                                </button>
                                <button type="submit" class="px-6 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black uppercase tracking-wider shadow-lg shadow-emerald-600/20 transition-all active:scale-95">
                                    Enregistrer l'Affectation
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
