@extends('layouts.app')

@push('styles')
<style>
    .signature-pad {
        border: 1px solid #e2e8f0;
        background-color: #ffffff;
        width: 100%;
        height: 120px;
        touch-action: none;
        border-radius: 12px;
        box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.05);
    }
    .weight-input {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .weight-input:focus {
        background-color: #f8fafc !important;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
    }
    .glass-effect {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(8px);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-slate-50 py-8 lg:py-12" 
     x-data="purchaseInvoiceForm()" 
     x-init="initForm">
    
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        {{-- TOP NAVIGATION & TITLE --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-200 ring-4 ring-indigo-50">
                    <i class="fa-solid fa-file-invoice-dollar text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-black text-slate-900 tracking-tight">Facture d'Achat</h1>
                    <p class="text-xs font-semibold text-slate-500 flex items-center gap-1.5 uppercase tracking-wider">
                        <span class="inline-block w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                        Relevé de 200 poids · Gestion Coopérative
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('purchase_invoices.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                    Tableau de bord
                </a>
            </div>
        </div>

        {{-- ERRORS --}}
        @if($errors->any() || session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl px-5 py-4 flex items-start gap-4 animate-pulse">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-triangle-exclamation text-red-600"></i>
            </div>
            <div>
                <p class="text-sm font-black text-red-800 uppercase tracking-wide">Attention : Erreur(s)</p>
                @if(session('error'))
                    <p class="text-xs text-red-600 font-medium">{{ session('error') }}</p>
                @endif
                @foreach($errors->all() as $err)
                    <p class="text-xs text-red-600 font-medium">• {{ $err }}</p>
                @endforeach
            </div>
        </div>
        @endif

        {{-- FORMULAIRE --}}
        <form action="{{ route('purchase_invoices.store') }}" method="POST" id="mainForm" @submit.prevent="submitForm">
            @csrf

            {{-- DOCUMENT PRINCIPAL --}}
            <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/60 border border-slate-200 overflow-hidden">
                
                {{-- HEADER DU DOCUMENT --}}
                <div class="flex flex-col lg:flex-row justify-between gap-6 px-6 sm:px-10 py-8 bg-slate-50/50 border-b border-slate-100">
                    {{-- Logo & Brand --}}
                    <div class="flex items-center gap-5">
                        <div class="w-20 h-20 rounded-3xl bg-white shadow-xl shadow-slate-200/50 flex items-center justify-center p-2 border border-slate-100 ring-8 ring-slate-50">
                            <img src="{{ asset('images/logo.jpg') }}" class="w-full h-full object-contain" alt="Logo">
                        </div>
                        <div>
                            <h2 class="text-2xl font-black text-slate-900 tracking-[0.15em] uppercase leading-none mb-1">Bio Farm Trading</h2>
                            <p class="text-[10px] sm:text-xs font-bold text-indigo-600/70 uppercase tracking-[0.2em] mb-3">Service Production & Qualité</p>
                            <div class="flex flex-wrap gap-2">
                                <span class="px-2 py-0.5 rounded-full bg-indigo-100 text-[10px] font-black text-indigo-700 uppercase tracking-tighter">Bio Premium</span>
                                <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-[10px] font-black text-emerald-700 uppercase tracking-tighter">Export Grade</span>
                            </div>
                        </div>
                    </div>

                    {{-- Bon info --}}
                    <div class="flex flex-col sm:flex-row lg:flex-col justify-between items-start sm:items-center lg:items-end gap-3 sm:gap-10">
                        <div class="text-left lg:text-right">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Référence Facture</p>
                            <input type="hidden" name="bon_no" value="{{ $nextBonNo }}">
                            <div class="inline-flex items-center gap-2 bg-indigo-600 px-4 py-1.5 rounded-xl shadow-lg shadow-indigo-100">
                                <i class="fa-solid fa-hashtag text-indigo-200 text-xs"></i>
                                <span class="text-lg font-black text-white italic tracking-widest">{{ $nextBonNo }}</span>
                            </div>
                        </div>
                        <div class="text-left lg:text-right w-full sm:w-auto">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Date d'opération</p>
                            <div class="relative inline-flex items-center w-full">
                                <i class="fa-regular fa-calendar absolute left-3 text-indigo-500"></i>
                                <input type="date" name="date_invoice" value="{{ date('Y-m-d') }}" 
                                       class="pl-10 pr-4 py-2 bg-white rounded-xl border-slate-200 text-sm font-black text-slate-800 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition w-full sm:w-44 shadow-sm" required>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- BANDEAU TITRE --}}
                <div class="py-4 bg-white border-y border-slate-100 flex items-center justify-center">
                    <div class="flex items-center gap-8">
                        <div class="h-px w-12 sm:w-32 bg-slate-200"></div>
                        <h3 class="text-lg sm:text-2xl font-black tracking-[0.4em] text-slate-800 uppercase italic">Facture d'Achat</h3>
                        <div class="h-px w-12 sm:w-32 bg-slate-200"></div>
                    </div>
                </div>

                {{-- INFOS SECTION --}}
                <div class="p-6 sm:p-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 bg-slate-50 p-6 rounded-[2rem] border border-slate-100">
                        {{-- Zone --}}
                        <div class="group">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-3">
                                <i class="fa-solid fa-location-dot mr-1.5 text-indigo-500"></i> Zone de provenance
                            </label>
                            <input type="text" name="zone" list="zones_list" 
                                   class="w-full px-5 py-3.5 bg-white border-2 border-slate-100 rounded-2xl text-sm font-bold text-slate-800 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition shadow-sm placeholder-slate-300" 
                                   placeholder="Saisir la zone...">
                        </div>

                        {{-- Producteur --}}
                        <div class="group">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-3">
                                <i class="fa-solid fa-user-tie mr-1.5 text-indigo-500"></i> Producteur / OP
                            </label>
                            <input type="text" name="producteur" 
                                   class="w-full px-5 py-3.5 bg-white border-2 border-slate-100 rounded-2xl text-sm font-bold text-slate-800 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition shadow-sm placeholder-slate-300" 
                                   placeholder="Nom du producteur...">
                        </div>

                        {{-- Chauffeur --}}
                        <div class="group">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-3">
                                <i class="fa-solid fa-truck-fast mr-1.5 text-indigo-500"></i> Chauffeur
                            </label>
                            <input type="text" name="chauffeur" list="chauffeurs_list" 
                                   class="w-full px-5 py-3.5 bg-white border-2 border-slate-100 rounded-2xl text-sm font-bold text-slate-800 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition shadow-sm placeholder-slate-300" 
                                   placeholder="Nom du chauffeur...">
                        </div>

                        {{-- Matricule --}}
                        <div class="group">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-3">
                                <i class="fa-solid fa-id-card mr-1.5 text-indigo-500"></i> Matricule Véhicule
                            </label>
                            <input type="text" name="code_parcelle_matricule" list="matricules_list" 
                                   class="w-full px-5 py-3.5 bg-white border-2 border-slate-100 rounded-2xl text-sm font-bold text-slate-800 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition shadow-sm placeholder-slate-300 uppercase" 
                                   placeholder="Ex: TG 7151 BL...">
                        </div>

                        {{-- Fruit --}}
                        <div class="group">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-3">
                                <i class="fa-solid fa-leaf mr-1.5 text-indigo-500"></i> Type de Fruit
                            </label>
                            <input type="text" name="fruit" list="fruits_list" 
                                   class="w-full px-5 py-3.5 bg-white border-2 border-slate-100 rounded-2xl text-sm font-bold text-slate-800 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition shadow-sm placeholder-slate-300" 
                                   placeholder="Sélectionner le fruit...">
                        </div>

                        {{-- Avarie --}}
                        <div class="group">
                            <label class="block text-[10px] font-black text-orange-400 uppercase tracking-widest mb-2 px-3">
                                <i class="fa-solid fa-shield-virus mr-1.5 text-orange-500"></i> Taux d'avarie (%)
                            </label>
                            <div class="relative">
                                <input type="number" name="avarie_pct" x-model="avariePct" step="0.01" min="0" max="100"
                                       class="w-full pl-5 pr-12 py-3.5 bg-orange-50 ring-1 ring-orange-200 border-0 rounded-2xl text-sm font-black text-orange-700 placeholder-orange-200 focus:ring-4 focus:ring-orange-100 transition shadow-sm" 
                                       placeholder="0.00">
                                <div class="absolute right-5 top-1/2 -translate-y-1/2 font-black text-orange-400">%</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION RELEVÉ --}}
                <div class="relative">
                    <div class="bg-indigo-950 text-white py-3 flex items-center justify-center gap-10">
                        <div class="h-px w-20 bg-indigo-500/30"></div>
                        <h4 class="text-xs font-black uppercase tracking-[1em] italic">Relevé de Poids</h4>
                        <div class="h-px w-20 bg-indigo-500/30"></div>
                    </div>
                </div>

                {{-- STATS BAR --}}
                <div class="flex items-center justify-between px-10 py-4 bg-indigo-50/50">
                    <div class="flex items-center gap-8">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-white border-2 border-indigo-200 flex items-center justify-center">
                                <i class="fa-solid fa-boxes-stacked text-indigo-500 text-[10px]"></i>
                            </div>
                            <div>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-wider">Cases remplies</p>
                                <p class="text-sm font-black text-slate-800 leading-none">
                                    <span class="text-indigo-600 text-base" x-text="filledCount"></span> <span class="text-slate-300 font-normal">/ 200</span>
                                </p>
                            </div>
                        </div>
                        <div class="h-10 w-px bg-slate-200"></div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-white border-2 border-emerald-200 flex items-center justify-center">
                                <i class="fa-solid fa-weight-hanging text-emerald-500 text-[10px]"></i>
                            </div>
                            <div>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-wider">Poids Brut Total</p>
                                <p class="text-sm font-black text-slate-800 leading-none" x-text="totalWeight().toFixed(2) + ' kg'"></p>
                            </div>
                        </div>
                    </div>
                    <button type="button" @click="clearWeights" 
                            class="px-4 py-2 bg-red-50 text-red-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-600 hover:text-white transition flex items-center gap-2">
                        <i class="fa-solid fa-trash-can"></i>
                        Vider tout le relevé
                    </button>
                </div>

                {{-- GRILLES DES POIDS --}}
                <div class="divide-y divide-slate-100">
                    @php
                        $weightGroups = [
                            ['n'=>1, 'from'=>1,   'to'=>50,  'color'=>'indigo'],
                            ['n'=>2, 'from'=>51,  'to'=>100, 'color'=>'emerald'],
                            ['n'=>3, 'from'=>101, 'to'=>150, 'color'=>'amber'],
                            ['n'=>4, 'from'=>151, 'to'=>200, 'color'=>'purple'],
                        ];
                    @endphp

                    @foreach($weightGroups as $group)
                    @php $offset = ($group['n']-1)*50; @endphp
                    <div x-data="{ expanded: {{ $group['n'] === 1 ? 'true' : 'false' }} }" class="bg-white">
                        {{-- Group Header --}}
                        <button type="button" @click="expanded = !expanded"
                                class="w-full flex items-center justify-between px-6 sm:px-10 py-4 hover:bg-slate-50 transition border-l-4"
                                :class="expanded ? 'border-{{ $group['color'] }}-500 bg-slate-50/50' : 'border-transparent'">
                            <div class="flex items-center gap-6">
                                <div class="w-10 h-10 rounded-2xl bg-{{ $group['color'] }}-600 flex items-center justify-center shadow-lg shadow-{{ $group['color'] }}-100 ring-4 ring-{{ $group['color'] }}-50">
                                    <span class="text-sm font-black text-white italic">{{ $group['n'] }}</span>
                                </div>
                                <div class="text-left">
                                    <p class="text-xs font-black text-slate-800 uppercase tracking-widest">Groupe {{ $group['n'] }}</p>
                                    <p class="text-[10px] font-bold text-slate-400">Cases {{ $group['from'] }} à {{ $group['to'] }}</p>
                                </div>
                                <div class="flex items-center gap-4 ml-6 sm:ml-12">
                                    <div class="px-3 py-1 bg-white rounded-full border border-slate-200 text-[10px] font-black text-slate-600 uppercase tracking-tighter">
                                        <span x-text="grpFilled({{ $offset }})"></span> / 50 cases
                                    </div>
                                    <div class="px-3 py-1 bg-{{ $group['color'] }}-100 rounded-full text-[10px] font-black text-{{ $group['color'] }}-700 uppercase tracking-tighter italic">
                                        <span x-text="grpTotal({{ $offset }}).toFixed(2)"></span> kg
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                {{-- Quick Calibre for Group --}}
                                <div class="hidden sm:flex items-center gap-1 bg-white p-1 rounded-xl border border-slate-200" @click.stop>
                                    <button type="button" @click="setGrpCalibre({{ $offset }}, 'PF')" class="px-3 py-1 rounded-lg text-[9px] font-black transition hover:bg-slate-100">FIX PF</button>
                                    <button type="button" @click="setGrpCalibre({{ $offset }}, 'GF')" class="px-3 py-1 rounded-lg text-[9px] font-black transition hover:bg-slate-100">FIX GF</button>
                                </div>
                                <i class="fa-solid fa-chevron-down text-slate-300 transition-transform duration-300" :class="expanded ? 'rotate-180' : ''"></i>
                            </div>
                        </button>

                        {{-- Group Content --}}
                        <div x-show="expanded" x-collapse>
                            <div class="p-6 sm:px-10 pb-10 bg-slate-50/30">
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                                    @for($col=0; $col<5; $col++)
                                    @php $colOffset = $offset + $col*10; @endphp
                                    <div class="bg-white rounded-[1.5rem] border border-slate-200 shadow-sm overflow-hidden flex flex-col h-full">
                                        <div class="bg-{{ $group['color'] }}-600 py-2 text-center">
                                            <span class="text-[9px] font-black text-white/90 uppercase tracking-[0.2em] italic">Bloc {{ $col+1 }}</span>
                                        </div>
                                        <div class="flex-1 divide-y divide-slate-100">
                                            @for($row=0; $row<10; $row++)
                                            @php $idx = $colOffset + $row; @endphp
                                            <div class="flex items-center gap-3 p-2 group hover:bg-slate-50 transition-colors">
                                                <span class="w-7 text-[10px] font-black text-slate-300 group-hover:text-{{ $group['color'] }}-400 transition-colors">{{ str_pad($idx+1, 3, '0', STR_PAD_LEFT) }}</span>
                                                <div class="relative flex-1">
                                                    <input type="number" step="0.01" 
                                                           name="weights[{{ $idx }}]"
                                                           x-model="weights[{{ $idx }}]"
                                                           @input="updateStats"
                                                           class="w-full h-10 text-center text-sm font-black border-2 border-slate-100 rounded-xl transition-all weight-input focus:ring-0"
                                                           :class="calibres[{{ $idx }}] === 'GF' ? 'text-amber-600' : 'text-indigo-600'"
                                                           placeholder="0.00">
                                                    <div class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center gap-1.5" @click.stop>
                                                        <button type="button" 
                                                                @click="toggleCalibre({{ $idx }})"
                                                                class="w-8 h-6 rounded-lg text-[9px] font-black transition-all shadow-sm flex items-center justify-center border"
                                                                :class="calibres[{{ $idx }}] === 'GF' ? 'bg-amber-600 text-white border-amber-500 shadow-amber-100' : 'bg-indigo-600 text-white border-indigo-500 shadow-indigo-100'">
                                                            <span x-text="calibres[{{ $idx }}]"></span>
                                                        </button>
                                                    </div>
                                                    <input type="hidden" name="calibres[{{ $idx }}]" :value="calibres[{{ $idx }}]">
                                                </div>
                                            </div>
                                            @endfor
                                        </div>
                                        <div class="bg-{{ $group['color'] }}-50 py-2 border-t border-{{ $group['color'] }}-100">
                                            <p class="text-[9px] font-black text-{{ $group['color'] }}-700 text-center uppercase">Total : <span x-text="colTotal({{ $colOffset }}, 10).toFixed(1)"></span> kg</p>
                                        </div>
                                    </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- PANNEAU DES TOTAUX --}}
                <div class="p-6 sm:p-10 bg-slate-100 border-t-4 border-slate-900">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        
                        {{-- Colonne GAUCHE - Détails Calculs --}}
                        <div class="space-y-4">
                            <h5 class="text-sm font-black text-slate-900 uppercase tracking-widest flex items-center justify-between">
                                <span>Détails de la transaction</span>
                                <i class="fa-solid fa-calculator text-indigo-300"></i>
                            </h5>
                            
                            <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-200">
                                <div class="space-y-4 divide-y divide-slate-100">
                                    {{-- PF Section --}}
                                    <div class="pb-3 flex items-end justify-between">
                                        <div>
                                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-wide mb-1">Calibre PF (Petit Fruit)</p>
                                            <p class="text-sm font-black text-indigo-700">Poids : <span x-text="poidsMarchandPF().toFixed(2)"></span> kg <span class="text-[9px] text-slate-400 font-normal">(Net)</span></p>
                                        </div>
                                        <div class="text-right">
                                            <label class="block text-[8px] font-black text-slate-400 uppercase mb-1">P.U PF (FCFA)</label>
                                            <input type="number" name="pu_pf" x-model.number="pu_pf" class="w-24 px-3 py-1.5 bg-indigo-50 border-0 rounded-lg text-right font-black text-indigo-700 focus:ring-2 focus:ring-indigo-300 transition">
                                        </div>
                                    </div>

                                    {{-- GF Section --}}
                                    <div class="py-3 flex items-end justify-between">
                                        <div>
                                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-wide mb-1">Calibre GF (Grand Fruit)</p>
                                            <p class="text-sm font-black text-amber-600">Poids : <span x-text="poidsMarchandGF().toFixed(2)"></span> kg <span class="text-[9px] text-slate-400 font-normal">(Net)</span></p>
                                        </div>
                                        <div class="text-right">
                                            <label class="block text-[8px] font-black text-slate-400 uppercase mb-1">P.U GF (FCFA)</label>
                                            <input type="number" name="pu_gf" x-model.number="pu_gf" class="w-24 px-3 py-1.5 bg-amber-50 border-0 rounded-lg text-right font-black text-amber-600 focus:ring-2 focus:ring-amber-300 transition">
                                        </div>
                                    </div>

                                    {{-- Montant Marchandises --}}
                                    <div class="pt-3 flex items-center justify-between">
                                        <span class="text-xs font-black text-slate-800 uppercase italic">Montant Fruits</span>
                                        <span class="text-base font-black text-slate-900" x-text="formatCurrency(montantTotal()) + ' FCFA'"></span>
                                    </div>
                                </div>
                            </div>

                            {{-- Prime Bio --}}
                            <div class="bg-emerald-50 rounded-[1.5rem] p-4 border border-emerald-100 flex items-center justify-between shadow-sm shadow-emerald-50">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center">
                                        <i class="fa-solid fa-medal text-white"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-emerald-800 uppercase">Prime Bio par kg</p>
                                        <p class="text-xs font-bold text-emerald-600">Appliquée au poids total brut</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="number" name="prime_bio_kg" x-model.number="primeBio" 
                                           class="w-20 px-3 py-2 bg-white border-0 rounded-xl text-right font-black text-emerald-700 focus:ring-2 focus:ring-emerald-300 transition shadow-sm">
                                    <span class="text-[10px] font-black text-emerald-800">/ kg</span>
                                </div>
                            </div>
                        </div>

                        {{-- Colonne DROITE - Crédits & NET --}}
                        <div class="space-y-4">
                            <h5 class="text-sm font-black text-slate-900 uppercase tracking-widest flex items-center justify-between">
                                <span>Règlement final</span>
                                <i class="fa-solid fa-receipt text-indigo-300"></i>
                            </h5>

                            <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-200">
                                <div class="space-y-5">
                                    {{-- Crédit --}}
                                    <div class="flex items-center justify-between group">
                                        <div class="flex items-center gap-2">
                                            <i class="fa-solid fa-minus-circle text-red-100 group-hover:text-red-400 transition-colors"></i>
                                            <span class="text-xs font-black text-red-700 uppercase italic">Retrait Crédit</span>
                                        </div>
                                        <input type="number" name="total_credit" x-model.number="manualCredit" 
                                               class="w-32 px-4 py-2 bg-red-50 border-0 rounded-xl text-right font-black text-red-700 focus:ring-2 focus:ring-red-200 transition shadow-sm" placeholder="0">
                                    </div>

                                    <div class="h-px bg-slate-100"></div>

                                    {{-- NET À PAYER --}}
                                    <div class="py-4 px-6 bg-indigo-600 rounded-[2rem] shadow-xl shadow-indigo-100 flex items-center justify-between ring-4 ring-indigo-50">
                                        <span class="text-sm font-black text-white uppercase tracking-widest italic">Net à Payer</span>
                                        <span class="text-2xl font-black text-white" x-text="formatCurrency(netAPayer()) + ' FCFA'"></span>
                                    </div>

                                    {{-- En Lettres --}}
                                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 relative overflow-hidden">
                                        <i class="fa-solid fa-quote-left absolute top-2 left-2 text-slate-200/50 text-2xl"></i>
                                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-wider mb-2 relative z-10">Montant net en lettres</p>
                                        <textarea name="net_payer_lettre" x-model="netAPayerLettre" 
                                                  class="w-full text-xs font-black italic text-slate-600 border-0 bg-transparent resize-none p-0 focus:ring-0 leading-relaxed capitalize relative z-10" 
                                                  rows="2" readonly></textarea>
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
                        <div class="flex items-center justify-between mb-4">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] group-hover:text-indigo-600 transition-colors">A2C SAM / Responsable</p>
                            <button type="button" @click="clearSignature('signature-resp')" class="text-[9px] font-black text-red-400 hover:text-red-700 transition uppercase tracking-widest">
                                <i class="fa-solid fa-eraser mr-1"></i> Effacer
                            </button>
                        </div>
                        <div class="relative rounded-[1.5rem] bg-slate-50 p-1 border-2 border-dashed border-slate-200">
                            <canvas id="signature-resp" class="signature-pad w-full rounded-2xl"></canvas>
                            <input type="hidden" name="signature_resp" id="signature_resp_input">
                        </div>
                        <p class="text-[8px] text-slate-400 text-center mt-3 font-bold italic tracking-wider">Signature & Cachet Officiel</p>
                    </div>

                    {{-- Signature Producteur --}}
                    <div class="p-8 group shadow-inner">
                        <div class="flex items-center justify-between mb-4">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] group-hover:text-amber-500 transition-colors">Producteur / Livreur</p>
                            <button type="button" @click="clearSignature('signature-prod')" class="text-[9px] font-black text-red-400 hover:text-red-700 transition uppercase tracking-widest">
                                <i class="fa-solid fa-eraser mr-1"></i> Effacer
                            </button>
                        </div>
                        <div class="relative rounded-[1.5rem] bg-slate-50 p-1 border-2 border-dashed border-slate-200">
                            <canvas id="signature-prod" class="signature-pad w-full rounded-2xl"></canvas>
                            <input type="hidden" name="signature_prod" id="signature_prod_input">
                        </div>
                        <p class="text-[8px] text-slate-400 text-center mt-3 font-bold italic tracking-wider">Attestation de réception de fonds</p>
                    </div>
                </div>
            </div>

            {{-- FOTER BUTTONS --}}
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-2 text-slate-400">
                    <i class="fa-solid fa-circle-info italic"></i>
                    <p class="text-[10px] font-bold italic tracking-wide lowercase">Les calculs de poids marchand sont automatiques après déduction du taux d'avarie.</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                    <a href="{{ route('purchase_invoices.index') }}" 
                       class="px-8 py-3.5 bg-white border border-slate-200 rounded-[1.5rem] text-sm font-black text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition text-center shadow-lg shadow-slate-100">
                        Abandonner
                    </a>
                    <button type="submit" 
                            class="group relative inline-flex items-center justify-center gap-3 px-12 py-3.5 bg-indigo-600 rounded-[1.5rem] text-[15px] font-black text-white hover:bg-indigo-700 shadow-xl shadow-indigo-100 transition-all overflow-hidden focus:ring-4 focus:ring-indigo-100">
                        <span class="relative z-10 flex items-center gap-3">
                            <i class="fa-solid fa-cloud-arrow-up group-hover:animate-bounce"></i>
                            Enregistrer la facture
                        </span>
                        <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-indigo-700 scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-500"></div>
                    </button>
                </div>
            </div>

            <input type="hidden" name="weights_csv" :value="weightsCSV">
            <input type="hidden" name="calibres_csv" :value="calibresCSV">
        </form>
    </div>

    {{-- DATALISTS --}}
    <datalist id="zones_list">
        <option value="Avé"><option value="Zio"><option value="Vo"><option value="Danyi">
        <option value="Kloto"><option value="Agou"><option value="Haho"><option value="Bas-mono">
    </datalist>
    <datalist id="chauffeurs_list">
        <option value="SOUMAGBO Yao"><option value="AGBADZI Komi Victor">
        <option value="AMEGBETO K. Promise"><option value="MORKLEY Komi">
    </datalist>
    <datalist id="matricules_list">
        <option value="BL 7151"><option value="BL 7238"><option value="BD 2671">
        <option value="BH 5895"><option value="BH 5588"><option value="EL 2473">
    </datalist>
    <datalist id="fruits_list">
        <option value="Ananas Cayenne"><option value="Ananas Braza">
        <option value="Papaye"><option value="Banane"><option value="Mangue">
    </datalist>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('purchaseInvoiceForm', () => ({
            weights: Array(200).fill(null),
            calibres: Array(200).fill('PF'),
            pu_pf: 0,
            pu_gf: 0,
            manualCredit: 0,
            primeBio: 0,
            avariePct: 0,
            filledCount: 0,
            
            initForm() {
                this.updateStats();
                this.$watch('weights', () => this.updateStats(), { deep: true });
                this.$watch('calibres', () => this.updateStats(), { deep: true });
            },
            
            updateStats() {
                this.filledCount = this.weights.filter(w => parseFloat(w) > 0).length;
            },
            
            get weightsCSV() { return this.weights.map(w => w || '').join(','); },
            get calibresCSV() { return this.calibres.join(','); },
            get netAPayerLettre() { return this.numberToWords(this.netAPayer()); },
            
            toggleCalibre(idx) {
                this.calibres[idx] = this.calibres[idx] === 'PF' ? 'GF' : 'PF';
                this.calibres = [...this.calibres];
            },
            
            setGrpCalibre(offset, val) {
                for (let i = offset; i < offset + 50; i++) this.calibres[i] = val;
                this.calibres = [...this.calibres];
            },

            clearWeights() {
                if(confirm('Souhaitez-vous vraiment vider tous les poids ?')) {
                    this.weights = Array(200).fill(null);
                    this.calibres = Array(200).fill('PF');
                }
            },
            
            clearSignature(id) {
                if (sigPads[id]) sigPads[id].clear();
            },
            
            totalWeight() { return this.weights.reduce((sum, w) => sum + (parseFloat(w) || 0), 0); },
            weightPF() { return this.weights.reduce((sum, w, i) => sum + (parseFloat(w) > 0 && this.calibres[i] === 'PF' ? parseFloat(w) : 0), 0); },
            weightGF() { return this.weights.reduce((sum, w, i) => sum + (parseFloat(w) > 0 && this.calibres[i] === 'GF' ? parseFloat(w) : 0), 0); },
            
            poidsMarchandPF() { return this.weightPF() * (1 - (this.avariePct || 0) / 100); },
            poidsMarchandGF() { return this.weightGF() * (1 - (this.avariePct || 0) / 100); },
            
            colTotal(start, count) {
                let s = 0;
                for (let i = start; i < start + count; i++) s += parseFloat(this.weights[i]) || 0;
                return s;
            },
            
            grpTotal(offset) { return this.colTotal(offset, 50); },
            grpFilled(offset) {
                let count = 0;
                for (let i = offset; i < offset + 50; i++) if (parseFloat(this.weights[i]) > 0) count++;
                return count;
            },
            
            montantTotal() {
                return (this.poidsMarchandPF() * (this.pu_pf || 0)) + (this.poidsMarchandGF() * (this.pu_gf || 0));
            },
            
            totalPrime() { return this.totalWeight() * (this.primeBio || 0); },
            netAPayer() { return Math.max(0, Math.round((this.montantTotal() + this.totalPrime()) - (this.manualCredit || 0))); },
            formatCurrency(val) { return new Intl.NumberFormat('fr-FR').format(Math.round(val)); },
            
            numberToWords(n) {
                if (!n || n <= 0) return "Zéro franc CFA";
                const units = ["", "un", "deux", "trois", "quatre", "cinq", "six", "sept", "huit", "neuf"];
                const tens = ["", "dix", "vingt", "trente", "quarante", "cinquante", "soixante", "soixante-dix", "quatre-vingt", "quatre-vingt-dix"];
                
                if (n < 1000000) {
                    return n.toLocaleString('fr-FR') + " francs CFA";
                }
                return n.toString() + " francs CFA";
            },

            submitForm() {
                // Collect signatures
                if (sigPads['signature-resp']) {
                    document.getElementById('signature_resp_input').value = sigPads['signature-resp'].isEmpty() ? '' : sigPads['signature-resp'].toDataURL();
                }
                if (sigPads['signature-prod']) {
                    document.getElementById('signature_prod_input').value = sigPads['signature-prod'].isEmpty() ? '' : sigPads['signature-prod'].toDataURL();
                }
                
                if (this.filledCount === 0) {
                    alert('ERREUR : Aucun poids n\'a été saisi.');
                    return;
                }
                
                document.getElementById('mainForm').submit();
            }
        }));
    });

    let sigPads = {};

    function resizeCanvas() {
        ['signature-resp', 'signature-prod'].forEach(id => {
            const canvas = document.getElementById(id);
            if (!canvas || !sigPads[id]) return;
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            sigPads[id].clear();
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        ['signature-resp', 'signature-prod'].forEach(id => {
            const canvas = document.getElementById(id);
            if (canvas) sigPads[id] = new SignaturePad(canvas, { backgroundColor: 'rgb(255, 255, 255)' });
        });
        resizeCanvas();
        window.addEventListener("resize", resizeCanvas);
    });
</script>
@endpush
@endsection