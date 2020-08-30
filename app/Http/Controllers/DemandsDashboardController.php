<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UsersDemand;
use App\Product;
use App\DemandsProduct;
use App\User;
use Carbon\Carbon;

class DemandsDashboardController extends Controller
{
    public function demands($status){
        if($status == 'delivered'){
            return response()->json(['demands' => UsersDemand::where('status', $status)->whereDate('created_at', Carbon::today())->get()]);
        }

        return response()->json(['demands' => UsersDemand::where('status', $status)->get()]);
    }

    public function stats(){
        $totalValue = number_format(DemandsProduct::whereDate('created_at', Carbon::today())->sum('price'), 2);
        $totalDemands = UsersDemand::whereDate('created_at', Carbon::today())->count();
        $totalUsers = User::all()->count();
        $totalProducts = Product::all()->count();

        return response()->json([
            'totalValue' => $totalValue,
            'totalDemands' => $totalDemands,
            'totalUsers' => $totalUsers,
            'totalProducts' => $totalProducts,
        ]);
    }

    public function updateStatus($id, $status){
        $demand = UsersDemand::find($id);
        $demand->update(['status' => $status]);

        return response()->json(['demand' => $demand]);
    }
}
