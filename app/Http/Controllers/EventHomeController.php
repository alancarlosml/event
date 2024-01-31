<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\Category;
use App\Models\Configuration;
use App\Models\City;
use App\Models\Event;
use App\Models\EventDate;
use App\Models\Lote;
use App\Models\Participante;
use App\Models\ParticipanteLote;
use App\Models\Place;
use App\Models\Option;
use App\Models\Order;
use App\Models\Owner;
use App\Models\User;
use App\Models\State;

class EventHomeController extends Controller
{
    public function events()
    {
        $site_info = [
            'menu' => 'events',
            'title' => 'Eventos',
            'description' => 'Ticket DZ6 - Venda de ingressos online',
        ];

        $event = new Event();

        $categories = Category::orderBy('description')->get();
        $states = State::orderBy('name')->get();

        $events = $event::getEvents(Null, '0', '0', '0', '0');

        return view('site.events', compact('events', 'categories', 'states', 'site_info'));
    }

    public function getMoreEvents(Request $request)
    {
        $event_val = $request->event_val;
        $category_val = $request->category_val;
        $area_val = $request->area_val;
        $state_val = $request->state_val;
        $period_val = $request->period_val;

        if($request->ajax()) {
            $events = Event::getEvents($event_val, $category_val, $area_val, $state_val, $period_val);
            $events_list = view('site.events_data', compact('events'))->render();

            return response()->json(['succes' => true, 'events_list' => $events_list]);
        }
    }

    public function getCity(Request $request)
    {
        $data['cities'] = City::where('uf', $request->uf)
            ->get(['name', 'id']);

        return response()->json($data);
    }

    public function check_slug(Request $request)
    {
        $slug = Str::slug($request->title, '-');

        $slug_exists = Event::where('slug', $slug)->count();

        return response()->json(['slug' => $slug, 'slug_exists' => $slug_exists]);
    }

    public function autocomplete_place(Request $request)
    {
        $data = Place::join('cities', 'cities.id', '=', 'places.city_id')
            ->join('states', 'states.id', '=', 'cities.uf')
            ->where('places.name', 'LIKE', '%'. $request->get('search'). '%')
            ->select('places.name as value', 'places.id', 'places.address', 'places.number', 'places.complement', 'places.district', 'places.zip', 'places.city_id', 'states.id as uf')
            ->get();

        return response()->json($data);
    }

}
