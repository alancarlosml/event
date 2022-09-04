<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\DB;
// use Cviebrock\EloquentSluggable\Sluggable;

use DateTime;

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

    public static function getEvents($event_val, $category_val, $area_val, $state_val, $period_val) {
        
        $events = Event::where('events.status', 1);

        if($event_val && !empty($event_val)) {
            $events->where(function($q) use ($event_val) {
                $q->where('events.name', 'like', "'%{$event_val}%'");
            });
        }

        if($category_val != '0') {
            $events = $events->join('areas', 'areas.id', '=', 'events.area_id');
            $events = $events->join('categories', 'categories.id', '=', 'areas.category_id');
            $events = $events->where('categories.id', $category_val);
        }

        if($area_val != null) {
            $events = $events->where('events.area_id', $area_val);
        }

        if($state_val != '0') {
            $events = $events->join('places', 'places.id', '=', 'events.place_id');
            $events = $events->join('cities', 'cities.id', '=', 'places.city_id');
            $events = $events->join('states', 'states.uf', '=', 'cities.uf');
            $events = $events->where('states.uf', $state_val);
        }

        if($period_val != '0') {

            $events = $events->join('event_dates', 'event_dates.event_id', '=', 'events.id');

            $today = date('Y-m-d');
            // $today = date('2022-08-27');

            switch( $period_val ) {
                case 'any':
                    $events = $events;
                    break;
                case 'today':
                    $events = $events->where('event_dates.date', $today);
                    // $dates = DB::table('event_dates')
                    //             ->selectRaw("date");

                    // $eventsJoin = $events->joinSub($dates, 'x', function ($join)
                    //     {
                    //         $join->on('x.event_id', '=', 'events.id');
                    //     })
                    //     ->selectRaw("x.date")
                    //     // ->where("MIN(x.date) as min_date, MAX(x.date) as max_date")
                    //     ->groupBy('events.id');

                    // // $events = $events->selectSub($eventsJoin, 'id');

                    // // $events = $events->orderBy(function ($eventsJoin) {
                    // //     $eventsJoin->whereBetween('2022-08-29', ['min_date', 'max_date']);
                    // // });
                    // $events = $eventsJoin;

                    break;
                case 'tomorrow':
                    $events = $events->where('event_dates.date', date('Y-m-d', strtotime("+1 day")));
                    break;
                case 'week':
                    $events = $events->where(DB::raw("week(event_dates.date)"), DB::raw("week(CURDATE())"));
                    break;
                case 'month':
                    $events = $events->where(DB::raw("month(event_dates.date)"), DB::raw("month(CURDATE())"));
                    break;
                case 'year':
                    $events = $events->where(DB::raw("year(event_dates.date)"), DB::raw("year(CURDATE())"));
                    break;
                default:
                    $events = $events->where(DB::raw("LOWER(MONTHNAME(event_dates.date))"), $period_val);
                    break;
            }

        }

        // Filter By Type
        // if($sort_by) {
        //     $sort_by = lcfirst($sort_by);
        //     if($sort_by == GlobalConstants::USER_TYPE_FRONTEND) {
        //         $users = $users->where('users.type', $sort_by);
        //     } else if($sort_by == GlobalConstants::USER_TYPE_BACKEND) {
        //         $users = $users->where('users.type', $sort_by);
        //     }
        // }

        // // Filter By Salaries
        // if ($range && $range != GlobalConstants::ALL) {
        //     $users = $users->where('users.salary', $range);
        // }

        // dd($events);

        return $events->distinct()->paginate(12);
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
