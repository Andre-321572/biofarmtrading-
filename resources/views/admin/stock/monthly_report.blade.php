<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rapport Mensuel d\'Approvisionnement') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <!-- Filters -->
                    <form method="GET" action="{{ route('admin.stock.monthly') }}" class="mb-6 flex gap-4 items-end">
                        <div>
                            <x-input-label for="month" :value="__('Mois')" />
                            <select name="month" id="month" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $i == $month ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <x-input-label for="year" :value="__('Année')" />
                            <select name="year" id="year" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @for($i = date('Y') - 1; $i <= date('Y') + 1; $i++)
                                    <option value="{{ $i }}" {{ $i == $year ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <x-primary-button>
                            {{ __('Filtrer') }}
                        </x-primary-button>
                    </form>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Produit
                                    </th>
                                    @foreach($shops as $shop)
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $shop->name }}
                                        </th>
                                    @endforeach
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-100">
                                        TOTAL
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($products as $product)
                                    @php
                                        $rowTotal = 0;
                                        // Only show row if there was some supply
                                        $hasData = false;
                                        foreach($shops as $shop) {
                                            if (isset($data[$product->id][$shop->id]) && $data[$product->id][$shop->id] > 0) {
                                                $hasData = true;
                                                break;
                                            }
                                        }
                                    @endphp

                                    @if($hasData)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $product->name }}
                                            </td>
                                            @foreach($shops as $shop)
                                                @php
                                                    $qty = $data[$product->id][$shop->id] ?? 0;
                                                    $rowTotal += $qty;
                                                @endphp
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                    @if($qty > 0)
                                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full font-bold">
                                                            +{{ $qty }}
                                                        </span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            @endforeach
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-center bg-gray-50">
                                                {{ $rowTotal }}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                        
                        @if(empty($data))
                            <div class="text-center py-10 text-gray-500">
                                Aucune donnée d'approvisionnement pour cette période.
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
