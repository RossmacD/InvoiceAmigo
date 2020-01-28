<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    //Set default values
    protected $attributes = [
        'client_id' => 0,
        'status' =>'unseen',
        'currency' => 'eur',
     ];
}