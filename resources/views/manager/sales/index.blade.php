<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-green-600 shadow-lg shadow-green-200 rounded-2xl text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
                <div>
                    <h2 class="font-black text-2xl text-slate-800 leading-tight uppercase tracking-tighter">
                        Caisse <span class="text-green-600">POS</span>
                    </h2>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $shop->name }}</p>
                </div>
            </div>
            
            <div class="flex flex-wrap items-center justify-center md:justify-end gap-6 w-full md:w-auto">
                <div class="text-center md:text-right px-6 py-2 bg-white rounded-2xl border border-slate-100 shadow-sm">
                    <p class="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em] mb-0.5">Total Journée</p>
                    <p class="text-2xl font-black text-slate-900 tracking-tighter">{{ number_format($todaysTotal, 0, ',', ' ') }} <span class="text-xs font-bold text-slate-300">F</span></p>
                </div>
                
                <div class="flex items-center gap-3">
                    <a href="{{ route('manager.stock.index') }}" class="p-4 bg-blue-600 text-white rounded-2xl shadow-xl shadow-blue-100 hover:bg-blue-500 transition-all group">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    </a>
                    <a href="{{ route('manager.sales.report') }}" class="p-4 bg-slate-900 text-white rounded-2xl shadow-xl shadow-slate-200 hover:bg-slate-800 transition-all group">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="posSystem()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl" role="alert">
                    <p class="font-bold">Succès : {{ session('success') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Formulaire -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                        <div class="p-6 bg-slate-50 border-b flex justify-between items-center">
                            <h3 class="font-black text-xl text-slate-800 flex items-center gap-3">
                                <span class="p-2.5 bg-green-600 text-white rounded-2xl shadow-lg shadow-green-200">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                </span>
                                Panier de Vente
                            </h3>
                            <button type="button" @click="addItem()" class="px-4 py-2 bg-green-600 text-white rounded-xl font-bold text-sm">+ Ajouter</button>
                        </div>

                        <form action="{{ route('manager.sales.store') }}" method="POST" class="p-6">
                            @csrf
                            <div class="space-y-4 mb-8">
                                <template x-for="(item, index) in items" :key="item.id">
                                    <div class="grid grid-cols-12 gap-3 p-4 bg-slate-50 rounded-2xl border">
                                        <div class="col-span-12 lg:col-span-5">
                                            <select x-model="item.product_id" @change="updatePrice(item)" :name="'items['+index+'][product_id]'" class="w-full rounded-xl border-slate-200 text-sm font-bold" required>
                                                <option value="">Produit...</option>
                                                @foreach($products as $p)
                                                    @php $stock = $p->shops->find($shop->id)->pivot->quantity ?? 0; @endphp
                                                    <option value="{{ $p->id }}" {{ $stock <= 0 ? 'disabled' : '' }}>
                                                        {{ $p->name }} (Stock: {{ $stock }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-span-6 lg:col-span-3">
                                            <select x-model="item.unit_type" @change="updatePrice(item)" :name="'items['+index+'][unit_type]'" class="w-full rounded-xl border-slate-200 text-sm font-bold">
                                                <option value="carton">Gros</option>
                                                <option value="detail">Détail</option>
                                            </select>
                                        </div>
                                        <div class="col-span-4 lg:col-span-2">
                                            <input type="number" x-model="item.quantity" @input="updatePrice(item)" :name="'items['+index+'][quantity]'" class="w-full rounded-xl border-slate-200 text-sm font-black" min="1" required />
                                        </div>
                                        <div class="col-span-2 flex items-center justify-end">
                                            <button type="button" @click="removeItem(item.id)" class="text-red-500" x-show="items.length > 1">🗑️</button>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <div class="bg-slate-900 rounded-3xl p-8 text-white">
                                <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-6">
                                    <div>
                                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Total à encaisser</p>
                                        <span class="text-4xl font-black text-green-400" x-text="totalFormat() + ' FCFA'"></span>
                                    </div>
                                    <div class="flex gap-4 w-full md:w-auto">
                                        <div class="flex-1">
                                            <select name="payment_method" class="w-full bg-slate-800 border-none rounded-xl text-sm font-bold text-white py-3">
                                                <option value="cash">💸 Espèces</option>
                                                <option value="tmoney">🟢 TMoney</option>
                                                <option value="flooz">🔵 Flooz</option>
                                            </select>
                                        </div>
                                        <div class="flex-1">
                                            <input type="text" name="customer_name" class="w-full bg-slate-800 border-none rounded-xl text-sm font-bold text-white py-3" placeholder="Client">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="w-full py-5 bg-green-600 rounded-3xl font-black text-lg hover:bg-green-500 transition-all shadow-xl shadow-green-900/40 uppercase tracking-widest">
                                    Valider l'Encaissement
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Historique -->
                <div class="lg:col-span-1 space-y-8">
                    <div class="bg-white rounded-3xl shadow-xl p-6 border border-slate-100">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="font-black text-xl text-slate-800">Ventes du jour</h3>
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] font-black uppercase">Live</span>
                        </div>
                        
                        <div class="space-y-4">
                            @foreach($validatedOrders as $o)
                                <div class="p-4 bg-slate-50 rounded-2xl text-sm border border-slate-100 hover:bg-white transition-all">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <p class="text-[10px] font-black text-slate-400">#{{ $o->id }} - {{ $o->updated_at->format('H:i') }}</p>
                                            <p class="font-black text-slate-900">{{ number_format($o->total_amount, 0, ',', ' ') }} F</p>
                                        </div>
                                        <span class="text-[10px] font-black uppercase px-2 py-1 bg-slate-200 rounded-lg text-slate-600">{{ $o->payment_method }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function posSystem() {
            return {
                items: [{id: 1, product_id: '', unit_type: 'carton', quantity: 1, price: 0, subtotal: 0}],
                products: @json($products),
                addItem() {
                    this.items.push({id: Date.now(), product_id: '', unit_type: 'carton', quantity: 1, price: 0, subtotal: 0});
                },
                removeItem(id) {
                    this.items = this.items.filter(i => i.id !== id);
                },
                updatePrice(item) {
                    let p = this.products.find(pr => pr.id == item.product_id);
                    if (p) {
                        item.price = item.unit_type === 'detail' ? (parseFloat(p.price_detail) || parseFloat(p.price)) : parseFloat(p.price);
                        item.subtotal = item.price * item.quantity;
                    } else {
                        item.price = 0;
                        item.subtotal = 0;
                    }
                },
                totalFormat() {
                    let total = this.items.reduce((s, i) => s + (parseFloat(i.subtotal) || 0), 0);
                    return new Intl.NumberFormat('fr-FR').format(total);
                }
            }
        }
    </script>
</x-app-layout>
