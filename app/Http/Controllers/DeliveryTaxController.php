<?php

namespace App\Http\Controllers;

use App\DeliveryTax;
use Illuminate\Http\Request;

class DeliveryTaxController extends Controller
{
    public function getTax(){
        return response()->json(['tax' => DeliveryTax::first()->value]);
    }
}
