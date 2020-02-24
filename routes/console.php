<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('ia:regen',function(){
    $this->call('migrate:refresh');
    $this->call('passport:client', ['--personal'=>true]);
    $this->info("Invoice Amigo successfully regenerated");
})->describe('Regenerate InvoiceAmigo DB with personal access key');
