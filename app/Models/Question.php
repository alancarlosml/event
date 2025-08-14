<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'order',
        'required',
        'unique',
        'status',
        'option_id',
        'event_id',
    ];

    public function option()
    {
        return $this->belongsTo(Option::class);
    }

    public function value()
    {
        $option_value = OptionValue::join('questions', 'questions.id', '=', 'option_values.question_id')
            ->where('option_values.question_id', $this->id)
            ->select('option_values.id', 'option_values.value')
            ->get();

        return $option_value;
    }

    // app/Models/Question.php (exemplo)
    public function getFormattedOptionsAttribute()
    {
        $parts = [
            $this->question,
            "(Tipo: {$this->option->option})"
        ];

        if ($this->value()->count() > 0) {
            $values = $this->value()->pluck('value')->implode(', ');
            $parts[] = "[Opções: {$values}]";
        }

        if ($this->required) {
            $parts[] = 'Obrigatório';
        }

        if ($this->unique) {
            $parts[] = 'Único';
        }

        return implode('; ', $parts);
    }
}
