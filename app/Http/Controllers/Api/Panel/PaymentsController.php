<?php

namespace App\Http\Controllers\Api\Panel;
use App\Http\Controllers\Controller;
use App\Models\Accounting;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentChannel;
use App\Models\ReserveMeeting;
use App\Models\Sale;
use App\Models\TicketUser;
use App\PaymentChannels\ChannelManager;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\URL;


class PaymentsController extends Controller
{
    protected $order_session_key;
    
    public function __construct()
    {
        $this->order_session_key = 'payment.order_id';
    }
    
    public function paymentByCredit(Request $request)
    {
        validateParam($request->all(), [
            'order_id' => ['required',
            Rule::exists('orders', 'id')->where('status', Order::$pending),
            
        ],
    ]);
    
    $user = apiAuth();
    $orderId = $request->input('order_id');
    
    $order = Order::where('id', $orderId)
    ->where('user_id', $user->id)
    ->first();
    
    
    if ($order->type === Order::$meeting) {
        $orderItem = OrderItem::where('order_id', $order->id)->first();
        $reserveMeeting = ReserveMeeting::where('id', $orderItem->reserve_meeting_id)->first();
        $reserveMeeting->update(['locked_at' => time()]);
    }
    
    if ($user->getAccountingCharge() < $order->amount) {
        $order->update(['status' => Order::$fail]);
        
            return apiResponse2(0, 'not_enough_credit', trans('api.payment.not_enough_credit'));
            
            
        }
        
        $order->update([
            'payment_method' => Order::$credit
        ]);
        
        $this->setPaymentAccounting($order, 'credit');
        
        $order->update([
            'status' => Order::$paid
        ]);
        
        return apiResponse2(1, 'paid', trans('api.payment.paid'));
        
    }
    
    
    public function paymentRequest(Request $request)
    {
        $user = apiAuth();
        validateParam($request->all(), [
            'gateway_id' => ['required',
            Rule::exists('payment_channels', 'id')
        ],
        'order_id' => ['required',
        Rule::exists('orders', 'id')->where('status', Order::$pending)
        ->where('user_id', $user->id),
            
        ],
        
        
    ]);


    $gateway = $request->input('gateway_id');
    $orderId = $request->input('order_id');
    
    $order = Order::where('id', $orderId)
    ->where('user_id', $user->id)
    ->first();


     if ($order->type === Order::$meeting) {
        $orderItem = OrderItem::where('order_id', $order->id)->first();
                $reserveMeeting = ReserveMeeting::where('id', $orderItem->reserve_meeting_id)->first();
                $reserveMeeting->update(['locked_at' => time()]);
            }
        
        
        $paymentChannel = PaymentChannel::where('id', $gateway)
        ->where('status', 'active')
        ->first();
        
        if (!$paymentChannel) {
            return apiResponse2(0, 'disabled_gateway', trans('api.payment.disabled_gateway'));
        }
        
        $order->payment_method = Order::$paymentChannel;
        $order->save();
        
        try {
            $channelManager = ChannelManager::makeChannel($paymentChannel);
            $redirect_url = $channelManager->paymentRequest($order);
            
            
            if (in_array($paymentChannel->class_name, ['Paytm', 'Payu', 'Zarinpal', 'Stripe', 'Paysera', 'Cashu', 'Iyzipay', 'MercadoPago'])) {
                
                return $redirect_url;
            }
            
            return $redirect_url;
            return Redirect::away($redirect_url);
            
        } catch (\Exception $exception) {
            
            if (!$paymentChannel) {
                return apiResponse2(0, 'gateway_error', trans('api.payment.gateway_error'));
            }
            
        }
    }

    
    public function paymentVerify(Request $request, $gateway)
    {
        $paymentChannel = PaymentChannel::where('class_name', $gateway)
        ->where('status', 'active')
        ->first();
        
        try {
            $channelManager = ChannelManager::makeChannel($paymentChannel);
            $order = $channelManager->verify($request);
            
            if (!empty($order)) {
                $orderItem = OrderItem::where('order_id', $order->id)->first();

                $reserveMeeting = null;
                if ($orderItem && $orderItem->reserve_meeting_id) {
                    $reserveMeeting = ReserveMeeting::where('id', $orderItem->reserve_meeting_id)->first();
                }
                
                if ($order->status == Order::$paying) {
                    $this->setPaymentAccounting($order);
                    
                    $order->update(['status' => Order::$paid]);
                } else {
                    if ($order->type === Order::$meeting) {
                        $reserveMeeting->update(['locked_at' => null]);
                    }
                }
                
                session()->put($this->order_session_key, $order->id);
                
                return redirect('/payments/status');
            } else {
                $toastData = [
                    'title' => trans('cart.fail_purchase'),
                    'msg' => trans('cart.gateway_error'),
                    'status' => 'error'
                ];
                
                return redirect('cart')->with($toastData);
            }
            
        } catch (\Exception $exception) {
            $toastData = [
                'title' => trans('cart.fail_purchase'),
                'msg' => trans('cart.gateway_error'),
                'status' => 'error'
            ];
            
            return redirect('cart')->with(['toast' => $toastData]);
        }
    }
    
