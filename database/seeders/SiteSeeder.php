<?php

namespace Database\Seeders;

use App\Models\Floor;
use App\Models\Room;
use App\Models\Site;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $campus = Site::create(['site_name' => 'HDC-Cikarang Campus 1']);
        $campus_id = $campus->id;
        $floor = [
            '1F',
            'DC-1F',
            'DC. Shaft Selatan',
            'DC. Shaft Utara',
            'Opening Trench',
            'PH Lantai-1',
            'PH Roof Top',
            'PH-1F',
            'PH-2F',
            'PH-RF',
            'PH. Lantai-1',
            'Selasar DC Lt.2',
            'Shaft Selatan',
            'Shaft Utara',
            'TES Room',
            'TES Tank Room',
            'UG',
        ];
        foreach ($floor as $floor) {
            Floor::create(['floor_name' => $floor, 'site_id' => $campus_id]);
        }

        $office = Site::create(['site_name' => 'HDC-Cikarang Office']);
        $office_id = $office->id;
        $floor = [
            '1F',
            '2F',
            '3F',
            '4F',
            'RF',
            'UG',
        ];
        foreach ($floor as $floor) {
            Floor::create(['floor_name' => $floor, 'site_id' => $office_id]);
        }

        Site::create(['site_name' => 'DC Serpong']);

        $office = Site::create(['site_name' => 'DC Sentul']);
        $office_id = $office->id;
        $foor = Floor::create(['floor_name' => 'GF', 'site_id' => $office_id]);
        $foor_id = $foor->id;
        $room = [
            'BRI GF',
            'BTPNS GF',
            'BUKOPIN GF',
            'DATA HALL GF',
            'DC HALL GF',
            'DEUTSCHE BANK GF',
            'EX-CONTROL ROOM',
            'GDN GF',
            'GDN/BLIBLI GF',
            'MCP GF',
            'MMR A GF',
            'MMR B GF',
            'PERTAMINA GF',
            'SHARING B GF',
            'SHARING C GF',
            'SHINHAN GF',
            'UTILITY A1 GF',
            'UTILITY A2 GF',
            'UTILITY B GF',
            'WR COLLEGA GF',
        ];
        foreach ($room as $room) {
            Room::create(['room_name' => $room, 'floor_id' => $foor_id]);
        }

        Site::create(['site_name' => 'DC Surabaya']);
    }
}
