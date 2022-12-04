<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\City;

class Place extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'number',
        'district',
        'zip',
        'complement',
        'city_id',
        'status'
    ];

    public function cite()
    {
      return $this->belongsTo(City::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function get_city()
    {
        return City::join('states', 'cities.uf', '=', 'states.id')
                ->where('cities.id', $this->city_id)
                ->selectRaw('cities.name, states.uf')->first();
    }
}
