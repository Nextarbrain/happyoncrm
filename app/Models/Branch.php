<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    protected $table = 'branch';
    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'user_id',
        'status',
        'created_at',
        'updated_at'
    ];

    public function admin()
    {
        return $this->hasOne(Admin::class);
    }
}
