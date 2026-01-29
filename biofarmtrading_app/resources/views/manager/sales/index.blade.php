<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-3">
                <span class="p-2 bg-green-100 text-green-700 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </span>
                {{ __('Point de Vente') }} - <span class="text-green-600">{{ $shop->name }}</span>
            </h2>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Total Journée</p>
                    <p class="text-2xl font-black text-gray-900">{{ number_format($todaysTotal, 0, ',', ' ') }} <span class="text-sm font-medium text-gray-400">FCFA</span></p>
                </div>
                <a href="{{ route('manager.stock.index') }}" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-xl shadow-lg hover:bg-blue-700 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    Gérer Stock
                </a>
                <a href="{{ route('manager.sales.report') }}" class="px-6 py-3 bg-gray-900 text-white font-bold rounded-xl shadow-lg hover:bg-gray-800 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Rapport
                </a>
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
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                        <h3 class="font-bold text-lg mb-6 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-green-50 text-green-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            </span>
                            Nouvelle Vente
                        </h3>
                        
                        <form action="{{ route('manager.sales.store') }}" method="POST" id="pos-form">
                            @csrf
                            
                            <div class="space-y-4 mb-8" x-data="{ items: [{id: 1, product_id: '', quantity: 1}] }">
                                <template x-for="(item, index) in items" :key="item.id">
                                    <div class="grid grid-cols-12 gap-4 items-end bg-gray-50 p-4 rounded-xl border border-gray-100">
                                        <div class="col-span-12 md:col-span-7">
                                            <x-input-label :value="__('Produit')" class="mb-1" />
                                            <select x-bind:name="`items[${index}][product_id]`" class="w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm" required>
                                                <option value="">Choisir un produit...</option>
                                                @foreach($products as $product)
                                                    @php
                                                        $stock = $product->shops->find($shop->id)->pivot->quantity;
                                                    @endphp
                                                    <option value="{{ $product->id }}" {{ $stock <= 0 ? 'disabled' : '' }}>
                                                        {{ $product->name }} - {{ number_format($product->price, 0) }} FCFA (Stock: {{ $stock }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-span-8 md:col-span-3">
                                            <x-input-label :value="__('Quantité')" class="mb-1" />
                                            <x-text-input type="number" x-bind:name="`items[${index}][quantity]`" class="w-full" min="1" value="1" required />
                                        </div>
                                        <div class="col-span-4 md:col-span-2">
                                            <button type="button" @click="items = items.filter(i => i.id !== item.id)" class="w-full py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors" x-show="items.length > 1">
                                                <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                                
                                <button type="button" @click="items.push({id: Date.now(), product_id: '', quantity: 1})" class="w-full py-3 border-2 border-dashed border-gray-300 text-gray-500 rounded-xl hover:border-green-500 hover:text-green-600 transition-all font-bold">
                                    + Ajouter un autre produit
                                </button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 pt-6 border-t border-gray-100">
                                <div>
                                    <x-input-label for="payment_method" :value="__('Mode de Paiement')" />
                                    <select id="payment_method" name="payment_method" class="mt-1 block w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm">
                                        <option value="cash">Espèces</option>
                                        <option value="tmoney">TMoney</option>
                                        <option value="flooz">Flooz</option>
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                     <div>
                                        <x-input-label for="customer_name" :value="__('Nom Client (Optionnel)')" />
                                        <x-text-input id="customer_name" name="customer_name" type="text" class="mt-1 block w-full" placeholder="Client Comptoir" />
                                    </div>
                                     <div>
                                        <x-input-label for="customer_phone" :value="__('Tél Client (Optionnel)')" />
                                        <x-text-input id="customer_phone" name="customer_phone" type="text" class="mt-1 block w-full" placeholder="90..." />
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <x-primary-button class="py-4 px-8 bg-green-600 hover:bg-green-700 text-lg shadow-lg shadow-green-200 w-full md:w-auto justify-center">
                                    {{ __('Encaisser la Vente') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Pending & Recent Sales -->
                <div class="lg:col-span-1 space-y-8">
                    
                    <!-- Pending Web Orders -->
                    <div class="bg-indigo-50 rounded-3xl shadow-sm border border-indigo-100 p-6">
                        <h3 class="font-bold text-lg mb-4 text-indigo-900 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                            Commandes Web (A Valider)
                        </h3>
                        @if($pendingOrders->count() > 0)
                            <div class="space-y-4 overflow-y-auto max-h-[400px] pr-2 custom-scrollbar">
                                @foreach($pendingOrders as $order)
                                    <div class="bg-white rounded-2xl p-4 shadow-sm border border-indigo-100">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <p class="font-bold text-gray-900">{{ $order->customer_name }}</p>
                                                <p class="text-xs text-gray-500">{{ $order->customer_phone }}</p>
                                                <p class="text-[10px] text-indigo-500 font-bold uppercase mt-1">{{ $order->delivery_address ?? 'Retrait Boutique' }}</p>
                                            </div>
                                            <span class="text-xs font-bold text-gray-400">#{{ $order->id }}</span>
                                        </div>
                                        
                                        <div class="space-y-1 mb-3 pt-2 border-t border-dashed border-gray-200">
                                            @foreach($order->items as $item)
                                                <div class="text-sm text-gray-600 flex justify-between">
                                                    <span>{{ $item->quantity }}x {{ $item->product->name }}</span>
                                                    <span class="font-medium text-gray-900">{{ number_format($item->price * $item->quantity, 0) }} F</span>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="flex justify-between items-center pt-2 border-t border-gray-200 mb-4">
                                            <span class="text-sm font-medium text-gray-500">Total</span>
                                            <span class="font-black text-gray-900">{{ number_format($order->total_amount, 0) }} F</span>
                                        </div>

                                        <form action="{{ route('manager.sales.validate', $order->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-indigo-200 transition-colors">
                                                Valider & Livrer
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @else
                           <p class="text-center text-sm text-indigo-300 py-4">Aucune commande web en attente.</p>
                        @endif
                    </div>

                    <!-- Validated Orders History -->
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-bold text-lg mb-6 text-gray-800">Ventes du Jour (Validées)</h3>
                        
                        @if($validatedOrders->count() > 0)
                            <div class="space-y-4 overflow-y-auto max-h-[600px] pr-2 custom-scrollbar">
                                @foreach($validatedOrders as $order)
                                    <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100 hover:bg-green-50 transition-colors">
                                        <div class="flex justify-between items-start mb-2">
                                            <span class="text-xs font-bold text-gray-400">#{{ $order->id }} • {{ $order->updated_at->format('H:i') }}</span>
                                            <span class="text-xs font-bold uppercase px-2 py-1 rounded-full {{ $order->payment_method === 'cash' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                                {{ $order->payment_method }}
                                            </span>
                                        </div>
                                        <div class="space-y-1 mb-3">
                                            @foreach($order->items as $item)
                                                <div class="text-sm text-gray-600 flex justify-between">
                                                    <span>{{ $item->quantity }}x {{ $item->product->name }}</span>
                                                    <span class="font-medium text-gray-900">{{ number_format($item->price * $item->quantity, 0) }} F</span>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Total</span>
                                            <span class="font-black text-gray-900">{{ number_format($order->total_amount, 0) }} F</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12 text-gray-400">
                                <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <p>Aucune vente validée aujourd'hui.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
