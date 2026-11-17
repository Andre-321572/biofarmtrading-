@extends('layouts.store')

@section('title', 'Contact & Points de Vente - Bio Farm Trading')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-16">

    <!-- En-tête Institutionnel Sobre -->
    <div class="text-center max-w-3xl mx-auto space-y-3">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-50 text-emerald-800 border border-emerald-200">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            Service Client & Relations Partenaires
        </span>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 tracking-tight">
            Contactez Bio Farm Trading
        </h1>
        <p class="text-base text-slate-600 leading-relaxed font-normal">
            Particuliers, distributeurs, supermarchés ou partenaires exportateurs : notre équipe commerciale basée à Lomé est à votre disposition pour toute commande ou demande de partenariat.
        </p>
    </div>

    <!-- Grille Principale : Coordonnées Directes & Formulaire Commercial -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
        
        <!-- Colonne Gauche : Boutiques & Contacts Directs (5 cols) -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- Carte 1 : Boutique Cacaveli -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4 hover:border-emerald-300 transition-colors">
                <div class="flex items-center justify-between">
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-900 border border-emerald-200">
                        Boutique Officielle
                    </span>
                    <span class="text-xs font-semibold text-emerald-700">Ouvert 6j/7</span>
                </div>

                <div>
                    <h3 class="text-lg font-black text-slate-900">Boutique & Comptoir Cacaveli</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Vente au détail, dégustation et retrait de commandes.</p>
                </div>

                <div class="space-y-2 text-xs text-slate-700 font-medium">
                    <div class="flex items-start gap-2.5">
                        <svg class="w-4 h-4 text-emerald-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Carrefour Cacaveli, Lomé, Togo</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span>+228 90 00 00 01</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Du lundi au samedi : 08h00 - 18h30</span>
                    </div>
                </div>

                <div class="pt-2">
                    <a href="https://wa.me/22890000001?text=Bonjour%20Boutique%20Cacaveli%2C%20je%20souhaite%20des%20renseignements%20sur%20les%20produits%20Bio%20Farm" target="_blank" 
                       class="w-full py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl font-bold text-xs flex items-center justify-center gap-2 transition-colors">
                        <span>Écrire sur WhatsApp</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>

            <!-- Carte 2 : Boutique Hedzranawoe -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4 hover:border-teal-300 transition-colors">
                <div class="flex items-center justify-between">
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-teal-100 text-teal-900 border border-teal-200">
                        Boutique Marché
                    </span>
                    <span class="text-xs font-semibold text-teal-700">Ouvert 6j/7</span>
                </div>

                <div>
                    <h3 class="text-lg font-black text-slate-900">Boutique Hedzranawoe</h3>
                    <p class="text-xs text-slate-500 mt-0.5">À proximité directe du marché moderne.</p>
                </div>

                <div class="space-y-2 text-xs text-slate-700 font-medium">
                    <div class="flex items-start gap-2.5">
                        <svg class="w-4 h-4 text-teal-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Marché Hedzranawoe, Lomé, Togo</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-teal-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span>+228 90 00 00 02</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-teal-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Du lundi au samedi : 08h00 - 18h30</span>
                    </div>
                </div>

                <div class="pt-2">
                    <a href="https://wa.me/22890000002?text=Bonjour%20Boutique%20Hedzranawoe%2C%20je%20souhaite%20des%20renseignements%20sur%20les%20produits%20Bio%20Farm" target="_blank" 
                       class="w-full py-2.5 bg-teal-700 hover:bg-teal-800 text-white rounded-xl font-bold text-xs flex items-center justify-center gap-2 transition-colors">
                        <span>Écrire sur WhatsApp</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>

            <!-- Carte 3 : Direction Commerciale & Export -->
            <div class="bg-slate-900 rounded-2xl p-6 text-white space-y-3 border border-slate-800">
                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-400">Siège & Commandes B2B</span>
                <h4 class="text-base font-black">Bio Farm Trading SARL</h4>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Pour les commandes industrielles, les grossistes et les démarches d'exportation vers l'Afrique de l'Ouest et l'international.
                </p>
                <div class="pt-2 border-t border-slate-800 space-y-1.5 text-xs text-slate-300">
                    <div>Email : <a href="mailto:contact@biofarmtrading.com" class="text-emerald-300 font-bold hover:underline">contact@biofarmtrading.com</a></div>
                    <div>Lomé, République Togolaise</div>
                </div>
            </div>

        </div>

        <!-- Colonne Droite : Formulaire de Contact Professionnel (7 cols) -->
        <div class="lg:col-span-7 bg-white rounded-2xl p-8 sm:p-10 border border-slate-200 shadow-sm space-y-6">
            
            <div class="space-y-1 border-b border-slate-100 pb-5">
                <h2 class="text-2xl font-black text-slate-900">Envoyez-nous un Message</h2>
                <p class="text-xs sm:text-sm text-slate-500">Remplissez le formulaire ci-dessous, notre équipe vous répondra sous 24 heures ouvrées.</p>
            </div>

            <form action="{{ route('contact.send') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Type de Demande (Profil) -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Vous êtes :</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 text-xs">
                        <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer">
                            <input type="radio" name="profile" value="particulier" checked class="text-emerald-700 focus:ring-emerald-700">
                            <span class="font-semibold text-slate-800">Particulier</span>
                        </label>
                        <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer">
                            <input type="radio" name="profile" value="distributeur" class="text-emerald-700 focus:ring-emerald-700">
                            <span class="font-semibold text-slate-800">Distributeur</span>
                        </label>
                        <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer col-span-2 sm:col-span-1">
                            <input type="radio" name="profile" value="export" class="text-emerald-700 focus:ring-emerald-700">
                            <span class="font-semibold text-slate-800">B2B / Export</span>
                        </label>
                    </div>
                </div>

                <!-- Nom & Téléphone -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nom Complet *</label>
                        <input type="text" name="name" required 
                               placeholder="Ex : Kofi Mensah"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 text-xs font-semibold text-slate-800 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Téléphone / WhatsApp</label>
                        <input type="tel" name="phone" 
                               placeholder="Ex : +228 90 00 00 00"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 text-xs font-semibold text-slate-800 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 transition-all">
                    </div>
                </div>

                <!-- Email & Sujet -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Adresse Email *</label>
                        <input type="email" name="email" required 
                               placeholder="votre-email@exemple.com"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 text-xs font-semibold text-slate-800 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Sujet de la Demande *</label>
                        <input type="text" name="subject" required 
                               placeholder="Ex : Commande de purs jus JOOSSI"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 text-xs font-semibold text-slate-800 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 transition-all">
                    </div>
                </div>

                <!-- Message -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Votre Message *</label>
                    <textarea name="message" rows="5" required 
                              placeholder="Précisez votre demande, quantités souhaitées ou points de livraison..."
                              class="w-full px-4 py-3 rounded-xl border border-slate-200 text-xs font-semibold text-slate-800 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 transition-all"></textarea>
                </div>

                <!-- Bouton d'Envoi -->
                <button type="submit" 
                        class="w-full py-4 bg-emerald-700 hover:bg-emerald-800 text-white font-black text-xs uppercase tracking-wider rounded-xl shadow-md shadow-emerald-700/20 transition-all flex items-center justify-center gap-2 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    <span>Envoyer mon Message</span>
                </button>
            </form>

        </div>

    </div>

    <!-- Foire Aux Questions / Réponses Rapides -->
    <div class="rounded-2xl bg-slate-50 p-8 sm:p-10 border border-slate-200 space-y-6">
        <div class="text-center max-w-xl mx-auto space-y-1">
            <h3 class="text-xl font-black text-slate-900">Questions Fréquemment Posées</h3>
            <p class="text-xs text-slate-500">Retrouvez les réponses immédiates à vos questions courantes.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
            <div class="bg-white p-5 rounded-xl border border-slate-200 space-y-2">
                <h4 class="text-xs font-bold text-slate-900">Comment récupérer ma commande ?</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Vous pouvez retirer gratuitement votre commande dans nos boutiques de Cacaveli ou Hedzranawoe, ou demander une livraison à domicile à Lomé.
                </p>
            </div>
            <div class="bg-white p-5 rounded-xl border border-slate-200 space-y-2">
                <h4 class="text-xs font-bold text-slate-900">Vos produits sont-ils 100% naturels ?</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Absolument. Nos jus JOOSSI sont pressés sans eau ajoutée ni conservateur, et nos fruits KINU sont séchés sans sucres raffinés ajoutés.
                </p>
            </div>
            <div class="bg-white p-5 rounded-xl border border-slate-200 space-y-2">
                <h4 class="text-xs font-bold text-slate-900">Proposez-vous des tarifs pour revendeurs ?</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Oui, nous disposons d'une grille tarifaire dédiée aux supermarchés, restaurants, hôtels et revendeurs avec des conditionnements par carton.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection
