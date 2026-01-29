<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestion des Catégories') }}
            </h2>
            <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-xl font-bold hover:bg-green-700 transition-colors shadow-lg shadow-green-200">
                + Nouvelle Catégorie
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50 text-gray-400 text-xs font-bold uppercase tracking-widest">
                                <th class="px-8 py-4">Nom</th>
                                <th class="px-8 py-4">Slug</th>
                                <th class="px-8 py-4">Description</th>
                                <th class="px-8 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($categories as $category)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-8 py-6">
                                        <p class="font-bold text-gray-900">{{ $category->name }}</p>
                                    </td>
                                    <td class="px-8 py-6 text-gray-500 font-mono text-xs">
                                        {{ $category->slug }}
                                    </td>
                                    <td class="px-8 py-6 text-gray-500 text-sm">
                                        {{ Str::limit($category->description, 100) }}
                                    </td>
                                    <td class="px-8 py-6 text-right space-x-2">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="inline-block p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline-block" onsubmit="return confirm('Supprimer cette catégorie ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
