<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nouvelle Catégorie') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="name" :value="__('Nom de la Catégorie')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <div>
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-xl shadow-sm">{{ old('description') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>

                    <div class="flex items-center justify-end mt-8">
                        <a href="{{ route('admin.categories.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Annuler</a>
                        <x-primary-button class="bg-green-600 hover:bg-green-700">
                            {{ __('Créer la Catégorie') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
