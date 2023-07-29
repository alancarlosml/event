<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Owner;

class OwnerController extends Controller
{
    public function index()
    {

        $owners = Owner::orderBy('name')->get();

        return view('owner.index', compact('owners'));
    }

    public function create()
    {

        return view('owner.add');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'icon' => 'mimes:jpg,jpeg,bmp,png|max:2048',
            'status' => 'nullable',
        ]);

        $input = $request->all();

        if(isset($input['status'])) {
            $input['status'] = 1;
        } else {
            $input['status'] = 0;
        }

        $owner_id = Owner::create($input)->id;

        $owner = Owner::findOrFail($owner_id);

        if($request->file('icon')) {
            $fileName = time().'_'.$request->file('icon')->getClientOriginalName();
            $filePath = $request->file('icon')->storeAs('owners', $fileName, 'public');

            if($owner) {
                $owner->icon = $filePath;
                $owner->save();
            }
        }

        return redirect()->route('owner.index');
    }

    public function edit($id)
    {

        $owner = Owner::find($id);

        return view('owner.edit', compact('owner'));
    }

    public function update(Request $request, $id)
    {
        $owner = Owner::findOrFail($id);

        if($owner->icon) {
            $this->validate($request, [
                'name' => 'required',
                'status',
            ]);
        } else {
            $this->validate($request, [
                'name' => 'required',
                'icon' => 'mimes:jpg,jpeg,bmp,png|max:2048',
                'status',
            ]);
        }

        $input = $request->all();

        if(isset($input['status'])) {
            $input['status'] = 1;
        } else {
            $input['status'] = 0;
        }

        $owner->fill($input)->save();

        if($request->file('icon')) {
            $fileName = time().'_'.$request->file('icon')->getClientOriginalName();
            $filePath = $request->file('icon')->storeAs('owners', $fileName, 'public');

            if($owner) {
                $owner->icon = $filePath;
                $owner->save();
            }
        }

        return redirect()->route('owner.index');
    }

    public function destroy($id)
    {
        $owner = Owner::findOrFail($id);

        $owner->delete();

        return redirect()->route('owner.index');
    }

    public function show($id)
    {

        $owner = Owner::find($id);

        return view('owner.show', compact('owner'));
    }
}
