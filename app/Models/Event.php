<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
// use Cviebrock\EloquentSluggable\Sluggable;

class Event extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'name',
        'hash',
        'subtitle',
        'slug',
        'description',
        'banner',
        'banner_option',
        'owner_id',
        'area_id',
        'max_tickets',
        'theme',
        'status'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function getAll(){
        
        $events = Event::where('events.status', 1)
                        ->join('event_dates', 'event_dates.event_id', '=', 'events.id')
                        ->selectRaw('events.*, min(event_dates.date) min_date, min(event_dates.time_begin) min_time')
                        ->orderBy('events.created_at','ASC')
                        ->groupBy('events.id')
                        ->get();
        // dd($events);

        return $events;
    }

    public function get_category()
    {
        return Category::join('areas', 'areas.category_id', '=', 'categories.id')
                ->where('areas.id', $this->area_id)
                ->selectRaw('categories.description, categories.id')->first();
    }

    public function area()
    {
      return $this->belongsTo(Area::class);
    }

    public function place()
    {
      return $this->belongsTo(Place::class);
    }

    public function owner()
    {
      return $this->belongsTo(Owner::class);
    }

    public function lotes()
    {
        return $this->hasMany(Lote::class)->orderBy('order');
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    public function participantes()
    {
        return $this->belongsToMany(
            Participante::class,
            'participantes_events',
            'event_id',
            'participante_id');
    }

    public function get_participante_admin()
    {
        return Participante::join('participantes_events', 'participantes_events.participante_id', '=', 'participantes.id')
                ->where('participantes_events.event_id', $this->id)
                ->where('participantes_events.role', 'admin')
                ->selectRaw('participantes.name, participantes.email')
                ->first();
    }

    public function event_dates()
    {
        return $this->hasMany(EventDate::class);
    }

    public function max_event_dates() 
    {
        return $this->event_dates()->max('event_dates.date');
    }

    public function min_event_dates() 
    {
        return $this->event_dates()->min('event_dates.date');
    }

    public function min_event_time() 
    {
        return $this->event_dates()->min('event_dates.time_begin');
    }
    

}