    public function setPaymentAccounting($order, $type = null)
    {
        if ($order->is_charge_account) {
            Accounting::charge($order);
        } else {
            foreach ($order->orderItems as $orderItem) {
                $sale = Sale::createSales($orderItem, $order->payment_method);
                
                if (!empty($orderItem->reserve_meeting_id)) {
                    $reserveMeeting = ReserveMeeting::where('id', $orderItem->reserve_meeting_id)->first();
                    $reserveMeeting->update([
                        'sale_id' => $sale->id,
                        'reserved_at' => time()
                    ]);
                }
                
                if (!empty($orderItem->subscribe_id)) {
                    Accounting::createAccountingForSubscribe($orderItem, $type);
                } elseif (!empty($orderItem->promotion_id)) {
                    Accounting::createAccountingForPromotion($orderItem, $type);
                } elseif (!empty($orderItem->installment_payment_id)) {
                    Accounting::createAccountingForInstallmentPayment($orderItem, $type);

                    $this->updateInstallmentOrder($orderItem, $sale,$order->status);
                } else {
                    // webinar and meeting
                    
                    Accounting::createAccounting($orderItem, $type);
                    TicketUser::useTicket($orderItem);
                }
            }
        }
        
        Cart::emptyCart($order->user_id);
    }
    
    public function payStatus(Request $request)
    {
        $orderId = $request->get('order_id', null);
        
        if (!empty(session()->get($this->order_session_key, null))) {
            $orderId = session()->get($this->order_session_key, null);
            session()->forget($this->order_session_key);
        }
        
        $order = Order::where('id', $orderId)
        ->where('user_id', auth()->id())
        ->first();
        
        if (!empty($order)) {
            $data = [
                'pageTitle' => trans('public.cart_page_title'),
                'order' => $order,
            ];
            
            return view('web.default.cart.status_pay', $data);
        }
        
        abort(404);
    }
    
    public function webChargeGenerator(Request $request)
    {
        return apiResponse2(1, 'generated', trans('api.link.generated'),
        [
            'link' => URL::signedRoute('my_api.web.charge', [apiAuth()->id])
            ]
        );
        
    }
    
