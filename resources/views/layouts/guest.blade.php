<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Bio Farm Trading') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
             body { font-family: 'Outfit', sans-serif; }
        </style>
    </head>
    <body class="h-full font-sans antialiased text-gray-900">
        <div class="min-h-screen flex">
            <!-- Brand Section (Left) -->
            <div class="hidden lg:flex lg:w-1/2 bg-green-900 relative items-center justify-center overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-green-800 to-green-950 opacity-90"></div>
                <div class="absolute -top-24 -left-24 w-96 h-96 bg-green-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
                <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-yellow-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
                
                <div class="relative z-10 p-12 text-center">
                    <div class="mb-8 flex justify-center">
                        <x-application-logo class="w-48 h-auto rounded-2xl shadow-2xl" />
                    </div>
                    <h2 class="text-4xl font-extrabold text-white mb-6">Bio Farm Trading</h2>
                    <p class="text-green-100 text-lg max-w-md mx-auto leading-relaxed">
                        Accédez à votre espace pour gérer vos commandes et découvrir nos produits 100% Bio.
                    </p>
                </div>
            </div>

            <!-- Form Section (Right) -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-50">
                <div class="w-full max-w-md space-y-8">
                    <div class="bg-white p-10 rounded-[2.5rem] shadow-xl border border-gray-100">
                         <!-- Mobile Logo -->
                        <div class="lg:hidden flex justify-center mb-8">
                             <x-application-logo class="w-24 h-auto" />
                        </div>
                        
                        {{ $slot }}
                    </div>
                    
                    <div class="text-center text-sm text-gray-400">
                        &copy; {{ date('Y') }} Bio Farm Trading. Tous droits réservés.
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
