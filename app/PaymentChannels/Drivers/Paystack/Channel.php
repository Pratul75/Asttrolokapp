<?php

namespace App\PaymentChannels\Drivers\Paystack;

use Anand\LaravelPaytmWallet\Facades\PaytmWallet;
use App\Models\Order;
use App\Models\PaymentChannel;
use App\PaymentChannels\IChannel;
use Illuminate\Http\Request;
use Unicodeveloper\Paystack\Paystack;

class Channel implements IChannel
{
    protected $currency;

    /**
     * Channel constructor.
     * @param PaymentChannel $paymentChannel
     */
    public function __construct(PaymentChannel $paymentChannel)
    {
        $this->currency = currency();
    }

    public function paymentRequest(Order $order)
    {
        $payStack = new Paystack();

        $payStack->getAuthorizationResponse([
            "amount" => $order->total_amount * 1000,
            "reference" => $payStack->genTranxRef(),
            "email" => $order->user->email,
            "callback_url" => $this->makeCallbackUrl($order),
            'metadata' => json_encode(['transaction' => $order->id]),
            'currency' => $this->currency
        ]);

        return $payStack->url;
    }

    private function makeCallbackUrl(Order $order)
    {
        $callbackUrl = route('payment_verify', [
            'gateway' => 'Paystack'
        ]);

        return $callbackUrl;
    }

    public function verify(Request $request)
    {
        $payStack = new Paystack();
        $payment = $payStack->getPaymentData();

        $order = order::where('id', $payment['data']['metadata']['transaction'])->first();

        if (!empty($order)) {
            if (isset($payment['status']) && $payment['status'] == true) {
                $order->update(['status' => Order::$paying]);
            } else {
                $order->update(['status' => Order::$fail]);
            }
        }

        return $order;
    }
}
