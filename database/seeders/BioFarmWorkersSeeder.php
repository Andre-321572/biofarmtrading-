<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Worker;

class BioFarmWorkersSeeder extends Seeder
{
    public function run()
    {
        $workers = [
            ['last_name' => 'ABOTSI', 'first_name' => 'Kodjo', 'shift' => 'day'],
            ['last_name' => 'ADEGNO', 'first_name' => 'Kokou Grégoire', 'shift' => 'day'],
            ['last_name' => 'ADETSU', 'first_name' => 'Koffi', 'shift' => 'day'],
            ['last_name' => 'ADIBADE', 'first_name' => 'Bella', 'shift' => 'day'],
            ['last_name' => 'ADISSOU', 'first_name' => 'Jacqueline', 'shift' => 'day'],
            ['last_name' => 'ADJOYI', 'first_name' => 'Olive', 'shift' => 'day'],
            ['last_name' => 'AGBEDIGNI', 'first_name' => 'Yawo Richard', 'shift' => 'day'],
            ['last_name' => 'AGEDZI', 'first_name' => 'Wotsa', 'shift' => 'day'],
            ['last_name' => 'AHIADZEGBE', 'first_name' => 'Komla', 'shift' => 'day'],
            ['last_name' => 'AHOLOU', 'first_name' => 'Emilie', 'shift' => 'day'],
            ['last_name' => 'AJAVON', 'first_name' => 'Makafui', 'shift' => 'day'],
            ['last_name' => 'AKLIKOKOU', 'first_name' => 'Kossi Nicodeme', 'shift' => 'day'],
            ['last_name' => 'AKOE', 'first_name' => 'Afi Sintia', 'shift' => 'day'],
            ['last_name' => 'AKOGO', 'first_name' => 'Komi', 'shift' => 'day'],
            ['last_name' => 'AKOGO', 'first_name' => 'Benjamin', 'shift' => 'day'],
            ['last_name' => 'AKOGO', 'first_name' => 'Blaise', 'shift' => 'day'],
            ['last_name' => 'AKPAKA', 'first_name' => 'Kossiwa Emilie', 'shift' => 'day'],
            ['last_name' => 'AKPAKA', 'first_name' => 'K Anicet', 'shift' => 'day'],
            ['last_name' => 'AKPAKA', 'first_name' => 'Ami Gaelle', 'shift' => 'day'],
            ['last_name' => 'AKPO', 'first_name' => 'Eyavi Fidele', 'shift' => 'day'],
        ];

        foreach ($workers as $worker) {
            Worker::updateOrCreate(
                ['last_name' => $worker['last_name'], 'first_name' => $worker['first_name']],
                $worker
            );
        }
    }
}
