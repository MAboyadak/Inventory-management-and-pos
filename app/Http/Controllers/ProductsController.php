<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Warehouse;
use App\Models\Warehouse_Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Keygen\Keygen;
use DNS1D;
use Exception;
use Illuminate\Support\Facades\Auth;


class ProductsController extends Controller
{
    public function index()
    {
        $products = Product::all();
        // dd($products);
        return view('products.index',compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $warehouses = Warehouse::all();
        $units = Unit::all();

        return view('products.create',compact('categories','warehouses','units'));
    }

    public function generateCode()
    {
        $id = Keygen::numeric(6)->generate();
        return $id;
    }

    public function store(Request $req)
    {
        if(!auth()->user()->can('store product')){
            return redirect()->back()->with('error', 'You don\'t have permission to do this action!');
        }
        // dd($req);
        // return;
        $req->validate([
            'code' => 'required|unique:products',
            'name' => 'required|unique:products',
        ]);

        $file = $req->file('image');
        $filename = '';
        // dd($file);
        if($file){
            $filename = time().$file->getClientOriginalName();
            Storage::disk('public')->putFileAs(
                'files/',
                $file,
                $filename
            );
        }


        //Display File Name
        // $file->getClientOriginalName();

        //Display File Extension
        // $file->getClientOriginalExtension();

        //Display File Real Path
        // $file->getRealPath();

        //Display File Mime Type
        // $file->getMimeType();

        //Move Uploaded File

        // $destinationPath = 'uploads';
        // $file->move($destinationPath,$file->getClientOriginalName());

        $product = Product::create([
            'code' => $req->code,
            'name' => $req->name,
            'category_id' => $req->category_id,
            'unit_id' => $req->unit_id,
            'cost' => $req->cost,
            'price' => $req->price,
            'alert_quantity' => $req->alert_quantity,
            'details' => $req->details,
            'image' => $filename,
        ]);

        $warehouses = $req->warehouse_ids;
        $quantities = $req->qtys;

        foreach($warehouses as $i => $wh )
        {
            Warehouse_Product::create([
                'product_id' => $product->id,
                'warehouse_id' => $wh,
                'qty' => $quantities[$i],
            ]);
        }

        return redirect()->route('products.index')->with('success','Product Created successfully');
    }

    public function printBarcode()
    {
        $products = Product::all();
        // $lims_product_list_with_variant = $this->productWithVariant();
        // dd($lims_product_list_without_variant);
        return view('products.print_barcode', compact('products'));
    }

    public function productSearch(Request $request)
    {
        // exploding code from the whole string (code (name))
        $product_code = explode("(", $request['data']);
        $product_code[0] = rtrim($product_code[0], " ");

        // get the product using the code
        $product_data = Product::where([
            ['code', $product_code[0] ]
            // ['is_active', true]
        ])->first();

        if(!$product_data) {
            $product_data = Product::join('product_variants', 'products.id', 'product_variants.product_id')
                ->select('products.*', 'product_variants.item_code', 'product_variants.variant_id', 'product_variants.additional_price')
                ->where('product_variants.item_code', $product_code[0])
                ->first();

            $variant_id = $product_data->variant_id;
            $additional_price = $product_data->additional_price;
        }
        else {
            $variant_id = '';
            $additional_price = 0;
        }

        $product[] = $product_data->name;
        // if($product_data->is_variant)
        //     $product[] = $product_data->item_code;
        // else
            $product[] = $product_data->code;

        $product[] = $product_data->price + $additional_price;
        $product[] = DNS1D::getBarcodePNG($product_data->code, 'C128');
        // $product[] = $product_data->promotion_price;
        // $product[] = config('currency');
        // $product[] = config('currency_position');
        // $quantity =
        $product[] = 20;
        $product[] = $product_data->id;
        // $product[] = $variant_id;
        return $product;
    }

    public function delete($id)
    {
        if(! Auth::user()->can('delete product')){
            return redirect()->back()->with('error', 'You don\'t have permission to do this action!');
        }

        if(Product::find($id)->exists()){
            try{
                Product::destroy($id);
                return redirect()->back()->with('success','Product deleted successfully');
            }catch(Exception $e){
                return redirect()->back()->with('error',$e->getMessage());
            }

        }else{
            session()->flash('error', 'This Product doesn\'t exist!');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        if(!Auth::user()->can('edit product')){
            return redirect()->back()->with('error', 'You don\'t have permission to do this action!');
        }

        // find product
        $product = Product::find($id);
        if(!$product->exists()){
            return redirect()->back()->with('error','This Product doesn\'t exist!');
        }

        $categories = Category::all();
        $warehouses = Warehouse::all();
        $warehousesProducts = Warehouse_Product::where('product_id','=',$id)->with(['product','warehouse'])->get();
        // dd($warehouseProducts);
        $units = Unit::all();

        return view('products.edit',compact('categories','warehouses','units','product','warehousesProducts'));
    }


    public function update(Request $req,$id)
    {
        if(!auth()->user()->can('update product')){
            // return redirect()->back()->with('error', 'You don\'t have permission to do this action!');
            return 'a7ten';
        }
        // dd($req);
        // return;

        $product = Product::find($id);

        if(!$product)
        {
            return 'a7a';
            // return redirect()->back()->with('error','This product is not exist!');
        }

        $req->validate([
            'code' => 'required|unique:products,code,'.$id,
            'name' => 'required|unique:products,name,'.$id,
        ]);

        $file = $req->file('image');
        // return var_dump($file) ;
        $filename = '';
        // dd($file);

        if(!$file){
            $filename = $product->image;
        }

        if($file){
            $filename = time().$file->getClientOriginalName();
            Storage::disk('public')->putFileAs(
                'files/',
                $file,
                $filename
            );
        }


        //Display File Name
        // $file->getClientOriginalName();

        //Display File Extension
        // $file->getClientOriginalExtension();

        //Display File Real Path
        // $file->getRealPath();

        //Display File Mime Type
        // $file->getMimeType();

        //Move Uploaded File

        // $destinationPath = 'uploads';
        // $file->move($destinationPath,$file->getClientOriginalName());

        $product = Product::where('id','=',$id)->update([
            'code' => $req->code,
            'name' => $req->name,
            'category_id' => $req->category_id,
            'unit_id' => $req->unit_id,
            'cost' => $req->cost,
            'price' => $req->price,
            'alert_quantity' => $req->alert_quantity,
            'details' => $req->details,
            'image' => $filename,
        ]);

        // dd($product);

        $warehouses = $req->warehouse_ids;
        $quantities = $req->qtys;

        $warehousesProducts = Warehouse_Product::where('product_id','=',$id)->get();

        if (count($warehousesProducts) > 0) {
            Warehouse_Product::where('product_id','=',$id)->delete();
        }


        foreach($warehouses as $i => $wh )
        {
            Warehouse_Product::create([
                'product_id' => $id,
                'warehouse_id' => $wh,
                'qty' => $quantities[$i],
            ]);
        }

        return redirect()->route('products.index')->with('success','Product '. Product::find($id)->name .' updated successfully');
    }

}
