<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestion des Produits') }}
            </h2>
            <a href="{{ route('admin.products.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-xl font-bold hover:bg-green-700 transition-colors shadow-lg shadow-green-200">
                + Ajouter un Produit
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                
                {{-- Mobile view: Card list --}}
                <div class="md:hidden divide-y divide-gray-100">
                    @foreach($products as $product)
                        <div class="p-4 space-y-4">
                            <div class="flex items-center space-x-4">
                                @if($product->image_path)
                                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-16 h-16 rounded-xl object-cover shadow-sm">
                                @else
                                    <div class="w-16 h-16 bg-green-50 rounded-xl flex items-center justify-center text-green-200">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start">
                                        <p class="font-bold text-gray-900 truncate uppercase text-sm">{{ $product->name }}</p>
                                        <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded-full text-[10px] font-bold uppercase whitespace-nowrap">
                                            {{ $product->category->name }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-gray-400 mt-1 line-clamp-1">{{ $product->description }}</p>
                                    <p class="text-sm font-black text-green-600 mt-1">{{ number_format($product->price, 0, ',', ' ') }} FCFA</p>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-xl p-3 flex justify-between items-center text-xs">
                                <div class="space-y-1">
                                    @if(auth()->user()->role === 'manager' && auth()->user()->shop_id)
                                        @php
                                            $shopStock = $product->shops()->where('shop_id', auth()->user()->shop_id)->first()->pivot->quantity ?? 0;
                                        @endphp
                                        <p class="text-gray-500">Votre Boutique: <span class="font-bold {{ $shopStock < 10 ? 'text-red-500' : 'text-gray-900' }}">{{ $shopStock }}</span></p>
                                    @else
                                        @foreach($product->shops as $shop)
                                            <p class="text-gray-500">{{ $shop->name }}: <span class="font-bold text-gray-900">{{ $shop->pivot->quantity }}</span></p>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="p-2 bg-white text-blue-600 border border-gray-100 rounded-lg shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 012 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </a>
                                    @if(auth()->user()->role === 'admin')
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr ?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 bg-white text-red-600 border border-gray-100 rounded-lg shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Desktop view: Table --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50 text-gray-400 text-xs font-bold uppercase tracking-widest">
                                <th class="px-8 py-4">Produit</th>
                                <th class="px-8 py-4">Catégorie</th>
                                <th class="px-8 py-4">Prix</th>
                                <th class="px-8 py-4">Stock</th>
                                <th class="px-8 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($products as $product)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center space-x-4">
                                            @if($product->image_path)
                                                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-xl object-cover">
                                            @else
                                                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-200">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                </div>
                                            @endif
                                            <div>
                                                <p class="font-bold text-gray-900 uppercase text-sm mb-1">{{ $product->name }}</p>
                                                <p class="text-[11px] text-gray-400">{{ Str::limit($product->description, 40) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[10px] font-bold uppercase tracking-widest">
                                            {{ $product->category->name }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 font-black text-gray-900">
                                        {{ number_format($product->price, 0, ',', ' ') }} FCFA
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-[11px] space-y-1">
                                            @if(auth()->user()->role === 'manager' && auth()->user()->shop_id)
                                                @php
                                                    $shopStock = $product->shops()->where('shop_id', auth()->user()->shop_id)->first()->pivot->quantity ?? 0;
                                                @endphp
                                                <p class="text-gray-500">Votre Boutique: <span class="font-bold {{ $shopStock < 10 ? 'text-red-500' : 'text-gray-900' }}">{{ $shopStock }}</span></p>
                                            @else
                                                @foreach($product->shops as $shop)
                                                    <p class="text-gray-500">{{ $shop->name }}: <span class="font-bold text-gray-900">{{ $shop->pivot->quantity }}</span></p>
                                                @endforeach
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-right space-x-1">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="inline-flex p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 012 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </a>
                                        @if(auth()->user()->role === 'admin')
                                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr ?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 italic">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
