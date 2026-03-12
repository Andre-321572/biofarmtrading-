@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-100 py-5" x-data="purchaseInvoiceForm()">

    <div class="max-w-6xl mx-auto px-4">

        {{-- TOP BAR --}}
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center shadow">
                    <i class="fa-solid fa-file-invoice-dollar text-white text-sm"></i>
                </div>
                <div>
                    <h1 class="text-base font-black text-slate-800 leading-none">Nouvelle Facture d'Achat</h1>
                    <p class="text-xs text-slate-400">Achat coopérative · Relevé de 200 poids</p>
                </div>
            </div>
            <a href="{{ route('purchase_invoices.index') }}" class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50 shadow-sm transition">
                <i class="fa-solid fa-arrow-left mr-1.5"></i>Retour
            </a>
        </div>

        {{-- ERREURS DE VALIDATION --}}
        @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 rounded-xl px-4 py-3">
            <p class="text-xs font-bold text-red-700 mb-1">Erreurs :</p>
            @foreach($errors->all() as $err)
                <p class="text-xs text-red-600">• {{ $err }}</p>
            @endforeach
        </div>
        @endif

        <form action="{{ route('purchase_invoices.store') }}" method="POST" id="mainForm">
            @csrf

            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden" style="font-family:'Courier New',monospace">
                
                {{-- HEADER DOCUMENT --}}
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-4 sm:px-5 py-4 border-b-2 border-slate-800 bg-slate-50">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full border-2 border-slate-300 overflow-hidden bg-white flex items-center justify-center shrink-0">
                            <img src="{{ asset('images/logo.jpg') }}" class="w-10 h-10 sm:w-12 sm:h-12 object-contain" onerror="this.style.display='none'">
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm sm:text-lg font-black tracking-widest text-slate-900 uppercase truncate">BIO FARM TRADING</p>
                            <p class="text-[8px] sm:text-[10px] text-slate-500 uppercase font-bold leading-tight">Production & Commercialisation</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between md:justify-end gap-5 flex-shrink-0 border-t border-slate-200 md:border-0 pt-3 md:pt-0">
                        <div class="text-left md:text-right">
                            <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400">BON N°</p>
                            <input type="text" name="bon_no" value="#{{ $nextBonNo }}" class="text-base sm:text-xl font-black text-slate-900 border-0 bg-transparent p-0 w-24 sm:w-32 md:text-right focus:ring-0">
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Date</p>
                            <input type="date" name="date_invoice" value="{{ date('Y-m-d') }}" class="text-sm font-black text-slate-900 border-0 border-b-2 border-slate-300 bg-transparent focus:border-indigo-600 focus:ring-0 p-0 w-28 sm:w-32 text-right" required>
                        </div>
                    </div>
                </div>

                <div class="text-center py-2 bg-slate-100 border-b border-slate-300">
                    <h2 class="text-lg font-black tracking-[0.5em] text-slate-800 uppercase">Facture d'Achat</h2>
                </div>

                {{-- FORM GRID --}}
                <div class="grid grid-cols-1 md:grid-cols-2 bg-slate-200 gap-[1px]">
                    {{-- Zone --}}
                    <div class="flex bg-white">
                        <label class="bg-slate-50 px-3 py-3 text-[9px] font-bold uppercase text-slate-400 w-24 sm:w-32 flex items-center shrink-0 border-r border-slate-100">ZONE</label>
                        <input type="text" name="zone" list="zones_list" class="flex-1 px-3 py-2 text-sm font-bold border-0 focus:ring-0" placeholder="...">
                        <datalist id="zones_list">
                            <option value="Avé">
                            <option value="Zio">
                            <option value="Vo">
                            <option value="Danyi">
                            <option value="Kloto">
                            <option value="Agou">
                            <option value="Haho">
                            <option value="Bas-mono">
                        </datalist>
                    </div>
                    {{-- Producteur --}}
                    <div class="flex bg-white">
                        <label class="bg-slate-50 px-3 py-3 text-[9px] font-bold uppercase text-slate-400 w-24 sm:w-32 flex items-center shrink-0 border-r border-slate-100">PRODUCTEUR</label>
                        <input type="text" name="producteur" class="flex-1 px-3 py-2 text-sm font-bold border-0 focus:ring-0" placeholder="...">
                    </div>
                    {{-- Chauffeur --}}
                    <div class="flex bg-white">
                        <label class="bg-slate-50 px-3 py-3 text-[9px] font-bold uppercase text-slate-400 w-24 sm:w-32 flex items-center shrink-0 border-r border-slate-100">CHAUFFEUR</label>
                        <input type="text" name="chauffeur" list="chauffeurs_list" class="flex-1 px-3 py-2 text-sm font-bold border-0 focus:ring-0" placeholder="...">
                        <datalist id="chauffeurs_list">
                            <option value="SOUMAGBO Yao">
                            <option value="AGBADZI Komi Victor">
                            <option value="AMEGBETO K. Promise">
                            <option value="MORKLEY Komi">
                        </datalist>
                    </div>
                    {{-- Matricule --}}
                    <div class="flex bg-white">
                        <label class="bg-slate-50 px-3 py-3 text-[9px] font-bold uppercase text-slate-400 w-24 sm:w-32 flex items-center shrink-0 border-r border-slate-100 uppercase">MATRICULE</label>
                        <input type="text" name="code_parcelle_matricule" list="matricules_list" class="flex-1 px-3 py-2 text-sm font-bold border-0 focus:ring-0 uppercase" placeholder="...">
                        <datalist id="matricules_list">
                            <option value="BL 7151">
                            <option value="BL 7238">
                            <option value="BD 2671">
                            <option value="BH 5895">
                            <option value="BH 5588">
                            <option value="EL 2473">
                        </datalist>
                    </div>
                    {{-- Fruit --}}
                    <div class="flex bg-white">
                        <label class="bg-slate-50 px-3 py-3 text-[9px] font-bold uppercase text-slate-400 w-24 sm:w-32 flex items-center shrink-0 border-r border-slate-100">FRUIT</label>
                        <input type="text" name="fruit" list="fruits_list" class="flex-1 px-3 py-2 text-sm font-bold border-0 focus:ring-0" placeholder="...">
                        <datalist id="fruits_list">
                            <option value="Ananas Cayenne">
                            <option value="Ananas Braza">
                            <option value="Papaye">
                            <option value="Banane">
                            <option value="Mangue">
                        </datalist>
                    </div>
                    {{-- Calibre --}}
                    <div class="flex bg-white">
                        <label class="bg-slate-50 px-3 py-3 text-[9px] font-bold uppercase text-slate-400 w-24 sm:w-32 flex items-center shrink-0 border-r border-slate-100">CALIBRE</label>
                        <select name="calibre" class="flex-1 px-3 py-2 text-sm font-bold border-0 focus:ring-0 cursor-pointer">
                            <option value="">...</option>
                            <option value="Petit fruit">Petit fruit</option>
                            <option value="Gros fruit">Gros fruit</option>
                        </select>
                    </div>
                    {{-- % Avarie --}}
                    <div class="flex bg-white border border-orange-200">
                        <label class="bg-orange-50 px-3 py-3 text-[9px] font-bold uppercase text-orange-500 w-24 sm:w-32 flex items-center shrink-0 border-r border-orange-200">
                            <i class="fa-solid fa-triangle-exclamation mr-1 text-orange-400"></i> % AVARIE
                        </label>
                        <div class="flex-1 flex items-center px-3">
                            <input type="number" name="avarie_pct" id="avarie_pct_input"
                                   x-model.number="avariePct"
                                   step="0.01" min="0" max="100"
                                   class="flex-1 py-2 text-sm font-black text-orange-600 border-0 focus:ring-0 bg-transparent"
                                   placeholder="0.00">
                            <span class="ml-1 text-sm font-black text-orange-500">%</span>
                        </div>
                        {{-- Aperçu en temps réel --}}
                        <div class="hidden sm:flex items-center gap-3 px-3 border-l border-orange-100 bg-orange-50/50">
                            <div class="text-right">
                                <p class="text-[8px] text-orange-400 uppercase font-bold">Avarié</p>
                                <p class="text-[11px] font-black text-orange-600" x-text="poidsAvarieCalc().toFixed(2) + ' kg'">0.00 kg</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[8px] text-slate-400 uppercase font-bold">Marchand</p>
                                <p class="text-[11px] font-black text-slate-700" x-text="poidsMarchandCalc().toFixed(2) + ' kg'">0.00 kg</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RELEVÉ DE POIDS --}}
                <div class="bg-slate-800 text-white text-center py-1 font-black tracking-[0.8em] uppercase text-xs">
                    Relevé de Poids
                </div>

                {{-- BARRE STATS POIDS --}}
                <div class="flex items-center justify-between px-5 py-2 bg-slate-100 border-b border-slate-300">
                    <div class="text-[10px] font-black text-slate-500 uppercase flex gap-4">
                        <span>Cases : <span class="text-indigo-600" x-text="filled()"></span>/200</span>
                        <span>Total : <span class="text-green-600" x-text="totalWeight().toFixed(2)+' kg'"></span></span>
                    </div>
                </div>

                {{-- 4 GROUPES DE 50 --}}
                @php
                    $groups = [
                        ['num'=>1,'color'=>'blue',  'from'=>1,  'to'=>50,  'bg'=>'bg-blue-600',  'light'=>'bg-blue-50',  'border'=>'border-blue-200', 'text'=>'text-blue-700', 'badge'=>'bg-blue-100 text-blue-800'],
                        ['num'=>2,'color'=>'emerald','from'=>51, 'to'=>100, 'bg'=>'bg-emerald-600','light'=>'bg-emerald-50','border'=>'border-emerald-200','text'=>'text-emerald-700','badge'=>'bg-emerald-100 text-emerald-800'],
                        ['num'=>3,'color'=>'amber',  'from'=>101,'to'=>150, 'bg'=>'bg-amber-500',  'light'=>'bg-amber-50',  'border'=>'border-amber-200', 'text'=>'text-amber-700', 'badge'=>'bg-amber-100 text-amber-800'],
                        ['num'=>4,'color'=>'purple', 'from'=>151,'to'=>200, 'bg'=>'bg-purple-600', 'light'=>'bg-purple-50', 'border'=>'border-purple-200','text'=>'text-purple-700','badge'=>'bg-purple-100 text-purple-800'],
                    ];
                @endphp

                <div class="divide-y divide-slate-200">
                @foreach($groups as $g)
                @php $offset = ($g['num']-1)*50; $alpineOpen = $g['num']===1 ? 'true' : 'false'; @endphp
                <div x-data="{ open: {{ $alpineOpen }} }">
                    <button type="button" @click="open=!open"
                            class="w-full flex items-center justify-between px-4 py-2 text-left hover:brightness-95 transition select-none {{ $g['light'] }} border-b {{ $g['border'] }}">
                        <div class="flex items-center gap-3 flex-1 overflow-hidden">
                            <div class="w-6 h-6 rounded {{ $g['bg'] }} flex items-center justify-center shrink-0">
                                <span class="text-[10px] font-black text-white">{{ $g['num'] }}</span>
                            </div>
                            <span class="text-xs font-black {{ $g['text'] }} truncate">Groupe {{ $g['num'] }} <span class="opacity-50 font-normal hidden sm:inline">({{ $g['from'] }}–{{ $g['to'] }})</span></span>
                            
                            {{-- Sélecteur de calibre pour le groupe --}}
                            <div class="flex items-center bg-white rounded border border-slate-200 p-0.5 ml-auto sm:ml-0" @click.stop>
                                <label class="text-[8px] font-bold px-2 text-slate-400 uppercase hidden xs:inline">Calibre Groupe:</label>
                                <select class="text-[9px] font-black border-0 focus:ring-0 py-0.5 pl-2 pr-6 rounded cursor-pointer bg-slate-50"
                                        @change="setGrpCalibre({{ $offset }}, $event.target.value)">
                                    <option value="">PF / GF</option>
                                    <option value="PF">PETIT (PF)</option>
                                    <option value="GF">GROS (GF)</option>
                                </select>
                            </div>

                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold {{ $g['badge'] }} hidden sm:flex">
                                <span x-text="grpFilled({{ $offset }})"></span>/50 cases &nbsp;·&nbsp;
                                <span x-text="grpTotal({{ $offset }}).toFixed(2)+' kg'"></span>
                            </span>
                        </div>
                        <i class="fa-solid fa-chevron-down w-3 h-3 {{ $g['text'] }} transition-transform duration-200" :class="open?'rotate-180':''"></i>
                    </button>
                    <div x-show="open" class="{{ $g['light'] }} p-2">
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-2">
                            @for($col=0; $col<5; $col++)
                            @php $colOffset = $offset + $col*10; @endphp
                            <div class="bg-white rounded border {{ $g['border'] }}">
                                @for($row=0; $row<10; $row++)
                                @php $absIdx = $colOffset + $row; @endphp
                                <div class="grid grid-cols-[25px_1fr] border-b border-slate-50 items-center">
                                    <div class="py-1 text-center text-[8px] font-black {{ $g['text'] }} border-r border-slate-100 bg-slate-50/50">
                                        {{ str_pad($absIdx+1, 3, '0', STR_PAD_LEFT) }}
                                    </div>
                                    <input type="number" step="0.01" name="weights[]" x-model.number="weights[{{ $absIdx }}]"
                                           class="w-full text-center py-1 text-[10px] font-bold border-0 focus:ring-1 focus:ring-indigo-400 bg-transparent" placeholder="...">
                                    <input type="hidden" name="calibres[]" x-model="calibres[{{ $absIdx }}]">
                                </div>
                                @endfor
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>
                @endforeach
                </div>

                {{-- TOTALS SECTION --}}
                <div class="grid grid-cols-1 md:grid-cols-2 bg-slate-200 gap-[1px] border-t-2 border-slate-800">
                    {{-- Left Column (Prices) --}}
                    <div class="flex flex-col divide-y divide-slate-100 bg-white">
                        <div class="flex items-center">
                            <label class="bg-slate-50 px-3 sm:px-4 py-2.5 text-[9px] sm:text-[10px] font-bold uppercase text-slate-400 w-28 sm:w-48 border-r border-slate-100 shrink-0">POIDS TOTAL</label>
                            <div class="flex-1 px-3 sm:px-4 text-right font-black text-slate-900 text-sm" x-text="totalWeight().toFixed(2) + ' kg'"></div>
                        </div>
                        <div class="flex items-center">
                            <label class="bg-slate-50 px-3 sm:px-4 py-2.5 text-[9px] sm:text-[10px] font-bold uppercase text-slate-400 w-28 sm:w-48 border-r border-slate-100 shrink-0">P.U</label>
                            <div class="flex-1 flex items-center px-3 sm:px-4">
                                <input type="number" name="pu" x-model.number="pu" class="w-full py-1 text-right font-black text-slate-900 border-0 focus:ring-0 text-sm" placeholder="0">
                                <span class="ml-2 text-[10px] font-bold text-slate-400 whitespace-nowrap">FCFA</span>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <label class="bg-slate-50 px-3 sm:px-4 py-2.5 text-[9px] sm:text-[10px] font-bold uppercase text-slate-400 w-28 sm:w-48 border-r border-slate-100 shrink-0 uppercase">MONTANT TOTAL</label>
                            <div class="flex-1 px-3 sm:px-4 text-right font-black text-green-700 text-sm" x-text="formatCurrency(montantTotal()) + ' FCFA'"></div>
                        </div>
                        <div class="flex items-center bg-green-50">
                            <label class="bg-green-100/50 px-3 sm:px-4 py-3 text-[9px] sm:text-[10px] font-black uppercase text-green-800 w-28 sm:w-48 border-r border-green-200 shrink-0">NET À PAYER</label>
                            <div class="flex-1 px-3 sm:px-4 text-right font-black text-green-600 text-lg sm:text-xl" x-text="formatCurrency(netAPayer()) + ' FCFA'"></div>
                        </div>
                        <div class="flex items-center">
                            <label class="bg-slate-50 px-3 sm:px-4 py-2.5 text-[9px] sm:text-[10px] font-bold uppercase text-slate-400 w-28 sm:w-48 border-r border-slate-100 shrink-0 leading-tight">PRIME BIO/KG</label>
                            <div class="flex-1 px-3 sm:px-4">
                                <input type="number" name="prime_bio_kg" x-model.number="primeBio" class="w-full py-1 text-right font-bold text-indigo-600 border-0 focus:ring-0 text-sm" placeholder="...">
                            </div>
                        </div>
                    </div>

                    {{-- Right Column (Credits) --}}
                    <div class="flex flex-col divide-y divide-slate-100 bg-white">
                        <div class="flex items-center">
                            <label class="bg-slate-50 px-3 sm:px-4 py-2.5 text-[9px] sm:text-[10px] font-bold uppercase text-slate-400 w-28 sm:w-48 border-r border-slate-100 shrink-0">Poids Avarié</label>
                            <div class="flex-1 flex items-center px-3 sm:px-4 justify-end">
                                <span class="font-black text-orange-500 text-sm" x-text="poidsAvarieCalc().toFixed(2) + ' kg'">0.00 kg</span>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <label class="bg-slate-50 px-3 sm:px-4 py-2.5 text-[9px] sm:text-[10px] font-bold uppercase text-slate-400 w-28 sm:w-48 border-r border-slate-100 shrink-0 leading-tight">Poids marchand</label>
                            <div class="flex-1 flex items-center px-3 sm:px-4 justify-end">
                                <span class="font-black text-slate-700 text-sm" x-text="poidsMarchandCalc().toFixed(2) + ' kg'">0.00 kg</span>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <label class="bg-slate-50 px-3 sm:px-4 py-2.5 text-[9px] sm:text-[10px] font-bold uppercase text-slate-400 w-28 sm:w-48 border-r border-slate-100 shrink-0">TOTAL CRÉDIT</label>
                            <div class="flex-1 px-3 sm:px-4 text-right font-black text-red-600 text-sm" x-text="formatCurrency(totalCredit()) + ' FCFA'"></div>
                        </div>
                        <div class="flex flex-col px-3 sm:px-4 py-2 bg-slate-50 border-b border-slate-100">
                            <label class="text-[9px] font-bold uppercase text-slate-400 mb-1">NET À PAYER EN LETTRE</label>
                            <textarea name="net_payer_lettre" rows="2" x-text="numberToWords(netAPayer())" class="w-full p-0 text-[10px] font-bold text-slate-600 italic border-0 bg-transparent focus:ring-0 resize-none leading-tight" readonly placeholder="Calcul automatique..."></textarea>
                        </div>
                        <div class="flex items-center">
                            <label class="bg-slate-50 px-3 sm:px-4 py-2.5 text-[9px] sm:text-[10px] font-bold uppercase text-slate-400 w-28 sm:w-48 border-r border-slate-100 shrink-0 leading-tight">TOTAL PRIME</label>
                            <div class="flex-1 px-3 sm:px-4 text-right font-black text-indigo-600 text-sm" x-text="formatCurrency(totalPrime()) + ' FCFA'"></div>
                        </div>
                    </div>
                </div>

                {{-- SIGNATURES --}}
                <div class="border-t border-slate-200 grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-slate-200 bg-slate-50">
                    <div class="px-5 py-4">
                        <p class="text-[9px] font-bold uppercase text-slate-500 mb-6">Responsable Bio Farm</p>
                        <div class="border-b border-dotted border-slate-300 mb-1"></div>
                        <p class="text-[8px] text-slate-400 text-center uppercase font-bold">Cachet & Signature</p>
                    </div>
                    <div class="px-5 py-4 text-right">
                        <p class="text-[9px] font-bold uppercase text-slate-500 mb-6">Le Producteur / Opérateur</p>
                        <div class="border-b border-dotted border-slate-300 mb-1"></div>
                        <p class="text-[8px] text-slate-400 text-center uppercase font-bold">Signature</p>
                    </div>
                </div>

            </div>

            <div class="mt-6 flex flex-col sm:flex-row items-center justify-end gap-3">
                <a href="{{ route('purchase_invoices.index') }}" class="px-6 py-2.5 bg-white border border-slate-300 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50 shadow-sm transition">Annuler</a>
                <button type="submit" class="px-8 py-2.5 bg-indigo-600 rounded-xl text-sm font-black text-white hover:bg-indigo-700 shadow-lg transition flex items-center gap-2">
                    <i class="fa-solid fa-check"></i> Enregistrer la Facture
                </button>
            </div>

        </form>
    </div>
