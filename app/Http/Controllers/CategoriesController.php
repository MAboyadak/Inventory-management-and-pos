<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('categories.index',compact('categories'));
    }

    public function store(Request $request)
    {
        if(! Auth::user()->can('store warehouse')){
            return redirect()->back()->with('error', 'You don\'t have permission to do this action!');
        }

        $request->validate([
            'name' => 'required|unique:categories',
            'parent_id' => 'nullable',
        ]);

        Category::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->back()->with('success','Category created successfully');

    }

    public function delete($id)
    {
        if(! Auth::user()->can('delete warehouse')){
            return redirect()->back()->with('error', 'You don\'t have permission to do this action!');
        }

        if(Category::find($id)->exists()){
            Category::destroy($id);
            return redirect()->back()->with('success','Category deleted successfully');
        }else{
            session()->flash('error', 'This Category doesn\'t exist!');
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        if(! Auth::user()->can('edit warehouse')){
            return redirect()->back()->with('error', 'You don\'t have permission to do this action!');
        }

        $request->validate([
            'name' => 'required|unique:categories,name,' . $request->id,
            'parent_id' => 'nullable',
        ]);

        Category::where('id','=',$request->id)->update([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->back()->with('success','Category Updated successfully');
    }

    public function getData($id)
    {
        // return($id);
        if(! Auth::user()->can('edit warehouse')){
            // return redirect()->back()->with('error', 'You don\'t have permission to do this action!');
            session()->flash('error', 'You don\'t have permission to do this action!!');
            return 'not authorized';
        }

        $cat = Category::find($id);
        if($cat->exists()){
            return $cat;
        }else{
            session()->flash('error', 'This Category doesn\'t exist!');
            return 'not existed';
        }
    }


}
