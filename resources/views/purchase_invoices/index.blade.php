@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-8 lg:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- HEADER SECTION --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl bg-indigo-600 flex items-center justify-center shadow-xl shadow-indigo-100 ring-4 ring-indigo-50">
                    <i class="fa-solid fa-receipt text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Registre des Factures</h1>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                        <span class="inline-block w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                        {{ $invoices->total() }} Factures d'Achat enregistrées
                    </p>
                </div>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                {{-- Quick Stats Pills --}}
                <div class="hidden sm:flex items-center gap-2 px-4 py-2 bg-white rounded-2xl border border-slate-200 shadow-sm">
                    <div class="text-right border-r border-slate-100 pr-4">
                        <p class="text-[8px] font-black text-slate-400 uppercase">Volume Total</p>
                        <p class="text-sm font-black text-indigo-600 tracking-tighter">{{ number_format($invoices->sum('total_weight'), 0, ',', ' ') }} kg</p>
                    </div>
                    <div class="text-right pl-2">
                        <p class="text-[8px] font-black text-slate-400 uppercase">Valeur Totale</p>
                        <p class="text-sm font-black text-emerald-600 tracking-tighter">{{ number_format($invoices->sum('net_a_payer'), 0, ',', ' ') }} <span class="text-[10px]">FCFA</span></p>
                    </div>
                </div>

                <a href="{{ route('purchase_invoices.create') }}" 
                   class="group relative inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 rounded-2xl text-sm font-black text-white hover:bg-indigo-700 shadow-xl shadow-indigo-100 transition-all overflow-hidden">
                    <i class="fa-solid fa-plus group-hover:rotate-90 transition-transform"></i>
                    <span>Nouvelle Facture</span>
                    <div class="absolute inset-0 bg-gradient-to-r from-white/10 to-transparent scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-500"></div>
                </a>
            </div>
        </div>

        {{-- MESSAGES FLASH --}}
        @if(session('success'))
        <div class="mb-8 bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-[2rem] shadow-sm flex items-center gap-4 animate-in fade-in slide-in-from-top-4 duration-500">
            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-circle-check text-emerald-500"></i>
            </div>
            <p class="text-sm font-bold tracking-tight">{{ session('success') }}</p>
        </div>
        @endif

        {{-- FILTERS & TOOLS (Placeholder for future search) --}}
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <button class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-filter text-indigo-500"></i> Filtrer
                </button>
                <button class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-download text-indigo-500"></i> Exporter
                </button>
            </div>
        </div>

        {{-- TABLE CARD --}}
        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-[0.2em]">Bon / Date</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-[0.2em]">Producteur & Zone</th>
                            <th class="hidden lg:table-cell px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-[0.2em]">Logistique</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-[0.2em]">Détails Fruits</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] text-right">Règlement Net</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($invoices as $invoice)
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            {{-- Bon/Date --}}
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                        <i class="fa-solid fa-hashtag text-xs"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-black text-slate-900 tracking-tight">{{ $invoice->bon_no }}</div>
                                        <div class="text-[10px] font-black text-indigo-500 flex items-center gap-1">
                                            <i class="fa-regular fa-calendar-check"></i>
                                            {{ $invoice->date_invoice->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Producteur --}}
                            <td class="px-8 py-6">
                                <div class="text-sm font-black text-slate-700 capitalize">
                                    {{ $invoice->producteur ?: 'Inconnu' }}
                                </div>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="inline-flex px-2 py-0.5 rounded-lg bg-orange-50 text-[9px] font-black text-orange-600 uppercase tracking-tighter">
                                        <i class="fa-solid fa-map-pin mr-1"></i> {{ $invoice->zone ?: '—' }}
                                    </span>
                                </div>
                            </td>

                            {{-- Logistique --}}
                            <td class="hidden lg:table-cell px-8 py-6">
                                <div class="flex flex-col gap-1.5">
                                    <div class="flex items-center gap-2 text-[10px] font-bold text-slate-500">
                                        <i class="fa-solid fa-truck-pickup w-3 text-indigo-400"></i>
                                        <span>Mat: <span class="font-black text-slate-800 uppercase">{{ $invoice->code_parcelle_matricule ?: '—' }}</span></span>
                                    </div>
                                    <div class="flex items-center gap-2 text-[10px] font-bold text-slate-500">
                                        <i class="fa-solid fa-user-gear w-3 text-indigo-400"></i>
                                        <span>Drv: <span class="font-black text-slate-800">{{ $invoice->chauffeur ?: '—' }}</span></span>
                                    </div>
                                </div>
                            </td>

                            {{-- Fruit & Poids --}}
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="inline-flex px-2 py-0.5 rounded-lg bg-indigo-50 text-[10px] font-black text-indigo-700 uppercase tracking-tighter">
                                        {{ $invoice->fruit ?: 'Fruitiers' }}
                                    </span>
                                    <span class="text-sm font-black text-slate-900 tracking-tight">
                                        {{ number_format($invoice->total_weight, 1, ',', ' ') }} <span class="text-[10px] text-slate-400">kg</span>
                                    </span>
                                </div>
                                <div class="flex items-center gap-3">
                                    @if($invoice->total_weight_pf > 0)
                                    <div class="flex items-center gap-1 text-[9px] font-black text-indigo-500">
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                        PF: {{ number_format($invoice->total_weight_pf, 1) }}
                                    </div>
                                    @endif
                                    @if($invoice->total_weight_gf > 0)
                                    <div class="flex items-center gap-1 text-[9px] font-black text-orange-500">
                                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                                        GF: {{ number_format($invoice->total_weight_gf, 1) }}
                                    </div>
                                    @endif
                                    @if($invoice->avarie_pct > 0)
                                    <div class="flex items-center gap-1 text-[9px] font-black text-red-500 ml-2">
                                        <i class="fa-solid fa-shield-virus"></i>
                                        -{{ number_format($invoice->avarie_pct, 1) }}%
                                    </div>
                                    @endif
                                </div>
                            </td>

                            {{-- Net à payer --}}
                            <td class="px-8 py-6 text-right">
                                <div class="inline-flex flex-col items-end">
                                    <span class="text-base font-black text-emerald-600 tracking-tighter">
                                        {{ number_format($invoice->net_a_payer, 0, ',', ' ') }} <span class="text-[10px]">FCFA</span>
                                    </span>
                                    <div class="flex items-center gap-2 mt-1">
                                        @if($invoice->total_credit > 0)
                                        <span class="px-1.5 py-0.5 rounded bg-red-50 text-[8px] font-black text-red-500 uppercase">
                                            Crédit déduit
                                        </span>
                                        @endif
                                        <span class="text-[9px] font-bold text-slate-400 italic">
                                            Val: {{ number_format($invoice->montant_total, 0, ',', ' ') }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            {{-- Actions --}}
                            <td class="px-8 py-6">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('purchase_invoices.show', $invoice) }}" 
                                       class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 transition shadow-sm" 
                                       title="Visualiser">
                                        <i class="fa-solid fa-eye text-sm"></i>
                                    </a>
                                    
                                    <a href="{{ route('purchase_invoices.pdf', $invoice) }}" 
                                       target="_blank"
                                       class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:bg-rose-50 hover:text-rose-600 transition shadow-sm" 
                                       title="PDF">
                                        <i class="fa-solid fa-file-pdf text-sm"></i>
                                    </a>
                                    
                                    @can('delete', $invoice)
                                    <form action="{{ route('purchase_invoices.destroy', $invoice) }}" 
                                          method="POST" 
                                          class="inline"
                                          onsubmit="return confirm('❗ ATTENTION : Cette action supprimera définitivement la facture. Confirmer ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:bg-red-50 hover:text-red-600 transition shadow-sm" 
                                                title="Supprimer">
                                            <i class="fa-solid fa-trash-alt text-sm"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="relative mb-6">
                                        <div class="absolute inset-0 bg-indigo-50 rounded-full scale-150 animate-ping opacity-20"></div>
                                        <div class="w-20 h-20 rounded-3xl bg-white border border-slate-100 shadow-xl flex items-center justify-center relative z-10">
                                            <i class="fa-solid fa-receipt text-slate-200 text-3xl"></i>
                                        </div>
                                    </div>
                                    <h3 class="text-lg font-black text-slate-900 mb-2">Aucune donnée disponible</h3>
                                    <p class="text-sm text-slate-400 max-w-xs mx-auto mb-8 font-medium">Votre registre de facturation est actuellement vide. Lancez une nouvelle opération d'achat dès maintenant.</p>
                                    <a href="{{ route('purchase_invoices.create') }}" 
                                       class="inline-flex items-center gap-3 px-8 py-3 bg-indigo-600 text-white rounded-2xl text-sm font-black hover:bg-indigo-700 shadow-xl shadow-indigo-100 transition truncate">
                                        <i class="fa-solid fa-plus"></i> Créer ma première facture
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- FOOTER / PAGINATION --}}
            @if($invoices->hasPages())
            <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
                {{ $invoices->links() }}
            </div>
            @endif
        </div>

        {{-- BOTTOM SUMMARY CARDS --}}
        @if($invoices->count() > 0)
        <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-indigo-950 p-6 rounded-[2rem] shadow-xl shadow-indigo-200/50 border border-indigo-900 relative overflow-hidden group">
                <i class="fa-solid fa-weight-hanging absolute -right-4 -bottom-4 text-6xl text-white/5 rotate-12 group-hover:scale-110 transition-transform"></i>
                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1">Volume récolté</p>
                <p class="text-2xl font-black text-white italic">
                    {{ number_format($invoices->sum('total_weight'), 1, ',', ' ') }} <span class="text-xs font-normal opacity-50">kg</span>
                </p>
            </div>
            
            <div class="bg-white p-6 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-200 relative overflow-hidden group">
                <i class="fa-solid fa-money-bill-transfer absolute -right-4 -bottom-4 text-6xl text-slate-50 rotate-12 group-hover:scale-110 transition-transform"></i>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Engagé financier</p>
                <p class="text-2xl font-black text-emerald-600 italic">
                    {{ number_format($invoices->sum('net_a_payer'), 0, ',', ' ') }} <span class="text-xs font-normal text-slate-300">FCFA</span>
                </p>
            </div>
            
            <div class="bg-white p-6 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-200 relative overflow-hidden group">
                <i class="fa-solid fa-percentage absolute -right-4 -bottom-4 text-6xl text-slate-50 rotate-12 group-hover:scale-110 transition-transform"></i>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Taux Perte moyen</p>
                @php $avgAvarie = $invoices->where('avarie_pct', '>', 0)->avg('avarie_pct') ?: 0; @endphp
                <p class="text-2xl font-black text-orange-500 italic">
                    {{ number_format($avgAvarie, 1, ',', ' ') }} <span class="text-xs font-normal text-slate-300">%</span>
                </p>
            </div>
            
            <div class="bg-white p-6 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-200 relative overflow-hidden group">
                <i class="fa-solid fa-award absolute -right-4 -bottom-4 text-6xl text-slate-50 rotate-12 group-hover:scale-110 transition-transform"></i>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Primes distribuées</p>
                <p class="text-2xl font-black text-indigo-600 italic">
                    {{ number_format($invoices->sum('montant_total_prime'), 0, ',', ' ') }} <span class="text-xs font-normal text-slate-300">FCFA</span>
                </p>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
    /* Custom Scrollbar for better UX in table headers */
    .overflow-x-auto::-webkit-scrollbar { height: 6px; }
    .overflow-x-auto::-webkit-scrollbar-track { background: #f8fafc; }
    .overflow-x-auto::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .overflow-x-auto::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
</style>
@endsection