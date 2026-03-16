@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-100 py-5">

    <div class="max-w-6xl mx-auto px-4">

        {{-- TOP BAR --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-slate-800 flex items-center justify-center shadow">
                    <i class="fa-solid fa-file-invoice-dollar text-white text-sm"></i>
                </div>
                <div>
                    <h1 class="text-sm sm:text-base font-black text-slate-800 leading-none uppercase">Détails Facture</h1>
                    <p class="text-[10px] sm:text-xs text-slate-400">Réf : {{ $purchaseInvoice->bon_no }}</p>
                </div>
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <a href="{{ route('purchase_invoices.pdf', $purchaseInvoice) }}" class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2 bg-red-600 rounded-lg text-xs font-bold text-white hover:bg-red-700 transition shadow-sm">
                    <i class="fa-solid fa-file-pdf mr-1.5"></i>PDF
                </a>
                <a href="{{ route('purchase_invoices.index') }}" class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2 bg-white border border-slate-200 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50 transition shadow-sm">
                    <i class="fa-solid fa-arrow-left mr-1.5"></i>Retour
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden" style="font-family:'Courier New',monospace">
            
            {{-- HEADER DOCUMENT --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-4 sm:px-5 py-4 border-b-2 border-slate-800 bg-slate-50">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="w-12 h-12 flex-shrink-0 sm:w-14 sm:h-14 rounded-full border-2 border-slate-300 overflow-hidden bg-white flex items-center justify-center">
                        <img src="{{ asset('images/logo.jpg') }}" class="w-10 h-10 sm:w-12 sm:h-12 object-contain" onerror="this.style.display='none'">
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs sm:text-lg font-black tracking-widest text-slate-900 uppercase truncate">BIO FARM TRADING</p>
                        <p class="text-[8px] sm:text-[10px] text-slate-500 uppercase font-bold leading-tight">Production & Commercialisation</p>
                    </div>
                </div>
                <div class="flex items-center justify-between md:justify-end gap-4 sm:gap-8 flex-shrink-0 border-t border-slate-200 md:border-0 pt-3 md:pt-0">
                    <div class="text-left md:text-right">
                        <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400">BON N°</p>
                        <p class="text-xs sm:text-xl font-black text-slate-900 leading-none">{{ $purchaseInvoice->bon_no }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Date</p>
                        <p class="text-xs sm:text-base font-black text-slate-900 leading-none">{{ $purchaseInvoice->date_invoice->format('d/m/Y') }}</p>
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
                    <label class="bg-slate-50 px-4 py-3 text-[10px] font-bold uppercase text-slate-500 w-44 border-r border-slate-100 leading-tight">MATRICULE</label>
                    <div class="flex-1 px-4 py-3 text-sm font-black text-slate-800 uppercase">{{ $purchaseInvoice->code_parcelle_matricule ?? '—' }}</div>
                </div>
                <div class="flex bg-white">
                    <label class="bg-slate-50 px-4 py-3 text-[10px] font-bold uppercase text-slate-500 w-32 border-r border-slate-100">FRUIT</label>
                    <div class="flex-1 px-4 py-3 text-sm font-bold text-slate-800 italic">{{ $purchaseInvoice->fruit ?? '—' }}</div>
                </div>
                <div class="flex bg-white border-t border-orange-100">
                    <label class="bg-orange-50 px-4 py-3 text-[10px] font-bold uppercase text-orange-400 w-32 border-r border-orange-100">% AVARIE</label>
                    <div class="flex-1 px-4 py-3 text-sm font-black text-orange-600 italic">{{ number_format($purchaseInvoice->avarie_pct ?? 0, 2, ',', ' ') }} %</div>
                </div>
                <div class="flex bg-white border-t border-slate-100">
                    <label class="bg-slate-50 px-4 py-3 text-[10px] font-bold uppercase text-slate-400 w-32 border-r border-slate-100">POIDS MARCHAND</label>
                    <div class="flex-1 px-4 py-3 text-sm font-black text-slate-700 italic">{{ number_format($purchaseInvoice->poids_marchand_total, 2, ',', ' ') }} kg</div>
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
                $rowsPerCol = (int) ceil($count / $numCols);
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
                                @php 
                                    $idx = (int) ($i + ($c * $rowsPerCol)); 
                                    $weightItem = $allWeights->get($idx);
                                @endphp
                                <td class="py-1 text-center font-black text-indigo-600 bg-slate-50 border-r border-slate-100">
                                    {{ $weightItem ? str_pad($weightItem->position, 3, '0', STR_PAD_LEFT) : '—' }}
                                </td>
                                 <td class="py-1 text-center font-bold border-r border-slate-100">
                                     @if($weightItem)
                                        <span class="{{ $weightItem->calibre === 'GF' ? 'text-orange-700' : 'text-slate-800' }}">
                                            {{ number_format($weightItem->weight, 2, ',', ' ') }}
                                        </span>
                                        <span class="text-[8px] font-black {{ $weightItem->calibre === 'GF' ? 'text-orange-500' : 'text-indigo-400' }}">
                                            [{{ $weightItem->calibre }}]
                                        </span>
                                     @else
                                        <span class="text-slate-200">—</span>
                                     @endif
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
                                        $colWeights = $allWeights->slice((int)($c * $rowsPerCol), (int)$rowsPerCol);
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
                    <div class="flex items-center border-t border-slate-50">
                        <label class="bg-slate-50/50 px-4 py-2 text-[9px] font-bold uppercase text-slate-400 w-48 border-r border-slate-100 italic">Dont Petit Fruit (PF)</label>
                        <div class="flex-1 px-4 text-right font-bold text-indigo-600 text-xs">{{ number_format($purchaseInvoice->weights->where('calibre', 'PF')->sum('weight'), 2, ',', ' ') }} kg</div>
                    </div>
                    <div class="flex items-center border-t border-slate-50">
                        <label class="bg-slate-50/50 px-4 py-2 text-[9px] font-bold uppercase text-slate-400 w-48 border-r border-slate-100 italic">Dont Gros Fruit (GF)</label>
                        <div class="flex-1 px-4 text-right font-bold text-orange-600 text-xs">{{ number_format($purchaseInvoice->weights->where('calibre', 'GF')->sum('weight'), 2, ',', ' ') }} kg</div>
                    </div>
                    <div class="flex items-center">
                        <label class="bg-indigo-50/50 px-4 py-2.5 text-[10px] font-bold uppercase text-indigo-500 w-48 border-r border-indigo-100">P.U PF</label>
                        <div class="flex-1 px-4 text-right font-black text-indigo-700">{{ number_format($purchaseInvoice->pu_pf ?? 0, 0, ',', ' ') }} FCFA/kg</div>
                    </div>
                    <div class="flex items-center">
                        <label class="bg-amber-50/50 px-4 py-2.5 text-[10px] font-bold uppercase text-amber-500 w-48 border-r border-amber-100">P.U GF</label>
                        <div class="flex-1 px-4 text-right font-black text-amber-700">{{ number_format($purchaseInvoice->pu_gf ?? 0, 0, ',', ' ') }} FCFA/kg</div>
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
                        <label class="bg-indigo-50/30 px-4 py-3 text-[10px] font-bold uppercase text-indigo-400 w-48 border-r border-indigo-50 leading-tight">Poids marchand petit fruit</label>
                        <div class="flex-1 px-4 text-right font-bold text-indigo-600">{{ number_format($purchaseInvoice->poids_marchand_pf, 2, ',', ' ') }} kg</div>
                    </div>
                    <div class="flex items-center">
                        <label class="bg-amber-50/30 px-4 py-3 text-[10px] font-bold uppercase text-amber-400 w-48 border-r border-amber-50 leading-tight">Poids marchand gros fruit</label>
                        <div class="flex-1 px-4 text-right font-bold text-amber-600">{{ number_format($purchaseInvoice->poids_marchand_gf, 2, ',', ' ') }} kg</div>
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
            <div class="border-t border-slate-200 grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-slate-200 bg-slate-50">
                <div class="px-5 py-4 flex flex-col items-center">
                    <p class="text-[9px] font-bold uppercase text-slate-500 mb-2 w-full">Responsable Bio Farm</p>
                    @if($purchaseInvoice->signature_resp)
                        <img src="{{ $purchaseInvoice->signature_resp }}" class="h-24 object-contain mix-blend-multiply">
                    @else
                        <div class="h-24 flex items-center justify-center text-[10px] text-slate-300 italic">Pas de signature</div>
                    @endif
                    <div class="w-full border-b border-dotted border-slate-300 mb-1 mt-2"></div>
                    <p class="text-[8px] text-slate-400 text-center uppercase font-bold">Cachet & Signature</p>
                </div>
                <div class="px-5 py-4 flex flex-col items-center">
                    <p class="text-[9px] font-bold uppercase text-slate-500 mb-2 w-full text-right">Le Producteur / Opérateur</p>
                    @if($purchaseInvoice->signature_prod)
                        <img src="{{ $purchaseInvoice->signature_prod }}" class="h-24 object-contain mix-blend-multiply">
                    @else
                        <div class="h-24 flex items-center justify-center text-[10px] text-slate-300 italic">Pas de signature</div>
                    @endif
                    <div class="w-full border-b border-dotted border-slate-300 mb-1 mt-2"></div>
                    <p class="text-[8px] text-slate-400 text-center uppercase font-bold">Signature</p>
                </div>
            </div>

        </div>

        <div class="mt-8 text-center">
            <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">Généré le {{ now()->format('d/m/Y à H:i') }} par {{ Auth::user()->name }}</p>
        </div>

    </div>
</div>
@endsection
