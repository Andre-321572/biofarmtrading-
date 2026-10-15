<x-app-layout>
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-6">

        <!-- Flash alerts -->
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center justify-between text-emerald-800 shadow-sm animate-fade-in">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold">✓</div>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
                <button type="button" @click="$el.closest('div').remove()" class="text-emerald-500 hover:text-emerald-700 font-bold">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl flex items-center justify-between text-rose-800 shadow-sm animate-fade-in">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center font-bold">!</div>
                    <span class="text-sm font-semibold">{{ session('error') }}</span>
                </div>
                <button type="button" @click="$el.closest('div').remove()" class="text-rose-500 hover:text-rose-700 font-bold">&times;</button>
            </div>
        @endif

        <!-- En-tête de page -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Personnel de l'Entreprise</h1>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-indigo-50 text-indigo-700 border border-indigo-200 uppercase tracking-wider">Permanent</span>
                        </div>
                        <p class="text-xs font-semibold text-slate-500 mt-0.5">Registre des collaborateurs et salariés Bio Farm Trading (distinct des ouvriers journaliers de production)</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route($prefix . 'job_positions.index') }}" class="px-4 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    Gérer les Postes
                </a>
                <a href="{{ route($prefix . 'employees.create') }}" class="px-5 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-lg shadow-emerald-600/20 transition-all flex items-center gap-2 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
                    Nouveau Personnel
                </a>
            </div>
        </div>

        <!-- Statistiques Clés -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg">
                    👥
                </div>
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Effectif Total</span>
                    <p class="text-2xl font-black text-slate-900">{{ $totalStaff }}</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg">
                    ✅
                </div>
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">En Activité</span>
                    <p class="text-2xl font-black text-emerald-600">{{ $activeStaff }}</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg">
                    🏖️
                </div>
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">En Congé</span>
                    <p class="text-2xl font-black text-amber-600">{{ $onLeaveStaff }}</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-lg">
                    💰
                </div>
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Masse Salariale Mensuelle</span>
                    <p class="text-xl font-black text-purple-600">{{ number_format($totalMonthlyPayroll, 0, ',', ' ') }} <span class="text-xs font-semibold text-slate-400">FCFA</span></p>
                </div>
            </div>
        </div>

        <!-- Filtres et Recherche -->
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm">
            <form method="GET" action="{{ route($prefix . 'employees.index') }}" class="grid grid-cols-1 sm:grid-cols-5 gap-3">
                <div class="sm:col-span-2 relative">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Rechercher par nom, matricule, téléphone..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>

                <div>
                    <select name="position_id" class="w-full py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-emerald-500">
                        <option value="">Tous les postes</option>
                        @foreach($positions as $pos)
                            <option value="{{ $pos->id }}" {{ $positionId == $pos->id ? 'selected' : '' }}>{{ $pos->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <select name="status" class="w-full py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-emerald-500">
                        <option value="">Tous les statuts</option>
                        <option value="actif" {{ $status === 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="conge" {{ $status === 'conge' ? 'selected' : '' }}>En congé</option>
                        <option value="suspendu" {{ $status === 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                        <option value="demissionnaire" {{ $status === 'demissionnaire' ? 'selected' : '' }}>Démissionnaire</option>
                        <option value="licencie" {{ $status === 'licencie' ? 'selected' : '' }}>Licencié</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="flex-1 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl text-xs font-bold transition-all text-center">
                        Filtrer
                    </button>
                    @if($search || $positionId || $status || $contractType)
                        <a href="{{ route($prefix . 'employees.index') }}" class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl text-xs font-bold" title="Réinitialiser">
                            ✕
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tableau du Personnel -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-100 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            <th class="py-4 px-6">Personnel</th>
                            <th class="py-4 px-6">Poste & Service</th>
                            <th class="py-4 px-6">Contrat</th>
                            <th class="py-4 px-6">Date Embauche</th>
                            <th class="py-4 px-6">Salaire Mensuel</th>
                            <th class="py-4 px-6 text-center">Statut</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                        @forelse($employees as $emp)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-2xl bg-indigo-50 text-indigo-700 flex items-center justify-center font-black text-xs border border-indigo-100">
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
                                                    <span class="text-[11px] text-slate-400">📞 {{ $emp->phone }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    @if($emp->jobPosition)
                                        <p class="font-bold text-slate-900">{{ $emp->jobPosition->title }}</p>
                                        @if($emp->jobPosition->department)
                                            <span class="text-[10px] text-slate-400">{{ $emp->jobPosition->department }}</span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">Non affecté</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    @php $cb = $emp->contract_badge; @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-[11px] font-bold {{ $cb['class'] }}">
                                        {{ $cb['label'] }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-slate-500">
                                    @if($emp->hired_at)
                                        <p class="font-bold text-slate-800">{{ $emp->hired_at->format('d/m/Y') }}</p>
                                        <span class="text-[10px] text-slate-400">{{ $emp->hired_at->diffForHumans(null, true) }}</span>
                                    @else
                                        <span class="text-slate-400 italic">-</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    @if($emp->monthly_salary)
                                        <span class="font-black text-slate-900">{{ number_format($emp->monthly_salary, 0, ',', ' ') }}</span>
                                        <span class="text-[10px] font-bold text-slate-400">FCFA/mois</span>
                                    @else
                                        <span class="text-slate-400 italic">Non renseigné</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-center">
                                    @php $sb = $emp->status_badge; @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $sb['class'] }}">
                                        {{ $sb['label'] }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route($prefix . 'employees.show', $emp->id) }}" class="p-2 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all" title="Voir la fiche">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </a>

                                        <a href="{{ route($prefix . 'employees.edit', $emp->id) }}" class="p-2 text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all" title="Modifier">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </a>

                                        <form action="{{ route($prefix . 'employees.destroy', $emp->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer {{ addslashes($emp->full_name) }} du personnel ?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all" title="Supprimer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-xl mb-3">👥</div>
                                        <p class="font-bold text-slate-600">Aucun personnel enregistré</p>
                                        <p class="text-xs text-slate-400 mt-1">Enregistrez les employés permanents (Comptable, Chauffeur, Gardien, etc.)</p>
                                        <a href="{{ route($prefix . 'employees.create') }}" class="mt-4 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all">
                                            + Enregistrer un personnel
                                        </a>
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

    </div>
</x-app-layout>
