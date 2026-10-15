<x-app-layout>
    <div class="py-8 bg-slate-50 min-h-screen" x-data="{
        showNewRateModal: false,
        showEditWorkerModal: false,
        newRecruitRate: '{{ number_format($defaultNewRate, 2, '.', '') }}',
        regularRate: '{{ number_format($defaultRate, 2, '.', '') }}',
        currentWorker: null,
        workerForm: {
            id: null,
            name: '',
            hired_at: '',
            is_new: 'null',
            hourly_rate: ''
        },
        openEditWorker(worker) {
            this.currentWorker = worker;
            this.workerForm.id = worker.id;
            this.workerForm.name = worker.last_name + ' ' + worker.first_name;
            this.workerForm.hired_at = worker.hired_at_raw || '';
            this.workerForm.is_new = worker.is_new_raw === null ? 'null' : (worker.is_new_raw ? '1' : '0');
            this.workerForm.hourly_rate = worker.hourly_rate_raw || '';
            this.showEditWorkerModal = true;
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Error Messages -->
            @if($errors->any())
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 shadow-xs space-y-1">
                    <div class="flex items-center gap-2 font-black text-xs uppercase tracking-wider text-rose-900">
                        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Erreur de saisie</span>
                    </div>
                    @foreach($errors->all() as $error)
                        <p class="text-xs font-semibold text-rose-700 pl-6">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Flash Success Messages -->
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
                                Ressources Humaines &bull; Recrues & Période d'Essai
                            </span>
                        </div>
                        <h1 class="text-2xl font-black text-slate-800 tracking-tight mt-1">
                            Période d'Essai & <span class="text-purple-600">Nouveaux Ouvriers</span>
                        </h1>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-0.5">
                            Période d'essai automatique de 14 jours ({{ number_format($defaultNewRate, 2, ',', ' ') }} FCFA/h) &bull; Titularisation automatique dès J+15 ({{ number_format($defaultRate, 2, ',', ' ') }} FCFA/h)
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button type="button" 
                            @click="showNewRateModal = true"
                            class="px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-2xl font-black text-xs shadow-md shadow-purple-600/20 transition-all flex items-center gap-2 active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                        <span>CONFIGURER LES TAUX</span>
                    </button>

                    <a href="{{ route($prefix . 'salaries.index') }}" 
                       class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl font-bold text-xs border border-slate-200 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span>État des Salaires</span>
                    </a>
                </div>
            </div>

            <!-- Grille d'information des taux & automatisation -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="bg-white rounded-3xl p-6 border-2 border-purple-300 shadow-xs relative overflow-hidden" style="background-color: #ffffff; border-color: #d8b4fe;">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black uppercase tracking-wider text-purple-700" style="color: #7e22ce;">Taux Période d'Essai (14 jours)</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-purple-100 text-purple-800 border border-purple-200" style="background-color: #f3e8ff; color: #6b21a8; border-color: #e9d5ff;">
                            Nouveaux
                        </span>
                    </div>
                    <div class="text-3xl font-black text-slate-900 mt-2" style="color: #0f172a;">
                        {{ number_format($defaultNewRate, 2, ',', ' ') }} <span class="text-lg font-bold text-purple-600" style="color: #9333ea;">FCFA / h</span>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-2" style="color: #64748b;">Appliqué durant les 14 premiers jours d'intégration</p>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-400">Taux Titularisé (Anciens)</span>
                    <div class="text-3xl font-black text-slate-800 mt-2">
                        {{ number_format($defaultRate, 2, ',', ' ') }} <span class="text-lg font-bold text-slate-400">FCFA / h</span>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-2">Bascule automatique après les 14 jours d'essai sans action requise</p>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-400">Ouvriers Détectés Récemment</span>
                    <div class="text-3xl font-black text-purple-600 mt-2">
                        {{ $newWorkers->count() }} <span class="text-lg font-bold text-slate-400">ouvrier(s)</span>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-2">En période d'essai ou récemment embauchés</p>
                </div>
            </div>

            <!-- Tableau des Recrues et Suivi de Période d'Essai -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-black text-slate-800 uppercase tracking-tight">Suivi des Recrues & Évolution Période d'Essai</h2>
                        <p class="text-xs text-slate-400">Contrôlez les dates d'embauche, les fins de période d'essai (14 jours) et les taux appliqués</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-black uppercase tracking-wider text-slate-500">
                                <th class="py-4 px-6">N°</th>
                                <th class="py-4 px-6">Ouvrier</th>
                                <th class="py-4 px-4 text-center">Équipe</th>
                                <th class="py-4 px-4 text-center">Date Embauche</th>
                                <th class="py-4 px-4 text-center">Fin Essai (14j)</th>
                                <th class="py-4 px-4 text-center">Taux Applicable</th>
                                <th class="py-4 px-4 text-center">Statut Actuel</th>
                                <th class="py-4 px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($newWorkers as $index => $worker)
                                @php
                                    $hiredAt = $worker->getHiredAt();
                                    $trialEndsAt = $worker->getTrialEndsAt();
                                    $inTrial = $worker->isCurrentlyInTrial();
                                    $remainingDays = $worker->getTrialDaysRemaining();
                                    $effectiveRate = $worker->getEffectiveHourlyRate($defaultRate, $defaultNewRate);
                                    $hasCustomRate = $worker->hourly_rate !== null && $worker->hourly_rate > 0;
                                    
                                    $workerData = [
                                        'id' => $worker->id,
                                        'first_name' => $worker->first_name,
                                        'last_name' => $worker->last_name,
                                        'hired_at_raw' => $worker->hired_at ? $worker->hired_at->format('Y-m-d') : ($worker->created_at ? $worker->created_at->format('Y-m-d') : ''),
                                        'is_new_raw' => $worker->is_new,
                                        'hourly_rate_raw' => $worker->hourly_rate,
                                    ];
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-4 px-6 font-bold text-slate-400 text-xs">{{ $index + 1 }}</td>
                                    
                                    <td class="py-4 px-6">
                                        <div class="font-black text-slate-900 uppercase">{{ $worker->last_name }}</div>
                                        <div class="text-xs text-slate-500">{{ $worker->first_name }}</div>
                                    </td>

                                    <td class="py-4 px-4 text-center">
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

                                    <!-- Date Embauche -->
                                    <td class="py-4 px-4 text-center text-xs text-slate-700 font-bold">
                                        {{ $hiredAt->format('d/m/Y') }}
                                        <span class="text-[10px] text-slate-400 block font-normal">
                                            ({{ $hiredAt->diffForHumans() }})
                                        </span>
                                    </td>

                                    <!-- Fin Essai (14j) -->
                                    <td class="py-4 px-4 text-center text-xs">
                                        <span class="font-bold text-slate-800">{{ $trialEndsAt->format('d/m/Y') }}</span>
                                        @if($inTrial)
                                            <span class="block text-[10px] font-bold text-purple-600">
                                                Reste {{ $remainingDays }} jour(s)
                                            </span>
                                        @else
                                            <span class="block text-[10px] font-bold text-emerald-600">
                                                Période achevée
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Taux Applicable -->
                                    <td class="py-4 px-4 text-center">
                                        <span class="font-black text-slate-900">{{ number_format($effectiveRate, 2, ',', ' ') }} F</span>
                                        @if($hasCustomRate)
                                            <span class="block text-[10px] font-bold text-blue-600">Taux personnalisé</span>
                                        @elseif($worker->isNew())
                                            <span class="block text-[10px] font-bold text-purple-600">Taux Essai</span>
                                        @else
                                            <span class="block text-[10px] font-bold text-emerald-600">Taux Titularisé</span>
                                        @endif
                                    </td>

                                    <!-- Statut Actuel -->
                                    <td class="py-4 px-4 text-center">
                                        @if($worker->is_new === true)
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-purple-100 text-purple-800 border border-purple-200">
                                                Essai forcé (RH)
                                            </span>
                                        @elseif($worker->is_new === false)
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                Titularisé (RH)
                                            </span>
                                        @elseif($inTrial)
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-purple-50 text-purple-700 border border-purple-300">
                                                En essai ({{ $remainingDays }}j)
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200">
                                                Titularisé auto
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Actions -->
                                    <td class="py-4 px-6 text-right">
                                        <div class="inline-flex items-center gap-2">
                                            <!-- Bouton Modifier profil et date d'embauche -->
                                            <button type="button" 
                                                    @click="openEditWorker({{ json_encode($workerData) }})"
                                                    class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all bg-slate-100 hover:bg-purple-50 hover:text-purple-700 text-slate-700 border border-slate-200 hover:border-purple-200 flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                                <span>Ajuster</span>
                                            </button>

                                            <!-- Bascule rapide -->
                                            <form method="POST" action="{{ route($prefix . 'workers.update_status', $worker->id) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="is_new" value="{{ $worker->isNew() ? '0' : '1' }}">
                                                <button type="submit" 
                                                        class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all border {{ $worker->isNew() ? 'bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-purple-50 hover:bg-purple-100 text-purple-700 border-purple-200' }}"
                                                        title="{{ $worker->isNew() ? 'Titulariser immédiatement' : 'Remettre en période d\'essai' }}">
                                                    {{ $worker->isNew() ? 'Titulariser' : 'Mettre Essai' }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-12 text-center text-slate-400">
                                        <p class="font-bold text-base">Aucun ouvrier en période d'essai ou récemment embauché.</p>
                                        <p class="text-xs mt-1">Tous les ouvriers ont achevé leurs 14 jours d'essai et sont rémunérés au tarif standard.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- ==================================================== -->
        <!-- MODAL 1 : CONFIGURATION GÉNÉRALE DES TAUX (RH/ADMIN) -->
        <!-- ==================================================== -->
        <div x-show="showNewRateModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showNewRateModal = false"></div>

                <div class="inline-block w-full max-w-lg my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl border border-slate-200 relative z-10">
                    <form method="POST" action="{{ route($prefix . 'salaries.update_rate') }}">
                        @csrf
                        <div class="px-6 py-5 bg-gradient-to-r from-purple-900 to-indigo-900 text-white flex justify-between items-center" style="background-color: #581c87; color: #ffffff;">
                            <div>
                                <h3 class="text-base font-black uppercase tracking-tight">Paramétrer les Taux Horaires</h3>
                                <p class="text-[11px] text-purple-200">Définir les tarifs par heure selon la période d'essai (14 jours)</p>
                            </div>
                            <button type="button" @click="showNewRateModal = false" class="text-purple-300 hover:text-white p-1 rounded-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <div class="p-6 space-y-5">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-xs font-black uppercase tracking-wider text-purple-700">
                                        Taux Période d'Essai (14 premiers jours)
                                    </label>
                                    <span class="text-[10px] font-bold text-purple-600 bg-purple-50 px-2 py-0.5 rounded-full border border-purple-200">
                                        Défaut : 218.75 FCFA
                                    </span>
                                </div>
                                <div class="relative">
                                    <input type="text" 
                                           inputmode="decimal" 
                                           name="new_hourly_rate" 
                                           x-model="newRecruitRate" 
                                           placeholder="Ex : 218,75 ou 218.75" 
                                           required 
                                           class="w-full text-2xl font-black text-purple-700 px-4 py-3 bg-purple-50 border-2 border-purple-200 rounded-2xl focus:bg-white focus:border-purple-600 focus:ring-4 focus:ring-purple-600/20 text-center">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-black uppercase text-purple-400">FCFA / h</span>
                                </div>
                                <div class="flex items-center gap-2 mt-2">
                                    <button type="button" @click="newRecruitRate = '218,75'" class="px-2.5 py-1 rounded-xl text-xs font-bold bg-purple-100 hover:bg-purple-200 text-purple-800 transition-colors">
                                        Fixer à 218,75 FCFA
                                    </button>
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-xs font-black uppercase tracking-wider text-slate-700">
                                        Taux Standard (Anciens ouvriers dès J+15)
                                    </label>
                                    <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full border border-slate-200">
                                        Défaut : 275.00 FCFA
                                    </span>
                                </div>
                                <div class="relative">
                                    <input type="text" 
                                           inputmode="decimal" 
                                           name="hourly_rate" 
                                           x-model="regularRate" 
                                           placeholder="Ex : 275 ou 275,00" 
                                           required 
                                           class="w-full text-2xl font-black text-slate-900 px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-2xl focus:bg-white focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/20 text-center">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-black uppercase text-slate-400">FCFA / h</span>
                                </div>
                                <div class="flex items-center gap-2 mt-2">
                                    <button type="button" @click="regularRate = '275'" class="px-2.5 py-1 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-800 transition-colors">
                                        Fixer à 275 FCFA
                                    </button>
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

        <!-- ==================================================== -->
        <!-- MODAL 2 : AJUSTEMENT INDIVIDUEL D'UN OUVRIER         -->
        <!-- ==================================================== -->
        <div x-show="showEditWorkerModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showEditWorkerModal = false"></div>

                <div class="inline-block w-full max-w-lg my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl border border-slate-200 relative z-10">
                    <form method="POST" :action="'{{ url($prefix . 'workers') }}/' + workerForm.id + '/status'">
                        @csrf
                        @method('PATCH')

                        <div class="px-6 py-5 bg-gradient-to-r from-slate-900 to-purple-950 text-white flex justify-between items-center" style="background-color: #0f172a; color: #ffffff;">
                            <div>
                                <h3 class="text-base font-black uppercase tracking-tight" x-text="workerForm.name"></h3>
                                <p class="text-[11px] text-purple-200">Date d'embauche & Conditions salariales</p>
                            </div>
                            <button type="button" @click="showEditWorkerModal = false" class="text-slate-400 hover:text-white p-1 rounded-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <div class="p-6 space-y-4">
                            <!-- Date d'embauche -->
                            <div>
                                <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">
                                    Date d'embauche de l'ouvrier
                                </label>
                                <input type="date" 
                                       name="hired_at" 
                                       x-model="workerForm.hired_at" 
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold text-slate-800 focus:bg-white focus:border-purple-600 focus:ring-2 focus:ring-purple-600/20">
                                <p class="text-[11px] text-slate-400 mt-1">
                                    La période d'essai de 14 jours démarre à cette date.
                                </p>
                            </div>

                            <!-- Statut de l'ouvrier -->
                            <div>
                                <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">
                                    Règle de Statut
                                </label>
                                <select name="is_new" 
                                        x-model="workerForm.is_new" 
                                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold text-slate-800 focus:bg-white focus:border-purple-600 focus:ring-2 focus:ring-purple-600/20">
                                    <option value="null">Automatique (Période d'essai de 14 jours)</option>
                                    <option value="1">Forcer en Période d'Essai (Taux Nouveaux)</option>
                                    <option value="0">Titulariser immédiatement (Taux Anciens)</option>
                                </select>
                            </div>

                            <!-- Taux horaire individuel optionnel -->
                            <div>
                                <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">
                                    Taux horaire spécifique personnalisé (optionnel)
                                </label>
                                <div class="relative">
                                    <input type="text" 
                                           inputmode="decimal" 
                                           name="hourly_rate" 
                                           x-model="workerForm.hourly_rate" 
                                           placeholder="Ex : 218,75 ou 300 (laisser vide pour tarif standard)" 
                                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold text-slate-800 focus:bg-white focus:border-purple-600 focus:ring-2 focus:ring-purple-600/20">
                                    <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">FCFA/h</span>
                                </div>
                                <p class="text-[11px] text-slate-400 mt-1">
                                    Si spécifié, ce taux sera appliqué de manière prioritaire pour cet ouvrier.
                                </p>
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                                <button type="button" @click="showEditWorkerModal = false" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">
                                    Annuler
                                </button>
                                <button type="submit" class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-black shadow-md shadow-purple-600/20">
                                    Mettre à jour l'Ouvrier
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
