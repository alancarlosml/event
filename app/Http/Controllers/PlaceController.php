<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Place;
use App\Models\State;
use App\Models\City;

class PlaceController extends Controller
{
    public function index(){
        
        // $places = Place::orderBy('name')->get();

        $places = DB::table('places')
            ->join('cities', 'cities.id', '=', 'places.city_id')
            ->join('states', 'states.uf', '=', 'cities.uf')
            ->select('places.*', 'cities.name as city_name', 'states.uf as city_uf')
            ->orderBy('name')
            ->get();

        // dd($places);

        return view('place.index', compact('places'));
    }

    public function create(){

        $states = State::orderBy('name')->get();

        return view('place.add', compact('states'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'address' => 'required',
            'number' => 'required',
            'district' => 'required',
            'zip' => 'required',
            'state' => 'required',
            'city_id' => 'required',
            'status' => 'required'
        ]);

        $input = $request->all();

        Place::create($input);

        return redirect()->route('place.index');
    }

    public function edit($id){
                
        // $place = Place::find($id);

        $place = DB::table('places')
            ->join('cities', 'cities.id', '=', 'places.city_id')
            ->join('states', 'states.uf', '=', 'cities.uf')
            ->where('places.id', $id)
            ->select('places.*', 'cities.id as city_id', 'states.uf as city_uf')
            ->orderBy('name')
            ->first();

        $states = State::orderBy('name')->get();

        return view('place.edit', compact('place', 'states'));
    }

    public function update(Request $request, $id)
    {
        $place = Place::findOrFail($id);

        $this->validate($request, [
            'name' => 'required',
            'address' => 'required',
            'number' => 'required',
            'district' => 'required',
            'zip' => 'required',
            'state' => 'required',
            'city_id' => 'required',
            'status' => 'required'
        ]);

        $input = $request->all();

        if(isset($input['status'])){
            $input['status'] = 1;
        }else{
            $input['status'] = 0;
        }

        $place->fill($input)->save();

        return redirect()->route('place.index');
    }

    public function destroy($id)
    {
        $place = Place::findOrFail($id);

        $place->delete();
        
        return redirect()->route('place.index');
    }

    public function show($id){
                
        $place = Place::find($id);

        $city_uf = DB::table('cities')
                    ->join('states', 'states.uf', '=', 'cities.uf')
                    ->where('cities.id', $place->city_id)
                    ->select('cities.name', 'states.uf')
                    ->first();

        return view('place.show', compact('place', 'city_uf'));
    }

    public function getCity(Request $request)
    {
        $data['cities'] = City::where("uf",$request->uf)
                    ->get(["name","id"]);
        
        return response()->json($data);
    }
}
