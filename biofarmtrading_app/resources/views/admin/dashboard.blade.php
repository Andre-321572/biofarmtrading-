<x-app-layout>
    <x-slot name="header">
        <div class="flex-1 flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                {{ __('Tableau de Bord Administration') }}
            </h2>
            <button onclick="window.adminNotifications.enableAudio()" class="px-5 py-2.5 bg-green-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-green-900/20 hover:bg-green-700 hover:scale-105 transition-all duration-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" /></svg>
                Alertes sonores
            </button>
        </div>
    </x-slot>

    <div class="space-y-8">
        
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-green-100 text-green-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Revenus Total</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_revenue'], 0) }} <span class="text-sm font-semibold">FCFA</span></p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-blue-100 text-blue-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Commandes</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['orders_count'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-purple-100 text-purple-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Clients</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['customers_count'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-red-100 text-red-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Stock Faible</p>
                        <p class="text-2xl font-bold text-red-600">{{ $stats['low_stock_count'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($lowStockProducts->count() > 0)
            <div class="bg-red-50 border border-red-100 p-6 rounded-2xl">
                <div class="flex items-center gap-3 mb-4">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    <h3 class="text-sm font-bold text-red-800 uppercase tracking-wider">Alerte Stock Critique</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($lowStockProducts as $product)
                        <div class="bg-white p-4 rounded-xl border border-red-50 flex justify-between items-center text-sm shadow-sm">
                            <span class="font-bold text-gray-700">{{ $product->name }}</span>
                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full font-bold">{{ $product->stock }} restant(s)</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Recent Orders -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 uppercase tracking-wider text-sm">Dernières Commandes</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-green-600 hover:text-green-700 uppercase tracking-widest">Voir tout →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/50 text-[11px] font-bold text-gray-400 uppercase tracking-[0.1em]">
                        <tr>
                            <th class="px-8 py-4">Référence</th>
                            <th class="px-8 py-4">Client</th>
                            <th class="px-8 py-4">Statut</th>
                            <th class="px-8 py-4 text-right">Montant</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($recentOrders as $order)
                            <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                                <td class="px-8 py-5 text-sm font-medium text-gray-400">#{{ $order->id }}</td>
                                <td class="px-8 py-5">
                                    <span class="font-bold text-gray-900 text-sm tracking-tight uppercase">{{ $order->customer_name }}</span>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="px-3 py-1 text-[10px] font-bold uppercase rounded-full {{ $order->status === 'delivered' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right font-bold text-gray-900">{{ number_format($order->total_amount, 0) }} FCFA</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
