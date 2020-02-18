<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Product;
use App\Service;
use App\Invoice;


class User extends Authenticatable
{
    use HasApiTokens, Notifiable;


    public function invoices()
    {
        // $incoming = $this->incomingInvoices();
        // $outgoing = $this->outgoingInvoices();
        // return $outgoing->union($incoming);
        return $this->hasMany('App\invoice','user_id') ;
        //return $this->hasMany('App\invoice', 'user_id')->where();
    }

    // public function incomingInvoices()
    // {
    //     return $this->hasMany('App\Invoice', 'client_id');
    // }

    public function outgoingInvoices()
    {
        return $this->hasMany('App\Invoice', 'user_id');
    }

    public function products()
    {
        return $this->hasMany('App\Product', 'user_id');
    }
    public function services()
    {
        return $this->hasMany('App\Service', 'user_id');
    }

    public function business() {
        return $this->hasOne('App\Business');
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
}
