<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventDate extends Model
{
    use HasFactory;

    protected $fillable = [
      'date',
      'time_begin',
      'time_end',
      'status',
      'event_id'
    ];

    public function event()
    {
      return $this->belongsTo(Event::class);
    }
}
