@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-100 py-5" x-data="arrivageForm()" @keydown.escape.window="showCalculator=false">

{{-- CALCULATRICE SUPPRIMÉE COMME DEMANDÉ --}}

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
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-4 sm:px-5 py-4 border-b-2 border-slate-800 bg-slate-50">
            <div class="flex items-center gap-3 sm:gap-4">
                <div class="w-12 h-12 flex-shrink-0 sm:w-14 sm:h-14 rounded-full border-2 border-slate-300 overflow-hidden bg-white flex items-center justify-center">
                    <img src="{{ asset('images/logo.jpg') }}" class="w-10 h-10 sm:w-12 sm:h-12 object-contain" onerror="this.style.display='none'">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm sm:text-lg font-black tracking-widest text-slate-900 uppercase truncate">Bio Farm Trading</p>
                    <p class="text-[9px] sm:text-xs text-slate-500 whitespace-normal sm:truncate leading-tight">Production · Commercialisation de produits agricoles biologiques</p>
                </div>
            </div>
            <div class="flex items-center justify-between md:justify-end gap-5 flex-shrink-0 border-t border-slate-200 md:border-0 pt-3 md:pt-0">
                <div class="text-left md:text-right">
                    <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400">BON N°</p>
                    <p class="text-base sm:text-lg font-black text-slate-900" x-text="bonRef">#</p>
                </div>
                <div class="text-right">
                    <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Date</p>
                    <input type="date" name="date_arrivage" value="{{ date('Y-m-d') }}"
                           class="text-sm font-black text-slate-900 border-0 border-b-2 border-slate-300 bg-transparent focus:border-green-600 focus:ring-0 p-0 w-28 sm:w-32 text-right md:text-left" required>
                </div>
            </div>
        </div>

        {{-- INFOS GÉNÉRALES --}}
        <div class="border-b border-slate-300">
            {{-- Ligne 1 --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-[1px] bg-slate-200 border-b border-slate-200">
                
                {{-- Chauffeur --}}
                <div class="flex min-h-[40px] bg-white relative" x-data="{ open: false, val: '', opts: ['SOUMAGBO Yao', 'AGBADZI Komi Victor', 'AMEGBETO K. Promise', 'MORKLEY Komi'] }" @click.away="open = false" :class="open ? 'z-50' : 'z-10'">
                    <span class="bg-slate-100 px-3 py-2 text-[10px] sm:text-[9px] font-bold uppercase text-slate-500 w-24 sm:w-28 flex items-center flex-shrink-0 leading-tight border-r border-slate-200">Chauffeur</span>
                    <input type="text" name="chauffeur" x-model="val" @focus="open = true" class="flex-1 px-3 py-2 text-sm sm:text-xs font-semibold border-0 focus:ring-0 bg-transparent w-full" required placeholder="Saisir ou choisir" autocomplete="off">
                    <button type="button" @click="open = !open" class="px-3 text-slate-400 hover:text-slate-600 outline-none bg-white"><i class="fa-solid fa-chevron-down text-xs"></i></button>
                    <div x-show="open" style="display:none;" class="absolute top-[100%] left-0 right-0 bg-white border border-slate-200 shadow-xl rounded-b-lg max-h-48 overflow-y-auto mt-[1px]">
                        <ul class="py-1">
                            <template x-for="opt in opts.filter(o => o.toLowerCase().includes(val.toLowerCase()))">
                                <li @click="val = opt; open = false" class="px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-green-50 hover:text-green-700 cursor-pointer" x-text="opt"></li>
                            </template>
                        </ul>
                    </div>
                </div>

                {{-- Matricule --}}
                <div class="flex min-h-[40px] bg-white relative" x-data="{ open: false, val: '', opts: ['BL 7151', 'BL 7238', 'BD 2671', 'BH 5895', 'BH 5588', 'EL 2473'] }" @click.away="open = false" :class="open ? 'z-50' : 'z-10'">
                    <span class="bg-slate-100 px-3 py-2 text-[10px] sm:text-[9px] font-bold uppercase text-slate-500 w-24 sm:w-28 flex items-center flex-shrink-0 leading-tight border-r border-slate-200">Matricule</span>
                    <input type="text" name="matricule_camion" x-model="val" @focus="open = true" class="flex-1 px-3 py-2 text-sm sm:text-xs font-semibold border-0 focus:ring-0 bg-transparent uppercase w-full" required placeholder="Saisir ou choisir" autocomplete="off">
                    <button type="button" @click="open = !open" class="px-3 text-slate-400 hover:text-slate-600 outline-none bg-white"><i class="fa-solid fa-chevron-down text-xs"></i></button>
                    <div x-show="open" style="display:none;" class="absolute top-[100%] left-0 right-0 bg-white border border-slate-200 shadow-xl rounded-b-lg max-h-48 overflow-y-auto mt-[1px]">
                        <ul class="py-1">
                            <template x-for="opt in opts.filter(o => o.toLowerCase().includes(val.toLowerCase()))">
                                <li @click="val = opt; open = false" class="px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-green-50 hover:text-green-700 cursor-pointer uppercase" x-text="opt"></li>
                            </template>
                        </ul>
                    </div>
                </div>

                {{-- Zone --}}
                <div class="flex min-h-[40px] bg-white relative" x-data="{ open: false, val: '', opts: ['Avé', 'Zio', 'Vo', 'Danyi', 'Kloto Agou', 'Haho', 'Bas-mono'] }" @click.away="open = false" :class="open ? 'z-50' : 'z-10'">
                    <span class="bg-slate-100 px-3 py-2 text-[10px] sm:text-[9px] font-bold uppercase text-slate-500 w-24 sm:w-28 flex items-center flex-shrink-0 leading-tight border-r border-slate-200">Zone</span>
                    <input type="text" name="zone_provenance" x-model="val" @focus="open = true" class="flex-1 px-3 py-2 text-sm sm:text-xs font-semibold border-0 focus:ring-0 bg-transparent w-full" required placeholder="Saisir ou choisir" autocomplete="off">
                    <button type="button" @click="open = !open" class="px-3 text-slate-400 hover:text-slate-600 outline-none bg-white"><i class="fa-solid fa-chevron-down text-xs"></i></button>
                    <div x-show="open" style="display:none;" class="absolute top-[100%] left-0 right-0 bg-white border border-slate-200 shadow-xl rounded-b-lg max-h-48 overflow-y-auto mt-[1px]">
                        <ul class="py-1">
                            <template x-for="opt in opts.filter(o => o.toLowerCase().includes(val.toLowerCase()))">
                                <li @click="val = opt; open = false" class="px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-green-50 hover:text-green-700 cursor-pointer" x-text="opt"></li>
                            </template>
                        </ul>
                    </div>
                </div>

                {{-- Fruit --}}
                <div class="flex min-h-[40px] bg-white relative" x-data="{ open: false, opts: ['Ananas Cayenne', 'Ananas Braza', 'Papaye', 'Banane', 'Mangue'] }" @click.away="open = false" :class="open ? 'z-50' : 'z-10'">
                    <span class="bg-slate-100 px-3 py-2 text-[10px] sm:text-[9px] font-bold uppercase text-slate-500 w-24 sm:w-28 flex items-center flex-shrink-0 leading-tight border-r border-slate-200">Fruit</span>
                    <input type="text" name="fruit_type" x-model="globalFruit" @focus="open = true" class="flex-1 px-3 py-2 text-sm sm:text-xs font-semibold border-0 focus:ring-0 bg-transparent w-full" required placeholder="Saisir ou choisir" autocomplete="off">
                    <button type="button" @click="open = !open" class="px-3 text-slate-400 hover:text-slate-600 outline-none bg-white"><i class="fa-solid fa-chevron-down text-xs"></i></button>
                    <div x-show="open" style="display:none;" class="absolute top-[100%] left-0 right-0 bg-white border border-slate-200 shadow-xl rounded-b-lg max-h-48 overflow-y-auto mt-[1px]">
                        <ul class="py-1">
                            <template x-for="opt in opts.filter(o => o.toLowerCase().includes(globalFruit.toLowerCase()))">
                                <li @click="globalFruit = opt; open = false" class="px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-green-50 hover:text-green-700 cursor-pointer" x-text="opt"></li>
                            </template>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- BARRE STATS GLOBALE --}}
        <div class="flex flex-col sm:flex-row flex-wrap items-center justify-between gap-3 px-4 py-3 sm:py-2 bg-slate-800">
            <div class="text-sm text-slate-300 font-mono flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-start">
                <span>Cases : <span class="font-black text-white" x-text="filled()"></span>/200</span>
                <span class="opacity-30">|</span>
                <span>Total : <span class="font-black text-green-400" x-text="totalG().toFixed(2)+' kg'"></span></span>
            </div>
            <button type="button" @click="clearAll()" class="text-sm text-red-400 hover:text-red-300 transition flex items-center justify-center gap-2 w-full sm:w-auto bg-slate-700/50 sm:bg-transparent py-2 sm:py-0 rounded-lg sm:rounded-none">
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
            <div x-show="open" x-collapse class="{{ $g['light'] }} p-2 sm:p-3">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">

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
                            <div class="py-1 text-center text-[10px] font-bold border-r border-slate-200 flex items-center justify-center transition-colors"
                                 :class="poids[{{ $absIdx }}]>0?'{{ $g['text'] }}':'text-slate-300'">
                                {{ str_pad($absIdx+1,3,'0',STR_PAD_LEFT) }}
                            </div>
                            <div class="flex items-center">
                                <input type="number" step="0.01" min="0"
                                       x-model.number="poids[{{ $absIdx }}]"
                                       class="w-full text-center text-xs sm:text-[10px] font-bold border-0 focus:ring-1 focus:ring-green-400 bg-transparent py-2.5 sm:py-1.5 px-1 rounded"
                                       :class="poids[{{ $absIdx }}]>0?'text-slate-800 bg-green-50':'text-slate-200'"
                                       placeholder="0.00">
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

    <div class="mt-6 flex flex-col-reverse sm:flex-row items-center justify-between gap-4">
        <p class="text-xs text-slate-400 text-center sm:text-left w-full sm:w-auto">Cliquez sur une case pour saisir le poids</p>
        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <a href="{{ route('arrivages.index') }}" class="px-4 py-3 sm:py-2.5 bg-white border border-slate-300 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50 shadow-sm transition text-center w-full sm:w-auto">Annuler</a>
            <button type="button" @click="submitForm()"
                    class="inline-flex items-center justify-center gap-2 px-6 py-3 sm:py-2.5 bg-green-600 rounded-xl text-sm font-bold text-white hover:bg-green-700 shadow transition focus:ring-2 focus:ring-green-500 focus:ring-offset-2 w-full sm:w-auto">
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

        get bonRef() {
            let id = {{ optional(\App\Models\Arrivage::orderBy('id','desc')->first())->id + 1 ?? 1 }};
            let f = (this.globalFruit || '').toLowerCase().trim();
            let code = 'DIV';
            if (f.includes('ananas')) code = 'ANAS';
            else if (f.includes('papaye')) code = 'PAP';
            else if (f.includes('banane')) code = 'BAN';
            else if (f.includes('mangue')) code = 'MAN';
            else if (f) {
                // Prend les 3 premieres lettres du fruit si non connu
                code = f.substring(0, 3).toUpperCase();
            }
            return String(id).padStart(3, '0') + '/' + code + '/' + new Date().getFullYear();
        },

        openCalc(i) {
            // Désactivé
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