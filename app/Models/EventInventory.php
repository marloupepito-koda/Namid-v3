<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventInventory extends Model
{
    use HasFactory;
    protected $table = 'event_inventory';
    protected $fillable = [
        'unitid',
        'eventid',
        'ticketid',
        'ticket_type',
        'ticket_name',
        'price',
        'count',
        'bind',
        'quantity',
        'start',
        'end',
        'remaining',
        'date',
        'status',
        'numbered',
        'iatp',
        'tt',
        'pop',
    ];
}
