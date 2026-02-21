<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'hash',
        'status',
        'gatway_hash',
        'gatway_reference',
        'gatway_status',
        'gatway_payment_method',
        'gatway_description',
        'gatway_date_status',
        'event_id',
        'event_date_id',
        'participante_id',
        'coupon_id',
    ];

    const STATUS_PENDING = 2;
    const STATUS_CONFIRMED = 1;
    const STATUS_CANCELLED = 4;
    const STATUS_REFUNDED = 5;

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }

    public function participante()
    {
        return $this->belongsTo(Participante::class, 'participante_id', 'id');
    }

    public function order_items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id', 'id');
    }

    public function eventDate()
    {
        return $this->belongsTo(EventDate::class, 'event_date_id', 'id');
    }

    public function get_participante()
    {
        return Participante::find($this->participante_id);
    }

    public function getTotalAttribute()
    {
        return $this->order_items()->sum('value');
    }

    public function getTotalQuantityAttribute()
    {
        return $this->order_items()->sum('quantity');
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isConfirmed()
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isRefunded()
    {
        return $this->status === self::STATUS_REFUNDED;
    }

    public function canBeCancelled()
    {
        return in_array($this->status, [self::STATUS_PENDING]);
    }

    public function canBeRefunded()
    {
        if ($this->status !== self::STATUS_CONFIRMED) {
            return false;
        }

        if ($this->created_at->diffInDays(now()) > 180) {
            return false;
        }

        return true;
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            1 => 'Confirmado',
            2 => 'Pendente',
            3 => 'Rejeitado',
            4 => 'Cancelado',
            5 => 'Reembolsado',
            6 => 'Contestado',
        ];

        return $labels[$this->status] ?? 'Desconhecido';
    }

    public function getStatusClassAttribute()
    {
        $classes = [
            1 => 'success',
            2 => 'warning',
            3 => 'danger',
            4 => 'secondary',
            5 => 'info',
            6 => 'dark',
        ];

        return $classes[$this->status] ?? 'secondary';
    }

    public function getPaymentMethodLabelAttribute()
    {
        $methods = [
            'credit_card' => 'Cartão de Crédito',
            'debit_card' => 'Cartão de Débito',
            'pix' => 'PIX',
            'ticket' => 'Boleto',
            'bolbradesco' => 'Boleto Bradesco',
            'bank_transfer' => 'Transferência Bancária',
            'free' => 'Gratuito',
        ];

        return $methods[$this->gatway_payment_method] ?? $this->gatway_payment_method ?? 'N/A';
    }

    public function getOrganizer()
    {
        return DB::table('participantes_events')
            ->where('event_id', $this->event_id)
            ->where('role', 'admin')
            ->first();
    }

    public function getMpAccount()
    {
        $organizer = $this->getOrganizer();
        
        if (!$organizer) {
            return null;
        }

        return MpAccount::where('participante_id', $organizer->participante_id)->first();
    }
}
