@extends('layouts.store')

@section('title', $product->name . ' - Bio Farm Trading')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-start">
    <!-- Product Image -->
    <div class="bg-white rounded-[2rem] overflow-hidden shadow-2xl border border-gray-100 aspect-square">
        @if($product->image_path)
            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex items-center justify-center bg-green-50 text-green-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-48 w-48" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        @endif
    </div>

    <!-- Product Details -->
    <div class="flex flex-col">
        <nav class="mb-6 flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm font-medium">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="text-gray-400 hover:text-green-600">Produits</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="ml-1 text-gray-400 md:ml-2">{{ $product->category->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">{{ $product->name }}</h1>
        <p class="text-3xl font-black text-green-600 mb-8">{{ number_format($product->price, 0, ',', ' ') }} FCFA</p>
        
        <div class="prose prose-green mb-10 text-gray-600 text-lg leading-relaxed">
            <p>{{ $product->description }}</p>
        </div>

        <!-- Meta Info -->
        <div class="grid grid-cols-2 gap-4 mb-10">
            <div class="p-4 rounded-2xl glass border border-gray-50 flex items-center space-x-3">
                <div class="p-2 bg-green-100 text-green-600 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <span class="block text-xs text-gray-400 font-bold uppercase tracking-wider">Qualité</span>
                    <span class="text-sm font-semibold text-gray-700">100% Bio</span>
                </div>
            </div>
            <div class="p-4 rounded-2xl glass border border-gray-50 flex items-center space-x-3">
                <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z" />
                        <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <span class="block text-xs text-gray-400 font-bold uppercase tracking-wider">Origine</span>
                    <span class="text-sm font-semibold text-gray-700">Produit au Togo</span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4">
            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex-1">
                @csrf
                <button type="submit" class="w-full py-5 gradient-brand text-white font-bold rounded-2xl shadow-xl hover:shadow-2xl btn-premium flex items-center justify-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Ajouter au Panier</span>
                </button>
            </form>
            <!-- WhatsApp Modal Trigger -->
            <div x-data="{ open: false }" class="flex-1">
                <button @click="open = !open" type="button" class="w-full py-5 bg-white border-2 border-green-600 text-green-600 font-bold rounded-2xl hover:bg-green-50 btn-premium flex items-center justify-center space-x-2 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.038 3.284l-.569 2.103 2.201-.588c.959.566 1.931.946 3.097.947h.001c3.181 0 5.767-2.586 5.768-5.766 0-3.18-2.587-5.766-5.768-5.766zm3.434 8.272c-.117.33-.679.624-1.121.724-.316.071-.734.127-1.137-.035-.245-.098-.553-.228-2.731-1.139-.938-.403-1.547-1.353-1.637-1.472-.09-.12-1.077-1.357-1.077-2.588s.642-1.838.871-2.083c.23-.245.501-.306.668-.306s.334.004.481.012c.153.008.358-.058.56.43s.688 1.674.75 1.8c.063.126.104.272.021.439-.083.167-.125.272-.25.417-.125.146-.262.326-.375.438-.125.125-.255.261-.11.512.146.251.646 1.066 1.388 1.725.954.847 1.758 1.11 2.012 1.235.253.125.402.104.551-.063.149-.167.643-.751.815-1.002.172-.25.344-.209.58-.125s1.493.704 1.751.83c.258.125.43.188.492.292.063.104.063.604-.054.914zm-3.434-11.272c-5.14 0-9.324 4.183-9.327 9.326-.001 1.68.455 3.313 1.32 4.74l-.993 3.633 3.734-.979c1.52.907 3.013 1.385 4.624 1.386 5.14 0 9.324-4.183 9.327-9.325.002-2.489-.965-4.831-2.724-6.592-1.758-1.759-4.101-2.727-6.591-2.729z"/>
                    </svg>
                    <span>Commander via WhatsApp</span>
                </button>

                <!-- Modal -->
                <div x-show="open" @click.away="open = false" x-transition class="absolute z-50 mt-2 w-full sm:w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 p-4" style="display: none;">
                    <div class="text-center mb-4">
                        <h4 class="font-bold text-gray-900">Choisissez votre Boutique</h4>
                        <p class="text-xs text-gray-500">Contactez la boutique la plus proche</p>
                    </div>
                    
                    <div class="space-y-3">
                        <a href="https://wa.me/22890000001?text=Bonjour%20Cacaveli%2C%20je%20souhaite%20commander%20{{ $product->name }}" target="_blank" class="flex items-center justify-between p-3 bg-gray-50 hover:bg-green-50 rounded-xl transition-colors group">
                            <div class="flex items-center space-x-3">
                                <span class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center font-bold text-xs">C</span>
                                <div class="text-left">
                                    <p class="font-bold text-gray-800 text-sm group-hover:text-green-700">Cacaveli</p>
                                    <p class="text-xs text-gray-400">+228 90 00 00 01</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-300 group-hover:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </a>

                        <a href="https://wa.me/22890000002?text=Bonjour%20Hedzranawoe%2C%20je%20souhaite%20commander%20{{ $product->name }}" target="_blank" class="flex items-center justify-between p-3 bg-gray-50 hover:bg-green-50 rounded-xl transition-colors group">
                             <div class="flex items-center space-x-3">
                                <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">H</span>
                                <div class="text-left">
                                    <p class="font-bold text-gray-800 text-sm group-hover:text-green-700">Hedzranawoe</p>
                                    <p class="text-xs text-gray-400">+228 90 00 00 02</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-300 group-hover:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reviews Section -->
<div class="mt-32">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-6">
        <div>
            <h3 class="text-4xl font-black text-gray-900 mb-2">Avis Clients</h3>
            <p class="text-gray-500 font-medium">Découvrez ce que nos clients disent de ce produit.</p>
        </div>
        
        @auth
            <button onclick="document.getElementById('review-form').scrollIntoView({behavior: 'smooth'})" class="px-8 py-4 bg-gray-900 text-white rounded-2xl font-bold hover:bg-gray-800 transition-all shadow-xl shadow-gray-200 text-sm">
                Laisser un avis
            </button>
        @endauth
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-16">
        <!-- Reviews List -->
        <div class="lg:col-span-2 space-y-8">
            @forelse($product->reviews()->latest()->get() as $review)
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-50">
                    <div class="flex justify-between items-start mb-6">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-green-50 text-green-600 rounded-full flex items-center justify-center font-black">
                                {{ substr($review->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">{{ $review->user->name }}</p>
                                <p class="text-xs text-gray-400 uppercase font-black tracking-widest">{{ $review->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="flex text-yellow-400">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $review->rating ? 'fill-current' : 'text-gray-200' }}" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                    </div>
                    <p class="text-gray-600 leading-relaxed italic">"{{ $review->comment }}"</p>
                </div>
            @empty
                <div class="bg-gray-50 p-12 rounded-[2rem] text-center border-2 border-dashed border-gray-200">
                    <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">Aucun avis pour le moment</p>
                    <p class="text-sm text-gray-400 mt-2">Soyez le premier à partager votre expérience !</p>
                </div>
            @endforelse
        </div>

        <!-- Review Form Sidebar -->
        <div class="col-span-1">
            <div id="review-form" class="bg-white p-8 rounded-[2.5rem] shadow-2xl border border-gray-50 sticky top-32">
                <h4 class="text-2xl font-black text-gray-900 mb-6">Ajouter un Avis</h4>
                
                @auth
                    <form action="{{ route('products.review.store', $product) }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-4">Note</label>
                            <div class="flex space-x-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <label class="cursor-pointer group">
                                        <input type="radio" name="rating" value="{{ $i }}" class="hidden peer" required>
                                        <svg class="w-8 h-8 text-gray-200 peer-checked:text-yellow-400 group-hover:text-yellow-300 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </label>
                                @endfor
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Votre Commentaire</label>
                            <textarea name="comment" rows="4" required placeholder="Que pensez-vous du goût ?" class="w-full px-4 py-3 rounded-xl border-2 border-gray-50 focus:border-green-500 focus:ring-0 outline-none text-sm"></textarea>
                        </div>

                        <button type="submit" class="w-full py-4 bg-green-600 text-white font-black rounded-2xl shadow-xl hover:bg-green-700 transition-all btn-premium text-sm">
                            Publier mon Avis
                        </button>
                    </form>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500 text-sm mb-6">Connectez-vous pour laisser votre avis sur ce produit.</p>
                        <a href="{{ route('login') }}" class="inline-block px-8 py-4 bg-gray-900 text-white font-bold rounded-2xl hover:bg-gray-800 transition-all text-xs">
                            Se Connecter
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
