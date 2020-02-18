<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use Searchable;
    //Set default values
    protected $attributes = [
        // 'user_id' => 0,
    ];
    protected $fillable = ['name', 'description', 'cost', 'user_id'];
}
