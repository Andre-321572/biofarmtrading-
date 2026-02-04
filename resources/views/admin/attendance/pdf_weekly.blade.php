<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Liste de Pointage - Bio Farm Trading</title>
    <style>
        @page { margin: 10mm; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10pt; margin: 0; padding: 0; color: #000; }
        
        .header { text-align: center; margin-bottom: 5px; position: relative; }
        .africa-logo { width: 100px; margin-bottom: 10px; }
        .title { font-size: 24pt; font-weight: bold; color: #15803d; text-transform: uppercase; margin-bottom: 2px; }
        .subtitle { font-size: 8pt; font-weight: bold; color: #333; margin-bottom: 5px; }
        .green-line { width: 100%; height: 2px; background-color: #15803d; margin-bottom: 15px; }
        
        .week-info { text-align: center; font-size: 14pt; font-weight: bold; margin-bottom: 15px; text-transform: lowercase; }
        .week-info::first-letter { text-transform: uppercase; }

        .shift-title { 
            background-color: #f3f4f6; 
            padding: 5px 10px; 
            font-weight: bold; 
            border-left: 4px solid #15803d; 
            margin: 15px 0 5px 0;
            text-transform: uppercase;
            font-size: 10pt;
        }

        table { width: 100%; border-collapse: collapse; border: 1px solid #000; margin-bottom: 10px; }
        th, td { border: 1px solid #000; padding: 2px; text-align: center; vertical-align: middle; font-size: 8pt; }
        
        thead th { background-color: #f9fafb; font-size: 7.5pt; font-weight: bold; }
        .col-n { width: 4%; }
        .col-name { width: 15%; text-align: left; padding-left: 3px; font-weight: bold; text-transform: uppercase; overflow: hidden; }
        .col-prenom { width: 15%; text-align: left; padding-left: 3px; font-style: italic; overflow: hidden; }
        .col-day { width: 8.5%; }
        .col-session { width: 4%; font-size: 7pt; font-weight: bold; }
        .col-total { width: 5.5%; font-weight: bold; background-color: #f3f4f6; }

        .present { color: #15803d; font-weight: bold; font-size: 12pt; }
        .absent { color: #dc2626; font-weight: bold; font-size: 12pt; }
        .late { color: #c2410c; font-weight: bold; font-size: 8pt; }

        /* Footer */
        .footer { position: fixed; bottom: -8mm; left: 0; right: 0; font-size: 8pt; color: #555; }
        .footer-table { border: none; width: 100%; }
        .footer-table td { border: none; padding: 0; }
        .footer-left { text-align: left; vertical-align: bottom; }
        .footer-right { text-align: right; vertical-align: bottom; }
        .certif-box { font-size: 7.5pt; text-align: right; }
        .legal-info { font-weight: bold; color: #000; }
        
    </style>
</head>
<body>

    <div class="header" style="text-align: left;">
        <div style="display: table; width: 100%;">
            <div style="display: table-cell; vertical-align: middle; width: 100px;">
                <img src="{{ public_path('images/biofarm_logo.jpg') }}" style="width: 80px; height: auto;">
            </div>
            <div style="display: table-cell; vertical-align: middle; text-align: center; padding-right: 100px;">
                <div class="title">BIO FARM TRADING</div>
                <div class="subtitle">Production - Transformation - Commercialisation des produits agricoles biologiques</div>
            </div>
        </div>
        <div class="green-line"></div>
    </div>

    <div class="week-info">
        liste de pointage journalier {{ $startOfWeek->format('d') }} au {{ $endOfWeek->translatedFormat('d F Y') }}
    </div>

    @if($dayWorkers->count() > 0)
        <div class="shift-title">Équipe de Jour</div>
        <table>
            <thead>
                <tr>
                    <th rowspan="2" class="col-n">N°</th>
                    <th rowspan="2" class="col-name">Nom</th>
                    <th rowspan="2" class="col-prenom">Prénoms</th>
                    @foreach($days as $day)
                        <th colspan="2">{{ $day->translatedFormat('l') }}</th>
                    @endforeach
                    <th rowspan="2" class="col-total">HT</th>
                </tr>
                <tr>
                    @foreach($days as $day)
                        <th class="col-session">Arr.</th>
                        <th class="col-session">Dép.</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($dayWorkers as $index => $worker)
                    @php
                        $totalMinutes = 0;
                        $attendancesByDay = [];
                        foreach($worker->attendances as $att) {
                            if ($att->date) {
                                $d = \Carbon\Carbon::parse($att->date)->format('Y-m-d');
                                $attendancesByDay[$d][] = $att;
                            }
                        }

                        foreach($attendancesByDay as $dayDate => $dayAtts) {
                            $dayMinutes = 0;
                            foreach($dayAtts as $att) {
                                if ($att->arrival_time && $att->departure_time) {
                                    $start = \Carbon\Carbon::parse($dayDate . ' ' . $att->arrival_time);
                                    $end = \Carbon\Carbon::parse($dayDate . ' ' . $att->departure_time);
                                    if ($end->lessThan($start)) $end->addDay();
                                    $dayMinutes += $end->diffInMinutes($start);
                                }
                            }
                            if ($dayMinutes > 0) {
                                $dayMinutes = max(0, $dayMinutes - 120);
                            }
                            $totalMinutes += $dayMinutes;
                        }
                        
                        $h = floor(abs($totalMinutes) / 60);
                        $m = abs($totalMinutes) % 60;
                        $totalHoursText = $h . 'h' . ($m > 0 ? sprintf('%02d', $m) : '');
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="col-name">{{ $worker->last_name }}</td>
                        <td class="col-prenom">{{ $worker->first_name }}</td>
                        
                        @foreach($days as $day)
                            @php
                                $att = $worker->attendances->where('date', $day->format('Y-m-d'))->first();
                            @endphp
                            <td>
                                {{ $att && $att->arrival_time ? \Carbon\Carbon::parse($att->arrival_time)->format('H:i') : '' }}
                            </td>
                            <td>
                                {{ $att && $att->departure_time ? \Carbon\Carbon::parse($att->departure_time)->format('H:i') : '' }}
                            </td>
                        @endforeach
                        <td class="col-total">{{ $totalHoursText }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($nightWorkers->count() > 0)
        <div class="shift-title">Équipe de Nuit</div>
        <table>
            <thead>
                <tr>
                    <th class="col-n">N°</th>
                    <th class="col-name">Nom</th>
                    <th class="col-prenom">Prénoms</th>
                    @foreach($days as $day)
                        <th class="col-day">{{ $day->translatedFormat('l') }}</th>
                    @endforeach
                    <th class="col-total">HT</th>
                </tr>
            </thead>
            <tbody>
                @foreach($nightWorkers as $index => $worker)
                    @php
                        $totalMinutesNight = 0;
                        foreach($worker->attendances as $att) {
                            if ($att->status === 'present') {
                                $totalMinutesNight += 240; // 4 hours for night shift by default
                            }
                        }
                        $totalHoursNightText = floor($totalMinutesNight / 60) . 'h' . ($totalMinutesNight % 60 > 0 ? sprintf('%02d', $totalMinutesNight % 60) : '');
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="col-name">{{ $worker->last_name }}</td>
                        <td class="col-prenom">{{ $worker->first_name }}</td>
                        
                        @foreach($days as $day)
                            @php
                                $att = $worker->attendances->where('date', $day->format('Y-m-d'))->where('session', 'morning')->first();
                                $status = $att ? $att->status : 'none';
                            @endphp
                            <td>
                                @if($status == 'present')
                                    <span class="present">✓</span>
                                @elseif($status == 'absent')
                                    <span class="absent">✕</span>
                                @endif
                            </td>
                        @endforeach
                        <td class="col-total">{{ $totalHoursNightText }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        <table class="footer-table">
            <tr>
                <td class="footer-left" style="width: 60%;">
                    <span class="legal-info">BIO FARM TRADING RCCM : TG-LOM 2019 B 1488</span><br>
                    NIF 1001469316 | Tél : (+228) 92 02 01 10
                </td>
                <td class="footer-right" style="width: 40%;">
                    <div class="certif-box">
                        <p style="margin: 0;">Produits bios Certifiés Par</p>
                        <p style="margin: 0;">Ecocert International S.A.S</p>
                        <p style="margin: 0; font-weight: bold;">TG-BIO-154</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>



</body>
</html>
