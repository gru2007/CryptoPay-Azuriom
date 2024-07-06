<?php

namespace Azuriom\Plugin\Crypto;

use Azuriom\Models\User;
use Azuriom\Plugin\Shop\Cart\Cart;
use Azuriom\Plugin\Shop\Models\Payment;
use Azuriom\Plugin\Shop\Payment\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CryptoMethod extends PaymentMethod
{
    /**
     * The payment method id name.
     *
     * @var string
     */
    protected $id = 'cryptopay';

    /**
     * The payment method display name.
     *
     * @var string
     */
    protected $name = 'CryptoPay';

    public function startPayment(Cart $cart, float $amount, string $currency)
    {
        $payment = $this->createPayment($cart, $amount, $currency);

        $pay_id = $payment->id;
        $desc = $this->gateway->data['desc'];
        $hidden = $this->gateway->data['hidden'];
        
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Crypto-Pay-API-Token' => $this->gateway->data['private-key']
        ])->post('https://pay.crypt.bot/api/createInvoice', [
            'amount' => $amount,
            'payload' => strval($pay_id),
            'description' => $desc,
            'currency_type' => 'fiat',
            'fiat' => $currency,
            'hidden_message' => $hidden,
            'paid_btn_name' => 'callback',
            'paid_btn_url' => route('shop.payments.success', 'cryptopay'),
        ]);

        if($response->json('ok') == false){
            return response($response->json('error.name'), $response->json('error.code'));
        }

        return redirect()->away($response->json('result.web_app_invoice_url'));
    }

    public function notification(Request $request, ?string $paymentId)
    {
        
        /***    Проверка подписи     ***/
        $sign_header = $request->header('crypto-pay-api-signature');

        $secret_key = hash('sha256', $this->gateway->data['private-key'], true);
        $calculated_signature = hash_hmac('sha256', $request->getContent(), $secret_key);

        if (!hash_equals($sign_header, $calculated_signature)) {
            return response()->json(['status' => 'invalid sign.']);
        } 
        /***                         ***/

        $payment = Payment::findOrFail($request->json('payload.payload'));
        

        return $this->processPayment($payment, $request->input('payload.invoice_id'));

    }

   public function success(Request $request)
    {
        return redirect()->route('shop.home')->with('success', trans('messages.status.success'));
    }

    public function view()
    {
        return 'cryptopay::admin.crypto';
    }

    public function rules()
    {
        return [
            'private-key' => ['required', 'string'],
            'desc' => ['required', 'string'],
            'hidden' => ['required', 'string'],
            'color' => ['required', 'int'],
        ];
    }

    public function image()
    {
        if(!isset($this->gateway->data['color'])) {
            return asset('plugins/cryptopay/img/cryptobot-black.svg');
        }

        if($this->gateway->data['color'] == 1){
            return asset('plugins/cryptopay/img/cryptobot-white.svg');
        } else {
            return asset('plugins/cryptopay/img/cryptobot-black.svg');
        }
    }

}
