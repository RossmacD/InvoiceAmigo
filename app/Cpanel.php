<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cpanel extends Model
{
    protected $table = 'cpanel';

    public function business() {
        return $this->belongsTo('App\Business');
    }


}
