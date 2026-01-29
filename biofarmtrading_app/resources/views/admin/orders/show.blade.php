<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Détails de la Commande') }} #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-50 transition-colors">
                    Retour
                </a>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer_phone) }}?text=Bonjour%20{{ urlencode($order->customer_name) }}%2C%20je%20vous%20contacte%20concernant%20votre%20commande%20sur%20Bio%20Farm%20Trading." target="_blank" class="px-4 py-2 bg-green-500 text-white rounded-xl font-bold hover:bg-green-600 transition-colors flex items-center shadow-lg shadow-green-100">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.484 8.412-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.309 1.656zm6.29-4.139c1.52.907 3.013 1.385 4.624 1.386 5.14 0 9.324-4.183 9.327-9.325.002-2.489-.965-4.831-2.724-6.592-1.758-1.759-4.101-2.727-6.591-2.729-5.141 0-9.324 4.183-9.327 9.326-.001 1.68.455 3.313 1.32 4.74l-.993 3.633 3.734-.979zm11.367-5.684c-.312-.156-1.848-.912-2.134-1.017-.286-.104-.494-.156-.703.156s-.807 1.017-1.003 1.24c-.195.223-.39.25-.703.093-.312-.156-1.317-.485-2.51-1.549-.928-.827-1.554-1.849-1.736-2.161-.182-.312-.019-.481.137-.636.141-.139.312-.364.469-.546.156-.182.208-.312.312-.52.104-.208.052-.39-.026-.546-.078-.156-.703-1.693-.963-2.319-.253-.611-.51-.528-.703-.537-.181-.009-.39-.01-.599-.01s-.547.078-.833.39c-.286.312-1.093 1.069-1.093 2.606s1.119 3.018 1.275 3.226c.156.208 2.201 3.361 5.333 4.715.745.322 1.327.515 1.779.659.749.238 1.43.205 1.969.125.599-.089 1.848-.756 2.108-1.485.26-.729.26-1.355.182-1.485-.078-.13-.286-.208-.599-.364z"/></svg>
                    WhatsApp
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Order Content -->
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-8 border-b border-gray-50 uppercase tracking-widest text-xs font-bold text-gray-400">
                            Articles Commandés
                        </div>
                        <div class="p-0">
                            <table class="w-full text-left">
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($order->items as $item)
                                        <tr class="hover:bg-gray-50/30 transition-colors">
                                            <td class="p-8">
                                                <div class="flex items-center space-x-4">
                                                    @if($item->product->image_path)
                                                        <img src="{{ asset('storage/' . $item->product->image_path) }}" class="w-16 h-16 rounded-2xl object-cover border border-gray-100 shadow-sm">
                                                    @else
                                                        <div class="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center text-green-200">
                                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <p class="font-black text-gray-900">{{ $item->product->name }}</p>
                                                        <p class="text-xs text-gray-400">{{ number_format($item->price, 0) }} FCFA x {{ $item->quantity }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-8 text-right font-black text-gray-900">
                                                {{ number_format($item->price * $item->quantity, 0) }} FCFA
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-gray-50/50">
                                        <td class="p-8 text-gray-500 font-bold uppercase tracking-widest text-xs">Total de la commande</td>
                                        <td class="p-8 text-right text-2xl font-black text-green-600">
                                            {{ number_format($order->total_amount, 0) }} FCFA
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Order Info & Actions -->
                <div class="space-y-8">
                    <!-- Statut Update -->
                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 space-y-6">
                        <div class="uppercase tracking-widest text-xs font-bold text-gray-400">Actions & Statut</div>
                        
                        <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <x-input-label for="status" value="Statut de livraison" class="mb-2" />
                            <select name="status" class="w-full border-gray-200 rounded-xl focus:border-green-500 focus:ring-green-500 text-sm font-bold text-gray-700 shadow-sm" onchange="this.form.submit()">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Traitement</option>
                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Expédiée</option>
                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Livrée</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Annulée</option>
                            </select>
                        </form>

                        <form action="{{ route('admin.orders.update-payment-status', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <x-input-label for="payment_status" value="Statut du paiement" class="mb-2" />
                            <select name="payment_status" class="w-full border-gray-200 rounded-xl focus:border-green-500 focus:ring-green-500 text-sm font-bold text-gray-700 shadow-sm" onchange="this.form.submit()">
                                <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Payé</option>
                                <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Échoué</option>
                            </select>
                        </form>
                    </div>

                    <!-- Customer Info -->
                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 space-y-6">
                        <div class="uppercase tracking-widest text-xs font-bold text-gray-400">Informations Client</div>
                        
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Nom complet</p>
                                <p class="text-sm font-bold text-gray-900">{{ $order->customer_name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Téléphone</p>
                                <p class="text-sm font-bold text-gray-900">{{ $order->customer_phone }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Mode de paiement</p>
                                <p class="text-sm font-bold text-green-600 uppercase">{{ $order->payment_method }}</p>
                            </div>
                            
                            @if($order->shop)
                                <div>
                                    <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Retrait en boutique</p>
                                    <p class="text-sm font-bold text-gray-900">{{ $order->shop->name }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $order->shop->address }}</p>
                                </div>
                            @else
                                <div>
                                    <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Adresse de livraison</p>
                                    <p class="text-sm font-bold text-gray-900">{{ $order->delivery_address }}</p>
                                </div>
                            @endif

                            @if($order->notes)
                                <div>
                                    <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Notes</p>
                                    <p class="text-sm italic text-gray-600">"{{ $order->notes }}"</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
