<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier le Produit: ') }} {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    @if(auth()->user()->role === 'admin')
                        <div>
                            <x-input-label for="name" :value="__('Nom du Produit')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $product->name)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="category_id" :value="__('Catégorie')" />
                            <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-xl shadow-sm">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="price" :value="__('Prix (FCFA)')" />
                                <x-text-input id="price" name="price" type="number" class="mt-1 block w-full" :value="old('price', $product->price)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('price')" />
                            </div>
                            <!-- Admin sees total stock or can't edit it? Admin can edit global params. Stock is per shop now. -->
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-xl shadow-sm">{{ old('description', $product->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div>
                            <x-input-label for="image" :value="__('Image du Produit')" />
                            @if($product->image_path)
                                <div class="mt-2 mb-4">
                                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-32 h-32 rounded-xl object-cover border border-gray-200">
                                </div>
                            @endif
                            <input id="image" name="image" type="file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-green-50 file:text-green-700 hover:file:bg-green-100" />
                            <x-input-error class="mt-2" :messages="$errors->get('image')" />
                        </div>
                    @else
                        <!-- Manager View -->
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $product->name }}</h3>
                            <p class="text-sm text-gray-500 mb-4">{{ $product->description }}</p>
                            @if($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}" class="w-24 h-24 rounded-xl object-cover mb-4">
                            @endif
                        </div>
                        
                        <div>
                            <x-input-label for="stock" :value="__('Stock dans votre boutique (' . auth()->user()->shop->name . ')')" />
                            <x-text-input id="stock" name="stock" type="number" class="mt-1 block w-full" :value="old('stock', $shopStock ?? 0)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('stock')" />
                        </div>
                    @endif
                    
                    <div class="flex items-center justify-end mt-8">
                        <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Annuler</a>
                        <x-primary-button class="bg-green-600 hover:bg-green-700">
                            {{ __('Mettre à jour le Produit') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
