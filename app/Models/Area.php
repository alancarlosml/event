<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'status'
    ];

    public function category()
    {
      return $this->belongsTo(Category::class);
    }
    
}
