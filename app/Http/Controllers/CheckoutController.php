<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UsersDemand;
use App\DemandsProduct;
use Auth;

class CheckoutController extends Controller
{
    public function registerDemand(Request $request){

        $userDemand = UsersDemand::create([
            'user_id' => Auth::user()->id,
            'status' => 'received',
            'payment_type' => $request->payType,
            'cep' => $request->cep,
            'state' => $request->state,
            'city' => $request->city,
            'neighborhood' => $request->neighborhood,
            'street' => $request->street,
            'number' => $request->number,
            'complement' => $request->complement
        ]);

        $items = $request->items;

        foreach($items as $item){
            DemandsProduct::create([
                'users_demand_id' => $userDemand->id,
                'product_id' => $item['product']['id'],
                'price' => $item['product']['price'],
            ]);
        }

        return response()->json(['userDemand' => $userDemand]);
    }
}
