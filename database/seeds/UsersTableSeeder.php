<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $role_user = Role::where('name', 'user')->first();
        $role_business = Role::where('name', 'business')->first();

        $user = new User();
        $user->name = 'John Smyth';
        // $user->address = 'Main Street';
        // $user->phone = '0' . $this->random_str(2, '0123456789') . '-' . $this->random_str(7, '0123456789');
        $user->email = 'john@smyth.com';
        $user->password = bcrypt('secret');
        $user->save();
        $user->roles()->attach($role_user);

        $business = new User();
        $business->name = 'Ultan O Nuallain';
        $business->email = 'ultan.on98@gmail.com';
        $business->password = bcrypt('secret');
        $business->save();
        $business->roles()->attach($role_business);

    }
}
