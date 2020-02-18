<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceItems extends Model
{
    public function invoice()
    {
        return $this->belongsTo('App\Invoice');
    }

    protected $fillable = ['name','description','cost','quantity'];

}