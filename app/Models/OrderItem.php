<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function lote()
    {
        return $this->belongsTo(Lote::class);
    }

    public function answers()
    {
        return $this->hasMany(OptionAnswer::class);
    }

    public function get_name_participante()
    {
        return OrderItem::join('option_answers', 'order_items.id', '=', 'option_answers.order_item_id')
            ->join('questions', 'option_answers.question_id', 'questions.id')
            ->where('questions.question', 'Nome')
            ->where('order_items.id', $this->id)
            ->select('option_answers.answer')
            ->first();

    }

    public function get_email_participante()
    {
        return OrderItem::join('option_answers', 'order_items.id', '=', 'option_answers.order_item_id')
            ->join('questions', 'option_answers.question_id', 'questions.id')
            ->where('questions.question', 'E-mail')
            ->where('order_items.id', $this->id)
            ->select('option_answers.answer')
            ->first();

    }
}
