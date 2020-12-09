<?php

namespace App\Http\Controllers;

use App\Bill;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function notPaidBills()
    {
        return response()->json(['bills' => Bill::where('paid', 0)->get()]);
    }

    public function store(Request $request)
    {
        return response()->json(['bill' => Bill::create($request->all())]);
    }

    public function monthBills($month, $year)
    {
        return response()->json(['bills' => Bill::where('paid', 1)->whereMonth('due', $month)->whereYear('due', $year)->get()]);
    }

    public function setPaid($id)
    {
        $bill = Bill::find($id);
        $bill->paid = 1;
        $bill->save();
        
        return response()->json(['bill' => $bill]);
    }

    public function destroy($id)
    {
        Bill::find($id)->delete();
    }
}
