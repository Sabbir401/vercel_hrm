<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class child extends Model
{
    use HasFactory;
    protected $fillable = [
        'EID',
        'Child_Name',
        'NID',
        'Email',
        'Contact_No',
        'DOB',
    ];

    public function employee()
    {
        return $this->belongsTo(employee::class, 'EID');
    }
}
