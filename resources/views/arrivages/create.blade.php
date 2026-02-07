@extends('layouts.app')

@section('content')
<div class="py-12" x-data="arrivageForm()">
    
    <!-- Calculator Modal -->
    <div x-show="showCalculator" 
         class="fixed inset-0 z-[100] overflow-y-auto" 
         style="display: none;"
         @keydown.escape.window="showCalculator = false">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="showCalculator = false"></div>

        <!-- Calculator Widget -->
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl shadow-2xl transition-all sm:my-8 w-full max-w-sm border border-gray-700" 
                 style="background-color: #1f1f1f;">
                
                <!-- Display -->
                <div class="p-6 pb-2" style="background-color: #1f1f1f;">
                    <div class="text-right">
                        <div style="color: #9ca3af; font-family: ui-monospace, monospace; font-size: 0.875rem; height: 1.5rem;" x-text="calcExpression"></div>
                        <div style="color: #ffffff; font-size: 3rem; font-weight: 300; line-height: 1; margin-top: 0.25rem;" x-text="calcDisplay">0</div>
                    </div>
                </div>

                <!-- Keypad -->
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; background-color: #1f1f1f; padding: 20px;">
                    <!-- Row 1 -->
                    <button type="button" @click="clearCalc()" class="col-span-2 text-white font-bold transition-all active:scale-95" 
                            style="background-color: #4b5563; border-radius: 12px; height: 60px; border: none; cursor: pointer;">AC</button>
                    <button type="button" @click="backspaceCalc()" class="text-white font-bold transition-all active:scale-95" 
                            style="background-color: #4b5563; border-radius: 12px; height: 60px; border: none; cursor: pointer;">⌫</button>
                    <button type="button" @click="appendCalc('+')" class="text-white text-2xl font-bold transition-all active:scale-95" 
                            style="background-color: #ff9f0a; border-radius: 12px; height: 60px; border: none; cursor: pointer;">+</button>

                    <!-- Row 2 -->
                    <button type="button" @click="appendCalc('7')" class="text-white text-xl font-bold transition-all active:scale-95" style="background-color: #374151; border-radius: 12px; height: 60px; border: none; cursor: pointer;">7</button>
                    <button type="button" @click="appendCalc('8')" class="text-white text-xl font-bold transition-all active:scale-95" style="background-color: #374151; border-radius: 12px; height: 60px; border: none; cursor: pointer;">8</button>
                    <button type="button" @click="appendCalc('9')" class="text-white text-xl font-bold transition-all active:scale-95" style="background-color: #374151; border-radius: 12px; height: 60px; border: none; cursor: pointer;">9</button>
                    <div style="height: 60px;"></div>

                    <!-- Row 3 -->
                    <button type="button" @click="appendCalc('4')" class="text-white text-xl font-bold transition-all active:scale-95" style="background-color: #374151; border-radius: 12px; height: 60px; border: none; cursor: pointer;">4</button>
                    <button type="button" @click="appendCalc('5')" class="text-white text-xl font-bold transition-all active:scale-95" style="background-color: #374151; border-radius: 12px; height: 60px; border: none; cursor: pointer;">5</button>
                    <button type="button" @click="appendCalc('6')" class="text-white text-xl font-bold transition-all active:scale-95" style="background-color: #374151; border-radius: 12px; height: 60px; border: none; cursor: pointer;">6</button>
                    <div style="height: 60px;"></div>

                    <!-- Row 4 -->
                    <button type="button" @click="appendCalc('1')" class="text-white text-xl font-bold transition-all active:scale-95" style="background-color: #374151; border-radius: 12px; height: 60px; border: none; cursor: pointer;">1</button>
                    <button type="button" @click="appendCalc('2')" class="text-white text-xl font-bold transition-all active:scale-95" style="background-color: #374151; border-radius: 12px; height: 60px; border: none; cursor: pointer;">2</button>
                    <button type="button" @click="appendCalc('3')" class="text-white text-xl font-bold transition-all active:scale-95" style="background-color: #374151; border-radius: 12px; height: 60px; border: none; cursor: pointer;">3</button>
                    <div style="height: 60px;"></div>

                    <!-- Row 5 -->
                    <button type="button" @click="appendCalc('.')" class="text-white text-xl font-bold transition-all active:scale-95" style="background-color: #374151; border-radius: 12px; height: 60px; border: none; cursor: pointer;">,</button>
                    <button type="button" @click="appendCalc('0')" class="text-white text-xl font-bold transition-all active:scale-95" style="background-color: #374151; border-radius: 12px; height: 60px; border: none; cursor: pointer;">0</button>
                    <button type="button" @click="confirmCalc()" class="col-span-2 text-white font-black transition-all active:scale-95 shadow-lg shadow-orange-500/20" 
                            style="background-color: #ff9f0a; border-radius: 12px; height: 60px; border: none; cursor: pointer;">
                        VALIDER
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 to-green-500 p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        Nouvel Arrivage
                    </h2>
                    <a href="{{ route('arrivages.index') }}" class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg transition text-sm font-medium backdrop-blur-sm">
                        Retour à la liste
                    </a>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('arrivages.store') }}" method="POST" class="p-6 space-y-8" @submit.prevent="submitForm">
                @csrf

                <!-- Section 1: Informations Générales -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-6 rounded-xl border border-gray-100">
                    <div>
                        <x-input-label for="chauffeur" value="Nom du Chauffeur *" class="text-gray-700 font-semibold mb-1" />
                        <x-text-input id="chauffeur" name="chauffeur" type="text" class="mt-1 block w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('chauffeur')" />
                    </div>

                    <div>
                        <x-input-label for="matricule_camion" value="Matricule du Camion *" class="text-gray-700 font-semibold mb-1" />
                        <x-text-input id="matricule_camion" name="matricule_camion" type="text" class="mt-1 block w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm" required />
                        <x-input-error class="mt-2" :messages="$errors->get('matricule_camion')" />
                    </div>

                    <div>
                        <x-input-label for="date_arrivage" value="Date d'Arrivage *" class="text-gray-700 font-semibold mb-1" />
                        <x-text-input id="date_arrivage" name="date_arrivage" type="date" value="{{ date('Y-m-d') }}" class="mt-1 block w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm" required />
                        <x-input-error class="mt-2" :messages="$errors->get('date_arrivage')" />
                    </div>

                    <div>
                        <x-input-label for="zone_provenance" value="Zone de Provenance *" class="text-gray-700 font-semibold mb-1" />
                        <x-text-input id="zone_provenance" name="zone_provenance" type="text" class="mt-1 block w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm" required />
                        <x-input-error class="mt-2" :messages="$errors->get('zone_provenance')" />
                    </div>
                </div>

                <!-- Section 2: Détails des Fruits -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <span class="w-2 h-8 bg-green-500 rounded-full"></span>
                            Détails des Fruits
                        </h3>
                        <button type="button" @click="addDetail()" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            + Ajouter une ligne
                        </button>
                    </div>

                    <div class="overflow-x-auto border border-gray-200 rounded-xl shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200 bg-white">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type de Fruit</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Variété</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">
                                        Poids (kg) <span class="text-gray-400 font-normal ml-1">Cliquez pour calculer</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <template x-for="(detail, index) in details" :key="index">
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap align-top">
                                            <select :name="'details[' + index + '][fruit]'" x-model="detail.fruit" @change="updateVarietyState(index)" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm" required>
                                                <option value="" disabled>Sélectionner...</option>
                                                <option value="ananas">🍍 Ananas</option>
                                                <option value="papaye">🥭 Papaye</option>
                                            </select>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap align-top">
                                            <select :name="'details[' + index + '][variete]'" 
                                                    x-model="detail.variete" 
                                                    :disabled="detail.fruit !== 'ananas'" 
                                                    :required="detail.fruit === 'ananas'" 
                                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-400">
                                                <template x-if="detail.fruit !== 'ananas'">
                                                    <option value="non_applicable">Non Applicable</option>
                                                </template>
                                                <template x-if="detail.fruit === 'ananas'">
                                                    <optgroup label="Variétés Ananas">
                                                        <option value="" disabled>Choisir...</option>
                                                        <option value="cayenne_lisse">Cayenne Lisse</option>
                                                        <option value="braza">Braza</option>
                                                    </optgroup>
                                                </template>
                                            </select>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap align-top">
                                            <div class="relative rounded-md shadow-sm">
                                                <input type="text" 
                                                       :value="detail.poids > 0 ? detail.poids.toFixed(2) : ''"
                                                       @click="openCalculator(index)"
                                                       class="block w-full rounded-md border-gray-300 pr-12 focus:border-green-500 focus:ring-green-500 sm:text-sm cursor-pointer bg-gray-50" 
                                                       placeholder="0.00" 
                                                       readonly 
                                                       required>
                                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                                    kg
                                                </div>
                                            </div>
                                            <!-- Hidden input for submission -->
                                            <input type="hidden" :name="'details[' + index + '][poids]'" :value="detail.poids">
                                            
                                            <!-- Expression preview if exists -->
                                            <p class="mt-1 text-xs text-blue-600" x-show="detail.poids_expression && detail.poids_expression.includes('+')">
                                                <span x-text="detail.poids_expression.replace(/\+/g, ' + ')"></span>
                                            </p>

                                            <!-- Backend Validation Error (if any) -->
                                            @if($errors->has('details.*.poids'))
                                                @foreach($errors->get('details.*.poids') as $key => $message)
                                                    <p class="mt-1 text-xs text-red-600" x-show="index == {{ explode('.', $key)[1] }}">
                                                        {{ $message[0] }}
                                                    </p>
                                                @endforeach
                                            @endif
                                            
                                            <!-- Frontend Validation Error (Alpine) -->
                                            <p class="mt-1 text-xs text-red-600" x-show="detail.error_message" x-text="detail.error_message"></p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium align-top">
                                            <button type="button" @click="removeDetail(index)" class="text-red-400 hover:text-red-600 p-2 rounded-full hover:bg-red-50 transition" :disabled="details.length === 1" :class="{'opacity-50 cursor-not-allowed': details.length === 1}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Section 3: Totaux (Affichage Premium) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6">
                    <!-- Total Ananas -->
                    <div class="bg-blue-50 rounded-xl p-6 border border-blue-100 shadow-sm relative overflow-hidden group hover:shadow-md transition">
                        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-blue-100 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
                        <h4 class="text-blue-800 font-bold mb-4 flex items-center z-10 relative">
                            <span class="text-2xl mr-2">🍍</span> Total Ananas
                        </h4>
                        <div class="space-y-3 z-10 relative">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-blue-600">Cayenne Lisse:</span>
                                <span class="font-bold text-blue-900" x-text="totals.cayenne.toFixed(2) + ' kg'"></span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-blue-600">Braza:</span>
                                <span class="font-bold text-blue-900" x-text="totals.braza.toFixed(2) + ' kg'"></span>
                            </div>
                            <div class="pt-3 border-t border-blue-200 mt-2 flex justify-between items-center">
                                <span class="font-bold text-blue-800">Total:</span>
                                <span class="font-black text-xl text-blue-900" x-text="totals.ananas.toFixed(2) + ' kg'"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Total Papaye -->
                    <div class="bg-orange-50 rounded-xl p-6 border border-orange-100 shadow-sm relative overflow-hidden group hover:shadow-md transition">
                        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-orange-100 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
                        <h4 class="text-orange-800 font-bold mb-4 flex items-center z-10 relative">
                            <span class="text-2xl mr-2">🥭</span> Total Papaye
                        </h4>
                        <div class="flex flex-col justify-end h-full pb-1 z-10 relative">
                            <div class="flex justify-between items-end">
                                <span class="text-orange-600 font-medium">Poids Total:</span>
                                <span class="font-black text-3xl text-orange-600" x-text="totals.papaye.toFixed(2)"></span>
                            </div>
                            <span class="text-right text-sm text-orange-400 font-medium">kg</span>
                        </div>
                    </div>

                    <!-- Grand Total -->
                    <div class="bg-gray-900 rounded-xl p-6 shadow-lg text-white relative overflow-hidden flex flex-col justify-between">
                        <div class="absolute inset-0 bg-gradient-to-br from-gray-800 to-gray-900"></div>
                        <div class="relative z-10">
                            <h4 class="text-gray-400 font-medium uppercase tracking-wider text-xs mb-1">Poids Total Arrivage</h4>
                            <div class="text-4xl font-black tracking-tight" x-text="totals.general.toFixed(2)"></div>
                            <div class="text-gray-400 text-sm mt-1">kilogrammes</div>
                        </div>
                        <div class="relative z-10 mt-4 pt-4 border-t border-gray-700 flex justify-between items-center">
                            <span class="text-gray-400 text-sm">Lignes totales:</span>
                            <span class="bg-gray-700 px-2 py-1 rounded text-xs font-bold" x-text="details.length"></span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-4 pt-6 mt-6 border-t border-gray-100">
                    <a href="{{ route('arrivages.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        Annuler
                    </a>
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Enregistrer l'Arrivage
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function arrivageForm() {
        return {
            showCalculator: false,
            currentDetailIndex: null,
            calcDisplay: '0',
            calcExpression: '',
            
            details: {!! \Illuminate\Support\Js::from(old('details', [
                ['fruit' => 'ananas', 'variete' => '', 'poids_expression' => '', 'poids' => 0]
            ])) !!}.map(detail => ({
                ...detail,
                poids: parseFloat(detail.poids) || 0 // Ensure number type
            })),
            totals: {
                cayenne: 0,
                braza: 0,
                ananas: 0,
                papaye: 0,
                general: 0
            },
            
            init() {
                this.calculateAllTotals();
            },
            
            // Calculator Methods
            openCalculator(index) {
                this.currentDetailIndex = index;
                // Initialize display with current weight if > 0
                this.calcDisplay = this.details[index].poids > 0 ? this.details[index].poids.toString() : '0';
                this.showCalculator = true;
            },
            
            appendCalc(val) {
                if (this.calcDisplay === '0' && val !== '.') {
                    this.calcDisplay = val;
                } else {
                    this.calcDisplay += val;
                }
            },
            
            clearCalc() {
                this.calcDisplay = '0';
            },
            
            backspaceCalc() {
                if (this.calcDisplay.length > 1) {
                    this.calcDisplay = this.calcDisplay.slice(0, -1);
                } else {
                    this.calcDisplay = '0';
                }
            },
            
            confirmCalc() {
                // Parse the calculation expression
                let expression = this.calcDisplay.replace(/,/g, '.');
                
                try {
                    // Safe eval 
                    // Remove trailing operators like '10+' before eval
                    if (/[+\-]$/.test(expression)) {
                        expression = expression.slice(0, -1);
                    }
                    
                    const result = new Function('return ' + expression)();
                    
                    if (!isNaN(result) && isFinite(result)) {
                        // Update the current row
                        this.details[this.currentDetailIndex].poids = parseFloat(result);
                        this.details[this.currentDetailIndex].poids_expression = this.calcDisplay; 
                        
                        this.calculateAllTotals();
                        this.showCalculator = false;
                    }
                } catch (e) {
                    this.calcDisplay = 'Erreur';
                }
            },

            addDetail() {
                this.details.push({ 
                    fruit: '', 
                    variete: 'non_applicable', 
                    poids_expression: '', 
                    poids: 0 
                });
            },
            
            removeDetail(index) {
                if (this.details.length > 1) {
                    this.details.splice(index, 1);
                    this.calculateAllTotals();
                }
            },
            
            updateVarietyState(index) {
                const detail = this.details[index];
                if (detail.fruit === 'ananas') {
                    detail.variete = ''; // Reset to force selection
                } else {
                    detail.variete = 'non_applicable';
                }
                this.calculateAllTotals();
            },
            
            calculateAllTotals() {
                let cayenne = 0;
                let braza = 0;
                let papaye = 0;
                
                this.details.forEach(detail => {
                    const weight = parseFloat(detail.poids) || 0;
                    
                    if (detail.fruit === 'ananas') {
                        if (detail.variete === 'cayenne_lisse') {
                            cayenne += weight;
                        } else if (detail.variete === 'braza') {
                            braza += weight;
                        }
                    } else if (detail.fruit === 'papaye') {
                        papaye += weight;
                    }
                });
                
                this.totals.cayenne = cayenne;
                this.totals.braza = braza;
                this.totals.ananas = cayenne + braza;
                this.totals.papaye = papaye;
                this.totals.general = cayenne + braza + papaye;
            },
            
            submitForm(e) {
                let hasError = false;
                
                this.details = this.details.map(detail => {
                    if (parseFloat(detail.poids) <= 0) {
                        hasError = true;
                        return { ...detail, error_message: 'Poids requis !' };
                    }
                    return { ...detail, error_message: null };
                });
                
                if (hasError) {
                    alert("Veuillez renseigner le poids pour toutes les lignes.");
                    return;
                }
                
                e.target.submit();
            }
        }
    }
</script>
@endsection
