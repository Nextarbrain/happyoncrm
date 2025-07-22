<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory;

    public function isValid()
    {
        $now = Carbon::now();

        return $this->start_from <= $now && $this->valid_till >= $now;
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class,'branch_id');
    }
}
