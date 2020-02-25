<?php

use Illuminate\Database\Seeder;
use App\Business;

class BusinessesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $business = new Business();
        $business->business_name = 'Ultan Enterprises';
        $business->website = 'ultan.ie';
        $business->address = 'Wicklow';
        $business->country = 'Ireland';
        $business->postcode = 'A12 B345';
        $business->user_id = 2;
        // $business->email = 'info@ultan.ie';
        $business->save();
    }
}
