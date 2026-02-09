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

            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 bg-slate-50 border-b border-slate-100">
                    <h3 class="font-black text-xl text-slate-800 uppercase tracking-tight">Détail des Ventes par Produit</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">
                                <th class="px-8 py-5">Produit</th>
                                <th class="px-8 py-5">Type</th>
                                <th class="px-8 py-5 text-center">Quantité</th>
                                <th class="px-8 py-5 text-right">CA Généré</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($productStats as $stat)
                                <tr class="hover:bg-green-50/30 transition-colors group">
                                    <td class="px-8 py-5 font-black text-slate-900 group-hover:text-green-700 transition-colors">{{ $stat->name }}</td>
                                    <td class="px-8 py-5">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $stat->unit_type === 'detail' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                                            {{ $stat->unit_type === 'detail' ? 'Détail' : 'Gros' }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        <span class="text-sm font-black text-slate-900 bg-slate-100 px-3 py-1.5 rounded-xl">
                                            {{ number_format($stat->total_qty, 0) }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-right font-black text-lg text-slate-900">
                                        {{ number_format($stat->total_revenue, 0, ',', ' ') }} <span class="text-[10px] text-slate-400 uppercase">F</span>
                                    </td>
                                </tr>
                            @endforeach
                            @if($productStats->isEmpty())
                                <tr>
                                    <td colspan="4" class="px-8 py-20 text-center">
                                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                        </div>
                                        <p class="text-slate-400 font-black uppercase text-[10px] tracking-[0.2em]">Aucune transaction ce mois-ci</p>
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
