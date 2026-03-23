@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-100 py-5">
    <div class="max-w-4xl mx-auto px-4">
        {{-- TOP BAR --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500 flex items-center justify-center shadow">
                    <i class="fa-solid fa-pen-to-square text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-xl font-black text-slate-800 leading-tight">Modifier l'entête</h1>
                    <p class="text-sm text-slate-500 font-medium tracking-wide">Arrivage du <span class="text-amber-600">{{ $arrivage->date_arrivage->format('d/m/Y') }}</span></p>
                </div>
            </div>
            <a href="{{ route('arrivages.index') }}" class="px-4 py-2 border border-slate-200 bg-white rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-800 shadow-sm transition-all focus:ring-2 focus:ring-slate-200 focus:outline-none flex items-center justify-center sm:inline-flex w-full sm:w-auto">
                <i class="fa-solid fa-arrow-left mr-2"></i>Retour
            </a>
        </div>

        {{-- ERREURS DE VALIDATION --}}
        @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 rounded-r-xl px-5 py-4 shadow-sm">
            <div class="flex items-start">
                <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5 mr-3"></i>
                <div>
                    <h3 class="text-sm font-bold text-red-800 mb-2">Veuillez corriger les erreurs suivantes :</h3>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $err)
                            <li class="text-sm text-red-700">{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        {{-- FORMULAIRE --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <form action="{{ route('arrivages.update', $arrivage) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="p-6 sm:p-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- N° de Bon / Ref (Optionnel) -->
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700">N° de Bon / Référence <span class="text-slate-400 font-normal text-xs">(Optionnel)</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                    <i class="fa-solid fa-hashtag"></i>
                                </span>
                                <input type="text" name="custom_bon_ref" value="{{ old('custom_bon_ref', $arrivage->custom_bon_ref) }}" autocomplete="off"
                                       class="pl-10 w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500 shadow-sm text-sm uppercase-input" placeholder="{{ $arrivage->bon_ref }}">
                            </div>
                        </div>

                        <!-- Date -->
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700">Date de l'arrivage <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                    <i class="fa-regular fa-calendar-days"></i>
                                </span>
                                <input type="date" name="date_arrivage" value="{{ old('date_arrivage', $arrivage->date_arrivage ? $arrivage->date_arrivage->format('Y-m-d') : '') }}" required
                                       class="pl-10 w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500 shadow-sm text-sm">
                            </div>
                        </div>

                        <!-- Chauffeur -->
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700">Nom du Chauffeur <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                    <i class="fa-solid fa-user-tie"></i>
                                </span>
                                <input type="text" list="list_chauffeurs" name="chauffeur" value="{{ old('chauffeur', $arrivage->chauffeur) }}" required autocomplete="off"
                                       class="pl-10 w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500 shadow-sm text-sm uppercase-input">
                            </div>
                        </div>

                        <!-- Matricule Camion -->
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700">Matricule du Camion <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                    <i class="fa-solid fa-truck"></i>
                                </span>
                                <input type="text" list="list_matricules" name="matricule_camion" value="{{ old('matricule_camion', $arrivage->matricule_camion) }}" required autocomplete="off"
                                       class="pl-10 w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500 shadow-sm text-sm uppercase">
                            </div>
                        </div>

                        <!-- Zone de provenance -->
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700">Zone de Provenance <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                    <i class="fa-solid fa-location-dot"></i>
                                </span>
                                <input type="text" list="list_zones" name="zone_provenance" value="{{ old('zone_provenance', $arrivage->zone_provenance) }}" required autocomplete="off"
                                       class="pl-10 w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500 shadow-sm text-sm capitalize-input">
                            </div>
                        </div>

                        <!-- PH -->
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700">Valeur PH</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                    <i class="fa-solid fa-vial"></i>
                                </span>
                                <input type="text" name="ph" value="{{ old('ph', $arrivage->ph) }}" autocomplete="off"
                                       class="pl-10 w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500 shadow-sm text-sm">
                            </div>
                        </div>

                        <!-- Brix -->
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700">Valeur Brix</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                    <i class="fa-solid fa-temperature-half"></i>
                                </span>
                                <input type="text" name="brix" value="{{ old('brix', $arrivage->brix) }}" autocomplete="off"
                                       class="pl-10 w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500 shadow-sm text-sm">
                            </div>
                        </div>
                    </div>

                    {{-- DATALISTS --}}
                    <datalist id="list_chauffeurs">
                        <option value="SOUMAGBO Yao">
                        <option value="AGBADZI Komi Victor">
                        <option value="AMEGBETO K. Promise">
                        <option value="MORKLEY Komi">
                    </datalist>
                    <datalist id="list_matricules">
                        <option value="BL 7151">
                        <option value="BL 7238">
                        <option value="BD 2671">
                        <option value="BH 5895">
                        <option value="BH 5588">
                        <option value="EL 2473">
                    </datalist>
                    <datalist id="list_zones">
                        <option value="Avé">
                        <option value="Zio">
                        <option value="Vo">
                        <option value="Danyi">
                        <option value="Kloto Agou">
                        <option value="Haho">
                        <option value="Bas-mono">
                    </datalist>
                </div>

                <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-3">
                    <a href="{{ route('arrivages.index') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-200 rounded-xl transition-colors">
                        Annuler
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-amber-500 hover:bg-amber-600 border border-transparent rounded-xl text-sm font-bold text-white shadow-sm transition-all focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .uppercase-input { text-transform: uppercase; }
    .capitalize-input { text-transform: capitalize; }
</style>
@endsection
