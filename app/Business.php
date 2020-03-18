<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Invoice;


class Business extends Model
{
    public function user() {
        return $this->belongsTo('App\User');
    }

    public function outgoingInvoices()
    {
        return $this->hasMany('App\Invoice','business_id');
    }

    public function products()
    {
        return $this->hasMany('App\Product', 'business_id');
    }
    public function services()
    {
        return $this->hasMany('App\Service', 'business_id');
    }

    public function cpanel() {
        return $this->hasOne('App\Cpanel');
    }

    public function invoicedUsers() {
        return $this->hasMany('App\InvoicedUsers');
    }

}
