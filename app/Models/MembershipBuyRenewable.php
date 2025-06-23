<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipBuyRenewable extends Model
{
    use HasFactory;
    protected $table = 'membership_buy_renewable';

    protected $fillable = [
        'amount',
        'branch_id',
        'user_id',
        'payment_method',
        'created_at',
    ];

}
