<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
    ];

    public function participante()
    {
        return $this->belongsTo(Participante::class, 'participante_id', 'id');
    }

    public function order_items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }
}
