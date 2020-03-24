<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoicedUsers extends Model
{
    public function business(){
        return $this->belongsTo('App\Business');
    }
}
