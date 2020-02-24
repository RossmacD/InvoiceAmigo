<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public function service() {
      return $this->belongsTo('App\Business');
    }
}
