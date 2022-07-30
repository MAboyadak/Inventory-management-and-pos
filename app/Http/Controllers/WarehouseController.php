<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::all();
        return view('settings.warehouses',compact('warehouses'));
    }

    public function store(Request $request)
    {
        if(! Auth::user()->can('store warehouse')){
            return redirect()->back()->with('error', 'You don\'t have permission to do this action!');
        }

        $request->validate([
            'name' => 'required|unique:warehouses',
            'address' => 'max:255',
        ]);

        Warehouse::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->back()->with('success','Warehouse created successfully');

    }

    public function delete($id)
    {
        if(! Auth::user()->can('delete warehouse')){
            return redirect()->back()->with('error', 'You don\'t have permission to do this action!');
        }

        if(Warehouse::find($id)->exists()){
            Warehouse::destroy($id);
            return redirect()->back()->with('success','Warehouse deleted successfully');
        }else{
            session()->flash('error', 'This warehouse doesn\'t exist!');
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        if(! Auth::user()->can('edit warehouse')){
            return redirect()->back()->with('error', 'You don\'t have permission to do this action!');
        }

        $request->validate([
            'name' => 'required|unique:warehouses,name,'. $request->id,
            'address' => 'max:255',
        ]);

        Warehouse::where('id','=',$request->id)->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->back()->with('success','Warehouse Updated successfully');
    }

    public function getData($id)
    {
        if(! Auth::user()->can('edit warehouse')){
            // return redirect()->back()->with('error', 'You don\'t have permission to do this action!');
            session()->flash('error', 'You don\'t have permission to do this action!!');
            return 'not authorized';
        }

        $wh = Warehouse::find($id);
        if($wh->exists()){
            return $wh;
        }else{
            session()->flash('error', 'This warehouse doesn\'t exist!');
            return 'not existed';
        }
    }
}
