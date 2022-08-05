<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Configuration;

class ConfigurationController extends Controller
{
    public function edit(){
                
        $configuration = Configuration::find(1);

        return view('configuration.edit', compact('configuration'));
    }

    public function update(Request $request)
    {
        $configuration = Configuration::findOrFail(1);

        $this->validate($request, [
            'tax' => 'required'
        ]);

        $input = $request->all();

        $configuration->fill($input)->save();

        return redirect()->back();
    }
}
