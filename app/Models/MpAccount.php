<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MpAccount extends Model
{
    use HasFactory;

    protected $table = 'mp_accounts';

    protected $fillable = [
        'participante_id',
        'access_token',
        'public_key',
        'refresh_token',
        'expires_in',
        'mp_user_id'
    ];

    public function participante()
    {
        return $this->belongsTo(Participante::class);
    }
}