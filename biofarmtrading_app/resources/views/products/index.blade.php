@extends('layouts.store')

@section('title', 'Bio Farm Trading - Nos Produits 100% Naturels')

@section('content')
<!-- Hero Section -->
<div class="relative overflow-hidden rounded-3xl mb-16 bg-gradient-to-br from-green-600 to-green-400 p-8 md:p-16 text-white shadow-2xl">
    <div class="relative z-10 max-w-2xl">
        <h1 class="text-4xl md:text-6xl font-extrabold mb-6 leading-tight">Savourez la Nature à l'État Pur.</h1>
        <p class="text-xl text-green-50 mb-10 leading-relaxed">
            Fruits séchés et jus d'ananas 100% Bio naturels, transformés avec soin au Togo. Sans conservateurs, sans additifs, juste le goût authentique du fruit.
        </p>
        <div class="flex flex-wrap gap-4">
            <a href="#catalogue" class="px-8 py-4 bg-white text-green-600 font-bold rounded-2xl shadow-xl hover:bg-green-50 transition-all btn-premium">Explorer le Catalogue</a>
            
            <!-- WhatsApp Modal Trigger -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="px-8 py-4 bg-green-700 text-white font-bold rounded-2xl shadow-xl hover:bg-green-800 transition-all btn-premium flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.038 3.284l-.569 2.103 2.201-.588c.959.566 1.931.946 3.097.947h.001c3.181 0 5.767-2.586 5.768-5.766 0-3.18-2.587-5.766-5.768-5.766zm3.434 8.272c-.117.33-.679.624-1.121.724-.316.071-.734.127-1.137-.035-.245-.098-.553-.228-2.731-1.139-.938-.403-1.547-1.353-1.637-1.472-.09-.12-1.077-1.357-1.077-2.588s.642-1.838.871-2.083c.23-.245.501-.306.668-.306s.334.004.481.012c.153.008.358-.058.56.43s.688 1.674.75 1.8c.063.126.104.272.021.439-.083.167-.125.272-.25.417-.125.146-.262.326-.375.438-.125.125-.255.261-.11.512.146.251.646 1.066 1.388 1.725.954.847 1.758 1.11 2.012 1.235.253.125.402.104.551-.063.149-.167.643-.751.815-1.002.172-.25.344-.209.58-.125s1.493.704 1.751.83c.258.125.43.188.492.292.063.104.063.604-.054.914zm-3.434-11.272c-5.14 0-9.324 4.183-9.327 9.326-.001 1.68.455 3.313 1.32 4.74l-.993 3.633 3.734-.979c1.52.907 3.013 1.385 4.624 1.386 5.14 0 9.324-4.183 9.327-9.325.002-2.489-.965-4.831-2.724-6.592-1.758-1.759-4.101-2.727-6.591-2.729z"/>
                    </svg>
                    Commander via WhatsApp
                </button>

                <!-- WhatsApp Selection Modal (Fixed to avoid clipping) -->
                <template x-teleport="body">
                    <div x-show="open" 
                         class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/60 backdrop-blur-md"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         x-cloak>
                        
                        <div @click.away="open = false" 
                             class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-sm overflow-hidden transform transition-all border border-gray-100 text-gray-900"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0 text-gray-900">
                            
                            <!-- Header -->
                            <div class="bg-gray-50 p-6 border-b border-gray-100 flex justify-between items-center">
                                <div>
                                    <h4 class="font-black text-gray-900 text-lg tracking-tight">Nos Boutiques</h4>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Contact WhatsApp rapide</p>
                                </div>
                                <button @click="open = false" class="p-2 text-gray-400 hover:text-red-500 transition-colors bg-white rounded-full shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                            
                            <!-- Shop List -->
                            <div class="p-4 space-y-3">
                                <a href="https://wa.me/22890000001?text=Bonjour%20Cacaveli%2C%20je%20souhaite%20commander%20des%20produits" target="_blank" class="flex items-center justify-between p-4 bg-green-50/50 hover:bg-green-100/50 rounded-2xl transition-all group border border-green-100/20">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 rounded-xl bg-green-600 text-white flex items-center justify-center font-black text-base shadow-lg shadow-green-200">C</div>
                                        <div class="text-left">
                                            <p class="font-black text-gray-900 group-hover:text-green-700 text-sm">Cacaveli</p>
                                            <p class="text-[11px] text-gray-500 font-bold">+228 90 00 00 01</p>
                                        </div>
                                    </div>
                                    <div class="w-7 h-7 rounded-full bg-white flex items-center justify-center text-green-600 shadow-sm transform group-hover:translate-x-1 transition-transform">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                    </div>
                                </a>

                                <a href="https://wa.me/22890000002?text=Bonjour%20Hedzranawoe%2C%20je%20souhaite%20commander%20des%20produits" target="_blank" class="flex items-center justify-between p-4 bg-blue-50/50 hover:bg-blue-100/50 rounded-2xl transition-all group border border-blue-100/20">
                                     <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-black text-base shadow-lg shadow-blue-200">H</div>
                                        <div class="text-left">
                                            <p class="font-black text-gray-900 group-hover:text-blue-700 text-sm">Hedzranawoe</p>
                                            <p class="text-[11px] text-gray-500 font-bold">+228 90 00 00 02</p>
                                        </div>
                                    </div>
                                    <div class="w-7 h-7 rounded-full bg-white flex items-center justify-center text-blue-600 shadow-sm transform group-hover:translate-x-1 transition-transform">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
    
    <!-- Abstract Shapes for Premium Feel -->
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-green-300 opacity-20 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-yellow-300 opacity-20 rounded-full blur-3xl"></div>
</div>

