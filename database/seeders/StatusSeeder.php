<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = [
            ['Waiting Approval','warning'],
            ['AM Coordination','warning'],
            ['Approved','success'],
            ['On Progress','warning'],
            ['Completed','success'],
            ['Rejected','danger'],
            ['Closed','success'],
            ['Check power','warning'],
            ['Available','success'],
        ];

        foreach ($status as $status) {
            Status::create(['status_name' => $status[0],'badge'=> $status[1]]);
        }
    }
}
