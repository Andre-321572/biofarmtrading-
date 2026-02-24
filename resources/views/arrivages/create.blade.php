@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-100 py-5" x-data="arrivageForm()" @keydown.escape.window="showCalculator=false">

{{-- CALCULATRICE --}}
<div x-show="showCalculator" x-cloak class="fixed inset-0 z-[200] flex items-center justify-center">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showCalculator=false"></div>
    <div class="relative w-68 rounded-3xl overflow-hidden shadow-2xl" style="background:#1c1c1e;width:260px">
        <div class="px-4 pt-4 pb-2">
            <div class="text-right">
                <p class="font-mono text-xs h-4 truncate" style="color:#636366" x-text="expr"></p>
                <p class="font-light text-white mt-0.5 truncate" style="font-size:2.6rem;line-height:1.1" x-text="disp"></p>
                <p class="text-xs mt-1 font-bold" style="color:#30d158" x-show="idx!==null"
                   x-text="'Case '+String((idx||0)+1).padStart(3,'0')+' — Groupe '+(Math.floor((idx||0)/50)+1)"></p>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:7px;background:#000;padding:12px;border-radius:0 0 1.5rem 1.5rem">
            <button type="button" @click="clr()" style="grid-column:span 2;background:#a5a5a5;color:#000;font-size:18px;font-weight:700;border-radius:50px;height:52px;border:none;cursor:pointer">AC</button>
            <button type="button" @click="back()" style="background:#3a3a3c;color:#fff;border-radius:50%;width:52px;height:52px;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;margin:auto">
                <i class="fa-solid fa-arrow-left"></i>
            </button>
            <button type="button" @click="app('+')" style="background:#ff9f0a;color:#fff;font-size:24px;border-radius:50%;width:52px;height:52px;border:none;cursor:pointer;margin:auto">+</button>
            @foreach([7,8,9,null,4,5,6,null,1,2,3,null] as $n)
                @if($n!==null)<button type="button" @click="app('{{$n}}')" style="background:#3a3a3c;color:#fff;font-size:20px;border-radius:50%;width:52px;height:52px;border:none;cursor:pointer;margin:auto">{{$n}}</button>
                @else<div></div>@endif
            @endforeach
            <button type="button" @click="app('.')" style="background:#3a3a3c;color:#fff;font-size:20px;border-radius:50%;width:52px;height:52px;border:none;cursor:pointer;margin:auto">,</button>
            <button type="button" @click="app('0')" style="background:#3a3a3c;color:#fff;font-size:20px;border-radius:50%;width:52px;height:52px;border:none;cursor:pointer;margin:auto">0</button>
            <button type="button" @click="ok()" style="grid-column:span 2;background:#30d158;color:#000;font-size:14px;font-weight:800;border-radius:50px;height:52px;border:none;cursor:pointer">
                VALIDER <i class="fa-solid fa-check ml-1"></i>
            </button>
        </div>
    </div>
</div>

