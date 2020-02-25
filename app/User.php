<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Product;
use App\Service;
use App\Invoice;
use App\Business;


class User extends Authenticatable
{
    use HasApiTokens, Notifiable;


    public function invoices()
    {
        // $incoming = $this->incomingInvoices();
        // $outgoing = $this->outgoingInvoices();
        // return $outgoing->union($incoming);
        return $this->hasMany('App\Invoice','user_id') ;
        //return $this->hasMany('App\invoice', 'user_id')->where();
    }

    // public function incomingInvoices()
    // {
    //     return $this->hasMany('App\Invoice', 'client_id');
    // }

    // public function outgoingInvoices()
    // {
    //     return $this->hasMany('App\Invoice', 'business_id');
    // }

    // public function products()
    // {
    //     return $this->hasMany('App\Product', 'user_id');
    // }
    // public function services()
    // {
    //     return $this->hasMany('App\Service', 'user_id');
    // }

    public function business() {
        return $this->hasOne('App\Business');
    }

    public function roles(){
      return $this->belongsToMany('App\Role', 'user_roles');
    }

    public function hasRole($role){
      return null !== $this->roles()->where('name', $role)->first();
    }

    public function hasAnyRole($roles){
      return null !== $this->roles()->whereIn('name', $roles)->first();
    }

    public function authoriseRoles($roles){
      if(is_array($roles)){
        return $this->hasAnyRoles($roles) || abort(401, 'this action is unauthorised');
      }
        return $this->hasRole($roles) || abort(401, 'this action is unauthorised');
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
