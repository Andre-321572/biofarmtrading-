@extends('layouts.app')

@push('styles')
<style>
    .signature-pad {
        border: 1px solid #e2e8f0;
        background-color: #ffffff;
        width: 100%;
        height: 120px;
        touch-action: none;
        border-radius: 8px;
    }
    button[type="submit"] {
        pointer-events: auto !important;
        cursor: pointer !important;
    }
    .weight-input {
        transition: all 0.2s;
    }
    .weight-input:focus {
        outline: none;
        ring: 2px solid #3b82f6;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-slate-100 py-5" 
     x-data="purchaseInvoiceForm()" 
     x-init="initForm">
    
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

        {{-- MESSAGES D'ERREUR --}}
        @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 rounded-xl px-4 py-3">
            <p class="text-xs font-bold text-red-700 mb-1">Erreurs :</p>
            @foreach($errors->all() as $err)
                <p class="text-xs text-red-600">• {{ $err }}</p>
            @endforeach
        </div>
        @endif

        @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 rounded-xl px-4 py-3">
            <p class="text-xs text-red-600">{{ session('error') }}</p>
        </div>
        @endif

        {{-- FORMULAIRE --}}
        <form action="{{ route('purchase_invoices.store') }}" method="POST" id="mainForm">
            @csrf

            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden" style="font-family:'Courier New',monospace">
                
                {{-- HEADER --}}
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
                            <input type="hidden" name="bon_no" value="{{ $nextBonNo }}">
                            <span class="text-base sm:text-xl font-black text-slate-900">{{ $nextBonNo }}</span>
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
                    <!-- Zone -->
                    <div class="flex bg-white">
                        <label class="bg-slate-50 px-3 py-3 text-[9px] font-bold uppercase text-slate-400 w-24 sm:w-32 flex items-center shrink-0 border-r border-slate-100">ZONE</label>
                        <input type="text" name="zone" list="zones_list" class="flex-1 px-3 py-2 text-sm font-bold border-0 focus:ring-0" placeholder="...">
                        <datalist id="zones_list">
                            <option value="Avé"><option value="Zio"><option value="Vo"><option value="Danyi">
                            <option value="Kloto"><option value="Agou"><option value="Haho"><option value="Bas-mono">
                        </datalist>
                    </div>
                    <!-- Producteur -->
                    <div class="flex bg-white">
                        <label class="bg-slate-50 px-3 py-3 text-[9px] font-bold uppercase text-slate-400 w-24 sm:w-32 flex items-center shrink-0 border-r border-slate-100">PRODUCTEUR</label>
                        <input type="text" name="producteur" class="flex-1 px-3 py-2 text-sm font-bold border-0 focus:ring-0" placeholder="...">
                    </div>
                    <!-- Chauffeur -->
                    <div class="flex bg-white">
                        <label class="bg-slate-50 px-3 py-3 text-[9px] font-bold uppercase text-slate-400 w-24 sm:w-32 flex items-center shrink-0 border-r border-slate-100">CHAUFFEUR</label>
                        <input type="text" name="chauffeur" list="chauffeurs_list" class="flex-1 px-3 py-2 text-sm font-bold border-0 focus:ring-0" placeholder="...">
                        <datalist id="chauffeurs_list">
                            <option value="SOUMAGBO Yao"><option value="AGBADZI Komi Victor">
                            <option value="AMEGBETO K. Promise"><option value="MORKLEY Komi">
                        </datalist>
                    </div>
                    <!-- Matricule -->
                    <div class="flex bg-white">
                        <label class="bg-slate-50 px-3 py-3 text-[9px] font-bold uppercase text-slate-400 w-24 sm:w-32 flex items-center shrink-0 border-r border-slate-100 uppercase">MATRICULE</label>
                        <input type="text" name="code_parcelle_matricule" list="matricules_list" class="flex-1 px-3 py-2 text-sm font-bold border-0 focus:ring-0 uppercase" placeholder="...">
                        <datalist id="matricules_list">
                            <option value="BL 7151"><option value="BL 7238"><option value="BD 2671">
                            <option value="BH 5895"><option value="BH 5588"><option value="EL 2473">
                        </datalist>
                    </div>
                    <!-- Fruit -->
                    <div class="flex bg-white">
                        <label class="bg-slate-50 px-3 py-3 text-[9px] font-bold uppercase text-slate-400 w-24 sm:w-32 flex items-center shrink-0 border-r border-slate-100">FRUIT</label>
                        <input type="text" name="fruit" list="fruits_list" class="flex-1 px-3 py-2 text-sm font-bold border-0 focus:ring-0" placeholder="...">
                        <datalist id="fruits_list">
                            <option value="Ananas Cayenne"><option value="Ananas Braza">
                            <option value="Papaye"><option value="Banane"><option value="Mangue">
                        </datalist>
                    </div>

                    <!-- % Avarie -->
                    <div class="flex bg-white border border-orange-200">
                        <label class="bg-orange-50 px-3 py-3 text-[9px] font-bold uppercase text-orange-500 w-24 sm:w-32 flex items-center shrink-0 border-r border-orange-200">
                            <i class="fa-solid fa-triangle-exclamation mr-1 text-orange-400"></i> % AVARIE
                        </label>
                        <div class="flex-1 flex items-center px-3">
                            <input type="number" name="avarie_pct" x-model="avariePct"
                                   step="0.01" min="0" max="100"
                                   class="flex-1 py-2 text-sm font-black text-orange-600 border-0 focus:ring-0 bg-transparent"
                                   placeholder="0.00">
                            <span class="ml-1 text-sm font-black text-orange-500">%</span>
                        </div>
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

                {{-- STATS POIDS --}}
                <div class="flex items-center justify-between px-5 py-2 bg-slate-100 border-b border-slate-300">
                    <div class="text-[10px] font-black text-slate-500 uppercase flex gap-4">
                        <span>Cases : <span class="text-indigo-600" x-text="filledCount"></span>/200</span>
                        <span>Total : <span class="text-green-600" x-text="totalWeight().toFixed(2)+' kg'"></span></span>
                    </div>
                </div>

                {{-- GROUPES DE POIDS --}}
                @php
                    $groups = [
                        ['num'=>1,'color'=>'blue',  'from'=>1,  'to'=>50,  'bg'=>'bg-blue-600',  'light'=>'bg-blue-50',  'border'=>'border-blue-200', 'text'=>'text-blue-700'],
                        ['num'=>2,'color'=>'emerald','from'=>51, 'to'=>100, 'bg'=>'bg-emerald-600','light'=>'bg-emerald-50','border'=>'border-emerald-200','text'=>'text-emerald-700'],
                        ['num'=>3,'color'=>'amber',  'from'=>101,'to'=>150, 'bg'=>'bg-amber-500',  'light'=>'bg-amber-50',  'border'=>'border-amber-200', 'text'=>'text-amber-700'],
                        ['num'=>4,'color'=>'purple', 'from'=>151,'to'=>200, 'bg'=>'bg-purple-600', 'light'=>'bg-purple-50', 'border'=>'border-purple-200','text'=>'text-purple-700'],
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
                            <span class="text-xs font-black {{ $g['text'] }} truncate">Groupe {{ $g['num'] }} ({{ $g['from'] }}–{{ $g['to'] }})</span>
                            
                            <select class="text-[9px] font-black border-0 focus:ring-0 py-0.5 px-2 rounded bg-slate-50 ml-4"
                                    @change="setGrpCalibre({{ $offset }}, $event.target.value)">
                                <option value="PF">PF</option>
                                <option value="GF">GF</option>
                            </select>

                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold {{ $g['badge'] ?? 'bg-slate-100' }} ml-2">
                                <span x-text="grpFilled({{ $offset }})"></span>/50
                                <span x-text="grpTotal({{ $offset }}).toFixed(1)"></span>kg
                            </span>
                        </div>
                        <i class="fa-solid fa-chevron-down transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    
                    <div x-show="open" class="p-3 bg-slate-50">
                        <div class="grid grid-cols-5 gap-2">
                            @for($col=0; $col<5; $col++)
                            @php $colOffset = $offset + $col*10; @endphp
                            <div class="bg-white rounded border border-slate-200">
                                <div class="bg-blue-600 text-white text-center py-1 text-[10px] font-bold">
                                    {{ $colOffset+1 }}-{{ $colOffset+10 }}
                                </div>
                                @for($row=0; $row<10; $row++)
                                @php $idx = $colOffset + $row; @endphp
                                <div class="flex items-center p-1 border-b border-slate-100 last:border-0">
                                    <span class="w-8 text-[9px] font-bold text-slate-400">{{ str_pad($idx+1, 3, '0', STR_PAD_LEFT) }}</span>
                                    
                                    {{-- Input de poids avec liaison bidirectionnelle Alpine et nom pour PHP --}}
                                    <input type="number" 
                                           step="0.01" 
                                           name="weights[{{ $idx }}]"
                                           x-model="weights[{{ $idx }}]"
                                           @input="updateStats"
                                           class="w-16 text-center text-[10px] font-bold border rounded py-1 weight-input"
                                           :class="calibres[{{ $idx }}] === 'GF' ? 'border-orange-300 bg-orange-50' : 'border-blue-200'">
                                    
                                    {{-- Bouton pour changer le calibre --}}
                                    <button type="button"
                                            @click="toggleCalibre({{ $idx }})"
                                            class="ml-1 px-1.5 py-0.5 text-[8px] font-black rounded"
                                            :class="calibres[{{ $idx }}] === 'GF' ? 'bg-orange-600 text-white' : 'bg-blue-600 text-white'">
                                        <span x-text="calibres[{{ $idx }}]"></span>
                                    </button>
                                    
                                    {{-- Hidden pour envoyer le calibre au serveur --}}
                                    <input type="hidden" 
                                           name="calibres[{{ $idx }}]" 
                                           :value="calibres[{{ $idx }}]">
                                </div>
                                @endfor
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>
                @endforeach
                </div>

                {{-- TOTAUX --}}
                <div class="grid grid-cols-1 md:grid-cols-2 bg-slate-200 gap-[1px] border-t-2 border-slate-800">
                    <!-- Colonne gauche -->
                    <div class="bg-white p-4">
                        <div class="flex justify-between mb-2">
                            <span class="text-[10px] font-bold uppercase text-slate-500">POIDS TOTAL</span>
                            <span class="font-black" x-text="totalWeight().toFixed(2) + ' kg'"></span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-[10px] font-bold uppercase text-indigo-600">P.U PF</span>
                            <input type="number" name="pu_pf" x-model="pu_pf" class="w-24 text-right font-black border-0 focus:ring-0 text-sm">
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-[10px] font-bold uppercase text-amber-600">P.U GF</span>
                            <input type="number" name="pu_gf" x-model="pu_gf" class="w-24 text-right font-black border-0 focus:ring-0 text-sm">
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-[10px] font-bold uppercase text-green-600">MONTANT</span>
                            <span class="font-black" x-text="formatCurrency(montantTotal()) + ' FCFA'"></span>
                        </div>
                        <div class="flex justify-between bg-green-50 p-2 rounded">
                            <span class="text-xs font-black uppercase text-green-800">NET À PAYER</span>
                            <span class="font-black text-green-600" x-text="formatCurrency(netAPayer()) + ' FCFA'"></span>
                        </div>
                    </div>

                    <!-- Colonne droite -->
                    <div class="bg-white p-4">
                        <div class="flex justify-between mb-2">
                            <span class="text-[10px] font-bold uppercase text-indigo-600">Poids marchand PF</span>
                            <span class="font-black" x-text="poidsMarchandPF().toFixed(2) + ' kg'"></span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-[10px] font-bold uppercase text-amber-600">Poids marchand GF</span>
                            <span class="font-black" x-text="poidsMarchandGF().toFixed(2) + ' kg'"></span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-[10px] font-bold uppercase text-red-600">CRÉDIT</span>
                            <input type="number" name="total_credit" x-model="manualCredit" class="w-24 text-right font-black border-0 focus:ring-0 text-sm">
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-[10px] font-bold uppercase text-indigo-600">PRIME BIO/KG</span>
                            <input type="number" name="prime_bio_kg" x-model="primeBio" class="w-24 text-right font-black border-0 focus:ring-0 text-sm">
                        </div>
                        <div class="mt-4 p-2 bg-slate-50 rounded">
                            <div class="text-[8px] font-bold uppercase text-slate-400 mb-1">En lettres :</div>
                            <textarea name="net_payer_lettre" x-model="netAPayerLettre" class="w-full text-[9px] font-bold italic border-0 bg-transparent resize-none" rows="2" readonly></textarea>
                        </div>
                    </div>
                </div>

                {{-- SIGNATURES --}}
                <div class="grid grid-cols-1 md:grid-cols-2 divide-x divide-slate-200">
                    <div class="p-4">
                        <p class="text-[9px] font-bold uppercase text-slate-500 mb-2">Responsable</p>
                        <canvas id="signature-resp" class="signature-pad"></canvas>
                        <input type="hidden" name="signature_resp" id="signature_resp_input">
                        <button type="button" @click="clearSignature('signature-resp')" class="text-[8px] text-red-500 mt-1">Effacer</button>
                    </div>
                    <div class="p-4">
                        <p class="text-[9px] font-bold uppercase text-slate-500 mb-2">Producteur</p>
                        <canvas id="signature-prod" class="signature-pad"></canvas>
                        <input type="hidden" name="signature_prod" id="signature_prod_input">
                        <button type="button" @click="clearSignature('signature-prod')" class="text-[8px] text-red-500 mt-1">Effacer</button>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('purchase_invoices.index') }}" class="px-6 py-2.5 bg-white border border-slate-300 rounded-xl text-sm font-semibold hover:bg-slate-50">Annuler</a>
                <button type="submit" class="px-8 py-2.5 bg-indigo-600 rounded-xl text-sm font-black text-white hover:bg-indigo-700 shadow-lg">
                    <i class="fa-solid fa-check mr-2"></i>Enregistrer
                </button>
            </div>

            <input type="hidden" name="weights_csv" :value="weightsCSV">
            <input type="hidden" name="calibres_csv" :value="calibresCSV">
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('purchaseInvoiceForm', () => ({
            // Données
            weights: Array(200).fill(null),
            calibres: Array(200).fill('PF'),
            pu_pf: 0,
            pu_gf: 0,
            manualCredit: 0,
            primeBio: 0,
            avariePct: 0,
            filledCount: 0,
            
            // Méthode d'initialisation
            initForm() {
                this.updateStats();
                
                // Watchers
                this.$watch('weights', () => this.updateStats(), { deep: true });
                this.$watch('calibres', () => this.updateStats(), { deep: true });
                this.$watch('pu_pf', () => this.updateStats());
                this.$watch('pu_gf', () => this.updateStats());
                this.$watch('manualCredit', () => this.updateStats());
                this.$watch('primeBio', () => this.updateStats());
                this.$watch('avariePct', () => this.updateStats());
            },
            
            // Mise à jour des statistiques
            updateStats() {
                this.filledCount = this.weights.filter(w => parseFloat(w) > 0).length;
            },
            
            // Getter pour weightsCSV
            get weightsCSV() {
                return this.weights.map(w => w || '').join(',');
            },
            
            // Getter pour calibresCSV
            get calibresCSV() {
                return this.calibres.join(',');
            },
            
            // Getter pour netAPayerLettre
            get netAPayerLettre() {
                return this.numberToWords(this.netAPayer());
            },
            
            // Changer le calibre
            toggleCalibre(idx) {
                this.calibres[idx] = this.calibres[idx] === 'PF' ? 'GF' : 'PF';
                this.calibres = [...this.calibres];
            },
            
            // Changer tout un groupe
            setGrpCalibre(offset, val) {
                for (let i = offset; i < offset + 50; i++) {
                    this.calibres[i] = val;
                }
                this.calibres = [...this.calibres];
            },
            
            // Effacer une signature
            clearSignature(id) {
                if (sigPads[id]) sigPads[id].clear();
            },
            
            // Calculs
            totalWeight() {
                return this.weights.reduce((sum, w) => sum + (parseFloat(w) || 0), 0);
            },
            
            weightPF() {
                return this.weights.reduce((sum, w, i) => 
                    sum + (parseFloat(w) > 0 && this.calibres[i] === 'PF' ? parseFloat(w) : 0), 0);
            },
            
            weightGF() {
                return this.weights.reduce((sum, w, i) => 
                    sum + (parseFloat(w) > 0 && this.calibres[i] === 'GF' ? parseFloat(w) : 0), 0);
            },
            
            poidsMarchandPF() {
                return this.weightPF() * (1 - (this.avariePct || 0) / 100);
            },
            
            poidsMarchandGF() {
                return this.weightGF() * (1 - (this.avariePct || 0) / 100);
            },
            
            poidsAvarieCalc() {
                return this.totalWeight() * (this.avariePct || 0) / 100;
            },
            
            poidsMarchandCalc() {
                return this.totalWeight() - this.poidsAvarieCalc();
            },
            
            grpTotal(offset) {
                let sum = 0;
                for (let i = offset; i < offset + 50; i++) {
                    sum += parseFloat(this.weights[i]) || 0;
                }
                return sum;
            },
            
            grpFilled(offset) {
                let count = 0;
                for (let i = offset; i < offset + 50; i++) {
                    if (parseFloat(this.weights[i]) > 0) count++;
                }
                return count;
            },
            
            montantTotal() {
                return (this.poidsMarchandPF() * (this.pu_pf || 0)) + 
                       (this.poidsMarchandGF() * (this.pu_gf || 0));
            },
            
            totalPrime() {
                return this.totalWeight() * (this.primeBio || 0);
            },
            
            netAPayer() {
                return Math.round((this.montantTotal() + this.totalPrime()) - (this.manualCredit || 0));
            },
            
            formatCurrency(val) {
                return new Intl.NumberFormat('fr-FR').format(Math.round(val));
            },
            
            numberToWords(n) {
                if (!n || n <= 0) return "";
                // Version simplifiée pour l'exemple
                return n.toString() + " francs CFA";
            }
        }));
    });

    // Signature pads
    let sigPads = {};

    function resizeCanvas() {
        ['signature-resp', 'signature-prod'].forEach(id => {
            const canvas = document.getElementById(id);
            if (!canvas || !sigPads[id]) return;
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            const w = canvas.offsetWidth;
            const h = canvas.offsetHeight;
            if (canvas.width !== w * ratio || canvas.height !== h * ratio) {
                canvas.width = w * ratio;
                canvas.height = h * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
                sigPads[id].clear();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Initialiser les signatures
        ['signature-resp', 'signature-prod'].forEach(id => {
            const canvas = document.getElementById(id);
            if (canvas) {
                sigPads[id] = new SignaturePad(canvas, { backgroundColor: 'rgb(255, 255, 255)' });
            }
        });
        resizeCanvas();
        window.addEventListener("resize", resizeCanvas);
        
        // Gestionnaire de soumission
        const form = document.getElementById('mainForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                // Récupérer les signatures
                if (sigPads['signature-resp']) {
                    document.getElementById('signature_resp_input').value = 
                        sigPads['signature-resp'].isEmpty() ? '' : sigPads['signature-resp'].toDataURL();
                }
                if (sigPads['signature-prod']) {
                    document.getElementById('signature_prod_input').value = 
                        sigPads['signature-prod'].isEmpty() ? '' : sigPads['signature-prod'].toDataURL();
                }
                
                // Vérifier les poids
                const weightInputs = document.querySelectorAll('input[name^="weights"]');
                let hasWeight = false;
                weightInputs.forEach(input => {
                    if (parseFloat(input.value) > 0) {
                        hasWeight = true;
                    }
                });
                
                if (!hasWeight) {
                    e.preventDefault();
                    alert('ERREUR : Vous n\'avez saisi aucun poids !');
                    return false;
                }
            });
        }
    });
</script>
@endpush
@endsection