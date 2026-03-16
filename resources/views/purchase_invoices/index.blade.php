@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    {{-- Header --}}
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Factures d'Achat</h1>
            <p class="mt-1 text-sm text-gray-500">Liste des opérations d'achat de la coopérative.</p>
        </div>
        <a href="{{ route('purchase_invoices.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
            <i class="fa-solid fa-plus mr-2"></i> Nouvelle Facture
        </a>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 rounded-md flex items-center gap-3 text-green-700 border-l-4 border-green-500">
        <i class="fa-solid fa-circle-check"></i>
        <p class="text-sm font-bold">{{ session('success') }}</p>
    </div>
    @endif

    {{-- Table --}}
    <div class="bg-white shadow rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Bon / Date</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Producteur & Zone</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Poids Brut</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Net à payer</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($invoices as $invoice)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">{{ $invoice->bon_no }}</div>
                            <div class="text-xs text-gray-500">{{ $invoice->date_invoice->format('d/m/Y') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 font-medium">{{ $invoice->producteur ?: '—' }}</div>
                            <div class="text-xs text-indigo-600">{{ $invoice->zone ?: '—' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">{{ number_format($invoice->total_weight, 2, ',', ' ') }} kg</div>
                            <div class="text-[10px] text-gray-400">
                                @if($invoice->total_weight_pf > 0) PF: {{ number_format($invoice->total_weight_pf, 1) }} @endif
                                @if($invoice->total_weight_gf > 0) | GF: {{ number_format($invoice->total_weight_gf, 1) }} @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-sm font-bold text-green-600">{{ number_format($invoice->net_a_payer, 0, ',', ' ') }} FCFA</div>
                            @if($invoice->total_credit > 0)
                            <div class="text-[9px] text-red-400">Crédit: {{ number_format($invoice->total_credit, 0, ',', ' ') }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex justify-center gap-3">
                                <a href="{{ route('purchase_invoices.show', $invoice) }}" class="text-indigo-600 hover:text-indigo-900" title="Détails">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('purchase_invoices.pdf', $invoice) }}" target="_blank" class="text-red-600 hover:text-red-900" title="PDF">
                                    <i class="fa-solid fa-file-pdf"></i>
                                </a>
                                @can('delete', $invoice)
                                <form action="{{ route('purchase_invoices.destroy', $invoice) }}" method="POST" onsubmit="return confirm('Supprimer ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            Aucune facture trouvée.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($invoices->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $invoices->links() }}
        </div>
        @endif
    </div>

    {{-- Stats cards simple --}}
    @if($invoices->count() > 0)
    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
            <p class="text-xs font-bold text-gray-400 uppercase">Volume Total</p>
            <p class="text-2xl font-bold text-indigo-600">{{ number_format($invoices->sum('total_weight'), 1, ',', ' ') }} kg</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
            <p class="text-xs font-bold text-gray-400 uppercase">Valeur Totale</p>
            <p class="text-2xl font-bold text-green-600">{{ number_format($invoices->sum('net_a_payer'), 0, ',', ' ') }} FCFA</p>
        </div>
    </div>
    @endif
</div>
@endsection