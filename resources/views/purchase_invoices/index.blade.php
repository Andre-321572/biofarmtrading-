@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-black text-slate-900">Mes Factures d'Achat</h1>
                <p class="text-sm text-slate-500">Gestion des achats coopérative Bio Farm Trading</p>
            </div>
            <a href="{{ route('purchase_invoices.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 rounded-xl text-sm font-bold text-white hover:bg-indigo-700 shadow-indigo-200 shadow-lg transition">
                <i class="fa-solid fa-plus"></i> Nouvelle Facture
            </a>
        </div>

        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-bold">
            {{ session('success') }}
        </div>
        @endif

        {{-- LISTE --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-wider">Bon N° / Date</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-wider">Producteur / Zone</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-wider">Fruit</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-wider">Poids Total</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-wider text-right">Net à Payer</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-wider text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($invoices as $invoice)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4">
                                <div class="text-sm font-black text-slate-900">{{ $invoice->bon_no }}</div>
                                <div class="text-[10px] font-bold text-slate-400 uppercase">{{ $invoice->date_invoice->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-slate-700">{{ $invoice->producteur }}</div>
                                <div class="text-[10px] font-bold text-slate-400 uppercase italic">{{ $invoice->zone }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-indigo-50 text-indigo-700 uppercase">
                                    {{ $invoice->fruit }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-black text-slate-900">{{ number_format($invoice->total_weight, 2, ',', ' ') }} kg</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="text-sm font-black text-green-600">{{ number_format($invoice->net_a_payer, 0, ',', ' ') }} FCFA</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('purchase_invoices.show', $invoice) }}" class="p-2 text-slate-400 hover:text-indigo-600 transition" title="Détails">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('purchase_invoices.pdf', $invoice) }}" class="p-2 text-slate-400 hover:text-red-600 transition" title="Télécharger PDF">
                                        <i class="fa-solid fa-file-pdf"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mb-4">
                                        <i class="fa-solid fa-folder-open text-slate-300 text-2xl"></i>
                                    </div>
                                    <p class="text-slate-500 font-bold">Aucune facture trouvée</p>
                                    <a href="{{ route('purchase_invoices.create') }}" class="mt-4 text-indigo-600 text-sm font-black hover:underline">Créer la première facture</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($invoices->hasPages())
            <div class="px-6 py-4 border-t border-slate-200">
                {{ $invoices->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
