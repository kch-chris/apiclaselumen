<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Products;

class ProductController extends Controller
{
    public function index(){

        $products = Products::all();;
        
        return view('catalogs.products.index')->with('products',$products);

    }

    public function show($product)
    {
        $product = Products::where('productsID', '=' , $product)->firstOrFail();
        
        return view('catalogs.products.show')->with('product',$product);
    }


    public function create()
    {
        return view('catalogs.products.create');
    }

    public function store(Request $request)
    {
        try {
            $newProduct = new Products();

            $newProduct->name = $request->post('name');
            $newProduct->description = $request->post('description');
            $newProduct->price = $request->post('price');
            $newProduct->cost = $request->post('cost');
    
            $newProduct->save();
    
            return response()->json(['status'=> true,'message'=>'Productoo Creado']);

        } catch (\Throwable $th) {
            
            return response()->json(['status'=> false,'message'=>$th->getMessage()]);
        }

        

    }

    public function edit($product)
    {
        $product = Products::where('productsID', '=' , $product)->firstOrFail();
        // dd($product);
        return view('catalogs.products.edit')->with('product',$product);
    }

    public function update($productID,Request $request)
    {   
        try {
            $product = Products::where('productsID', '=' , $productID)->firstOrFail();

            $product->name = $request->post('name');
            $product->description = $request->post('description');
            $product->price = $request->post('price');
            $product->cost = $request->post('cost');

            $product->save();
    
            return response()->json(['status'=> true,'message'=>'Producto Actualizado']);

        } catch (\Throwable $th) {
            
            return response()->json(['status'=> false,'message'=>$th->getMessage()]);
        }

    }

    public function destroy($productID)
    {
        Products::destroy($productID);

        return redirect()->route('products.index');
    }
}
