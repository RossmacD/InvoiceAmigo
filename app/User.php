<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    public function invoices(){
        $incoming= $this->incomingInvoices();
        $outgoing=$this->outgoingInvoices();
        return $outgoing->union($incoming);
        //return $this->hasMany('App\invoice','user_id') ;
        //return $this->hasMany('App\invoice', 'user_id')->where();
    }

    public function incomingInvoices(){
        return $this->hasMany('App\invoice','client_id');
    }

    public function outgoingInvoices()
    {
        return $this->hasMany('App\invoice', 'user_id');
    }

    public function products(){
        return $this->hasMany('App\product','user_id');
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    // protected $attributes = [
    //     'currency' => 'eur',
    // ];
}
