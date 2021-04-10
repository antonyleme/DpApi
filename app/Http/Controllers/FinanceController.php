<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UsersDemand;
use App\Bill;
use Carbon\Carbon;
use App\DeliveryTax;

class FinanceController extends Controller
{
    public function stats(){
        $demands = UsersDemand::where('status', '<>', 'refused')
                                ->whereMonth('created_at', Carbon::now()->month)
                                ->whereYear('created_at', Carbon::now()->year)
                                ->get();

        $totalReceived = 0;

        $totalApp = 0;
        $totalBalcony = 0;

        foreach($demands as $demand){
            foreach($demand->products()->get() as $product){
                $totalReceived += $product->pivot->price * $product->pivot->qtd;
                if($demand->payment_type == 'balcony'){
                    $totalBalcony += $product->pivot->price * $product->pivot->qtd;
                } else {
                    $totalApp += $product->pivot->price * $product->pivot->qtd + DeliveryTax::first()->value;
                }
            }
            if($demand->payment_type != 'balcony'){
                $totalReceived += DeliveryTax::first()->value;
            }
        }

        $totalBills = Bill::whereMonth('created_at', Carbon::now()->month)
                            ->whereYear('created_at', Carbon::now()->year)
                            ->sum('value');

        return response()->json(['totalReceived' => $totalReceived, 'totalApp' => $totalApp, 'totalBalcony' => $totalBalcony, 'totalBills' => $totalBills]);
    }

    public function statsByDate($date){
        $demands = UsersDemand::where('status', '<>', 'refused')
                                ->whereDate('created_at', $date)
                                ->get();

        $totalReceived = 0;

        $totalApp = 0;
        $totalBalcony = 0;

        foreach($demands as $demand){
            foreach($demand->products()->get() as $product){
                $totalReceived += $product->pivot->price * $product->pivot->qtd;
                if($demand->payment_type == 'balcony'){
                    $totalBalcony += $product->pivot->price * $product->pivot->qtd;
                } else {
                    $totalApp += $product->pivot->price * $product->pivot->qtd;
                }
            }
            if($demand->payment_type != 'balcony'){
                $totalReceived += DeliveryTax::first()->value;
            }
        }

        $totalBills = Bill::whereMonth('created_at', Carbon::parse($date)->month)
                            ->whereYear('created_at', Carbon::parse($date)->year)
                            ->sum('value');

        return response()->json(['totalReceived' => $totalReceived, 'totalApp' => $totalApp, 'totalBalcony' => $totalBalcony, 'totalBills' => $totalBills]);
    }
    
    public function statsByMonth($month, $year){
        $demands = UsersDemand::where('status', '<>', 'refused')
                                ->whereMonth('created_at', $month)
                                ->whereYear('created_at', $year)
                                ->get();

        $totalReceived = 0;

        $totalApp = 0;
        $totalBalcony = 0;

        foreach($demands as $demand){
            foreach($demand->products()->get() as $product){
                $totalReceived += $product->pivot->price * $product->pivot->qtd;
                if($demand->payment_type == 'balcony'){
                    $totalBalcony += $product->pivot->price * $product->pivot->qtd;
                } else {
                    $totalApp += $product->pivot->price * $product->pivot->qtd;
                }
            }
            if($demand->payment_type != 'balcony'){
                $totalReceived += DeliveryTax::first()->value;
            }
        }

        $totalBills = Bill::whereMonth('created_at', $month)
                            ->whereYear('created_at', $year)
                            ->sum('value');

        return response()->json(['totalReceived' => $totalReceived, 'totalApp' => $totalApp, 'totalBalcony' => $totalBalcony, 'totalBills' => $totalBills]);
    }
}
