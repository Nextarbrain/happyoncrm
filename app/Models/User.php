<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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

    public function membership()
    {
        return $this->belongsTo(MembershipBuy::class, 'id', 'user_id');
    }
    public function membershipHistory()
    {
        return $this->belongsTo(MembershipHistory::class, 'id', 'user_id');
    }

    public function kid()
    {
        return $this->belongsTo(Kid::class, 'id', 'user_id');
    }
    public function kids()
    {
        return $this->hasMany(Kid::class, 'user_id', 'id');
    }

    public function firstKid()
    {
        // return $this->hasOne(Kid::class, 'id', 'user_id')->oldestOfMany();
        return $this->hasOne(Kid::class, 'user_id', 'id')->oldestOfMany();
    }

    public function userDetails()
    {
        return $this->belongsTo(UserDetail::class, 'id', 'user_id');
    }
    public function event()
    {
        return $this->belongsTo(Event::class, 'id', 'user_id');
    }
    public function paymentInfo()
    {
        return $this->belongsTo(PaymentMethod::class, 'id', 'user_id');
    }

    public function paymentInfos()
    {
        return $this->hasMany(PaymentMethod::class, 'user_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    
}
