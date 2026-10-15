<x-app-layout>
    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto space-y-6">

        <!-- En-tête avec bouton retour -->
        <div class="flex items-center justify-between bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-3">
                <a href="{{ route($prefix . 'employees.index') }}" class="w-10 h-10 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center font-black transition-all">
                    ←
                </a>
                <div>
                    <h1 class="text-xl font-black text-slate-900 tracking-tight">Fiche Salarié</h1>
                    <p class="text-xs font-semibold text-slate-500 mt-0.5">Bio Farm Trading &bull; Personnel Permanent</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route($prefix . 'employees.edit', $personnel->id) }}" class="px-5 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-lg shadow-emerald-600/20 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    Modifier la fiche
                </a>
            </div>
        </div>

        <!-- Carte Principale Profil -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-emerald-950 p-6 sm:p-8 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div class="w-20 h-20 rounded-3xl bg-emerald-500/20 border-2 border-emerald-400/40 text-emerald-300 flex items-center justify-center font-black text-2xl shadow-xl">
                        {{ substr($personnel->first_name, 0, 1) }}{{ substr($personnel->last_name, 0, 1) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-3">
                            <h2 class="text-2xl font-black tracking-tight">{{ $personnel->full_name }}</h2>
                            @php $sb = $personnel->status_badge; @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $sb['class'] }}">
                                {{ $sb['label'] }}
                            </span>
                        </div>
                        <div class="flex flex-wrap items-center gap-2 mt-2">
                            <span class="font-mono text-xs bg-white/10 px-2.5 py-0.5 rounded-lg border border-white/10 text-emerald-300 font-bold">
                                {{ $personnel->matricule }}
                            </span>
                            @if($personnel->jobPosition)
                                <span class="text-xs bg-emerald-500/20 text-emerald-200 px-3 py-0.5 rounded-lg font-bold border border-emerald-500/30">
                                    {{ $personnel->jobPosition->title }}
                                </span>
                                @if($personnel->jobPosition->department)
                                    <span class="text-xs text-slate-300">&bull; {{ $personnel->jobPosition->department }}</span>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <div class="text-right sm:border-l sm:border-slate-700/60 sm:pl-6">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block">Salaire Mensuel</span>
                    <p class="text-2xl font-black text-emerald-400 mt-1">
                        {{ $personnel->monthly_salary ? number_format($personnel->monthly_salary, 0, ',', ' ') : '0' }}
                        <span class="text-xs font-bold text-slate-300">FCFA</span>
                    </p>
                </div>
            </div>

            <!-- Détails en 2 colonnes -->
            <div class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-2 gap-8 divide-y md:divide-y-0 md:divide-x divide-slate-100">
                
                <!-- Colonne Gauche : Coordonnées & État Civil -->
                <div class="space-y-6">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">Coordonnées & Informations Personnelles</h3>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-2xl">
                            <span class="text-xs font-bold text-slate-500">Numéro de Téléphone</span>
                            <span class="text-xs font-black text-slate-900">{{ $personnel->phone ?: 'Non renseigné' }}</span>
                        </div>

                        <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-2xl">
                            <span class="text-xs font-bold text-slate-500">Adresse Email</span>
                            <span class="text-xs font-black text-slate-900">{{ $personnel->email ?: 'Non renseigné' }}</span>
                        </div>

                        <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-2xl">
                            <span class="text-xs font-bold text-slate-500">Genre / Sexe</span>
                            <span class="text-xs font-black text-slate-900">{{ $personnel->gender === 'M' ? 'Masculin' : ($personnel->gender === 'F' ? 'Féminin' : 'Non spécifié') }}</span>
                        </div>

                        <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-2xl">
                            <span class="text-xs font-bold text-slate-500">N° Pièce d'identité (CNI)</span>
                            <span class="text-xs font-mono font-bold text-slate-900">{{ $personnel->national_id ?: 'Non renseigné' }}</span>
                        </div>

                        <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-2xl">
                            <span class="text-xs font-bold text-slate-500">Adresse de résidence</span>
                            <span class="text-xs font-bold text-slate-900">{{ $personnel->address ?: 'Non renseigné' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Colonne Droite : Contrat, Poste & Urgence -->
                <div class="space-y-6 md:pl-8 pt-6 md:pt-0">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">Contrat & Affectation</h3>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-2xl">
                            <span class="text-xs font-bold text-slate-500">Poste Officiel</span>
                            <span class="text-xs font-black text-slate-900">{{ $personnel->jobPosition ? $personnel->jobPosition->title : 'Non affecté' }}</span>
                        </div>

                        <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-2xl">
                            <span class="text-xs font-bold text-slate-500">Type de Contrat</span>
                            @php $cb = $personnel->contract_badge; @endphp
                            <span class="px-2.5 py-0.5 rounded-lg text-xs font-bold {{ $cb['class'] }}">{{ $cb['label'] }}</span>
                        </div>

                        <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-2xl">
                            <span class="text-xs font-bold text-slate-500">Date d'embauche</span>
                            <span class="text-xs font-black text-slate-900">
                                {{ $personnel->hired_at ? $personnel->hired_at->format('d/m/Y') : 'Non renseignée' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-2xl">
                            <span class="text-xs font-bold text-slate-500">Ancienneté</span>
                            <span class="text-xs font-black text-emerald-700">
                                {{ $personnel->hired_at ? $personnel->hired_at->diffForHumans(null, true) : '-' }}
                            </span>
                        </div>

                        <div class="p-3.5 bg-amber-50/60 border border-amber-100 rounded-2xl space-y-1">
                            <span class="text-[10px] font-black text-amber-700 uppercase tracking-widest block">Contact d'Urgence</span>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-800">{{ $personnel->emergency_contact_name ?: 'Non renseigné' }}</span>
                                <span class="text-xs font-black text-amber-800">{{ $personnel->emergency_contact_phone }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Observations / Notes -->
            @if($personnel->notes)
                <div class="p-6 sm:p-8 bg-slate-50/50 border-t border-slate-100">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Observations & Notes RH</h3>
                    <p class="text-xs font-medium text-slate-700 leading-relaxed whitespace-pre-line">{{ $personnel->notes }}</p>
                </div>
            @endif

            <!-- Footer avec suppression -->
            <div class="p-6 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                <span class="text-[11px] text-slate-400">Dossier créé le {{ $personnel->created_at ? $personnel->created_at->format('d/m/Y H:i') : '-' }}</span>

                <form action="{{ route($prefix . 'employees.destroy', $personnel->id) }}" method="POST" onsubmit="return confirm('Confirmez-vous la suppression définitive du dossier de {{ addslashes($personnel->full_name) }} ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 rounded-xl text-rose-600 hover:bg-rose-100 text-xs font-bold transition-all">
                        Supprimer ce dossier
                    </button>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
