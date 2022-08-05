<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipanteLote extends Model
{
    use HasFactory;

    protected $table = 'participantes_lotes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hash',
        'number',
        'status'
    ];

    public function participante()
    {
      return $this->belongsTo(Participante::class);
    }

    public function lote()
    {
        return $this->hasMany(Lote::class);
    }
}
