@extends('layouts.app')

@push('styles')
<style>
    .signature-pad {
        border: 2px solid #e2e8f0;
        background-color: #ffffff;
        width: 100%;
        height: 150px;
        touch-action: none;
        border-radius: 8px;
    }
    .weight-card {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 8px;
        background: white;
        transition: all 0.2s;
    }
    .weight-card:focus-within {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }
    .weight-card input {
        border: none;
        padding: 4px;
        width: 100%;
        text-align: center;
        font-weight: 700;
        font-size: 14px;
    }
    .weight-card input:focus {
        outline: none;
    }
    .calibre-badge {
        font-size: 9px;
        font-weight: 800;
        padding: 2px 6px;
        border-radius: 4px;
        cursor: pointer;
        display: inline-block;
        text-transform: uppercase;
    }
    .bg-pf { background-color: #eef2ff; color: #4338ca; border: 1px solid #c7d2fe; }
    .bg-gf { background-color: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
    
    /* Clean Financial Row */
    .fin-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .fin-label { font-size: 13px; font-weight: 600; color: #64748b; }
    .fin-value { font-size: 15px; font-weight: 700; color: #1e293b; }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="purchaseInvoiceForm()" x-init="initForm">
    
    {{-- Header --}}
    <div class="mb-8 border-b border-gray-200 pb-5">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Nouvelle Facture d'Achat</h1>
                <p class="mt-1 text-sm text-gray-500">Enregistrement d'un bordereau de 200 poids.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('purchase_invoices.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Annuler
                </a>
            </div>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 text-red-700">
        <p class="font-bold">Erreurs détectées :</p>
        <ul class="list-disc list-inside text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('purchase_invoices.store') }}" method="POST" id="purchaseForm" @submit.prevent="submitForm">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Colonne Gauche: Informations & Poids --}}
            <div class="lg:col-span-2 space-y-8">
                
                {{-- Informations Générales --}}
                <div class="bg-white shadow rounded-lg border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h2 class="text-sm font-bold text-gray-700 uppercase tracking-wider">Informations Générales de l'opération</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Bon N° (Auto)</label>
                                <input type="text" name="bon_no" value="{{ $nextBonNo }}" class="w-full bg-gray-50 border-gray-200 rounded-md font-mono font-bold text-gray-400" readonly>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Date d'opération</label>
                                <input type="date" name="date_invoice" value="{{ date('Y-m-d') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Zone / Préfecture</label>
                                <input type="text" name="zone" list="zones_list" class="w-full border-gray-300 rounded-md" placeholder="Ex: Avé">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Producteur / O.P</label>
                                <input type="text" name="producteur" class="w-full border-gray-300 rounded-md" placeholder="Nom du producteur">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Chauffeur</label>
                                <input type="text" name="chauffeur" list="chauffeurs_list" class="w-full border-gray-300 rounded-md" placeholder="Nom du chauffeur">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Matricule Camion</label>
                                <input type="text" name="code_parcelle_matricule" list="matricules_list" class="w-full border-gray-300 rounded-md uppercase" placeholder="Matricule">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Produit / Fruit</label>
                                <input type="text" name="fruit" list="fruits_list" class="w-full border-gray-300 rounded-md" placeholder="Ex: Ananas Cayenne">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Taux d'avarie (%)</label>
                                <div class="relative">
                                    <input type="number" name="avarie_pct" x-model.number="avariePct" step="0.01" class="w-full border-gray-300 rounded-md pr-10" placeholder="0.00">
                                    <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Relevé de Poids (Tabs for Groups of 50) --}}
                <div class="bg-white shadow rounded-lg border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <h2 class="text-sm font-bold text-gray-700 uppercase tracking-wider">Relevé de Poids (200 cases)</h2>
                        <div class="flex items-center gap-4 text-xs font-bold">
                            <span class="text-indigo-600 bg-indigo-50 px-2 py-1 rounded">Brut: <span x-text="totalWeight().toFixed(2)"></span> kg</span>
                            <span class="text-gray-500 bg-gray-100 px-2 py-1 rounded"><span x-text="filledCount"></span> / 200 cases</span>
                        </div>
                    </div>
                    
                    <div x-data="{ activeGroup: 1 }" class="p-6">
                        {{-- Group Tabs --}}
                        <div class="flex flex-wrap gap-2 mb-6">
                            @foreach([1, 2, 3, 4] as $g)
                            <button type="button" 
                                    @click="activeGroup = {{ $g }}"
                                    :class="activeGroup === {{ $g }} ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                                    class="px-4 py-2 rounded-md text-xs font-bold transition-colors">
                                Groupe {{ $g }} ({{ ($g-1)*50 + 1 }}-{{ $g*50 }})
                            </button>
                            @endforeach
                        </div>

                        {{-- Weight Grid --}}
                        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                            <template x-for="i in 50">
                                @php $index = '((activeGroup - 1) * 50) + (i - 1)'; @endphp
                                <div class="weight-card" :class="calibres[{{ $index }}] === 'GF' ? 'border-amber-200 bg-amber-50/30' : ''">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-[9px] font-bold text-gray-400" x-text="String(Number({{ $index }}) + 1).padStart(3, '0')"></span>
                                        <div class="calibre-badge" 
                                             @click="toggleCalibre({{ $index }})"
                                             :class="calibres[{{ $index }}] === 'PF' ? 'bg-pf' : 'bg-gf'"
                                             x-text="calibres[{{ $index }}]">
                                        </div>
                                    </div>
                                    <input type="number" step="0.01" 
                                           class="bg-transparent"
                                           x-model="weights[{{ $index }}]"
                                           @input="updateStats"
                                           placeholder="0.00">
                                </div>
                            </template>
                        </div>
                        
                        <div class="mt-6 p-4 bg-gray-50 rounded-md flex justify-between items-center">
                            <div class="text-xs font-bold text-gray-500">
                                Sous-total Groupe : <span class="text-gray-900" x-text="groupTotal(activeGroup).toFixed(2)"></span> kg
                            </div>
                            <button type="button" @click="clearGroup(activeGroup)" class="text-xs text-red-600 font-bold hover:underline">Vider ce groupe</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Colonne Droite: Totaux, Crédit & Signature --}}
            <div class="space-y-8">
                
                {{-- Récapitulatif Financier --}}
                <div class="bg-white shadow rounded-lg border border-gray-200 overflow-hidden sticky top-24">
                    <div class="px-6 py-4 border-b border-gray-100 bg-indigo-600">
                        <h2 class="text-sm font-bold text-white uppercase tracking-wider">Récapitulatif & Règlement</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-1">
                            <div class="fin-row">
                                <span class="fin-label">Poids Marchand PF (Net)</span>
                                <span class="fin-value"><span x-text="poidsMarchandPF().toFixed(2)"></span> kg</span>
                            </div>
                            <div class="fin-row">
                                <span class="fin-label">P.U PF</span>
                                <input type="number" name="pu_pf" x-model.number="pu_pf" class="w-24 text-right border-gray-200 rounded p-1 font-bold">
                            </div>
                            <div class="fin-row">
                                <span class="fin-label">Poids Marchand GF (Net)</span>
                                <span class="fin-value"><span x-text="poidsMarchandGF().toFixed(2)"></span> kg</span>
                            </div>
                            <div class="fin-row">
                                <span class="fin-label">P.U GF</span>
                                <input type="number" name="pu_gf" x-model.number="pu_gf" class="w-24 text-right border-gray-200 rounded p-1 font-bold">
                            </div>
                            <div class="fin-row">
                                <span class="fin-label">Prime Biologique / kg</span>
                                <input type="number" name="prime_bio_kg" x-model.number="primeBio" class="w-24 text-right border-gray-200 rounded p-1 font-bold">
                            </div>
                            <div class="fin-row">
                                <span class="fin-label text-red-600">Retrait Crédit</span>
                                <input type="number" name="total_credit" x-model.number="manualCredit" class="w-24 text-right border-gray-200 rounded p-1 font-bold text-red-600">
                            </div>
                            
                            <div class="mt-6 pt-6 border-t-2 border-indigo-600">
                                <div class="flex justify-between items-baseline mb-4">
                                    <span class="text-sm font-black text-gray-900 uppercase">Net à Payer</span>
                                    <span class="text-2xl font-black text-indigo-700" x-text="formatCurrency(netAPayer()) + ' F'"></span>
                                </div>
                                <div class="bg-gray-50 border border-gray-100 rounded-md p-3">
                                    <p class="text-[9px] font-bold text-gray-400 uppercase mb-1">Montant en lettres</p>
                                    <textarea name="net_payer_lettre" x-model="netAPayerLettre" 
                                              class="w-full bg-transparent border-none p-0 text-xs font-bold text-gray-600 italic focus:ring-0" 
                                              rows="2" readonly></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Signatures --}}
                        <div class="mt-8 space-y-6">
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <label class="text-[10px] font-bold text-gray-500 uppercase">Signature Responsable</label>
                                    <button type="button" @click="clearSignature('signature-resp')" class="text-[9px] text-red-500">Effacer</button>
                                </div>
                                <canvas id="signature-resp" class="signature-pad"></canvas>
                                <input type="hidden" name="signature_resp" id="signature_resp_input">
                            </div>
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <label class="text-[10px] font-bold text-gray-500 uppercase">Signature Producteur</label>
                                    <button type="button" @click="clearSignature('signature-prod')" class="text-[9px] text-red-500">Effacer</button>
                                </div>
                                <canvas id="signature-prod" class="signature-pad"></canvas>
                                <input type="hidden" name="signature_prod" id="signature_prod_input">
                            </div>
                        </div>

                        {{-- Boutons --}}
                        <div class="mt-8 space-y-3">
                            <button type="submit" 
                                    class="w-full flex justify-center py-4 px-4 border border-transparent rounded-md shadow-sm text-sm font-black text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 uppercase tracking-widest">
                                Enregistrer la Facture
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="weights_csv" :value="weightsCSV">
        <input type="hidden" name="calibres_csv" :value="calibresCSV">
    </form>
