<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Category extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'description',
        'slug',
        'status'
    ];

    public function areas()
    {
        return $this->hasMany(Area::class);
    }

    public function getAll(){
        
        $categories = Category::where('categories.status', 1)
                        ->orderBy('categories.created_at','ASC')
                        ->get();

        return $categories;
    }
    
}
