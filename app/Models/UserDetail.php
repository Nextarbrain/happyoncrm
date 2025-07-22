<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;
    public function scopeUpcomingBirthdays($query)
    {
        return $query->whereRaw('DATE_FORMAT(dob, "%m-%d") >= DATE_FORMAT(CURRENT_DATE, "%m-%d")')
            ->orderByRaw('DATE_FORMAT(dob, "%m-%d")');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