<div class="max-w-6xl mx-auto px-4">

    {{-- TOP BAR --}}
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-green-600 flex items-center justify-center shadow">
                <i class="fa-solid fa-file-invoice text-white text-sm"></i>
            </div>
            <div>
                <h1 class="text-base font-black text-slate-800 leading-none">Nouvel Arrivage</h1>
                <p class="text-xs text-slate-400">200 cases · 4 groupes de 50</p>
            </div>
        </div>
        <a href="{{ route('arrivages.index') }}" class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50 shadow-sm transition">
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

    {{-- FORMULAIRE --}}
    <form action="{{ route('arrivages.store') }}" method="POST" id="mainForm">
    @csrf

    {{-- DOCUMENT --}}
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden" style="font-family:'Courier New',monospace">

        {{-- HEADER DOCUMENT --}}
        <div class="flex items-center gap-4 px-5 py-3 border-b-2 border-slate-800 bg-slate-50">
            <div class="w-14 h-14 rounded-full border-2 border-slate-300 overflow-hidden bg-white flex items-center justify-center shrink-0">
                <img src="{{ asset('images/logo.jpg') }}" class="w-12 h-12 object-contain" onerror="this.style.display='none'">
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-lg font-black tracking-widest text-slate-900 uppercase truncate">Bio Farm Trading</p>
                <p class="text-xs text-slate-500 truncate">Production · Commercialisation de produits agricoles biologiques</p>
            </div>
            <div class="flex items-center gap-5 shrink-0">
                <div class="text-right">
                    <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400">BON N°</p>
                    <p class="text-xl font-black text-slate-900">#{{ optional(\App\Models\Arrivage::orderBy('id','desc')->first())->id + 1 ?? 1 }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Date</p>
                    <input type="date" name="date_arrivage" value="{{ date('Y-m-d') }}"
                           class="text-sm font-black text-slate-900 border-0 border-b-2 border-slate-300 bg-transparent focus:border-green-600 focus:ring-0 p-0 w-32" required>
                </div>
            </div>
        </div>

        {{-- INFOS GÉNÉRALES --}}
        <div class="border-b border-slate-300">
            {{-- Ligne 1 --}}
            <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-slate-200 border-b border-slate-200">
                <div class="flex min-h-[36px]">
                    <span class="bg-slate-100 border-r border-slate-200 px-2 py-1 text-[9px] font-bold uppercase text-slate-500 w-24 flex items-center shrink-0 leading-tight">Chauffeur</span>
                    <select name="chauffeur" class="flex-1 px-2 text-xs font-semibold border-0 focus:ring-0 bg-transparent" required>
                        <option value="" disabled selected>— Sélectionner —</option>
                        @foreach(['Mr YAO','Mr VICTOR','Mr PROMISE'] as $c)
                        <option value="{{ $c }}">{{ $c }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex min-h-[36px]">
                    <span class="bg-slate-100 border-r border-slate-200 px-2 py-1 text-[9px] font-bold uppercase text-slate-500 w-24 flex items-center shrink-0 leading-tight">Matricule</span>
                    <input type="text" name="matricule_camion" class="flex-1 px-2 text-xs font-semibold border-0 focus:ring-0 bg-transparent uppercase" required placeholder="AB-1234-CD">
                </div>
                <div class="flex min-h-[36px]">
                    <span class="bg-slate-100 border-r border-slate-200 px-2 py-1 text-[9px] font-bold uppercase text-slate-500 w-24 flex items-center shrink-0 leading-tight">Zone</span>
                    <input type="text" name="zone_provenance" class="flex-1 px-2 text-xs font-semibold border-0 focus:ring-0 bg-transparent" required>
                </div>
                <div class="flex min-h-[36px]">
                    <span class="bg-slate-100 border-r border-slate-200 px-2 py-1 text-[9px] font-bold uppercase text-slate-500 w-24 flex items-center shrink-0 leading-tight">Fruit</span>
                    <select name="fruit_type" x-model="globalFruit" class="flex-1 px-2 text-xs font-semibold border-0 focus:ring-0 bg-transparent" required>
                        <option value="" disabled selected>— Sélectionner —</option>
                        <option value="ananas_cayenne">Ananas Cayenne</option>
                        <option value="ananas_braza">Ananas Braza</option>
                        <option value="papaye">Papaye</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- BARRE STATS GLOBALE --}}
        <div class="flex items-center justify-between px-4 py-1.5 bg-slate-800">
            <div class="text-xs text-slate-300 font-mono flex items-center gap-3">
                <span>Cases : <span class="font-black text-white" x-text="filled()"></span>/200</span>
                <span class="opacity-30">|</span>
                <span>Total : <span class="font-black text-green-400" x-text="totalG().toFixed(2)+' kg'"></span></span>
            </div>
            <button type="button" @click="clearAll()" class="text-xs text-red-400 hover:text-red-300 transition flex items-center gap-2">
                <i class="fa-solid fa-trash-can"></i> Tout effacer
            </button>
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

            {{-- EN-TÊTE GROUPE --}}
            <button type="button" @click="open=!open"
                    class="w-full flex items-center justify-between px-4 py-2.5 text-left hover:brightness-95 transition select-none {{ $g['light'] }} border-b {{ $g['border'] }}">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg {{ $g['bg'] }} flex items-center justify-center shrink-0">
                        <span class="text-xs font-black text-white">{{ $g['num'] }}</span>
                    </div>
                    <div>
                        <span class="text-sm font-black {{ $g['text'] }}">Groupe {{ $g['num'] }}</span>
                        <span class="ml-2 text-xs {{ $g['text'] }} opacity-60">Cases {{ str_pad($g['from'],3,'0',STR_PAD_LEFT) }}–{{ str_pad($g['to'],3,'0',STR_PAD_LEFT) }}</span>
                    </div>
                    {{-- Badge résumé --}}
                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-bold {{ $g['badge'] }}">
                        <span x-text="grpFilled({{ $offset }})"></span>/50 cases
                        &nbsp;·&nbsp;
                        <span x-text="grpTotal({{ $offset }}).toFixed(2)+' kg'"></span>
                    </span>
                </div>
                <i class="fa-solid fa-chevron-down w-4 h-4 {{ $g['text'] }} transition-transform duration-200" :class="open?'rotate-180':''"></i>
            </button>

            {{-- GRILLE 50 CASES --}}
            <div x-show="open" x-collapse class="{{ $g['light'] }} p-3">
                <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:6px">

                    @for($col=0; $col<5; $col++)
                    @php $colOffset = $offset + $col*10; @endphp
                    <div class="rounded-lg overflow-hidden border {{ $g['border'] }} bg-white shadow-sm">
                        <div class="grid grid-cols-2 {{ $g['bg'] }}">
                            <div class="py-1 text-center text-[9px] font-bold text-white/80 border-r border-white/20">N°</div>
                            <div class="py-1 text-center text-[9px] font-bold text-white/80">Poids</div>
                        </div>
                        @for($row=0; $row<10; $row++)
                        @php $absIdx = $colOffset + $row; @endphp
                        <div class="grid grid-cols-2 border-b border-slate-100 hover:bg-slate-50 group transition-colors"
                             :class="poids[{{ $absIdx }}]>0?'{{ $g['light'] }}':''">
                            <div class="py-1.5 text-center text-[9px] font-bold border-r border-slate-200 flex items-center justify-center transition-colors"
                                 :class="poids[{{ $absIdx }}]>0?'{{ $g['text'] }}':'text-slate-300'">
                                {{ str_pad($absIdx+1,3,'0',STR_PAD_LEFT) }}
                            </div>
                            <div class="flex items-center">
                                <input type="text"
                                       :value="poids[{{ $absIdx }}]>0?poids[{{ $absIdx }}].toFixed(2):''"
                                       @click="openCalc({{ $absIdx }})"
                                       class="w-full text-center text-[10px] font-bold border-0 focus:ring-0 bg-transparent cursor-pointer py-1.5 px-1"
                                       :class="poids[{{ $absIdx }}]>0?'text-slate-800':'text-slate-200'"
                                       style="caret-color:transparent"
                                       placeholder="·" readonly>
                            </div>
                        </div>
                        @endfor
                        <div class="grid grid-cols-2 {{ $g['bg'] }} bg-opacity-15 border-t-2 {{ $g['border'] }}">
                            <div class="py-1.5 text-center text-[9px] font-black {{ $g['text'] }} border-r {{ $g['border'] }}">T</div>
                            <div class="py-1.5 text-center text-[9px] font-black text-slate-700"
                                 x-text="colTotal({{ $colOffset }},10).toFixed(2)"></div>
                        </div>
                    </div>
                    @endfor

                </div>

                <div class="mt-2 flex items-center justify-end gap-3 px-1">
                    <span class="text-xs {{ $g['text'] }} font-semibold">Sous-total Groupe {{ $g['num'] }} :</span>
                    <span class="text-sm font-black {{ $g['text'] }}" x-text="grpTotal({{ $offset }}).toFixed(2)+' kg'"></span>
                </div>
            </div>

        </div>
        @endforeach
        </div>

        {{-- SIGNATURES --}}
        <div class="border-t border-slate-200 grid grid-cols-2 divide-x divide-slate-200">
            <div class="px-5 py-4">
                <p class="text-[9px] font-bold uppercase text-slate-500">A2C SAM / Responsable</p>
                <div class="border-b border-dotted border-slate-300 mt-7 mb-1"></div>
                <p class="text-[8px] text-slate-400 text-center">Signature & Cachet</p>
            </div>
            <div class="px-5 py-4 text-right">
                <p class="text-[9px] font-bold uppercase text-slate-500">Le Producteur</p>
                <div class="border-b border-dotted border-slate-300 mt-7 mb-1"></div>
                <p class="text-[8px] text-slate-400 text-center">Signature</p>
            </div>
        </div>

    </div>

    <div class="mt-4 flex items-center justify-between">
        <p class="text-xs text-slate-400">Cliquez sur une case pour saisir le poids</p>
        <div class="flex gap-3">
            <a href="{{ route('arrivages.index') }}" class="px-4 py-2 bg-white border border-slate-300 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50 shadow-sm transition">Annuler</a>
            <button type="button" @click="submitForm()"
                    class="inline-flex items-center gap-2 px-5 py-2 bg-green-600 rounded-xl text-sm font-bold text-white hover:bg-green-700 shadow transition focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                <i class="fa-solid fa-check"></i>
                Enregistrer
            </button>
        </div>
    </div>

    </form>
