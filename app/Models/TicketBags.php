<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketBags extends Model
{
    use HasFactory;
     protected $table = 'ticket_bags';
      protected $fillable = [
            'unitid',
            'seller',
            'eventid',
            'ticketid',
            'ticket_type',
            'bagid',
            'price',
            'bind',
            'start',
            'end',
            'count',
            'quantity',
            'status',
            'date',
    ];
}
