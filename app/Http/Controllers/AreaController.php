<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Area;
use App\Models\Category;

class AreaController extends Controller
{
    public function index($category_id)
    {

        $category = Category::find($category_id);
        $areas = Area::where('category_id', $category_id)->orderBy('name')->get();

        $category_id = $category->id;

        // dd($areas);

        return view('area.index', compact('category', 'areas'));
    }

    public function create($category_id)
    {

        $category = Category::find($category_id);

        return view('area.add', compact('category'));
    }

    public function store(Request $request, $category_id)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $input = $request->all();

        $input['slug'] = Str::slug($input['name'], '-');

        $input['category_id'] = $category_id;

        Area::create($input);

        return redirect()->route('area.index', $category_id);
    }

    public function edit($category_id, $id)
    {

        $category = Category::find($category_id);

        $area = Area::find($id);

        return view('area.edit', compact('area', 'category'));
    }

    public function update(Request $request, $category_id, $id)
    {
        $category = Category::findOrFail($category_id);

        $area = Area::findOrFail($id);

        $this->validate($request, [
            'name' => 'required',
        ]);

        $input = $request->all();

        $input['slug'] = Str::slug($input['name'], '-');

        if(isset($input['status'])) {
            $input['status'] = 1;
        } else {
            $input['status'] = 0;
        }

        $area->fill($input)->save();

        return redirect()->route('area.index', $category_id);
    }

    public function destroy($category_id, $id)
    {
        $area = Area::findOrFail($id);

        $area->delete();

        return redirect()->route('area.index', $category_id);
    }

    public function show($id)
    {

        $category = Category::find($id);

        return view('category.show', compact('category'));
    }
}
