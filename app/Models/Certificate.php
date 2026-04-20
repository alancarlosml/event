<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Certificate extends Model
{
    protected $fillable = [
        'hash',
        'code',
        'event_id',
        'participante_id',
        'order_id',
        'order_item_id',
        'issued_at',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Certificate $certificate) {
            if (empty($certificate->hash)) {
                $certificate->hash = Str::uuid()->toString();
            }
            if (empty($certificate->code)) {
                $certificate->code = strtoupper(Str::random(12));
            }
            if (empty($certificate->issued_at)) {
                $certificate->issued_at = now();
            }
        });
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function participante()
    {
        return $this->belongsTo(Participante::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function getParticipantName()
    {
        if ($this->orderItem) {
            $nameData = $this->orderItem->get_name_participante();

            return $nameData?->answer ?? $this->participante->name;
        }

        return $this->participante->name;
    }

    public function getParticipantDisplayNameAttribute()
    {
        return $this->getParticipantName();
    }
}
