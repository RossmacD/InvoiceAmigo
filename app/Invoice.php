<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function business(){
        return $this->belongsTo('App\Business');
    }

    public function invoiceItems()
    {
        return $this->hasMany('App\InvoiceItems');
    }

    //Set default values
    protected $attributes = [
        'client_id' => 0,
        'business_id' => 0,
        'status' =>'unseen',
        'currency' => 'eur',
        'total_cost'=>'0'
     ];
}
