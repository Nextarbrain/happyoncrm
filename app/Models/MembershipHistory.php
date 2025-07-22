<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class MembershipHistory extends Model
{
    use HasFactory, SoftDeletes;
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kid()
    {
        return $this->belongsTo(Kid::class, 'user_id', 'user_id');
    }

    public function membershipBuy()
    {
        return $this->belongsTo(MembershipBuy::class, 'user_id', 'user_id');
    }

    public function paymentInfo()
    {
        return $this->belongsTo(PaymentMethod::class, 'user_id', 'user_id');
    }

    public function userDetails()
    {
        return $this->belongsTo(UserDetail::class, 'user_id','user_id');
    }

    public function singleUser()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class,'branch_id');
    }
}
