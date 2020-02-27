<?php

use Illuminate\Database\Seeder;
use App\Service;

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $service = new Service();
        $service->name = 'Web Design';
        $service->description = 'Frontend website design';
        $service->cost = '20';
        $service->rate_unit = 'hour';
        $service->business_id = '1';
        $service->save();

        $service = new Service();
        $service->name = 'Web Development';
        $service->description = 'Backend website development';
        $service->cost = '180';
        $service->rate_unit = 'day';
        $service->business_id = '1';
        $service->save();
    }
}
