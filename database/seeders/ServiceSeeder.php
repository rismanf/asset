<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Visit_activity;
use App\Models\Visit_role;
use App\Models\Cat_identy;
use App\Models\Rack_power_default;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $service = [
            'Visit',
            'Access Card',
            'Rack',
            'Goods In',
            'Goods Out',
            'Electricity',
            'Additional Port',
            'Crossconnect',
            'Crossconnect Terminated',
            'Smarthand',
            'Key Management',
            'Technical Assistance',
        ];

        foreach ($service as $service) {
            Service::create(['service_name' => $service]);
        }


        $Visit_activity = [
            'MAINTENANCE',
            'VISIT OPPORTUNITY',
            'INSTALLMENT',
            'LABELLING',
            'PROJECT',
            'ASSESSMENT',
            'AUDITING',
        ];

        foreach ($Visit_activity as $Visit_activity) {
            Visit_activity::create(['visit_activity_name' => $Visit_activity]);
        }

        $Visit_role = [
            'Employe',
            'Vendor',
        ];

        foreach ($Visit_role as $Visit_role) {
            Visit_role::create(['visit_roles_name' => $Visit_role]);
        }

        $cat_identy = [
            'KTP',
            'SIM',
            'KITAS',
            'PASPORT',
        ];

        foreach ($cat_identy as $cat_identy) {
            Cat_identy::create(['cat_identy_name' => $cat_identy]);
        }

        $rack_default = [
            '2',
            '3.5',
            '5',
            '7',
        ];

        foreach ($rack_default as $rack_default) {
            Rack_power_default::create(['power_default' => $rack_default]);
        }
    }
}
