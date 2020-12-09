<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Http;
use App\User;

class Notification extends Model
{
    protected $fillable = ['title', 'body', 'total_receivers'];

    public static function sendToUser($userId, $status){
        $user = User::find($userId);

        if($status == 'accepted'){
            $title = 'O seu pedido foi aceito!';
            $message = 'Já estamos preparando o seu pedido!';
        } else if($status == 'refused') {
            $title = 'Não podemos te atender agora.';
            $message = 'Se você fez o pagamento online, em breve estornaremos o valor no seu cartão.';
        } else {
            return;
        }

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Accept-encoding' => 'gzip, deflate',
            'Content-Type' => 'application/json',
        ])->post('https://exp.host/--/api/v2/push/send', [
            json_encode([
                'to' => $user->notification_token,
                'sound' => 'default',
                'title' => $title,
                'body' => $message,
                'data' => json_encode(['status' => $status]),
            ]),
        ]);
    }
}
