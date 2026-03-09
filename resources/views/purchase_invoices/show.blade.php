@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-100 py-5">

    <div class="max-w-6xl mx-auto px-4">

        {{-- TOP BAR --}}
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-slate-800 flex items-center justify-center shadow">
                    <i class="fa-solid fa-file-invoice-dollar text-white text-sm"></i>
                </div>
                <div>
                    <h1 class="text-base font-black text-slate-800 leading-none">Détails de la Facture</h1>
                    <p class="text-xs text-slate-400">Réf : {{ $purchaseInvoice->bon_no }}</p>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('purchase_invoices.pdf', $purchaseInvoice) }}" class="px-3 py-1.5 bg-red-600 rounded-lg text-xs font-bold text-white hover:bg-red-700 shadow shadow-red-200 transition">
                    <i class="fa-solid fa-file-pdf mr-1.5"></i>PDF
                </a>
                <a href="{{ route('purchase_invoices.index') }}" class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50 shadow-sm transition">
                    <i class="fa-solid fa-arrow-left mr-1.5"></i>Retour
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden" style="font-family:'Courier New',monospace">
            
            {{-- HEADER DOCUMENT --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-5 py-4 border-b-2 border-slate-800 bg-slate-50">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full border-2 border-slate-300 overflow-hidden bg-white flex items-center justify-center">
                        <img src="{{ asset('images/logo.jpg') }}" class="w-12 h-12 object-contain" onerror="this.style.display='none'">
                    </div>
                    <div>
                        <p class="text-lg font-black tracking-widest text-slate-900 uppercase">SAM TOGO</p>
                        <p class="text-[10px] text-slate-500 uppercase font-bold">Bio Farm Trading - Production & Commercialisation</p>
                    </div>
                </div>
                <div class="flex items-center gap-8">
                    <div class="text-right">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">BON N°</p>
                        <p class="text-xl font-black text-slate-900">{{ $purchaseInvoice->bon_no }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Date</p>
                        <p class="text-base font-black text-slate-900">{{ $purchaseInvoice->date_invoice->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="text-center py-2 bg-slate-100 border-b border-slate-300">
                <h2 class="text-lg font-black tracking-[0.5em] text-slate-800 uppercase">Facture d'Achat</h2>
            </div>

            {{-- FORM GRID --}}
            <div class="grid grid-cols-1 md:grid-cols-2 bg-slate-200 gap-[1px]">
                <div class="flex bg-white">
                    <label class="bg-slate-50 px-4 py-3 text-[10px] font-bold uppercase text-slate-500 w-32 border-r border-slate-100">ZONE</label>
                    <div class="flex-1 px-4 py-3 text-sm font-bold text-slate-800 italic">{{ $purchaseInvoice->zone ?? '—' }}</div>
                </div>
                <div class="flex bg-white">
                    <label class="bg-slate-50 px-4 py-3 text-[10px] font-bold uppercase text-slate-500 w-32 border-r border-slate-100">PRODUCTEUR</label>
                    <div class="flex-1 px-4 py-3 text-sm font-bold text-slate-800 italic">{{ $purchaseInvoice->producteur ?? '—' }}</div>
                </div>
                <div class="flex bg-white">
                    <label class="bg-slate-50 px-4 py-3 text-[10px] font-bold uppercase text-slate-500 w-32 border-r border-slate-100">CHAUFFEUR</label>
                    <div class="flex-1 px-4 py-3 text-sm font-bold text-slate-800 italic">{{ $purchaseInvoice->chauffeur ?? '—' }}</div>
                </div>
                <div class="flex bg-white">
                    <label class="bg-slate-50 px-4 py-3 text-[10px] font-bold uppercase text-slate-500 w-44 border-r border-slate-100 leading-tight">CODE PARCELLE / MATRICULE</label>
                    <div class="flex-1 px-4 py-3 text-sm font-black text-slate-800 uppercase">{{ $purchaseInvoice->code_parcelle_matricule ?? '—' }}</div>
                </div>
                <div class="flex bg-white">
                    <label class="bg-slate-50 px-4 py-3 text-[10px] font-bold uppercase text-slate-500 w-32 border-r border-slate-100">FRUIT</label>
                    <div class="flex-1 px-4 py-3 text-sm font-bold text-slate-800 italic">{{ $purchaseInvoice->fruit ?? '—' }}</div>
                </div>
                <div class="flex bg-white">
                    <label class="bg-slate-50 px-4 py-3 text-[10px] font-bold uppercase text-slate-500 w-32 border-r border-slate-100">QTÉ ESTIMÉE</label>
                    <div class="flex-1 px-4 py-3 text-sm font-bold text-slate-800 italic text-right">{{ number_format($purchaseInvoice->quantite_estimee, 2, ',', ' ') }} kg</div>
                </div>
            </div>

            {{-- RELEVÉ DE POIDS --}}
            <div class="bg-slate-800 text-white text-center py-1 font-black tracking-[0.8em] uppercase text-xs">
                Relevé de Poids
            </div>

            @php 
                $allWeights = $purchaseInvoice->weights->sortBy('position')->values();
                $count = $allWeights->count();
                $numCols = 5;
                $rowsPerCol = ceil($count / $numCols);
                if ($rowsPerCol < 5) $rowsPerCol = 5; 
            @endphp

            <div class="overflow-x-auto bg-white p-2">
                <table class="w-full text-[10px] border-collapse">
                    <thead>
                        <tr class="bg-slate-700 text-white leading-none">
                            @for($c = 0; $c < $numCols; $c++)
                                <th class="py-1 border-r border-white/10 uppercase">N°</th>
                                <th class="py-1 border-r border-white/10 uppercase">Poids kg</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @for($i = 0; $i < $rowsPerCol; $i++)
                        <tr class="border-b border-slate-100 italic">
                            @for($c = 0; $c < $numCols; $c++)
                                @php $idx = $i + ($c * $rowsPerCol); @endphp
                                <td class="py-1 text-center font-black text-indigo-600 bg-slate-50 border-r border-slate-100">
                                    {{ isset($allWeights[$idx]) ? str_pad($allWeights[$idx]->position, 3, '0', STR_PAD_LEFT) : '—' }}
                                </td>
                                <td class="py-1 text-center font-bold text-slate-800 border-r border-slate-100">
                                    {{ isset($allWeights[$idx]) ? number_format($allWeights[$idx]->weight, 2, ',', ' ') : '—' }}
                                </td>
                            @endfor
                        </tr>
                        @endfor
                    </tbody>
                    <tfoot>
                        <tr class="bg-slate-50 font-black">
                            @for($c = 0; $c < $numCols; $c++)
                                <td class="py-1 text-center text-slate-400 border-r border-slate-200">T</td>
                                <td class="py-1 text-center text-slate-800 border-r border-slate-200">
                                    @php
                                        $colWeights = $allWeights->slice($c * $rowsPerCol, $rowsPerCol);
                                    @endphp
                                    {{ number_format($colWeights->sum('weight'), 2, ',', ' ') }}
                                </td>
                            @endfor
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- TOTALS SECTION --}}
            <div class="grid grid-cols-1 md:grid-cols-2 bg-slate-200 gap-[1px] border-t-2 border-slate-800">
                {{-- Left Column --}}
                <div class="flex flex-col divide-y divide-slate-100 bg-white">
                    <div class="flex items-center">
                        <label class="bg-slate-50 px-4 py-3 text-[10px] font-bold uppercase text-slate-500 w-48 border-r border-slate-100">POIDS TOTAL</label>
                        <div class="flex-1 px-4 text-right font-black text-slate-900">{{ number_format($purchaseInvoice->total_weight, 2, ',', ' ') }} kg</div>
                    </div>
                    <div class="flex items-center">
                        <label class="bg-slate-50 px-4 py-3 text-[10px] font-bold uppercase text-slate-500 w-48 border-r border-slate-100">P.U</label>
                        <div class="flex-1 px-4 text-right font-black text-slate-900">{{ number_format($purchaseInvoice->pu, 0, ',', ' ') }} FCFA/kg</div>
                    </div>
                    <div class="flex items-center">
                        <label class="bg-slate-50 px-4 py-3 text-[10px] font-bold uppercase text-slate-500 w-48 border-r border-slate-100">MONTANT TOTAL</label>
                        <div class="flex-1 px-4 text-right font-black text-green-700">{{ number_format($purchaseInvoice->montant_total, 0, ',', ' ') }} FCFA</div>
                    </div>
                    <div class="flex items-center bg-green-50">
                        <label class="bg-green-100/50 px-4 py-4 text-[10px] font-black uppercase text-green-800 w-48 border-r border-green-200">NET À PAYER</label>
                        <div class="flex-1 px-4 text-right font-black text-green-600 text-2xl">{{ number_format($purchaseInvoice->net_a_payer, 0, ',', ' ') }} FCFA</div>
                    </div>
                    <div class="flex items-center">
                        <label class="bg-slate-50 px-4 py-3 text-[10px] font-bold uppercase text-slate-500 w-48 border-r border-slate-100">PRIME BIO/KG</label>
                        <div class="flex-1 px-4 text-right font-bold text-indigo-600">{{ number_format($purchaseInvoice->prime_bio_kg, 0, ',', ' ') }} FCFA/kg</div>
                    </div>
                </div>

                {{-- Right Column (Credits) --}}
                <div class="flex flex-col divide-y divide-slate-100 bg-white">
                    <div class="flex items-center">
                        <label class="bg-slate-50 px-4 py-3 text-[10px] font-bold uppercase text-slate-500 w-48 border-r border-slate-100">Poids Avarié</label>
                        <div class="flex-1 px-4 text-right font-bold text-red-600">{{ number_format($purchaseInvoice->poids_avarie, 0, ',', ' ') }} FCFA</div>
                    </div>
                    <div class="flex items-center">
                        <label class="bg-slate-50 px-4 py-3 text-[10px] font-bold uppercase text-slate-500 w-48 border-r border-slate-100 leading-tight">Poids marchand (Poids net)</label>
                        <div class="flex-1 px-4 text-right font-bold text-red-600">{{ number_format($purchaseInvoice->poids_marchand, 0, ',', ' ') }} FCFA</div>
                    </div>
                    <div class="flex items-center">
                        <label class="bg-slate-50 px-4 py-3 text-[10px] font-bold uppercase text-slate-500 w-48 border-r border-slate-100">TOTAL CRÉDIT</label>
                        <div class="flex-1 px-4 text-right font-black text-red-600">{{ number_format($purchaseInvoice->total_credit, 0, ',', ' ') }} FCFA</div>
                    </div>
                    <div class="flex flex-col px-4 py-3 bg-slate-50 border-b border-slate-200 min-h-[60px]">
                        <label class="text-[9px] font-bold uppercase text-slate-400 mb-1">NET À PAYER EN LETTRE</label>
                        <p class="text-[10px] font-bold text-slate-600 italic">{{ $purchaseInvoice->net_payer_lettre ?? '—' }}</p>
                    </div>
                    <div class="flex items-center">
                        <label class="bg-slate-50 px-4 py-3 text-[10px] font-bold uppercase text-slate-500 w-48 border-r border-slate-100 leading-tight">MONTANT TOTAL DE LA PRIME</label>
                        <div class="flex-1 px-4 text-right font-black text-indigo-600">{{ number_format($purchaseInvoice->montant_total_prime, 0, ',', ' ') }} FCFA</div>
                    </div>
                </div>
            </div>

            {{-- SIGNATURES --}}
            <div class="border-t border-slate-200 grid grid-cols-2 divide-x divide-slate-200">
                <div class="px-5 py-4">
                    <p class="text-[9px] font-bold uppercase text-slate-500">A2C SAM / Responsable</p>
                    <div class="border-b border-dotted border-slate-300 mt-7 mb-1"></div>
                    <p class="text-[8px] text-slate-400 text-center uppercase">Signature & Cachet</p>
                </div>
                <div class="px-5 py-4 text-right">
                    <p class="text-[9px] font-bold uppercase text-slate-500">Le Producteur</p>
                    <div class="border-b border-dotted border-slate-300 mt-7 mb-1"></div>
                    <p class="text-[8px] text-slate-400 text-center uppercase">Signature</p>
                </div>
            </div>

        </div>

        <div class="mt-8 text-center">
            <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">Généré le {{ now()->format('d/m/Y à H:i') }} par {{ Auth::user()->name }}</p>
        </div>

    </div>
</div>
@endsection
