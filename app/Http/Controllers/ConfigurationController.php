<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Configuration;

class ConfigurationController extends Controller
{
    public function edit()
    {

        $configuration = Configuration::find(1);

        return view('configuration.edit', compact('configuration'));
    }

    public function update(Request $request)
    {
        $configuration = Configuration::findOrFail(1);

        $this->validate($request, [
            'tax' => 'required|numeric',
        ], [
            'tax.required' => 'Campo obrigatório',
            'tax.numeric' => 'O campo taxa de juros deve ser um número.',
        ]);

        $input = $request->all();

        $input['tax'] = (double)$input['tax'] / 100;

        $configuration->fill($input)->save();

        return redirect()->back();
    }
}