</div>

<script>
function purchaseInvoiceForm() {
    return {
        weights: Array(200).fill(null),
        calibres: Array(200).fill('PF'),
        pu: 0,
        primeBio: 0,
        avariePct: 0,

        poidsAvarieCalc() {
            return (this.totalWeight() * (this.avariePct || 0)) / 100;
        },

        poidsMarchandCalc() {
            return this.totalWeight() - this.poidsAvarieCalc();
        },
        grpTotal(offset) {
            let sum = 0;
            for (let i = offset; i < offset + 50; i++) sum += (this.weights[i] || 0);
            return sum;
        },

        grpFilled(offset) {
            return this.weights.slice(offset, offset + 50).filter(v => v > 0).length;
        },

        setGrpCalibre(offset, val) {
            if (!val) return;
            for (let i = offset; i < offset + 50; i++) {
                this.calibres[i] = val;
            }
        },

        totalWeight() {
            return this.weights.reduce((a, b) => a + (b || 0), 0);
        },

        filled() {
            return this.weights.filter(v => v > 0).length;
        },

        montantTotal() {
            return this.totalWeight() * this.pu;
        },

        totalCredit() {
            return this.poidsAvarieCalc();
        },

        netAPayer() {
            return this.montantTotal() - this.poidsAvarieCalc();
        },

        totalPrime() {
            return this.totalWeight() * (this.primeBio || 0);
        },

        formatCurrency(val) {
            return new Intl.NumberFormat('fr-FR').format(Math.round(val));
        },

        numberToWords(n) {
            if (n <= 0) return "";
            const units = ['', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf'];
            const teens = ['dix', 'onze', 'douze', 'treize', 'quatorze', 'quinze', 'seize', 'dix-sept', 'dix-huit', 'dix-neuf'];
            const tens = ['', 'dix', 'vingt', 'trente', 'quarante', 'cinquante', 'soixante', 'soixante', 'quatre-vingt', 'quatre-vingt'];
            
            function convert(num) {
                if (num < 10) return units[num];
                if (num < 20) return teens[num - 10];
                if (num < 70) return tens[Math.floor(num / 10)] + (num % 10 === 1 ? ' et un' : (num % 10 !== 0 ? '-' + units[num % 10] : ''));
                if (num < 80) return "soixante-" + convert(num - 60);
                if (num < 100) return tens[Math.floor(num / 10)] + (num % 10 !== 0 ? '-' + convert(num % 10) : '');
                if (num < 200) return "cent" + (num % 100 !== 0 ? " " + convert(num % 100) : "");
                if (num < 1000) return units[Math.floor(num / 100)] + " cent" + (num % 100 !== 0 ? " " + convert(num % 100) : "");
                if (num < 2000) return "mille" + (num % 1000 !== 0 ? " " + convert(num % 1000) : "");
                if (num < 1000000) return convert(Math.floor(num / 1000)) + " mille" + (num % 1000 !== 0 ? " " + convert(num % 1000) : "");
                if (num < 1000000000) return convert(Math.floor(num / 1000000)) + " million" + (Math.floor(num/1000000) > 1 ? "s" : "") + (num % 1000000 !== 0 ? " " + convert(num % 1000000) : "");
                return num.toString();
            }
            
            let result = convert(Math.round(n)).trim();
            return result.charAt(0).toUpperCase() + result.slice(1) + " francs CFA";
        }
    }
}
</script>
@endsection
