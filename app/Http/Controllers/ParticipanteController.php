<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\Participante;

class ParticipanteController extends Controller
{
    public function index()
    {

        $participantes = Participante::orderBy('name')->get();

        $participantesAdmins = Participante::orderBy('name')
            ->join('participantes_events', 'participantes.id', '=', 'participantes_events.participante_id')
            ->where('participantes_events.role', 'admin')
            ->select('participantes.id', 'participantes.name')
            ->groupBy('participantes.id')
            ->get();

        $participantesConvidados = Participante::orderBy('name')
            ->join('participantes_events', 'participantes.id', '=', 'participantes_events.participante_id')
            ->where('participantes_events.role', 'convidado')
            ->select('participantes.id', 'participantes.name')
            ->groupBy('participantes.id')
            ->get();

        return view('participante.index', compact('participantes', 'participantesAdmins', 'participantesConvidados'));
    }

    public function create()
    {

        return view('participante.add');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:participantes,email',
            'password' => 'required|min:8',
            'cpf' => 'required',
            'phone' => 'required',
        ]);

        $input = $request->all();

        Participante::create($input);

        return redirect()->route('participante.index');
    }

    public function edit($id)
    {

        $participante = Participante::find($id);

        return view('participante.edit', compact('participante'));
    }

    public function update(Request $request, $id)
    {
        $participante = Participante::findOrFail($id);

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:participantes,email,' . $participante->id,
            'password' => 'nullable|min:8',
            'cpf' => 'required',
            'phone' => 'required',
        ]);

        $input = $request->all();

        if(isset($input['status'])) {
            $input['status'] = 1;
        } else {
            $input['status'] = 0;
        }

        if(empty($input['password'])) {
            unset($input['password']);
        } else {
            Hash::make($input['password']);
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

    public function show($id)
    {
        $participante = Participante::find($id);

        return view('participante.show', compact('participante'));
    }

    public function listEvents($id)
    {
        $participante = Participante::find($id);

        return view('participante.list_events', compact('participante'));
    }
    
}
