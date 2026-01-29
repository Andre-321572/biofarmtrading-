<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion des Stocks et Approvisionnements') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Alerts -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl" role="alert">
                    <p class="font-bold">Succès</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
             @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-xl" role="alert">
                    <p class="font-bold">Erreur</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Supply Form -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <h3 class="font-bold text-lg mb-6 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </span>
                        Nouvel Approvisionnement
                    </h3>

                    <form action="{{ route('admin.stock.store') }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div>
                            <x-input-label for="shop_id" :value="__('Boutique de destination')" />
                            <select id="shop_id" name="shop_id" class="mt-1 block w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm" required>
                                <option value="">Sélectionner une boutique...</option>
                                @foreach($shops as $shop)
                                    <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('shop_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="product_id" :value="__('Produit à livrer')" />
                            <select id="product_id" name="product_id" class="mt-1 block w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm" required>
                                <option value="">Sélectionner un produit...</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('product_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="quantity" :value="__('Quantité à ajouter')" />
                            <x-text-input id="quantity" class="block mt-1 w-full" type="number" name="quantity" min="1" required placeholder="Ex: 50" />
                            <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="note" :value="__('Note / Référence (Optionnel)')" />
                            <textarea id="note" name="note" class="mt-1 block w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm" rows="2" placeholder="Ex: Livraison usine #123"></textarea>
                            <x-input-error :messages="$errors->get('note')" class="mt-2" />
                        </div>

                        <div class="pt-4">
                            <x-primary-button class="w-full justify-center py-3 bg-blue-600 hover:bg-blue-700">
                                {{ __('Enregistrer l\'approvisionnement') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>

                <!-- Introduction / Summary or Product List -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 overflow-hidden">
                    <h3 class="font-bold text-lg mb-6">État actuel des Stocks</h3>
                    <div class="overflow-y-auto max-h-[500px] pr-2 custom-scrollbar">
                         <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50 text-gray-500">
                                <tr>
                                    <th class="px-4 py-2 rounded-l-lg">Produit</th>
                                    @foreach($shops as $shop)
                                        <th class="px-4 py-2 text-center">{{ $shop->name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($products as $product)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $product->name }}</td>
                                        @foreach($shops as $shop)
                                            @php
                                                $stock = $product->shops->find($shop->id)?->pivot->quantity ?? 0;
                                                $color = $stock < 10 ? 'text-red-600 bg-red-50' : ($stock < 50 ? 'text-yellow-600 bg-yellow-50' : 'text-green-600 bg-green-50');
                                            @endphp
                                            <td class="px-4 py-3 text-center">
                                                <span class="px-2 py-1 rounded-full font-bold text-xs {{ $color }}">
                                                    {{ $stock }}
                                                </span>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- History Table -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="font-bold text-lg">Historique des Mouvements de Stock</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500 uppercase font-bold text-xs">
                            <tr>
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4">Type</th>
                                <th class="px-6 py-4">Boutique</th>
                                <th class="px-6 py-4">Produit</th>
                                <th class="px-6 py-4 text-center">Quantité</th>
                                <th class="px-6 py-4">Auteur</th>
                                <th class="px-6 py-4">Note</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($movements as $movement)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 uppercase">
                                            {{ $movement->type }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $movement->shop->name }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $movement->product->name }}</td>
                                    <td class="px-6 py-4 text-center font-bold text-gray-800">+{{ $movement->quantity }}</td>
                                    <td class="px-6 py-4 text-gray-500">{{ $movement->user->name }}</td>
                                    <td class="px-6 py-4 text-gray-400 italic">{{Str::limit($movement->note, 30) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-400">Aucun mouvement récent.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-100">
                    {{ $movements->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