</div>

{{-- Datalists pour l'autocomplete --}}
<datalist id="zones_list">
    <option value="Avé"><option value="Zio"><option value="Vo"><option value="Danyi"><option value="Kloto"><option value="Agou">
</datalist>
<datalist id="chauffeurs_list">
    <option value="SOUMAGBO Yao"><option value="AGBADZI Komi Victor"><option value="AMEGBETO K. Promise">
</datalist>
<datalist id="matricules_list">
    <option value="TG 7151 BL"><option value="TG 7238 BL"><option value="TG 2671 BD">
</datalist>
<datalist id="fruits_list">
    <option value="Ananas Cayenne"><option value="Ananas Braza"><option value="Papaye"><option value="Banane">
</datalist>

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
            },
            
            updateStats() {
                this.filledCount = this.weights.filter(w => parseFloat(w) > 0).length;
            },
            
            toggleCalibre(idx) {
                this.calibres[idx] = this.calibres[idx] === 'PF' ? 'GF' : 'PF';
                this.calibres = [...this.calibres];
            },

            clearGroup(g) {
                if(confirm('Vider les 50 poids du groupe ' + g + ' ?')) {
                    let start = (g-1)*50;
                    for(let i=start; i<start+50; i++) {
                        this.weights[i] = null;
                        this.calibres[i] = 'PF';
                    }
                    this.updateStats();
                }
            },
            
            groupTotal(g) {
                let start = (g-1)*50;
                let sum = 0;
                for(let i=start; i<start+50; i++) sum += parseFloat(this.weights[i]) || 0;
                return sum;
            },

            totalWeight() { return this.weights.reduce((sum, w) => sum + (parseFloat(w) || 0), 0); },
            weightPF() { return this.weights.reduce((sum, w, i) => sum + (parseFloat(w) > 0 && this.calibres[i] === 'PF' ? parseFloat(w) : 0), 0); },
            weightGF() { return this.weights.reduce((sum, w, i) => sum + (parseFloat(w) > 0 && this.calibres[i] === 'GF' ? parseFloat(w) : 0), 0); },
            
            poidsMarchandPF() { return this.weightPF() * (1 - (this.avariePct || 0) / 100); },
            poidsMarchandGF() { return this.weightGF() * (1 - (this.avariePct || 0) / 100); },
            
            montantTotal() {
                return (this.poidsMarchandPF() * (this.pu_pf || 0)) + (this.poidsMarchandGF() * (this.pu_gf || 0));
            },
            totalPrime() { return this.totalWeight() * (this.primeBio || 0); },
            netAPayer() { return Math.max(0, Math.round((this.montantTotal() + this.totalPrime()) - (this.manualCredit || 0))); },
            
            formatCurrency(val) { return new Intl.NumberFormat('fr-FR').format(Math.round(val)); },
            get netAPayerLettre() { 
                let n = this.netAPayer();
                if(!n) return "Zéro franc CFA";
                return n.toLocaleString('fr-FR') + " francs CFA";
            },

            get weightsCSV() { return this.weights.map(w => w || '').join(','); },
            get calibresCSV() { return this.calibres.join(','); },

            submitForm() {
                if (sigPads['signature-resp']) {
                    document.getElementById('signature_resp_input').value = sigPads['signature-resp'].isEmpty() ? '' : sigPads['signature-resp'].toDataURL();
                }
                if (sigPads['signature-prod']) {
                    document.getElementById('signature_prod_input').value = sigPads['signature-prod'].isEmpty() ? '' : sigPads['signature-prod'].toDataURL();
                }
                
                if (this.filledCount === 0) { alert('Veuillez saisir au moins un poids.'); return; }
                document.getElementById('purchaseForm').submit();
            },

            clearSignature(id) {
                if (sigPads[id]) sigPads[id].clear();
            }
        }));
    });

    let sigPads = {};
    window.addEventListener('load', () => {
        ['signature-resp', 'signature-prod'].forEach(id => {
            const canvas = document.getElementById(id);
            if (canvas) {
                sigPads[id] = new SignaturePad(canvas, { backgroundColor: 'rgb(255, 255, 255)' });
                // Resize for display pixel ratio
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
            }
        });
    });
</script>
@endpush
@endsection