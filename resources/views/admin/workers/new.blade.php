<x-app-layout>
    <div class="py-8 bg-slate-50 min-h-screen" x-data="{
        showNewRateModal: false,
        newRecruitRate: {{ $defaultNewRate }},
        regularRate: {{ $defaultRate }},
        selectedWorker: null,
        showWorkerModal: false,
        workerRateInput: ''
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span class="text-sm font-bold">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Header Section -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-purple-100 border border-purple-200 flex items-center justify-center text-purple-700 shadow-sm flex-shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-purple-100 text-purple-800 border border-purple-200">
                                Ressources Humaines &bull; Recrues
                            </span>
                        </div>
                        <h1 class="text-2xl font-black text-slate-800 tracking-tight mt-1">
                            Nouveaux Ouvriers <span class="text-purple-600">Bio Farm</span>
                        </h1>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-0.5">
                            Gestion des recrues récentes et paramétrage du taux horaire d'intégration
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button type="button" 
                            @click="showNewRateModal = true"
                            class="px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-2xl font-black text-xs shadow-md shadow-purple-600/20 transition-all flex items-center gap-2 active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                        <span>PARAMÉTRER TAUX NOUVEAUX</span>
                    </button>

                    <a href="{{ route($prefix . 'salaries.index') }}" 
                       class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl font-bold text-xs border border-slate-200 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span>État des Salaires</span>
                    </a>
                </div>
            </div>

            <!-- Grille d'information des taux -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="bg-gradient-to-br from-purple-600 to-indigo-700 rounded-3xl p-6 text-white shadow-lg shadow-purple-600/15">
                    <span class="text-xs font-black uppercase tracking-wider text-purple-200">Taux Nouveaux Ouvriers</span>
                    <div class="text-3xl font-black mt-2">
                        {{ number_format($defaultNewRate, 0, ',', ' ') }} <span class="text-lg font-bold text-purple-200">FCFA / h</span>
                    </div>
                    <p class="text-[11px] text-purple-100 mt-2">Appliqué automatiquement aux ouvriers en période d'intégration</p>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-400">Taux Anciens Ouvriers</span>
                    <div class="text-3xl font-black text-slate-800 mt-2">
                        {{ number_format($defaultRate, 0, ',', ' ') }} <span class="text-lg font-bold text-slate-400">FCFA / h</span>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-2">Rémunération standard des ouvriers titularisés</p>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-400">Nouveaux Ouvriers Détectés</span>
                    <div class="text-3xl font-black text-purple-600 mt-2">
                        {{ $newWorkers->count() }} <span class="text-lg font-bold text-slate-400">ouvrier(s)</span>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-2">Ajoutés récemment ou avec statut spécifique</p>
                </div>
            </div>

            <!-- Tableau des Nouveaux Ouvriers -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-black text-slate-800 uppercase tracking-tight">Liste des Recrues & Nouveaux Ouvriers</h2>
                        <p class="text-xs text-slate-400">Consultez et modifiez les conditions d'intégration pour chaque ouvrier</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-black uppercase tracking-wider text-slate-500">
                                <th class="py-4 px-6">N°</th>
                                <th class="py-4 px-6">Ouvrier</th>
                                <th class="py-4 px-6">Équipe</th>
                                <th class="py-4 px-6">Date d'Ajout</th>
                                <th class="py-4 px-6">Taux Applicable</th>
                                <th class="py-4 px-6">Statut Actuel</th>
                                <th class="py-4 px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($newWorkers as $index => $worker)
                                @php
                                    $effectiveRate = $worker->getEffectiveHourlyRate($defaultRate, $defaultNewRate);
                                    $hasCustomRate = $worker->hourly_rate !== null && $worker->hourly_rate > 0;
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-4 px-6 font-bold text-slate-400 text-xs">{{ $index + 1 }}</td>
                                    <td class="py-4 px-6">
                                        <div class="font-black text-slate-900 uppercase">{{ $worker->last_name }}</div>
                                        <div class="text-xs text-slate-500">{{ $worker->first_name }}</div>
                                    </td>
                                    <td class="py-4 px-6">
                                        @if($worker->shift === 'night')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span> Nuit
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Jour
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-xs text-slate-600 font-medium">
                                        {{ $worker->created_at ? $worker->created_at->format('d/m/Y') : '-' }}
                                        <span class="text-slate-400 text-[10px] block">
                                            ({{ $worker->created_at ? $worker->created_at->diffForHumans() : '' }})
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="font-black text-slate-900">{{ number_format($effectiveRate, 0, ',', ' ') }} FCFA/h</span>
                                        @if($hasCustomRate)
                                            <span class="block text-[10px] font-bold text-blue-600">Taux personnalisé</span>
                                        @else
                                            <span class="block text-[10px] font-bold text-purple-600">Taux Recrue</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6">
                                        @if($worker->isNew())
                                            <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-purple-100 text-purple-800 border border-purple-200">
                                                Nouveau
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200">
                                                Ancien
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <div class="inline-flex items-center gap-2">
                                            <!-- Titulariser / Basculer en Ancien -->
                                            <form method="POST" action="{{ route($prefix . 'workers.update_status', $worker->id) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="is_new" value="{{ $worker->isNew() ? '0' : '1' }}">
                                                <button type="submit" 
                                                        class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all border {{ $worker->isNew() ? 'bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-purple-50 hover:bg-purple-100 text-purple-700 border-purple-200' }}"
                                                        title="{{ $worker->isNew() ? 'Passer cet ouvrier au statut Ancien' : 'Marquer comme Nouveau' }}">
                                                    {{ $worker->isNew() ? 'Titulariser (Ancien)' : 'Remettre Nouveau' }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-slate-400">
                                        <p class="font-bold text-base">Aucun nouvel ouvrier enregistré récemment.</p>
                                        <p class="text-xs mt-1">Tous les ouvriers ont été titularisés ou sont créés depuis plus de 60 jours.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Modal de paramétrage du Taux Horaire Nouveaux & Anciens -->
        <div x-show="showNewRateModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showNewRateModal = false"></div>

                <div class="inline-block w-full max-w-lg my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl border border-slate-200 relative z-10">
                    <form method="POST" action="{{ route($prefix . 'salaries.update_rate') }}">
                        @csrf
                        <div class="px-6 py-5 bg-gradient-to-r from-purple-900 to-indigo-900 text-white flex justify-between items-center">
                            <div>
                                <h3 class="text-base font-black uppercase tracking-tight">Paramétrer les Taux Horaires</h3>
                                <p class="text-[11px] text-purple-200">Définir les tarifs par heure selon l'ancienneté</p>
                            </div>
                            <button type="button" @click="showNewRateModal = false" class="text-purple-300 hover:text-white p-1 rounded-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <div class="p-6 space-y-5">
                            <div>
                                <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">
                                    Taux Horaire des NOUVEAUX Ouvriers (FCFA / h)
                                </label>
                                <div class="relative">
                                    <input type="number" 
                                           name="new_hourly_rate" 
                                           x-model.number="newRecruitRate" 
                                           min="1" 
                                           max="100000" 
                                           required 
                                           class="w-full text-2xl font-black text-purple-700 px-4 py-3 bg-purple-50 border-2 border-purple-200 rounded-2xl focus:bg-white focus:border-purple-600 focus:ring-4 focus:ring-purple-600/20 text-center">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-black uppercase text-purple-400">FCFA / h</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">
                                    Taux Horaire des ANCIENS Ouvriers (FCFA / h)
                                </label>
                                <div class="relative">
                                    <input type="number" 
                                           name="hourly_rate" 
                                           x-model.number="regularRate" 
                                           min="1" 
                                           max="100000" 
                                           required 
                                           class="w-full text-2xl font-black text-slate-900 px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-2xl focus:bg-white focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/20 text-center">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-black uppercase text-slate-400">FCFA / h</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                                <button type="button" @click="showNewRateModal = false" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">
                                    Annuler
                                </button>
                                <button type="submit" class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-black shadow-md shadow-purple-600/20">
                                    Enregistrer les Taux
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
