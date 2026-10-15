<x-app-layout>
    <style>
        [x-cloak] { display: none !important; }
        .gradient-card-blue { background: linear-gradient(135deg, #ffffff 0%, #f0f7ff 100%); }
        .gradient-card-green { background: linear-gradient(135deg, #ffffff 0%, #ecfdf5 100%); }
        .gradient-card-purple { background: linear-gradient(135deg, #ffffff 0%, #f5f3ff 100%); }
    </style>

    <div x-data="{
        showCreateModal: false,
        showEditModal: false,
        viewMode: 'table', // 'table' or 'grid'
        editPoste: { id: null, title: '', department: '', description: '', is_active: true },
        newPoste: { title: '', department: '', description: '', is_active: true },
        openEdit(p) {
            this.editPoste = { ...p };
            this.showEditModal = true;
        },
        quickCreate(title, department, desc) {
            this.newPoste = {
                title: title,
                department: department,
                description: desc,
                is_active: true
            };
            this.showCreateModal = true;
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

        <!-- En-tête Principal de Page -->
        <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200/80 shadow-sm relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-emerald-500/5 rounded-full blur-2xl pointer-events-none"></div>

            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 relative z-10">
                <div class="flex items-start sm:items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white flex items-center justify-center shadow-lg shadow-emerald-500/25 flex-shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Postes & Fonctions</h1>
                            <span class="px-3 py-1 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase tracking-wider">
                                Nomenclature BFT
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm font-medium text-slate-500 mt-1">
                            Définissez l'organigramme et les postes permanents de l'entreprise (Comptable, Chauffeur, Gardien, etc.)
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 flex-wrap">
                    <a href="{{ route($prefix . 'employees.index') }}" class="px-4 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all flex items-center gap-2 shadow-sm">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        <span>Voir le Personnel</span>
                    </a>

                    <button type="button" @click="newPoste = { title: '', department: '', description: '', is_active: true }; showCreateModal = true" class="px-5 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-lg shadow-emerald-600/25 transition-all flex items-center gap-2 active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
                        <span>Nouveau Poste</span>
                    </button>
                </div>
            </div>

            <!-- Suggestions rapides de postes types -->
            <div class="mt-6 pt-5 border-t border-slate-100 flex items-center gap-2 flex-wrap text-xs">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5 mr-1">
                    <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14H8a4 4 0 01-2.485-7.142A5.98 5.98 0 0110 4a5.98 5.98 0 014.485 2.858A4 4 0 0112 14z" /></svg>
                    Ajout rapide :
                </span>

                <button type="button" @click="quickCreate('Comptable', 'Administration & Finance', 'Gestion comptable, trésorerie, facturation et états financiers')" class="px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-200 border border-slate-200 font-semibold text-slate-700 transition-all text-[11px] flex items-center gap-1.5">
                    💼 Comptable
                </button>

                <button type="button" @click="quickCreate('Chauffeur / Livreur', 'Transport & Logistique', 'Conduite et livraison des marchandises et approvisionnements')" class="px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-200 border border-slate-200 font-semibold text-slate-700 transition-all text-[11px] flex items-center gap-1.5">
                    🚚 Chauffeur
                </button>

                <button type="button" @click="quickCreate('Gardien de Sécurité', 'Sécurité & Surveillance', 'Surveillance des locaux, entrées et sorties du site')" class="px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-200 border border-slate-200 font-semibold text-slate-700 transition-all text-[11px] flex items-center gap-1.5">
                    🛡️ Gardien
                </button>

                <button type="button" @click="quickCreate('Électricien', 'Technique & Maintenance', 'Maintenance préventive et curative des installations électriques')" class="px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-200 border border-slate-200 font-semibold text-slate-700 transition-all text-[11px] flex items-center gap-1.5">
                    ⚡ Électricien
                </button>

                <button type="button" @click="quickCreate('Magasinier', 'Logistique & Stocks', 'Réception, stockage et préparation des commandes')" class="px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-200 border border-slate-200 font-semibold text-slate-700 transition-all text-[11px] flex items-center gap-1.5">
                    📦 Magasinier
                </button>
            </div>
        </div>

        <!-- 3 Cartes Statistiques avec Effets Visuels Modernes -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="gradient-card-blue p-6 rounded-3xl border border-blue-100/80 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-black text-slate-400 uppercase tracking-wider">Total des Postes</span>
                        <p class="text-3xl font-black text-slate-900 mt-1">{{ $totalPositions }}</p>
                        <span class="text-[11px] font-semibold text-blue-600 mt-1 block">Rôles répertoriés</span>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-blue-500/10 text-blue-600 flex items-center justify-center font-bold text-2xl group-hover:scale-110 transition-transform">
                        📋
                    </div>
                </div>
            </div>

            <div class="gradient-card-green p-6 rounded-3xl border border-emerald-100/80 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-black text-slate-400 uppercase tracking-wider">Postes Actifs</span>
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        </div>
                        <p class="text-3xl font-black text-emerald-600 mt-1">{{ $activePositions }}</p>
                        <span class="text-[11px] font-semibold text-emerald-700 mt-1 block">Ouverts aux recrutements</span>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center font-bold text-2xl group-hover:scale-110 transition-transform">
                        ✨
                    </div>
                </div>
            </div>

            <div class="gradient-card-purple p-6 rounded-3xl border border-indigo-100/80 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-black text-slate-400 uppercase tracking-wider">Personnel Affecté</span>
                        <p class="text-3xl font-black text-indigo-600 mt-1">{{ $totalAssignedStaff }}</p>
                        <a href="{{ route($prefix . 'employees.index') }}" class="text-[11px] font-bold text-indigo-600 hover:text-indigo-800 mt-1 flex items-center gap-1">
                            <span>Voir les collaborateurs</span> &rarr;
                        </a>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-indigo-500/10 text-indigo-600 flex items-center justify-center font-bold text-2xl group-hover:scale-110 transition-transform">
                        👥
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres, Recherche et Switch de Vue -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <form method="GET" action="{{ route($prefix . 'job_positions.index') }}" class="flex-1 grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="sm:col-span-2 relative flex items-center">
                        <svg class="w-4 h-4 text-slate-400 absolute left-4 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Rechercher un poste, département, mot-clé..." class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                    </div>

                    <div class="flex items-center gap-2">
                        <select name="department" class="flex-1 py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-emerald-500">
                            <option value="">Tous les départements</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept }}" {{ $department === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                            @endforeach
                        </select>

                        <button type="submit" class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl text-xs font-bold transition-all shadow-sm">
                            Filtrer
                        </button>
                        @if($search || $department)
                            <a href="{{ route($prefix . 'job_positions.index') }}" class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl text-xs font-bold" title="Réinitialiser les filtres">
                                ✕
                            </a>
                        @endif
                    </div>
                </form>

                <!-- Boutons d'affichage : Tableau ou Cartes -->
                <div class="flex items-center gap-1.5 p-1 bg-slate-100 rounded-2xl border border-slate-200/60 self-end md:self-center">
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

        <!-- 1. VUE TABLEAU -->
        <div x-show="viewMode === 'table'" class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            <th class="py-4 px-6">Poste / Fonction</th>
                            <th class="py-4 px-6">Département</th>
                            <th class="py-4 px-6">Missions / Description</th>
                            <th class="py-4 px-6 text-center">Effectif Rattaché</th>
                            <th class="py-4 px-6 text-center">Statut</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                        @forelse($positions as $pos)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center justify-center font-black text-sm shadow-sm">
                                            {{ substr($pos->title, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 text-sm">{{ $pos->title }}</p>
                                            <span class="text-[10px] text-slate-400">Réf. #{{ str_pad($pos->id, 3, '0', STR_PAD_LEFT) }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    @if($pos->department)
                                        <span class="inline-flex items-center px-3 py-1 rounded-xl text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                            {{ $pos->department }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-xs italic">Général</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 max-w-sm">
                                    <p class="truncate text-slate-500 font-medium" title="{{ $pos->description }}">
                                        {{ $pos->description ?: 'Aucune description définie' }}
                                    </p>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <a href="{{ route($prefix . 'employees.index', ['position_id' => $pos->id]) }}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold {{ $pos->employees_count > 0 ? 'bg-indigo-50 text-indigo-700 border border-indigo-200 hover:bg-indigo-100' : 'bg-slate-100 text-slate-500' }} transition-colors" title="Voir les personnes affectées à ce poste">
                                        <span>{{ $pos->employees_count }}</span>
                                        <span class="text-[10px] font-normal">salarié{{ $pos->employees_count > 1 ? 's' : '' }}</span>
                                    </a>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    @if($pos->is_active)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Actif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                            Inactif
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button type="button" @click="openEdit({{ json_encode($pos) }})" class="p-2 text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all" title="Modifier le poste">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </button>

                                        <form action="{{ route($prefix . 'job_positions.destroy', $pos->id) }}" method="POST" onsubmit="return confirm('Confirmez-vous la suppression du poste « {{ addslashes($pos->title) }} » ?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all" title="Supprimer le poste">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-16 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center max-w-md mx-auto">
                                        <div class="w-16 h-16 rounded-3xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-3xl mb-4 border border-emerald-100 shadow-sm">
                                            📋
                                        </div>
                                        <p class="text-base font-black text-slate-800">Aucun poste n'a encore été créé</p>
                                        <p class="text-xs text-slate-400 mt-1.5 leading-relaxed">
                                            Commencez par ajouter les postes clés de Bio Farm Trading (Comptable, Chauffeur, Gardien, etc.) pour y affecter vos collaborateurs.
                                        </p>
                                        <div class="mt-5 flex items-center gap-3">
                                            <button type="button" @click="quickCreate('Comptable', 'Administration & Finance', 'Gestion comptable et trésorerie')" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-emerald-600/20">
                                                + Créer le poste Comptable
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($positions->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $positions->links() }}
                </div>
            @endif
        </div>

        <!-- 2. VUE GRILLE DE CARTES -->
        <div x-show="viewMode === 'grid'" x-cloak class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($positions as $pos)
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-start justify-between gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center justify-center font-black text-base shadow-sm">
                                {{ substr($pos->title, 0, 1) }}
                            </div>

                            @if($pos->is_active)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Actif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                    Inactif
                                </span>
                            @endif
                        </div>

                        <div class="mt-4">
                            <h3 class="text-base font-black text-slate-900 group-hover:text-emerald-700 transition-colors">{{ $pos->title }}</h3>
                            @if($pos->department)
                                <span class="inline-block mt-1 px-2.5 py-0.5 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                    {{ $pos->department }}
                                </span>
                            @endif
                        </div>

                        <p class="text-xs text-slate-500 mt-3 line-clamp-2 leading-relaxed">
                            {{ $pos->description ?: 'Aucune description spécifique renseignée pour ce poste.' }}
                        </p>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <a href="{{ route($prefix . 'employees.index', ['position_id' => $pos->id]) }}" class="inline-flex items-center gap-2 text-xs font-bold text-indigo-600 hover:text-indigo-800">
                            <span class="w-6 h-6 rounded-lg bg-indigo-50 flex items-center justify-center text-xs font-black">{{ $pos->employees_count }}</span>
                            <span>collaborateur{{ $pos->employees_count > 1 ? 's' : '' }}</span>
                        </a>

                        <div class="flex items-center gap-1">
                            <button type="button" @click="openEdit({{ json_encode($pos) }})" class="p-2 text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all" title="Modifier">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </button>

                            <form action="{{ route($prefix . 'job_positions.destroy', $pos->id) }}" method="POST" onsubmit="return confirm('Confirmez-vous la suppression du poste « {{ addslashes($pos->title) }} » ?')" class="inline">
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
                    <p class="font-bold">Aucun poste trouvé</p>
                </div>
            @endforelse
        </div>

        <!-- MODAL CRÉATION POSTE -->
        <div x-show="showCreateModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-fade-in">
            <div @click.away="showCreateModal = false" class="bg-white w-full max-w-lg rounded-3xl p-6 sm:p-8 shadow-2xl border border-slate-100 relative">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-black">+</div>
                        <div>
                            <h3 class="text-lg font-black text-slate-900">Nouveau Poste / Fonction</h3>
                            <p class="text-xs text-slate-500 font-medium">Définissez une fonction officielle au sein de Bio Farm Trading</p>
                        </div>
                    </div>
                    <button type="button" @click="showCreateModal = false" class="text-slate-400 hover:text-slate-600 text-2xl font-bold leading-none">&times;</button>
                </div>

                <form method="POST" action="{{ route($prefix . 'job_positions.store') }}" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Intitulé du poste <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" x-model="newPoste.title" required placeholder="Ex: Comptable, Gardien, Chauffeur, Électricien..." class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Département / Service</label>
                        <input type="text" name="department" x-model="newPoste.department" list="dept-suggestions" placeholder="Ex: Administration & Finance, Transport, Sécurité, Maintenance..." class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                        <datalist id="dept-suggestions">
                            <option value="Administration & Finance">
                            <option value="Transport & Logistique">
                            <option value="Sécurité & Surveillance">
                            <option value="Technique & Maintenance">
                            <option value="Production Agricole">
                            <option value="Commercial & Distribution">
                        </datalist>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Missions / Description du poste</label>
                        <textarea name="description" x-model="newPoste.description" rows="3" placeholder="Responsabilités principales du collaborateur..." class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all"></textarea>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" :checked="newPoste.is_active" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                            <span class="ml-3 text-xs font-bold text-slate-700">Poste actif et ouvert aux affectations</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="showCreateModal = false" class="px-5 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all">
                            Annuler
                        </button>
                        <button type="submit" class="px-6 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-lg shadow-emerald-600/20 transition-all active:scale-95">
                            Enregistrer le poste
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL ÉDITION POSTE -->
        <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-fade-in">
            <div @click.away="showEditModal = false" class="bg-white w-full max-w-lg rounded-3xl p-6 sm:p-8 shadow-2xl border border-slate-100 relative">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center font-black">✎</div>
                        <div>
                            <h3 class="text-lg font-black text-slate-900">Modifier le Poste</h3>
                            <p class="text-xs text-slate-500 font-medium">Mettre à jour les paramètres de cette fonction</p>
                        </div>
                    </div>
                    <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 text-2xl font-bold leading-none">&times;</button>
                </div>

                <form method="POST" :action="'{{ url(($prefix === 'admin.' ? 'admin' : 'rh') . '/postes') }}/' + editPoste.id" class="mt-6 space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Intitulé du poste <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" x-model="editPoste.title" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Département / Service</label>
                        <input type="text" name="department" x-model="editPoste.department" list="dept-suggestions" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Missions / Description du poste</label>
                        <textarea name="description" x-model="editPoste.description" rows="3" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all"></textarea>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" :checked="editPoste.is_active" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                            <span class="ml-3 text-xs font-bold text-slate-700">Poste actif et ouvert aux affectations</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="showEditModal = false" class="px-5 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all">
                            Annuler
                        </button>
                        <button type="submit" class="px-6 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-lg shadow-emerald-600/20 transition-all active:scale-95">
                            Sauvegarder les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
