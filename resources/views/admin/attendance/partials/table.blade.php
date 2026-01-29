<div class="overflow-x-auto">
    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-200">
                <th rowspan="2" class="sticky left-0 z-20 bg-gray-50 px-4 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest border-r border-gray-200">Personnel</th>
                @foreach($days as $day)
                    <th colspan="2" class="px-2 py-3 text-center border-r border-gray-200 {{ $day->isToday() ? 'bg-green-50' : '' }}">
                        <div class="flex flex-col items-center">
                            <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest">{{ $day->translatedFormat('l') }}</span>
                            <span class="text-xs font-black {{ $day->isToday() ? 'text-green-600' : 'text-gray-800' }}">{{ $day->format('d/m') }}</span>
                        </div>
                    </th>
                @endforeach
                <th colspan="2" class="bg-gray-50 px-3 py-3 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest border-l border-gray-200">Total</th>
            </tr>
            <tr class="bg-gray-50 border-b border-gray-200">
                @foreach($days as $day)
                    <th class="py-2 text-[8px] font-black text-gray-400 border-r border-gray-100 text-center">M</th>
                    <th class="py-2 text-[8px] font-black text-gray-400 border-r border-gray-200 text-center">S</th>
                @endforeach
                <th class="py-2 text-[8px] font-black text-gray-400 border-r border-gray-100 text-center">M</th>
                <th class="py-2 text-[8px] font-black text-gray-400 text-center">S</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($workers as $worker)
                @php
                    $totalM = 0; $totalS = 0;
                @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="sticky left-0 z-10 bg-white group-hover:bg-gray-50 px-4 py-3 whitespace-nowrap border-r border-gray-100">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-gray-900 uppercase tracking-tight leading-none mb-1">{{ $worker->last_name }}</span>
                            <span class="text-[10px] font-medium text-gray-400 leading-none">{{ $worker->first_name }}</span>
                        </div>
                    </td>
                    
                    @foreach($days as $day)
                        @foreach(['morning', 'afternoon'] as $session)
                            @php
                                $att = $worker->attendances->where('date', $day->format('Y-m-d'))->where('session', $session)->first();
                                $status = $att ? $att->status : 'none';
                                if ($status == 'present') { $session == 'morning' ? $totalM++ : $totalS++; }
                            @endphp
                            <td class="p-0 text-center border-r border-gray-50 cursor-pointer h-14 min-w-[45px]"
                                onclick="cycleStatus(this, '{{ $worker->id }}', '{{ $day->format('Y-m-d') }}', '{{ $session }}')">
                                <div class="status-icon flex items-center justify-center w-full h-full" data-status="{{ $status }}">
                                    @if($status == 'present')
                                        <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                    @elseif($status == 'absent')
                                        <div class="w-8 h-8 rounded-full bg-red-500 flex items-center justify-center text-white">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </div>
                                    @elseif($status == 'late')
                                        <div class="w-8 h-8 rounded-full bg-amber-500 flex items-center justify-center text-white font-bold text-xs">L</div>
                                    @else
                                        <div class="w-6 h-6 rounded-lg border-2 border-gray-100"></div>
                                    @endif
                                </div>
                            </td>
                        @endforeach
                    @endforeach
                    
                    <td class="bg-gray-50/50 text-center border-l border-gray-200">
                        <span class="text-xs font-black text-green-600">{{ $totalM }}</span>
                    </td>
                    <td class="bg-gray-50/50 text-center">
                        <span class="text-xs font-black text-blue-600">{{ $totalS }}</span>
                    </td>
                </tr>
            @endforeach
            
            @if(count($workers) == 0)
                <tr>
                    <td colspan="{{ 3 + (count($days) * 2) + 2 }}" class="py-16 text-center text-gray-300">
                        <span class="text-xs font-bold uppercase tracking-widest">Aucun ouvrier enregistré</span>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