<!-- Search and Filters -->
<div id="catalogue" class="mb-12 space-y-8">
    <!-- Search Bar -->
    <div class="max-w-xl mx-auto">
        <form action="{{ route('home') }}#catalogue" method="GET" class="relative group">
            <input type="text" name="search" value="{{ request('search') }}" 
                placeholder="Rechercher un produit (ex: Ananas)..." 
                class="w-full px-8 py-5 rounded-[2rem] border-2 border-gray-100 focus:border-green-500 focus:ring-0 transition-all text-lg shadow-sm group-hover:shadow-md outline-none pr-16">
            <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 p-3 bg-green-600 text-white rounded-2xl hover:bg-green-700 transition-colors shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
        </form>
    </div>

    <!-- Category Tabs -->
    <div class="overflow-x-auto pb-4">
        <div class="flex space-x-4 min-w-max justify-center">
            <a href="{{ route('home', ['search' => request('search')]) }}#catalogue" 
                class="px-8 py-3 rounded-2xl font-bold transition-all border-2 {{ !request('category') || request('category') == 'all' ? 'bg-green-600 text-white border-green-600 shadow-lg shadow-green-100' : 'bg-white text-gray-400 border-gray-100 hover:border-green-200 hover:text-green-600' }}">
                Tous les produits
            </a>
            @foreach($categories as $category)
                <a href="{{ route('home', ['category' => $category->slug, 'search' => request('search')]) }}#catalogue" 
                    class="px-8 py-3 rounded-2xl font-bold transition-all border-2 {{ request('category') == $category->slug ? 'bg-green-600 text-white border-green-600 shadow-lg shadow-green-100' : 'bg-white text-gray-400 border-gray-100 hover:border-green-200 hover:text-green-600' }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </div>
</div>

<!-- Product Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
    @foreach($products as $product)
        <div class="group relative bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 border border-gray-100">
            <!-- Product Image -->
            <div class="aspect-square bg-gray-50 overflow-hidden relative">
                @if($product->image_path)
                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-green-50 text-green-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                @endif
                
                <!-- Category Badge -->
                <div class="absolute top-4 left-4">
                    <span class="px-4 py-1.5 bg-white/90 backdrop-blur-md rounded-full text-xs font-bold text-green-700 shadow-sm">
                        {{ $product->category->name }}
                    </span>
                </div>
            </div>

            <!-- Product Info -->
            <div class="p-8">
                <a href="{{ route('products.show', $product->slug) }}" class="block text-2xl font-bold text-gray-900 mb-2 hover:text-green-600 transition-colors">
                    {{ $product->name }}
                </a>
                <p class="text-gray-500 text-sm line-clamp-2 mb-6">
                    {{ $product->description }}
                </p>
                
                <div class="flex items-center justify-between mt-auto">
                    <span class="text-2xl font-black text-gray-900">{{ number_format($product->price, 0, ',', ' ') }} <span class="text-sm font-medium text-gray-400">FCFA</span></span>
                    
                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="p-4 gradient-brand text-white rounded-2xl shadow-lg hover:rotate-6 transition-all btn-premium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Pagination -->
<div class="mt-16">
    {{ $products->links() }}
</div>

