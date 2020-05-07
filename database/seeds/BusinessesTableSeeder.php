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
        Stripe\Stripe::setApiKey('sk_test_6GCDXqiEEOn52aO7XcYEX7Bk00lJswlGE1');
        $account = Stripe\Account::create([
            'country' => 'IE',
            'type' => 'custom',
            'requested_capabilities' => ['card_payments', 'transfers'],
            'email'=>'ultan.on98@gmail.com',
            'business_profile' => [
                'mcc' => '5045',
              ],
        ]);
        Stripe\Account::update(
            $account->id,
            [
              'tos_acceptance' => [
                'date' => time(),
                'ip' => '79.97.211.163', 
              ],
              'business_type'=> 'individual',
              'business_profile' => [
                    'url' => $business->website
              ],
              'individual'=>[
                'first_name'=>'Ultan',
                'last_name'=>'O Nuaillain',
                'email'=>'ultan.on98@gmail.com',
                'phone'=>'+353851795523',
                'dob'=>[
                    'day'=>'01',
                    'month'=>'01',
                    'year'=>'1901',
                ],
                'address'=>[
                    'line1'=>'address_full_match',
                    'line2'=>'',
                    'postal_code'=>$business->postcode,
                    'state'=>'Co. Wicklow',
                    'city'=>'Greystones',
                    'country'=>'IE'
                ],
                ]
            ]);
       
        
        $business->stripe_id=$account->id;
        $business->save();
    }
}
