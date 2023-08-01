<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Configuration;
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

    }

    public function create($id)
    {

        $config = Configuration::findOrFail(1);

        $taxa_juros = $config->tax;

        return view('lote.add', compact('id', 'taxa_juros'));
    }

    public function store(Request $request, $id)
    {
        $config = Configuration::findOrFail(1);

        $taxa_juros = $config->tax;

        $input = $request->all();

        if($input['type'] == 0) {
            $this->validate($request, [
                'event_id' => 'nullable',
                'type' => 'required|integer',
                'tax_parcelamento' => 'required|integer',
                'tax_service' => 'required|integer',
                'value' => 'required',
                'name' => 'required',
                'quantity' => 'required',
                'description' => 'required',
                'limit_min' => 'required|min:1',
                'limit_max' => 'required|gte:limit_min',
                'datetime_begin' => 'required',
                'datetime_end' => 'required',
                'form_pagamento' => 'nullable',
                'visibility' => 'required',
            ]);
        } else {

            $this->validate($request, [
                'name' => 'required',
                'description' => 'required',
                'type' => 'required|integer',
                'quantity' => 'required',
                'visibility' => 'required',
                'limit_min' => 'required|min:1',
                'limit_max' => 'required|gte:limit_min',
                'datetime_begin' => 'required',
                'datetime_end' => 'required',
                'event_id' => 'nullable',
            ]);
        }

        $number_lotes = Lote::where('event_id', $id)->count();

        $input['event_id'] = $id;

        $input['order'] = $number_lotes + 1;
        $input['datetime_begin'] = date('Y-m-d H:m', strtotime(str_replace('/', '-', $input['datetime_begin'])));
        $input['datetime_end'] = date('Y-m-d H:m', strtotime(str_replace('/', '-', $input['datetime_end'])));

        if($input['type'] == 0) {
            $input['tax'] = doubleval($input['value']) * $taxa_juros;
            $input['final_value'] = doubleval($input['value']) - doubleval($input['value']) * $taxa_juros;
            $input['form_pagamento'] = implode(',', $input['form_pagamento']);
        }

        $input['hash'] = md5($input['name'] . $input['description'] . md5('papainoel'));

        Lote::create($input);

        return redirect()->route('event.lotes', $id);
    }

    public function edit($id)
    {

        $lote = Lote::find($id);

        $config = Configuration::findOrFail(1);

        $taxa_juros = $config->tax;

        $lote['datetime_begin'] = date('m/d/Y H:m', strtotime(str_replace('-', '/', $lote['datetime_begin'])));
        $lote['datetime_end'] = date('m/d/Y H:m', strtotime(str_replace('-', '/', $lote['datetime_end'])));

        return view('lote.edit', compact('lote', 'taxa_juros'));
    }

    public function update(Request $request, $id)
    {
        $config = Configuration::findOrFail(1);

        $taxa_juros = $config->tax;

        $lote = Lote::findOrFail($id);

        $input = $request->all();

        if($input['type'] == 0) {
            $this->validate($request, [
                'event_id' => 'nullable',
                'type' => 'required|integer',
                'tax_parcelamento' => 'required|integer',
                'tax_service' => 'required|integer',
                'value' => 'required',
                'name' => 'required',
                'quantity' => 'required',
                'description' => 'required',
                'limit_min' => 'required|min:1',
                'limit_max' => 'required|gte:limit_min',
                'datetime_begin' => 'required',
                'datetime_end' => 'required',
                'form_pagamento' => 'nullable',
                'visibility' => 'required',
            ]);
        } else {

            $this->validate($request, [
                'name' => 'required',
                'description' => 'required',
                'type' => 'required|integer',
                'quantity' => 'required',
                'visibility' => 'required',
                'limit_min' => 'required|min:1',
                'limit_max' => 'required|gte:limit_min',
                'datetime_begin' => 'required',
                'datetime_end' => 'required',
                'event_id' => 'nullable',
            ]);
        }

        $number_lotes = Lote::where('event_id', $input['event_id'])->count();

        $input['order'] = $number_lotes + 1;
        $input['datetime_begin'] = date('Y-m-d H:m', strtotime(str_replace('/', '-', $input['datetime_begin'])));
        $input['datetime_end'] = date('Y-m-d H:m', strtotime(str_replace('/', '-', $input['datetime_end'])));

        if($input['type'] == 0) {
            $input['tax'] = doubleval($input['value']) * $taxa_juros;
            $input['final_value'] = doubleval($input['value']) - doubleval($input['value']) * $taxa_juros;
            $input['form_pagamento'] = implode(',', $input['form_pagamento']);
        }

        if(isset($input['status'])) {
            $input['status'] = 1;
        } else {
            $input['status'] = 0;
        }

        $lote->fill($input)->save();

        return redirect()->route('event.lotes', $input['event_id']);
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

        foreach($input['order_lote'] as $order) {

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
