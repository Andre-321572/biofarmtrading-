@extends('layouts.store')

@section('title', 'Caisse - Bio Farm Trading')

@section('content')
<h1 class="text-4xl font-black text-gray-900 mb-12 text-center">Finaliser votre commande</h1>

<div class="max-w-5xl mx-auto">
    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Delivery & Payment Info -->
            <div class="space-y-8">
                <!-- Customer Info -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <h2 class="text-2xl font-bold mb-6 flex items-center gap-3">
                        <span class="w-8 h-8 gradient-brand text-white flex items-center justify-center rounded-lg text-sm">1</span>
                        Vos coordonnées
                    </h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nom complet</label>
                            <input type="text" name="customer_name" required value="{{ auth()->user()?->name }}"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Numéro de téléphone</label>
                            <input type="tel" name="customer_phone" required placeholder="Ex: +228 90 00 00 00"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all outline-none">
                        </div>
                    </div>
                </div>

                <!-- Delivery Type -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <h2 class="text-2xl font-bold mb-6 flex items-center gap-3">
                        <span class="w-8 h-8 gradient-brand text-white flex items-center justify-center rounded-lg text-sm">2</span>
                        Mode de livraison
                    </h2>
                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative flex flex-col p-4 border-2 rounded-2xl cursor-pointer transition-all hover:border-green-200" id="label-pickup">
                                <input type="radio" name="delivery_type" value="pickup" checked class="absolute top-4 right-4 text-green-600 focus:ring-green-500" onclick="toggleDelivery('pickup')">
                                <span class="font-bold text-gray-900">Retrait</span>
                                <span class="text-xs text-gray-500">En boutique</span>
                            </label>
                            <label class="relative flex flex-col p-4 border-2 rounded-2xl cursor-pointer transition-all hover:border-green-200" id="label-delivery">
                                <input type="radio" name="delivery_type" value="delivery" class="absolute top-4 right-4 text-green-600 focus:ring-green-500" onclick="toggleDelivery('delivery')">
                                <span class="font-bold text-gray-900">Livraison</span>
                                <span class="text-xs text-gray-500">À domicile</span>
                            </label>
                        </div>

                        <div id="shop-selection">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Choisir la boutique de retrait / traitement</label>
                            <select name="shop_id" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-green-500 outline-none">
                                @foreach($shops as $shop)
                                    <option value="{{ $shop->id }}">{{ $shop->name }} - {{ $shop->address }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-2 italic">Cette boutique traitera votre commande.</p>
                        </div>

                        <div id="address-selection" class="hidden">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Adresse de livraison</label>
                            <textarea name="delivery_address" placeholder="Quartier, Rue, Maison, Point de repère..." rows="3"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-green-500 outline-none"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <h2 class="text-2xl font-bold mb-6 flex items-center gap-3">
                        <span class="w-8 h-8 gradient-brand text-white flex items-center justify-center rounded-lg text-sm">3</span>
                        Paiement
                    </h2>
                    <div class="space-y-4">
                        <label class="flex items-center p-4 border rounded-2xl cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="radio" name="payment_method" value="cash" checked class="text-green-600 focus:ring-green-500 mr-4">
                            <div>
                                <span class="font-bold text-gray-900">Espèces</span>
                                <p class="text-xs text-gray-500 italic">Paiement à la livraison ou au retrait</p>
                            </div>
                        </label>
                        <label class="flex items-center p-4 border rounded-2xl cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="radio" name="payment_method" value="tmoney" class="text-green-600 focus:ring-green-500 mr-4">
                            <div>
                                <span class="font-bold text-gray-900 font-mono">TMoney</span>
                                <p class="text-xs text-gray-500 italic">Un agent vous contactera pour le transfert</p>
                            </div>
                        </label>
                        <label class="flex items-center p-4 border rounded-2xl cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="radio" name="payment_method" value="flooz" class="text-green-600 focus:ring-green-500 mr-4">
                            <div>
                                <span class="font-bold text-gray-900">Moov Money (Flooz)</span>
                                <p class="text-xs text-gray-500 italic">Un agent vous contactera pour le transfert</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="col-span-1">
                <div class="bg-gray-900 text-white p-8 rounded-[2.5rem] shadow-2xl sticky top-32">
                    <h2 class="text-2xl font-bold mb-8">Votre commande</h2>
                    
                    <div class="space-y-6 mb-8 max-h-[40vh] overflow-y-auto pr-4 scrollbar-thin scrollbar-thumb-gray-700">
                        @php $total = 0 @endphp
                        @foreach($cart as $id => $item)
                            @php $total += $item['price'] * $item['quantity'] @endphp
                            <div class="flex justify-between items-center bg-white/5 p-4 rounded-2xl">
                                <div class="flex-1">
                                    <p class="font-bold">{{ $item['name'] }}</p>
                                    <p class="text-xs text-gray-400">Qty: {{ $item['quantity'] }} &times; {{ number_format($item['price'], 0) }}</p>
                                </div>
                                <span class="font-bold text-green-400">{{ number_format($item['price'] * $item['quantity'], 0) }} FCFA</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-white/10 pt-6 space-y-4">
                        <div class="flex justify-between text-gray-400">
                            <span>Sous-total</span>
                            <span class="text-white font-semibold">{{ number_format($total, 0) }} FCFA</span>
                        </div>
                        <div class="flex justify-between text-gray-400">
                            <span>Frais de livraison</span>
                            <span class="text-xs italic bg-green-500/10 text-green-400 px-2 py-1 rounded">Gratuit (Bio Farm!)</span>
                        </div>
                        <div class="border-t border-white/10 pt-6 flex justify-between items-center">
                            <span class="text-xl font-bold">Total à payer</span>
                            <span class="text-3xl font-black text-green-500">{{ number_format($total, 0) }} FCFA</span>
                        </div>
                    </div>

                    <button type="submit" class="mt-10 w-full py-5 gradient-brand text-white font-bold rounded-2xl shadow-xl hover:shadow-2xl btn-premium text-lg">
                        Confirmer la commande
                    </button>
                    
                    <p class="mt-6 text-center text-xs text-gray-500 px-4">
                        En confirmant, vous acceptez nos conditions de vente et de livraison locales.
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function toggleDelivery(type) {
        if (type === 'pickup') {
            document.getElementById('address-selection').classList.add('hidden');
            document.getElementById('label-pickup').classList.add('border-green-500');
            document.getElementById('label-delivery').classList.remove('border-green-500');
        } else {
            document.getElementById('address-selection').classList.remove('hidden');
            document.getElementById('label-pickup').classList.remove('border-green-500');
            document.getElementById('label-delivery').classList.add('border-green-500');
        }
    }
    // Set initial state
    toggleDelivery('pickup');
</script>
@endsection
