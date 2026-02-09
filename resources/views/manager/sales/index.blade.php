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
                    <a href="{{ route('manager.stock.index') }}" class="p-4 bg-blue-600 text-white rounded-2xl shadow-xl shadow-blue-100 hover:bg-blue-500 transition-all group" title="Gérer Stock">
                        <svg class="w-6 h-6 transform group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    </a>
                    <a href="{{ route('manager.sales.report') }}" class="p-4 bg-slate-900 text-white rounded-2xl shadow-xl shadow-slate-200 hover:bg-slate-800 transition-all group" title="Rapport Mensuel">
                        <svg class="w-6 h-6 transform group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="space-y-8">
            
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl shadow-sm" role="alert">
                    <p class="font-bold">Succès</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
             @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-xl shadow-sm" role="alert">
                    <p class="font-bold">Erreur</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- POS Form -->
                <div class="lg:col-span-2 space-y-6" x-data="{ 
                    items: [{id: 1, product_id: '', unit_type: 'carton', quantity: 1, price: 0, subtotal: 0}],
                    products: @json($products),
                    addItem() {
                        this.items.push({id: Date.now(), product_id: '', unit_type: 'carton', quantity: 1, price: 0, subtotal: 0});
                    },
                    removeItem(id) {
                        this.items = this.items.filter(i => i.id !== id);
                    },
                    updateItem(item) {
                        const product = this.products.find(p => p.id == item.product_id);
                        if (product) {
                            item.price = item.unit_type === 'detail' ? (parseFloat(product.price_detail) || parseFloat(product.price)) : parseFloat(product.price);
                            item.subtotal = item.price * item.quantity;
                        } else {
                            item.price = 0;
                            item.subtotal = 0;
                        }
                    },
                    get totalSale() {
                        return this.items.reduce((sum, item) => sum + (parseFloat(item.subtotal) || 0), 0);
                    }
                }">
                    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                        <div class="p-6 md:p-8 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
                            <h3 class="font-black text-xl text-slate-800 flex items-center gap-3">
                                <span class="p-2.5 bg-green-600 text-white rounded-2xl shadow-lg shadow-green-200">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                </span>
                                Panier de Vente
                            </h3>
                            <button type="button" @click="addItem()" class="p-2 text-green-600 hover:bg-green-100 rounded-xl transition-colors flex items-center gap-2 font-bold text-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
                                <span class="hidden md:inline">Ajouter</span>
                            </button>
                        </div>

                        <form action="{{ route('manager.sales.store') }}" method="POST" id="pos-form" class="p-6 md:p-8">
                            @csrf
                            
                            <!-- Items List -->
                            <div class="space-y-4 mb-10">
                                <template x-for="(item, index) in items" :key="item.id">
                                    <div class="relative group grid grid-cols-12 gap-3 md:gap-4 items-start bg-slate-50/50 p-4 md:p-5 rounded-[2rem] border border-slate-100 hover:border-green-200 hover:bg-white hover:shadow-xl hover:shadow-slate-100 transition-all duration-300">
                                        <!-- Product Selection -->
                                        <div class="col-span-12 md:col-span-12 lg:col-span-5">
                                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Produit</label>
                                            <select 
                                                x-model="item.product_id" 
                                                @change="updateItem(item)"
                                                x-bind:name="`items[${index}][product_id]`" 
                                                class="w-full bg-white rounded-2xl border-slate-200 text-sm font-bold focus:ring-4 focus:ring-green-500/10 focus:border-green-500 transition-all py-3" 
                                                required
                                            >
                                                <option value="">Sélectionner un produit</option>
                                                @foreach($products as $product)
                                                    @php $stock = $product->shops->find($shop->id)->pivot->quantity; @endphp
                                                    <option value="{{ $product->id }}" {{ $stock <= 0 ? 'disabled' : '' }}>
                                                        {{ $product->name }} (Stock: {{ $stock }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Unit Type -->
                                        <div class="col-span-6 md:col-span-4 lg:col-span-3">
                                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Unité</label>
                                            <select 
                                                x-model="item.unit_type" 
                                                @change="updateItem(item)"
                                                x-bind:name="`items[${index}][unit_type]`" 
                                                class="w-full bg-white rounded-2xl border-slate-200 text-sm font-bold focus:ring-4 focus:ring-green-500/10 focus:border-green-500 transition-all py-3"
                                            >
                                                <option value="carton">En Gros (Carton)</option>
                                                <option value="detail">En Détail (Bouteille)</option>
                                            </select>
                                        </div>

                                        <!-- Quantity -->
                                        <div class="col-span-6 md:col-span-4 lg:col-span-2">
                                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Quantité</label>
                                            <input 
                                                type="number" 
                                                x-model="item.quantity" 
                                                @input="updateItem(item)"
                                                x-bind:name="`items[${index}][quantity]`" 
                                                class="w-full bg-white rounded-2xl border-slate-200 text-sm font-black focus:ring-4 focus:ring-green-500/10 focus:border-green-500 transition-all py-3" 
                                                min="1" 
                                                required 
                                            />
                                        </div>

                                        <!-- Subtotal and Remove -->
                                        <div class="col-span-12 md:col-span-4 lg:col-span-2 flex items-center justify-between md:justify-end gap-4 h-full pt-2 md:pt-6">
                                            <div class="text-right flex-1">
                                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Sous-total</p>
                                                <p class="text-lg font-black text-slate-900" x-text="new Intl.NumberFormat('fr-FR').format(item.subtotal) + ' F'"></p>
                                            </div>
                                            <button type="button" @click="removeItem(item.id)" class="p-2.5 bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all shadow-sm" x-show="items.length > 1">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Footer Stats & Submit -->
                            <div class="bg-slate-900 rounded-[2.5rem] p-8 md:p-10 shadow-2xl shadow-slate-900/30 text-white">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                                    <div class="space-y-6">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Paiement</label>
                                                <select name="payment_method" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-sm font-bold focus:ring-green-500 focus:border-green-500 text-white py-3">
                                                    <option value="cash">💸 Espèces</option>
                                                    <option value="tmoney">🟢 TMoney</option>
                                                    <option value="flooz">🔵 Flooz</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Client</label>
                                                <input type="text" name="customer_name" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-sm font-bold focus:ring-green-500 focus:border-green-500 text-white py-3" placeholder="Nom (facultatif)">
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-4 py-4 border-t border-slate-800">
                                            <div class="p-3 bg-white/10 rounded-2xl">
                                                <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                            </div>
                                            <div>
                                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total à encaisser</p>
                                                <p class="text-4xl font-black" x-text="new Intl.NumberFormat('fr-FR').format(totalSale) + ' FCFA'"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-4">
                                        <button type="submit" class="group relative w-full overflow-hidden py-5 bg-green-600 rounded-3xl font-black text-lg shadow-xl shadow-green-900/20 hover:bg-green-500 transition-all flex items-center justify-center gap-3">
                                            <span class="relative z-10 uppercase tracking-widest">Valider l'Encaissement</span>
                                            <svg class="w-6 h-6 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                                        </button>
                                        <p class="text-center text-[10px] text-slate-500 font-bold uppercase tracking-widest">Vérifiez les articles avant de valider</p>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


                <!-- Right Column: Pending & Recent Sales -->
                <div class="lg:col-span-1 space-y-8">
                    
                    <!-- Pending Web Orders -->
                    <div class="bg-indigo-600 rounded-[2.5rem] shadow-2xl shadow-indigo-200/50 p-6 md:p-8 text-white relative overflow-hidden">
                        <!-- Decorative Circle -->
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-500 rounded-full opacity-20"></div>
                        
                        <div class="relative z-10">
                            <h3 class="font-black text-xl mb-6 flex items-center gap-3">
                                <span class="flex h-3 w-3 relative">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-white"></span>
                                </span>
                                Commandes Web
                            </h3>
                            
                            @if($pendingOrders->count() > 0)
                                <div class="space-y-4 overflow-y-auto max-h-[500px] pr-2 custom-scrollbar">
                                    @foreach($pendingOrders as $order)
                                        <div class="bg-indigo-500/30 backdrop-blur-md rounded-3xl p-5 border border-white/10 hover:bg-indigo-500/40 transition-all group">
                                            <div class="flex justify-between items-start mb-4">
                                                <div>
                                                    <p class="font-black text-lg leading-tight uppercase tracking-tight">{{ $order->customer_name }}</p>
                                                    <p class="text-xs font-bold text-indigo-100 opacity-80">{{ $order->customer_phone }}</p>
                                                </div>
                                                <span class="bg-white/20 px-3 py-1 rounded-full text-[10px] font-black tracking-widest uppercase">#{{ $order->id }}</span>
                                            </div>
                                            
                                            <div class="space-y-2 mb-6 text-sm font-medium">
                                                @foreach($order->items as $item)
                                                    <div class="flex justify-between items-center bg-black/5 rounded-xl p-2 px-3">
                                                        <span>
                                                            <span class="font-black text-white">{{ $item->quantity }}</span> 
                                                            <span class="text-white/70">×</span> 
                                                            <span class="text-white/90">{{ $item->product->name }}</span>
                                                        </span>
                                                        <span class="text-[10px] font-black uppercase text-white/50">{{ $item->unit_type === 'detail' ? 'Détail' : 'Gros' }}</span>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <div class="flex justify-between items-center mb-6 px-1">
                                                <span class="text-indigo-200 font-bold text-xs uppercase tracking-widest">Total</span>
                                                <span class="font-black text-2xl tracking-tighter">{{ number_format($order->total_amount, 0, ',', ' ') }} F</span>
                                            </div>

                                            <form action="{{ route('manager.sales.validate', $order->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="w-full py-4 bg-white text-indigo-600 hover:bg-indigo-50 rounded-2xl text-sm font-black uppercase tracking-widest shadow-xl transition-all active:scale-[0.98]">
                                                    Valider la Sortie
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                               <div class="text-center py-10 bg-white/5 rounded-3xl border border-white/5">
                                    <svg class="w-12 h-12 mx-auto mb-3 opacity-30 shadow-inner" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                    <p class="text-indigo-100/50 font-bold uppercase text-[10px] tracking-widest">Aucune commande web</p>
                               </div>
                            @endif
                        </div>
                    </div>

                    <!-- Recent Sales History -->
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 p-6 md:p-8">
                        <div class="flex justify-between items-center mb-8">
                            <h3 class="font-black text-xl text-slate-800 uppercase tracking-tight">Ventes d'Aujourd'hui</h3>
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">Live</span>
                        </div>
                        
                        @if($validatedOrders->count() > 0)
                            <div class="space-y-4 overflow-y-auto max-h-[600px] pr-2 custom-scrollbar">
                                @foreach($validatedOrders as $order)
                                    <div class="group relative bg-slate-50/50 rounded-3xl p-5 border border-slate-100 hover:bg-white hover:shadow-xl hover:shadow-slate-100 transition-all duration-300">
                                        <div class="flex justify-between items-start mb-4">
                                            <div class="flex flex-col">
                                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $order->updated_at->format('H:i') }}</span>
                                                <span class="font-black text-slate-900 uppercase tracking-tight">#{{ $order->id }}</span>
                                            </div>
                                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest 
                                                {{ $order->payment_method === 'cash' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                                {{ $order->payment_method }}
                                            </span>
                                        </div>
                                        
                                        <div class="space-y-2 mb-4">
                                            @foreach($order->items as $item)
                                                <div class="text-sm font-bold text-slate-600 flex justify-between items-center bg-white/40 p-2 rounded-xl">
                                                    <span class="truncate pr-4">{{ $item->quantity }}x {{ $item->product->name }}</span>
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-[9px] font-black text-slate-300 uppercase">{{ $item->unit_type === 'detail' ? 'Détail' : 'Gros' }}</span>
                                                        <span class="text-slate-900 shrink-0 font-black">{{ number_format($item->price * $item->quantity, 0, ',', ' ') }} F</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="flex justify-between items-center pt-4 border-t border-slate-100 px-1">
                                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Payé</span>
                                            <span class="font-black text-xl text-green-600 tracking-tight">{{ number_format($order->total_amount, 0, ',', ' ') }} F</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-16">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                    <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <p class="text-slate-400 font-black uppercase text-[10px] tracking-widest">Aucune vente pour le moment</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
            </div>
        </div>
    </div>
</x-app-layout>
