@extends('layouts.store')

@section('title', 'Commande Réussie - Bio Farm Trading')

@section('content')
<div class="max-w-3xl mx-auto text-center py-16">
    <div class="w-32 h-32 gradient-brand text-white rounded-[2.5rem] flex items-center justify-center mx-auto mb-12 shadow-2xl animate-bounce">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
        </svg>
    </div>

    <h1 class="text-5xl font-black text-gray-900 mb-6 tracking-tight">C'est confirmé !</h1>
    <p class="text-xl text-gray-500 mb-12 max-w-lg mx-auto leading-relaxed">
        Merci pour votre confiance, <span class="font-bold text-gray-900">{{ $order->customer_name }}</span>. Votre commande <span class="text-green-600 font-mono">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span> est en cours de préparation.
    </p>

    <!-- Order Details Card -->
    <div class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 overflow-hidden text-left mb-12">
        <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Détails de la commande</span>
                <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
            <div class="text-right">
                <span class="px-4 py-1.5 bg-green-100 text-green-700 rounded-full text-xs font-bold uppercase tracking-widest leading-loose">
                    {{ $order->payment_method }}
                </span>
            </div>
        </div>
        
        <div class="p-8 space-y-6">
            @foreach($order->items as $item)
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <span class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center font-bold text-gray-600">
                            {{ $item->quantity }}
                        </span>
                        <div>
                            <p class="font-bold text-gray-900">{{ $item->product->name }}</p>
                            <p class="text-xs text-gray-400">{{ number_format($item->price, 0) }} FCFA / unité</p>
                        </div>
                    </div>
                    <span class="font-bold text-gray-900">{{ number_format($item->price * $item->quantity, 0) }} FCFA</span>
                </div>
            @endforeach
            
            <div class="border-t border-gray-50 pt-6 flex justify-between items-center">
                <p class="text-xl font-bold text-gray-900">Total payé</p>
                <p class="text-3xl font-black text-green-600">{{ number_format($order->total_amount, 0) }} FCFA</p>
            </div>
        </div>

        <div class="p-8 bg-green-50/30 flex items-start gap-4">
            <div class="p-3 bg-white rounded-2xl shadow-sm text-green-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h4 class="font-bold text-gray-900">Prochaine étape</h4>
                <p class="text-sm text-gray-600 leading-relaxed">
                    Un agent Bio Farm Trading va vous appeler au <span class="font-bold">{{ $order->customer_phone }}</span> pour confirmer le créneau de livraison ou le retrait en boutique.
                </p>
            </div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="{{ route('home') }}" class="px-10 py-5 bg-gray-900 text-white font-bold rounded-2xl shadow-xl hover:bg-black transition-all btn-premium">
            Retour à l'accueil
        </a>
        <a href="https://wa.me/22890000000?text=Ma%20commande%20%23{{ $order->id }}%20est%20confirmee%21" target="_blank" class="px-10 py-5 bg-white border-2 border-green-600 text-green-600 font-bold rounded-2xl hover:bg-green-50 transition-all btn-premium flex items-center justify-center gap-2">
            Nous écrire sur WhatsApp
        </a>
    </div>
</div>
@endsection
