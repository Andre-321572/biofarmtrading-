<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="space-y-8">
            <!-- Welcome Card -->
            <div class="bg-gradient-to-br from-green-600 to-green-400 rounded-3xl p-8 md:p-12 text-white shadow-xl relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-3xl font-black mb-2">Bienvenue, {{ Auth::user()->name }} !</h3>
                    <p class="text-green-50 opacity-90 mb-6">Heureux de vous revoir sur Bio Farm Trading.</p>
                    
                    @if(Auth::user()->role === 'manager')
                        <a href="{{ route('manager.sales.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-green-700 font-bold rounded-xl hover:bg-green-50 transition-colors shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            Accéder à ma Caisse & Stock
                        </a>
                    @elseif(Auth::user()->role === 'admin')
                         <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-6 py-3 bg-white text-green-700 font-bold rounded-xl hover:bg-green-50 transition-colors shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            Administration & Stock Global
                        </a>
                    @else
                        <p class="text-green-50 opacity-90">Retrouvez ici l'historique de vos commandes.</p>
                    @endif
                </div>
                <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Orders List -->
                <div class="lg:col-span-2 space-y-6">
                    <h4 class="text-xl font-bold text-gray-900">Mes Commandes Récentes</h4>
                    
                    @php
                        $userOrders = \App\Models\Order::where('user_id', Auth::id())->latest()->get();
                    @endphp

                    @forelse($userOrders as $order)
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600 font-bold">
                                    #{{ $order->id }}
                                </div>
                                <div class="hidden sm:block">
                                    <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">{{ $order->created_at->format('d/m/Y') }}</p>
                                    <p class="font-black text-gray-900">{{ number_format($order->total_amount, 0) }} FCFA</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-6">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest 
                                    @if($order->status === 'delivered') bg-green-100 text-green-700
                                    @elseif($order->status === 'pending') bg-yellow-100 text-yellow-700
                                    @else bg-blue-100 text-blue-700 @endif">
                                    {{ $order->status }}
                                </span>
                                <a href="https://wa.me/22890000000?text={{ urlencode('Je souhaite des informations sur ma commande #' . $order->id) }}" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.484 8.412-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.309 1.656zm6.29-4.139c1.52.907 3.013 1.385 4.624 1.386 5.14 0 9.324-4.183 9.327-9.325.002-2.489-.965-4.831-2.724-6.592-1.758-1.759-4.101-2.727-6.591-2.729-5.141 0-9.324 4.183-9.327 9.326-.001 1.68.455 3.313 1.32 4.74l-.993 3.633 3.734-.979zm11.367-5.684c-.312-.156-1.848-.912-2.134-1.017-.286-.104-.494-.156-.703.156s-.807 1.017-1.003 1.24c-.195.223-.39.25-.703.093-.312-.156-1.317-.485-2.51-1.549-.928-.827-1.554-1.849-1.736-2.161-.182-.312-.019-.481.137-.636.141-.139.312-.364.469-.546.156-.182.208-.312.312-.52.104-.208.052-.39-.026-.546-.078-.156-.703-1.693-.963-2.319-.253-.611-.51-.528-.703-.537-.181-.009-.39-.01-.599-.01s-.547.078-.833.39c-.286.312-1.093 1.069-1.093 2.606s1.119 3.018 1.275 3.226c.156.208 2.201 3.361 5.333 4.715.745.322 1.327.515 1.779.659.749.238 1.43.205 1.969.125.599-.089 1.848-.756 2.108-1.485.26-.729.26-1.355.182-1.485-.078-.13-.286-.208-.599-.364z"/></svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="bg-gray-50 rounded-3xl p-12 text-center border-2 border-dashed border-gray-200">
                            <p class="text-gray-400 font-medium">Vous n'avez pas encore passé de commande.</p>
                            <a href="{{ route('home') }}" class="inline-block mt-4 text-green-600 font-bold hover:underline">Démarrer mes achats →</a>
                        </div>
                    @endforelse
                </div>

                <!-- Account Info -->
                <div class="space-y-6">
                    <h4 class="text-xl font-bold text-gray-900">Mon Compte</h4>
                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex flex-col items-center text-center">
                        <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-3xl font-black mb-4">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <p class="font-black text-gray-900 text-lg">{{ Auth::user()->name }}</p>
                        <p class="text-sm text-gray-400 mb-6">{{ Auth::user()->email }}</p>
                        
                        <a href="{{ route('profile.edit') }}" class="w-full py-3 bg-gray-50 text-gray-700 rounded-xl font-bold text-sm hover:bg-gray-100 transition-colors">
                            Gérer mon profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
