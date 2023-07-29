<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTime extends Model
{
    use HasFactory;

    public function event_date()
    {
        return $this->belongsTo(EventDate::class);
    }
}
