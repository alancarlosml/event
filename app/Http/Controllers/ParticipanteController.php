<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Participante;

class ParticipanteController extends Controller
{
    public function index(){
        
        $participantes = Participante::orderBy('name')->get();

        return view('participante.index', compact('participantes'));
    }

    public function create(){

        return view('participante.add');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $input = $request->all();

        Participante::create($input);

        return redirect()->route('participante.index');
    }

    public function edit($id){
                
        $participante = Participante::find($id);

        return view('participante.edit', compact('participante'));
    }

    public function update(Request $request, $id)
    {
        $participante = Participante::findOrFail($id);

        $this->validate($request, [
            'name' => 'required'
        ]);

        $input = $request->all();

        if(isset($input['status'])){
            $input['status'] = 1;
        }else{
            $input['status'] = 0;
        }

        $participante->fill($input)->save();

        return redirect()->route('participante.index');
    }

    public function destroy($id)
    {
        $participante = Participante::findOrFail($id);

        $participante->delete();
        
        return redirect()->route('participante.index');
    }

    public function show($id){
                
        $participante = Participante::find($id);

        return view('participante.show', compact('participante'));
    }
}
