<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\DB;

// use Cviebrock\EloquentSluggable\Sluggable;

class Event extends Model
{
    use HasFactory;
    use HasRoles;

    protected $fillable = [
        'name',
        'hash',
        'subtitle',
        'slug',
        'description',
        // 'category',
        'banner',
        'banner_option',
        // 'admin_id',
        'owner_id',
        'area_id',
        'place_id',
        // 'place_id_hidden',
        'max_tickets',
        'theme',
        'status',
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
                'source' => 'title',
            ],
        ];
    }

    public function getAll()
    {

        $events = Event::where('events.status', 1)
            ->join('event_dates', 'event_dates.event_id', '=', 'events.id')
            ->selectRaw('events.*, min(event_dates.date) min_date, min(event_dates.time_begin) min_time')
            ->orderBy('events.created_at', 'ASC')
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
            'participante_id'
        );
    }

    public static function getEvents($event_val, $category_val, $area_val, $state_val, $period_val)
    {

        $events = Event::join('areas', 'areas.id', '=', 'events.area_id')
            ->join('categories', 'categories.id', '=', 'areas.category_id')
            ->join('places', 'places.id', '=', 'events.place_id')
            ->join('cities', 'cities.id', '=', 'places.city_id')
            ->join('states', 'states.id', '=', 'cities.uf')
            ->join('event_dates', 'event_dates.event_id', '=', 'events.id')
            ->select(
                'events.name as event_name',
                'events.slug as event_slug',
                'events.banner as event_banner',
                'categories.description as category_description',
                'areas.name as area_name',
                'places.name as place_name',
                'cities.name as city_name',
                'states.uf as state_uf',
                DB::raw('min(event_dates.date) as min_date'),
                DB::raw('min(event_dates.time_begin) as min_time')
            )
            ->where('events.status', 1);

        // dd($events->get());

        if($event_val && ! empty($event_val)) {

            $events->where('events.name', 'like', "%{$event_val}%");
        }

        if($category_val != '0') {
            $events->where('categories.id', $category_val);
        }

        if($area_val && $area_val != '0') {
            $events->where('events.area_id', $area_val);
        }

        if($state_val != '0') {
            $events->where('states.uf', $state_val);
        }

        if($period_val != '0') {

            $today = date('Y-m-d');

            switch($period_val) {
                case 'any':
                    $events;

                    break;
                case 'today':
                    $events->where('event_dates.date', $today);

                    break;
                case 'tomorrow':
                    $events->where('event_dates.date', date('Y-m-d', strtotime('+1 day')));

                    break;
                case 'week':
                    $events->where(DB::raw('week(event_dates.date)'), DB::raw('week(CURDATE())'));

                    break;
                case 'month':
                    $events->where(DB::raw('month(event_dates.date)'), DB::raw('month(CURDATE())'));

                    break;
                case 'year':
                    $events->where(DB::raw('year(event_dates.date)'), DB::raw('year(CURDATE())'));

                    break;
                default:
                    $events->where(DB::raw('LOWER(MONTHNAME(event_dates.date))'), $period_val);

                    break;
            }
        }

        // dd($events->get());

        // return $events->get();
        return $events->paginate(10);
    }

    public function get_participante_admin()
    {
        return Participante::join('participantes_events', 'participantes_events.participante_id', '=', 'participantes.id')
            ->where('participantes_events.event_id', $this->id)
            ->where('participantes_events.role', 'admin')
            ->selectRaw('participantes.id, participantes.name, participantes.email')
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
