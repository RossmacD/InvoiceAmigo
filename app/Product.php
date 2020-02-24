<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    public function business() {
      return $this->belongsTo('App\Business');
    }
    //Set default values
    protected $attributes = [
        // 'user_id' => 0,
    ];
    protected $fillable = ['name', 'description', 'cost', 'user_id'];
}
