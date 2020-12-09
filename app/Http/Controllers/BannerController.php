<?php

namespace App\Http\Controllers;

use App\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['banners' => Banner::orderBy('id', 'DESC')->get()]);
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
        return response()->json(['banner' => Banner::create($request->all())]);
    }

    public function uploadFile(){
        if(request()->hasFile('img')) {
            $originName = str_replace(" ", "-", request()->file('img')->getClientOriginalName());
            
            $extension = request()->file('img')->getClientOriginalExtension();
            $fileName = pathinfo($originName, PATHINFO_FILENAME).'_'.time().'.'.$extension;

            request()->file('img')->move(public_path('img/banners/'), $fileName);
            
            request()->merge(['imgPath' => 'img/banners/'.$fileName]);

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
        return response()->json(['banner' => Banner::find($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $banner = Banner::find($id);
        $banner->fill($request->all())->save();

        return response()->json(['banner' => $banner]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Banner::find($id)->delete();
    }
}
