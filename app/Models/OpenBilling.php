<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpenBilling extends Model
{
    use HasFactory, SoftDeletes;
    public function branch()
    {
        return $this->belongsTo(Branch::class,'branch_id');
    }
     public function openBillingItems()
    {
        return $this->hasMany(OpenBillingItem::class, 'open_billing_id', 'id');
    }
}
