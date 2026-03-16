@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    {{-- Header --}}
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-indigo-600 flex items-center justify-center text-white shadow">
                <i class="fa-solid fa-file-invoice-dollar text-xl"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-900">Détails de la Facture #{{ $purchaseInvoice->bon_no }}</h1>
                <p class="text-xs text-gray-500 font-medium">Enregistrée le {{ $purchaseInvoice->date_invoice->format('d/m/Y') }}</p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('purchase_invoices.pdf', $purchaseInvoice) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md text-sm font-bold text-white hover:bg-red-700 shadow-sm">
                <i class="fa-solid fa-file-pdf mr-2"></i> PDF
            </a>
            <a href="{{ route('purchase_invoices.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 shadow-sm">
                <i class="fa-solid fa-arrow-left mr-2"></i> Retour
            </a>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg border border-gray-200 overflow-hidden">
        {{-- Top Info Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-gray-100 bg-gray-50 border-b border-gray-200">
            <div class="p-4">
                <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Producteur / O.P</p>
                <p class="text-sm font-bold text-gray-800">{{ $purchaseInvoice->producteur ?: '—' }}</p>
                <p class="text-xs text-indigo-600 mt-1">{{ $purchaseInvoice->zone ?: '—' }}</p>
            </div>
            <div class="p-4">
                <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Logistique</p>
                <p class="text-sm font-bold text-gray-800">{{ $purchaseInvoice->chauffeur ?: '—' }}</p>
                <p class="text-xs text-gray-500 mt-1 uppercase">{{ $purchaseInvoice->code_parcelle_matricule ?: '—' }}</p>
            </div>
            <div class="p-4">
                <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Résumé Poids</p>
                <p class="text-sm font-bold text-gray-800">{{ number_format($purchaseInvoice->total_weight, 2) }} kg (Brut)</p>
                <p class="text-xs text-orange-600 mt-1">Avarie: {{ number_format($purchaseInvoice->avarie_pct, 2) }}%</p>
            </div>
        </div>

        {{-- Weights Display (Simplified) --}}
        <div class="p-6">
            <h3 class="text-xs font-bold text-gray-400 uppercase mb-4 flex items-center gap-2">
                <i class="fa-solid fa-list-ol"></i> Relevé des Poids
            </h3>
            
            <div class="grid grid-cols-2 sm:grid-cols-5 md:grid-cols-10 gap-2">
                @foreach($purchaseInvoice->weights->sortBy('position') as $w)
                <div class="p-2 border border-gray-100 rounded bg-gray-50/50 text-center relative overflow-hidden">
                    <div class="text-[8px] text-gray-300 absolute top-0.5 left-1">{{ str_pad($w->position, 3, '0', STR_PAD_LEFT) }}</div>
                    <div class="text-sm font-black {{ $w->calibre === 'GF' ? 'text-amber-600' : 'text-indigo-600' }}">{{ number_format($w->weight, 1) }}</div>
                    <div class="text-[7px] font-bold uppercase {{ $w->calibre === 'GF' ? 'text-amber-400' : 'text-indigo-300' }}">{{ $w->calibre }}</div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Financial Breakdown --}}
        <div class="grid grid-cols-1 md:grid-cols-2 border-t border-gray-200">
            <div class="p-6 space-y-3 bg-gray-50/50">
                <h3 class="text-xs font-bold text-gray-400 uppercase mb-4">Calcul du Montant</h3>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Poids Marchand PF ({{ number_format($purchaseInvoice->poids_marchand_pf, 2) }} kg)</span>
                    <span class="font-bold">{{ number_format($purchaseInvoice->poids_marchand_pf * $purchaseInvoice->pu_pf, 0, ',', ' ') }} F</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Poids Marchand GF ({{ number_format($purchaseInvoice->poids_marchand_gf, 2) }} kg)</span>
                    <span class="font-bold">{{ number_format($purchaseInvoice->poids_marchand_gf * $purchaseInvoice->pu_gf, 0, ',', ' ') }} F</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Prime Biologique Total</span>
                    <span class="font-bold text-indigo-600">+ {{ number_format($purchaseInvoice->montant_total_prime, 0, ',', ' ') }} F</span>
                </div>
                <div class="pt-2 border-t border-gray-200 flex justify-between font-black text-gray-900">
                    <span>Sous-total Brut</span>
                    <span>{{ number_format($purchaseInvoice->montant_total + $purchaseInvoice->montant_total_prime, 0, ',', ' ') }} F</span>
                </div>
            </div>
            
            <div class="p-6 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex justify-between text-sm text-red-600 font-bold">
                        <span>Retrait Crédit</span>
                        <span>- {{ number_format($purchaseInvoice->total_credit, 0, ',', ' ') }} F</span>
                    </div>
                    <div class="mt-4 p-4 bg-indigo-600 rounded-lg text-white flex justify-between items-center shadow-lg">
                        <span class="text-xs font-bold uppercase tracking-widest opacity-80">Net Payé</span>
                        <span class="text-2xl font-black">{{ number_format($purchaseInvoice->net_a_payer, 0, ',', ' ') }} <small class="text-sm font-normal">FCFA</small></span>
                    </div>
                </div>
                <div class="mt-4 p-3 bg-gray-50 border border-gray-100 rounded italic text-[11px] text-gray-600">
                    <span class="font-bold text-gray-400 not-italic uppercase block mb-1 text-[9px]">Montant en lettres</span>
                    {{ $purchaseInvoice->net_payer_lettre ?: '—' }}
                </div>
            </div>
        </div>

        {{-- Signatures --}}
        <div class="grid grid-cols-1 md:grid-cols-2 border-t border-gray-200 divide-y md:divide-y-0 md:divide-x divide-gray-100 h-48 bg-white">
            <div class="p-6 flex flex-col items-center justify-center">
                <p class="text-[9px] font-bold text-gray-400 uppercase mb-2">Responsable Bio Farm</p>
                @if($purchaseInvoice->signature_resp)
                    <img src="{{ $purchaseInvoice->signature_resp }}" class="h-24 object-contain mix-blend-multiply">
                @else
                    <span class="text-xs text-gray-300 italic">Signature manquante</span>
                @endif
            </div>
            <div class="p-6 flex flex-col items-center justify-center">
                <p class="text-[9px] font-bold text-gray-400 uppercase mb-2">Le Producteur / Livreur</p>
                @if($purchaseInvoice->signature_prod)
                    <img src="{{ $purchaseInvoice->signature_prod }}" class="h-24 object-contain mix-blend-multiply">
                @else
                    <span class="text-xs text-gray-300 italic">Signature manquante</span>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
