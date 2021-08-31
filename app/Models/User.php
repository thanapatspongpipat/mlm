<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'prefix_name',
        'fullname',
        'username',
        'firstname',
        'lastname',
        'on_card',
        'dob',
        'email',
        'phone_number',
        'line',
        'fb',
        'address',
        'zip_code',
        'user_invite_id',
        'user_upline_id',
        'bank_id',
        'bank_no',
        'bank_own_name',
        'level',
        'avatar',
        'position_space',
        'thai_id',
        'birth_date',
        'nationality',
        'sex',
        'ig',
        'country',
        'region',
        'province',
        'district',
        'sub_district',
        'send_address',
        'send_region',
        'send_province',
        'send_sub_district',
        'send_district',
        'send_zip_code',
        'send_email',
        'send_phone_number',
        'send_zip_code',
        'first_time_login',
        'password',
        'product_id',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function childrenUpline(){
        return $this->hasMany(User::class, 'user_upline_id', 'id')
        ->orderBy('position_space','ASC')
        ->with('childrenUpline');
    }

    public function getChlidrenAttribute(){
        return $this->hasMany(User::class, 'user_upline_id', 'id')
        ->orderBy('position_space','ASC')
        ->with('childrenUpline');
    }





}
