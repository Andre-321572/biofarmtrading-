<x-app-layout>
    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto space-y-6">

        <!-- En-tête -->
        <div class="flex items-center justify-between bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-3">
                <a href="{{ route($prefix . 'employees.index') }}" class="w-10 h-10 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center font-black transition-all">
                    ←
                </a>
                <div>
                    <h1 class="text-xl font-black text-slate-900 tracking-tight">Modifier : {{ $personnel->full_name }}</h1>
                    <p class="text-xs font-semibold text-slate-500 mt-0.5">Matricule : <span class="font-mono text-emerald-600 font-bold">{{ $personnel->matricule }}</span></p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route($prefix . 'employees.show', $personnel->id) }}" class="px-4 py-2 bg-indigo-50 text-indigo-700 rounded-2xl text-xs font-bold hover:bg-indigo-100 transition-all">
                    Voir la fiche
                </a>
            </div>
        </div>

        @if($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-800 shadow-sm">
                <p class="font-bold text-sm mb-1">Veuillez corriger les erreurs ci-dessous :</p>
                <ul class="list-disc list-inside text-xs space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route($prefix . 'employees.update', $personnel->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1 : État Civil & Identité -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-sm space-y-4">
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                    <span class="w-7 h-7 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center font-black text-xs">1</span>
                    <h2 class="text-sm font-black text-slate-900 uppercase tracking-wider">Identité & État Civil</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Prénom <span class="text-rose-500">*</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name', $personnel->first_name) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nom de famille <span class="text-rose-500">*</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name', $personnel->last_name) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Sexe</label>
                        <select name="gender" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                            <option value="">Sélectionner</option>
                            <option value="M" {{ old('gender', $personnel->gender) === 'M' ? 'selected' : '' }}>Masculin (M)</option>
                            <option value="F" {{ old('gender', $personnel->gender) === 'F' ? 'selected' : '' }}>Féminin (F)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Matricule</label>
                        <input type="text" name="matricule" value="{{ old('matricule', $personnel->matricule) }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">N° CNI / Pièce d'identité</label>
                        <input type="text" name="national_id" value="{{ old('national_id', $personnel->national_id) }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Adresse de résidence</label>
                        <input type="text" name="address" value="{{ old('address', $personnel->address) }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Numéro de Téléphone</label>
                        <input type="text" name="phone" value="{{ old('phone', $personnel->phone) }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Adresse Email</label>
                        <input type="email" name="email" value="{{ old('email', $personnel->email) }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                    </div>
                </div>
            </div>

            <!-- Section 2 : Affectation Professionnelle & Contrat -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-sm space-y-4">
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                    <span class="w-7 h-7 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-black text-xs">2</span>
                    <h2 class="text-sm font-black text-slate-900 uppercase tracking-wider">Poste & Contrat</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Poste / Fonction occupée</label>
                        <select name="job_position_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                            <option value="">Sélectionner un poste</option>
                            @foreach($positions as $pos)
                                <option value="{{ $pos->id }}" {{ old('job_position_id', $personnel->job_position_id) == $pos->id ? 'selected' : '' }}>
                                    {{ $pos->title }} {{ $pos->department ? "({$pos->department})" : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Type de contrat</label>
                        <select name="contract_type" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                            <option value="CDI" {{ old('contract_type', $personnel->contract_type) === 'CDI' ? 'selected' : '' }}>CDI (Contrat à Durée Indéterminée)</option>
                            <option value="CDD" {{ old('contract_type', $personnel->contract_type) === 'CDD' ? 'selected' : '' }}>CDD (Contrat à Durée Déterminée)</option>
                            <option value="Stage" {{ old('contract_type', $personnel->contract_type) === 'Stage' ? 'selected' : '' }}>Stage</option>
                            <option value="Prestation" {{ old('contract_type', $personnel->contract_type) === 'Prestation' ? 'selected' : '' }}>Prestation / Consultation</option>
                            <option value="Autre" {{ old('contract_type', $personnel->contract_type) === 'Autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Date d'embauche</label>
                        <input type="date" name="hired_at" value="{{ old('hired_at', $personnel->hired_at ? $personnel->hired_at->format('Y-m-d') : '') }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Statut <span class="text-rose-500">*</span></label>
                        <select name="status" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                            <option value="actif" {{ old('status', $personnel->status) === 'actif' ? 'selected' : '' }}>Actif</option>
                            <option value="conge" {{ old('status', $personnel->status) === 'conge' ? 'selected' : '' }}>En congé</option>
                            <option value="suspendu" {{ old('status', $personnel->status) === 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                            <option value="demissionnaire" {{ old('status', $personnel->status) === 'demissionnaire' ? 'selected' : '' }}>Démissionnaire</option>
                            <option value="licencie" {{ old('status', $personnel->status) === 'licencie' ? 'selected' : '' }}>Licencié</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section 3 : Rémunération Mensuelle -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-sm space-y-4">
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                    <span class="w-7 h-7 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center font-black text-xs">3</span>
                    <h2 class="text-sm font-black text-slate-900 uppercase tracking-wider">Rémunération Mensuelle</h2>
                </div>

                <div class="max-w-md">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Salaire mensuel de base (FCFA)</label>
                    <div class="relative">
                        <input type="text" inputmode="decimal" name="monthly_salary" value="{{ old('monthly_salary', $personnel->monthly_salary ? number_format($personnel->monthly_salary, 2, '.', '') : '') }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all pr-16">
                        <span class="absolute right-4 top-2.5 text-xs font-bold text-slate-400">FCFA</span>
                    </div>
                </div>
            </div>

            <!-- Section 4 : Urgence & Notes -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-sm space-y-4">
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                    <span class="w-7 h-7 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center font-black text-xs">4</span>
                    <h2 class="text-sm font-black text-slate-900 uppercase tracking-wider">Contact d'Urgence & Notes</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Personne à contacter en cas d'urgence</label>
                        <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $personnel->emergency_contact_name) }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Téléphone d'urgence</label>
                        <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $personnel->emergency_contact_phone) }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Observations / Notes RH</label>
                    <textarea name="notes" rows="3" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">{{ old('notes', $personnel->notes) }}</textarea>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex items-center justify-end gap-3 pt-4">
                <a href="{{ route($prefix . 'employees.index') }}" class="px-6 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all">
                    Annuler
                </a>
                <button type="submit" class="px-8 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-lg shadow-emerald-600/20 transition-all active:scale-95">
                    Mettre à jour le Personnel
                </button>
            </div>
        </form>

    </div>
</x-app-layout>
