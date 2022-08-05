<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventDate extends Model
{
    use HasFactory;

    public function event()
    {
      return $this->belongsTo(Event::class);
    }

    public function event_times()
    {
      return $this->hasMany(EventTime::class);
    }
}
