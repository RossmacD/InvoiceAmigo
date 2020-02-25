<?php

use Illuminate\Database\Seeder;
use App\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_user = new Role();
        $role_user->name = 'user';
        $role_user->description = 'A user/client';
        $role_user->save();

        $role_business = new Role();
        $role_business->name = 'business';
        $role_business->description = 'A business user';
        $role_business->save();

    }
}
