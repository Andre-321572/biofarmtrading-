@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-[1650px] mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Actions -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <div class="flex items-center gap-3">
                    <span class="p-2.5 bg-emerald-100 text-emerald-700 rounded-xl shadow-sm">
                        <i class="fa-solid fa-file-invoice text-xl"></i>
                    </span>
                    <div>
                        <h1 class="text-2xl font-black text-gray-900">
                            Rapport Journalier DE PRODUCTION : {{ $report->reference }}
                        </h1>
                        <p class="text-xs text-gray-500">
                            Saisi le {{ $report->created_at->format('d/m/Y à H:i') }} par <span class="font-bold text-gray-800">{{ $report->user->name ?? 'Responsable Production' }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('rp.production-reports.pdf', $report) }}" 
                   class="px-4 py-2.5 bg-rose-600 text-white font-bold text-sm rounded-xl shadow-sm hover:bg-rose-700 transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-file-pdf"></i> Télécharger PDF
                </a>

                <a href="{{ route('rp.production-reports.excel', $report) }}" 
                   class="px-4 py-2.5 bg-emerald-600 text-white font-bold text-sm rounded-xl shadow-sm hover:bg-emerald-700 transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-file-excel"></i> Exporter Excel
                </a>

                <a href="{{ route('rp.production-reports.edit', $report) }}" 
                   class="px-4 py-2.5 bg-amber-500 text-white font-bold text-sm rounded-xl shadow-sm hover:bg-amber-600 transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square"></i> Modifier
                </a>

                <a href="{{ route('rp.production-reports.index') }}" 
                   class="px-4 py-2.5 bg-slate-100 text-slate-700 font-bold text-sm rounded-xl hover:bg-slate-200 transition-colors">
                    <i class="fa-solid fa-arrow-left"></i> Retour
                </a>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" class="rounded-xl bg-emerald-50 p-4 border border-emerald-200 mb-6 flex items-center justify-between">
                <div class="flex items-center gap-3 text-emerald-800 font-medium text-sm">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
                    {{ session('success') }}
                </div>
                <button @click="show = false" class="text-emerald-500 hover:text-emerald-700">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        <!-- Document Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-8 mb-8">
            
            <!-- Document Header -->
            <div class="flex flex-col md:flex-row justify-between items-start border-b border-gray-200 pb-6 mb-6 gap-4">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/logo.jpg') }}" class="h-16 w-auto object-contain rounded-xl border border-gray-100 p-1 shadow-sm bg-white" alt="Logo Bio Farm Trading">
                    <div>
                        <div class="text-xl font-black text-emerald-700 tracking-wider uppercase">BIO FARM TRADING</div>
                        <div class="text-xs text-gray-500 mt-0.5">Unité de Transformation & Conditionnement Bio</div>
                        <div class="text-xs text-gray-500">Lomé, Togo</div>
                    </div>
                </div>

                <div class="mt-4 md:mt-0 text-left md:text-right">
                    <div class="inline-block px-3 py-1 bg-emerald-50 text-emerald-700 rounded-lg font-black text-sm border border-emerald-200 uppercase mb-2">
                        Réf : {{ $report->reference }}
                    </div>
                    <div class="text-sm font-bold text-gray-900">
                        Date de production : <span class="text-emerald-600">{{ $report->date_rapport->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex flex-wrap items-center md:justify-end gap-2 text-xs font-bold mt-2">
                        <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-md">
                            <i class="fa-solid fa-users mr-1"></i> Présents: {{ $report->nombre_ouvriers }}
                        </span>
                        <span class="px-2.5 py-1 bg-rose-100 text-rose-800 rounded-md">
                            <i class="fa-solid fa-user-xmark mr-1"></i> Absents: {{ $report->nombre_ouvriers_absents ?? 0 }}
                        </span>
                        <span class="px-2.5 py-1 bg-amber-100 text-amber-800 rounded-md">
                            <i class="fa-solid fa-bed mr-1"></i> Repos: {{ $report->nombre_ouvriers_repos ?? 0 }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Material Tools Inventory Badge Card -->
            <div class="bg-slate-900 text-slate-100 rounded-xl p-4 mb-6">
                <div class="text-xs font-black uppercase tracking-wider text-teal-400 mb-2 flex items-center gap-2">
                    <i class="fa-solid fa-toolbox text-teal-400"></i> Suivi du Matériel d'Atelier (Inventaire Journée)
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                    <div class="bg-slate-800 p-2.5 rounded-lg border border-slate-700">
                        <span class="text-slate-400 font-bold block text-[10px] uppercase">Couteaux Début</span>
                        <span class="text-base font-black text-white">{{ $report->nombre_couteaux_debut ?? 0 }} pcs</span>
                    </div>
                    <div class="bg-slate-800 p-2.5 rounded-lg border border-slate-700">
                        <span class="text-slate-400 font-bold block text-[10px] uppercase">Couteaux Fin</span>
                        <span class="text-base font-black text-emerald-400">{{ $report->nombre_couteaux_fin ?? 0 }} pcs</span>
                    </div>
                    <div class="bg-slate-800 p-2.5 rounded-lg border border-slate-700">
                        <span class="text-slate-400 font-bold block text-[10px] uppercase">Ciseaux Début</span>
                        <span class="text-base font-black text-white">{{ $report->nombre_ciseaux_debut ?? 0 }} pcs</span>
                    </div>
                    <div class="bg-slate-800 p-2.5 rounded-lg border border-slate-700">
                        <span class="text-slate-400 font-bold block text-[10px] uppercase">Ciseaux Fin</span>
                        <span class="text-base font-black text-teal-400">{{ $report->nombre_ciseaux_fin ?? 0 }} pcs</span>
                    </div>
                </div>
            </div>

            <!-- Lots Pelés Badge Section -->
            @if($report->lots_peles)
                <div class="bg-amber-50 rounded-xl p-4 border border-amber-200 mb-6">
                    <div class="text-xs font-bold text-amber-800 uppercase tracking-wider mb-1 flex items-center gap-2">
                        <i class="fa-solid fa-wheat-awn text-amber-600"></i> Section Épluchage / Pelage du jour
                    </div>
                    <div class="text-sm font-bold text-amber-900">
                        {{ $report->lots_peles }}
                    </div>
                </div>
            @endif

            <!-- Summary Totals Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8 bg-gray-50 p-4 rounded-xl border border-gray-100">
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase">Total Déclayé</span>
                    <div class="text-xl font-black text-gray-900 mt-0.5">{{ number_format($report->total_declaye, 2) }} kg</div>
                </div>
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase">Total Conditionnements</span>
                    <div class="text-xl font-black text-blue-600 mt-0.5">{{ $report->total_sachets }} sachets</div>
                    <div class="text-[11px] text-gray-500 font-medium">({{ $report->total_sachets_2kg }}x 2kg, {{ $report->total_sachets_1kg }}x 1kg)</div>
                </div>
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase">Déchets Totaux</span>
                    <div class="text-xl font-black text-rose-600 mt-0.5">{{ number_format($report->total_dechets, 2) }} kg</div>
                </div>
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase">Total TTPM (kg)</span>
                    <div class="text-xl font-black text-teal-600 mt-0.5">{{ number_format($report->total_ttpm, 2) }} kg</div>
                </div>
            </div>

            <!-- Table of Lots Grouped by Variety -->
            <h3 class="text-sm font-extrabold text-gray-900 uppercase tracking-wider mb-3">Détails des Lots de Production par Variété</h3>
            
            <div class="space-y-6 mb-6">
                @foreach($report->grouped_lots as $produit => $lotsGroup)
                    <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm">
                        <!-- Group Header -->
                        <div class="bg-slate-800 text-white px-4 py-2.5 flex items-center justify-between font-bold text-xs uppercase tracking-wider">
                            <span class="flex items-center gap-2">
                                @if(str_contains(strtolower($produit), 'papaye'))
                                    <i class="fa-solid fa-lemon text-amber-400"></i>
                                @elseif(str_contains(strtolower($produit), 'ananas'))
                                    <i class="fa-solid fa-apple-whole text-emerald-400"></i>
                                @else
                                    <i class="fa-solid fa-boxes-stacked text-blue-400"></i>
                                @endif
                                VARIÉTÉ : {{ $produit }}
                            </span>
                            <span class="text-[11px] font-normal text-gray-300">
                                {{ $lotsGroup->count() }} lot(s) enregistré(s)
                            </span>
                        </div>

                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100 text-[11px] font-black text-gray-600 uppercase">
                                <tr>
                                    <th class="px-4 py-2.5 text-left">N° Lot</th>
                                    <th class="px-4 py-2.5 text-right">Qté Déclayée</th>
                                    <th class="px-4 py-2.5 text-center">Poids par Coupe (kg)</th>
                                    <th class="px-4 py-2.5 text-center">Conditionnements (Sachets)</th>
                                    <th class="px-4 py-2.5 text-right">Déchets (kg)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 text-xs">
                                @foreach($lotsGroup as $lot)
                                    <tr class="hover:bg-gray-50/70">
                                        <td class="px-4 py-3 font-extrabold text-gray-900">{{ $lot->numero_lot }}</td>
                                        <td class="px-4 py-3 text-right font-bold text-gray-900">
                                            {{ $lot->quantite_declayee_kg > 0 ? number_format($lot->quantite_declayee_kg, 2) . ' kg' : '-' }}
                                        </td>

                                        <!-- Poids par Coupe -->
                                        <td class="px-4 py-3 text-center">
                                            <div class="flex flex-wrap justify-center gap-1.5 text-[11px]">
                                                @if($lot->poids_rcl_kg > 0)
                                                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 rounded font-black">RCL: {{ number_format($lot->poids_rcl_kg, 2) }} kg</span>
                                                @endif
                                                @if($lot->poids_mcl_kg > 0)
                                                    <span class="px-2 py-0.5 bg-blue-100 text-blue-800 rounded font-black">MCL: {{ number_format($lot->poids_mcl_kg, 2) }} kg</span>
                                                @endif
                                                @if($lot->poids_rps_kg > 0)
                                                    <span class="px-2 py-0.5 bg-amber-100 text-amber-800 rounded font-black">RPS: {{ number_format($lot->poids_rps_kg, 2) }} kg</span>
                                                @endif
                                                @if($lot->poids_mps_kg > 0)
                                                    <span class="px-2 py-0.5 bg-amber-100 text-amber-900 rounded font-black">MPS: {{ number_format($lot->poids_mps_kg, 2) }} kg</span>
                                                @endif
                                                @if($lot->poids_lpa_kg > 0)
                                                    <span class="px-2 py-0.5 bg-orange-100 text-orange-800 rounded font-black">LPA: {{ number_format($lot->poids_lpa_kg, 2) }} kg</span>
                                                @endif
                                                @if($lot->cl_ttpm_kg > 0)
                                                    <span class="px-2 py-0.5 bg-teal-100 text-teal-900 rounded font-black">TTPM: {{ number_format($lot->cl_ttpm_kg, 2) }} kg</span>
                                                @endif
                                                @if($lot->poids_rcl_kg == 0 && $lot->poids_mcl_kg == 0 && $lot->poids_rps_kg == 0 && $lot->poids_mps_kg == 0 && $lot->poids_lpa_kg == 0 && $lot->cl_ttpm_kg == 0)
                                                    <span class="text-gray-400 font-medium">-</span>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- Conditionnement Sachets -->
                                        <td class="px-4 py-3 text-center">
                                            <div class="flex flex-wrap justify-center gap-1 text-[11px]">
                                                @if($lot->sachets_rcl_2kg > 0) <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded font-bold border border-emerald-200">RCL 2kg: {{ $lot->sachets_rcl_2kg }}</span> @endif
                                                @if($lot->sachets_rcl_1kg > 0) <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded font-bold border border-emerald-200">RCL 1kg: {{ $lot->sachets_rcl_1kg }}</span> @endif
                                                @if($lot->sachets_mcl_2kg > 0) <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded font-bold border border-blue-200">MCL 2kg: {{ $lot->sachets_mcl_2kg }}</span> @endif
                                                @if($lot->sachets_mcl_1kg > 0) <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded font-bold border border-blue-200">MCL 1kg: {{ $lot->sachets_mcl_1kg }}</span> @endif
                                                @if($lot->sachets_rps_2kg > 0) <span class="px-2 py-0.5 bg-amber-50 text-amber-700 rounded font-bold border border-amber-200">RPS 2kg: {{ $lot->sachets_rps_2kg }}</span> @endif
                                                @if($lot->sachets_rps_1kg > 0) <span class="px-2 py-0.5 bg-amber-50 text-amber-700 rounded font-bold border border-amber-200">RPS 1kg: {{ $lot->sachets_rps_1kg }}</span> @endif
                                                @if($lot->sachets_mps_2kg > 0) <span class="px-2 py-0.5 bg-amber-50 text-amber-900 rounded font-bold border border-amber-200">MPS 2kg: {{ $lot->sachets_mps_2kg }}</span> @endif
                                                @if($lot->sachets_mps_1kg > 0) <span class="px-2 py-0.5 bg-amber-50 text-amber-900 rounded font-bold border border-amber-200">MPS 1kg: {{ $lot->sachets_mps_1kg }}</span> @endif
                                                @if($lot->sachets_lpa_2kg > 0) <span class="px-2 py-0.5 bg-orange-50 text-orange-700 rounded font-bold border border-orange-200">LPA 2kg: {{ $lot->sachets_lpa_2kg }}</span> @endif
                                                @if($lot->sachets_lpa_1kg > 0) <span class="px-2 py-0.5 bg-orange-50 text-orange-700 rounded font-bold border border-orange-200">LPA 1kg: {{ $lot->sachets_lpa_1kg }}</span> @endif
                                                @if($lot->sachets_lpa_100g > 0) <span class="px-2 py-0.5 bg-orange-50 text-orange-700 rounded font-bold border border-orange-200">LPA 100g: {{ $lot->sachets_lpa_100g }}</span> @endif
                                                @if($lot->sachets_ttpm_1kg > 0) <span class="px-2 py-0.5 bg-teal-50 text-teal-800 rounded font-bold border border-teal-200">TTPM 1kg: {{ $lot->sachets_ttpm_1kg }}</span> @endif
                                                @if($lot->sachets_2kg > 0) <span class="px-2 py-0.5 bg-gray-100 text-gray-800 rounded font-bold">2kg: {{ $lot->sachets_2kg }}</span> @endif
                                                @if($lot->sachets_1kg > 0) <span class="px-2 py-0.5 bg-gray-100 text-gray-800 rounded font-bold">1kg: {{ $lot->sachets_1kg }}</span> @endif
                                                @if($lot->total_sachets == 0) <span class="text-gray-400 font-medium">-</span> @endif
                                            </div>
                                        </td>

                                        <td class="px-4 py-3 text-right font-bold text-rose-600">
                                            {{ $lot->dechets_kg > 0 ? number_format($lot->dechets_kg, 2) . ' kg' : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-emerald-50 text-xs font-black text-gray-900">
                                <tr>
                                    <td class="px-4 py-2.5 text-left uppercase text-emerald-900">Sous-Total {{ $produit }}</td>
                                    <td class="px-4 py-2.5 text-right text-emerald-800">{{ number_format($lotsGroup->sum('quantite_declayee_kg'), 2) }} kg</td>
                                    <td class="px-4 py-2.5 text-center text-emerald-900">
                                        @if($lotsGroup->sum('poids_rcl_kg') > 0) RCL: {{ number_format($lotsGroup->sum('poids_rcl_kg'), 2) }}kg | @endif
                                        @if($lotsGroup->sum('poids_mcl_kg') > 0) MCL: {{ number_format($lotsGroup->sum('poids_mcl_kg'), 2) }}kg | @endif
                                        @if($lotsGroup->sum('poids_rps_kg') > 0) RPS: {{ number_format($lotsGroup->sum('poids_rps_kg'), 2) }}kg | @endif
                                        @if($lotsGroup->sum('poids_mps_kg') > 0) MPS: {{ number_format($lotsGroup->sum('poids_mps_kg'), 2) }}kg | @endif
                                        @if($lotsGroup->sum('poids_lpa_kg') > 0) LPA: {{ number_format($lotsGroup->sum('poids_lpa_kg'), 2) }}kg | @endif
                                        @if($lotsGroup->sum('cl_ttpm_kg') > 0) TTPM: {{ number_format($lotsGroup->sum('cl_ttpm_kg'), 2) }}kg @endif
                                    </td>
                                    <td class="px-4 py-2.5 text-center text-blue-700 font-bold">
                                        {{ $lotsGroup->sum(fn($l) => $l->total_sachets) }} sach. total
                                    </td>
                                    <td class="px-4 py-2.5 text-right text-rose-700">{{ number_format($lotsGroup->sum('dechets_kg'), 2) }} kg</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endforeach
            </div>

            <!-- Legend Section -->
            <div class="mt-6 p-4 bg-gray-50 rounded-xl border border-gray-200">
                <div class="text-[11px] font-black text-gray-700 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-info text-emerald-600"></i> Légende des Abréviations de Production Bio Farm :
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-2 text-xs text-gray-700">
                    <div><span class="font-bold text-emerald-700">RCL</span> : Rondelles Cayenne Lisse</div>
                    <div><span class="font-bold text-emerald-700">MCL</span> : Morceaux Cayenne Lisse</div>
                    <div><span class="font-bold text-emerald-700">RPS</span> : Rondelles Pain de Sucre</div>
                    <div><span class="font-bold text-emerald-700">MPS</span> : Morceaux Pain de Sucre</div>
                    <div><span class="font-bold text-emerald-700">LPA</span> : Lamelles de Papaye</div>
                    <div><span class="font-bold text-emerald-700">TTPM</span> : Très Très Petits Morceaux (1KG)</div>
                </div>
            </div>

            <!-- Notes Section -->
            @if($report->notes)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <h4 class="text-xs font-bold text-gray-500 uppercase mb-1">Observations / Remarques</h4>
                    <p class="text-xs text-gray-700 bg-gray-50 p-3 rounded-lg border border-gray-200 leading-relaxed">
                        {{ $report->notes }}
                    </p>
                </div>
            @endif

            <!-- Signatures Section -->
            <div class="mt-12 pt-8 border-t border-gray-200 grid grid-cols-2 gap-8 text-center text-xs">
                <div>
                    <div class="font-bold text-gray-700 uppercase">Le Responsable Production (RP)</div>
                    <div class="mt-12 border-b border-gray-300 w-48 mx-auto"></div>
                    <div class="text-gray-400 mt-1 italic">{{ $report->user->name ?? 'Signature' }}</div>
                </div>

                <div>
                    <div class="font-bold text-gray-700 uppercase">La Direction Générale (DG)</div>
                    <div class="mt-12 border-b border-gray-300 w-48 mx-auto"></div>
                    <div class="text-gray-400 mt-1 italic">Visa / Validation</div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
