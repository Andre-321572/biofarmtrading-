<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-3">
            <span class="p-2 bg-blue-100 text-blue-700 rounded-xl">
                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
            </span>
            {{ __('Gestion du Stock') }} - <span class="text-blue-600">{{ $shop->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
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

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Stock List -->
                 <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <h3 class="font-bold text-lg mb-6">Niveaux de Stock Actuels</h3>
                    <div class="overflow-y-auto max-h-[600px] pr-2 custom-scrollbar">
                         <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50 text-gray-500">
                                <tr>
                                    <th class="px-4 py-3 rounded-l-lg">Produit</th>
                                    <th class="px-4 py-3 text-right rounded-r-lg">Quantité</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($products as $product)
                                    @php
                                        $stock = $product->shops->first()->pivot->quantity;
                                        $color = $stock < 10 ? 'text-red-600 bg-red-50' : ($stock < 50 ? 'text-yellow-600 bg-yellow-50' : 'text-green-600 bg-green-50');
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $product->name }}</td>
                                        <td class="px-4 py-3 text-right">
                                            <span class="px-3 py-1 rounded-full font-bold text-sm {{ $color }}">
                                                {{ $stock }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Adjustment Form & History -->
                <div class="space-y-8">
                    <!-- Form -->
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                         <h3 class="font-bold text-lg mb-6 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-yellow-50 text-yellow-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg>
                            </span>
                             Signaler une Anomalie / Ajustement
                        </h3>
                        <p class="text-sm text-gray-500 mb-4">Utilisez ce formulaire pour corriger le stock (perte, vol, casse, ou inventaire).</p>
                        
                        <form action="{{ route('manager.stock.adjust') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <x-input-label for="product_id" :value="__('Produit Concerné')" />
                                <select id="product_id" name="product_id" class="mt-1 block w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm" required>
                                    <option value="">Choisir...</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }} (Actuel: {{ $product->shops->first()->pivot->quantity }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="quantity" :value="__('Quantité (Négatif pour perte)')" />
                                    <x-text-input id="quantity" class="block mt-1 w-full" type="number" name="quantity" required placeholder="-1" />
                                </div>
                                <div class="flex items-end">
                                    <p class="text-xs text-gray-400 pb-3">Ex: -1 pour une perte, +1 pour un surplus.</p>
                                </div>
                            </div>

                            <div>
                                <x-input-label for="reason" :value="__('Motif')" />
                                <x-text-input id="reason" class="block mt-1 w-full" type="text" name="reason" required placeholder="Bouteille cassée, Perte..." />
                            </div>

                            <div class="pt-2">
                                <x-primary-button class="w-full justify-center bg-yellow-600 hover:bg-yellow-700">
                                    Enregistrer l'ajustement
                                </x-primary-button>
                            </div>
                        </form>
                    </div>

                    <!-- History -->
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="font-bold text-gray-800">Derniers Mouvements</h3>
                        </div>
                         <div class="overflow-y-auto max-h-[300px]">
                            <table class="w-full text-left text-sm">
                                <tbody class="divide-y divide-gray-50">
                                    @forelse($movements as $movement)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-3">
                                                 <div class="text-gray-900 font-medium">{{ $movement->product->name }}</div>
                                                 <div class="text-xs text-gray-400">{{ $movement->created_at->format('d/m/Y H:i') }}</div>
                                            </td>
                                            <td class="px-6 py-3">
                                                <span class="px-2 py-1 rounded-full text-xs font-bold uppercase 
                                                    {{ $movement->quantity > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                                    {{ $movement->quantity > 0 ? '+' : '' }}{{ $movement->quantity }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-3 text-gray-500 text-xs">
                                                {{ $movement->type }} <br>
                                                <span class="italic text-gray-400">{{ $movement->note }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="p-6 text-center text-gray-400">Aucun mouvement.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
