@extends('layouts.app')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
    
    .signature-pad {
        border: 1px dashed #e2e8f0;
        background-color: #ffffff;
        width: 100%;
        height: 120px;
        touch-action: none;
        border-radius: 4px;
    }
    
    .weight-input {
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        text-align: center;
        width: 100%;
        padding: 4px 2px;
        font-weight: 800;
        color: #1e293b;
        font-size: 13px;
        transition: all 0.2s;
    }
    
    .weight-input:focus {
        border-color: #4f46e5;
        background-color: #f8fafc;
        outline: none;
        ring: 0;
    }

    .form-field-box {
        border-bottom: 1.5px solid #cbd5e1;
        background: transparent;
    }
    
    .form-field-box:focus-within {
        border-bottom-color: #4f46e5;
    }

    .mini-table-header {
        background-color: #1a202c;
        color: white;
        font-size: 9px;
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
        width: 30%;
        background-color: #f8fafc;
        border-right: 1px solid #f1f5f9;
        font-size: 9px;
        font-weight: 700;
        color: #3182ce;
        padding: 4px;
        text-align: center;
    }

    .mini-table-value {
        width: 70%;
        padding: 2px;
    }

    .accordion-header {
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 16px;
        background: white;
        border: 1px solid #f1f5f9;
        border-left: 4px solid #1a202c;
        transition: all 0.2s;
    }

    .summary-box {
        border: 1.5px solid #1a202c;
        border-radius: 4px;
        overflow: hidden;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 12px;
        border-bottom: 0.5px solid #e2e8f0;
    }

    .summary-row:last-child {
        border-bottom: none;
    }

    .summary-label {
        font-size: 10px;
        font-weight: bold;
        text-transform: uppercase;
        color: #4a5568;
    }

    .summary-val {
        font-size: 13px;
        font-weight: 1000;
        color: #1a202c;
    }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6" x-data="purchaseInvoiceForm()" x-init="initForm">
    
    <div class="bg-white shadow-2xl rounded-lg border border-slate-200 overflow-hidden">
        
        {{-- HEADER --}}
        <div class="p-6 md:p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="h-16 w-auto object-contain">
                <div>
                    <h1 class="text-2xl font-black text-emerald-600 tracking-tighter uppercase">Bio Farm Trading</h1>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest italic">Production - Commercialisation de produits agricoles biologiques</p>
                </div>
            </div>
            
            <div class="flex items-center gap-8">
                <div class="text-right">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Bon N°</p>
                    <div class="text-xl font-black text-slate-900 border-b-2 border-slate-900 px-2 italic">{{ $nextBonNo }}</div>
                </div>
                <div>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Date d'opération</p>
                    <input type="date" name="date_invoice" value="{{ date('Y-m-d') }}" 
                           class="bg-slate-50 border border-slate-900 rounded px-3 py-1.5 text-sm font-black text-slate-700">
                </div>
            </div>
        </div>

        <div class="px-6 md:px-8 pb-8">
            <form action="{{ route('purchase_invoices.store') }}" method="POST" id="mainForm" @submit.prevent="submitForm">
                @csrf
                <input type="hidden" name="bon_no" value="{{ $nextBonNo }}">
                
                {{-- INFO GRID --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6 bg-slate-50/50 p-6 rounded-xl border border-slate-200">
                    <div class="form-field-box">
                        <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Zone</label>
                        <input type="text" name="zone" list="zones_list" class="w-full bg-transparent border-none p-0 text-sm font-bold text-slate-800 focus:ring-0" placeholder="...">
                    </div>
                    <div class="form-field-box">
                        <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Producteur</label>
                        <input type="text" name="producteur" class="w-full bg-transparent border-none p-0 text-sm font-bold text-slate-800 focus:ring-0" placeholder="...">
                    </div>
                    <div class="form-field-box">
                        <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Chauffeur</label>
                        <input type="text" name="chauffeur" list="chauffeurs_list" class="w-full bg-transparent border-none p-0 text-sm font-bold text-slate-800 focus:ring-0" placeholder="...">
                    </div>
                    <div class="form-field-box">
                        <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Matricule</label>
                        <input type="text" name="code_parcelle_matricule" list="matricules_list" class="w-full bg-transparent border-none p-0 text-sm font-bold text-slate-800 focus:ring-0 uppercase" placeholder="...">
                    </div>
                    <div class="form-field-box">
                        <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Fruit</label>
                        <input type="text" name="fruit" list="fruits_list" class="w-full bg-transparent border-none p-0 text-sm font-bold text-slate-800 focus:ring-0" placeholder="...">
                    </div>
                    <div class="form-field-box">
                        <label class="block text-[9px] font-black text-orange-400 uppercase tracking-widest mb-0.5">% Avarie</label>
                        <input type="number" step="0.01" name="avarie_pct" x-model.number="avariePct" class="w-full bg-transparent border-none p-0 text-sm font-black text-orange-600 focus:ring-0" placeholder="0.00">
                    </div>
                </div>

                {{-- WEIGHTS --}}
                <div class="mt-8 bg-slate-900 text-white py-2 text-center text-xs font-black uppercase tracking-[1em]">Relevé de Poids</div>
                
                <div class="mt-4 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-2">
                    <template x-for="i in 200">
                        <div class="border border-slate-200 rounded overflow-hidden">
                            <div class="mini-table-header" x-text="String(i).padStart(3, '0')"></div>
                            <div class="flex items-center p-1 bg-white relative">
                                <input type="number" step="0.01" x-model="weights[i-1]" class="w-full text-center text-xs font-black border-none focus:ring-0 p-0 h-6" placeholder="0">
                                <div @click="toggleCalibre(i-1)" class="absolute right-0.5 top-0.5 cursor-pointer">
                                    <span class="text-[6px] font-black px-0.5 rounded border" :class="calibres[i-1] === 'PF' ? 'border-blue-200 text-blue-500' : 'border-orange-200 text-orange-500 bg-orange-50'" x-text="calibres[i-1]"></span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="mt-4 p-2 bg-slate-50 border border-slate-200 rounded flex justify-between items-center px-4">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Poids Total Brut : <span class="text-emerald-600 text-sm ml-2" x-text="totalWeight().toFixed(2)"></span> kg</span>
                    <button type="button" @click="clearAll" class="text-[9px] font-black text-red-500 uppercase tracking-widest hover:underline">Vider le bordereau</button>
                </div>

                {{-- FINANCIALS (2 COLUMNS) --}}
                <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-10">
                    {{-- Left Column --}}
                    <div class="summary-box">
                        <div class="summary-row">
                            <span class="summary-label">Poids Total</span>
                            <span class="summary-val" x-text="totalWeight().toFixed(2) + ' kg'"></span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">P.U PF</span>
                            <div class="flex items-baseline gap-2">
                                <input type="number" name="pu_pf" x-model.number="pu_pf" class="w-20 text-right text-sm font-black text-blue-600 border-none focus:ring-0 p-0 bg-transparent">
                                <span class="text-[8px] text-slate-300">FCFA/kg</span>
                            </div>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">P.U GF</span>
                            <div class="flex items-baseline gap-2">
                                <input type="number" name="pu_gf" x-model.number="pu_gf" class="w-20 text-right text-sm font-black text-orange-600 border-none focus:ring-0 p-0 bg-transparent">
                                <span class="text-[8px] text-slate-300">FCFA/kg</span>
                            </div>
                        </div>
                        <div class="summary-row bg-slate-50">
                            <span class="summary-label">Montant Total Fruits</span>
                            <span class="summary-val text-emerald-600" x-text="formatCurrency(montantTotalFruits()) + ' FCFA'"></span>
                        </div>
                        <div class="summary-row bg-blue-50 border-t-2 border-blue-600">
                            <span class="summary-label text-blue-700">Net à Payer</span>
                            <span class="summary-val text-blue-700 text-xl" x-text="formatCurrency(netAPayer()) + ' FCFA'"></span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Prime Bio/kg</span>
                            <div class="flex items-baseline gap-2">
                                <input type="number" name="prime_bio_kg" x-model.number="primeBio" class="w-20 text-right text-sm font-black text-emerald-600 border-none focus:ring-0 p-0 bg-transparent">
                                <span class="text-[8px] text-slate-300">FCFA/kg</span>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="summary-box">
                        <div class="summary-row">
                            <span class="summary-label">Poids Marchand Petit Fruit</span>
                            <span class="summary-val text-blue-600" x-text="poidsMarchandPF().toFixed(2) + ' kg'"></span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Poids Marchand Gros Fruit</span>
                            <span class="summary-val text-orange-600" x-text="poidsMarchandGF().toFixed(2) + ' kg'"></span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label text-red-600">Total Crédit</span>
                            <div class="flex items-baseline gap-2">
                                <input type="number" name="total_credit" x-model.number="manualCredit" class="w-20 text-right text-sm font-black text-red-600 border-none focus:ring-0 p-0 bg-transparent">
                                <span class="text-[8px] text-slate-300">FCFA</span>
                            </div>
                        </div>
                        <div class="letter-box">
                            <span class="text-[8px] font-black text-slate-300 uppercase block mb-1">Net à payer en lettre :</span>
                            <textarea name="net_payer_lettre" x-model="netAPayerLettre" class="w-full text-[10px] font-black italic bg-transparent border-none focus:ring-0 p-0" rows="2" readonly></textarea>
                        </div>
                        <div class="summary-row bg-emerald-50 mt-auto">
                            <span class="summary-label text-emerald-700">Montant Total de la Prime</span>
                            <span class="summary-val text-emerald-700" x-text="formatCurrency(totalPrime()) + ' FCFA'"></span>
                        </div>
                    </div>
                </div>

                {{-- SIGNATURES --}}
                <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="border border-dashed border-slate-200 rounded-lg p-4 text-center">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-4">A2C OFCA / Responsable</span>
                        <canvas id="signature-resp" class="signature-pad"></canvas>
                        <input type="hidden" name="signature_resp" id="signature_resp_input">
                        <button type="button" @click="clearSignature('signature-resp')" class="text-[8px] text-red-400 mt-2 uppercase font-black">Effacer</button>
                    </div>
                    <div class="border border-dashed border-slate-200 rounded-lg p-4 text-center">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-4">Le Producteur</span>
                        <canvas id="signature-prod" class="signature-pad"></canvas>
                        <input type="hidden" name="signature_prod" id="signature_prod_input">
                        <button type="button" @click="clearSignature('signature-prod')" class="text-[8px] text-red-400 mt-2 uppercase font-black">Effacer</button>
                    </div>
                </div>

                <div class="mt-10 flex gap-4 pt-8 border-t border-slate-100">
                    <button type="submit" class="flex-1 bg-emerald-600 text-white py-4 rounded-xl font-black uppercase tracking-widest shadow-xl shadow-emerald-100 hover:bg-emerald-700 transition">Enregistrer la Facture</button>
                    <a href="{{ route('purchase_invoices.index') }}" class="px-10 py-4 bg-slate-100 text-slate-600 rounded-xl font-black uppercase tracking-widest hover:bg-slate-200 transition text-center">Annuler</a>
                </div>

                <input type="hidden" name="weights_csv" :value="weightsCSV">
                <input type="hidden" name="calibres_csv" :value="calibresCSV">
            </form>
        </div>
    </div>
</div>

<datalist id="zones_list"><option value="Avé"><option value="Zio"><option value="Vo"><option value="Danyi"></datalist>
<datalist id="chauffeurs_list"><option value="SOUMAGBO Yao"><option value="AGBADZI Komi Victor"></datalist>
<datalist id="matricules_list"><option value="TG 7151 BL"><option value="TG 7238 BL"></datalist>
<datalist id="fruits_list"><option value="Ananas Cayenne"><option value="Ananas Braza"></datalist>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('purchaseInvoiceForm', () => ({
            weights: Array(200).fill(null),
            calibres: Array(200).fill('PF'),
            pu_pf: 0,
            pu_gf: 0,
            avariePct: 0,
            manualCredit: 0,
            primeBio: 0,
            
            initForm() {},
            
            toggleCalibre(idx) {
                this.calibres[idx] = this.calibres[idx] === 'PF' ? 'GF' : 'PF';
                this.calibres = [...this.calibres];
            },

            clearAll() {
                if(confirm('Vider tout le bordereau ?')) {
                    this.weights = Array(200).fill(null);
                    this.calibres = Array(200).fill('PF');
                    document.querySelectorAll('.signature-pad').forEach(c => sigPads[c.id].clear());
                }
            },

            totalWeight() { return this.weights.reduce((s, w) => s + (parseFloat(w) || 0), 0); },
            weightPF() { return this.weights.reduce((sum, w, i) => sum + (this.calibres[i] === 'PF' ? (parseFloat(w) || 0) : 0), 0); },
            weightGF() { return this.weights.reduce((sum, w, i) => sum + (this.calibres[i] === 'GF' ? (parseFloat(w) || 0) : 0), 0); },
            
            poidsMarchandPF() { return this.weightPF() * (1 - (this.avariePct || 0) / 100); },
            poidsMarchandGF() { return this.weightGF() * (1 - (this.avariePct || 0) / 100); },
            
            montantTotalFruits() {
                return (this.poidsMarchandPF() * (this.pu_pf || 0)) + (this.poidsMarchandGF() * (this.pu_gf || 0));
            },
            totalPrime() { return this.totalWeight() * (this.primeBio || 0); },
            netAPayer() { 
                return Math.round((this.montantTotalFruits() + this.totalPrime()) - (this.manualCredit || 0));
            },
            
            formatCurrency(val) { return new Intl.NumberFormat('fr-FR').format(Math.round(val)); },
            get netAPayerLettre() { 
                let n = this.netAPayer();
                return n > 0 ? n.toLocaleString('fr-FR') + " francs CFA" : "—";
            },

            get weightsCSV() { return this.weights.map(w => w || '').join(','); },
            get calibresCSV() { return this.calibres.join(','); },

            submitForm() {
                if (sigPads['signature-resp']) document.getElementById('signature_resp_input').value = sigPads['signature-resp'].isEmpty() ? '' : sigPads['signature-resp'].toDataURL();
                if (sigPads['signature-prod']) document.getElementById('signature_prod_input').value = sigPads['signature-prod'].isEmpty() ? '' : sigPads['signature-prod'].toDataURL();
                document.getElementById('mainForm').submit();
            },

            clearSignature(id) { sigPads[id].clear(); }
        }));
    });

    let sigPads = {};
    window.addEventListener('load', () => {
        ['signature-resp', 'signature-prod'].forEach(id => {
            const canvas = document.getElementById(id);
            if (canvas) {
                sigPads[id] = new SignaturePad(canvas, { backgroundColor: 'rgb(255, 255, 255)' });
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