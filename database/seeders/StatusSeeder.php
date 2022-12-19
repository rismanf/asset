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
            'Waiting Approval',
            'AM Coordination',
            'Approved',
            'On Progress',
            'Completed',
            'Rejected',
            'Closed',
        ];

        foreach ($status as $status) {
            Status::create(['status_name' => $status]);
        }
    }
}
