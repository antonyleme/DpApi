<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['products' => Product::orderBy('id', 'DESC')->get()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->uploadFile();
        return response()->json(['product' => Product::create($request->all())]);
    }

    public function uploadFile(){
        if(request()->hasFile('img')) {
            $originName = str_replace(" ", "-", request()->file('img')->getClientOriginalName());
            
            $extension = request()->file('img')->getClientOriginalExtension();
            $fileName = pathinfo($originName, PATHINFO_FILENAME).'_'.time().'.'.$extension;

            request()->file('img')->move(public_path('img/'), $fileName);
            
            request()->merge(['imgPath' => 'img/'.$fileName]);

            return true;
        }

        return false;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(['product' => Product::find($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //return $request;
        $this->uploadFile();
        $product = Product::find($request->id);
        $product->fill($request->all())->save();

        return response()->json(['product' => $product]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::find($id)->delete();
    }

    public function productEntry(Request $request, $id){
        $product = Product::find($id);
        $product->qtd += $request->qtd;
        $product->save();

        return response()->json(['product' => $product]);
    }
}
