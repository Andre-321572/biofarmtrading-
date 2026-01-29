<x-app-layout>
    <div class="py-12 bg-white min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('admin.stock.supply_bulk') }}" method="POST" class="border-2 border-gray-800 p-8 shadow-2xl relative bg-white">
                @csrf

                <!-- Watermark/Background (optional) -->
                <!-- <div class="absolute inset-0 flex items-center justify-center opacity-5 pointer-events-none">
                    <img src="{{ asset('images/logo.jpg') }}" class="w-2/3 grayscale" />
                </div> -->

                <!-- Header -->
                <div class="flex border-b-2 border-gray-800">
                    <div class="w-48 border-r-2 border-gray-800 p-4 flex items-center justify-center bg-white">
                        <img src="{{ asset('images/logo.jpg') }}" alt="Bio Farm Trading" class="w-full h-auto object-contain">
                    </div>
                    <div class="flex-1 p-4 flex flex-col justify-center items-center text-center bg-green-50/10">
                        <h1 class="text-4xl font-serif font-black text-green-600 uppercase tracking-widest scale-y-110 mb-2" style="font-family: 'Times New Roman', serif; text-shadow: 1px 1px 0px rgba(0,0,0,0.1);">BIO FARM TRADING</h1>
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-800">Production - Transformation - Commercialisation des produits agricoles biologiques</p>
                    </div>
                </div>

                <!-- Date & Info -->
                <div class="flex justify-between items-end mb-8 font-serif mt-6">
                    <div class="w-full">
                        <div class="flex justify-end mb-4">
                            <span class="font-bold mr-2 text-lg">Dates :</span>
                            <span class="border-b-2 border-dotted border-gray-400 min-w-[150px] text-center font-mono">{{ date('d / m / Y') }}</span>
                        </div>
                        
                        <div class="mb-4">
                             <label class="font-bold text-lg underline decoration-2 underline-offset-4">Bon de sortie N°</label> :
                             <input type="text" name="bon_numero" class="border-b-2 border-dotted border-gray-400 focus:outline-none focus:border-green-600 px-2 py-0 w-40 font-mono text-lg" placeholder="...">
                        </div>

                        <div class="flex items-center gap-4 relative">
                             <label class="font-bold text-lg underline decoration-2 underline-offset-4">Client/destination</label> :
                             <input type="text" name="destination_name" list="shops-list" class="flex-1 border-b-2 border-dotted border-gray-400 focus:outline-none focus:border-green-600 bg-transparent py-1 font-bold text-xl uppercase" placeholder="Choisir ou saisir..." autocomplete="off">
                             <datalist id="shops-list">
                                 @foreach($shops as $shop)
                                     <option value="{{ $shop->name }}"></option>
                                 @endforeach
                             </datalist>
                             <!-- Hidden shop_id if it matches a known shop -->
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <table class="w-full border-2 border-gray-800 mb-8 font-serif">
                    <thead>
                        <tr class="bg-gray-100 divide-x-2 divide-gray-800 border-b-2 border-gray-800">
                            <th class="w-12 py-2 text-center font-bold">Réf.</th>
                            <th class="py-2 px-4 text-left font-bold border-r-2 border-gray-800 text-lg">Description et désignation du produit</th>
                            <th class="w-40 py-2 text-center font-bold border-r-2 border-gray-800 text-sm leading-tight">Nombre de<br>conditionnement</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @foreach($products as $index => $product)
                            <tr class="divide-x-2 divide-gray-800 hover:bg-green-50 transition-colors">
                                <td class="text-center py-2 text-gray-500 font-mono text-xs">{{ $index + 1 }}</td>
                                <td class="px-4 py-2 font-bold text-gray-800 border-r-2 border-gray-800 text-lg">{{ $product->name }}</td>
                                <td class="p-0 border-r-2 border-gray-800">
                                    <input type="number" 
                                           name="products[{{ $product->id }}]" 
                                           min="0" 
                                           class="w-full h-full border-none focus:ring-2 focus:ring-green-500/50 text-center font-mono font-bold text-xl bg-transparent p-2"
                                           placeholder="">
                                </td>
                            </tr>
                        @endforeach
                        <!-- Empty rows for paper feel -->
                        @for($i = 0; $i < 3; $i++)
                            <tr class="divide-x-2 divide-gray-800 h-10">
                                <td></td>
                                <td class="border-r-2 border-gray-800"></td>
                                <td class="border-r-2 border-gray-800"></td>
                            </tr>
                        @endfor
                    </tbody>
                </table>

                <!-- Footer -->
                <div class="grid grid-cols-2 gap-8 mt-12 font-serif text-sm">
                    <div></div>
                    <div class="text-center relative pt-8">
                        <p class="font-bold mb-24 text-lg underline decoration-1 underline-offset-4">Responsable gestion des stocks</p>
                        <div class="border-b border-dashed border-gray-400 w-1/2 mx-auto"></div>
                    </div>
                </div>

                <!-- Footer Info -->
                <div class="mt-12 pt-4 border-t border-gray-800 text-center text-[10px] text-gray-600 font-serif">
                    <p class="font-bold">BIO FARM TRADING</p>
                    <p>Produits bios Certifiés Par Ecocert S.A.S</p>
                </div>

                <!-- Action Button (Floating) -->
                <div class="fixed bottom-8 right-8">
                    <input type="hidden" name="shop_id" id="shop_id_hidden">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-8 rounded-full shadow-2xl flex items-center gap-3 transform hover:scale-105 transition-all text-lg z-50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        VALIDER CE BON DE SORTIE
                    </button>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const shops = @json($shops);
                        const input = document.querySelector('input[name="destination_name"]');
                        const hidden = document.getElementById('shop_id_hidden');
                        
                        input.addEventListener('input', function(e) {
                            const val = e.target.value;
                            const match = shops.find(s => s.name === val);
                            if (match) {
                                hidden.value = match.id;
                                console.log("Shop selected:", match.name);
                            } else {
                                hidden.value = '';
                                console.log("Custom destination");
                            }
                        });
                    });
                </script>

            </form>
        </div>
    </div>
</x-app-layout>
