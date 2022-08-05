<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
            'description' => 'required',
            'slug' => 'required'
        ]);

        $input = $request->all();

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
            'description' => 'required',
            'slug' => 'required'
        ]);

        $input = $request->all();

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
}
