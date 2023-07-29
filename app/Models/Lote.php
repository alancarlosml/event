<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    use HasFactory;

    protected $fillable = [
        'hash',
        'name',
        'description',
        'value',
        'tax',
        'final_value',
        'tax_parcelamento',
        'tax_service',
        'form_pagamento',
        'type',
        'quantity',
        'visibility',
        'limit_min',
        'limit_max',
        'datetime_begin',
        'datetime_end',
        'order',
        'event_id',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function coupons()
    {
        return $this->belongsToMany(
            Coupon::class,
            'coupons_lotes',
            'lote_id',
            'coupon_id'
        );
    }

    // public function participantes()
    // {
    //     return $this->belongsToMany(
    //         Participante::class,
    //         'participantes_lotes',
    //         'lote_id',
    //         'participante_id');
    // }
}
