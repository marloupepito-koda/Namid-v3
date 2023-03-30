<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventBags extends Model
{
    use HasFactory;
     protected $table = 'event_bags';
      protected $fillable = [
          'unitid',
          'eventid',
        'bag_name',
        'seller',
        'location',
        'remaining',
        'status',
        'returned',
        'date'
    ];
}
