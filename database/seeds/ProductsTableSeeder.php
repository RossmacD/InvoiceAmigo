<?php

use Illuminate\Database\Seeder;
use App\Product;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = new Product();
        $product->name = 'Single Page Website';
        $product->description = 'A basic single page website template';
        $product->cost = '25';
        $product->business_id = '1';
        $product->save();

        $product = new Product();
        $product->name = 'Three Page Website';
        $product->description = 'A basic three page website template';
        $product->cost = '50';
        $product->business_id = '1';
        $product->save();

        $product = new Product();
        $product->name = 'Five Page Website';
        $product->description = 'A basic five page website template';
        $product->cost = '80';
        $product->business_id = '1';
        $product->save();

    }
}
