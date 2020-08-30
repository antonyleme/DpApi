<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;

class UserController extends Controller
{
    public function index(){
        return response()->json(['users' => User::all()]);
    }

    public function store(Request $request){
        $user = User::where('email', $request->email)->first();
        if($user) return response()->json(['message' => 'Invalid email'], 500);
        $user = User::create($request->all());
        $token = auth()->login($user);
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function update(Request $request){
        Auth::user()->update($request->all());

        return response()->json(['user' => Auth::user()]);
    }
}
