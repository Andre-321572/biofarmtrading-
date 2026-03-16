@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- HEADER AVEC STATS RAPIDES --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900">Factures d'Achat</h1>
                <p class="text-xs sm:text-sm text-slate-500">
                    <span class="font-bold text-indigo-600">{{ $invoices->total() }}</span> factures trouvées
                </p>
            </div>
            <a href="{{ route('purchase_invoices.create') }}" 
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 rounded-xl text-sm font-bold text-white hover:bg-indigo-700 shadow-indigo-200 shadow-lg transition">
                <i class="fa-solid fa-plus"></i> Nouvelle Facture
            </a>
        </div>

        {{-- MESSAGES FLASH --}}
        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-green-500"></i>
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation text-red-500"></i>
            {{ session('error') }}
        </div>
        @endif

        {{-- LISTE DES FACTURES --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-wider">Bon / Date</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-wider">Producteur</th>
                            <th class="hidden sm:table-cell px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-wider">Fruit</th>
                            <th class="hidden sm:table-cell px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-wider">Poids</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-wider text-right">Net à payer</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-wider text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($invoices as $invoice)
                        <tr class="hover:bg-slate-50 transition">
                            {{-- Colonne Bon/Date --}}
                            <td class="px-6 py-4">
                                <div class="text-sm font-black text-slate-900">{{ $invoice->bon_no }}</div>
                                <div class="text-[10px] font-bold text-slate-400 uppercase italic">
                                    {{ $invoice->date_invoice->format('d/m/Y') }}
                                </div>
                            </td>

                            {{-- Colonne Producteur --}}
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-slate-700">
                                    {{ $invoice->producteur ?: '—' }}
                                </div>
                                <div class="text-[10px] font-bold text-slate-400 uppercase italic">
                                    {{ $invoice->zone ?: '—' }}
                                </div>
                                @if($invoice->code_parcelle_matricule)
                                <div class="text-[8px] font-bold text-indigo-400">
                                    {{ $invoice->code_parcelle_matricule }}
                                </div>
                                @endif
                            </td>

                            {{-- Colonne Fruit --}}
                            <td class="hidden sm:table-cell px-6 py-4">
                                @if($invoice->fruit)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-indigo-50 text-indigo-700 uppercase">
                                    {{ $invoice->fruit }}
                                </span>
                                @else
                                <span class="text-slate-300">—</span>
                                @endif
                            </td>

                            {{-- Colonne Poids avec détails GF/PF --}}
                            <td class="hidden sm:table-cell px-6 py-4">
                                <div class="text-sm font-black text-slate-900">
                                    {{ number_format($invoice->total_weight, 2, ',', ' ') }} kg
                                </div>
                                
                                {{-- Détail des calibres --}}
                                <div class="flex gap-3 mt-1 text-[9px] font-bold">
                                    @if($invoice->total_weight_pf > 0)
                                    <span class="text-indigo-600">
                                        PF: {{ number_format($invoice->total_weight_pf, 2, ',', ' ') }} kg
                                    </span>
                                    @endif
                                    
                                    @if($invoice->total_weight_gf > 0)
                                    <span class="text-orange-600">
                                        GF: {{ number_format($invoice->total_weight_gf, 2, ',', ' ') }} kg
                                    </span>
                                    @endif
                                </div>

                                {{-- Pourcentage d'avarie si > 0 --}}
                                @if($invoice->avarie_pct > 0)
                                <div class="text-[8px] text-red-500 font-bold mt-1">
                                    Avarie: {{ number_format($invoice->avarie_pct, 2, ',', ' ') }}%
                                </div>
                                @endif
                            </td>

                            {{-- Colonne Net à payer --}}
                            <td class="px-6 py-4 text-right">
                                <div class="text-sm font-black text-green-600">
                                    {{ number_format($invoice->net_a_payer, 0, ',', ' ') }} FCFA
                                </div>
                                {{-- Petit détail du montant --}}
                                <div class="text-[8px] text-slate-400">
                                    {{ number_format($invoice->montant_total, 0, ',', ' ') }} + 
                                    {{ number_format($invoice->montant_total_prime, 0, ',', ' ') }} prime
                                    @if($invoice->total_credit > 0)
                                    <br>- {{ number_format($invoice->total_credit, 0, ',', ' ') }} crédit
                                    @endif
                                </div>
                            </td>

                            {{-- Colonne Actions --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('purchase_invoices.show', $invoice) }}" 
                                       class="p-2 text-slate-400 hover:text-indigo-600 transition" 
                                       title="Voir détails">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    
                                    <a href="{{ route('purchase_invoices.pdf', $invoice) }}" 
                                       class="p-2 text-slate-400 hover:text-red-600 transition" 
                                       title="Télécharger PDF"
                                       target="_blank">
                                        <i class="fa-solid fa-file-pdf"></i>
                                    </a>
                                    
                                    {{-- Option de suppression si nécessaire --}}
                                    @can('delete', $invoice)
                                    <form action="{{ route('purchase_invoices.destroy', $invoice) }}" 
                                          method="POST" 
                                          class="inline"
                                          onsubmit="return confirm('Supprimer cette facture ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 text-slate-400 hover:text-red-600 transition" 
                                                title="Supprimer">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mb-4">
                                        <i class="fa-solid fa-file-invoice text-slate-300 text-2xl"></i>
                                    </div>
                                    <p class="text-slate-500 font-bold">Aucune facture trouvée</p>
                                    <p class="text-xs text-slate-400 mt-1">Commencez par créer une nouvelle facture</p>
                                    <a href="{{ route('purchase_invoices.create') }}" 
                                       class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-600 rounded-lg text-sm font-bold hover:bg-indigo-100 transition">
                                        <i class="fa-solid fa-plus"></i> Créer une facture
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination --}}
            @if($invoices->hasPages())
            <div class="px-6 py-4 border-t border-slate-200">
                {{ $invoices->links() }}
            </div>
            @endif
        </div>

        {{-- Résumé rapide --}}
        @if($invoices->count() > 0)
        <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-xl border border-slate-200">
                <p class="text-[10px] font-bold text-slate-400 uppercase">Total poids</p>
                <p class="text-lg font-black text-indigo-600">
                    {{ number_format($invoices->sum('total_weight'), 0, ',', ' ') }} kg
                </p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200">
                <p class="text-[10px] font-bold text-slate-400 uppercase">Total montant</p>
                <p class="text-lg font-black text-green-600">
                    {{ number_format($invoices->sum('net_a_payer'), 0, ',', ' ') }} FCFA
                </p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection