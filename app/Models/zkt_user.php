<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class zkt_user extends Model
{
    use HasFactory;

    protected $fillable = [
        'User_Id',
        'Name',
        'Card_No',
    ];

    public function attendence()
    {
        return $this->hasOne(zkt_attendence::class, 'user_id');
    }
}
