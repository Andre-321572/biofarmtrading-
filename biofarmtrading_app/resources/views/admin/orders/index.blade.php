<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion des Commandes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50 text-gray-400 text-xs font-bold uppercase tracking-widest">
                                <th class="px-8 py-4">Commande</th>
                                <th class="px-8 py-4">Client</th>
                                <th class="px-8 py-4">Statut</th>
                                <th class="px-8 py-4">Paiement</th>
                                <th class="px-8 py-4 text-right">Montant</th>
                                <th class="px-8 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($orders as $order)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-8 py-6 font-mono text-sm text-gray-500">
                                        #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="px-8 py-6">
                                        <p class="font-bold text-gray-900">{{ $order->customer_name }}</p>
                                        <p class="text-xs text-gray-400">{{ $order->customer_phone }}</p>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest 
                                            @if($order->status === 'delivered') bg-green-100 text-green-700
                                            @elseif($order->status === 'pending') bg-yellow-100 text-yellow-700
                                            @elseif($order->status === 'cancelled') bg-red-100 text-red-700
                                            @else bg-blue-100 text-blue-700 @endif">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-medium text-gray-700 uppercase">{{ $order->payment_method }}</span>
                                            <span class="text-[10px] font-bold uppercase tracking-widest {{ $order->payment_status === 'paid' ? 'text-green-600' : 'text-orange-600' }}">
                                                {{ $order->payment_status }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-right font-black text-gray-900">
                                        {{ number_format($order->total_amount, 0) }} FCFA
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="px-4 py-2 bg-gray-900 text-white rounded-xl text-xs font-bold hover:bg-gray-800 transition-colors shadow-lg shadow-gray-200">
                                            Détails
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-8 py-4 bg-gray-50 border-t border-gray-100">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
