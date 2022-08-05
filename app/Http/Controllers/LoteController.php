<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Lote;

class LoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function create($id){

        return view('lote.add', compact('id'));
    }

    public function store(Request $request, $id)
    {
        $input = $request->all();

        if($input['type'] == 0){
            $this->validate($request, [
                'name' => 'required',
                'description' => 'required',
                'type' => 'required|integer',
                'quantity' => 'required',
                'visibility' => 'required',
                'limit_min' => 'required|min:1',
                'limit_max' => 'required|gt:limit_min',
                'datetime_begin' => 'required',
                'datetime_end' => 'required',
                'tax_parcelamento' => 'required|integer',
                'tax_service' => 'required|integer',
                'form_pagamento' => 'required|integer'
            ]);
        } else {

            $this->validate($request, [
                'name' => 'required',
                'description' => 'required',
                'type' => 'required|integer',
                'quantity' => 'required',
                'visibility' => 'required',
                'limit_min' => 'required|min:1',
                'limit_max' => 'required|gt:limit_min',
                'datetime_begin' => 'required',
                'datetime_end' => 'required'
            ]);
        }

        $number_lotes = Lote::where("event_id", $id)->count();

        $input['order'] = $number_lotes + 1;
        $input['datetime_begin'] = date('Y-m-d H:m', strtotime(str_replace('/', '-', $input['datetime_begin'])));
        $input['datetime_end'] = date('Y-m-d H:m', strtotime(str_replace('/', '-', $input['datetime_end'])));

        if($input['type'] == 0) {
            $input['tax'] = doubleval($input['value']) * 0.07;
            $input['final_value'] = doubleval($input['value']) - doubleval($input['value']) * 0.07;
        }

        Lote::create($input);

        return redirect()->route('event.lotes', $id);
    }

    public function edit($id){
                
        $lote = Lote::find($id);

        $lote['datetime_begin'] = date('m/d/Y H:m', strtotime(str_replace('-', '/', $lote['datetime_begin'])));
        $lote['datetime_end'] = date('m/d/Y H:m', strtotime(str_replace('-', '/', $lote['datetime_end'])));

        return view('lote.edit', compact('lote'));
    }

    public function update(Request $request, $id)
    {
        $lote = Lote::findOrFail($id);

        $input = $request->all();
        
        if($input['type'] == 0){
            $this->validate($request, [
                'name' => 'required',
                'description' => 'required',
                'type' => 'required|integer',
                'quantity' => 'required',
                'visibility' => 'required',
                'limit_min' => 'required|min:1',
                'limit_max' => 'required|gt:limit_min',
                'datetime_begin' => 'required',
                'datetime_end' => 'required',
                'tax_parcelamento' => 'required|integer',
                'tax_service' => 'required|integer',
                'form_pagamento' => 'required|integer'
            ]);
        } else {

            $this->validate($request, [
                'name' => 'required',
                'description' => 'required',
                'type' => 'required|integer',
                'quantity' => 'required',
                'visibility' => 'required',
                'limit_min' => 'required|min:1',
                'limit_max' => 'required|gt:limit_min',
                'datetime_begin' => 'required',
                'datetime_end' => 'required'
            ]);
        }

        $input['datetime_begin'] = date('Y-m-d H:m', strtotime(str_replace('/', '-', $input['datetime_begin'])));
        $input['datetime_end'] = date('Y-m-d H:m', strtotime(str_replace('/', '-', $input['datetime_end'])));

        if($input['type'] == 0) {
            $input['tax'] = doubleval($input['value']) * 0.07;
            $input['final_value'] = doubleval($input['value']) - doubleval($input['value']) * 0.07;
        }

        if(isset($input['status'])){
            $input['status'] = 1;
        }else{
            $input['status'] = 0;
        }

        $lote->fill($input)->save();

        return redirect()->route('event.lotes', $id);
    }

    public function destroy($id)
    {
        $lote = Lote::findOrFail($id);

        $lote->delete();
        
        return redirect()->route('event.lotes', $lote->event_id);
    }

    public function save_lotes(Request $request, $id)
    {
        $input = $request->all();

        foreach($input['order_lote'] as $order){
            
            $id_order = explode('_', $order);
            $idorder = $id_order[0];
            $order = $id_order[1];
            $lote = Lote::findOrFail($idorder);

            if($lote) {
                $lote->order = $order;
                $lote->save();
            }
        }

        return redirect()->route('event.lotes', $id);
    }
}