    public function webChargeRender(User $user)
    {
        Auth::login($user);
        return redirect('/panel/financial/account');
        
    }
    
    
    public function charge(Request $request)
    {
        validateParam($request->all(), [
            'amount' => 'required|numeric',
            'gateway_id' => ['required',
            Rule::exists('payment_channels', 'id')->where('status', 'active')
            ]
            ,
        ]);
        
        
        $gateway_id = $request->input('gateway_id');
        $amount = $request->input('amount');
        
        
        $userAuth = apiAuth();
        
        $paymentChannel = PaymentChannel::find($gateway_id);
        
        $order = Order::create([
            'user_id' => $userAuth->id,
            'status' => Order::$pending,
            'payment_method' => Order::$paymentChannel,
            'is_charge_account' => true,
            'total_amount' => $amount,
            'amount' => $amount,
            'created_at' => time(),
            'type' => Order::$charge,
        ]);
        
        
        OrderItem::updateOrCreate([
            'user_id' => $userAuth->id,
            'order_id' => $order->id,
        ], [
            'amount' => $amount,
            'total_amount' => $amount,
            'tax' => 0,
            'tax_price' => 0,
            'commission' => 0,
            'commission_price' => 0,
            'created_at' => time(),
        ]);
        
        
        if ($paymentChannel->class_name == 'Razorpay') {
            return $this->echoRozerpayForm($order);
        } else {
            $paymentController = new PaymentsController();
            
            $paymentRequest = new Request();
            $paymentRequest->merge([
                'gateway_id' => $paymentChannel->id,
                'order_id' => $order->id
            ]);
            
            return $paymentController->paymentRequest($paymentRequest);
        }
    }
    
    private function echoRozerpayForm($order)
    {
        $generalSettings = getGeneralSettings();
        
        echo '<form action="/payments/verify/Razorpay" method="get">
        <input type="hidden" name="order_id" value="' . $order->id . '">
        
        <script src="/assets/default/js/app.js"></script>
            <script src="https://checkout.razorpay.com/v1/checkout.js"
            data-key="' . env('RAZORPAY_API_KEY') . '"
            data-amount="' . (int)($order->total_amount * 100) . '"
            data-buttontext="product_price"
            data-description="Rozerpay"
            data-currency="' . currency() . '"
            data-image="' . $generalSettings['logo'] . '"
            data-prefill.name="' . $order->user->full_name . '"
            data-prefill.email="' . $order->user->email . '"
            data-theme.color="#43d477">
            </script>
            
            <style>
            .razorpay-payment-button {
                opacity: 0;
                visibility: hidden;
                }
            </style>
            
            <script>
                $(document).ready(function() {
                    $(".razorpay-payment-button").trigger("click");
                    })
                    </script>
                    </form>';
                    return '';
                }
                
    public function paymentOrderAfterVerify($order)
    {
                    
            if (!empty($order)) {
                        
                if ($order->status == Order::$paying) {
                    
                $this->setPaymentAccounting($order);
                $order->update(['status' => Order::$paid]);
                
            }elseif ($order->status == 'part') {
                $this->setPaymentAccounting($order);
                $orderItem = OrderItem::where('order_id', $order->id)->first();
                $InstallmentOrderPayment = InstallmentOrderPayment :: where('id', $orderItem->installment_payment_id)->first();
               
                if($order->total_amount >= $InstallmentOrderPayment->amount){
                    $order->update(['status' => Order::$paid]);
                    InstallmentOrderPayment :: where('id', $orderItem->installment_payment_id)->update([
                        'status'=> 'paid']);
                    }
                   
                } else {
                    if ($order->type === Order::$meeting) {
                        $orderItem = OrderItem::where('order_id', $order->id)->first();
                        
                        if ($orderItem && $orderItem->reserve_meeting_id) {
                            $reserveMeeting = ReserveMeeting::where('id', $orderItem->reserve_meeting_id)->first();
                            
                            if ($reserveMeeting) {
                                $reserveMeeting->update(['locked_at' => null]);
                            }
                        }
                    }
                }

            session()->put($this->order_session_key, $order->id);

            return true;
            // return redirect('/payments/status');
        } else {
            $toastData = [
                'title' => trans('cart.fail_purchase'),
                'msg' => trans('cart.gateway_error'),
                'status' => 'error'
            ];
           return false;
        }
    }
    
