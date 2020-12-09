<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Key;

class KeyController extends Controller
{
    public function getKey($name){
        return response()->json(['key' => Key::where('name', $name)->first()->name]);
    }
}
