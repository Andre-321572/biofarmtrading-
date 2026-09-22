@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-black text-gray-900 flex items-center gap-3">
                    <span class="p-2.5 bg-emerald-100 text-emerald-700 rounded-xl shadow-sm">
                        <i class="fa-solid fa-file-lines text-xl"></i>
                    </span>
                    Rapports Journaliers de Production
                </h1>
                <p class="text-sm text-gray-500 mt-1 sm:pl-14">
                    Historique et consultation des comptes-rendus d'atelier transmis à la Direction Générale.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('rp.production-reports.create') }}" 
                   class="inline-flex items-center px-5 py-3 rounded-xl bg-emerald-600 text-white font-bold text-sm shadow-md hover:bg-emerald-700 transition-all duration-200">
                    <i class="fa-solid fa-plus-circle mr-2"></i>
                    Nouveau Rapport
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

        <!-- Search & Filter Form -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm mb-6">
            <form method="GET" action="{{ route('rp.production-reports.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Recherche (Réf / N° Lot)</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Ex: RP-2026..., Lot 285"
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3.5 text-gray-400 text-xs"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Date Début</label>
                    <input type="date" name="date_start" value="{{ request('date_start') }}"
                           class="w-full py-2.5 rounded-xl border-gray-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Date Fin</label>
                    <input type="date" name="date_end" value="{{ request('date_end') }}"
                           class="w-full py-2.5 rounded-xl border-gray-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="w-full py-2.5 px-4 bg-gray-900 text-white rounded-xl font-bold text-sm hover:bg-gray-800 transition-colors">
                        <i class="fa-solid fa-filter mr-1"></i> Filtrer
                    </button>
                    @if(request()->anyFilled(['search', 'date_start', 'date_end']))
                        <a href="{{ route('rp.production-reports.index') }}" class="py-2.5 px-4 bg-gray-100 text-gray-600 rounded-xl font-bold text-sm hover:bg-gray-200 transition-colors">
                            Réinitialiser
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Reports Table Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @if($reports->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Référence</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date & Saisi Par</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Effectif</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lots Pelés</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Total Déclayé</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Sachets</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Déchets</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($reports as $report)
                                <tr class="hover:bg-gray-50 transition-colors group">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-black text-xs px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-lg inline-block border border-emerald-100">
                                            {{ $report->reference }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-sm text-gray-900">{{ $report->date_rapport->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $report->user->name ?? 'RP' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-100 text-purple-800">
                                            <i class="fa-solid fa-users mr-1.5 text-[10px]"></i>
                                            {{ $report->nombre_ouvriers }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-600 max-w-xs truncate">
                                        {{ $report->lots_peles ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right font-black text-sm text-gray-900">
                                        {{ number_format($report->total_declaye, 2) }} kg
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-sm text-blue-600">
                                        {{ $report->total_sachets }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-sm text-rose-600">
                                        {{ number_format($report->total_dechets, 2) }} kg
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center space-x-1.5">
                                            <a href="{{ route('rp.production-reports.show', $report) }}" 
                                               class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Consulter">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="{{ route('rp.production-reports.edit', $report) }}" 
                                               class="p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Modifier">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <a href="{{ route('rp.production-reports.pdf', $report) }}" 
                                               class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Télécharger PDF">
                                                <i class="fa-solid fa-file-pdf"></i>
                                            </a>
                                            <a href="{{ route('rp.production-reports.excel', $report) }}" 
                                               class="p-2 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Exporter Excel">
                                                <i class="fa-solid fa-file-excel"></i>
                                            </a>
                                            <form action="{{ route('rp.production-reports.destroy', $report) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce rapport de production ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Supprimer">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($reports->hasPages())
                    <div class="p-4 border-t border-gray-100 bg-gray-50">
                        {{ $reports->links() }}
                    </div>
                @endif
            @else
                <div class="p-12 text-center text-gray-500">
                    <i class="fa-solid fa-folder-open text-4xl text-gray-300 mb-3"></i>
                    <p class="text-base font-semibold text-gray-700">Aucun rapport trouvé.</p>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
