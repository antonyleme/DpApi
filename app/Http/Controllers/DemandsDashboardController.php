<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UsersDemand;
use App\Product;
use App\DemandsProduct;
use App\User;
use Carbon\Carbon;
use App\CreditPayment;
use App\Notification;
use App\DeliveryTax;

class DemandsDashboardController extends Controller
{
    public function demands($status){
        if($status == 'delivered' || $status == 'balcony'){
            return response()->json(['demands' => UsersDemand::where('status', $status)->whereDate('created_at', Carbon::today())->get()]);
        }

        return response()->json(['demands' => UsersDemand::where('status', $status)->get()]);
    }

    public function stats(){
        $totalValue = 0;
        $demands = DemandsProduct::whereDate('demands_products.created_at', Carbon::today())
                        ->join('users_demands', 'users_demands.id', 'demands_products.users_demand_id')
                        ->where('users_demands.status', '<>', 'refused')
                        ->get();
        
        $deliveryTax = DeliveryTax::first()->value;

        foreach($demands as $demand){
            $totalValue += $demand->price * $demand->qtd;

            if($demand->status != 'balcony'){
                $totalValue += $deliveryTax;
            }
        }
        
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

        if($status == 'refused'){
            $products = $demand->products()->get();

            foreach($products as $demandProduct){
                $product = Product::find($demandProduct->id);
                $product->qtd += $demandProduct->pivot->qtd;
                $product->save();
            }
        }

        //Se o pedido for recusado e tiver sido pago pelo cartão, ele faz o reembolso do pagamento no cartão
        if($status == 'refused' && $demand->payment_type == 'app'){
            $refund = $this->refundProcess($demand->charge_id);

            if($refund['status'] != 'succeeded')
                return reponse()->json(['message' => 'Erro ao reembolsar pagamento.']);
            
            CreditPayment::where('users_demand_id', $demand->id)->update(['status' => 'Refunded']);

        }

        $demand->update(['status' => $status]);
        
        Notification::sendToUser($demand->user_id, $status);

        return response()->json(['demand' => $demand]);
    }

    public function refundProcess($charge_id){
        \Stripe\Stripe::setApiKey('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');

        $refund = \Stripe\Refund::create([
            'charge' => $charge_id,
        ]);

        return $refund;
    }

    public function storeBalconySale(Request $request){
        /*$userDemand = UsersDemand::create([
            'status' => 'balcony',
            'payment_type' => 'balcony'
        ]);*/

        //$userDemand = UsersDemand::create($request->all());

        $userDemand = UsersDemand::create([
            'status' => $request->status,
            'client_name' => $request->client_name,
            'payment_type' => $request->payment_type,
            'cep' => $request->cep,
            'state' => $request->state,
            'city' => $request->city,
            'neighborhood' => $request->neighborhood,
            'street' => $request->street,
            'number' => $request->number,
            'complement' => $request->complement,
            'charge_id' => $request->payment_type == 'app' ? $charge->id : null,
            'charge_for' => $request->charge_for ? $request->charge_for : 0,
        ]);

        $items = $request->items;

        $createdDemandProducts = [];
        $outOfQtd = false;

        foreach($items as $item){
            $product = Product::find($item['id']);
            if($product->qtd < $item['qtd']){
                $outOfQtd = true;
                break;
            }
            $product->qtd -= $item['qtd'];
            $product->save();

            $demandProduct = DemandsProduct::create([
                'users_demand_id' => $userDemand->id,
                'product_id' => $item['id'],
                'price' => $item['qtd'] >= 10 ? $item['promo_price'] : $item['price'],
                'qtd' => $item['qtd'],
            ]);
            $createdDemandProducts[] = $demandProduct;
        }

        if($outOfQtd){
            foreach($createdDemandProducts as $createdDemandProduct){
                $product = Product::find($createdDemandProduct->product_id);
                $product->qtd += $createdDemandProduct->qtd;
                $product->save();
                $createdDemandProduct->delete();
            }

            $userDemand->delete();

            return response()->json(['error' => 'out of qtd', 'message' => 'A quantidade de algum dos produtos selecionados é maior do que a que temos em estoque.'], 400);
        }

        return response()->json(['demand' => $userDemand]);
    }
}
