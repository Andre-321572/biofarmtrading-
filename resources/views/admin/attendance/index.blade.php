@php
    $prefix = Auth::user()->role === 'admin' ? 'admin.' : (Auth::user()->role === 'rh' ? 'rh.' : '');
    $workerStoreRoute = Auth::user()->role === 'admin' ? route('admin.workers.store') : route('rh.attendance.store_workers');
@endphp

<x-app-layout>
    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>

    <div x-data="{}" class="py-8 bg-slate-50">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            
            <!-- Controls & Header -->
            <div class="mb-6 flex flex-col md:flex-row justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-slate-200 gap-4">
                <div class="flex items-center gap-4">
                    <div class="p-2 bg-slate-50 rounded-2xl">
                        <img src="{{ public_path('images/biofarm_logo.jpg') }}" class="w-12 h-12 object-contain" onerror="this.src='/images/biofarm_logo.jpg'">
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tight">
                            Pointage <span class="text-green-600">Bio Farm</span>
                        </h2>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">
                            {{ $startOfWeek->format('d') }} au {{ $endOfWeek->translatedFormat('d F Y') }}
                        </p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    <!-- Week Navigation -->
                    <div class="flex items-center bg-slate-100 rounded-xl p-1 shadow-inner">
                        <a href="{{ route($prefix . 'attendance.index', ['date' => $prevWeek]) }}" class="p-2 hover:bg-white hover:shadow-sm rounded-lg transition-all text-slate-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
                        </a>
                        <span class="px-4 font-black text-slate-700 text-[10px] uppercase tracking-tighter">Semaine {{ $startOfWeek->weekOfYear }}</span>
                        <a href="{{ route($prefix . 'attendance.index', ['date' => $nextWeek]) }}" class="p-2 hover:bg-white hover:shadow-sm rounded-lg transition-all text-slate-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>

                    <!-- PDF -->
                    <a href="{{ route($prefix . 'attendance.pdf', ['date' => request('date')]) }}" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl font-black text-xs shadow-lg shadow-red-600/20 transition-all flex items-center gap-2 active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.1" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        EXPORTER PDF
                    </a>
                </div>
            </div>

            <!-- Add Workers Section (Bulk) -->
            <div x-data="{ rows: [{id: Date.now()}] }" class="mb-10 bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50/50 px-8 py-4 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-xs font-black text-slate-600 uppercase tracking-widest flex items-center gap-2">
                        <div class="w-1.5 h-1.5 bg-blue-500 rounded-full"></div>
                        Ajout rapide de personnel
                    </h3>
                    <button @click="rows.push({id: Date.now()})" type="button" class="text-[10px] font-black text-blue-600 hover:text-blue-700 uppercase tracking-[0.2em] flex items-center gap-1 transition-all">
                        + Nouvelle ligne
                    </button>
                </div>

                <form action="{{ $workerStoreRoute }}" method="POST">
                    @csrf
                    <div class="p-8">
                        <div class="space-y-3 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                            <template x-for="(row, index) in rows" :key="row.id">
                                <div class="flex gap-4 items-center animate-in fade-in slide-in-from-top-2 duration-300">
                                    <div class="w-8 h-10 flex items-center justify-center text-[10px] font-black text-slate-300" x-text="index + 1"></div>
                                    <div class="flex-1">
                                        <input type="text" :name="'workers['+index+'][last_name]'" placeholder="NOM" class="w-full h-12 rounded-2xl border-slate-200 bg-slate-50 text-xs font-black uppercase placeholder:text-slate-300 focus:bg-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all px-4" required>
                                    </div>
                                    <div class="flex-1">
                                        <input type="text" :name="'workers['+index+'][first_name]'" placeholder="PRÉNOMS" class="w-full h-12 rounded-2xl border-slate-200 bg-slate-50 text-xs font-bold placeholder:text-slate-300 focus:bg-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all px-4" required>
                                    </div>
                                    <div class="w-48">
                                        <select :name="'workers['+index+'][shift]'" class="w-full h-12 rounded-2xl border-slate-200 bg-slate-50 text-xs font-black uppercase focus:bg-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all cursor-pointer px-4">
                                            <option value="day">ÉQUIPE JOUR</option>
                                            <option value="night">ÉQUIPE NUIT</option>
                                        </select>
                                    </div>
                                    <button x-show="rows.length > 1" @click="rows.splice(index, 1)" type="button" class="p-2 text-slate-300 hover:text-red-500 transition-colors">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="px-8 py-6 bg-slate-100/80 border-t border-slate-200 flex justify-between items-center bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]">
                        <div class="flex items-center gap-2 text-slate-400">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                             <p class="text-[10px] font-black uppercase tracking-widest italic">Vérifiez les noms avant d'enregistrer</p>
                        </div>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-12 py-4 rounded-2xl font-black text-[12px] tracking-[0.2em] shadow-[0_20px_50px_rgba(22,163,74,0.3)] transition-all hover:-translate-y-1 active:scale-95 flex items-center gap-4 border-b-4 border-green-800 group">
                            <span>ENREGISTRER LA LISTE</span>
                            <div class="w-2 h-2 bg-white rounded-full animate-ping group-hover:block"></div>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Day Shift Table (Time Tracking) -->
            <div class="space-y-6 mb-12">
                <div class="flex items-center gap-4">
                    <div class="h-8 w-1 bg-amber-400 rounded-full"></div>
                    <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight flex items-center gap-2">
                        Équipe du Jour
                        <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-1 rounded-md font-bold">{{ $dayWorkers->count() }} ouvriers</span>
                    </h3>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="overflow-x-auto custom-scrollbar shadow-inner">
                        <table class="w-full border-collapse text-[11px] font-medium leading-none min-w-[1000px]">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-100 italic">
                                    <th rowspan="2" class="px-2 py-4 w-10 text-center text-slate-400 border-r border-slate-100 sticky left-0 bg-slate-50/50 z-10">N°</th>
                                    <th rowspan="2" class="px-4 py-4 text-left w-48 border-r border-slate-100 uppercase tracking-wider font-black text-slate-600 sticky left-10 bg-slate-50/50 z-10">Nom</th>
                                    <th rowspan="2" class="px-4 py-4 text-left w-56 border-r border-slate-100 uppercase tracking-wider font-black text-slate-600 sticky left-[12.5rem] bg-slate-50/50 z-10">Prénoms</th>
                                    @foreach($days as $day)
                                        <th colspan="2" class="px-1 py-3 text-center border-r border-slate-100 {{ $day->isToday() ? 'bg-green-50/50' : '' }}">
                                            <span class="block uppercase font-black text-slate-700 tracking-tighter">{{ $day->translatedFormat('l') }}</span>
                                            <span class="text-[9px] font-bold text-slate-400 opacity-60">{{ $day->format('d/m') }}</span>
                                        </th>
                                    @endforeach
                                    <th rowspan="2" class="px-2 py-4 w-16 text-center text-slate-400 uppercase tracking-widest text-[9px] font-black border-r border-slate-100">Total H.</th>
                                    <th rowspan="2" class="px-2 py-4 w-16 text-center text-slate-400 uppercase tracking-widest text-[9px] font-black">Actions</th>
                                </tr>
                                <tr class="bg-slate-50/30 border-b border-slate-100">
                                    @foreach($days as $day)
                                        <th class="py-1.5 text-center w-12 border-r border-slate-100 text-[9px] font-black text-slate-400 {{ $day->isToday() ? 'bg-green-50/80 text-green-700' : '' }}">ARR.</th>
                                        <th class="py-1.5 text-center w-12 border-r border-slate-100 text-[9px] font-black text-slate-400 {{ $day->isToday() ? 'bg-green-50/80 text-green-700' : '' }}">DÉP.</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($dayWorkers as $index => $worker)
                                    @php
                                        $totalMinutes = 0;
                                        foreach($worker->attendances as $att) {
                                            if ($att->arrival_time && $att->departure_time) {
                                                $start = \Carbon\Carbon::parse($att->arrival_time);
                                                $end = \Carbon\Carbon::parse($att->departure_time);
                                                $totalMinutes += $end->diffInMinutes($start);
                                            } elseif ($att->status === 'present') {
                                                $totalMinutes += 240; // 4 hours per session mark if legacy
                                            }
                                        }
                                        $totalHours = floor($totalMinutes / 60) . 'h' . ($totalMinutes % 60 > 0 ? sprintf('%02d', $totalMinutes % 60) : '');
                                    @endphp
                                    <tr class="hover:bg-slate-50/50 transition-colors group">
                                        <td class="py-3 text-center font-bold text-slate-300 border-r border-slate-100 text-[10px] sticky left-0 bg-white group-hover:bg-slate-50 z-10">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 font-black text-slate-800 uppercase tracking-tighter border-r border-slate-100 sticky left-10 bg-white group-hover:bg-slate-50 z-10">{{ $worker->last_name }}</td>
                                        <td class="px-4 py-3 text-slate-500 font-bold italic border-r border-slate-100 sticky left-[12.5rem] bg-white group-hover:bg-slate-50 z-10">{{ $worker->first_name }}</td>
                                        
                                        @foreach($days as $day)
                                            @php
                                                $att = $worker->attendances->where('date', $day->format('Y-m-d'))->where('session', 'morning')->first();
                                            @endphp
                                            <td class="p-0 text-center h-12 w-12 border-r border-slate-100 bg-white hover:bg-slate-50 transition-colors">
                                                <input type="time" 
                                                    value="{{ $att && $att->arrival_time ? \Carbon\Carbon::parse($att->arrival_time)->format('H:i') : '' }}"
                                                    onchange="updateTime(this, '{{ $worker->id }}', '{{ $day->format('Y-m-d') }}', 'arrival_time')"
                                                    class="w-full h-full border-none bg-transparent text-[10px] font-black p-0 text-center focus:ring-2 focus:ring-green-500/20 focus:bg-white transition-all">
                                            </td>
                                            <td class="p-0 text-center h-12 w-12 border-r border-slate-100 bg-white hover:bg-slate-50 transition-colors">
                                                <input type="time" 
                                                    value="{{ $att && $att->departure_time ? \Carbon\Carbon::parse($att->departure_time)->format('H:i') : '' }}"
                                                    onchange="updateTime(this, '{{ $worker->id }}', '{{ $day->format('Y-m-d') }}', 'departure_time')"
                                                    class="w-full h-full border-none bg-transparent text-[10px] font-black p-0 text-center focus:ring-2 focus:ring-green-500/20 focus:bg-white transition-all">
                                            </td>
                                        @endforeach
                                        
                                        <td class="py-3 text-center font-black bg-slate-50/50 text-slate-700 border-r border-slate-100">{{ $totalHours }}</td>

                                        <td class="py-3 px-2 text-center group-hover:bg-slate-50/80 transition-all">
                                            <div class="flex items-center justify-center gap-1">
                                                <button @click="$dispatch('edit-worker', { id: '{{ $worker->id }}', last_name: '{{ addslashes($worker->last_name) }}', first_name: '{{ addslashes($worker->first_name) }}', shift: '{{ $worker->shift }}' })" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-white rounded-lg transition-all" title="Modifier">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                                </button>
                                                <form action="{{ route($prefix . ($prefix == 'admin.' ? 'workers.destroy' : 'attendance.destroy_worker'), $worker) }}" method="POST" onsubmit="return confirm('Supprimer cet ouvrier et tout son historique de pointage ?')" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-white rounded-lg transition-all" title="Supprimer">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ 5 + (count($days) * 2) }}" class="py-12 text-center">
                                            <div class="flex flex-col items-center opacity-20">
                                                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                                <p class="text-[10px] font-black uppercase tracking-widest">Aucun ouvrier de jour</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Night Shift Table (Status Only) -->
            <div class="space-y-6">
                <div class="flex items-center gap-4">
                    <div class="h-8 w-1 bg-indigo-600 rounded-full"></div>
                    <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight flex items-center gap-2">
                        Équipe de Nuit
                        <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-1 rounded-md font-bold">{{ $nightWorkers->count() }} ouvriers</span>
                    </h3>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="overflow-x-auto custom-scrollbar shadow-inner">
                        <table class="w-full border-collapse text-[11px] font-medium leading-none min-w-[1000px]">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-100 italic">
                                    <th class="px-2 py-4 w-10 text-center text-slate-400 border-r border-slate-100 sticky left-0 bg-slate-50/50 z-10">N°</th>
                                    <th class="px-4 py-4 text-left w-48 border-r border-slate-100 uppercase tracking-wider font-black text-slate-600 sticky left-10 bg-slate-50/50 z-10">Nom</th>
                                    <th class="px-4 py-4 text-left w-56 border-r border-slate-100 uppercase tracking-wider font-black text-slate-600 sticky left-[12.5rem] bg-slate-50/50 z-10">Prénoms</th>
                                    @foreach($days as $day)
                                        <th class="px-1 py-3 text-center border-r border-slate-100 {{ $day->isToday() ? 'bg-green-50/50' : '' }}">
                                            <span class="block uppercase font-black text-slate-700 tracking-tighter">{{ $day->translatedFormat('l') }}</span>
                                            <span class="text-[9px] font-bold text-slate-400 opacity-60">{{ $day->format('d/m') }}</span>
                                        </th>
                                    @endforeach
                                    <th class="px-2 py-4 w-16 text-center text-slate-400 uppercase tracking-widest text-[9px] font-black border-r border-slate-100">Total H.</th>
                                    <th class="px-2 py-4 w-16 text-center text-slate-400 uppercase tracking-widest text-[9px] font-black">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($nightWorkers as $index => $worker)
                                    @php
                                        $totalMinutesNight = 0;
                                        foreach($worker->attendances as $att) {
                                            if ($att->status === 'present') {
                                                $totalMinutesNight += 240; // 4 hours for night shift by default
                                            }
                                        }
                                        $totalHoursNight = floor($totalMinutesNight / 60) . 'h' . ($totalMinutesNight % 60 > 0 ? sprintf('%02d', $totalMinutesNight % 60) : '');
                                    @endphp
                                    <tr class="hover:bg-slate-50/50 transition-colors group">
                                        <td class="py-3 text-center font-bold text-slate-300 border-r border-slate-100 text-[10px] sticky left-0 bg-white group-hover:bg-slate-50 z-10">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 font-black text-slate-800 uppercase tracking-tighter border-r border-slate-100 sticky left-10 bg-white group-hover:bg-slate-50 z-10">{{ $worker->last_name }}</td>
                                        <td class="px-4 py-3 text-slate-500 font-bold italic border-r border-slate-100 sticky left-[12.5rem] bg-white group-hover:bg-slate-50 z-10">{{ $worker->first_name }}</td>
                                        
                                        @foreach($days as $day)
                                            @php
                                                // Using 'morning' session as container for Daily Night shift status too
                                                $att = $worker->attendances->where('date', $day->format('Y-m-d'))->where('session', 'morning')->first();
                                                $status = $att ? $att->status : 'none';
                                            @endphp
                                            <td class="p-0 text-center h-12 w-8 cursor-pointer relative border-r border-slate-100 group-hover:border-slate-200 transition-all"
                                                onclick="cycleStatus(this, '{{ $worker->id }}', '{{ $day->format('Y-m-d') }}', 'morning')">
                                                
                                                <div class="status-icon flex items-center justify-center h-full w-full" data-status="{{ $status }}">
                                                     @if($status == 'present')
                                                        <span class="text-green-600 font-black text-sm">✓</span>
                                                    @elseif($status == 'absent')
                                                        <span class="text-red-500 font-black text-sm">✕</span>
                                                    @endif
                                                </div>
                                            </td>
                                        @endforeach
                                        
                                        <td class="py-3 text-center font-black bg-slate-50/50 text-indigo-700 border-r border-slate-100">{{ $totalHoursNight }}</td>

                                        <td class="py-3 px-2 text-center group-hover:bg-slate-50/80 transition-all">
                                            <div class="flex items-center justify-center gap-1">
                                                <button @click="$dispatch('edit-worker', { id: '{{ $worker->id }}', last_name: '{{ addslashes($worker->last_name) }}', first_name: '{{ addslashes($worker->first_name) }}', shift: '{{ $worker->shift }}' })" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-white rounded-lg transition-all" title="Modifier">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                                </button>
                                                <form action="{{ route($prefix . ($prefix == 'admin.' ? 'workers.destroy' : 'attendance.destroy_worker'), $worker) }}" method="POST" onsubmit="return confirm('Supprimer cet ouvrier et tout son historique de pointage ?')" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-white rounded-lg transition-all" title="Supprimer">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ 5 + count($days) }}" class="py-12 text-center">
                                            <div class="flex flex-col items-center opacity-20">
                                                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                                                <p class="text-[10px] font-black uppercase tracking-widest">Aucun ouvrier de nuit</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Worker Modal -->
    <div x-data="{ 
            open: false, 
            worker: { id: '', last_name: '', first_name: '', shift: 'day' },
            prefix: '{{ $prefix }}'
        }" 
        @edit-worker.window="worker = $event.detail; open = true;"
        x-show="open" 
        class="fixed inset-0 z-50 overflow-y-auto" 
        x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true" @click="open = false">
                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                <div class="bg-white p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-black text-slate-800 uppercase tracking-tight">Modifier l'ouvrier</h3>
                        <button @click="open = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    <form :action="prefix === 'admin.' ? '/admin/workers/' + worker.id : '/rh/workers/' + worker.id" method="POST" class="space-y-5">
                        @csrf
                        @method('PATCH')
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Nom de famille</label>
                            <input type="text" name="last_name" x-model="worker.last_name" class="w-full rounded-2xl border-slate-200 bg-slate-50 text-sm font-black uppercase focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all h-12 px-4">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Prénoms</label>
                            <input type="text" name="first_name" x-model="worker.first_name" class="w-full rounded-2xl border-slate-200 bg-slate-50 text-sm font-bold focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all h-12 px-4">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Équipe</label>
                            <select name="shift" x-model="worker.shift" class="w-full rounded-2xl border-slate-200 bg-slate-50 text-sm font-black uppercase focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all h-12 px-4">
                                <option value="day">JOUR</option>
                                <option value="night">NUIT</option>
                            </select>
                        </div>
                        <div class="pt-4 flex gap-3">
                            <button type="button" @click="open = false" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 py-4 rounded-2xl font-black text-xs tracking-widest transition-all">ANNULER</button>
                            <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-4 rounded-2xl font-black text-xs tracking-widest shadow-xl shadow-green-600/20 transition-all active:scale-95">ENREGISTRER</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Script for status cycle -->
    <script>
        const updateUrl = "{{ route($prefix . 'attendance.update') }}";
        const csrfToken = "{{ csrf_token() }}";

        function updateTime(input, workerId, date, field) {
            const val = input.value;
            
            fetch(updateUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ worker_id: workerId, date: date, session: 'morning', [field]: val })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    // Optional: Visual feedback of success (green border?)
                    input.classList.remove('text-slate-600');
                    input.classList.add('text-green-600');
                    setTimeout(() => {
                        input.classList.remove('text-green-600');
                        input.classList.add('text-slate-600');
                    }, 1000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors de la sauvegarde.');
            });
        }

        function cycleStatus(cell, workerId, date, session) {
            const iconDiv = cell.querySelector('.status-icon');
            let currentStatus = iconDiv.getAttribute('data-status');
            
            // Toggle logic: None -> Present -> Absent -> None
            let nextStatus = 'none';
            if (currentStatus === 'none') nextStatus = 'present';
            else if (currentStatus === 'present') nextStatus = 'absent';
            else if (currentStatus === 'absent') nextStatus = 'none'; 

            updateVisual(iconDiv, nextStatus);

            fetch(updateUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ worker_id: workerId, date: date, session: session, status: nextStatus })
            })
            .then(response => response.json())
            .catch(error => {
                console.error('Error:', error);
                updateVisual(iconDiv, currentStatus);
                alert('Erreur lors de la mise à jour.');
            });
        }

        function updateVisual(div, status) {
            div.setAttribute('data-status', status);
            div.innerHTML = '';
            
            if (status === 'present') {
                div.innerHTML = '<span class="text-green-600 font-black text-sm">✓</span>';
            } else if (status === 'absent') {
                div.innerHTML = '<span class="text-red-500 font-black text-sm">✕</span>';
            }
        }
    </script>
</x-app-layout>
