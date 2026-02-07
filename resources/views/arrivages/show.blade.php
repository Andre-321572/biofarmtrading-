@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                <a href="{{ route('arrivages.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                Détails de l'Arrivage #{{ $arrivage->id }}
            </h2>
            <div class="flex gap-3">
                <a href="{{ route('arrivages.pdf', $arrivage) }}" class="inline-flex items-center px-4 py-2 bg-red-50 text-red-700 border border-red-200 rounded-lg hover:bg-red-100 transition-colors font-medium text-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    Télécharger PDF
                </a>
                <a href="{{ route('arrivages.excel', $arrivage) }}" class="inline-flex items-center px-4 py-2 bg-green-50 text-green-700 border border-green-200 rounded-lg hover:bg-green-100 transition-colors font-medium text-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Télécharger Excel
                </a>
            </div>
        </div>

        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
            <!-- Info Banner -->
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Transport</p>
                    <div class="mt-1 flex items-center gap-2">
                        <span class="font-bold text-gray-900">{{ $arrivage->chauffeur }}</span>
                        <span class="px-2 py-0.5 rounded-md bg-gray-200 text-gray-600 text-xs font-mono">{{ $arrivage->matricule_camion }}</span>
                    </div>
                </div>
                <div class="md:text-right">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Provenance & Date</p>
                    <div class="mt-1">
                        <span class="font-medium text-gray-900">{{ $arrivage->zone_provenance }}</span>
                        <span class="text-gray-400 mx-2">•</span>
                        <span class="text-gray-600">{{ $arrivage->date_arrivage->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Details Table -->
            <div class="p-6">
                @if($arrivage->details->count() > 0)
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-6 bg-green-500 rounded-full"></span>
                        Détails des Fruits
                    </h3>
                    <div class="overflow-hidden border border-gray-200 rounded-xl">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fruit</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Variété</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Poids (kg)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($arrivage->details as $detail)
                                    @if($detail->poids > 0)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ ucfirst($detail->fruit) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($detail->variete === 'cayenne_lisse')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Cayenne Lisse
                                                </span>
                                            @elseif($detail->variete === 'braza')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                    Braza
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                                            {{ number_format($detail->poids, 2) }}
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Aucun détail disponible.</p>
                @endif
            </div>

            <!-- Totals Section -->
            <div class="bg-gray-50 p-6 border-t border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Récapitulatif Financier</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Ananas Box -->
                    @if($arrivage->total_ananas > 0)
                    <div class="bg-blue-50/50 rounded-xl p-5 border border-blue-100">
                        <h4 class="text-blue-800 font-bold mb-3 flex items-center gap-2">
                            <span>🍍</span> ANANAS
                        </h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between text-blue-600">
                                <span>Cayenne Lisse:</span>
                                <span class="font-medium">{{ number_format($arrivage->total_ananas_cayenne, 2) }} kg</span>
                            </div>
                            <div class="flex justify-between text-blue-600">
                                <span>Braza:</span>
                                <span class="font-medium">{{ number_format($arrivage->total_ananas_braza, 2) }} kg</span>
                            </div>
                            <div class="pt-2 mt-2 border-t border-blue-200 flex justify-between text-blue-900 font-bold">
                                <span>Total Ananas:</span>
                                <span>{{ number_format($arrivage->total_ananas, 2) }} kg</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Papaye Box -->
                    @if($arrivage->total_papaye > 0)
                    <div class="bg-orange-50/50 rounded-xl p-5 border border-orange-100">
                        <h4 class="text-orange-800 font-bold mb-3 flex items-center gap-2">
                            <span>🥭</span> PAPAYE
                        </h4>
                        <div class="flex flex-col justify-end h-full">
                            <div class="flex justify-between text-orange-900 font-bold text-lg">
                                <span>Total Papaye:</span>
                                <span>{{ number_format($arrivage->total_papaye, 2) }} kg</span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Grand Total -->
                <div class="mt-6 bg-gray-900 rounded-xl p-6 text-white flex justify-between items-center shadow-lg">
                    <div>
                        <p class="text-gray-400 text-sm uppercase tracking-wider font-semibold">Poids Total Arrivage</p>
                        <p class="text-xs text-gray-500 mt-1">Tous fruits confondus</p>
                    </div>
                    <div class="text-3xl font-black tracking-tight">
                        {{ number_format($arrivage->total_general, 2) }} <span class="text-lg font-normal text-gray-400">kg</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
