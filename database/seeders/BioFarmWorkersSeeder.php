<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Worker;

class BioFarmWorkersSeeder extends Seeder
{
    public function run()
    {
        $dayWorkers = [
            ["last_name" => "ABOTSI", "first_name" => "Kodjo", "shift" => "day"],
            ["last_name" => "ADENYO", "first_name" => "Yao Justin", "shift" => "day"],
            ["last_name" => "ADETSU", "first_name" => "Koffi", "shift" => "day"],
            ["last_name" => "ADISSOU", "first_name" => "Jacqueline", "shift" => "day"],
            ["last_name" => "ADJOYI", "first_name" => "Olive", "shift" => "day"],
            ["last_name" => "AGBETY", "first_name" => "Adzo Audrey", "shift" => "day"],
            ["last_name" => "AGEDZI", "first_name" => "Wotsa", "shift" => "day"],
            ["last_name" => "AHIAVOR", "first_name" => "Komi Amen", "shift" => "day"],
            ["last_name" => "AHOLOU", "first_name" => "Emilie", "shift" => "day"],
            ["last_name" => "AKOE", "first_name" => "Afi Sintia", "shift" => "day"],
            ["last_name" => "AKOGO", "first_name" => "Benjamin", "shift" => "day"],
            ["last_name" => "AKOGO", "first_name" => "Blaise", "shift" => "day"],
            ["last_name" => "AKOHIN", "first_name" => "Yao Victorien", "shift" => "day"],
            ["last_name" => "AKOTO", "first_name" => "Aku", "shift" => "day"],
            ["last_name" => "AKPAKA", "first_name" => "K Anicet", "shift" => "day"],
            ["last_name" => "AKPO", "first_name" => "Eyavi Fidele", "shift" => "day"],
            ["last_name" => "ALABA", "first_name" => "Mawulawoè", "shift" => "day"],
            ["last_name" => "ALKISSAN", "first_name" => "E.Bernard", "shift" => "day"],
            ["last_name" => "AMEGBLETO", "first_name" => "Mabelle", "shift" => "day"],
            ["last_name" => "AMEKO", "first_name" => "Abla Esther", "shift" => "day"],
            ["last_name" => "ATCHIN", "first_name" => "Yao Ferdinand", "shift" => "day"],
            ["last_name" => "ATIGAN", "first_name" => "K.Charles Sélom", "shift" => "day"],
            ["last_name" => "ATIVON", "first_name" => "fidèle", "shift" => "day"],
            ["last_name" => "ATSOUKPATI", "first_name" => "Emmanuel", "shift" => "day"],
            ["last_name" => "AVANYO", "first_name" => "Adzo Rachel", "shift" => "day"],
            ["last_name" => "AWANYO", "first_name" => "Atsupui", "shift" => "day"],
            ["last_name" => "AWUNOR", "first_name" => "Koffi", "shift" => "day"],
            ["last_name" => "AZIAMAYOR", "first_name" => "Kossi Marcel", "shift" => "day"],
            ["last_name" => "BAHAMING", "first_name" => "Ebebalaki Popo", "shift" => "day"],
            ["last_name" => "BAKA", "first_name" => "Julienne", "shift" => "day"],
            ["last_name" => "BEDI", "first_name" => "Amivi", "shift" => "day"],
            ["last_name" => "BEDJE", "first_name" => "Yao Daniel", "shift" => "day"],
            ["last_name" => "BOSSOU", "first_name" => "Adjo", "shift" => "day"],
            ["last_name" => "DABLA", "first_name" => "Komi. Ferdinand", "shift" => "day"],
            ["last_name" => "DJAKOU", "first_name" => "Yawa", "shift" => "day"],
            ["last_name" => "DOGBLA", "first_name" => "Essi Délali", "shift" => "day"],
            ["last_name" => "DOGBLA", "first_name" => "Komi Salomon", "shift" => "day"],
            ["last_name" => "DOVLOE", "first_name" => "K.Martin", "shift" => "day"],
            ["last_name" => "EKPE", "first_name" => "Abla Jeanne", "shift" => "day"],
            ["last_name" => "GALLEY", "first_name" => "Ama", "shift" => "day"],
            ["last_name" => "GAMADO", "first_name" => "Noélie", "shift" => "day"],
            ["last_name" => "GAVON", "first_name" => "Afi", "shift" => "day"],
            ["last_name" => "GOHOUN", "first_name" => "jean claude", "shift" => "day"],
            ["last_name" => "HOTO", "first_name" => "Kodzo Yona", "shift" => "day"],
            ["last_name" => "KATCHAKA", "first_name" => "Afiwa", "shift" => "day"],
            ["last_name" => "KITABLAME", "first_name" => "Ame Claire", "shift" => "day"],
            ["last_name" => "KOGNO", "first_name" => "Kokouvi Jean", "shift" => "day"],
            ["last_name" => "KOUDESSE", "first_name" => "Amivi", "shift" => "day"],
            ["last_name" => "KOUMA", "first_name" => "Ami Ahoefa", "shift" => "day"],
            ["last_name" => "KPOGLI", "first_name" => "Rosaline", "shift" => "day"],
            ["last_name" => "LAMBONI", "first_name" => "Rosaline", "shift" => "day"],
            ["last_name" => "LANYO", "first_name" => "K.Germain", "shift" => "day"],
            ["last_name" => "MAWUSSI", "first_name" => "Isidor", "shift" => "day"],
            ["last_name" => "MISSIHOUN", "first_name" => "Ablam Vincent", "shift" => "day"],
            ["last_name" => "SAVI", "first_name" => "Ama Rébécca", "shift" => "day"],
            ["last_name" => "SEBIENOU", "first_name" => "Bénédicte", "shift" => "day"],
            ["last_name" => "SEMABIA", "first_name" => "Abla", "shift" => "day"],
            ["last_name" => "SEMEKONAWO", "first_name" => "Amitoa-Bubune", "shift" => "day"],
            ["last_name" => "SIKA", "first_name" => "K. Samuel", "shift" => "day"],
            ["last_name" => "SIMLIWA", "first_name" => "Tcha", "shift" => "day"],
            ["last_name" => "TCHA-YAO", "first_name" => "Raissa", "shift" => "day"],
            ["last_name" => "TCHANGAI", "first_name" => "Ami Merveille", "shift" => "day"],
            ["last_name" => "TCHAODA", "first_name" => "Abla Marie", "shift" => "day"],
            ["last_name" => "TCHENYOWU", "first_name" => "Komla Mawunyo", "shift" => "day"],
            ["last_name" => "TEGA", "first_name" => "Kokouvi Adelphe", "shift" => "day"],
            ["last_name" => "TENGUE", "first_name" => "Hubertine", "shift" => "day"],
            ["last_name" => "TODJAGBO", "first_name" => "Akouvi", "shift" => "day"],
            ["last_name" => "TODJAGBO", "first_name" => "Yawavi Noélie", "shift" => "day"],
            ["last_name" => "TONUI", "first_name" => "Adjo", "shift" => "day"],
            ["last_name" => "TOVE", "first_name" => "Koffi Mawuli", "shift" => "day"],
            ["last_name" => "ZIKPUI", "first_name" => "Ama", "shift" => "day"]
        ];

        $nightWorkers = [
            ["last_name" => "AGBOBA", "first_name" => "KODJO OLIVIER", "shift" => "night"],
            ["last_name" => "AKLISHIE", "first_name" => "ELIZABETH", "shift" => "night"],
            ["last_name" => "AWANYOH", "first_name" => "REBECCA", "shift" => "night"],
            ["last_name" => "AZIAKOU", "first_name" => "HERVE", "shift" => "night"],
            ["last_name" => "BADASSOU", "first_name" => "Komi Bruce", "shift" => "night"],
            ["last_name" => "BOUAME", "first_name" => "HERVE", "shift" => "night"],
            ["last_name" => "DJATO", "first_name" => "Nadège", "shift" => "night"],
            ["last_name" => "DOGBATSE", "first_name" => "KENNETH", "shift" => "night"],
            ["last_name" => "DOGBEDZIE", "first_name" => "Joel", "shift" => "night"],
            ["last_name" => "DOGBLE", "first_name" => "Abla", "shift" => "night"],
            ["last_name" => "EDZE", "first_name" => "Akou", "shift" => "night"],
            ["last_name" => "EZAO", "first_name" => "Akouvi", "shift" => "night"],
            ["last_name" => "HOTOR", "first_name" => "Kodzo", "shift" => "night"],
            ["last_name" => "KINIZIBA", "first_name" => "Essodjolo", "shift" => "night"],
            ["last_name" => "NYAMANYO", "first_name" => "Laurent", "shift" => "night"],
            ["last_name" => "TANDJA", "first_name" => "Komla", "shift" => "night"],
            ["last_name" => "YAKA", "first_name" => "K.Paul", "shift" => "night"]
        ];

        $keptIds = [];

        foreach (array_merge($dayWorkers, $nightWorkers) as $workerData) {
            $lastName = trim(mb_strtoupper($workerData['last_name']));
            $firstName = trim($workerData['first_name']);

            // Search case-insensitively to prevent any casing duplication
            $worker = Worker::whereRaw('LOWER(TRIM(last_name)) = ?', [mb_strtolower($lastName)])
                ->whereRaw('LOWER(TRIM(first_name)) = ?', [mb_strtolower($firstName)])
                ->first();

            if ($worker) {
                $worker->update([
                    'last_name' => $lastName,
                    'first_name' => $firstName,
                    'shift' => $workerData['shift'],
                ]);
            } else {
                $worker = Worker::create([
                    'last_name' => $lastName,
                    'first_name' => $firstName,
                    'shift' => $workerData['shift'],
                ]);
            }

            $keptIds[] = $worker->id;
        }

        // Purge any extra, duplicate, or stale worker records not in the official list
        Worker::whereNotIn('id', $keptIds)->delete();
    }
}
