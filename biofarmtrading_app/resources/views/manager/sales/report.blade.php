<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center gap-4">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Rapport Mensuel') }} - <span class="text-green-600">{{ \Carbon\Carbon::now()->locale('fr')->isoFormat('MMMM YYYY') }}</span>
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('manager.sales.report.pdf') }}" class="flex items-center gap-2 px-6 py-2 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-all shadow-lg shadow-red-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Télécharger PDF
                </a>
                <a href="{{ route('manager.sales.index') }}" class="px-6 py-2 bg-gray-100 text-gray-600 font-bold rounded-xl hover:bg-gray-200 transition-all">
                    Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                <div class="p-8 bg-green-600 text-white text-center">
                    <p class="text-lg opacity-80 mb-2">Chiffre d'Affaires Total (Mois en cours)</p>
                    <p class="text-5xl font-black">{{ number_format($monthTotal, 0, ',', ' ') }} <span class="text-2xl font-medium">FCFA</span></p>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50">
                    <h3 class="font-bold text-lg text-gray-800">Ddétail par Produit</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs font-bold uppercase tracking-widest">
                                <th class="px-8 py-4">Produit</th>
                                <th class="px-8 py-4 text-center">Quantité Vendue</th>
                                <th class="px-8 py-4 text-right">Montant Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($productStats as $stat)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-8 py-4 font-bold text-gray-900">{{ $stat->name }}</td>
                                    <td class="px-8 py-4 text-center">
                                        <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full font-bold text-sm">
                                            {{ $stat->total_qty }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-4 text-right font-black text-gray-800">
                                        {{ number_format($stat->total_revenue, 0, ',', ' ') }} FCFA
                                    </td>
                                </tr>
                            @endforeach
                            @if($productStats->isEmpty())
                                <tr>
                                    <td colspan="3" class="px-8 py-12 text-center text-gray-400">
                                        Aucune donnée pour ce mois.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
