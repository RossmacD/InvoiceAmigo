<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function invoiceItems()
    {
        return $this->hasMany('App\InvoiceItems');
    }

    public function business(){
        return $this->belongsTo('App\Business');
    }

    //Set default values
    protected $attributes = [   
        'client_id' => 0,
        'status' =>'unseen',
        'currency' => 'eur',
        'total_cost'=>'0'
     ];
}