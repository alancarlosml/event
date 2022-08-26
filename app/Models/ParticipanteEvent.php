<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipanteEvent extends Model
{
    use HasFactory;

    protected $table = 'participantes_events';

    protected $fillable = [
        'status'
    ];
}
