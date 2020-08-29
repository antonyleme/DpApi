<?php

namespace App\Http\Controllers;

use App\UsersDemand;
use App\User;
use Auth;
use Illuminate\Http\Request;

class UsersDemandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['demands' => UsersDemand::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return response()->json(['demand' => UsersDemand::create($request->all())]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(['demand' => UsersDemand::find($id)]);
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
        $usersDemand = UsersDemand::find($id);
        $usersDemand->update($request->all());

        return response()->json(['demand' => $usersDemand]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        UsersDemand::find($id)->delete();
    }

    public function userDemands(){
        return response()->json(['demands' => Auth::user()->demands()->get()]);
    }
}
