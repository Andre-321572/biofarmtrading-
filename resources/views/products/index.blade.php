@extends('layouts.store')

@section('title', 'Bio Farm Trading - Fruits Séchés & Jus Naturels 100% Bio du Togo')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-16">

    <!-- ========================================================================= -->
    <!-- 1. HERO SECTION INSTITUTIONNELLE & COMMERCIALE (SOBRE, ÉLÉGANTE, ECOCERT) -->
    <!-- ========================================================================= -->
    <section class="rounded-3xl shadow-xl overflow-hidden border border-emerald-900/20 text-white" 
             style="background: linear-gradient(135deg, #064e3b 0%, #065f46 50%, #042f2e 100%);">
        <div class="p-8 sm:p-12 lg:p-16">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                
                <!-- Colonne Gauche : Titre & Propositions de valeur -->
                <div class="lg:col-span-7 space-y-6 text-left">
                    

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight text-white">
                        Fruits Séchés d'Exception & Purs Jus Naturels 100% Bio
                    </h1>

                    <p class="text-base sm:text-lg text-emerald-100/90 font-normal leading-relaxed max-w-2xl">
                        Leader togolais de la transformation fruitière agro-écologique. Retrouvez nos marques officielles <strong class="text-white font-bold">KINU</strong> (fruits séchés au soleil) et <strong class="text-white font-bold">JOOSSI</strong> (purs jus pressés sans eau ajoutée ni conservateur).
                    </p>

                    <!-- Boutons d'Action Principaux -->
                    <div class="flex flex-wrap items-center gap-4 pt-2">
                        <a href="#catalogue" 
                           class="px-8 py-4 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-sm rounded-xl shadow-lg transition-all flex items-center gap-2">
                            <span>Découvrir la Boutique</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>

                        <!-- Bouton WhatsApp Modal -->
                        <div x-data="{ open: false }" class="relative">
                            <button type="button" @click="open = true" 
                                    class="px-6 py-4 bg-emerald-900/80 hover:bg-emerald-800 text-white font-bold text-sm rounded-xl border border-emerald-600/40 transition-all flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-300" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.038 3.284l-.569 2.103 2.201-.588c.959.566 1.931.946 3.097.947h.001c3.181 0 5.767-2.586 5.768-5.766 0-3.18-2.587-5.766-5.768-5.766zm3.434 8.272c-.117.33-.679.624-1.121.724-.316.071-.734.127-1.137-.035-.245-.098-.553-.228-2.731-1.139-.938-.403-1.547-1.353-1.637-1.472-.09-.12-1.077-1.357-1.077-2.588s.642-1.838.871-2.083c.23-.245.501-.306.668-.306s.334.004.481.012c.153.008.358-.058.56.43s.688 1.674.75 1.8c.063.126.104.272.021.439-.083.167-.125.272-.25.417-.125.146-.262.326-.375.438-.125.125-.255.261-.11.512.146.251.646 1.066 1.388 1.725.954.847 1.758 1.11 2.012 1.235.253.125.402.104.551-.063.149-.167.643-.751.815-1.002.172-.25.344-.209.58-.125s1.493.704 1.751.83c.258.125.43.188.492.292.063.104.063.604-.054.914zm-3.434-11.272c-5.14 0-9.324 4.183-9.327 9.326-.001 1.68.455 3.313 1.32 4.74l-.993 3.633 3.734-.979c1.52.907 3.013 1.385 4.624 1.386 5.14 0 9.324-4.183 9.327-9.325.002-2.489-.965-4.831-2.724-6.592-1.758-1.759-4.101-2.727-6.591-2.729z"/>
                                </svg>
                                <span>Commander par WhatsApp</span>
                            </button>

                            <!-- Modal Choix Boutique Plein Écran Centré (Non Clipé) -->
                            <div x-show="open" 
                                 style="display: none;"
                                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/75 backdrop-blur-xs"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95">
                                 
                                <div @click.away="open = false" 
                                     class="w-full max-w-md bg-white rounded-3xl shadow-2xl p-6 border border-slate-200 text-slate-800 text-left space-y-5 relative">
                                    
                                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                                        <div>
                                            <h4 class="text-base font-black uppercase text-slate-900 tracking-wider">Commander par WhatsApp</h4>
                                            <p class="text-xs text-slate-500 font-medium">Sélectionnez la boutique la plus proche à Lomé</p>
                                        </div>
                                        <button @click="open = false" type="button" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>

                                    <div class="space-y-3">
                                        <a href="https://wa.me/22890000001?text=Bonjour%20Boutique%20Cacaveli%2C%20je%20souhaite%20commander%20des%20produits%20Bio%20Farm" target="_blank" 
                                           class="flex items-center gap-4 p-4 rounded-2xl border-2 border-emerald-100 hover:border-emerald-500 hover:bg-emerald-50/50 text-slate-800 transition-all group">
                                            <div class="w-12 h-12 rounded-2xl bg-emerald-700 text-white font-black text-base flex items-center justify-center flex-shrink-0 shadow-md shadow-emerald-700/20">C</div>
                                            <div class="flex-1">
                                                <strong class="text-sm font-black block text-slate-900 group-hover:text-emerald-800">Boutique Cacaveli</strong>
                                                <span class="text-xs text-slate-500 font-medium block">Carrefour Cacaveli, Lomé</span>
                                                <span class="text-xs text-emerald-700 font-bold block mt-0.5">+228 90 00 00 01</span>
                                            </div>
                                            <svg class="w-5 h-5 text-emerald-600 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                        </a>

                                        <a href="https://wa.me/22890000002?text=Bonjour%20Boutique%20Hedzranawoe%2C%20je%20souhaite%20commander%20des%20produits%20Bio%20Farm" target="_blank" 
                                           class="flex items-center gap-4 p-4 rounded-2xl border-2 border-teal-100 hover:border-teal-500 hover:bg-teal-50/50 text-slate-800 transition-all group">
                                            <div class="w-12 h-12 rounded-2xl bg-teal-700 text-white font-black text-base flex items-center justify-center flex-shrink-0 shadow-md shadow-teal-700/20">H</div>
                                            <div class="flex-1">
                                                <strong class="text-sm font-black block text-slate-900 group-hover:text-teal-800">Boutique Hedzranawoe</strong>
                                                <span class="text-xs text-slate-500 font-medium block">Marché Hedzranawoe, Lomé</span>
                                                <span class="text-xs text-teal-700 font-bold block mt-0.5">+228 90 00 00 02</span>
                                            </div>
                                            <svg class="w-5 h-5 text-teal-600 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                        </a>
                                    </div>

                                    <div class="pt-1 text-center">
                                        <span class="text-[11px] text-slate-400 font-medium">Réponse rapide et préparation immédiate de votre commande.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chiffres Clés Institutionnels -->
                    <div class="grid grid-cols-3 gap-6 pt-6 border-t border-emerald-800/80 text-left">
                        <div>
                            <div class="text-2xl font-black text-white">1 800 T</div>
                            <div class="text-xs text-emerald-200/80 font-medium uppercase tracking-wider">Capacité annuelle</div>
                        </div>
                        <div>
                            <div class="text-2xl font-black text-white">100% Bio</div>
                            <div class="text-xs text-emerald-200/80 font-medium uppercase tracking-wider">ECOCERT & HACCP</div>
                        </div>
                        <div>
                            <div class="text-2xl font-black text-white">2 Boutiques</div>
                            <div class="text-xs text-emerald-200/80 font-medium uppercase tracking-wider">À Lomé (Togo)</div>
                        </div>
                    </div>

                </div>

                <!-- Colonne Droite : Vitrine Sobre Logo & Photographies Réelles -->
                <div class="lg:col-span-5 flex flex-col items-center justify-center">
                    <div class="w-full max-w-md bg-white rounded-2xl p-6 shadow-2xl border border-emerald-900/30 text-slate-800 space-y-4">
                        
                        <!-- Logo Officiel -->
                        <div class="flex items-center justify-center py-4 border-b border-slate-100">
                            <img src="{{ asset('images/joossi_kinu_final.png') }}" alt="Bio Farm Trading - Joossi & Kinu" class="h-28 w-auto object-contain">
                        </div>

                        <!-- 3 Photos Réelles du Terroir (Ananas, Mangue, Papaye) -->
                        <div class="grid grid-cols-3 gap-3 pt-2">
                            <div class="bg-slate-50 rounded-xl p-2 border border-slate-100 flex flex-col items-center text-center">
                                <div class="w-16 h-16 flex items-center justify-center">
                                    <img src="{{ asset('images/fruits/ananas.png') }}" alt="Ananas Bio du Togo" class="w-full h-full object-contain">
                                </div>
                                <span class="text-[11px] font-bold text-slate-700 mt-1">Ananas Bio</span>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-2 border border-slate-100 flex flex-col items-center text-center">
                                <div class="w-16 h-16 flex items-center justify-center">
                                    <img src="{{ asset('images/fruits/mangue.png') }}" alt="Mangue Bio du Togo" class="w-full h-full object-contain">
                                </div>
                                <span class="text-[11px] font-bold text-slate-700 mt-1">Mangue Bio</span>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-2 border border-slate-100 flex flex-col items-center text-center">
                                <div class="w-16 h-16 flex items-center justify-center">
                                    <img src="{{ asset('images/fruits/papaye.png') }}" alt="Papaye Bio du Togo" class="w-full h-full object-contain">
                                </div>
                                <span class="text-[11px] font-bold text-slate-700 mt-1">Papaye Bio</span>
                            </div>
                        </div>

                        <div class="text-center pt-2">
                            <span class="inline-block text-[11px] font-bold text-emerald-800 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-100">
                                Transformation locale à Lomé &bull; Qualité Export
                            </span>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 2. PRÉSENTATION DES DEUX MARQUES : KINU & JOOSSI (ZÉRO ÉMOJI, PUR SVG)   -->
    <!-- ========================================================================= -->
    <section class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <!-- Gamme KINU : Fruits Séchés -->
        <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-sm hover:border-amber-400 transition-all space-y-4">
            <div class="flex items-center justify-between">
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-50 text-amber-800 border border-amber-200">
                    Gamme KINU &bull; Fruits Séchés
                </span>
                <span class="text-xs font-semibold text-slate-400">100% Naturel</span>
            </div>
            
            <h3 class="text-2xl font-black text-slate-900 tracking-tight">
                Fruits Tropicaux Séchés au Soleil
            </h3>
            
            <p class="text-sm text-slate-600 leading-relaxed font-normal">
                Mangue Kent, ananas Cayenne Lisse, papaye Solo et banane séchés lentement selon un procédé respectueux qui préserve les vitamines, les fibres et les arômes intenses des fruits mûris à point.
            </p>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs text-slate-500 font-medium">Sachets hermétiques fraîcheur</span>
                <a href="{{ route('home', ['category' => 'fruits-seches']) }}#catalogue" 
                   class="text-xs font-black uppercase tracking-wider text-amber-800 hover:text-amber-900 flex items-center gap-1">
                    <span>Voir les fruits séchés</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        <!-- Gamme JOOSSI : Purs Jus -->
        <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-sm hover:border-emerald-400 transition-all space-y-4">
            <div class="flex items-center justify-between">
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-50 text-emerald-800 border border-emerald-200">
                    Gamme JOOSSI &bull; Purs Jus de Fruits
                </span>
                <span class="text-xs font-semibold text-slate-400">Pressés Frais</span>
            </div>
            
            <h3 class="text-2xl font-black text-slate-900 tracking-tight">
                Purs Jus d'Ananas & Mariages Naturels
            </h3>
            
            <p class="text-sm text-slate-600 leading-relaxed font-normal">
                Pur jus d'ananas biologique pressé sans eau ajoutée, sans sucre raffiné et sans conservateur. Décliné en recettes savoureuses : Ananas Nature, Ananas-Gingembre tonifiant, et Ananas-Bissap.
            </p>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs text-slate-500 font-medium">Bouteilles pasteurisées</span>
                <a href="{{ route('home', ['category' => 'jus-de-fruits']) }}#catalogue" 
                   class="text-xs font-black uppercase tracking-wider text-emerald-800 hover:text-emerald-900 flex items-center gap-1">
                    <span>Voir les purs jus</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

    </section>

    <!-- ========================================================================= -->
    <!-- 3. CATALOGUE COMMERCIAL ÉPURÉ & PROFESSIONNEL                            -->
    <!-- ========================================================================= -->
    <section id="catalogue" class="scroll-mt-24 space-y-6">
        
        <div class="flex flex-col md:flex-row items-start md:items-end justify-between gap-4 border-b border-slate-200 pb-5">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-700">Catalogue en Ligne</span>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mt-0.5">Nos Produits Disponibles</h2>
                <p class="text-xs sm:text-sm text-slate-500">Commandez en ligne avec livraison à Lomé ou retrait dans nos boutiques.</p>
            </div>

            <!-- Barre de Recherche Sobre -->
            <form action="{{ route('home') }}#catalogue" method="GET" class="w-full md:w-80">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Rechercher un produit..." 
                           class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 transition-all">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                </div>
            </form>
        </div>

        <!-- Onglets Catégories Épurés -->
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('home', ['search' => request('search')]) }}#catalogue" 
               class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all {{ !request('category') || request('category') == 'all' ? 'bg-emerald-700 text-white shadow-sm' : 'bg-white hover:bg-slate-100 text-slate-700 border border-slate-200' }}">
                Tous les Produits
            </a>
            @foreach($categories as $category)
                <a href="{{ route('home', ['category' => $category->slug, 'search' => request('search')]) }}#catalogue" 
                   class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all {{ request('category') == $category->slug ? 'bg-emerald-700 text-white shadow-sm' : 'bg-white hover:bg-slate-100 text-slate-700 border border-slate-200' }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

        <!-- Grille des Produits -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($products as $product)
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-200 border border-slate-200 flex flex-col justify-between">
                    
                    <!-- Image du Produit -->
                    <div class="aspect-square bg-white relative overflow-hidden flex items-center justify-center p-4 border-b border-slate-100">
                        <a href="{{ route('products.show', $product->slug) }}" class="w-full h-full flex items-center justify-center">
                            <img src="{{ $product->display_image }}" alt="{{ $product->name }}" class="w-full h-full object-contain hover:scale-105 transition-transform duration-300">
                        </a>

                        <!-- Badge Catégorie -->
                        <div class="absolute top-3 left-3">
                            <span class="px-2.5 py-1 bg-white rounded-md text-[10px] font-bold uppercase tracking-wider text-slate-700 border border-slate-200 shadow-xs">
                                {{ $product->category->name ?? 'Bio Farm' }}
                            </span>
                        </div>

                        <!-- Badge Certifié Bio -->
                        <div class="absolute top-3 right-3">
                            <span class="px-2 py-0.5 bg-emerald-600 text-white rounded text-[10px] font-bold uppercase tracking-wider">
                                100% Bio
                            </span>
                        </div>
                    </div>

                    <!-- Informations Produit -->
                    <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <a href="{{ route('products.show', $product->slug) }}" class="block text-base font-bold text-slate-900 hover:text-emerald-700 transition-colors">
                                {{ $product->name }}
                            </a>
                            <p class="text-xs text-slate-500 font-normal line-clamp-2 mt-1 leading-relaxed">
                                {{ $product->description }}
                            </p>
                        </div>

                        <!-- Prix et Bouton Panier -->
                        <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                            <div>
                                <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block">Prix unitaire</span>
                                <div class="text-xl font-black text-slate-900">
                                    {{ number_format($product->price, 0, ',', ' ') }} <span class="text-xs font-bold text-emerald-700">FCFA</span>
                                </div>
                            </div>

                            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-sm transition-all flex items-center gap-1.5 active:scale-95">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    <span>Ajouter</span>
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            @empty
                <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-slate-200 space-y-3">
                    <p class="text-base font-bold text-slate-700">Aucun produit ne correspond à votre sélection</p>
                    <a href="{{ route('home') }}#catalogue" class="inline-block px-5 py-2.5 bg-emerald-700 text-white text-xs font-bold rounded-xl">
                        Réinitialiser la sélection
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="pt-4 flex justify-center">
            {{ $products->links() }}
        </div>

    </section>

    <!-- ========================================================================= -->
    <!-- 4. LES 4 ENGAGEMENTS QUALITÉ (ICÔNES VECTORIELLES PROFESSIONNELLES)        -->
    <!-- ========================================================================= -->
    <section class="rounded-2xl bg-white p-8 sm:p-12 border border-slate-200 shadow-xs space-y-8">
        
        <div class="text-center max-w-2xl mx-auto space-y-1">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-700">Certifications & Charte Qualité</span>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Pourquoi Choisir Bio Farm Trading ?</h2>
            <p class="text-xs sm:text-sm text-slate-500">Une traçabilité complète de nos champs partenaires jusqu'à votre table.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Engagement 1 -->
            <div class="p-6 rounded-xl bg-slate-50 border border-slate-100 space-y-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h4 class="text-sm font-bold text-slate-900">Certifié ECOCERT</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Culture biologique garantie sans pesticides, sans engrais chimiques et sans OGM.
                </p>
            </div>

            <!-- Engagement 2 -->
            <div class="p-6 rounded-xl bg-slate-50 border border-slate-100 space-y-3">
                <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                </div>
                <h4 class="text-sm font-bold text-slate-900">Normes HACCP</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Atelier de transformation aux standards sanitaires stricts garantissant une parfaite sécurité alimentaire.
                </p>
            </div>

            <!-- Engagement 3 -->
            <div class="p-6 rounded-xl bg-slate-50 border border-slate-100 space-y-3">
                <div class="w-10 h-10 rounded-lg bg-teal-100 text-teal-800 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h4 class="text-sm font-bold text-slate-900">0% Sucre Ajouté</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Aucun colorant, aucun additif, ni eau ajoutée. Le goût authentique et les vitamines pures du fruit.
                </p>
            </div>

            <!-- Engagement 4 -->
            <div class="p-6 rounded-xl bg-slate-50 border border-slate-100 space-y-3">
                <div class="w-10 h-10 rounded-lg bg-rose-100 text-rose-800 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <h4 class="text-sm font-bold text-slate-900">Commerce Équitable</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Rémunération juste de nos petits producteurs partenaires dans les différentes régions du Togo.
                </p>
            </div>

        </div>

    </section>

    <!-- ========================================================================= -->
    <!-- 5. NOS BOUTIQUES OFFICIELLES À LOMÉ (#boutiques)                          -->
    <!-- ========================================================================= -->
    <section id="boutiques" class="scroll-mt-24 space-y-6">
        
        <div class="text-center max-w-2xl mx-auto space-y-1">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-700">Réseau de Vente Direct</span>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Nos Boutiques Officielles à Lomé</h2>
            <p class="text-xs sm:text-sm text-slate-500">Passez en boutique pour déguster nos jus frais ou retirer vos commandes.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Boutique 1 : Cacaveli -->
            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-xs flex flex-col justify-between space-y-6">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-800 border border-emerald-200">
                            Boutique Cacaveli
                        </span>
                        <span class="text-xs font-semibold text-emerald-600">Ouvert du Lundi au Samedi</span>
                    </div>

                    <h3 class="text-xl font-black text-slate-900">Boutique & Comptoir Cacaveli</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Retrouvez toute la gamme JOOSSI en bouteille fraîche et les sachets de fruits séchés KINU.
                    </p>

                    <div class="space-y-1.5 pt-2 text-xs font-semibold text-slate-700">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Carrefour Cacaveli, Lomé, Togo</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <span>+228 90 00 00 01</span>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <a href="https://wa.me/22890000001?text=Bonjour%20Boutique%20Cacaveli%2C%20je%20souhaite%20passer%20une%20commande." target="_blank" 
                       class="w-full py-3 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl font-bold text-xs text-center transition-colors flex items-center justify-center gap-2">
                        <span>Commander à Cacaveli</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>

            <!-- Boutique 2 : Hedzranawoe -->
            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-xs flex flex-col justify-between space-y-6">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-teal-50 text-teal-800 border border-teal-200">
                            Boutique Hedzranawoe
                        </span>
                        <span class="text-xs font-semibold text-teal-600">Ouvert du Lundi au Samedi</span>
                    </div>

                    <h3 class="text-xl font-black text-slate-900">Boutique Hedzranawoe</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        À proximité immédiate du marché de Hedzranawoe pour vos achats au détail et paniers de fruits séchés.
                    </p>

                    <div class="space-y-1.5 pt-2 text-xs font-semibold text-slate-700">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-teal-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Marché Hedzranawoe, Lomé, Togo</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-teal-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <span>+228 90 00 00 02</span>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <a href="https://wa.me/22890000002?text=Bonjour%20Boutique%20Hedzranawoe%2C%20je%20souhaite%20passer%20une%20commande." target="_blank" 
                       class="w-full py-3 bg-teal-700 hover:bg-teal-800 text-white rounded-xl font-bold text-xs text-center transition-colors flex items-center justify-center gap-2">
                        <span>Commander à Hedzranawoe</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>

        </div>

    </section>

</div>
@endsection
