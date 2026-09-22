@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header & Quick Action -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-black tracking-tight text-gray-900 flex items-center gap-3">
                    <span class="p-2.5 bg-emerald-100 text-emerald-700 rounded-xl shadow-sm">
                        <i class="fa-solid fa-industry text-xl"></i>
                    </span>
                    Espace Responsable Production (RP)
                </h1>
                <p class="text-sm text-gray-500 mt-1 sm:pl-14">
                    Tableau de bord exécutif de transformation, conditionnement et suivi de rendement.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('rp.production-reports.index') }}" 
                   class="inline-flex items-center px-4 py-2.5 rounded-xl bg-gray-100 text-gray-700 font-bold text-sm hover:bg-gray-200 transition-colors">
                    <i class="fa-solid fa-list-check mr-2"></i>
                    Tous les Rapports
                </a>
                <a href="{{ route('rp.production-reports.create') }}" 
                   class="inline-flex items-center px-5 py-2.5 rounded-xl bg-emerald-600 text-white font-bold text-sm shadow-md hover:bg-emerald-700 transition-all duration-200">
                    <i class="fa-solid fa-plus-circle mr-2"></i>
                    Nouveau Rapport Journalier
                </a>
            </div>
        </div>

        <!-- Section 1: Executive KPI Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5 mb-8">
            
            <!-- Card 1: Total Déclayé -->
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[11px] font-black text-gray-400 uppercase tracking-wider">Quantité Déclayée</span>
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-scale-balanced text-base"></i>
                    </div>
                </div>
                <div class="text-2xl font-black text-gray-900">
                    {{ number_format($totalDeclayeMonth, 1) }} <span class="text-xs font-semibold text-gray-400">kg</span>
                </div>
                <p class="text-[11px] text-emerald-600 mt-2 flex items-center gap-1 font-bold">
                    <i class="fa-solid fa-chart-line"></i> Total transformé ce mois
                </p>
            </div>

            <!-- Card 2: Total Sachets -->
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[11px] font-black text-gray-400 uppercase tracking-wider">Conditionnement</span>
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-box-archive text-base"></i>
                    </div>
                </div>
                <div class="text-2xl font-black text-gray-900">
                    {{ number_format($totalSachetsMonth) }} <span class="text-xs font-semibold text-gray-400">sachets</span>
                </div>
                <p class="text-[11px] text-blue-600 mt-2 flex items-center gap-1 font-bold">
                    <i class="fa-solid fa-cubes"></i> Total conditionné ce mois
                </p>
            </div>

            <!-- Card 3: Taux de Déchets -->
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[11px] font-black text-gray-400 uppercase tracking-wider">Déchets & Pertes</span>
                    <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-trash-arrow-up text-base"></i>
                    </div>
                </div>
                <div class="text-2xl font-black text-gray-900">
                    {{ number_format($totalDechetsMonth, 1) }} <span class="text-xs font-semibold text-gray-400">kg</span>
                </div>
                <div class="mt-2 flex items-center gap-1">
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase {{ $dechetsPercentage > 20 ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700' }}">
                        Taux: {{ $dechetsPercentage }}%
                    </span>
                    <span class="text-[11px] text-gray-400 font-medium">du déclayé</span>
                </div>
            </div>

            <!-- Card 4: TTPM Produit -->
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[11px] font-black text-gray-400 uppercase tracking-wider">Total TTPM</span>
                    <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-wheat-awn text-base"></i>
                    </div>
                </div>
                <div class="text-2xl font-black text-gray-900">
                    {{ number_format($totalTtpmMonth, 1) }} <span class="text-xs font-semibold text-gray-400">kg</span>
                </div>
                <p class="text-[11px] text-amber-600 mt-2 flex items-center gap-1 font-bold">
                    <i class="fa-solid fa-scissors"></i> Très Très Petits Morceaux
                </p>
            </div>

            <!-- Card 5: Productivité Ouvrier -->
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[11px] font-black text-gray-400 uppercase tracking-wider">Productivité / Ouvrier</span>
                    <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-user-gear text-base"></i>
                    </div>
                </div>
                <div class="text-2xl font-black text-gray-900">
                    {{ $productivityPerWorker }} <span class="text-xs font-semibold text-gray-400">kg/ouvrier</span>
                </div>
                <p class="text-[11px] text-purple-600 mt-2 flex items-center gap-1 font-bold">
                    <i class="fa-solid fa-users"></i> Moy. {{ $avgOuvriersMonth }} ouvriers/jour
                </p>
            </div>

        </div>

        <!-- Section 2: Répartition des Coupes & Conditionnements du Mois -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-8">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-6">
                <div>
                    <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa-solid fa-pie-chart text-emerald-600"></i>
                        Répartition des Conditionnements par Produit & Coupe (Mois en Cours)
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">Détail cumulé des sachets produits pour RCL, MCL, RPS, MPS et Papaye LPA.</p>
                </div>
                <span class="px-3 py-1 bg-emerald-50 text-emerald-700 rounded-lg text-xs font-bold border border-emerald-100">
                    Bio Farm Production
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Box 1: Cayenne Lisse (RCL & MCL) -->
                <div class="bg-gradient-to-br from-emerald-50/50 to-emerald-100/30 rounded-xl p-5 border border-emerald-100">
                    <div class="flex items-center gap-2.5 mb-4">
                        <span class="p-2 bg-emerald-600 text-white rounded-lg shadow-sm">
                            <i class="fa-solid fa-apple-whole text-sm"></i>
                        </span>
                        <div>
                            <h3 class="font-extrabold text-gray-900 text-sm">Ananas Cayenne Lisse (CL)</h3>
                            <span class="text-[10px] font-bold text-emerald-700">Rondelles (RCL) & Morceaux (MCL)</span>
                        </div>
                    </div>

                    <div class="space-y-2.5 text-xs">
                        <div class="flex justify-between items-center bg-white p-2.5 rounded-lg border border-emerald-100/60 shadow-2xs">
                            <span class="font-bold text-gray-700">Rondelles RCL (2KG)</span>
                            <span class="font-black text-emerald-800 bg-emerald-100/80 px-2 py-0.5 rounded">{{ $cutStats['rcl_2kg'] }} sach.</span>
                        </div>
                        <div class="flex justify-between items-center bg-white p-2.5 rounded-lg border border-emerald-100/60 shadow-2xs">
                            <span class="font-bold text-gray-700">Rondelles RCL (1KG)</span>
                            <span class="font-black text-emerald-800 bg-emerald-100/80 px-2 py-0.5 rounded">{{ $cutStats['rcl_1kg'] }} sach.</span>
                        </div>
                        <div class="flex justify-between items-center bg-white p-2.5 rounded-lg border border-emerald-100/60 shadow-2xs">
                            <span class="font-bold text-gray-700">Morceaux MCL (2KG)</span>
                            <span class="font-black text-emerald-800 bg-emerald-100/80 px-2 py-0.5 rounded">{{ $cutStats['mcl_2kg'] }} sach.</span>
                        </div>
                        <div class="flex justify-between items-center bg-white p-2.5 rounded-lg border border-emerald-100/60 shadow-2xs">
                            <span class="font-bold text-gray-700">Morceaux MCL (1KG)</span>
                            <span class="font-black text-emerald-800 bg-emerald-100/80 px-2 py-0.5 rounded">{{ $cutStats['mcl_1kg'] }} sach.</span>
                        </div>
                    </div>
                </div>

                <!-- Box 2: Pain de Sucre (RPS & MPS) -->
                <div class="bg-gradient-to-br from-amber-50/50 to-amber-100/30 rounded-xl p-5 border border-amber-100">
                    <div class="flex items-center gap-2.5 mb-4">
                        <span class="p-2 bg-amber-500 text-white rounded-lg shadow-sm">
                            <i class="fa-solid fa-lemon text-sm"></i>
                        </span>
                        <div>
                            <h3 class="font-extrabold text-gray-900 text-sm">Ananas Pain de Sucre (PS)</h3>
                            <span class="text-[10px] font-bold text-amber-700">Rondelles (RPS) & Morceaux (MPS)</span>
                        </div>
                    </div>

                    <div class="space-y-2.5 text-xs">
                        <div class="flex justify-between items-center bg-white p-2.5 rounded-lg border border-amber-100/60 shadow-2xs">
                            <span class="font-bold text-gray-700">Rondelles RPS (2KG)</span>
                            <span class="font-black text-amber-800 bg-amber-100/80 px-2 py-0.5 rounded">{{ $cutStats['rps_2kg'] }} sach.</span>
                        </div>
                        <div class="flex justify-between items-center bg-white p-2.5 rounded-lg border border-amber-100/60 shadow-2xs">
                            <span class="font-bold text-gray-700">Rondelles RPS (1KG)</span>
                            <span class="font-black text-amber-800 bg-amber-100/80 px-2 py-0.5 rounded">{{ $cutStats['rps_1kg'] }} sach.</span>
                        </div>
                        <div class="flex justify-between items-center bg-white p-2.5 rounded-lg border border-amber-100/60 shadow-2xs">
                            <span class="font-bold text-gray-700">Morceaux MPS (2KG)</span>
                            <span class="font-black text-amber-800 bg-amber-100/80 px-2 py-0.5 rounded">{{ $cutStats['mps_2kg'] }} sach.</span>
                        </div>
                        <div class="flex justify-between items-center bg-white p-2.5 rounded-lg border border-amber-100/60 shadow-2xs">
                            <span class="font-bold text-gray-700">Morceaux MPS (1KG)</span>
                            <span class="font-black text-amber-800 bg-amber-100/80 px-2 py-0.5 rounded">{{ $cutStats['mps_1kg'] }} sach.</span>
                        </div>
                    </div>
                </div>

                <!-- Box 3: Papaye (LPA) -->
                <div class="bg-gradient-to-br from-orange-50/50 to-orange-100/30 rounded-xl p-5 border border-orange-100">
                    <div class="flex items-center gap-2.5 mb-4">
                        <span class="p-2 bg-orange-500 text-white rounded-lg shadow-sm">
                            <i class="fa-solid fa-seedling text-sm"></i>
                        </span>
                        <div>
                            <h3 class="font-extrabold text-gray-900 text-sm">Papaye (LPA)</h3>
                            <span class="text-[10px] font-bold text-orange-700">Lamelles de Papaye (2KG, 1KG, 100G)</span>
                        </div>
                    </div>

                    <div class="space-y-2.5 text-xs">
                        <div class="flex justify-between items-center bg-white p-2.5 rounded-lg border border-orange-100/60 shadow-2xs">
                            <span class="font-bold text-gray-700">Lamelles LPA (2KG)</span>
                            <span class="font-black text-orange-800 bg-orange-100/80 px-2 py-0.5 rounded">{{ $cutStats['lpa_2kg'] }} sach.</span>
                        </div>
                        <div class="flex justify-between items-center bg-white p-2.5 rounded-lg border border-orange-100/60 shadow-2xs">
                            <span class="font-bold text-gray-700">Lamelles LPA (1KG)</span>
                            <span class="font-black text-orange-800 bg-orange-100/80 px-2 py-0.5 rounded">{{ $cutStats['lpa_1kg'] }} sach.</span>
                        </div>
                        <div class="flex justify-between items-center bg-white p-2.5 rounded-lg border border-orange-100/60 shadow-2xs">
                            <span class="font-bold text-gray-700">Lamelles LPA (100G)</span>
                            <span class="font-black text-orange-800 bg-orange-100/80 px-2 py-0.5 rounded">{{ $cutStats['lpa_100g'] }} sach.</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Section 3: Recent Production Reports Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa-solid fa-clock-rotate-left text-emerald-600"></i>
                        Derniers Rapports Journaliers Soumis
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">Synthèse et téléchargement direct des derniers comptes-rendus d'atelier.</p>
                </div>
                <a href="{{ route('rp.production-reports.index') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                    Voir l'historique complet <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            @if($recentReports->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Référence</th>
                                <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Effectif</th>
                                <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lots Pelés</th>
                                <th class="px-6 py-3.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Total Déclayé</th>
                                <th class="px-6 py-3.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Total Sachets</th>
                                <th class="px-6 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentReports as $report)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            {{ $report->reference }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                        {{ $report->date_rapport->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-100 text-purple-800">
                                            {{ $report->nombre_ouvriers }} ouvriers
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-600 max-w-xs truncate">
                                        {{ $report->lots_peles ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-gray-900">
                                        {{ number_format($report->total_declaye, 2) }} kg
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-blue-600">
                                        {{ $report->total_sachets }} sachets
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('rp.production-reports.show', $report) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Voir détails">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="{{ route('rp.production-reports.pdf', $report) }}" class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Télécharger PDF">
                                                <i class="fa-solid fa-file-pdf"></i>
                                            </a>
                                            <a href="{{ route('rp.production-reports.excel', $report) }}" class="p-2 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Télécharger Excel">
                                                <i class="fa-solid fa-file-excel"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center text-gray-500">
                    <i class="fa-solid fa-clipboard-list text-4xl text-gray-300 mb-3"></i>
                    <p class="text-base font-semibold text-gray-700">Aucun rapport de production enregistré pour le moment.</p>
                    <a href="{{ route('rp.production-reports.create') }}" class="mt-4 inline-flex items-center px-4 py-2 rounded-xl bg-emerald-600 text-white font-bold text-sm">
                        Créer le premier rapport
                    </a>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
