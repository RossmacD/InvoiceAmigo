<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    public function user() {
        return $this->belongsTo('App\User');
    }

    public function invoices()
    {
        return $this->hasMany('App\Invoice');
    }
    public function products()
    {
        return $this->hasMany('App\Product', 'business_id');
    }
    public function services()
    {
        return $this->hasMany('App\Service', 'business_id');
    }
    
}
