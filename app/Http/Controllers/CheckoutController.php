<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UsersDemand;
use App\DemandsProduct;
use App\CreditPayment;
use App\Product;
use Auth;

class CheckoutController extends Controller
{
    //Funçao pra realizar o pagamento no mercado pago
    /*
    public function makeTransaction($request){
        $response = Http::post('https://api.mercadopago.com/v1/payments?access_token=TEST-7139957601757173-101101-378a355a80cc3e3b394aefc10124dd80-395095537', [
            'token' => $request->token, 
            'installments' => 1,
            'transaction_amount' => $request->transactionAmount,
            'description' => 'Pedido no DP APP',
            'payer' => [
                'email' => $request->email
            ]
        ]);

        return $response;
    }*/

    public function registerDemand(Request $request){

        if($request->payType == 'app'){
            $charge = $this->paymentProcess($request);

            if($charge['status'] != 'succeeded')
                return response()->json(['message' => 'Payment error'], 400);
        }
        
        //Registra o pedido
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
            'complement' => $request->complement,
            'charge_id' => $request->payType == 'app' ? $charge->id : null,
        ]);

        $items = $request->items;

        $createdDemandProducts = [];
        $outOfQtd = false;

        foreach($items as $item){
            $product = Product::find($item['product']['id']);
            if($product->qtd < $item['qtd']){
                $outOfQtd = true;
                break;
            }
            $product->qtd -= $item['qtd'];
            $product->save();

            $demandProduct = DemandsProduct::create([
                'users_demand_id' => $userDemand->id,
                'product_id' => $item['product']['id'],
                'price' => $item['product']['price'],
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

        //Se for pagamento pelo app e tiver sido aprovado, registra o pagamento no banco
        if($request->payType == 'app'){
            CreditPayment::create([
                'users_demand_id' => $userDemand->id, 
                'status' => 'succeeded', 
                'amount' => $request->amount
            ]);
        }

        return response()->json(['userDemand' => $userDemand]);
    }

    public function paymentProcess($request){
        \Stripe\Stripe::setApiKey('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');

        $charge = \Stripe\Charge::create([
            'amount' => $request->amount * 100,
            'currency' => 'brl',
            'description' => 'Pedido DP APP #'.random_int(0, 999999999).' do cliente '.Auth::user()->name,
            'source' => $request->token,
        ]);

        return $charge;
    }
}
