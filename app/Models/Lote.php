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

    /**
     * Calcula a taxa da plataforma para um valor de ingresso.
     * Centraliza o cálculo para evitar divergências entre fluxos.
     *
     * @param float $value Valor sobre o qual calcular a taxa
     * @return float
     */
    public function calculateTax(float $value): float
    {
        $config = Configuration::first();
        $taxRate = ($this->event && $this->event->config_tax != 0.0)
            ? $this->event->config_tax
            : ($config->tax ?? 0);

        // Se tax_service = 1, taxa é sobre o valor do item; senão, sobre o valor do lote
        $base = $this->tax_service ? $value : $this->value;

        return round($base * $taxRate, 2);
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
