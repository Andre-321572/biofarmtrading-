@extends('layouts.store')

@section('title', 'Contactez-nous - Bio Farm Trading')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="text-center mb-16">
        <h1 class="text-5xl font-black text-gray-900 mb-4">Parlons Ensemble</h1>
        <p class="text-xl text-gray-500 italic">Une question ? Un partenariat ? Nous sommes à votre écoute.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
        <!-- Contact Info Cards -->
        <div class="space-y-8">
            <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-gray-50 hover:shadow-2xl transition-shadow group">
                <div class="w-14 h-14 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center mb-6 shadow-sm group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Email</h3>
                <p class="text-gray-500 mb-4">Écrivez-nous à tout moment, nous répondons sous 24h.</p>
                <a href="mailto:contact@biofarmtrading.tg" class="text-green-600 font-bold hover:underline">contact@biofarmtrading.tg</a>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-gray-50 hover:shadow-2xl transition-shadow group">
                <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mb-6 shadow-sm group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Téléphone</h3>
                <p class="text-gray-500 mb-4">Disponible du lundi au samedi, de 8h à 18h.</p>
                <a href="tel:+22890000000" class="text-blue-600 font-bold hover:underline">+228 90 00 00 00</a>
            </div>

            <div class="bg-gradient-to-br from-green-600 to-green-400 p-8 rounded-[2.5rem] shadow-xl text-white">
                <h3 class="text-2xl font-bold mb-4">Suivez-nous</h3>
                <p class="mb-6 opacity-90">Rejoignez notre communauté sur les réseaux sociaux pour découvrir nos nouveautés.</p>
                <div class="flex space-x-4">
                    <a href="#" class="p-3 bg-white/20 rounded-xl hover:bg-white/30 transition-colors">
                        <span class="sr-only">Facebook</span>
                        <!-- Custom Icon or Text -->
                        FB
                    </a>
                    <a href="#" class="p-3 bg-white/20 rounded-xl hover:bg-white/30 transition-colors">
                        <span class="sr-only">Instagram</span>
                        IG
                    </a>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="bg-white p-10 rounded-[3rem] shadow-2xl border border-gray-50">
            <h2 class="text-3xl font-black text-gray-900 mb-8">Envoyez-nous un message</h2>
            <form action="{{ route('contact.send') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nom Complet</label>
                        <input type="text" name="name" required class="w-full px-6 py-4 rounded-2xl border-2 border-gray-100 focus:border-green-500 focus:ring-0 transition-all outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" required class="w-full px-6 py-4 rounded-2xl border-2 border-gray-100 focus:border-green-500 focus:ring-0 transition-all outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Sujet</label>
                    <input type="text" name="subject" required class="w-full px-6 py-4 rounded-2xl border-2 border-gray-100 focus:border-green-500 focus:ring-0 transition-all outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Message</label>
                    <textarea name="message" rows="5" required class="w-full px-6 py-4 rounded-2xl border-2 border-gray-100 focus:border-green-500 focus:ring-0 transition-all outline-none"></textarea>
                </div>
                <button type="submit" class="w-full py-5 gradient-brand text-white font-black rounded-2xl shadow-xl hover:shadow-2xl btn-premium text-lg">
                    Envoyer le Message
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
