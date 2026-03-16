@extends('layouts.app')

@push('styles')
<style>
    .show-field-box {
        border-bottom: 1.5px solid #e2e8f0;
        padding-bottom: 4px;
    }
    
    .mini-table-header {
        background-color: #3b82f6;
        color: white;
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
        padding: 4px 0;
        text-align: center;
    }

    .mini-table-row {
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
    }

    .mini-table-index {
        width: 35%;
        background-color: #f8fafc;
        border-right: 1px solid #f1f5f9;
        font-size: 10px;
        font-weight: 700;
        color: #94a3b8;
        padding: 4px;
        text-align: center;
    }

    .mini-table-value {
        width: 65%;
        padding: 4px;
        text-align: center;
        font-weight: 800;
        font-size: 13px;
        color: #1e293b;
    }

    .accordion-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 16px;
        background: white;
        border: 1px solid #f1f5f9;
        border-left: 4px solid #3b82f6;
    }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    
    {{-- TOP BAR --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('purchase_invoices.index') }}" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-indigo-600 transition shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="text-lg font-black text-slate-800 uppercase tracking-tight">Consultation Facture</h1>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('purchase_invoices.pdf', $purchaseInvoice) }}" target="_blank" class="inline-flex items-center gap-2 px-6 py-2 bg-red-600 rounded-lg text-xs font-black text-white hover:bg-red-700 transition shadow-sm">
                <i class="fa-solid fa-file-pdf"></i> Imprimer PDF
            </a>
        </div>
    </div>

    {{-- MAIN CARD --}}
    <div class="bg-white shadow-xl rounded-lg border border-slate-100 overflow-hidden">
        
        {{-- HEADER --}}
        <div class="p-6 md:p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 border-b border-slate-50">
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="h-16 w-auto object-contain">
                <div>
                    <h1 class="text-xl font-black text-slate-800 tracking-tighter uppercase">Bio Farm Trading</h1>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest italic">Production - Commercialisation</p>
                </div>
            </div>
            
            <div class="flex items-center gap-8">
                <div class="text-right">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Bon N°</p>
                    <div class="text-lg font-black text-slate-900 border-b-2 border-slate-200 px-2 italic">{{ $purchaseInvoice->bon_no }}</div>
                </div>
                <div>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Date d'opération</p>
                    <div class="text-sm font-black text-slate-700 bg-slate-50 px-4 py-1.5 rounded border border-slate-200">
                        {{ $purchaseInvoice->date_invoice->format('d/m/Y') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- INFO GRID --}}
        <div class="px-6 md:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 bg-slate-50/30 p-6 rounded-xl border border-slate-100 mb-8">
                <div class="show-field-box">
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Zone</p>
                    <p class="text-sm font-bold text-slate-800">{{ $purchaseInvoice->zone ?: '—' }}</p>
                </div>
                <div class="show-field-box">
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Producteur</p>
                    <p class="text-sm font-bold text-slate-800">{{ $purchaseInvoice->producteur ?: '—' }}</p>
                </div>
                <div class="show-field-box">
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Chauffeur</p>
                    <p class="text-sm font-bold text-slate-800">{{ $purchaseInvoice->chauffeur ?: '—' }}</p>
                </div>
                <div class="show-field-box">
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Fruit</p>
                    <p class="text-sm font-bold text-slate-800">{{ $purchaseInvoice->fruit ?: '—' }}</p>
                </div>
                <div class="show-field-box">
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Matricule</p>
                    <p class="text-sm font-bold text-slate-800 uppercase">{{ $purchaseInvoice->code_parcelle_matricule ?: '—' }}</p>
                </div>
                <div class="show-field-box">
                    <p class="text-[8px] font-black text-orange-400 uppercase tracking-[0.2em] mb-1">Taux d'avarie</p>
                    <p class="text-sm font-black text-orange-600">{{ number_format($purchaseInvoice->avarie_pct, 2) }} %</p>
                </div>
            </div>

            {{-- WEIGHTS SECTION --}}
            <div class="bg-indigo-900 text-white py-2 text-center text-[10px] font-black uppercase tracking-[0.8em] mb-6">
                Relevé de Poids
            </div>

            @php 
                $allWeights = $purchaseInvoice->weights->sortBy('position')->values();
                $count = $allWeights->count();
                $groups = [
                    ['id' => 1, 'start' => 1, 'end' => 50],
                    ['id' => 2, 'start' => 51, 'end' => 100],
                    ['id' => 3, 'start' => 101, 'end' => 150],
                    ['id' => 4, 'start' => 151, 'end' => 200]
                ];
            @endphp

            <div class="space-y-4">
                @foreach($groups as $g)
                <div class="border border-slate-100 rounded-lg overflow-hidden">
                    <div class="accordion-header">
                        <div class="flex items-center gap-4">
                            <div class="w-6 h-6 rounded-full bg-blue-600 flex items-center justify-center text-white text-[10px] font-black">{{ $g['id'] }}</div>
                            <span class="text-xs font-black text-slate-800 uppercase tracking-widest">Groupe {{ $g['id'] }} ({{ $g['start'] }}-{{ $g['end'] }})</span>
                        </div>
                        <div class="flex items-center gap-6">
                            @php 
                                $groupWeights = $allWeights->whereBetween('position', [$g['start'], $g['end']]);
                                $filled = $groupWeights->filter(fn($w) => $w->weight > 0)->count();
                                $total = $groupWeights->sum('weight');
                            @endphp
                            <span class="text-[10px] font-bold text-slate-400">{{ $filled }} / 50 cases</span>
                            <span class="text-[11px] font-black text-blue-600">{{ number_format($total, 2) }} kg</span>
                        </div>
                    </div>

                    <div class="p-4 bg-slate-50/50">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @for($col = 0; $col < 3; $col++)
                            @php 
                                $startIndex = ($g['id']-1)*50 + ($col*17);
                                $itemsInCol = ($col == 2 ? 16 : 17);
                            @endphp
                            <div class="bg-white rounded border border-slate-200 overflow-hidden shadow-sm">
                                <div class="mini-table-header flex">
                                    <div class="w-[35%] border-r border-white/20">N°</div>
                                    <div class="w-[65%]">Poids (kg)</div>
                                </div>
                                <div class="divide-y divide-slate-100">
                                    @for($row = 0; $row < $itemsInCol; $row++)
                                    @php 
                                        $p = $startIndex + $row + 1;
                                        $wItem = $allWeights->firstWhere('position', $p);
                                    @endphp
                                    @if($p <= $g['end'])
                                    <div class="mini-table-row">
                                        <div class="mini-table-index">{{ str_pad($p, 3, '0', STR_PAD_LEFT) }}</div>
                                        <div class="mini-table-value relative">
                                            @if($wItem && $wItem->weight > 0)
                                                <span class="{{ $wItem->calibre === 'GF' ? 'text-amber-600' : 'text-slate-800' }}">
                                                    {{ number_format($wItem->weight, 2) }}
                                                </span>
                                                <span class="text-[7px] font-black px-1 rounded border ml-1 {{ $wItem->calibre === 'PF' ? 'border-indigo-100 text-indigo-400' : 'border-amber-100 text-amber-500 bg-amber-50' }}">
                                                    {{ $wItem->calibre }}
                                                </span>
                                            @else
                                                <span class="text-slate-200">—</span>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                    @endfor
                                </div>
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- FINANCIALS --}}
            <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-10">
                {{-- Detail Table --}}
                <div class="bg-white border-t-2 border-slate-800 pt-6">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Décompte Financier</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-slate-100">
                            <span class="text-sm font-bold text-slate-500">Poids Marchand PF (Net)</span>
                            <span class="text-sm font-black text-indigo-600">{{ number_format($purchaseInvoice->poids_marchand_pf, 2) }} kg <small class="ml-1 opacity-50">à {{ number_format($purchaseInvoice->pu_pf) }} F</small></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-100">
                            <span class="text-sm font-bold text-slate-500">Poids Marchand GF (Net)</span>
                            <span class="text-sm font-black text-amber-600">{{ number_format($purchaseInvoice->poids_marchand_gf, 2) }} kg <small class="ml-1 opacity-50">à {{ number_format($purchaseInvoice->pu_gf) }} F</small></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-100">
                            <span class="text-sm font-bold text-slate-500">Prime Biologique Total</span>
                            <span class="text-sm font-black text-emerald-600">+ {{ number_format($purchaseInvoice->montant_total_prime, 0, ',', ' ') }} F <small class="ml-1 opacity-50">à {{ number_format($purchaseInvoice->prime_bio_kg) }} F/kg</small></span>
                        </div>
                    </div>
                </div>

                {{-- Total & Credit --}}
                <div class="bg-white border-t-2 border-slate-800 pt-6">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Synthèse & Paiement</h3>
                    <div class="flex justify-between items-baseline mb-6">
                        <span class="text-xs font-black text-red-600 uppercase tracking-wider italic">Retrait Crédit / Avance</span>
                        <span class="text-base font-black text-red-600">- {{ number_format($purchaseInvoice->total_credit, 0, ',', ' ') }} FCFA</span>
                    </div>
                    
                    <div class="bg-blue-600 rounded-lg p-6 text-white shadow-xl flex justify-between items-center ring-4 ring-blue-50">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-black uppercase tracking-[0.2em] opacity-80">Net à Payer</span>
                            <span class="text-3xl font-black tracking-tighter">{{ number_format($purchaseInvoice->net_a_payer, 0, ',', ' ') }} <small class="text-sm font-normal">FCFA</small></span>
                        </div>
                        <i class="fa-solid fa-receipt text-3xl opacity-20"></i>
                    </div>

                    <div class="mt-4 p-4 bg-slate-50 border border-slate-100 rounded text-xs italic text-slate-500">
                        <span class="font-black text-slate-300 uppercase not-italic block text-[8px] mb-1">Montant en toutes lettres</span>
                        {{ $purchaseInvoice->net_payer_lettre ?: '—' }}
                    </div>
                </div>
            </div>

            {{-- SIGNATURES --}}
            <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-10 pt-10 border-t border-slate-100">
                <div class="flex flex-col items-center">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">A2C SAM / Responsable</p>
                    <div class="h-32 w-full flex items-center justify-center bg-slate-50/50 rounded-lg border border-dashed border-slate-200 overflow-hidden p-2">
                        @if($purchaseInvoice->signature_resp)
                            <img src="{{ $purchaseInvoice->signature_resp }}" class="max-h-full mix-blend-multiply">
                        @else
                            <span class="text-[10px] text-slate-300 italic">Signature non enregistrée</span>
                        @endif
                    </div>
                    <p class="text-[8px] text-slate-300 font-bold mt-2 uppercase tracking-widest italic">Signature & Cachet Officiel</p>
                </div>
                <div class="flex flex-col items-center">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Le Producteur / Livreur</p>
                    <div class="h-32 w-full flex items-center justify-center bg-slate-50/50 rounded-lg border border-dashed border-slate-200 overflow-hidden p-2">
                        @if($purchaseInvoice->signature_prod)
                            <img src="{{ $purchaseInvoice->signature_prod }}" class="max-h-full mix-blend-multiply">
                        @else
                            <span class="text-[10px] text-slate-300 italic">Signature non enregistrée</span>
                        @endif
                    </div>
                    <p class="text-[8px] text-slate-300 font-bold mt-2 uppercase tracking-widest italic">Attestation de réception de fonds</p>
                </div>
            </div>
        </div>

        <div class="bg-slate-50 p-6 text-center border-t border-slate-100">
            <p class="text-[8px] text-slate-400 font-bold uppercase tracking-widest">Opération archivée le {{ $purchaseInvoice->created_at->format('d/m/Y à H:i') }} · Identifiant système #{{ $purchaseInvoice->id }}</p>
        </div>
    </div>
</div>
@endsection