<!-- Mission & Why Bio Farm -->
<div class="mt-32 space-y-24">
    <!-- Trust Badges / Features -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-12 bg-gray-50 p-12 rounded-[3rem]">
        <div class="text-center space-y-4">
            <div class="w-20 h-20 bg-white rounded-3xl shadow-xl flex items-center justify-center mx-auto text-green-600 mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
            </div>
            <h4 class="text-2xl font-black text-gray-900">Certifié Bio</h4>
            <p class="text-gray-500 font-medium">Nos produits sont certifiés **ECOCERT** et respectent les normes de sécurité alimentaire **HACCP**.</p>
        </div>
        <div class="text-center space-y-4">
            <div class="w-20 h-20 bg-white rounded-3xl shadow-xl flex items-center justify-center mx-auto text-yellow-500 mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
            </div>
            <h4 class="text-2xl font-black text-gray-900">Expertise Locale</h4>
            <p class="text-gray-500 font-medium">Capacité de transformation de **1800 tonnes** de fruits frais par an pour un goût préservé.</p>
        </div>
        <div class="text-center space-y-4">
            <div class="w-20 h-20 bg-white rounded-3xl shadow-xl flex items-center justify-center mx-auto text-blue-500 mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
            </div>
            <h4 class="text-2xl font-black text-gray-900">Impact Social</h4>
            <p class="text-gray-500 font-medium">Soutien aux communautés rurales et promotion de l'agriculture biologique au Togo.</p>
        </div>
    </div>

    <!-- About Content -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
        <div class="relative">
            <div class="aspect-square rounded-[3rem] bg-white overflow-hidden shadow-2xl skew-y-3 border border-gray-50 flex items-center justify-center p-12">
                 <img src="{{ asset('images/logo.jpg') }}" alt="Bio Farm Trading Logo" class="w-full h-auto transform -rotate-3">
            </div>
            <div class="absolute -bottom-10 -right-10 bg-white p-8 rounded-3xl shadow-xl border border-gray-100 hidden md:block">
                <p class="text-5xl font-black text-green-600 mb-2">100% Bio</p>
                <h1 class="text-5xl font-black text-green-600 mb-2"></h1>
            </div>
        </div>
        <div class="space-y-8">
            <h2 class="text-5xl font-black text-gray-900 leading-tight">Notre Engagement pour la Terre et l'Homme</h2>
            <div class="prose prose-lg text-gray-600 font-medium leading-relaxed">
                <p>
                    Depuis Lomé, **Bio Farm Trading** s'est donné pour mission de valoriser la richesse des fruits de notre terroir tout en respectant l'environnement. 
                </p>
                <p>
                    Spécialisés dans le séchage et la transformation de fruits tropicaux (Ananas Cayenne Lisse, Mangue, Papaye), nous garantissons des produits sans additifs, préservant toutes leurs propriétés nutritionnelles.
                </p>
                <p>
                    Notre démarche est certifiée par les plus hautes instances internationales, car nous croyons que manger sain est un droit, et cultiver bio est un devoir.
                </p>
            </div>
            <a href="{{ route('contact.index') }}" class="inline-block px-10 py-5 bg-gray-900 text-white font-black rounded-2xl shadow-xl hover:shadow-2xl btn-premium transition-all">
                En savoir plus sur nos certifications
            </a>
        </div>
    </div>
</div>

<!-- Shops Section -->
<div id="boutiques" class="mt-32 pt-16 border-t border-gray-100">
    <div class="text-center mb-16">
        <h2 class="text-4xl font-black text-gray-900 mb-4">Nos Boutiques Physiques</h2>
        <p class="text-gray-500 max-w-2xl mx-auto italic">Venez nous rendre visite et découvrez nos produits directement sur place dans nos points de vente à Lomé.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-gray-50 group hover:border-green-200 transition-colors">
            <div class="w-14 h-14 gradient-brand text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg rotate-3 group-hover:rotate-0 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Boutique Cacaveli</h3>
            <p class="text-gray-500 mb-6">Située à Cacaveli, Lomé, notre boutique principale vous accueille tous les jours.</p>
            <div class="flex flex-col space-y-2 text-sm text-gray-600 font-medium">
                <span class="flex items-center gap-2">📍 Cacaveli, Lomé, Togo</span>
                <span class="flex items-center gap-2">📞 +228 90 00 00 01</span>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-gray-50 group hover:border-green-200 transition-colors">
            <div class="w-14 h-14 bg-yellow-400 text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg -rotate-3 group-hover:rotate-0 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Boutique Hedzranawoe</h3>
            <p class="text-gray-500 mb-6">Retrouvez-nous au cœur de Hedzranawoe pour vos jus et fruits séchés préférés.</p>
            <div class="flex flex-col space-y-2 text-sm text-gray-600 font-medium">
                <span class="flex items-center gap-2">📍 Hedzranawoe, Lomé, Togo</span>
                <span class="flex items-center gap-2">📞 +228 90 00 00 02</span>
            </div>
        </div>
    </div>
</div>
@endsection
