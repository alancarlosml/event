<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    protected $table = 'places';

    protected $fillable = [
        'name',
        'address',
        'number',
        'district',
        'zip',
        'complement',
        'city_id',
        'status',
    ];

    // Canonical relationship: place belongs to a city
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    // Backward-compatible alias (typo kept intentionally)
    public function cite()
    {
        return $this->city();
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    // Backward-compatible helper: return the actual City model
    public function get_city()
    {
        // Ensure callers like $place->get_city()->name receive a model, not a Relation
        return $this->city; // lazy-loads the related City model
    }

    // Accessor to allow usage like $place->get_city->name
    public function getGetCityAttribute()
    {
        return $this->city;
    }
}
