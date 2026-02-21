<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    public function places()
    {
        return $this->hasMany(Place::class);
    }

    /** Coluna "uf" em cities é o ID do estado (FK). Use state->uf para a sigla (ex: MA, SP). */
    public function state()
    {
        return $this->belongsTo(State::class, 'uf', 'id');
    }
}
