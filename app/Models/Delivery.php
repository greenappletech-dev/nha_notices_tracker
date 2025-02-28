<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'notice_type_id',
        'district_id',
        'project_id',
        'beneficiary_id',
        'photo',
        'date_captured',
        'user_id',
    ];
}