</div>
</div>

<script>
function arrivageForm() {
    return {
        showCalculator: false,
        idx: null,
        disp: '0',
        expr: '',
        globalFruit: '',
        poids: Array(200).fill(0),

        openCalc(i) {
            this.idx = i;
            this.disp = this.poids[i] > 0 ? String(this.poids[i]) : '0';
            this.expr = '';
            this.showCalculator = true;
        },

        app(v) {
            if (v === '+') {
                this.expr = this.disp + '+';
                this.disp = '0';
                return;
            }
            this.disp = (this.disp === '0' && v !== '.') ? v : this.disp + v;
        },

        clr() { this.disp = '0'; this.expr = ''; },

        back() {
            this.disp = this.disp.length > 1 ? this.disp.slice(0, -1) : '0';
        },

        ok() {
            let e = (this.expr + this.disp).replace(/,/g, '.');
            if (/[+\-]$/.test(e)) e = e.slice(0, -1);
            try {
                const r = new Function('return ' + e)();
                if (!isNaN(r) && isFinite(r) && r >= 0) {
                    const a = [...this.poids];
                    a[this.idx] = parseFloat(r.toFixed(2));
                    this.poids = a;
                }
            } catch (_) {}
            this.showCalculator = false;
        },

        clearAll() {
            if (confirm('Effacer les 200 cases ?')) this.poids = Array(200).fill(0);
        },

        colTotal(start, count) {
            let s = 0;
            for (let i = start; i < start + count; i++) s += this.poids[i] || 0;
            return s;
        },

        grpTotal(offset) { return this.colTotal(offset, 50); },

        grpFilled(offset) {
            return this.poids.slice(offset, offset + 50).filter(v => v > 0).length;
        },

        totalG() { return this.poids.reduce((s, v) => s + (v || 0), 0); },
        filled() { return this.poids.filter(v => v > 0).length; },

        submitForm() {
            if (!this.globalFruit) {
                alert('Sélectionnez le type de fruit.');
                return;
            }
            if (this.filled() === 0) {
                alert('Saisissez au moins un poids.');
                return;
            }

            const form = document.getElementById('mainForm');

            form.querySelectorAll('input[data-poids-hidden]').forEach(el => el.remove());

            for (let i = 0; i < 200; i++) {
                const inp = document.createElement('input');
                inp.type = 'hidden';
                inp.name = 'poids[' + i + ']';
                inp.value = this.poids[i] > 0 ? this.poids[i] : 0;
                inp.setAttribute('data-poids-hidden', '1');
                form.appendChild(inp);
            }

            form.submit();
        }
    }
}
</script>
@endsection