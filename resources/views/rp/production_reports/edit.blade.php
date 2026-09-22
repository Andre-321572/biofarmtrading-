@extends('layouts.app')

@section('content')
<div class="py-5" x-data="productionEditForm()">
    <div class="max-w-[1750px] mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5 bg-white p-5 rounded-lg border border-gray-200 shadow-xs">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-500 text-white rounded-lg flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-pen-to-square text-xl"></i>
                </div>
                <div>
                    <h1 class="text-lg font-black text-gray-900 tracking-tight">
                        Modifier le Rapport : {{ $report->reference }}
                    </h1>
                    <p class="text-xs text-gray-500">
                        Mise à jour d'atelier du {{ $report->date_rapport->format('d/m/Y') }}.
                    </p>
                </div>
            </div>
            <a href="{{ route('rp.production-reports.show', $report) }}" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold text-xs rounded-lg hover:bg-slate-200 transition-colors flex items-center gap-2 self-start sm:self-auto">
                <i class="fa-solid fa-arrow-left"></i> Annuler
            </a>
        </div>

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="rounded-lg bg-rose-50 p-4 border border-rose-200 mb-5">
                <div class="flex items-center gap-2 text-rose-800 font-bold text-sm mb-2">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    Veuillez corriger les erreurs ci-dessous :
                </div>
                <ul class="list-disc list-inside text-xs text-rose-700 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('rp.production-reports.update', $report) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Section 1: Informations Générales du Jour -->
            <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-xs mb-4 space-y-4">
                
                <!-- Block 1: Date & Effectif Ouvriers -->
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-1.5 h-3.5 bg-amber-500 rounded-sm"></span>
                        <h2 class="text-[11px] font-black text-gray-900 uppercase tracking-wider">
                            1. Date & Effectif Ouvriers
                        </h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Date -->
                        <div class="bg-slate-50 p-2 rounded-md border border-slate-200">
                            <label class="block text-[10px] font-bold text-gray-700 uppercase tracking-wider mb-0.5">
                                Date du Rapport <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="date_rapport" value="{{ old('date_rapport', $report->date_rapport->format('Y-m-d')) }}" required
                                   class="w-full h-8 py-1 px-2 rounded-md border-gray-300 text-xs font-bold text-gray-900 focus:border-amber-500 focus:ring-amber-500 bg-white">
                        </div>

                        <!-- Ouvriers Présents -->
                        <div class="bg-emerald-50/70 p-2 rounded-md border border-emerald-200">
                            <label class="block text-[10px] font-bold text-emerald-900 uppercase tracking-wider mb-0.5">
                                Ouvriers Présents <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="nombre_ouvriers" value="{{ old('nombre_ouvriers', $report->nombre_ouvriers) }}" min="0" required
                                       class="w-full h-8 py-1 pl-7 pr-2 rounded-md border-emerald-300 text-xs font-black text-emerald-800 focus:border-amber-500 focus:ring-amber-500 bg-white">
                                <i class="fa-solid fa-users absolute left-2 top-2 text-emerald-600 text-[11px]"></i>
                            </div>
                        </div>

                        <!-- Ouvriers Absents -->
                        <div class="bg-rose-50/70 p-2 rounded-md border border-rose-200">
                            <label class="block text-[10px] font-bold text-rose-900 uppercase tracking-wider mb-0.5">
                                Ouvriers Absents
                            </label>
                            <div class="relative">
                                <input type="number" name="nombre_ouvriers_absents" value="{{ old('nombre_ouvriers_absents', $report->nombre_ouvriers_absents) }}" min="0"
                                       class="w-full h-8 py-1 pl-7 pr-2 rounded-md border-rose-300 text-xs font-black text-rose-800 focus:border-amber-500 focus:ring-amber-500 bg-white">
                                <i class="fa-solid fa-user-xmark absolute left-2 top-2 text-rose-500 text-[11px]"></i>
                            </div>
                        </div>

                        <!-- Ouvriers Au Repos -->
                        <div class="bg-amber-50/70 p-2 rounded-md border border-amber-200">
                            <label class="block text-[10px] font-bold text-amber-900 uppercase tracking-wider mb-0.5">
                                Ouvriers au Repos
                            </label>
                            <div class="relative">
                                <input type="number" name="nombre_ouvriers_repos" value="{{ old('nombre_ouvriers_repos', $report->nombre_ouvriers_repos) }}" min="0"
                                       class="w-full h-8 py-1 pl-7 pr-2 rounded-md border-amber-300 text-xs font-black text-amber-800 focus:border-amber-500 focus:ring-amber-500 bg-white">
                                <i class="fa-solid fa-bed absolute left-2 top-2 text-amber-600 text-[11px]"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Block 2: Inventaire Matériel (Couteaux & Ciseaux) -->
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-1.5 h-3.5 bg-amber-600 rounded-sm"></span>
                        <h2 class="text-[11px] font-black text-gray-900 uppercase tracking-wider">
                            2. Suivi du Matériel d'Atelier (Début & Fin de Journée)
                        </h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Couteaux Début -->
                        <div class="bg-slate-50 p-2 rounded-md border border-slate-200">
                            <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider mb-0.5">
                                Couteaux (Début)
                            </label>
                            <div class="relative">
                                <input type="number" name="nombre_couteaux_debut" value="{{ old('nombre_couteaux_debut', $report->nombre_couteaux_debut) }}" min="0"
                                       class="w-full h-8 py-1 pl-7 pr-2 rounded-md border-gray-300 text-xs font-bold text-slate-900 focus:border-amber-500 focus:ring-amber-500 bg-white">
                                <i class="fa-solid fa-kitchen-set absolute left-2 top-2 text-slate-500 text-[11px]"></i>
                            </div>
                        </div>

                        <!-- Couteaux Fin -->
                        <div class="bg-slate-50 p-2 rounded-md border border-slate-200">
                            <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider mb-0.5">
                                Couteaux (Fin)
                            </label>
                            <div class="relative">
                                <input type="number" name="nombre_couteaux_fin" value="{{ old('nombre_couteaux_fin', $report->nombre_couteaux_fin) }}" min="0"
                                       class="w-full h-8 py-1 pl-7 pr-2 rounded-md border-gray-300 text-xs font-bold text-slate-900 focus:border-amber-500 focus:ring-amber-500 bg-white">
                                <i class="fa-solid fa-kitchen-set absolute left-2 top-2 text-slate-500 text-[11px]"></i>
                            </div>
                        </div>

                        <!-- Ciseaux Début -->
                        <div class="bg-slate-50 p-2 rounded-md border border-slate-200">
                            <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider mb-0.5">
                                Ciseaux (Début)
                            </label>
                            <div class="relative">
                                <input type="number" name="nombre_ciseaux_debut" value="{{ old('nombre_ciseaux_debut', $report->nombre_ciseaux_debut) }}" min="0"
                                       class="w-full h-8 py-1 pl-7 pr-2 rounded-md border-gray-300 text-xs font-bold text-slate-900 focus:border-amber-500 focus:ring-amber-500 bg-white">
                                <i class="fa-solid fa-scissors absolute left-2 top-2 text-slate-500 text-[11px]"></i>
                            </div>
                        </div>

                        <!-- Ciseaux Fin -->
                        <div class="bg-slate-50 p-2 rounded-md border border-slate-200">
                            <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider mb-0.5">
                                Ciseaux (Fin)
                            </label>
                            <div class="relative">
                                <input type="number" name="nombre_ciseaux_fin" value="{{ old('nombre_ciseaux_fin', $report->nombre_ciseaux_fin) }}" min="0"
                                       class="w-full h-8 py-1 pl-7 pr-2 rounded-md border-gray-300 text-xs font-bold text-slate-900 focus:border-amber-500 focus:ring-amber-500 bg-white">
                                <i class="fa-solid fa-scissors absolute left-2 top-2 text-slate-500 text-[11px]"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Block 3: Lots Pelés & Observations -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-1">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-700 uppercase tracking-wider mb-0.5">
                            Lots Pelés (N° Lot pelés du jour)
                        </label>
                        <input type="text" name="lots_peles" value="{{ old('lots_peles', $report->lots_peles) }}" placeholder="Ex: Pelage du Lot 287/ANAS/2026 et 007/PAP/2026"
                               class="w-full h-8 py-1 px-2.5 rounded-md border-gray-300 text-xs font-medium focus:border-amber-500 focus:ring-amber-500 bg-slate-50">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-700 uppercase tracking-wider mb-0.5">Observations / Notes Générales</label>
                        <input type="text" name="notes" value="{{ old('notes', $report->notes) }}" placeholder="Remarques éventuelles sur la journée de travail..."
                               class="w-full h-8 py-1 px-2.5 rounded-md border-gray-300 text-xs font-medium focus:border-amber-500 focus:ring-amber-500 bg-slate-50">
                    </div>
                </div>

            </div>

            <!-- Section 2: Détail des Lots Traités -->
            <div class="bg-white rounded-lg p-5 border border-gray-200 shadow-xs mb-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 pb-3 border-b border-gray-200 gap-2">
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-4 bg-amber-500 rounded-sm"></span>
                        <div>
                            <h2 class="text-xs font-black text-gray-900 uppercase tracking-wider">
                                3. Saisie des Lots de Production & Coupes
                            </h2>
                            <p class="text-[11px] text-gray-500">Mise à jour des poids déclayés, poids par coupe (kg) et sachets par format.</p>
                        </div>
                    </div>
                    <button type="button" @click="addLot()" class="px-4 py-2 bg-amber-500 text-white rounded-lg text-xs font-bold hover:bg-amber-600 shadow-sm transition-colors flex items-center gap-1.5 self-start sm:self-auto">
                        <i class="fa-solid fa-plus text-xs"></i> Ajouter un Lot
                    </button>
                </div>

                <!-- Légende Abréviations Claire -->
                <div class="bg-amber-50/80 border border-amber-200 text-amber-950 rounded-lg p-3 mb-4 text-xs font-medium flex flex-wrap items-center gap-x-6 gap-y-1.5">
                    <span class="font-black text-amber-800 uppercase tracking-wider text-[11px] flex items-center gap-1">
                        <i class="fa-solid fa-bookmark text-amber-600"></i> Légende des Coupes Bio Farm :
                    </span>
                    <span class="text-[11px]"><strong class="font-bold text-emerald-900">RCL</strong> = Rondelle Cayenne Lisse</span>
                    <span class="text-[11px]"><strong class="font-bold text-blue-900">MCL</strong> = Morceaux Cayenne Lisse</span>
                    <span class="text-[11px]"><strong class="font-bold text-amber-900">RPS</strong> = Rondelle Pain de Sucre</span>
                    <span class="text-[11px]"><strong class="font-bold text-amber-950">MPS</strong> = Morceaux Pain de Sucre</span>
                    <span class="text-[11px]"><strong class="font-bold text-orange-900">LPA</strong> = Lamelles Papaye</span>
                    <span class="text-[11px]"><strong class="font-bold text-teal-900">TTPM</strong> = Très Très Petits Morceaux (Ananas Uniquement - 1KG)</span>
                </div>

                <!-- Dynamic Table -->
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-slate-800 text-white">
                            <tr class="text-[10px] font-black uppercase tracking-wider text-left">
                                <th class="px-3 py-3 w-[150px]">N° LOT *</th>
                                <th class="px-3 py-3 w-[170px]">PRODUIT</th>
                                <th class="px-3 py-3 w-[120px] text-right">DÉCLAYÉ (KG)</th>
                                <th class="px-4 py-3 min-w-[500px] text-center">POIDS (KG) & SACHETS PAR FORMAT DE COUPE</th>
                                <th class="px-3 py-3 w-[110px] text-right">DÉCHETS (KG)</th>
                                <th class="px-3 py-3 w-[110px] text-right">QTÉ TTPM (KG)</th>
                                <th class="px-2 py-3 w-10 text-center">ACTION</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <template x-for="(lot, index) in lots" :key="index">
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <!-- N° Lot -->
                                    <td class="p-3 align-middle">
                                        <input type="text" :name="'lots['+index+'][numero_lot]'" x-model="lot.numero_lot" required
                                               class="w-full py-1.5 px-2.5 rounded-md border-gray-300 text-xs font-bold focus:border-amber-500 focus:ring-amber-500 bg-white">
                                    </td>

                                    <!-- Type Produit -->
                                    <td class="p-3 align-middle">
                                        <select :name="'lots['+index+'][type_produit]'" x-model="lot.type_produit" @change="onFruitChange(index)"
                                                class="w-full py-1.5 px-2 rounded-md border-gray-300 text-xs font-bold text-amber-800 bg-amber-50/50 focus:border-amber-500 focus:ring-amber-500">
                                            <option value="Ananas Cayenne Lisse">🍍 Ananas Cayenne Lisse (CL)</option>
                                            <option value="Ananas Pain de Sucre">🍍 Ananas Pain de Sucre (PS)</option>
                                            <option value="Papaye">🥭 Papaye (LPA)</option>
                                            <option value="Mangue">🥭 Mangue</option>
                                            <option value="Banane">🍌 Banane</option>
                                            <option value="Autre">📦 Autre</option>
                                        </select>
                                    </td>

                                    <!-- Qté Déclayée kg -->
                                    <td class="p-3 align-middle">
                                        <input type="number" step="0.01" min="0" :name="'lots['+index+'][quantite_declayee_kg]'" x-model="lot.quantite_declayee_kg"
                                               class="w-full py-1.5 px-2 rounded-md border-gray-300 text-xs font-bold text-right text-gray-900 focus:border-amber-500 focus:ring-amber-500 bg-white">
                                    </td>

                                    <!-- Dynamic Inline Poids & Sachets par Coupe -->
                                    <td class="p-3 align-middle bg-slate-50/40">
                                        
                                        <!-- Cayenne Lisse -->
                                        <template x-if="lot.type_produit === 'Ananas Cayenne Lisse'">
                                            <div class="space-y-2">
                                                <!-- RCL Row -->
                                                <div class="flex items-center gap-2 bg-white p-2 rounded-md border border-emerald-200">
                                                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 font-extrabold rounded text-[11px] min-w-[46px] text-center">RCL</span>
                                                    <div class="flex items-center gap-1">
                                                        <span class="text-[11px] font-bold text-gray-500">Poids:</span>
                                                        <input type="number" step="0.01" min="0" :name="'lots['+index+'][poids_rcl_kg]'" x-model="lot.poids_rcl_kg" class="w-20 py-1 px-2 rounded border-gray-300 text-xs text-right font-bold text-emerald-700 focus:ring-amber-500">
                                                        <span class="text-[10px] text-gray-400 font-bold">kg</span>
                                                    </div>
                                                    <div class="h-4 w-px bg-gray-200 mx-0.5"></div>
                                                    <div class="flex items-center gap-1">
                                                        <span class="text-[11px] font-bold text-gray-500">2kg:</span>
                                                        <input type="number" min="0" :name="'lots['+index+'][sachets_rcl_2kg]'" x-model="lot.sachets_rcl_2kg" class="w-14 py-1 px-2 rounded border-gray-300 text-xs text-right font-bold focus:ring-amber-500">
                                                    </div>
                                                    <div class="flex items-center gap-1">
                                                        <span class="text-[11px] font-bold text-gray-500">1kg:</span>
                                                        <input type="number" min="0" :name="'lots['+index+'][sachets_rcl_1kg]'" x-model="lot.sachets_rcl_1kg" class="w-14 py-1 px-2 rounded border-gray-300 text-xs text-right font-bold focus:ring-amber-500">
                                                    </div>
                                                </div>

                                                <!-- MCL Row -->
                                                <div class="flex items-center gap-2 bg-white p-2 rounded-md border border-blue-200">
                                                    <span class="px-2 py-0.5 bg-blue-100 text-blue-800 font-extrabold rounded text-[11px] min-w-[46px] text-center">MCL</span>
                                                    <div class="flex items-center gap-1">
                                                        <span class="text-[11px] font-bold text-gray-500">Poids:</span>
                                                        <input type="number" step="0.01" min="0" :name="'lots['+index+'][poids_mcl_kg]'" x-model="lot.poids_mcl_kg" class="w-20 py-1 px-2 rounded border-gray-300 text-xs text-right font-bold text-blue-700 focus:ring-amber-500">
                                                        <span class="text-[10px] text-gray-400 font-bold">kg</span>
                                                    </div>
                                                    <div class="h-4 w-px bg-gray-200 mx-0.5"></div>
                                                    <div class="flex items-center gap-1">
                                                        <span class="text-[11px] font-bold text-gray-500">2kg:</span>
                                                        <input type="number" min="0" :name="'lots['+index+'][sachets_mcl_2kg]'" x-model="lot.sachets_mcl_2kg" class="w-14 py-1 px-2 rounded border-gray-300 text-xs text-right font-bold focus:ring-amber-500">
                                                    </div>
                                                    <div class="flex items-center gap-1">
                                                        <span class="text-[11px] font-bold text-gray-500">1kg:</span>
                                                        <input type="number" min="0" :name="'lots['+index+'][sachets_mcl_1kg]'" x-model="lot.sachets_mcl_1kg" class="w-14 py-1 px-2 rounded border-gray-300 text-xs text-right font-bold focus:ring-amber-500">
                                                    </div>
                                                </div>

                                                <!-- TTPM Row -->
                                                <div class="flex items-center gap-2 bg-white p-2 rounded-md border border-slate-200">
                                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-800 font-extrabold rounded text-[11px] min-w-[46px] text-center border border-slate-200">TTPM</span>
                                                    <div class="flex items-center gap-1">
                                                        <span class="text-[11px] font-bold text-gray-700">Poids TTPM:</span>
                                                        <input type="number" step="0.01" min="0" :name="'lots['+index+'][cl_ttpm_kg]'" x-model="lot.cl_ttpm_kg" placeholder="0.00" class="w-20 py-1 px-2 rounded border-gray-300 text-xs text-right font-bold text-slate-800 focus:ring-amber-500">
                                                        <span class="text-[10px] text-gray-400 font-bold">kg</span>
                                                    </div>
                                                    <div class="h-4 w-px bg-gray-200 mx-0.5"></div>
                                                    <div class="flex items-center gap-1">
                                                        <span class="text-[11px] font-bold text-gray-700">Sachets 1KG:</span>
                                                        <input type="number" min="0" :name="'lots['+index+'][sachets_ttpm_1kg]'" x-model="lot.sachets_ttpm_1kg" placeholder="0" class="w-14 py-1 px-2 rounded border-gray-300 text-xs text-right font-bold focus:ring-amber-500">
                                                    </div>
                                                    <span class="text-[10px] text-gray-400 italic">(Format 1KG unique)</span>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- Pain de Sucre -->
                                        <template x-if="lot.type_produit === 'Ananas Pain de Sucre'">
                                            <div class="space-y-2">
                                                <!-- RPS Row -->
                                                <div class="flex items-center gap-2 bg-white p-2 rounded-md border border-amber-200">
                                                    <span class="px-2 py-0.5 bg-amber-100 text-amber-800 font-extrabold rounded text-[11px] min-w-[46px] text-center">RPS</span>
                                                    <div class="flex items-center gap-1">
                                                        <span class="text-[11px] font-bold text-gray-500">Poids:</span>
                                                        <input type="number" step="0.01" min="0" :name="'lots['+index+'][poids_rps_kg]'" x-model="lot.poids_rps_kg" class="w-20 py-1 px-2 rounded border-gray-300 text-xs text-right font-bold text-amber-700 focus:ring-amber-500">
                                                        <span class="text-[10px] text-gray-400 font-bold">kg</span>
                                                    </div>
                                                    <div class="h-4 w-px bg-gray-200 mx-0.5"></div>
                                                    <div class="flex items-center gap-1">
                                                        <span class="text-[11px] font-bold text-gray-500">2kg:</span>
                                                        <input type="number" min="0" :name="'lots['+index+'][sachets_rps_2kg]'" x-model="lot.sachets_rps_2kg" class="w-14 py-1 px-2 rounded border-gray-300 text-xs text-right font-bold focus:ring-amber-500">
                                                    </div>
                                                    <div class="flex items-center gap-1">
                                                        <span class="text-[11px] font-bold text-gray-500">1kg:</span>
                                                        <input type="number" min="0" :name="'lots['+index+'][sachets_rps_1kg]'" x-model="lot.sachets_rps_1kg" class="w-14 py-1 px-2 rounded border-gray-300 text-xs text-right font-bold focus:ring-amber-500">
                                                    </div>
                                                </div>

                                                <!-- MPS Row -->
                                                <div class="flex items-center gap-2 bg-white p-2 rounded-md border border-amber-300">
                                                    <span class="px-2 py-0.5 bg-amber-200 text-amber-900 font-extrabold rounded text-[11px] min-w-[46px] text-center">MPS</span>
                                                    <div class="flex items-center gap-1">
                                                        <span class="text-[11px] font-bold text-gray-500">Poids:</span>
                                                        <input type="number" step="0.01" min="0" :name="'lots['+index+'][poids_mps_kg]'" x-model="lot.poids_mps_kg" class="w-20 py-1 px-2 rounded border-gray-300 text-xs text-right font-bold text-amber-800 focus:ring-amber-500">
                                                        <span class="text-[10px] text-gray-400 font-bold">kg</span>
                                                    </div>
                                                    <div class="h-4 w-px bg-gray-200 mx-0.5"></div>
                                                    <div class="flex items-center gap-1">
                                                        <span class="text-[11px] font-bold text-gray-500">2kg:</span>
                                                        <input type="number" min="0" :name="'lots['+index+'][sachets_mps_2kg]'" x-model="lot.sachets_mps_2kg" class="w-14 py-1 px-2 rounded border-gray-300 text-xs text-right font-bold focus:ring-amber-500">
                                                    </div>
                                                    <div class="flex items-center gap-1">
                                                        <span class="text-[11px] font-bold text-gray-500">1kg:</span>
                                                        <input type="number" min="0" :name="'lots['+index+'][sachets_mps_1kg]'" x-model="lot.sachets_mps_1kg" class="w-14 py-1 px-2 rounded border-gray-300 text-xs text-right font-bold focus:ring-amber-500">
                                                    </div>
                                                </div>

                                                <!-- TTPM Row -->
                                                <div class="flex items-center gap-2 bg-white p-2 rounded-md border border-slate-200">
                                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-800 font-extrabold rounded text-[11px] min-w-[46px] text-center border border-slate-200">TTPM</span>
                                                    <div class="flex items-center gap-1">
                                                        <span class="text-[11px] font-bold text-gray-700">Poids TTPM:</span>
                                                        <input type="number" step="0.01" min="0" :name="'lots['+index+'][cl_ttpm_kg]'" x-model="lot.cl_ttpm_kg" placeholder="0.00" class="w-20 py-1 px-2 rounded border-gray-300 text-xs text-right font-bold text-slate-800 focus:ring-amber-500">
                                                        <span class="text-[10px] text-gray-400 font-bold">kg</span>
                                                    </div>
                                                    <div class="h-4 w-px bg-gray-200 mx-0.5"></div>
                                                    <div class="flex items-center gap-1">
                                                        <span class="text-[11px] font-bold text-gray-700">Sachets 1KG:</span>
                                                        <input type="number" min="0" :name="'lots['+index+'][sachets_ttpm_1kg]'" x-model="lot.sachets_ttpm_1kg" placeholder="0" class="w-14 py-1 px-2 rounded border-gray-300 text-xs text-right font-bold focus:ring-amber-500">
                                                    </div>
                                                    <span class="text-[10px] text-gray-400 italic">(Format 1KG unique)</span>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- Papaye (Pas de TTPM pour Papaye!) -->
                                        <template x-if="lot.type_produit === 'Papaye'">
                                            <div class="flex items-center gap-2 bg-white p-2 rounded-md border border-orange-200">
                                                <span class="px-2 py-0.5 bg-orange-100 text-orange-800 font-extrabold rounded text-[11px] min-w-[46px] text-center">LPA</span>
                                                <div class="flex items-center gap-1">
                                                    <span class="text-[11px] font-bold text-gray-500">Poids:</span>
                                                    <input type="number" step="0.01" min="0" :name="'lots['+index+'][poids_lpa_kg]'" x-model="lot.poids_lpa_kg" placeholder="0.00" class="w-20 py-1 px-2 rounded border-gray-300 text-xs text-right font-bold text-orange-700 focus:ring-emerald-500">
                                                    <span class="text-[10px] text-gray-400 font-bold">kg</span>
                                                </div>
                                                <div class="h-4 w-px bg-gray-200 mx-0.5"></div>
                                                <div class="flex items-center gap-1">
                                                    <span class="text-[11px] font-bold text-gray-500">2kg:</span>
                                                    <input type="number" min="0" :name="'lots['+index+'][sachets_lpa_2kg]'" x-model="lot.sachets_lpa_2kg" placeholder="0" class="w-12 py-1 px-2 rounded border-gray-300 text-xs text-right font-bold focus:ring-emerald-500">
                                                </div>
                                                <div class="flex items-center gap-1">
                                                    <span class="text-[11px] font-bold text-gray-500">1kg:</span>
                                                    <input type="number" min="0" :name="'lots['+index+'][sachets_lpa_1kg]'" x-model="lot.sachets_lpa_1kg" placeholder="0" class="w-12 py-1 px-2 rounded border-gray-300 text-xs text-right font-bold focus:ring-emerald-500">
                                                </div>
                                                <div class="flex items-center gap-1">
                                                    <span class="text-[11px] font-bold text-gray-500">100g:</span>
                                                    <input type="number" min="0" :name="'lots['+index+'][sachets_lpa_100g]'" x-model="lot.sachets_lpa_100g" placeholder="0" class="w-12 py-1 px-2 rounded border-gray-300 text-xs text-right font-bold focus:ring-emerald-500">
                                                </div>
                                            </div>
                                        </template>

                                        <!-- Generic / Autre -->
                                        <template x-if="lot.type_produit !== 'Ananas Cayenne Lisse' && lot.type_produit !== 'Ananas Pain de Sucre' && lot.type_produit !== 'Papaye'">
                                            <div class="flex items-center gap-3 bg-white p-2 rounded-md border border-gray-200">
                                                <div class="flex items-center gap-1">
                                                    <span class="text-[11px] font-bold text-gray-500">Sachets 2KG:</span>
                                                    <input type="number" min="0" :name="'lots['+index+'][sachets_2kg]'" x-model="lot.sachets_2kg" placeholder="0" class="w-16 py-1 px-2 rounded border-gray-300 text-xs text-right font-bold">
                                                </div>
                                                <div class="flex items-center gap-1">
                                                    <span class="text-[11px] font-bold text-gray-500">Sachets 1KG:</span>
                                                    <input type="number" min="0" :name="'lots['+index+'][sachets_1kg]'" x-model="lot.sachets_1kg" placeholder="0" class="w-16 py-1 px-2 rounded border-gray-300 text-xs text-right font-bold">
                                                </div>
                                            </div>
                                        </template>
                                    </td>

                                    <!-- Déchets kg -->
                                    <td class="p-3 align-middle">
                                        <input type="number" step="0.01" min="0" :name="'lots['+index+'][dechets_kg]'" x-model="lot.dechets_kg" placeholder="0.00"
                                               class="w-full py-1.5 px-2 rounded-md border-gray-300 text-xs text-right text-rose-600 font-bold focus:border-amber-500 focus:ring-amber-500 bg-white">
                                    </td>

                                    <!-- Qté TTPM kg -->
                                    <td class="p-3 align-middle">
                                        <input type="number" step="0.01" min="0" x-model="lot.cl_ttpm_kg" placeholder="0.00"
                                               :disabled="lot.type_produit === 'Papaye'"
                                               class="w-full py-1.5 px-2 rounded-md border-gray-300 text-xs text-right text-teal-700 font-bold focus:border-amber-500 focus:ring-amber-500 bg-white disabled:bg-gray-100 disabled:text-gray-400">
                                    </td>

                                    <!-- Action Supprimer -->
                                    <td class="p-2 align-middle text-center">
                                        <button type="button" @click="removeLot(index)" x-show="lots.length > 1" title="Supprimer ce lot"
                                                class="w-8 h-8 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition-all flex items-center justify-center mx-auto">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Add Row Button Bottom -->
                <div class="mt-4 flex justify-start">
                    <button type="button" @click="addLot()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold transition-colors flex items-center gap-1.5">
                        <i class="fa-solid fa-plus-circle text-amber-600"></i> Ajouter une ligne de lot
                    </button>
                </div>
            </div>

            <!-- Submit Section -->
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('rp.production-reports.show', $report) }}" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-bold text-xs hover:bg-gray-50 transition-colors">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-lg bg-amber-600 hover:bg-amber-700 text-white font-black text-xs shadow-md transition-all flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Mettre à jour le Rapport
                </button>
            </div>
        </form>

    </div>