    private function handleMeetingReserveReward($user)
    {
        if ($user->isUser()) {
            $type = Reward::STUDENT_MEETING_RESERVE;
        } else {
            $type = Reward::INSTRUCTOR_MEETING_RESERVE;
        }
        
        $meetingReserveReward = RewardAccounting::calculateScore($type);
        
        RewardAccounting::makeRewardAccounting($user->id, $meetingReserveReward, $type);
    }
    
    private function updateProductOrder($sale, $orderItem)
    {
        $product = $orderItem->product;
        
        $status = ProductOrder::$waitingDelivery;
        
        if ($product and $product->isVirtual()) {
            $status = ProductOrder::$success;
        }
        
        ProductOrder::where('product_id', $orderItem->product_id)
        ->where(function ($query) use ($orderItem) {
            $query->where(function ($query) use ($orderItem) {
                $query->whereNotNull('buyer_id');
                $query->where('buyer_id', $orderItem->user_id);
            });
            
            $query->orWhere(function ($query) use ($orderItem) {
                $query->whereNotNull('gift_id');
                $query->where('gift_id', $orderItem->gift_id);
            });
        })
        ->update([
            'sale_id' => $sale->id,
            'status' => $status,
        ]);
        
        if ($product and $product->getAvailability() < 1) {
            $notifyOptions = [
                '[p.title]' => $product->title,
            ];
            sendNotification('product_out_of_stock', $notifyOptions, $product->creator_id);
        }
    }
    
    private function updateInstallmentOrder($orderItem, $sale ,$order_status)
    {
        // die("updateInstallmentOrder");
        $installmentPayment = $orderItem->installmentPayment;
        
        if (!empty($installmentPayment)) {
            $installmentOrder = $installmentPayment->installmentOrder;
            
            $installmentPayment->update([
                'sale_id' => $sale->id,
                'status' => $order_status == 'part'?$order_status:'paid',
            ]);
            
            /* Notification Options */
            $notifyOptions = [
                '[u.name]' => $installmentOrder->user->full_name,
                '[installment_title]' => $installmentOrder->installment->main_title,
                '[time.date]' => dateTimeFormat(time(), 'j M Y - H:i'),
                '[amount]' => handlePrice($installmentPayment->amount),
            ];
            
            if ($installmentOrder and $installmentOrder->status == 'paying' and $installmentPayment->type == 'upfront') {
                $installment = $installmentOrder->installment;
                
                if ($installment) {
                    if ($installment->needToVerify()) {
                        $status = 'pending_verification';
                        
                        sendNotification("installment_verification_request_sent", $notifyOptions, $installmentOrder->user_id);
                        sendNotification("admin_installment_verification_request_sent", $notifyOptions, 1); // Admin
                    } else {
                        $status = 'open';
                        
                        sendNotification("paid_installment_upfront", $notifyOptions, $installmentOrder->user_id);
                    }
                    
                    $installmentOrder->update([
                        'status' => $status
                    ]);
                    
                    if ($status == 'open' and !empty($installmentOrder->product_id) and !empty($installmentOrder->product_order_id)) {
                        $productOrder = ProductOrder::query()->where('installment_order_id', $installmentOrder->id)
                        ->where('id', $installmentOrder->product_order_id)
                        ->first();
                        
                        $product = Product::query()->where('id', $installmentOrder->product_id)->first();
                        
                        if (!empty($product) and !empty($productOrder)) {
                            $productOrderStatus = ProductOrder::$waitingDelivery;
                            
                            if ($product->isVirtual()) {
                                $productOrderStatus = ProductOrder::$success;
                            }
                            
                            $productOrder->update([
                                'status' => $productOrderStatus
                            ]);
                        }
                    }
                }
            }


            if ($installmentPayment->type == 'step') {
                sendNotification("paid_installment_step", $notifyOptions, $installmentOrder->user_id);
                sendNotification("paid_installment_step_for_admin", $notifyOptions, 1); // For Admin
            }
            
        }
    }
    
}