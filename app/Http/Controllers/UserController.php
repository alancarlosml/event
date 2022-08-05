<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

class UserController extends Controller
{
    public function index(){
        
        $users = User::orderBy('name')->get();

        return view('user.index', compact('users'));
    }

    public function create(){

        return view('user.add');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $input = $request->all();

        User::create($input);

        return redirect()->route('user.index');
    }

    public function edit($id){
                
        $user = User::find($id);

        return view('user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $this->validate($request, [
            'name' => 'required'
        ]);

        $input = $request->all();

        if(isset($input['status'])){
            $input['status'] = 1;
        }else{
            $input['status'] = 0;
        }

        $user->fill($input)->save();

        return redirect()->route('user.index');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();
        
        return redirect()->route('user.index');
    }
}
