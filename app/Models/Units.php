<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Units extends Model
{
    use HasFactory;
protected $table = 'units';
     protected $fillable = [
        'units_name',
        'units_shortname',
        'units_fullname',
        'units_code',
        'units_status',
        'year',
    ];
}
