<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Category;

class CategoryController extends Controller
{
    public function index(){
        
        $categories = Category::orderBy('description')->get();

        // dd($categories);

        return view('category.index', compact('categories'));
    }

    public function create(){

        return view('category.add');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'description' => 'required'
        ]);

        $input = $request->all();

        $input['slug'] = $slug = Str::slug($input['description'], '-');

        if(isset($input['status'])){
            $input['status'] = 1;
        }else{
            $input['status'] = 0;
        }

        Category::create($input);

        return redirect()->route('category.index');
    }

    public function edit($id){
                
        $category = Category::find($id);

        return view('category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $this->validate($request, [
            'description' => 'required'
        ]);

        $input = $request->all();

        $input['slug'] = $slug = Str::slug($input['description'], '-');

        if(isset($input['status'])){
            $input['status'] = 1;
        }else{
            $input['status'] = 0;
        }

        $category->fill($input)->save();

        return redirect()->route('category.index');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        $category->delete();
        
        return redirect()->route('category.index');
    }

    public function show($id){
                
        $category = Category::find($id);

        return view('category.show', compact('category'));
    }

    public function getAreas(Request $request)
    {
        $data['areas'] = Area::where("category_id",$request->category_id)
                    ->where("status", 1)
                    ->get(["name","id"]);
        
        return response()->json($data);
    }
}
