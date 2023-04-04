<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitInventory extends Model
{
    use HasFactory;
    protected $table = 'unit_inventory';
     protected $fillable = [
        'unitid',
        'ticket_type',
        'ticket_name',
        'count',
        'bind',
        'quantity',
        'start',
        'end',
        'date',
        'status'
    ];
}
