<?php

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
      'event_id'
    ];

    public function option()
    {
      return $this->belongsTo(Option::class);
    }

    public function value()
    {
        return OptionValue::join('questions', 'questions.id', '=', 'option_values.question_id')
                ->where('option_values.question_id', $this->id)
                ->selectRaw('option_values.value')
                ->first();
    }
}