</div>

<script>
function productionEditForm() {
    return {
        lots: @json($report->lots),
        addLot() {
            this.lots.push({
                numero_lot: 'Lot 001/ANA/' + new Date().getFullYear(),
                type_produit: 'Ananas Cayenne Lisse',
                quantite_declayee_kg: 0,
                poids_rcl_kg: 0, poids_mcl_kg: 0, poids_rps_kg: 0, poids_mps_kg: 0, poids_lpa_kg: 0,
                sachets_2kg: 0, sachets_1kg: 0, sachets_500g: 0,
                sachets_rcl_2kg: 0, sachets_rcl_1kg: 0,
                sachets_mcl_2kg: 0, sachets_mcl_1kg: 0,
                sachets_rps_2kg: 0, sachets_rps_1kg: 0,
                sachets_mps_2kg: 0, sachets_mps_1kg: 0,
                sachets_lpa_2kg: 0, sachets_lpa_1kg: 0, sachets_lpa_100g: 0,
                sachets_ttpm_1kg: 0,
                dechets_kg: 0,
                cl_ttpm_kg: 0
            });
        },
        removeLot(index) {
            if (this.lots.length > 1) {
                this.lots.splice(index, 1);
            }
        },
        onFruitChange(index) {
            let lot = this.lots[index];
            if (lot.type_produit === 'Papaye') {
                if (!lot.numero_lot || lot.numero_lot.includes('ANA')) {
                    lot.numero_lot = lot.numero_lot ? lot.numero_lot.replace('ANA', 'PAP') : 'Lot 001/PAP/2026';
                }
                // Reset TTPM for Papaye
                lot.cl_ttpm_kg = 0;
                lot.sachets_ttpm_1kg = 0;
            } else if (lot.type_produit === 'Ananas Pain de Sucre') {
                if (!lot.numero_lot || lot.numero_lot.includes('PAP')) {
                    lot.numero_lot = lot.numero_lot ? lot.numero_lot.replace('PAP', 'ANA') : 'Lot 001/ANA/2026';
                }
            } else if (lot.type_produit === 'Ananas Cayenne Lisse') {
                if (!lot.numero_lot || lot.numero_lot.includes('PAP')) {
                    lot.numero_lot = lot.numero_lot ? lot.numero_lot.replace('PAP', 'ANA') : 'Lot 001/ANA/2026';
                }
            }
        }
    }
}
</script>
@endsection
