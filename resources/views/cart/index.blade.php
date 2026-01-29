@extends('layouts.store')

@section('title', 'Votre Panier - Bio Farm Trading')

@section('content')
<h1 class="text-4xl font-black text-gray-900 mb-12">Votre Panier</h1>

@if(count($cart) > 0)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Cart Items -->
        <div class="lg:col-span-2 space-y-6">
            @php $total = 0 @endphp
            @foreach($cart as $id => $details)
                @php $total += $details['price'] * $details['quantity'] @endphp
                <div class="flex flex-col sm:flex-row items-center bg-white p-6 rounded-3xl shadow-sm border border-gray-100 gap-6">
                    <div class="w-24 h-24 bg-gray-50 rounded-2xl overflow-hidden flex-shrink-0">
                        @if($details['image'])
                            <img src="{{ asset('storage/' . $details['image']) }}" alt="{{ $details['name'] }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-green-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-900">{{ $details['name'] }}</h3>
                        <p class="text-green-600 font-semibold mb-4">{{ number_format($details['price'], 0, ',', ' ') }} FCFA</p>
                        
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden bg-gray-50">
                                <button class="px-4 py-2 hover:bg-gray-100 text-gray-600" onclick="updateQuantity({{ $id }}, -1)">-</button>
                                <span class="px-4 py-2 font-bold text-gray-900" id="quantity-{{ $id }}">{{ $details['quantity'] }}</span>
                                <button class="px-4 py-2 hover:bg-gray-100 text-gray-600" onclick="updateQuantity({{ $id }}, 1)">+</button>
                            </div>
                            <button class="text-red-400 hover:text-red-600 font-medium text-sm transition-colors" onclick="removeItem({{ $id }})">Supprimer</button>
                        </div>
                    </div>
                    
                    <div class="text-right">
                        <p class="text-lg font-black text-gray-900">
                            {{ number_format($details['price'] * $details['quantity'], 0, ',', ' ') }} FCFA
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Summary -->
        <div class="col-span-1">
            <div class="bg-white p-8 rounded-3xl shadow-xl border border-gray-100 sticky top-32">
                <h2 class="text-2xl font-bold mb-8">Récapitulatif</h2>
                
                <div class="space-y-4 mb-8">
                    <div class="flex justify-between text-gray-500">
                        <span>Sous-total</span>
                        <span>{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>Livraison</span>
                        <span class="text-xs italic">Calculé à l'étape suivante</span>
                    </div>
                    <div class="border-t border-gray-100 pt-4 flex justify-between items-center">
                        <span class="text-xl font-bold text-gray-900">Total</span>
                        <span class="text-2xl font-black text-green-600">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>
                
                <a href="{{ route('checkout.index') }}" class="block w-full py-5 gradient-brand text-white text-center font-bold rounded-2xl shadow-lg hover:shadow-2xl btn-premium mb-4">
                    Passer à la Caisse
                </a>
                
                <a href="{{ route('home') }}" class="block w-full py-4 text-center text-gray-500 font-semibold hover:text-green-600 transition-colors">
                    Continuer les achats
                </a>
            </div>
        </div>
    </div>
@else
    <div class="text-center py-24 glass rounded-[3rem]">
        <div class="w-24 h-24 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
        </div>
        <h2 class="text-3xl font-bold text-gray-900 mb-4">Votre panier est vide</h2>
        <p class="text-gray-500 mb-10 max-w-sm mx-auto">Explorez notre catalogue de délices naturels et remplissez votre panier.</p>
        <a href="{{ route('home') }}" class="inline-block px-10 py-5 gradient-brand text-white font-bold rounded-2xl shadow-xl hover:shadow-2xl btn-premium">
            Découvrir nos produits
        </a>
    </div>
@endif

<script>
    function updateQuantity(id, delta) {
        let currentQty = parseInt(document.getElementById('quantity-' + id).innerText);
        let newQty = currentQty + delta;
        if (newQty < 1) return;

        fetch("{{ route('cart.update') }}", {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                id: id,
                quantity: newQty
            })
        }).then(() => window.location.reload());
    }

    function removeItem(id) {
        fetch("{{ route('cart.remove') }}", {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                id: id
            })
        }).then(() => window.location.reload());
    }
</script>
@endsection
