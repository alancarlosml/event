<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Configuration;
use App\Models\Lote;
use App\Models\Event; // Added this import for the new validation

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
                'value' => 'required|numeric|min:0',
                'name' => 'required',
                'quantity' => 'required|integer|min:1',
                'description' => 'required',
                'limit_min' => 'required|integer|min:1',
                'limit_max' => 'required|integer|gte:limit_min',
                'datetime_begin' => 'required|date|after:now',
                'datetime_end' => 'required|date|after:datetime_begin',
                'form_pagamento' => 'nullable',
                'visibility' => 'required',
            ], [
                'value.required' => 'O valor do lote é obrigatório.',
                'value.numeric' => 'O valor deve ser um número.',
                'value.min' => 'O valor não pode ser negativo.',
                'quantity.required' => 'A quantidade é obrigatória.',
                'quantity.integer' => 'A quantidade deve ser um número inteiro.',
                'quantity.min' => 'A quantidade deve ser pelo menos 1.',
                'limit_min.required' => 'O limite mínimo é obrigatório.',
                'limit_min.integer' => 'O limite mínimo deve ser um número inteiro.',
                'limit_min.min' => 'O limite mínimo deve ser pelo menos 1.',
                'limit_max.required' => 'O limite máximo é obrigatório.',
                'limit_max.integer' => 'O limite máximo deve ser um número inteiro.',
                'limit_max.gte' => 'O limite máximo deve ser maior ou igual ao limite mínimo.',
                'datetime_begin.required' => 'A data de início é obrigatória.',
                'datetime_begin.date' => 'A data de início deve ser uma data válida.',
                'datetime_begin.after' => 'A data de início deve ser posterior ao momento atual.',
                'datetime_end.required' => 'A data de fim é obrigatória.',
                'datetime_end.date' => 'A data de fim deve ser uma data válida.',
                'datetime_end.after' => 'A data de fim deve ser posterior à data de início.',
            ]);
        } else {

            $this->validate($request, [
                'name' => 'required',
                'description' => 'required',
                'type' => 'required|integer',
                'quantity' => 'required|integer|min:1',
                'visibility' => 'required',
                'limit_min' => 'required|integer|min:1',
                'limit_max' => 'required|integer|gte:limit_min',
                'datetime_begin' => 'required|date|after:now',
                'datetime_end' => 'required|date|after:datetime_begin',
                'event_id' => 'nullable',
            ], [
                'quantity.required' => 'A quantidade é obrigatória.',
                'quantity.integer' => 'A quantidade deve ser um número inteiro.',
                'quantity.min' => 'A quantidade deve ser pelo menos 1.',
                'limit_min.required' => 'O limite mínimo é obrigatório.',
                'limit_min.integer' => 'O limite mínimo deve ser um número inteiro.',
                'limit_min.min' => 'O limite mínimo deve ser pelo menos 1.',
                'limit_max.required' => 'O limite máximo é obrigatório.',
                'limit_max.integer' => 'O limite máximo deve ser um número inteiro.',
                'limit_max.gte' => 'O limite máximo deve ser maior ou igual ao limite mínimo.',
                'datetime_begin.required' => 'A data de início é obrigatória.',
                'datetime_begin.date' => 'A data de início deve ser uma data válida.',
                'datetime_begin.after' => 'A data de início deve ser posterior ao momento atual.',
                'datetime_end.required' => 'A data de fim é obrigatória.',
                'datetime_end.date' => 'A data de fim deve ser uma data válida.',
                'datetime_end.after' => 'A data de fim deve ser posterior à data de início.',
            ]);
        }

        // VALIDATION: Check if event exists and has dates
        $event = Event::find($id);
        if (!$event) {
            return redirect()->back()->withErrors(['error' => 'Evento não encontrado.']);
        }

        if ($event->event_dates()->count() == 0) {
            return redirect()->back()->withErrors(['error' => 'O evento deve ter pelo menos uma data antes de criar lotes.']);
        }

        // VALIDATION: Check if lot sales period doesn't exceed event dates
        $eventDates = $event->event_dates()->orderBy('date')->get();
        $lotBegin = \Carbon\Carbon::parse($input['datetime_begin']);
        $lotEnd = \Carbon\Carbon::parse($input['datetime_end']);
        
        $eventStartDate = $eventDates->first()->date;
        $eventEndDate = $eventDates->last()->date;
        
        if ($lotEnd->date() > $eventEndDate) {
            return redirect()->back()->withErrors(['error' => 'O período de venda do lote não pode ultrapassar a data do evento.']);
        }

        $number_lotes = Lote::where('event_id', $id)->count();

        $input['event_id'] = $id;

        $input['order'] = $number_lotes + 1;
        $input['datetime_begin'] = date('Y-m-d H:m', strtotime(str_replace('/', '-', $input['datetime_begin'])));
        $input['datetime_end'] = date('Y-m-d H:m', strtotime(str_replace('/', '-', $input['datetime_end'])));

        if($input['type'] == 0) {
            $input['tax'] = doubleval($input['value']) * $taxa_juros;
            // Se a taxa é paga pelo participante (tax_service == 0), soma ao valor
            // Se a taxa é paga pelo organizador (tax_service == 1), subtrai do valor
            if(isset($input['tax_service']) && $input['tax_service'] == 0) {
                $input['final_value'] = doubleval($input['value']) + doubleval($input['value']) * $taxa_juros;
            } else {
                $input['final_value'] = doubleval($input['value']) - doubleval($input['value']) * $taxa_juros;
            }
            $input['form_pagamento'] = implode(',', $input['form_pagamento']);
        }

        $input['hash'] = md5($input['name'] . $input['description'] . $input['event_id'] . time() . uniqid() . md5(config('services.hash_secret')));

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
                'value' => 'required|numeric|min:0',
                'name' => 'required',
                'quantity' => 'required|integer|min:1',
                'description' => 'required',
                'limit_min' => 'required|integer|min:1',
                'limit_max' => 'required|integer|gte:limit_min',
                'datetime_begin' => 'required|date',
                'datetime_end' => 'required|date|after:datetime_begin',
                'form_pagamento' => 'nullable',
                'visibility' => 'required',
            ], [
                'value.required' => 'O valor do lote é obrigatório.',
                'value.numeric' => 'O valor deve ser um número.',
                'value.min' => 'O valor não pode ser negativo.',
                'quantity.required' => 'A quantidade é obrigatória.',
                'quantity.integer' => 'A quantidade deve ser um número inteiro.',
                'quantity.min' => 'A quantidade deve ser pelo menos 1.',
                'limit_min.required' => 'O limite mínimo é obrigatório.',
                'limit_min.integer' => 'O limite mínimo deve ser um número inteiro.',
                'limit_min.min' => 'O limite mínimo deve ser pelo menos 1.',
                'limit_max.required' => 'O limite máximo é obrigatório.',
                'limit_max.integer' => 'O limite máximo deve ser um número inteiro.',
                'limit_max.gte' => 'O limite máximo deve ser maior ou igual ao limite mínimo.',
                'datetime_begin.required' => 'A data de início é obrigatória.',
                'datetime_begin.date' => 'A data de início deve ser uma data válida.',
                'datetime_end.required' => 'A data de fim é obrigatória.',
                'datetime_end.date' => 'A data de fim deve ser uma data válida.',
                'datetime_end.after' => 'A data de fim deve ser posterior à data de início.',
            ]);
        } else {

            $this->validate($request, [
                'name' => 'required',
                'description' => 'required',
                'type' => 'required|integer',
                'quantity' => 'required|integer|min:1',
                'visibility' => 'required',
                'limit_min' => 'required|integer|min:1',
                'limit_max' => 'required|integer|gte:limit_min',
                'datetime_begin' => 'required|date',
                'datetime_end' => 'required|date|after:datetime_begin',
                'event_id' => 'nullable',
            ], [
                'quantity.required' => 'A quantidade é obrigatória.',
                'quantity.integer' => 'A quantidade deve ser um número inteiro.',
                'quantity.min' => 'A quantidade deve ser pelo menos 1.',
                'limit_min.required' => 'O limite mínimo é obrigatório.',
                'limit_min.integer' => 'O limite mínimo deve ser um número inteiro.',
                'limit_min.min' => 'O limite mínimo deve ser pelo menos 1.',
                'limit_max.required' => 'O limite máximo é obrigatório.',
                'limit_max.integer' => 'O limite máximo deve ser um número inteiro.',
                'limit_max.gte' => 'O limite máximo deve ser maior ou igual ao limite mínimo.',
                'datetime_begin.required' => 'A data de início é obrigatória.',
                'datetime_begin.date' => 'A data de início deve ser uma data válida.',
                'datetime_end.required' => 'A data de fim é obrigatória.',
                'datetime_end.date' => 'A data de fim deve ser uma data válida.',
                'datetime_end.after' => 'A data de fim deve ser posterior à data de início.',
            ]);
        }

        // VALIDATION: Check if event exists and has dates
        $event = Event::find($input['event_id']);
        if (!$event) {
            return redirect()->back()->withErrors(['error' => 'Evento não encontrado.']);
        }

        if ($event->event_dates()->count() == 0) {
            return redirect()->back()->withErrors(['error' => 'O evento deve ter pelo menos uma data antes de editar lotes.']);
        }

        // VALIDATION: Check if lot sales period doesn't exceed event dates
        $eventDates = $event->event_dates()->orderBy('date')->get();
        $lotBegin = \Carbon\Carbon::parse($input['datetime_begin']);
        $lotEnd = \Carbon\Carbon::parse($input['datetime_end']);
        
        $eventStartDate = $eventDates->first()->date;
        $eventEndDate = $eventDates->last()->date;
        
        if ($lotEnd->date() > $eventEndDate) {
            return redirect()->back()->withErrors(['error' => 'O período de venda do lote não pode ultrapassar a data do evento.']);
        }

        $number_lotes = Lote::where('event_id', $input['event_id'])->count();

        $input['order'] = $number_lotes + 1;
        $input['datetime_begin'] = date('Y-m-d H:m', strtotime(str_replace('/', '-', $input['datetime_begin'])));
        $input['datetime_end'] = date('Y-m-d H:m', strtotime(str_replace('/', '-', $input['datetime_end'])));

        if($input['type'] == 0) {
            $input['tax'] = doubleval($input['value']) * $taxa_juros;
            // Se a taxa é paga pelo participante (tax_service == 0), soma ao valor
            // Se a taxa é paga pelo organizador (tax_service == 1), subtrai do valor
            if(isset($input['tax_service']) && $input['tax_service'] == 0) {
                $input['final_value'] = doubleval($input['value']) + doubleval($input['value']) * $taxa_juros;
            } else {
                $input['final_value'] = doubleval($input['value']) - doubleval($input['value']) * $taxa_juros;
            }
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
        $event_id = $lote->event_id;
        $lote->delete();

        return redirect()->route('event.lotes', $event_id);
    }

    /**
     * Corrigir hashes de lotes existentes
     */
    public function fixLoteHashes()
    {
        $lotes = Lote::all();
        
        foreach($lotes as $lote) {
            // Gerar novo hash único
            $newHash = md5($lote->name . $lote->description . $lote->event_id . $lote->id . time() . uniqid() . md5(config('services.hash_secret')));
            
            // Atualizar o hash
            $lote->update(['hash' => $newHash]);
        }
        
        return response()->json(['success' => 'Hashes dos lotes corrigidos com sucesso!']);
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
