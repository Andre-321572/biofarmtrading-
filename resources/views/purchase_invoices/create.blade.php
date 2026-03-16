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
        border-left: 4px solid #3b82f6;
        transition: all 0.2s;
    }

    .accordion-header:hover {
        background: #f8fafc;
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6" x-data="purchaseInvoiceForm()" x-init="initForm">
    
    {{-- MAIN CARD --}}
    <div class="bg-white shadow-xl rounded-lg border border-slate-100 overflow-hidden">
        
        {{-- HEADER: LOGO & DATE/BON --}}
        <div class="p-6 md:p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
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
                    <div class="text-lg font-black text-slate-900 border-b-2 border-slate-200 px-2 italic">{{ $nextBonNo }}</div>
                </div>
                <div>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Date</p>
                    <div class="relative">
                        <input type="date" name="date_invoice" value="{{ date('Y-m-d') }}" 
                               class="bg-slate-50 border border-slate-200 rounded px-3 py-1 text-sm font-black text-slate-700 focus:ring-0 focus:border-indigo-500 transition shadow-sm">
                    </div>
                </div>
            </div>
        </div>

        {{-- FORM GRID (2x3) --}}
        <div class="px-6 md:px-8 pb-8">
            <form action="{{ route('purchase_invoices.store') }}" method="POST" id="mainForm" @submit.prevent="submitForm">
                @csrf
                <input type="hidden" name="bon_no" value="{{ $nextBonNo }}">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-slate-50/30 p-4 rounded-xl border border-slate-100">
                    {{-- Row 1 --}}
                    <div class="form-field-box">
                        <label class="block text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mb-0.5">Zone</label>
                        <input type="text" name="zone" list="zones_list" class="w-full bg-transparent border-none p-0 text-sm font-bold text-slate-800 focus:ring-0 placeholder:text-slate-200" placeholder="...">
                    </div>
                    <div class="form-field-box">
                        <label class="block text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mb-0.5">Producteur</label>
                        <input type="text" name="producteur" class="w-full bg-transparent border-none p-0 text-sm font-bold text-slate-800 focus:ring-0 placeholder:text-slate-200" placeholder="...">
                    </div>
                    <div class="form-field-box">
                        <label class="block text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mb-0.5">Chauffeur</label>
                        <input type="text" name="chauffeur" list="chauffeurs_list" class="w-full bg-transparent border-none p-0 text-sm font-bold text-slate-800 focus:ring-0 placeholder:text-slate-200" placeholder="...">
                    </div>
                    
                    {{-- Row 2 --}}
                    <div class="form-field-box">
                        <label class="block text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mb-0.5">Fruit</label>
                        <input type="text" name="fruit" list="fruits_list" class="w-full bg-transparent border-none p-0 text-sm font-bold text-slate-800 focus:ring-0 placeholder:text-slate-200" placeholder="...">
                    </div>
                    <div class="form-field-box">
                        <label class="block text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mb-0.5">Matricule</label>
                        <input type="text" name="code_parcelle_matricule" list="matricules_list" class="w-full bg-transparent border-none p-0 text-sm font-bold text-slate-800 focus:ring-0 placeholder:text-slate-200 uppercase" placeholder="...">
                    </div>
                    <div class="form-field-box flex gap-4">
                        <div class="flex-1">
                            <label class="block text-[8px] font-black text-indigo-400 uppercase tracking-[0.2em] mb-0.5">P.U PF</label>
                            <input type="number" name="pu_pf" x-model.number="pu_pf" class="w-full bg-transparent border-none p-0 text-sm font-black text-indigo-600 focus:ring-0" placeholder="0">
                        </div>
                        <div class="flex-1">
                            <label class="block text-[8px] font-black text-amber-400 uppercase tracking-[0.2em] mb-0.5">P.U GF</label>
                            <input type="number" name="pu_gf" x-model.number="pu_gf" class="w-full bg-transparent border-none p-0 text-sm font-black text-amber-600 focus:ring-0" placeholder="0">
                        </div>
                    </div>
                </div>

                {{-- STATS BAR --}}
                <div class="mt-8 flex items-center justify-between px-2">
                    <div class="flex items-center gap-6 font-mono text-[11px] font-black">
                        <div class="flex items-center gap-2">
                            <span class="text-slate-300">Cases :</span>
                            <span class="text-indigo-600"><span x-text="filledCount"></span> / 200</span>
                        </div>
                        <div class="h-4 w-px bg-slate-200"></div>
                        <div class="flex items-center gap-2">
                            <span class="text-slate-300">Total :</span>
                            <span class="text-emerald-600"><span x-text="totalWeight().toFixed(2)"></span> kg</span>
                        </div>
                    </div>
                    
                    <button type="button" @click="clearAll" class="text-red-500 hover:text-red-700 transition flex items-center gap-2 text-[10px] font-black uppercase tracking-widest">
                        <i class="fa-solid fa-trash-can"></i>
                        Tout effacer
                    </button>
                </div>

                {{-- WEIGHT GROUPS (ACCORDIONS) --}}
                <div class="mt-6 space-y-4">
                    @php
                        $groups = [
                            ['id' => 1, 'start' => 1, 'end' => 50, 'color' => 'blue'],
                            ['id' => 2, 'start' => 51, 'end' => 100, 'color' => 'emerald'],
                            ['id' => 3, 'start' => 101, 'end' => 150, 'color' => 'amber'],
                            ['id' => 4, 'start' => 151, 'end' => 200, 'color' => 'purple']
                        ];
                    @endphp

                    @foreach($groups as $g)
                    <div x-data="{ open: {{ $g['id'] === 1 ? 'true' : 'false' }} }" class="border border-slate-100 rounded-lg overflow-hidden shadow-sm">
                        <div class="accordion-header" @click="open = !open">
                            <div class="flex items-center gap-4">
                                <div class="w-6 h-6 rounded-full bg-blue-600 flex items-center justify-center text-white text-[10px] font-black">{{ $g['id'] }}</div>
                                <div class="flex flex-col">
                                    <span class="text-xs font-black text-slate-800">Groupe {{ $g['id'] }}</span>
                                    <span class="text-[9px] font-bold text-slate-400">Cases {{ str_pad($g['start'], 3, '0', STR_PAD_LEFT) }}-{{ str_pad($g['end'], 3, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-10">
                                <div class="flex items-center gap-4 text-[10px] font-black">
                                    <span class="px-2 py-0.5 bg-slate-100 rounded text-slate-600"><span x-text="groupFilled({{ ($g['id']-1)*50 }})"></span> / 50 cases</span>
                                    <span class="px-2 py-0.5 bg-indigo-50 rounded text-indigo-600"><span x-text="groupTotal({{ ($g['id']-1)*50 }}).toFixed(2)"></span> kg</span>
                                </div>
                                <i class="fa-solid fa-chevron-down text-slate-300 transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                            </div>
                        </div>

                        <div x-show="open" x-collapse>
                            <div class="p-6 bg-slate-50/50">
                                {{-- Sub Grid --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @for($col = 0; $col < 3; $col++)
                                    @php $base = ($g['id']-1)*50 + ($col*17); @endphp
                                    <div class="bg-white rounded border border-slate-200 overflow-hidden shadow-sm h-full flex flex-col">
                                        <div class="mini-table-header">
                                            <div class="flex">
                                                <div class="w-[35%] border-r border-white/20">N°</div>
                                                <div class="w-[65%]">Poids</div>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            @for($row = 0; $row < ($col == 2 ? 16 : 17); $row++)
                                            @php $idx = $base + $row; @endphp
                                            @if($idx < $g['end'])
                                            <div class="mini-table-row">
                                                <div class="mini-table-index">{{ str_pad($idx+1, 3, '0', STR_PAD_LEFT) }}</div>
                                                <div class="mini-table-value relative">
                                                    <input type="number" step="0.01" 
                                                           x-model="weights[{{ $idx }}]"
                                                           class="weight-input"
                                                           :class="calibres[{{ $idx }}] === 'GF' ? 'text-amber-600 border-amber-200 bg-amber-50/10' : ''"
                                                           placeholder="0">
                                                    <div @click="toggleCalibre({{ $idx }})" 
                                                         class="absolute right-1 top-1/2 -translate-y-1/2 cursor-pointer opacity-40 hover:opacity-100 transition">
                                                        <span class="text-[7px] font-black px-1 rounded border" 
                                                              :class="calibres[{{ $idx }}] === 'PF' ? 'border-indigo-200 text-indigo-500' : 'border-amber-200 text-amber-500 bg-amber-50'"
                                                              x-text="calibres[{{ $idx }}]"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            @endfor
                                        </div>
                                        <div class="bg-slate-50 p-2 border-t border-slate-200 text-center">
                                            <span class="text-[9px] font-black text-slate-400 uppercase">Total : <span class="text-blue-600" x-text="subColTotal({{ $base }}, {{ ($col == 2 ? 16 : 17) }}).toFixed(2)"></span> kg</span>
                                        </div>
                                    </div>
                                    @endfor
                                </div>
                                <div class="mt-4 text-right">
                                    <span class="text-[10px] font-black text-slate-400 uppercase italic">Sous-total Groupe {{ $g['id'] }} : <span class="text-blue-600 text-sm" x-text="groupTotal({{ ($g['id']-1)*50 }}).toFixed(2)"></span> kg</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- SIGNATURES --}}
                <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div>
                        <div class="flex justify-between items-center mb-2 px-1">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">A2C SAM / Responsable</label>
                            <button type="button" @click="clearSignature('signature-resp')" class="text-[8px] font-black text-red-400 hover:text-red-700 uppercase tracking-widest">
                                <i class="fa-solid fa-eraser"></i> Effacer
                            </button>
                        </div>
                        <canvas id="signature-resp" class="signature-pad"></canvas>
                        <p class="text-[8px] text-slate-300 text-center mt-2 font-bold italic">Signature & Cachet Officiel</p>
                        <input type="hidden" name="signature_resp" id="signature_resp_input">
                    </div>
                    <div>
                        <div class="flex justify-between items-center mb-2 px-1">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Le Producteur</label>
                            <button type="button" @click="clearSignature('signature-prod')" class="text-[8px] font-black text-red-400 hover:text-red-700 uppercase tracking-widest">
                                <i class="fa-solid fa-eraser"></i> Effacer
                            </button>
                        </div>
                        <canvas id="signature-prod" class="signature-pad"></canvas>
                        <p class="text-[8px] text-slate-300 text-center mt-2 font-bold italic">Attestation de réception de fonds</p>
                        <input type="hidden" name="signature_prod" id="signature_prod_input">
                    </div>
                </div>

                <div class="mt-12 flex flex-col md:flex-row items-center justify-between gap-6 border-t border-slate-100 pt-8">
                    <p class="text-[10px] text-slate-400 italic font-medium">Cliquez sur une case pour saisir le poids.</p>
                    <div class="flex gap-4 w-full md:w-auto">
                        <a href="{{ route('purchase_invoices.index') }}" class="flex-1 md:flex-none text-center px-10 py-3 bg-white border border-slate-200 rounded-lg text-sm font-black text-slate-600 hover:bg-slate-50 transition shadow-sm">
                            Annuler
                        </a>
                        <button type="submit" class="flex-1 md:flex-none inline-flex items-center justify-center gap-3 px-12 py-3 bg-emerald-600 rounded-lg text-sm font-black text-white hover:bg-emerald-700 shadow-lg shadow-emerald-100 transition">
                            <i class="fa-solid fa-check"></i>
                            Enregistrer
                        </button>
                    </div>
                </div>

                {{-- Hidden Financial fields --}}
                <input type="hidden" name="avarie_pct" :value="avariePct">
                <input type="hidden" name="manual_credit" :value="manualCredit">
                <input type="hidden" name="prime_bio" :value="primeBio">
                <input type="hidden" name="weights_csv" :value="weightsCSV">
                <input type="hidden" name="calibres_csv" :value="calibresCSV">
            </form>
        </div>
    </div>
</div>

{{-- DATALISTS --}}
<datalist id="zones_list">
    <option value="Avé"><option value="Zio"><option value="Vo"><option value="Danyi">
</datalist>
<datalist id="chauffeurs_list">
    <option value="SOUMAGBO Yao"><option value="AGBADZI Komi Victor">
</datalist>
<datalist id="matricules_list">
    <option value="TG 7151 BL"><option value="TG 7238 BL">
</datalist>
<datalist id="fruits_list">
    <option value="Ananas Cayenne"><option value="Ananas Braza">
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
            avariePct: 0,
            manualCredit: 0,
            primeBio: 0,
            filledCount: 0,
            
            initForm() {
                this.$watch('weights', () => this.updateStats(), { deep: true });
            },
            
            updateStats() {
                this.filledCount = this.weights.filter(w => parseFloat(w) > 0).length;
            },
            
            toggleCalibre(idx) {
                this.calibres[idx] = this.calibres[idx] === 'PF' ? 'GF' : 'PF';
                this.calibres = [...this.calibres];
            },

            clearAll() {
                if(confirm('Tout effacer ?')) {
                    this.weights = Array(200).fill(null);
                    this.calibres = Array(200).fill('PF');
                    document.querySelectorAll('.signature-pad').forEach(canvas => {
                        if(sigPads[canvas.id]) sigPads[canvas.id].clear();
                    });
                }
            },

            subColTotal(start, count) {
                let s = 0;
                for(let i=start; i<start+count; i++) s += parseFloat(this.weights[i]) || 0;
                return s;
            },

            groupTotal(offset) { return this.subColTotal(offset, 50); },
            groupFilled(offset) {
                let c = 0;
                for(let i=offset; i<offset+50; i++) if(parseFloat(this.weights[i]) > 0) c++;
                return c;
            },

            totalWeight() { return this.weights.reduce((s, w) => s + (parseFloat(w) || 0), 0); },
            get weightsCSV() { return this.weights.map(w => w || '').join(','); },
            get calibresCSV() { return this.calibres.join(','); },

            submitForm() {
                if (sigPads['signature-resp']) document.getElementById('signature_resp_input').value = sigPads['signature-resp'].isEmpty() ? '' : sigPads['signature-resp'].toDataURL();
                if (sigPads['signature-prod']) document.getElementById('signature_prod_input').value = sigPads['signature-prod'].isEmpty() ? '' : sigPads['signature-prod'].toDataURL();
                
                if(this.filledCount === 0) { alert('Saisir au moins un poids.'); return; }
                document.getElementById('mainForm').submit();
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