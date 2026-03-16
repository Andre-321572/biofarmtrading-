@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-8 lg:py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        
        {{-- TOP NAVIGATION & ACTIONS --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-8">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-100 ring-4 ring-indigo-50">
                    <i class="fa-solid fa-file-invoice-dollar text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-black text-slate-900 tracking-tight">Détails de la Facture</h1>
                    <p class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em] flex items-center gap-2">
                        <i class="fa-solid fa-hashtag text-[8px]"></i>
                        Réf: {{ $purchaseInvoice->bon_no }}
                    </p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('purchase_invoices.pdf', $purchaseInvoice) }}" 
                   target="_blank"
                   class="inline-flex items-center gap-2 px-6 py-2.5 bg-rose-600 rounded-xl text-xs font-black text-white hover:bg-rose-700 transition shadow-lg shadow-rose-100">
                    <i class="fa-solid fa-file-pdf"></i>
                    Générer PDF
                </a>
                <a href="{{ route('purchase_invoices.index') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-black text-slate-600 hover:bg-slate-50 transition shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                    Retour au registre
                </a>
            </div>
        </div>

        {{-- MAIN DOCUMENT CARD --}}
        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/60 border border-slate-200 overflow-hidden">
            
            {{-- HEADER VISUEL --}}
            <div class="flex flex-col lg:flex-row justify-between gap-6 px-6 sm:px-10 py-8 bg-slate-50/50 border-b border-slate-100">
                {{-- Logo & Brand --}}
                <div class="flex items-center gap-5">
                    <div class="w-20 h-20 rounded-3xl bg-white shadow-xl shadow-slate-200/50 flex items-center justify-center p-2 border border-slate-100 ring-8 ring-slate-50">
                        <img src="{{ asset('images/logo.jpg') }}" class="w-full h-full object-contain" alt="Logo">
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 tracking-[0.15em] uppercase leading-none mb-1">Bio Farm Trading</h2>
                        <p class="text-[10px] sm:text-xs font-black text-indigo-600/70 uppercase tracking-[0.2em] mb-3">Service Production & Qualité</p>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-2 py-0.5 rounded-full bg-indigo-100 text-[10px] font-black text-indigo-700 uppercase tracking-tighter">Bio Certifié</span>
                            <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-[10px] font-black text-emerald-700 uppercase tracking-tighter">Opération Approuvée</span>
                        </div>
                    </div>
                </div>

                {{-- Bon info --}}
                <div class="flex flex-col sm:flex-row lg:flex-col justify-between items-start sm:items-center lg:items-end gap-3 sm:gap-10">
                    <div class="text-left lg:text-right">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Numéro de Bon</p>
                        <div class="inline-flex items-center gap-2 bg-indigo-600 px-4 py-1.5 rounded-xl shadow-lg shadow-indigo-100">
                            <i class="fa-solid fa-hashtag text-indigo-200 text-xs"></i>
                            <span class="text-lg font-black text-white italic tracking-widest">{{ $purchaseInvoice->bon_no }}</span>
                        </div>
                    </div>
                    <div class="text-left lg:text-right">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Date d'opération</p>
                        <p class="text-base font-black text-slate-800 leading-none">
                            <i class="fa-regular fa-calendar-check mr-2 text-indigo-500"></i>
                            {{ $purchaseInvoice->date_invoice->format('d F Y') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- INFOS SECTION --}}
            <div class="p-6 sm:p-10">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 bg-slate-50 p-6 rounded-[2rem] border border-slate-100">
                    {{-- Zone --}}
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 px-1">Zone de provenance</p>
                        <div class="bg-white px-4 py-3 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-3">
                            <i class="fa-solid fa-location-dot text-indigo-500"></i>
                            <span class="text-sm font-black text-slate-800">{{ $purchaseInvoice->zone ?: '—' }}</span>
                        </div>
                    </div>

                    {{-- Producteur --}}
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 px-1">Producteur / O.P</p>
                        <div class="bg-white px-4 py-3 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-3">
                            <i class="fa-solid fa-user-tie text-indigo-500"></i>
                            <span class="text-sm font-black text-slate-800">{{ $purchaseInvoice->producteur ?: '—' }}</span>
                        </div>
                    </div>

                    {{-- Chauffeur --}}
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 px-1">Chauffeur</p>
                        <div class="bg-white px-4 py-3 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-3">
                            <i class="fa-solid fa-truck-fast text-indigo-500"></i>
                            <span class="text-sm font-black text-slate-800">{{ $purchaseInvoice->chauffeur ?: '—' }}</span>
                        </div>
                    </div>

                    {{-- Matricule --}}
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 px-1">Matricule Véhicule</p>
                        <div class="bg-white px-4 py-3 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-3">
                            <i class="fa-solid fa-id-card text-indigo-500"></i>
                            <span class="text-sm font-black text-slate-800 uppercase tracking-widest">{{ $purchaseInvoice->code_parcelle_matricule ?: '—' }}</span>
                        </div>
                    </div>

                    {{-- Fruit --}}
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 px-1">Type de Fruit</p>
                        <div class="bg-white px-4 py-3 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-3">
                            <i class="fa-solid fa-leaf text-indigo-500"></i>
                            <span class="text-sm font-black text-slate-800">{{ $purchaseInvoice->fruit ?: '—' }}</span>
                        </div>
                    </div>

                    {{-- Avarie --}}
                    <div>
                        <p class="text-[9px] font-black text-orange-400 uppercase tracking-[0.2em] mb-2 px-1">Taux d'avarie enregistré</p>
                        <div class="bg-white px-4 py-3 rounded-2xl border border-orange-100 shadow-sm flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-shield-virus text-orange-500"></i>
                                <span class="text-sm font-black text-orange-600">{{ number_format($purchaseInvoice->avarie_pct, 2) }} %</span>
                            </div>
                            <span class="text-[9px] font-black text-orange-300 uppercase italic">Déduit du brut</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RELEVÉ DE POIDS --}}
            <div class="relative">
                <div class="bg-indigo-950 text-white py-3 flex items-center justify-center gap-10">
                    <div class="h-px w-20 bg-indigo-500/30"></div>
                    <h4 class="text-xs font-black uppercase tracking-[1em] italic">Relevé des 200 Poids</h4>
                    <div class="h-px w-20 bg-indigo-500/30"></div>
                </div>
            </div>

            {{-- GRILLES DES POIDS --}}
            @php 
                $allWeights = $purchaseInvoice->weights->sortBy('position')->values();
                $count = $allWeights->count();
                $numCols = 5;
                $rowsPerCol = (int) ceil($count / $numCols);
                if ($rowsPerCol < 10) $rowsPerCol = 10; 
            @endphp

            <div class="p-6 sm:p-10 bg-slate-50/50">
                <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                    @for($c = 0; $c < $numCols; $c++)
                    <div class="bg-white rounded-[1.5rem] border border-slate-200 shadow-sm overflow-hidden">
                        <div class="bg-slate-100 py-2.5 text-center border-b border-slate-200">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">Bloc {{ $c+1 }}</span>
                        </div>
                        <div class="divide-y divide-slate-100">
                            @for($r = 0; $r < $rowsPerCol; $r++)
                            @php 
                                $idx = (int) ($r + ($c * $rowsPerCol)); 
                                $weightItem = $allWeights->get($idx);
                            @endphp
                            <div class="flex items-center gap-4 px-4 py-2 hover:bg-slate-50 transition-colors">
                                <span class="text-[10px] font-black text-slate-300 w-6">{{ $weightItem ? str_pad($weightItem->position, 3, '0', STR_PAD_LEFT) : '—' }}</span>
                                <div class="flex-1 flex items-center justify-between">
                                    <span class="text-sm font-black {{ $weightItem ? 'text-slate-800' : 'text-slate-200 font-normal italic' }}">
                                        {{ $weightItem ? number_format($weightItem->weight, 1) : '0.0' }} <span class="text-[8px] opacity-40">kg</span>
                                    </span>
                                    @if($weightItem)
                                    <span class="px-1.5 py-0.5 rounded-md text-[8px] font-black uppercase {{ $weightItem->calibre === 'GF' ? 'bg-orange-100 text-orange-600' : 'bg-indigo-100 text-indigo-600' }}">
                                        {{ $weightItem->calibre }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            @endfor
                        </div>
                        {{-- Sous-total bloc --}}
                        <div class="bg-slate-50 py-2 border-t border-slate-100 text-center">
                            @php $colSum = $allWeights->slice((int)($c * $rowsPerCol), (int)$rowsPerCol)->sum('weight'); @endphp
                            <span class="text-[9px] font-black text-slate-400 uppercase">Total: <span class="text-slate-800">{{ number_format($colSum, 1) }} kg</span></span>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>

            {{-- PANNEAU DES TOTAUX --}}
            <div class="p-6 sm:p-10 bg-slate-100 border-t-4 border-slate-900">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    
                    {{-- Colonne GAUCHE - Détails Calculs --}}
                    <div class="space-y-4">
                        <h5 class="text-sm font-black text-slate-900 uppercase tracking-widest flex items-center justify-between">
                            <span>Détails du Décompte</span>
                            <i class="fa-solid fa-calculator text-indigo-300"></i>
                        </h5>
                        
                        <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-200">
                            <div class="space-y-4 divide-y divide-slate-100">
                                {{-- PF Section --}}
                                <div class="pb-3 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center">
                                            <span class="text-[10px] font-black text-indigo-600">PF</span>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-wide">Petit Fruit</p>
                                            <p class="text-sm font-black text-indigo-700">{{ number_format($purchaseInvoice->poids_marchand_pf, 2) }} kg <span class="text-[9px] text-slate-300 font-normal ml-1">à {{ number_format($purchaseInvoice->pu_pf) }} F</span></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-sm font-black text-slate-900 tracking-tighter">{{ number_format($purchaseInvoice->poids_marchand_pf * $purchaseInvoice->pu_pf, 0, ',', ' ') }} <span class="text-[9px]">F</span></span>
                                    </div>
                                </div>

                                {{-- GF Section --}}
                                <div class="py-3 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center">
                                            <span class="text-[10px] font-black text-orange-600">GF</span>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-wide">Grand Fruit</p>
                                            <p class="text-sm font-black text-orange-700">{{ number_format($purchaseInvoice->poids_marchand_gf, 2) }} kg <span class="text-[9px] text-slate-300 font-normal ml-1">à {{ number_format($purchaseInvoice->pu_gf) }} F</span></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-sm font-black text-slate-900 tracking-tighter">{{ number_format($purchaseInvoice->poids_marchand_gf * $purchaseInvoice->pu_gf, 0, ',', ' ') }} <span class="text-[9px]">F</span></span>
                                    </div>
                                </div>

                                {{-- Prime Bio --}}
                                <div class="py-3 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                                            <i class="fa-solid fa-medal text-emerald-500 text-[10px]"></i>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black text-emerald-500 uppercase tracking-wide">Prime Biologique</p>
                                            <p class="text-sm font-black text-emerald-700">{{ number_format($purchaseInvoice->total_weight, 2) }} kg <span class="text-[9px] text-emerald-300 font-normal ml-1">à {{ number_format($purchaseInvoice->prime_bio_kg) }} F</span></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-sm font-black text-emerald-600 tracking-tighter">{{ number_format($purchaseInvoice->montant_total_prime, 0, ',', ' ') }} <span class="text-[9px]">F</span></span>
                                    </div>
                                </div>

                                {{-- Montant Total --}}
                                <div class="pt-3 flex items-center justify-between">
                                    <span class="text-xs font-black text-slate-400 uppercase italic">Valeur Brute Totale</span>
                                    <span class="text-base font-black text-slate-900 tracking-tighter">{{ number_format($purchaseInvoice->montant_total + $purchaseInvoice->montant_total_prime, 0, ',', ' ') }} <span class="text-xs">FCFA</span></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Colonne DROITE - Crédits & NET --}}
                    <div class="space-y-4">
                        <h5 class="text-sm font-black text-slate-900 uppercase tracking-widest flex items-center justify-between">
                            <span>Bilan de Paiement</span>
                            <i class="fa-solid fa-receipt text-indigo-300"></i>
                        </h5>

                        <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-200 h-full flex flex-col justify-between">
                            <div class="space-y-5">
                                {{-- Crédit --}}
                                <div class="flex items-center justify-between group">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-minus-circle text-red-400 group-hover:scale-110 transition-transform"></i>
                                        <span class="text-xs font-black text-red-700 uppercase italic">Retrait Crédit</span>
                                    </div>
                                    <span class="text-base font-black text-red-600 tracking-tighter">- {{ number_format($purchaseInvoice->total_credit, 0, ',', ' ') }} <span class="text-xs font-normal">FCFA</span></span>
                                </div>

                                <div class="h-px bg-slate-100"></div>

                                {{-- NET À PAYER --}}
                                <div class="py-5 px-6 bg-indigo-600 rounded-[2rem] shadow-xl shadow-indigo-100 flex items-center justify-between ring-8 ring-indigo-50">
                                    <span class="text-sm font-black text-white uppercase tracking-widest italic">Net Payé</span>
                                    <span class="text-3xl font-black text-white tracking-tighter">
                                        {{ number_format($purchaseInvoice->net_a_payer, 0, ',', ' ') }} <span class="text-sm font-normal opacity-70">FCFA</span>
                                    </span>
                                </div>

                                {{-- En Lettres --}}
                                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 relative overflow-hidden mt-6">
                                    <i class="fa-solid fa-quote-left absolute top-2 left-2 text-slate-200/50 text-2xl"></i>
                                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-wider mb-2 relative z-10">Montant acquitté en toutes lettres</p>
                                    <p class="text-xs font-black italic text-slate-600 leading-relaxed capitalize relative z-10">
                                        {{ $purchaseInvoice->net_payer_lettre ?: '—' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION SIGNATURES --}}
            <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-slate-100 bg-white border-t border-slate-100">
                {{-- Signature Responsable --}}
                <div class="p-8 group">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 text-center">Responsable Bio Farm Trading</p>
                    <div class="relative h-40 rounded-[1.5rem] bg-slate-50/50 flex items-center justify-center p-4 border border-dashed border-slate-200 overflow-hidden group-hover:border-indigo-300 transition-colors">
                        @if($purchaseInvoice->signature_resp)
                            <img src="{{ $purchaseInvoice->signature_resp }}" class="max-w-full max-h-full object-contain mix-blend-multiply relative z-10" alt="Signature Resp">
                            <i class="fa-solid fa-stamp absolute top-4 right-4 text-indigo-100 text-3xl opacity-20 group-hover:opacity-40 transition-opacity"></i>
                        @else
                            <div class="flex flex-col items-center text-slate-300">
                                <i class="fa-solid fa-signature text-3xl mb-2 opacity-20"></i>
                                <span class="text-[10px] uppercase font-black italic">Signature absente</span>
                            </div>
                        @endif
                    </div>
                    <p class="text-[8px] text-slate-400 text-center mt-3 font-bold italic tracking-wider uppercase">Validation & Approbation</p>
                </div>

                {{-- Signature Producteur --}}
                <div class="p-8 group shadow-inner">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 text-center">Le Producteur / Livreur</p>
                    <div class="relative h-40 rounded-[1.5rem] bg-slate-50/50 flex items-center justify-center p-4 border border-dashed border-slate-200 overflow-hidden group-hover:border-amber-300 transition-colors">
                        @if($purchaseInvoice->signature_prod)
                            <img src="{{ $purchaseInvoice->signature_prod }}" class="max-w-full max-h-full object-contain mix-blend-multiply relative z-10" alt="Signature Prod">
                            <i class="fa-solid fa-award absolute top-4 right-4 text-amber-100 text-3xl opacity-20 group-hover:opacity-40 transition-opacity"></i>
                        @else
                            <div class="flex flex-col items-center text-slate-300">
                                <i class="fa-solid fa-user-check text-3xl mb-2 opacity-20"></i>
                                <span class="text-[10px] uppercase font-black italic">Signature absente</span>
                            </div>
                        @endif
                    </div>
                    <p class="text-[8px] text-slate-400 text-center mt-3 font-bold italic tracking-wider uppercase">Reconnaissance de réception de fonds</p>
                </div>
            </div>
        </div>

        {{-- AUDIT FOOTER --}}
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3 text-slate-400">
                <i class="fa-solid fa-circle-info italic"></i>
                <p class="text-[10px] font-black uppercase tracking-widest italic">Document généré par {{ Auth::user()->name }} · ID Opération #{{ $purchaseInvoice->id }}</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] italic">Statut: Enregistré au grand livre</span>
            </div>
        </div>
    </div>
</div>
@endsection
