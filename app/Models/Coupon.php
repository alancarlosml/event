<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'hash',
        'code',
        'discount_type',
        'discount_value',
        'limit_buy',
        'limit_tickets',
        'status',
        'event_id',
    ];

    public function lotes()
    {
        return $this->belongsToMany(
            Lote::class,
            'coupons_lotes',
            'coupon_id',
            'lote_id'
        );
    }
}
