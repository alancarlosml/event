<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipanteEvent extends Model
{
    use HasFactory;

    protected $table = 'participantes_events';

    protected $fillable = [
        'hash',
        'status',
        'participante_id',
        'event_id',
        'role',
    ];

    public function participante()
    {
        return $this->belongsTo(Participante::class, 'participante_id', 'id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }
}
