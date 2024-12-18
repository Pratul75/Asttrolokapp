<?php
namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\Cart;
use App\Models\CartInstallment;
use App\Models\Installment;
use App\Models\InstallmentOrder;
use App\Models\InstallmentOrderAttachment;
use App\Models\InstallmentOrderPayment;
use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\RegistrationPackage;
use App\Models\Subscribe;
use Illuminate\Support\Facades\DB;
use App\Models\Accounting;
use App\Models\PaymentChannel;
use App\Models\Webinar;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\WebinarPartPayment;
use App\Mixins\Installment\InstallmentPlans;
use App\Models\Sale;
use App\Http\Controllers\Api\Panel\PaymentsController;
use App\User;
use Illuminate\Support\Facades\Hash;
use Jenssegers\Agent\Agent;
use App\Models\WebinarAccessControl;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\Traits\RegionsDataByUser;

class InstallmentsController extends Controller
{
    use RegionsDataByUser;
    
    public function index(Request $request, $installmentId)
    {
        
        $user = apiAuth();
        $itemId = $request->get('item');
        $itemType = $request->get('item_type');
        
        if (empty($user) or !$user->enable_installments) {
            $toastData = [
                'title' => trans('public.request_failed'),
                'msg' => trans('update.you_cannot_use_installment_plans'),
                'status' => 'error'
            ];
            return back()->with(['toast' => $toastData]);
        }
        
        if (!empty($itemId) and !empty($itemType) and getInstallmentsSettings('status')) {
            
            $item = $this->getItem($itemId, $itemType, null);
            
            // if (!empty($item)) {
                $installment = Installment::query()->where('id', $installmentId)
                ->where('enable', true)
                ->withCount([
                    'steps'
                    ])
                    ->first();
                    
                    if (!empty($installment)) {
                        
                        if (!$installment->hasCapacity()) {
                            $toastData = [
                                'title' => trans('public.request_failed'),
                                'msg' => trans('update.installment_not_capacity'),
                                'status' => 'error'
                            ];
                            return back()->with(['toast' => $toastData]);
                        }
                        
                        $itemPrice = $item->getPrice();
                        $price = $item->price;
                        if(!empty(session('discountCouponId'))){
                            $discountId=session('discountCouponId');
                            $discountCoupon = Discount::where('id', $discountId)->first();
                            $percent = $discountCoupon->percent ?? 1;
                            $totalDiscount = ($price > 0) ? $price * $percent / 100 : 0;
                            $itemPrice1=$itemPrice-$totalDiscount;
                        }else{
                $totalDiscount = 0;
                $itemPrice1=$itemPrice-$totalDiscount;
            }
            // $discount = $item->getDiscount($cart->ticket, $user);
            
            $hasPhysicalProduct = false;
            if ($itemType == 'product') {
                $quantity = $request->get('quantity', 1);
                $itemPrice = $itemPrice * $quantity;
                $hasPhysicalProduct = ($item->type == Product::$physical);
            }
            $installmentPlans = new InstallmentPlans();
            $installments = $installmentPlans->getPlans('courses', $item->id, $item->type, $item->category_id, $item->teacher_id);
            
            
            
            $plansCount = $item->count();
            $minimumAmount = 0;
            foreach ($installments as $installment) {
                    if ($minimumAmount == 0 or $minimumAmount > $installment->totalPayments($itemPrice)) {
                        $minimumAmount = $installment->totalPayments($itemPrice);
                    }
                }
                $paymentChannels = PaymentChannel::where('status', 'active')->get();
                
                $data = [
                    'pageTitle' => trans('update.verify_your_installments'),
                    'installment' => $installment,
                    'installments' => $installments,
                    'overviewTitle' => $item->title,
                    'itemPrice' => $itemPrice1,
                    'itemType' => $itemType,
                    'itemId' => $item->id,
                    'item' => $item,
                    'cash' => $itemPrice,
                    'plansCount' => $plansCount,
                    'hasPhysicalProduct' => $hasPhysicalProduct,
                    'totalDiscount' => $totalDiscount,
                    'discountId' => !empty($discountId) ? $discountId : null,
                    'minimumAmount' => $minimumAmount,
                    'paymentChannels' => $paymentChannels,
                    
                ];
                
                session(['success'=>false]);
                return apiResponse2(1, 'checkout', trans('api.cart.checkout'), $data);
            }
        }
        // }
        
        abort(404);
    }
    
    public function partPayment(Request $request, $slug)
    {
        
        $user = apiAuth();
        
        $course = Webinar::where('slug', $slug)
        ->where('status', 'active')
        ->first();
        $itemId = $course->id;
        $itemType = $course->type;
        
        $installmentPlans = new InstallmentPlans();
        $installments = $installmentPlans->getPlans('courses', $course->id, $course->type, $course->category_id, $course->teacher_id);
        
        $installmentId = $installments[0]->id;
       
        if (!empty($itemId) and !empty($itemType) and getInstallmentsSettings('status')) {

            $item = $this->getItem($itemId, $itemType, null);
            
            // if (!empty($item)) {
                $installment = Installment::query()->where('id', $installmentId)
                ->where('enable', true)
                ->withCount([
                    'steps'
                    ])
                    ->first();
                    
                    if (!empty($installment)) {
                        
                        if (!$installment->hasCapacity()) {
                            $toastData = [
                                'title' => trans('public.request_failed'),
                                'msg' => trans('update.installment_not_capacity'),
                                'status' => 'error'
                            ];
                            return back()->with(['toast' => $toastData]);
                        }
                        
                        $itemPrice = $item->getPrice();
                        $price = $item->price;
                        if(!empty(session('discountCouponId'))){
                            $discountId=session('discountCouponId');
                            $discountCoupon = Discount::where('id', $discountId)->first();
                            $percent = $discountCoupon->percent ?? 1;
                            $totalDiscount = ($price > 0) ? $price * $percent / 100 : 0;
                            $itemPrice1=$itemPrice-$totalDiscount;
                        }else{
                            $totalDiscount = 0;
                            $itemPrice1=$itemPrice-$totalDiscount;
                        }
                        // $discount = $item->getDiscount($cart->ticket, $user);
                        
                        $hasPhysicalProduct = false;
                        if ($itemType == 'product') {
                            $quantity = $request->get('quantity', 1);
                        $itemPrice = $itemPrice * $quantity;
                        $hasPhysicalProduct = ($item->type == Product::$physical);
                    }
                   $installmentPlans = new InstallmentPlans();
                   $installments = $installmentPlans->getPlans('courses', $item->id, $item->type, $item->category_id, $item->teacher_id);

                   $plansCount = $item->count();
                   $minimumAmount = 0;
                   foreach ($installments as $installment) {
                       if ($minimumAmount == 0 or $minimumAmount > $installment->totalPayments($itemPrice)) {
                           $minimumAmount = $installment->totalPayments($itemPrice);
                        }
                    }
                    $paymentChannels = PaymentChannel::where('status', 'active')->get();
                    
                    $data = [
                        'pageTitle' => trans('update.verify_your_installments'),
                        'installment' => $installment,
                        'installments' => $installments,
                        'overviewTitle' => $item->title,
                        'itemPrice' => $itemPrice1,
                        'itemType' => $itemType,
                        'itemId' => $item->id,
                        'item' => $item,
                        'cash' => $itemPrice,
                        'plansCount' => $plansCount,
                        'hasPhysicalProduct' => $hasPhysicalProduct,
                        'totalDiscount' => $totalDiscount,
                        'discountId' => !empty($discountId) ? $discountId : null,
                        'minimumAmount' => $minimumAmount,
                        'paymentChannels' => $paymentChannels,
                        'mayank' => '1',
                    ];
                    
                    session(['success'=>false]);
                    $agent = new Agent();
                    if ($agent->isMobile()){
                        return view(getTemplate() . '.installment.partPayment.plans', $data);
                    }else{
                        return view('web.default2' . '.installment.partPayment.plans', $data);
                    }
                   
                }
            }
            // }

            abort(404);
        }
    
    public function index1(Request $request, $installmentId)
      {
            $user = auth()->user();
            $itemId = $request->get('item');
            $itemType = $request->get('item_type');
            
            if (empty($user) or !$user->enable_installments) {
                $toastData = [
                    'title' => trans('public.request_failed'),
                    'msg' => trans('update.you_cannot_use_installment_plans'),
                    'status' => 'error'
                ];
                return back()->with(['toast' => $toastData]);
            }
            
            if (!empty($itemId) and !empty($itemType) and getInstallmentsSettings('status')) {
                
                $item = $this->getItem($itemId, $itemType, $user);
                
                if (!empty($item)) {
                    $installment = Installment::query()->where('id', $installmentId)
                    ->where('enable', true)
                    ->withCount([
                        'steps'
                        ])
                        ->first();
                        
                        if (!empty($installment)) {
                            
                            if (!$installment->hasCapacity()) {
                                $toastData = [
                                    'title' => trans('public.request_failed'),
                                    'msg' => trans('update.installment_not_capacity'),
                                    'status' => 'error'
                                ];
                                return back()->with(['toast' => $toastData]);
                            }
                            
                            $itemPrice = $item->getPrice();
                            $price = $item->price;
                            if(!empty(session('discountCouponId'))){
                                $discountId=session('discountCouponId');
                                $discountCoupon = Discount::where('id', $discountId)->first();
                                $percent = $discountCoupon->percent ?? 1;
                                $totalDiscount = ($price > 0) ? $price * $percent / 100 : 0;
                                $itemPrice1=$itemPrice-$totalDiscount;
                            }else{
                                $totalDiscount = 0;
                $itemPrice1=$itemPrice-$totalDiscount;
            }
            // $discount = $item->getDiscount($cart->ticket, $user);
            
                    $hasPhysicalProduct = false;
                    if ($itemType == 'product') {
                        $quantity = $request->get('quantity', 1);
                        $itemPrice = $itemPrice * $quantity;
                        $hasPhysicalProduct = ($item->type == Product::$physical);
                    }
                    
                    $data = [
                        'pageTitle' => trans('update.verify_your_installments'),
                        'installment' => $installment,
                        'itemPrice' => $itemPrice1,
                        'itemType' => $itemType,
                        'item' => $item,
                        'hasPhysicalProduct' => $hasPhysicalProduct,
                        'totalDiscount' => $totalDiscount,
                        'discountId' => !empty($discountId) ? $discountId : null,
                    ];
                    
                    if ($hasPhysicalProduct) {
                        $data = array_merge($data, $this->getLocationsData($user));
                    }
                    
                    $agent = new Agent();
                    if ($agent->isMobile()){
                        return view(getTemplate() . '.installment.verify', $data);
                    }else{
                        return view('web.default2' . '.installment.verify', $data);
                    }
                 
                }
            }
        }

        abort(404);
    }

    public function getItem($itemId, $itemType, $user)
    {
       
        if ($itemType == 'course') {
            $course = Webinar::where('id', $itemId)
                ->where('status', 'active')
                ->first();
                
                // $hasBought = $course->checkUserHasBought($user);
                // $canSale = ($course->canSale() and !$hasBought);
                
                // if ($canSale and !empty($course->price)) {
                    return $course;
                    // }
                } else if ($itemType == 'bundles') {
                    $bundle = Bundle::where('id', $itemId)
                    ->where('status', 'active')
                    ->first();
                    
                    $hasBought = $bundle->checkUserHasBought($user);
                    $canSale = ($bundle->canSale() and !$hasBought);
                    
                    if ($canSale and !empty($bundle->price)) {
                        return $bundle;
                    }
                } elseif ($itemType == 'product') {
                    $product = Product::where('status', Product::$active)
                    ->where('id', $itemId)
                    ->first();
                    
                    $hasBought = $product->checkUserHasBought($user);
                    
                    if (!$hasBought and !empty($product->price)) {
                        return $product;
                    }
                } elseif ($itemType == 'registration_package') {
            $package = RegistrationPackage::where('id', $itemId)
            ->where('status', 'active')
            ->first();
            
            return $package;
        } elseif ($itemType == 'subscribe') {
            return Subscribe::where('id', $itemId)->first();
        }
        
        return null;
    }
    
    private function getColumnByItemType($itemType)
    {
        if ($itemType == 'course') {
            return 'webinar_id';
        } elseif ($itemType == 'product') {
            return 'product_id';
        } elseif ($itemType == 'bundles') {
            return 'bundle_id';
        } elseif ($itemType == 'subscribe') {
            return 'subscribe_id';
        } elseif ($itemType == 'registration_package') {
            return 'registration_package_id';
        }
    }
    
    public function store(Request $request)
    {
        $user = apiAuth();
        session(['success'=>true]);
        $itemId = $request->input('item');
        $itemType = $request->input('item_type');
        $totalDiscount= $request->input('totalDiscount');
        $discountId= $request->input('discount_id');
        $installmentId= $request->input('installment_id');
        $name = $request->input('name');
        $email = $request->input('email');
        $contact = $request->input('number');
        
        $payment_type ="";
        
        if($request->input('payment_type')){
            $payment_type = $request->input('payment_type');
            $amount = $request->input('amount');
            if($amount){
                $amount+=($itemId==2050?1:0);
            }
        }
        
            $item = $this->getItem($itemId, $itemType, $user);
            $itemPrice = $item->getPrice();
            if($totalDiscount)
            $itemPrice -= $totalDiscount;
        
        if(isset($amount)){
                if($amount >= $itemPrice){
                  
                    $order_main_table = Order::create([
                        'user_id' => $user->id,
                        'status' => Order::$paying,
                        'amount' => isset($amount)?$amount: $installment->getUpfront($order->getItemPrice()),
                        'tax' => 0,
                        'total_discount' => $totalDiscount,
                        'total_amount' => isset($amount)?$amount: $installment->getUpfront($order->getItemPrice()),
                        'product_delivery_fee' => null,
                        'created_at' => time(),
                    ]);
                    
                    
                    $discountCoupon = Discount::where('id', $discountId)->first();
                    
                    if (empty($discountCoupon) or $discountCoupon->checkValidDiscount() != 'ok') {
                        $discountCoupon = null;
                    }
                    
                    if($order_main_table){
                        
                        $order_item = OrderItem::create([
                            'user_id' => $user->id,
                            'order_id' => $order_main_table->id,
                            'webinar_id' => $itemId ?? null,
                            'bundle_id' => null,
                            'product_id' =>  null,
                            'product_order_id' =>null,
                            'reserve_meeting_id' => null,
                            'subscribe_id' =>null,
                            'promotion_id' => null,
                            'gift_id' =>null,
                            'installment_payment_id' => $installmentPayment->id ?? null,
                            'ticket_id' => null,
                            'discount_id' => !empty($discountId)?$discountId:($discountCoupon ? $discountCoupon->id : null),
                            'amount' =>  isset($amount)?$amount:  $installment->getUpfront($order->getItemPrice()),
                            'total_amount' =>  isset($amount)?$amount: $installment->getUpfront($order->getItemPrice()),
                            'tax' => 0,
                            'tax_price' => 0,
                            'commission' => 0,
                            'commission_price' => 0,
                            'product_delivery_fee' => 0,
                            'discount' => $totalDiscount,
                            'created_at' => time(),
                        ]);  
                    }
                    $sales_account=new PaymentsController();
                   $sales_account->paymentOrderAfterVerify($order_main_table);
                   return redirect('/payment/success');
            }else{
            
            $installment = Installment::query()->where('id', $installmentId)
            ->where('enable', true)
            ->withCount([
                'steps'
                ])
                ->first();
               
                if (!empty($installment)) {
                    if (!$installment->hasCapacity()) {
                        $toastData = [
                            'title' => trans('public.request_failed'),
                            'msg' => trans('update.installment_not_capacity'),
                            'status' => 'error'
                        ];
                        return back()->with(['toast' => $toastData]);
                    }
                  
                    // $this->validate($request, [
                        //     'item' => 'required',
                        //     'item_type' => 'required',
                        // ]);
                        
                        $data = $request->all();
                        $attachments = (!empty($data['attachments']) and count($data['attachments'])) ? array_map('array_filter', $data['attachments']) : [];
                        $attachments = !empty($attachments) ? array_filter($attachments) : [];
                        
                        if ($installment->request_uploads) {
                            if (count($attachments) < 1) {
                                return redirect()->back()->withErrors([
                                    'attachments' => trans('validation.required', ['attribute' => 'attachments'])
                                ]);
                            }
                        }
                        
                        if (!empty($installment->capacity)) {
                            $openOrdersCount = InstallmentOrder::query()->where('installment_id', $installment->id)
                            ->where('status', 'open')
                            ->count();
                            
                            if ($openOrdersCount >= $installment->capacity) {
                                $toastData = [
                                    'title' => trans('public.request_failed'),
                                    'msg' => trans('update.installment_not_capacity'),
                                    'status' => 'error'
                                ];
                                
                                return back()->with(['toast' => $toastData]);
                            }
                        }
                        
                        $item = $this->getItem($itemId, $itemType, $user);
                        
                        $installmentPlans = new InstallmentPlans($user);
                        $installments = $installmentPlans->getPlans('courses', $item->id, $item->type, $item->category_id, $item->teacher_id);
                        
                        // echo 'get installment plan <br>';
                        
                        $itemPrice = $item->getPrice();
                        $cash = $installments->sum('upfront');
                        $plansCount = $installments->count();
                        $minimumAmount = 0;
                        foreach ($installments as $installment) {
                            if ($minimumAmount == 0 or $minimumAmount > $installment->totalPayments($itemPrice)) {
                                $minimumAmount = $installment->totalPayments($itemPrice);
                            }
                        }
                     
                        if (!empty($item)) {
                            
                            $productOrder = null;
                            
                            if ($itemType == 'product') {
                                $hasPhysicalProduct = ($item->type == Product::$physical);
                                
                                $this->validate($request, [
                                    'country_id' => Rule::requiredIf($hasPhysicalProduct),
                                    'province_id' => Rule::requiredIf($hasPhysicalProduct),
                                    'city_id' => Rule::requiredIf($hasPhysicalProduct),
                                    'district_id' => Rule::requiredIf($hasPhysicalProduct),
                                    'address' => Rule::requiredIf($hasPhysicalProduct),
                                ]);
                                
                                /* Product Order */
                                $productOrder = $this->handleProductOrder($request, $user, $item);
                            }
                            
                            $columnName = $this->getColumnByItemType($itemType);
                            
                            $status = 'paying';
                            
                            if (empty($installment->upfront)) {
                                $status = 'open';
                                
                                if ($installment->needToVerify()) {
                                    $status = 'pending_verification';
                                }
                            }
                    
                            $order = InstallmentOrder:: where([
                                'installment_id' => $installment->id,
                        'user_id' => $user->id,
                        'webinar_id' => $item->id,
                        'status' => 'open',
                    ])->first();
                  
                    $itemPrice = $item->getPrice();
                    $itemPrice1=$itemPrice-$totalDiscount;
                   
                    if(!$order){
                    $order = InstallmentOrder::query()->updateOrCreate([
                        'installment_id' => $installment->id,
                        'user_id' => $user->id,
                        'discount' => $totalDiscount,
                        $columnName => $itemId,
                        'product_order_id' => !empty($productOrder) ? $productOrder->id : null,
                        'item_price' => $itemPrice1,
                        'status' => $status,
                    ], [
                        'created_at' => time(),
                    ]);
                }
                
                
          $part_amount_status=true;
          if (!empty($payment_type)) {
                    $status = $payment_type;
                    
                    
                    WebinarPartPayment::Create([
                        'user_id' => $user->id,
                        'installment_id' => $installmentId,
                        'webinar_id' => $itemId,
                        'amount' => $amount,
                    ]);
                  
                    $part_amount=0;
                    
                    // $this->shortPaymentSection($request,$user->id,$itemId);
                    $WebinarPartPayment =  WebinarPartPayment :: where('user_id',$user->id)
                    ->where('webinar_id',$itemId)
                    ->get();
                    
                    foreach ($WebinarPartPayment as $WebinarPartPayment1){
                        $part_amount = $part_amount + $WebinarPartPayment1->amount;
                    }
                    
                    if($order->status == 'open'){
                        // echo 'order status open';
                        
                        $orderPayments = InstallmentOrderPayment:: where(
                            'installment_order_id', $order->id)
                            ->get();
                            $totalSaleAmount=0;
                            
                            foreach($orderPayments as $orderPayment){
                                $saleId = $orderPayment->sale_id;
                                if($saleId){
                                    $sale = Sale :: where(['id' => $saleId ,
                                    'status' => null,])
                                    ->first();
                                    if($sale)
                                    $totalSaleAmount+=$sale->total_amount;
                            }
                            
                            
                        }
                      
                        $paidAmount=$totalSaleAmount+$part_amount;
                       
                        foreach($installments as $installment){
                            if($paidAmount > 0){
                           
                            $orderPayments1 = InstallmentOrderPayment:: where([
                                'type' => 'upfront' ,
                                'installment_order_id' => $order->id
                                ])->first();
                               
                                if($orderPayments1->status !='paid'){
                                  
                                    if($paidAmount >= $order->item_price*$installment->upfront/100){
                                      
                                        InstallmentOrderPayment:: where([
                                            'id' => $orderPayments1 ->id
                                            ])->update(['status'=>'paid']);
                                         
                                            $accounting = Accounting::where('installment_payment_id', $orderPayments1->id)
                                            ->where('user_id', $user->id)
                                            ->first();
                                            
                                            $OrderItem = OrderItem :: where('id',$accounting->order_item_id)
                                            ->first();
                                            
                                            OrderItem :: where('id',$OrderItem->id)
                                            ->update([
                                                'installment_type' => 'part' ?? null,
                                            ]);
                                        
                                            $order1 = Order :: where('id', $OrderItem->order_id)
                                            ->first();
                                            
                                            // $sales_account=new PaymentsController();
                                            // $sales_account->paymentOrderAfterVerify($order);
                                            
                                            
                                        }
                                    }
                                    
                            $paidAmount -=$order->item_price*$installment->upfront/100;
                            foreach($installment->steps as $steps){
                               
                                $orderPayments1 = InstallmentOrderPayment:: where([
                                    'step_id' => $steps->id,
                                    'installment_order_id' => $order->id,
                                    ])
                                    ->first();
                                    
                                    if($orderPayments1){
                                        // echo 'there is a step in installment order payment with id-'.$orderPayments1->id.;
                                        if($orderPayments1->status !='paid'){
                                            if($paidAmount >= $order->item_price*$steps->amount/100){
                                                $orderPayments1 -> update(['status'=>'paid']);
                                            
                                                $accounting = Accounting::where('installment_payment_id', $orderPayments1->id)
                                                ->where('user_id', $user->id)
                                                ->first();
                                              
                                            if(!$accounting){  
                                              
                                                $order_main_table = Order::create([
                                                'user_id' => $user->id,
                                                'status' => Order::$paying,
                                                'amount' =>$order->item_price*$steps->amount/100,
                                                'tax' => 0,
                                                'total_discount' => $totalDiscount,
                                                'total_amount' => $order->item_price*$steps->amount/100,
                                                'product_delivery_fee' => null,
                                                'created_at' => time(),
                                            ]);
                                            
                                            
                                            $discountCoupon = Discount::where('id', $discountId)->first();
                        
                                            if (empty($discountCoupon) or $discountCoupon->checkValidDiscount() != 'ok') {
                                                $discountCoupon = null;
                                            }
                                            
                                            if($order_main_table){
                                                
                                                $order_item = OrderItem::create([
                                                    'user_id' => $user->id,
                                                    'order_id' => $order_main_table->id,
                                                    'webinar_id' => $itemId ?? null,
                                                        'bundle_id' => null,
                                                        'product_id' =>  null,
                                                        'product_order_id' =>null,
                                                        'reserve_meeting_id' => null,
                                                        'subscribe_id' =>null,
                                                        'promotion_id' => null,
                                                        'gift_id' =>null,
                                                        'installment_payment_id' => $orderPayments1->id ?? null,
                                                        'installment_type' => 'part' ?? null,
                                                        'ticket_id' => null,
                                                        'discount_id' => !empty($discountId)?$discountId:($discountCoupon ? $discountCoupon->id : null),
                                                        'amount' =>  $order->item_price*$steps->amount/100,
                                                        'total_amount' =>  $order->item_price*$steps->amount/100,
                                                        'tax' => 0,
                                                        'tax_price' => 0,
                                                        'commission' => 0,
                                                        'commission_price' => 0,
                                                        'product_delivery_fee' => 0,
                                                        'discount' => $totalDiscount,
                                                        'created_at' => time(),
                                                    ]);  
                                                    }
                                                    $sales_account=new PaymentsController();
                                                    $sales_account->paymentOrderAfterVerify($order_main_table);
                                                    
                                                }else{
                                                Accounting :: where('id',$accounting->id)
                                                    ->update([
                                                    'total_amount' => $order->item_price*$steps->amount/100,
                                                ]);
                                                
                                                $OrderItem = OrderItem :: where('id',$accounting->order_item_id)
                                                    ->first();
                                                    
                                                    OrderItem :: where('id',$OrderItem->id)
                                                    ->update([
                                                        'total_amount' => $order->item_price*$steps->amount/100,
                                                        'installment_type' => 'part' ?? null,
                                                        
                                                    ]);
                                                    
                                                    $order = Order :: where('id', $OrderItem->order_id)
                                                    ->first();
                                                    
                                                    Order :: where('id',$order->id)
                                                    ->update([
                                                        'total_amount' => $order->item_price*$steps->amount/100,
                                                    ]);
                                                    
                                                // $sales_account=new PaymentsController();
                                                // $sales_account->paymentOrderAfterVerify($order);
                                                
                                                $sale =  Sale :: where('order_id',$order->id)->first();
                                                
                                                Sale ::  where('id',$sale->id)
                                                    ->update([
                                                        'total_amount' => $order->item_price*$steps->amount/100,
                                                    ]);
                                                    
                                                    $installmentPayment = InstallmentOrderPayment :: where('_id', $orderPayments1->id)
                                                    ->update([
                                                    'sale_id' => $sale->id,
                                                ]);
                                                
                                            }
                   
                                        }
                                        
                                        }
                                        $paidAmount -=$order->item_price*$steps->amount/100;
                                    
                                    }else{
                                    // create step in installment order payment
                                  
                                    $orderPayments1 = InstallmentOrderPayment:: create([
                                        'installment_order_id' => $order->id,
                                        'sale_id' => null,
                                        'type' => 'step',
                                        'step_id' => $steps->id,
                                        'amount' => $order->item_price*$steps->amount/100,
                                        'status' => 'paying',
                                        'created_at' => time(),
                                    ]);
                                  
                                    if($paidAmount >= $order->item_price*$steps->amount/100){
                                        $order_main_table = Order::create([
                                            'user_id' => $user->id,
                                            'status' => Order::$paying,
                                            'amount' =>$order->item_price*$steps->amount/100,
                                            'tax' => 0,
                                            'total_discount' => $totalDiscount,
                                            'total_amount' => $order->item_price*$steps->amount/100,
                                            'product_delivery_fee' => null,
                                            'created_at' => time(),
                                        ]);
                                        
                                        $discountCoupon = Discount::where('id', $discountId)->first();
                        
                                        if (empty($discountCoupon) or $discountCoupon->checkValidDiscount() != 'ok') {
                                            $discountCoupon = null;
                                        }
                                        
                                        if($order_main_table){
                                                                
                                                  $order_item = OrderItem::create([
                                                      'user_id' => $user->id,
                                                      'order_id' => $order_main_table->id,
                                                      'webinar_id' => $itemId ?? null,
                                                      'bundle_id' => null,
                                                      'product_id' =>  null,
                                                      'product_order_id' =>null,
                                                      'reserve_meeting_id' => null,
                                                      'subscribe_id' =>null,
                                                      'promotion_id' => null,
                                                      'gift_id' =>null,
                                                      'installment_payment_id' => $orderPayments1->id ?? null,
                                                      'installment_type' => 'part' ?? null,
                                                      'ticket_id' => null,
                                                      'discount_id' => !empty($discountId)?$discountId:($discountCoupon ? $discountCoupon->id : null),
                                                      'amount' =>  $order->item_price*$steps->amount/100,
                                                        'total_amount' =>  $order->item_price*$steps->amount/100,
                                                        'tax' => 0,
                                                        'tax_price' => 0,
                                                        'commission' => 0,
                                                        'commission_price' => 0,
                                                        'product_delivery_fee' => 0,
                                                        'discount' => $totalDiscount,
                                                        'created_at' => time(),
                                                    ]);  
                                                    }
                                                    $sales_account=new PaymentsController();
                                                    $sales_account->paymentOrderAfterVerify($order_main_table);
                                                    
                                                }
                                    
                                                $paidAmount -=$order->item_price*$steps->amount/100;
                                                
                                            }
                            }
                        }
                    }
                       return apiResponse2(1, 'success', 'installment request  successfully submitted',[$order]);
                    
                    }
                    
                    if($part_amount > $amount){
                        $part_amount_status=false;
                    }
    
         }
          if($order->status != 'open'){
     die('hi');
              /* Attachments */
              // $this->handleAttachments($attachments, $order);
              
              /* Update Product Order */
              if (!empty($productOrder)) {
                  $productOrder->update([
                      'installment_order_id' => $order->id
                    ]);
                }
                
                $notifyOptions = [
                    '[u.name]' => $order->user->full_name,
                    '[installment_title]' => $installment->main_title,
                    '[time.date]' => dateTimeFormat(time(), 'j M Y - H:i'),
                    '[amount]' => handlePrice($itemPrice),
                ];
                
                sendNotification("instalment_request_submitted", $notifyOptions, $order->user_id);
                sendNotification("instalment_request_submitted_for_admin", $notifyOptions, 1);
                
                /* Payment and Cart */
                if($part_amount_status){
                    if (!empty($installment->upfront)) {
                        $installmentPayment = InstallmentOrderPayment :: query()->updateOrCreate([
                            'installment_order_id' => $order->id,
                            'sale_id' => null,
                            'type' => 'upfront',
                            'step_id' => null,
                            'amount' => $installment->getUpfront($order->getItemPrice()),
                            'status' => $status,
                        ], [
                            'created_at' => time(),
                        ]);
                        
                        $order_main_table = Order::create([
                            'user_id' => $user->id,
                            'status' => ($status=='part')?$status:Order::$paying,
                            'amount' => isset($amount)?$amount: $installment->getUpfront($order->getItemPrice()),
                            'tax' => 0,
                            'total_discount' => $totalDiscount,
                            'total_amount' => isset($amount)?$amount: $installment->getUpfront($order->getItemPrice()),
                            'product_delivery_fee' => null,
                            'created_at' => time(),
                        ]);
                        
                        
                        $discountCoupon = Discount::where('id', $discountId)->first();
    
                        if (empty($discountCoupon) or $discountCoupon->checkValidDiscount() != 'ok') {
                            $discountCoupon = null;
                        }
                        
                        if($order_main_table){
                            
                            $order_item = OrderItem::create([
                                'user_id' => $user->id,
                                'order_id' => $order_main_table->id,
                                'webinar_id' => $itemId ?? null,
                                'bundle_id' => null,
                                'product_id' =>  null,
                                'product_order_id' =>null,
                                'reserve_meeting_id' => null,
                                'subscribe_id' =>null,
                                'promotion_id' => null,
                                'gift_id' =>null,
                                'installment_payment_id' => $installmentPayment->id ?? null,
                                'installment_type' => $status == 'part' ? $status : null,
                                'ticket_id' => null,
                                'discount_id' => !empty($discountId)?$discountId:($discountCoupon ? $discountCoupon->id : null),
                                'amount' =>  isset($amount)?$amount:  $installment->getUpfront($order->getItemPrice()),
                                'total_amount' =>  isset($amount)?$amount: $installment->getUpfront($order->getItemPrice()),
                                'tax' => 0,
                                'tax_price' => 0,
                                'commission' => 0,
                                'commission_price' => 0,
                                'product_delivery_fee' => 0,
                                'discount' => $totalDiscount,
                                'created_at' => time(),
                            ]);  
                        }
                        $sales_account=new PaymentsController();
                        $sales_account->paymentOrderAfterVerify($order_main_table);
                        
                        $this->shortPaymentSection($request,$user->id,$itemId);
                        return apiResponse2(1, 'success', 'installment request  successfully submitted',[$order_main_table]);
                    
                    } else {
                                        
                            if ($installment->needToVerify()) {
                            sendNotification("installment_verification_request_sent", $notifyOptions, $order->user_id);
                            sendNotification("admin_installment_verification_request_sent", $notifyOptions, 1); // Admin
                            
                           return apiResponse2(1, 'success', 'installment request  successfully submitted',[$order]);
                        } else {
                            sendNotification("approve_installment_verification_request", $notifyOptions, $order->user_id);
                            
                            return $this->handleOpenOrder($item, $productOrder);
                        }
                    }
            }else{
                    
                    $sale =  Sale :: where('buyer_id',$user->id)
                    ->where('webinar_id',$itemId)->first();
                    
                    Sale ::  where('id',$sale->id)
                    ->update([
                        'total_amount' => $part_amount,
                    ]);
                    
                    $order = Order :: where('id',$sale->order_id)
                    ->first();
                    
                    Order :: where('id',$order->id)
                    ->update([
                        'total_amount' => $part_amount,
                    ]);
                    
                    $OrderItem = OrderItem :: where('order_id',$order->id)
                    ->first();
                    
                    OrderItem :: where('id',$OrderItem->id)
                    ->update([
                        'total_amount' => $part_amount,
                        'installment_type' => 'part' ?? null,
                    ]);
                    
                    $accounting = Accounting::where('order_item_id', $OrderItem->id)
                    ->where('user_id', $user->id)
                    ->update([
                        'amount' => $part_amount,
                    ]);
                    
                    $installmentPayment = InstallmentOrderPayment :: where('sale_id', $sale->id)
                    ->first();
                    
                    if($installmentPayment->amount <= $part_amount){
                        $installmentPayment = InstallmentOrderPayment :: where('sale_id', $sale->id)
                        ->update([
                            'status' => 'paid',
                        ]);}
                        
                       return apiResponse2(1, 'success', 'installment request  successfully submitted',[$order]);
                        
                    }
                }
            }
        }
     }

    }
        else{
      
            $installment = Installment::query()->where('id', $installmentId)
            ->where('enable', true)
            ->withCount([
            'steps'
            ])
            ->first();
           
        if (!empty($installment)) {
            if (!$installment->hasCapacity()) {
                $toastData = [
                    'title' => trans('public.request_failed'),
                    'msg' => trans('update.installment_not_capacity'),
                    'status' => 'error'
                ];
                // return back()->with(['toast' => $toastData]);
                 return apiResponse2(0, trans('public.request_failed'), trans('update.installment_not_capacity'));
            }
            
                $data = $request->all();
                $attachments = (!empty($data['attachments']) and count($data['attachments'])) ? array_map('array_filter', $data['attachments']) : [];
                $attachments = !empty($attachments) ? array_filter($attachments) : [];
                
                if ($installment->request_uploads) {
                    if (count($attachments) < 1) {
                        // return redirect()->back()->withErrors([
                        //     'attachments' => trans('validation.required', ['attribute' => 'attachments'])
                        // ]);
                         return apiResponse2(0,"attachments",  trans('validation.required'));
                    }
                }
                
                if (!empty($installment->capacity)) {
                    $openOrdersCount = InstallmentOrder::query()->where('installment_id', $installment->id)
                    ->where('status', 'open')
                    ->count();
                    
                    if ($openOrdersCount >= $installment->capacity) {
                        $toastData = [
                            'title' => trans('public.request_failed'),
                            'msg' => trans('update.installment_not_capacity'),
                            'status' => 'error'
                        ];
                        
                        // return back()->with(['toast' => $toastData]);
                        return apiResponse2(0, trans('public.request_failed'), trans('update.installment_not_capacity'));
                    }
                }
                 
                $item = $this->getItem($itemId, $itemType, $user);
               
                $installmentPlans = new InstallmentPlans($user);
                $installments = $installmentPlans->getPlans('courses', $item->id, $item->type, $item->category_id, $item->teacher_id);
                
                $itemPrice = $item->getPrice();
                $cash = $installments->sum('upfront');
                $plansCount = $installments->count();
                $minimumAmount = 0;
                  foreach ($installments as $installment) {
                      if ($minimumAmount == 0 or $minimumAmount > $installment->totalPayments($itemPrice)) {
                          $minimumAmount = $installment->totalPayments($itemPrice);
                        }
                    }
                  
                    if (!empty($item)) {
                        
                        $productOrder = null;
                        
                        if ($itemType == 'product') {
                            $hasPhysicalProduct = ($item->type == Product::$physical);
                            
                            $this->validate($request, [
                                'country_id' => Rule::requiredIf($hasPhysicalProduct),
                                'province_id' => Rule::requiredIf($hasPhysicalProduct),
                                'city_id' => Rule::requiredIf($hasPhysicalProduct),
                                'district_id' => Rule::requiredIf($hasPhysicalProduct),
                                'address' => Rule::requiredIf($hasPhysicalProduct),
                            ]);
                            
                            /* Product Order */
                            $productOrder = $this->handleProductOrder($request, $user, $item);
                             
                        }
                        
                        $columnName = $this->getColumnByItemType($itemType);
                        
                        $status = 'paying';
                        
                        if (empty($installment->upfront)) {
                            $status = 'open';
                            
                            if ($installment->needToVerify()) {
                                $status = 'pending_verification';
                            }
                        }
                        //  print_r($status);die;
                        $order = InstallmentOrder:: where([
                            'installment_id' => $installment->id,
                            'user_id' => $user->id,
                            'webinar_id' => $item->id,
                            'status' => 'open',
                            ])->first();
                           
                            $itemPrice = $item->getPrice();
                            $itemPrice1=$itemPrice-$totalDiscount;
                   
                    if(!$order){
                        $order = InstallmentOrder::query()->updateOrCreate([
                            'installment_id' => $installment->id,
                            'user_id' => $user->id,
                            'discount' => $totalDiscount,
                            $columnName => $itemId,
                            'product_order_id' => !empty($productOrder) ? $productOrder->id : null,
                            'item_price' => $itemPrice1,
                            'status' => $status,
                        ], [
                            'created_at' => time(),
                        ]);
                    }
                     
                    $part_amount_status=true;
                    /* Update Product Order */
                    if (!empty($productOrder)) {
                        $productOrder->update([
                            'installment_order_id' => $order->id
                        ]);
                    }
                    
                    $notifyOptions = [
                        '[u.name]' => $order->user->full_name,
                    '[installment_title]' => $installment->main_title,
                    '[time.date]' => dateTimeFormat(time(), 'j M Y - H:i'),
                    '[amount]' => handlePrice($itemPrice),
                ];

                sendNotification("instalment_request_submitted", $notifyOptions, $order->user_id);
                sendNotification("instalment_request_submitted_for_admin", $notifyOptions, 1);
                
                /* Payment and Cart */
            if($part_amount_status){
                if (!empty($installment->upfront)) {
                    $installmentPayment = InstallmentOrderPayment :: query()->updateOrCreate([
                        'installment_order_id' => $order->id,
                        'sale_id' => null,
                        'type' => 'upfront',
                        'step_id' => null,
                        'amount' => $installment->getUpfront($order->getItemPrice()),
                        'status' => $status,
                    ], [
                        'created_at' => time(),
                    ]);
                    
                    $order_main_table = Order::create([
                        'user_id' => $user->id,
                        'status' => ($status=='part')?$status:Order::$paying,
                        'amount' => isset($amount)?$amount: $installment->getUpfront($order->getItemPrice()),
                        'tax' => 0,
                        'total_discount' => $totalDiscount,
                        'total_amount' => isset($amount)?$amount: $installment->getUpfront($order->getItemPrice()),
                        'product_delivery_fee' => null,
                        'created_at' => time(),
                    ]);
                    
                    $discountCoupon = Discount::where('id', $discountId)->first();

                    if (empty($discountCoupon) or $discountCoupon->checkValidDiscount() != 'ok') {
                        $discountCoupon = null;
                    }
                    
                    if($order_main_table){
                
                        $order_item = OrderItem::create([
                            'user_id' => $user->id,
                            'order_id' => $order_main_table->id,
                            'webinar_id' => $itemId ?? null,
                            'bundle_id' => null,
                            'product_id' =>  null,
                            'product_order_id' =>null,
                            'reserve_meeting_id' => null,
                            'subscribe_id' =>null,
                            'promotion_id' => null,
                            'gift_id' =>null,
                            'installment_payment_id' => $installmentPayment->id ?? null,
                            'installment_type' => $status == 'part' ? $status : null,
                            'ticket_id' => null,
                            'discount_id' => !empty($discountId)?$discountId:($discountCoupon ? $discountCoupon->id : null),
                            'amount' =>  isset($amount)?$amount:  $installment->getUpfront($order->getItemPrice()),
                            'total_amount' =>  isset($amount)?$amount: $installment->getUpfront($order->getItemPrice()),
                            'tax' => 0,
                            'tax_price' => 0,
                            'commission' => 0,
                            'commission_price' => 0,
                            'product_delivery_fee' => 0,
                            'discount' => $totalDiscount,
                            'created_at' => time(),
                            ]);  
                    }
                        $sales_account=new PaymentsController();
                        $sales_account->paymentOrderAfterVerify($order_main_table);
                        

                         return apiResponse2(1, 'success', 'installment request  successfully submitted',[$order_main_table]);
                
                } else {
                    
                    if ($installment->needToVerify()) {
                        sendNotification("installment_verification_request_sent", $notifyOptions, $order->user_id);
                        sendNotification("admin_installment_verification_request_sent", $notifyOptions, 1); // Admin
                         return apiResponse2(1, 'success', 'installment request  successfully submitted',[$order]);
                        // return redirect('/installments/request_submitted');
                    } else {
                        sendNotification("approve_installment_verification_request", $notifyOptions, $order->user_id);
                        
                        return $this->handleOpenOrder($item, $productOrder);
                    }
                }
            }else{
                
                $sale =  Sale :: where('buyer_id',$user->id)
                ->where('webinar_id',$itemId)->first();
                
                Sale ::  where('id',$sale->id)
                ->update([
                    'total_amount' => $part_amount,
                ]);
                
                $order = Order :: where('id',$sale->order_id)
                ->first();
                
                Order :: where('id',$order->id)
                ->update([
                    'total_amount' => $part_amount,
                ]);
                
                $OrderItem = OrderItem :: where('order_id',$order->id)
                ->first();
                
                OrderItem :: where('id',$OrderItem->id)
                ->update([
                    'total_amount' => $part_amount,
                    'installment_type' => 'part' ?? null,
                ]);
                
                $accounting = Accounting::where('order_item_id', $OrderItem->id)
                ->where('user_id', $user->id)
                ->update([
                    'amount' => $part_amount,
                ]);
                
                $installmentPayment = InstallmentOrderPayment :: where('sale_id', $sale->id)
                ->first();
                
                if($installmentPayment->amount <= $part_amount){
                    $installmentPayment = InstallmentOrderPayment :: where('sale_id', $sale->id)
                    ->update([
                        'status' => 'paid',
                    ]);}
                  
                     return apiResponse2(1, 'success', 'installment request  successfully submitted',[$order]);
                    
                }
            
            }
        }
    }
    
     return apiResponse2(0, 'failed', 'Item not found');
    }
    public function directAccess111(){
        $data = [
            ["babitasingh2308@gmail.com", 7535066577, 2069,100,'2024-09-10 09:49:04'],
            ["duttaaparna124@gmail.com", 7980683638, 2069,100,'2024-09-10 09:49:04'],
        ];
        
        foreach ($data as $data1){
         
                $email = $data1[0];
                $contact = $data1[1];
                $course_id = $data1[2];
                $percent = $data1[3];
                $date = $data1[4];
                if(!empty(User::where('email', $email)->orwhere('mobile', $contact)->first())){
                    $user = User::where('email', $email)->orwhere('mobile', $contact)->first();
                    
                    $WebinarAccessControl= WebinarAccessControl:: where([
                        'user_id' => $user->id,
                        'webinar_id' => $course_id,
                        ])->first(); 
                    if($WebinarAccessControl){
                        $WebinarAccessControl->update([
                            'percentage' => $percent,
                            'expire' => $date
                        ]);
                      
                    }else{
                        WebinarAccessControl::create([
                            'user_id' => $user->id,
                            'webinar_id' => $course_id,
                            'percentage' => $percent,
                            'expire' => $date
                        ]); 
                        
                    }
                }
        
        // }
    } 
}
    public function directAccessForm(){
        
        if (Auth::check() && Auth::user()->role_id ==2) {
            
            $users = User::all();
            $courses = Webinar::where('status', 'active')
            ->get();
            
            return view('web.default2' . '.cart.direct_access',compact('courses','users'));
        } else {
            return back();
        }
        
    }
    
    public function directAccess(Request $request)
    {
        
        $validatedData = $request->validate([
            'email' => 'required|email|max:255',
            'mobile' => 'required|string|max:15',
            'course' => 'required|string',
            'percentage' => 'required|numeric',
            'expire' => 'required|string',
        ]);
        
        if (!Auth::check() && Auth::user()->role_id !==2) {
            return back();
        }
        $data1 =$request->all();
        $email = $data1['email'];
        $contact = $data1['mobile'];
        $percentage = $data1['percentage'];
        $expire = $data1['expire'];
        //  session(['success'=>true]);
        $expirenew=$expire.' '.date('h:i:s');
        if(!empty(User::where('email', $email)->orwhere('mobile', $contact)->first())){
            $user = User::where('email', $email)->orwhere('mobile', $contact)->first();
        }else{
            $user = User::create([
                'role_name' => 'user',
                'role_id' => 1,
                'mobile' => $contact ?? null,
                'email' => $email ?? null,
                'full_name' => $name,
                // 'status' => User::$pending,
                'status'=>'active',
                'access_content' => 1,
                'password' => Hash::make(123456),
                'affiliate' => 0,
                'timezone' => 'Asia/Kolkata' ?? null,
                'created_at' => time(),
                'enable_installments' =>1
            ]); 
        }
        $itemId = $data1['course'];
        
        $data = [
            [$email, $contact, $itemId,$percentage,$expirenew],
        ];
        
        foreach ($data as $data1){
              
                $email = $data1[0];
                $contact = $data1[1];
                $course_id = $data1[2];
                $percent = $data1[3];
                $date = $data1[4];
                if(!empty(User::where('email', $email)->orwhere('mobile', $contact)->first())){
                    $user = User::where('email', $email)->orwhere('mobile', $contact)->first();
                    
                    $WebinarAccessControl= WebinarAccessControl:: where([
                        'user_id' => $user->id,
                        'webinar_id' => $course_id,
                        ])->first(); 
                        if($WebinarAccessControl){
                            $WebinarAccessControl->update([
                                'percentage' => $percent,
                                'expire' => $date
                            ]);
                            
                            return $response=['status' => 'success', 'message' => 'Data received successfully!'];
                        }else{
                            WebinarAccessControl::create([
                            'user_id' => $user->id,
                            'webinar_id' => $course_id,
                            'percentage' => $percent,
                            'expire' => $date
                        ]); 
            
            return $response=['status' => 'success', 'message' => 'Data received successfully!'];
        }
    }
    
    // }
    } 
    
    return $response=['status' => 'faild', 'message' => 'Data not received!'];
    }
    
    public function shortPaymentSection(Request $request,$userid,$item){

    $WebinarPartPayment = WebinarPartPayment :: select('user_id', 'webinar_id', 'installment_id', DB::raw('sum(amount) as total_amount'))
    ->where('user_id',$userid)
    ->where('webinar_id',$item)
    ->groupBy('user_id', 'webinar_id')
    ->first();
    
    // foreach ($WebinarPartPayments as $WebinarPartPayment){
        
    $order =InstallmentOrder::where([
        'installment_id' => $WebinarPartPayment->installment_id,
        'user_id' => $WebinarPartPayment->user_id,
        'webinar_id' => $WebinarPartPayment->webinar_id,
        'status' => 'open',
        ])->first();
      
        if(!$order){
            // return;
            // break;
        }
       
        $orderPayments = InstallmentOrderPayment :: where('installment_order_id', $order->id)
        ->get();
        
        $totalSaleAmount=0;
        
        foreach($orderPayments as $orderPayment){
            $saleId = $orderPayment->sale_id;
            if($saleId){
                $sale = Sale :: where(['id' => $saleId ,
                'status' => null,])
                ->first();
                if($sale)
                $totalSaleAmount+=$sale->total_amount;
        }
      
    }
    $paidAmount = $totalSaleAmount  + $WebinarPartPayment->total_amount;
    
    $user= User::where('id', $WebinarPartPayment->user_id)->first();
    
    $item = $this->getItem($WebinarPartPayment->webinar_id, 'course', $user);
    
          $installmentPlans = new InstallmentPlans($user);
          $installments = $installmentPlans->getPlans('courses', $item->id, $item->type, $item->category_id, $item->teacher_id);
       
          $itemPrice = $item->getPrice();
          $cash = $installments->sum('upfront');
          $plansCount = $installments->count();
          $minimumAmount = 0;
          foreach ($installments as $installment) {
              if ($minimumAmount == 0 or $minimumAmount > $installment->totalPayments($itemPrice)) {
                  $minimumAmount = $installment->totalPayments($itemPrice);
                }
            }
            
            foreach($installments as $installment){
               
                if($paidAmount > 0){
                 
                    $orderPayments1 = InstallmentOrderPayment:: where([
                        'type' => 'upfront' ,
                        'installment_order_id' => $order->id
                        ])->first();
                        $installmentOrderId= $order->id;
                       
                        if($orderPayments1->status !='paid'){
                          
                            if($paidAmount >= $order->item_price*$installment->upfront/100){
                             
                                InstallmentOrderPayment:: where([
                                    'id' => $installmentOrderId
                                    ])->update(['status'=>'paid']);
                                    
                                    // create order and order item also
                                    $accounting = Accounting::where('installment_payment_id', $orderPayments1->id)
                                    ->where('user_id', $user->id)
                                    ->first();
                                    
                                    $OrderItem = OrderItem :: where('id',$accounting->order_item_id)
                                    ->first();
                                    
                                    OrderItem :: where('id',$OrderItem->id)
                                    ->update([
                                    'installment_type' => 'part' ?? null,
                                ]);
                                    
                                $order1 = Order :: where('id', $OrderItem->order_id)
                                    ->first();
                                    
                                    // $sales_account=new PaymentsController();
                                    // $sales_account->paymentOrderAfterVerify($order);
                                    
                                    
                                }
                            }
                        
                        $paidAmount -=$order->item_price*$installment->upfront/100;
                   
                        foreach($installment->steps as $steps){
                        
                            $orderPayments1 = InstallmentOrderPayment:: where([
                                'step_id' => $steps->id,
                            'installment_order_id' => $installmentOrderId,
                            ])
                            ->first();
                            
                            if($orderPayments1){
                                if($orderPayments1->status !='paid'){
                                    if($paidAmount >= $order->item_price*$steps->amount/100){
                                        $orderPayments1 -> update(['status'=>'paid']);
                                        
                                        // create order and order item also
                                        $accounting = Accounting::where('installment_payment_id', $orderPayments1->id)
                                        ->where('user_id', $user->id)
                                        ->first();
                                        if(!$accounting){  
                                        $order_main_table = Order::create([
                                            'user_id' => $user->id,
                                            'status' => Order::$paying,
                                            'amount' =>$order->item_price*$steps->amount/100,
                                            'tax' => 0,
                                            'total_discount' => $totalDiscount ?? null,
                                            'total_amount' => $order->item_price*$steps->amount/100,
                                            'product_delivery_fee' => null,
                                            'created_at' => time(),
                                        ]);
                                        
                                        
                                        $discountCoupon = Discount::where('id', $discountId)->first();
                    
                                        if (empty($discountCoupon) or $discountCoupon->checkValidDiscount() != 'ok') {
                                            $discountCoupon = null;
                                        }
                                        
                                        if($order_main_table){
                                            
                                            $order_item = OrderItem::create([
                                                'user_id' => $user->id,
                                                'order_id' => $order_main_table->id,
                                                'webinar_id' => $itemId ?? null,
                                                'bundle_id' => null,
                                                'product_id' =>  null,
                                                'product_order_id' =>null,
                                                'reserve_meeting_id' => null,
                                                'subscribe_id' =>null,
                                                'promotion_id' => null,
                                                'gift_id' =>null,
                                                'installment_payment_id' => $orderPayments1->id ?? null,
                                                'installment_type' => 'part' ?? null,
                                                'ticket_id' => null,
                                                'discount_id' => !empty($discountId)?$discountId:($discountCoupon ? $discountCoupon->id : null),
                                                'amount' =>  $order->item_price*$steps->amount/100,
                                                'total_amount' =>  $order->item_price*$steps->amount/100,
                                                'tax' => 0,
                                                'tax_price' => 0,
                                                'commission' => 0,
                                                'commission_price' => 0,
                                                'product_delivery_fee' => 0,
                                                'discount' => $totalDiscount ?? null,
                                                'created_at' => time(),
                                                ]);  
                                                }
                                                $sales_account=new PaymentsController();
                                                $sales_account->paymentOrderAfterVerify($order_main_table);
               
                                            }else{
                                            Accounting :: where('id',$accounting->id)
                                                ->update([
                                                'total_amount' => $order->item_price*$steps->amount/100,
                                            ]);
                                            
                                            $OrderItem = OrderItem :: where('id',$accounting->order_item_id)
                                                ->first();
                                                
                                                OrderItem :: where('id',$OrderItem->id)
                                                ->update([
                                                'total_amount' => $order->item_price*$steps->amount/100,
                                                'installment_type' => 'part' ?? null,
                                                
                                            ]);
                                                
                                            $order = Order :: where('id', $OrderItem->order_id)
                                                ->first();
                                                
                                                Order :: where('id',$order->id)
                                                ->update([
                                                    'total_amount' => $order->item_price*$steps->amount/100,
                                                ]);
                                            
                                                // $sales_account=new PaymentsController();
                                                // $sales_account->paymentOrderAfterVerify($order);
                                                
                                                $sale =  Sale :: where('order_id',$order->id)->first();
                                                
                                            Sale ::  where('id',$sale->id)
                                                ->update([
                                                    'total_amount' => $order->item_price*$steps->amount/100,
                                            ]);
                                            
                                            $installmentPayment = InstallmentOrderPayment :: where('id', $orderPayments1->id)
                                            ->update([
                                                'sale_id' => $sale->id,
                                            ]);
                                            
                                        }
                                        
               
               
                                    }
                                    
                                }
                                $paidAmount -=$order->item_price*$steps->amount/100;
                                
                            }else{
                                
                                $orderPayments1 = InstallmentOrderPayment:: create([
                                    'installment_order_id' => $installmentOrderId,
                                    'sale_id' => null,
                                    'type' => 'step',
                                    'step_id' => $steps->id,
                                    'amount' => $order->item_price*$steps->amount/100,
                                    'status' => 'paying',
                                    
                                    'created_at' => time(),
                                ]);
                               
                                if($paidAmount >= $order->item_price*$steps->amount/100){
                                  
                                    $order_main_table = Order::create([
                                        'user_id' => $user->id,
                                        'status' => Order::$paying,
                                        'amount' =>$order->item_price*$steps->amount/100,
                                        'tax' => 0,
                                        'total_discount' => $totalDiscount ?? null,
                                        'total_amount' => $order->item_price*$steps->amount/100,
                                        'product_delivery_fee' => null,
                                        'created_at' => time(),
                                    ]);
                                    $discountId=null;
                                    
                                         $discountCoupon = Discount::where('id', $discountId)->first();
                                         
                                         if (empty($discountCoupon) or $discountCoupon->checkValidDiscount() != 'ok') {
                                             $discountCoupon = null;
                                            }
                                                    
                                            if($order_main_table){
                                                
                                                $order_item = OrderItem::create([
                                                    'user_id' => $user->id,
                                                    'order_id' => $order_main_table->id,
                                                    'webinar_id' => $itemId ?? null,
                                                    'bundle_id' => null,
                                                    'product_id' =>  null,
                                                    'product_order_id' =>null,
                                                    'reserve_meeting_id' => null,
                                                    'subscribe_id' =>null,
                                                    'promotion_id' => null,
                                                    'gift_id' =>null,
                                                    'installment_payment_id' => $orderPayments1->id ?? null,
                                                    'installment_type' => 'part' ?? null,
                                                    'ticket_id' => null,
                                                    'discount_id' => !empty($discountId)?$discountId:($discountCoupon ? $discountCoupon->id : null),
                                                    'amount' =>  $order->item_price*$steps->amount/100,
                                                    'total_amount' =>  $order->item_price*$steps->amount/100,
                                                    'tax' => 0,
                                                    'tax_price' => 0,
                                                    'commission' => 0,
                                                    'commission_price' => 0,
                                                    'product_delivery_fee' => 0,
                                                    'discount' => $totalDiscount ?? null,
                                                    'created_at' => time(),
                                                ]);  
                                                }
                                                $sales_account=new PaymentsController();
                                                $sales_account->paymentOrderAfterVerify($order_main_table);
                                                
                                            }
                                
                                            $paidAmount -=$order->item_price*$steps->amount/100;
                                            
                                        }
                        }
                    }
                }
            }
    public function updatePaymentSection(Request $request){
        $data = [
            ["Karan Sheth", "karansheth86@gmail.com", 15516894440, 53100.05, 53101, 0],
            
        ];
        $webinarId=2069;
        $discountId =null;
        foreach ($data as $data1){    
            
            if(!empty(User::where('email', $data1[1])->orwhere('mobile', $data1[2])->first())){
                $user = User::where('email', $data1[1])->orwhere('mobile', $data1[2])->first();
            }
            print_r($user->email);
            if(!$user){
                break;
            }
            $WebinarPartPayments = WebinarPartPayment :: select('user_id', 'webinar_id', 'installment_id', DB::raw('sum(amount) as total_amount'))
            ->where(['user_id'=> $user->id,'webinar_id'=> $webinarId])
            ->groupBy('user_id', 'webinar_id')
            ->get();
            
            if(!$WebinarPartPayments){
                break;
            }
            
            foreach ($WebinarPartPayments as $WebinarPartPayment){
                
                $order =InstallmentOrder::where([
                    'installment_id' => $WebinarPartPayment->installment_id,
                    'user_id' => $WebinarPartPayment->user_id,
                    'webinar_id' => $WebinarPartPayment->webinar_id,
                    'status' => 'open',
                    ])->first();
                    
                    if(!$order){
                        break;
                    }
                    $orderPayments = InstallmentOrderPayment :: where('installment_order_id', $order->id)
                    ->get();
                    
                    $totalSaleAmount=0;
                    
                    foreach($orderPayments as $orderPayment){
                        $saleId = $orderPayment->sale_id;
                        if($saleId){
                            $sale = Sale :: where(['id' => $saleId ,
                            'status' => null,])
                            ->first();
                            if($sale)
                            $totalSaleAmount+=$sale->total_amount;
                    }
               
                }
                $paidAmount = $totalSaleAmount  + $WebinarPartPayment->total_amount;
               
                $item = $this->getItem($WebinarPartPayment->webinar_id, 'course', $user);
                
                $installmentPlans = new InstallmentPlans($user);
                $installments = $installmentPlans->getPlans('courses', $item->id, $item->type, $item->category_id, $item->teacher_id);
                                
                $itemPrice = $item->getPrice();
                $cash = $installments->sum('upfront');
                $plansCount = $installments->count();
                $minimumAmount = 0;
                foreach ($installments as $installment) {
                    if ($minimumAmount == 0 or $minimumAmount > $installment->totalPayments($itemPrice)) {
                        $minimumAmount = $installment->totalPayments($itemPrice);
                    }
                }
                
                if($data1[4] == $paidAmount)
                break;
            
            if($data1[4] > $paidAmount){
                $remainingPaidAmount = $data1[4] - $paidAmount;
                $paidAmount = $data1[4];
                
                
                WebinarPartPayment::Create([
                    'user_id' => $user->id,
                    'installment_id' => $installments[0]->id,
                    'webinar_id' => $webinarId,
                    'amount' => $remainingPaidAmount,
                ]);
            }
            
            foreach($installments as $installment){
                if($paidAmount > 0){
                    $orderPayments1 = InstallmentOrderPayment:: where([
                        'type' => 'upfront' ,
                        'installment_order_id' => $order->id
                        ])->first();
                        if($orderPayments1->status !='paid'){
                            if($paidAmount >= $order->item_price*$installment->upfront/100){
                                InstallmentOrderPayment:: where([
                                    'id' => $orderPayments1 ->id
                                    ])->update(['status'=>'paid']);
                                    
                                    $accounting = Accounting::where('installment_payment_id', $orderPayments1->id)
                                    ->where('user_id', $user->id)
                                    ->first();
                                    
                                    $OrderItem = OrderItem :: where('id',$accounting->order_item_id)
                                    ->first();
                                    
                                    OrderItem :: where('id',$OrderItem->id)
                                    ->update([
                                        'installment_type' => 'part' ?? null,
                                    ]);
                                    
                                    $order1 = Order :: where('id', $OrderItem->order_id)
                                    ->first();
                                    
                                    // $sales_account=new PaymentsController();
                                    // $sales_account->paymentOrderAfterVerify($order);
                                    
                                    
                                }
                            }
                        
                        $paidAmount -=$order->item_price*$installment->upfront/100;
                   
                        foreach($installment->steps as $steps){
                                                        
                            $orderPayments1 = InstallmentOrderPayment:: where([
                                'step_id' => $steps->id,
                                'installment_order_id' => $order->id,
                                ])
                                ->first();
                                
                                if($orderPayments1){
                                    if($orderPayments1->status !='paid'){
                                        if($paidAmount >= $order->item_price*$steps->amount/100){
                                            $orderPayments1 -> update(['status'=>'paid']);
                                            
                                            //create order and order item also
                                            $accounting = Accounting::where('installment_payment_id', $orderPayments1->id)
                                            ->where('user_id', $user->id)
                                            ->first();
                                            if(!$accounting){  
                                                $order_main_table = Order::create([
                                                    'user_id' => $user->id,
                                                    'status' => Order::$paying,
                                                    'amount' =>$order->item_price*$steps->amount/100,
                                                    'tax' => 0,
                                                    'total_discount' => $totalDiscount ?? null,
                                                    'total_amount' => $order->item_price*$steps->amount/100,
                                                    'product_delivery_fee' => null,
                                                    'created_at' => time(),
                                                ]);
                                                
                                        
                                                $discountCoupon = Discount::where('id', $discountId)->first();
                                                
                                                if (empty($discountCoupon) or $discountCoupon->checkValidDiscount() != 'ok') {
                                                    $discountCoupon = null;
                                        }
                                        
                                        if($order_main_table){
                                            
                                            $order_item = OrderItem::create([
                                                'user_id' => $user->id,
                                                'order_id' => $order_main_table->id,
                                                'webinar_id' => $itemId ?? null,
                                                'bundle_id' => null,
                                                'product_id' =>  null,
                                                'product_order_id' =>null,
                                                'reserve_meeting_id' => null,
                                                'subscribe_id' =>null,
                                                'promotion_id' => null,
                                                'gift_id' =>null,
                                                'installment_payment_id' => $orderPayments1->id ?? null,
                                                'installment_type' => 'part' ?? null,
                                                'ticket_id' => null,
                                                'discount_id' => !empty($discountId)?$discountId:($discountCoupon ? $discountCoupon->id : null),
                                                'amount' =>  $order->item_price*$steps->amount/100,
                                                'total_amount' =>  $order->item_price*$steps->amount/100,
                                                'tax' => 0,
                                                'tax_price' => 0,
                                                'commission' => 0,
                                                'commission_price' => 0,
                                                'product_delivery_fee' => 0,
                                                'discount' => $totalDiscount ?? null,
                                                'created_at' => time(),
                                                ]);  
                                            }
                                            $sales_account=new PaymentsController();
                                               $sales_account->paymentOrderAfterVerify($order_main_table);
               
                                            }else{
                                            Accounting :: where('id',$accounting->id)
                                                ->update([
                                                    'total_amount' => $order->item_price*$steps->amount/100,
                                                ]);
                                                
                                                $OrderItem = OrderItem :: where('id',$accounting->order_item_id)
                                                ->first();
                                                
                                                OrderItem :: where('id',$OrderItem->id)
                                                ->update([
                                                    'total_amount' => $order->item_price*$steps->amount/100,
                                                    'installment_type' => 'part' ?? null,
                                                    
                                                ]);
                                                
                                                $order = Order :: where('id', $OrderItem->order_id)
                                                ->first();
                                                
                                                Order :: where('id',$order->id)
                                                ->update([
                                                    'total_amount' => $order->item_price*$steps->amount/100,
                                                ]);
                                            
                                            // $sales_account=new PaymentsController();
                                            // $sales_account->paymentOrderAfterVerify($order);
                                            
                                            $sale =  Sale :: where('order_id',$order->id)->first();
                                            
                                            Sale ::  where('id',$sale->id)
                                                ->update([
                                                'total_amount' => $order->item_price*$steps->amount/100,
                                            ]);
                                            
                                            
                                            $installmentPayment = InstallmentOrderPayment :: where('_id', $orderPayments1->id)
                                            ->update([
                                                'sale_id' => $sale->id,
                                            ]);
                                            
                                        }
               
                                    }
                                    
                                }
                                $paidAmount -=$order->item_price*$steps->amount/100;
                                
                            }else{
                                
                                // create step in installment order payment
                                // echo 'there is no step in installment order payment so we will create it<br>';
                                
                                $orderPayments1 = InstallmentOrderPayment:: create([
                                    'installment_order_id' => $order->id,
                                    'sale_id' => null,
                                    'type' => 'step',
                                    'step_id' => $steps->id,
                                    'amount' => $order->item_price*$steps->amount/100,
                                    'status' => 'paying',
                                    
                                    'created_at' => time(),
                                ]);
                              
                                if($paidAmount >= $order->item_price*$steps->amount/100){
                                   
                                    $order_main_table = Order::create([
                                            'user_id' => $user->id,
                                            'status' => Order::$paying,
                                            'amount' =>$order->item_price*$steps->amount/100,
                                            'tax' => 0,
                                            'total_discount' => $totalDiscount ?? null,
                                            'total_amount' => $order->item_price*$steps->amount/100,
                                            'product_delivery_fee' => null,
                                            'created_at' => time(),
                                        ]);
                                        $discountId=null;
                                        
                                         $discountCoupon = Discount::where('id', $discountId)->first();
                    
                                         if (empty($discountCoupon) or $discountCoupon->checkValidDiscount() != 'ok') {
                                            $discountCoupon = null;
                                        }
                                        
                                        if($order_main_table){
                                                            
                                            $order_item = OrderItem::create([
                                                    'user_id' => $user->id,
                                                    'order_id' => $order_main_table->id,
                                                    'webinar_id' => $itemId ?? null,
                                                    'bundle_id' => null,
                                                    'product_id' =>  null,
                                                    'product_order_id' =>null,
                                                    'reserve_meeting_id' => null,
                                                    'subscribe_id' =>null,
                                                    'promotion_id' => null,
                                                    'gift_id' =>null,
                                                    'installment_payment_id' => $orderPayments1->id ?? null,
                                                    'installment_type' => 'part' ?? null,
                                                    'ticket_id' => null,
                                                    'discount_id' => !empty($discountId)?$discountId:($discountCoupon ? $discountCoupon->id : null),
                                                    'amount' =>  $order->item_price*$steps->amount/100,
                                                    'total_amount' =>  $order->item_price*$steps->amount/100,
                                                    'tax' => 0,
                                                    'tax_price' => 0,
                                                    'commission' => 0,
                                                    'commission_price' => 0,
                                                    'product_delivery_fee' => 0,
                                                    'discount' => $totalDiscount ?? null,
                                                    'created_at' => time(),
                                                ]);  
                                                }
                                                $sales_account=new PaymentsController();
                                                $sales_account->paymentOrderAfterVerify($order_main_table);
                                                
                                            }
                                
                                            $paidAmount -=$order->item_price*$steps->amount/100;
                                            
                                        }
                        }
                    }
                }
  
       }
   }

}
    public function shortPaymentSection1(Request $request){
        $array1=[];
        $amount=[];
        $WebinarPartPayments = WebinarPartPayment :: get();
        echo "<pre>";
        
        foreach ($WebinarPartPayments as $WebinarPartPayment){
            
            $WebinarPartPayments1 = WebinarPartPayment::where([
                'user_id' => $WebinarPartPayment->user_id,
                'webinar_id' => $WebinarPartPayment->webinar_id,
                ])->get();
                
                foreach ($WebinarPartPayments1 as $WebinarPartPayment1){
                    if(isset($array1[$WebinarPartPayment1->user_id])){
                        if($array1[$WebinarPartPayment1->user_id] == $WebinarPartPayment1->webinar_id){
                            break;
                        }}else{
                            $array1[$WebinarPartPayment->user_id] = $WebinarPartPayment->webinar_id;
                        }
                        $user= User::where('id', $WebinarPartPayment1->user_id)->first();
                        // }
                        // if(count($WebinarPartPayments1)>1){
                            echo "<pre> count: ".count($WebinarPartPayments1);
                            echo " user id: ".User::where('id', $WebinarPartPayment1->user_id)->first()->email." ";
                            print_r($WebinarPartPayment1->user_id);
                            echo " Web id: ";
                            print_r($WebinarPartPayment1->webinar_id);
                            echo " amount: ";
                            print_r($WebinarPartPayment1->amount);
                            echo "<br>";
                            die();
                            $InstallmentOrder =InstallmentOrder::where([
                                'installment_id' => $WebinarPartPayment->installment_id,
                        'user_id' => $WebinarPartPayment->user_id,
                        'webinar_id' => $WebinarPartPayment->webinar_id,
                        'status' => 'open',
                        ])->first();
                        
                        
                        
                        $installmentPayment = InstallmentOrderPayment :: where('installment_order_id', $InstallmentOrder->id)
                        ->first();
                        
                        $accounting = Accounting::where('installment_payment_id', $installmentPayment->id)
                        ->first();
                        
                        Accounting::where('id', $accounting->id)
                        ->update([
                            'amount' => $WebinarPartPayment->amount,
                        ]);
                        
                $sale =  Sale :: where('installment_payment_id',$installmentPayment->id)
                ->first();
                
                Sale ::  where('id',$sale->id)
                ->update([
                    'total_amount' => $WebinarPartPayment->amount,
                    'status' => 'part',
                ]);
                
                $OrderItem = OrderItem :: where('id',$accounting->order_item_id)
                    ->first();
                    
                    OrderItem :: where('id',$OrderItem->id)
                    ->update([
                        'total_amount' => $WebinarPartPayment->amount,
                        'installment_type' => 'part',
                    ]);
                    
                    $order = Order :: where('id',$sale->order_id)
                    ->first();
                    
                    Order :: where('id',$order->id)
                    ->update([
                        'total_amount' => $WebinarPartPayment->amount,
                    ]);
                   
                    session(['success'=>true]);
                    
                    $itemId = $WebinarPartPayment->webinar_id;
                    $itemType = 'course';
                    $totalDiscount= $WebinarPartPayment->webinar_id;
                    $discountId= $request->get('discount_id');
                    $installmentId= $WebinarPartPayment->installment_id;
                    
                    $payment_type ="";
                    if($request->input('payment_type')){
                        $payment_type = $request->input('payment_type');
                    }
                   
                            
                    $installment = Installment::query()->where('id', $installmentId)
                    ->where('enable', true)
                    ->withCount([
                        'steps'
                        ])
                        ->first();
                       
             if (!empty($installment)) {
                    if (!$installment->hasCapacity()) {
                    $toastData = [
                        'title' => trans('public.request_failed'),
                        'msg' => trans('update.installment_not_capacity'),
                        'status' => 'error'
                    ];
                    return back()->with(['toast' => $toastData]);
                }
    
                $data = $request->all();
                $attachments = (!empty($data['attachments']) and count($data['attachments'])) ? array_map('array_filter', $data['attachments']) : [];
                $attachments = !empty($attachments) ? array_filter($attachments) : [];
                
                if ($installment->request_uploads) {
                    if (count($attachments) < 1) {
                        return redirect()->back()->withErrors([
                            'attachments' => trans('validation.required', ['attribute' => 'attachments'])
                        ]);
                    }
                }
                
                if (!empty($installment->capacity)) {
                    $openOrdersCount = InstallmentOrder::query()->where('installment_id', $installment->id)
                    ->where('status', 'open')
                    ->count();
                    
                    if ($openOrdersCount >= $installment->capacity) {
                        $toastData = [
                            'title' => trans('public.request_failed'),
                            'msg' => trans('update.installment_not_capacity'),
                            'status' => 'error'
                        ];
                        
                        return back()->with(['toast' => $toastData]);
                    }
                }
                
                $item = $this->getItem($itemId, $itemType, $user);
                
                $installmentPlans = new InstallmentPlans($user);
                $installments = $installmentPlans->getPlans('courses', $item->id, $item->type, $item->category_id, $item->teacher_id);
                
                // echo 'get installment plan <br>';
                
                $itemPrice = $item->getPrice();
                $cash = $installments->sum('upfront');
                $plansCount = $installments->count();
                $minimumAmount = 0;
                foreach ($installments as $installment) {
                    if ($minimumAmount == 0 or $minimumAmount > $installment->totalPayments($itemPrice)) {
                        $minimumAmount = $installment->totalPayments($itemPrice);
                    }
                }
               
                if (!empty($item)) {
                    
                    $productOrder = null;
                    
                    if ($itemType == 'product') {
                        $hasPhysicalProduct = ($item->type == Product::$physical);
                        
                        $this->validate($request, [
                            'country_id' => Rule::requiredIf($hasPhysicalProduct),
                            'province_id' => Rule::requiredIf($hasPhysicalProduct),
                            'city_id' => Rule::requiredIf($hasPhysicalProduct),
                            'district_id' => Rule::requiredIf($hasPhysicalProduct),
                            'address' => Rule::requiredIf($hasPhysicalProduct),
                        ]);
                        
                        /* Product Order */
                        $productOrder = $this->handleProductOrder($request, $user, $item);
                    }
                    
                    $columnName = $this->getColumnByItemType($itemType);
                    
                    $status = 'paying';
                    
                    if (empty($installment->upfront)) {
                        $status = 'open';
                        
                        if ($installment->needToVerify()) {
                            $status = 'pending_verification';
                        }
                    }
                    
                    $order = InstallmentOrder:: where([
                        'installment_id' => $installment->id,
                        'user_id' => $user->id,
                        'webinar_id' => $item->id,
                        'status' => 'open',
                        ])->first();
                      
                        $itemPrice = $item->getPrice();
                        $itemPrice1=$itemPrice-$totalDiscount;
                      
                if(!$order){
                    $order = InstallmentOrder::query()->updateOrCreate([
                        'installment_id' => $installment->id,
                        'user_id' => $user->id,
                        'discount' => $totalDiscount,
                        $columnName => $itemId,
                        'product_order_id' => !empty($productOrder) ? $productOrder->id : null,
                        'item_price' => $itemPrice1,
                        'status' => $status,
                    ], [
                        'created_at' => time(),
                    ]);
                }
                    
                
                $part_amount_status=true;
                if (!empty($payment_type)) {
                    $status = $payment_type;
                   
                        $part_amount=0;
                        $WebinarPartPayment =  WebinarPartPayment :: where('user_id',$user->id)
                        ->where('webinar_id',$itemId)
                        ->get();
                        
                        foreach ($WebinarPartPayment as $WebinarPartPayment1){
                            $part_amount = $part_amount + $WebinarPartPayment1->amount;
                        }
                        
                        if($order->status == 'open'){
                            
                            $orderPayments = InstallmentOrderPayment:: where(
                                'installment_order_id', $order->id)
                                ->get();
                                $totalSaleAmount=0;
                                
                                foreach($orderPayments as $orderPayment){
                        $saleId = $orderPayment->sale_id;
                        if($saleId){
                            $sale = Sale :: where(['id' => $saleId ,
                            'status' => null,])
                            ->first();
                            if($sale)
                            $totalSaleAmount+=$sale->total_amount;
                       }
              
                }
                $paidAmount=$totalSaleAmount+$part_amount;                                                     
                foreach($installments as $installment){
                    if($paidAmount > 0){
                        $orderPayments1 = InstallmentOrderPayment:: where([
                            'type' => 'upfront' ,
                            'installment_order_id' => $order->id
                            ])->first();
                            if($orderPayments1->status !='paid'){
                                if($paidAmount >= $order->item_price*$installment->upfront/100){
                                    InstallmentOrderPayment:: where([
                                        'id' => $orderPayments1 ->id
                                        ])->update(['status'=>'paid']);
                                    
                                        //create order and order item also
                                        $accounting = Accounting::where('installment_payment_id', $orderPayments1->id)
                                        ->where('user_id', $user->id)
                                        ->first();
                                        
                                        $OrderItem = OrderItem :: where('id',$accounting->order_item_id)
                                        ->first();
                                        
                                        OrderItem :: where('id',$OrderItem->id)
                                        ->update([
                                        'installment_type' => 'part' ?? null,
                                    ]);
                                        
                                    $order1 = Order :: where('id', $OrderItem->order_id)
                                        ->first();
                                    
                                    // $sales_account=new PaymentsController();
                                    // $sales_account->paymentOrderAfterVerify($order);
                        
                                    
                                }
                            }
                            
                            $paidAmount -=$order->item_price*$installment->upfront/100;
                           
                            foreach($installment->steps as $steps){
                              
                                $orderPayments1 = InstallmentOrderPayment:: where([
                                'step_id' => $steps->id,
                                'installment_order_id' => $order->id,
                                ])
                                ->first();
                                
                                if($orderPayments1){
                                    if($orderPayments1->status !='paid'){
                                        if($paidAmount >= $order->item_price*$steps->amount/100){
                                            $orderPayments1 -> update(['status'=>'paid']);
                                            
                                            //create order and order item also
                                            $accounting = Accounting::where('installment_payment_id', $orderPayments1->id)
                                                ->where('user_id', $user->id)
                                                ->first();
                                                if(!$accounting){  
                                                    $order_main_table = Order::create([
                                                        'user_id' => $user->id,
                                                        'status' => Order::$paying,
                                                        'amount' =>$order->item_price*$steps->amount/100,
                                                        'tax' => 0,
                                                        'total_discount' => $totalDiscount,
                                                        'total_amount' => $order->item_price*$steps->amount/100,
                                                        'product_delivery_fee' => null,
                                                        'created_at' => time(),
                                                    ]);
                                            
                                            
                                            $discountCoupon = Discount::where('id', $discountId)->first();
                        
                                            if (empty($discountCoupon) or $discountCoupon->checkValidDiscount() != 'ok') {
                                                $discountCoupon = null;
                                            }
                                                        
                                            if($order_main_table){
                                                                
                                                $order_item = OrderItem::create([
                                                    'user_id' => $user->id,
                                                    'order_id' => $order_main_table->id,
                                                    'webinar_id' => $itemId ?? null,
                                                    'bundle_id' => null,
                                                    'product_id' =>  null,
                                                    'product_order_id' =>null,
                                                    'reserve_meeting_id' => null,
                                                    'subscribe_id' =>null,
                                                    'promotion_id' => null,
                                                    'gift_id' =>null,
                                                    'installment_payment_id' => $orderPayments1->id ?? null,
                                                    'installment_type' => 'part' ?? null,
                                                    'ticket_id' => null,
                                                    'discount_id' => !empty($discountId)?$discountId:($discountCoupon ? $discountCoupon->id : null),
                                                    'amount' =>  $order->item_price*$steps->amount/100,
                                                    'total_amount' =>  $order->item_price*$steps->amount/100,
                                                    'tax' => 0,
                                                    'tax_price' => 0,
                                                    'commission' => 0,
                                                    'commission_price' => 0,
                                                    'product_delivery_fee' => 0,
                                                    'discount' => $totalDiscount,
                                                    'created_at' => time(),
                                                    ]);  
                                                    }
                                                    $sales_account=new PaymentsController();
                                                    $sales_account->paymentOrderAfterVerify($order_main_table);
                                                    
                                                }else{
                                                Accounting :: where('id',$accounting->id)
                                                    ->update([
                                                    'total_amount' => $order->item_price*$steps->amount/100,
                                                ]);
                                                
                                                $OrderItem = OrderItem :: where('id',$accounting->order_item_id)
                                                    ->first();
                                                    
                                                    OrderItem :: where('id',$OrderItem->id)
                                                    ->update([
                                                        'total_amount' => $order->item_price*$steps->amount/100,
                                                        'installment_type' => 'part' ?? null,
                                                        
                                                    ]);
                                                    
                                                    $order = Order :: where('id', $OrderItem->order_id)
                                                    ->first();
                                                    
                                                    Order :: where('id',$order->id)
                                                    ->update([
                                                        'total_amount' => $order->item_price*$steps->amount/100,
                                                    ]);
                                                
                                                    // $sales_account=new PaymentsController();
                                                    // $sales_account->paymentOrderAfterVerify($order);
                                                    
                                                    $sale =  Sale :: where('order_id',$order->id)->first();
                                                    
                                                    Sale ::  where('id',$sale->id)
                                                    ->update([
                                                    'total_amount' => $order->item_price*$steps->amount/100,
                                                ]);
                                                
                                                    
                                                    $installmentPayment = InstallmentOrderPayment :: where('_id', $orderPayments1->id)
                                                    ->update([
                                                    'sale_id' => $sale->id,
                                                ]);
                                                
                                            }
                   
                                        }
                                        
                                    }
                                    $paidAmount -=$order->item_price*$steps->amount/100;
                                    
                                }else{
                                    
                                    // create step in installment order payment
                                    $orderPayments1 = InstallmentOrderPayment:: create([
                                        'installment_order_id' => $order->id,
                                        'sale_id' => null,
                                        'type' => 'step',
                                        'step_id' => $steps->id,
                                        'amount' => $order->item_price*$steps->amount/100,
                                        'status' => 'paying',
                                        
                                        'created_at' => time(),
                                    ]);
                                   
                                    if($paidAmount >= $order->item_price*$steps->amount/100){
                                       
                                        $order_main_table = Order::create([
                                            'user_id' => $user->id,
                                            'status' => Order::$paying,
                                            'amount' =>$order->item_price*$steps->amount/100,
                                            'tax' => 0,
                                            'total_discount' => $totalDiscount,
                                            'total_amount' => $order->item_price*$steps->amount/100,
                                            'product_delivery_fee' => null,
                                            'created_at' => time(),
                                        ]);
                                        
                                        $discountCoupon = Discount::where('id', $discountId)->first();
                        
                                        if (empty($discountCoupon) or $discountCoupon->checkValidDiscount() != 'ok') {
                                            $discountCoupon = null;
                                        }
                                        
                                        if($order_main_table){
                                            
                                            $order_item = OrderItem::create([
                                                'user_id' => $user->id,
                                                'order_id' => $order_main_table->id,
                                                'webinar_id' => $itemId ?? null,
                                                'bundle_id' => null,
                                                'product_id' =>  null,
                                                'product_order_id' =>null,
                                                'reserve_meeting_id' => null,
                                                'subscribe_id' =>null,
                                                'promotion_id' => null,
                                                'gift_id' =>null,
                                                'installment_payment_id' => $orderPayments1->id ?? null,
                                                'installment_type' => 'part' ?? null,
                                                'ticket_id' => null,
                                                'discount_id' => !empty($discountId)?$discountId:($discountCoupon ? $discountCoupon->id : null),
                                                'amount' =>  $order->item_price*$steps->amount/100,
                                                'total_amount' =>  $order->item_price*$steps->amount/100,
                                                'tax' => 0,
                                                'tax_price' => 0,
                                                'commission' => 0,
                                                'commission_price' => 0,
                                                'product_delivery_fee' => 0,
                                                'discount' => $totalDiscount,
                                                'created_at' => time(),
                                                    ]);  
                                                }
                                                $sales_account=new PaymentsController();
                                                $sales_account->paymentOrderAfterVerify($order_main_table);
                                        
                                            }
                                
                                            $paidAmount -=$order->item_price*$steps->amount/100;
                                            
                                        }
                            }
                        }
                    }
                  
                }
               
            }
        }
    }
    
    } 
     }
    
    }
    
    public function fullAccess2()
    {
        $data = [
            ["Vinod Nalamwar", "vinod.nalamwar2015@gmail.com",  9822200770, 59000, 9387, 49613],
        ];
        $user = apiAuth();
        foreach ($data as $data1){
           
            session(['success'=>true]);
            
            $itemId = 2070;
            $itemType = 'course';
            $totalDiscount= 64900 - $data1[3];
            $discountId= 0;
            $installmentId= 16;
            $name = $data1[0];
            $email = $data1[1];
            $contact = $data1[2];
            
            $payment_type ="part";
            // if($request->input('payment_type')){
                // $payment_type = $request->input('payment_type');
                $amount = $data1[4];
                // }
                
                if(!empty(User::where('email', $email)->orwhere('mobile', $contact)->first())){
                    $user = User::where('email', $email)->orwhere('mobile', $contact)->first();
                }else{
                    $user = User::create([
                        'role_name' => 'user',
                        'role_id' => 1,
                        'mobile' => $contact ?? null,
                        'email' => $email ?? null,
                        'full_name' => $name,
                        // 'status' => User::$pending,
                        'status'=>'active',
                        'access_content' => 1,
                        'password' => Hash::make(123456),
                        'affiliate' => 0,
                        'timezone' => 'Asia/Kolkata' ?? null,
                        'created_at' => time(),
                        'enable_installments' =>1
                    ]); 
                }
                $WebinarPartPayment = WebinarPartPayment::where([
                    'user_id' => $user->id,
                    'installment_id' => $installmentId,
                    'webinar_id' => $itemId,
                    ])->first();
                    
                    if(!($WebinarPartPayment)){
                        if($data1[5]==0){
        
                            $order_main_table = Order::create([
                                'user_id' => $user->id,
                                'status' => Order::$paying,
                                'amount' => isset($amount)?$amount: $installment->getUpfront($order->getItemPrice()),
                                'tax' => 0,
                                'total_discount' => $totalDiscount,
                                'total_amount' => isset($amount)?$amount: $installment->getUpfront($order->getItemPrice()),
                                'product_delivery_fee' => null,
                                'created_at' => time(),
                            ]);
                        
                            
                            $discountCoupon = Discount::where('id', $discountId)->first();
                            
                            if (empty($discountCoupon) or $discountCoupon->checkValidDiscount() != 'ok') {
                $discountCoupon = null;
            }
                        
            if($order_main_table){
                
                $order_item = OrderItem::create([
                    'user_id' => $user->id,
                        'order_id' => $order_main_table->id,
                        'webinar_id' => $itemId ?? null,
                        'bundle_id' => null,
                        'product_id' =>  null,
                        'product_order_id' =>null,
                        'reserve_meeting_id' => null,
                        'subscribe_id' =>null,
                        'promotion_id' => null,
                        'gift_id' =>null,
                        'installment_payment_id' => $installmentPayment->id ?? null,
                        'ticket_id' => null,
                        'discount_id' => !empty($discountId)?$discountId:($discountCoupon ? $discountCoupon->id : null),
                        'amount' =>  isset($amount)?$amount:  $installment->getUpfront($order->getItemPrice()),
                        'total_amount' =>  isset($amount)?$amount: $installment->getUpfront($order->getItemPrice()),
                        'tax' => 0,
                        'tax_price' => 0,
                        'commission' => 0,
                        'commission_price' => 0,
                        'product_delivery_fee' => 0,
                        'discount' => $totalDiscount,
                        'created_at' => time(),
                    ]);  
                }
                    $sales_account=new PaymentsController();
                    $sales_account->paymentOrderAfterVerify($order_main_table);
                }
        
                if($data1[5]>0){
                
            $installment = Installment::query()->where('id', $installmentId)
            ->where('enable', true)
            ->withCount([
                'steps'
                ])
                ->first();
                
                if (!empty($installment)) {
                    if (!$installment->hasCapacity()) {
                        $toastData = [
                            'title' => trans('public.request_failed'),
                            'msg' => trans('update.installment_not_capacity'),
                            'status' => 'error'
                        ];
                        return back()->with(['toast' => $toastData]);
                    }
                   
                    if (!empty($installment->capacity)) {
                        $openOrdersCount = InstallmentOrder::query()->where('installment_id', $installment->id)
                        ->where('status', 'open')
                        ->count();
                        
                        if ($openOrdersCount >= $installment->capacity) {
                            $toastData = [
                                'title' => trans('public.request_failed'),
                                'msg' => trans('update.installment_not_capacity'),
                                'status' => 'error'
                            ];
                            
                            return back()->with(['toast' => $toastData]);
                        }
                    }
                
              $item = $this->getItem($itemId, $itemType, $user);
              
              if (!empty($item)) {
    
                  $productOrder = null;
                  
                  if ($itemType == 'product') {
                      $hasPhysicalProduct = ($item->type == Product::$physical);
                      
                      $this->validate($request, [
                          'country_id' => Rule::requiredIf($hasPhysicalProduct),
                          'province_id' => Rule::requiredIf($hasPhysicalProduct),
                          'city_id' => Rule::requiredIf($hasPhysicalProduct),
                          'district_id' => Rule::requiredIf($hasPhysicalProduct),
                          'address' => Rule::requiredIf($hasPhysicalProduct),
                        ]);
                        
                        /* Product Order */
                        $productOrder = $this->handleProductOrder($request, $user, $item);
                    }
                    
                    $columnName = $this->getColumnByItemType($itemType);
                    
                    $status = 'paying';
                    
                    if (empty($installment->upfront)) {
                        $status = 'open';
                        
                        if ($installment->needToVerify()) {
                            $status = 'pending_verification';
                        }
                    }
                  
                    $itemPrice = $item->getPrice();
                    $itemPrice1=$itemPrice-$totalDiscount;
                    
                    $order = InstallmentOrder::query()->updateOrCreate([
                        'installment_id' => $installment->id,
                        'user_id' => $user->id,
                        'discount' => $totalDiscount,
                        $columnName => $itemId,
                        'product_order_id' => !empty($productOrder) ? $productOrder->id : null,
                        'item_price' => $itemPrice1,
                        'status' => $status,
                    ], [
                        'created_at' => time(),
                    ]);
                    $part_amount_status=true;
                        if (!empty($payment_type)) {
                            $status = $payment_type;
                            
                            
                            WebinarPartPayment::Create([
                                'user_id' => $user->id,
                                'installment_id' => $installmentId,
                                'webinar_id' => $itemId,
                                'amount' => $amount,
                            ]);
                            
                            $part_amount=0;
                            $WebinarPartPayment =  WebinarPartPayment :: where('user_id',$user->id)
                            ->where('webinar_id',$itemId)
                            ->get();
                            
                            foreach ($WebinarPartPayment as $WebinarPartPayment1){
                                $part_amount = $part_amount + $WebinarPartPayment1->amount;
                            }
                            
                            if($part_amount > $amount){
                                $part_amount_status=false;
                            }
                        
                        }
    
                        
                        /* Attachments */
                        // $this->handleAttachments($attachments, $order);
                        
                        /* Update Product Order */
                        if (!empty($productOrder)) {
                            $productOrder->update([
                                'installment_order_id' => $order->id
                            ]);
                        }
                        
                        $notifyOptions = [
                            '[u.name]' => $order->user->full_name,
                            '[installment_title]' => $installment->main_title,
                            '[time.date]' => dateTimeFormat(time(), 'j M Y - H:i'),
                            '[amount]' => handlePrice($itemPrice),
                        ];
                        
                        sendNotification("instalment_request_submitted", $notifyOptions, $order->user_id);
                        sendNotification("instalment_request_submitted_for_admin", $notifyOptions, 1);
                       
                        /* Payment and Cart */
                        if($part_amount_status){
                            
                    if (!empty($installment->upfront)) {
                        $installmentPayment = InstallmentOrderPayment :: query()->updateOrCreate([
                            'installment_order_id' => $order->id,
                            'sale_id' => null,
                            'type' => 'upfront',
                            'step_id' => null,
                            'amount' => $installment->getUpfront($order->getItemPrice()),
                            'status' => $status,
                        ], [
                            'created_at' => time(),
                        ]);
                        
                        $order_main_table = Order::create([
                            'user_id' => $user->id,
                            'status' => ($status=='part')?$status:Order::$paying,
                            'amount' => isset($amount)?$amount: $installment->getUpfront($order->getItemPrice()),
                            'tax' => 0,
                            'total_discount' => $totalDiscount,
                            'total_amount' => isset($amount)?$amount: $installment->getUpfront($order->getItemPrice()),
                            'product_delivery_fee' => null,
                            'created_at' => time(),
                        ]);
                        
                        
                        $discountCoupon = Discount::where('id', $discountId)->first();
                        
                        if (empty($discountCoupon) or $discountCoupon->checkValidDiscount() != 'ok') {
                            $discountCoupon = null;
                        }
                        
                        if($order_main_table){
                            
                            $order_item = OrderItem::create([
                                'user_id' => $user->id,
                                'order_id' => $order_main_table->id,
                                'webinar_id' => $itemId ?? null,
                                'bundle_id' => null,
                                'product_id' =>  null,
                                'product_order_id' =>null,
                                'reserve_meeting_id' => null,
                                'subscribe_id' =>null,
                                'promotion_id' => null,
                                'gift_id' =>null,
                                'installment_payment_id' => $installmentPayment->id ?? null,
                                'ticket_id' => null,
                                'discount_id' => !empty($discountId)?$discountId:($discountCoupon ? $discountCoupon->id : null),
                                'amount' =>  isset($amount)?$amount:  $installment->getUpfront($order->getItemPrice()),
                                'total_amount' =>  isset($amount)?$amount: $installment->getUpfront($order->getItemPrice()),
                                'tax' => 0,
                                'tax_price' => 0,
                                'commission' => 0,
                                'commission_price' => 0,
                                'product_delivery_fee' => 0,
                                'discount' => $totalDiscount,
                                'created_at' => time(),
                            ]);  
                        }
                        $sales_account=new PaymentsController();
                        $sales_account->paymentOrderAfterVerify($order_main_table);
                
                    } else {
                                
                        if ($installment->needToVerify()) {
                            sendNotification("installment_verification_request_sent", $notifyOptions, $order->user_id);
                            sendNotification("admin_installment_verification_request_sent", $notifyOptions, 1); // Admin
                            
                            return redirect('/installments/request_submitted');
                        } else {
                            sendNotification("approve_installment_verification_request", $notifyOptions, $order->user_id);
                            
                            return $this->handleOpenOrder($item, $productOrder);
                        }
                    }
                    $sale =  Sale :: where('buyer_id',$user->id)
                    ->where('webinar_id',$itemId)->first();
                    
                    
                    $installmentPayment = InstallmentOrderPayment :: where('sale_id', $sale->id)
                    ->first();
                    
                    if($installmentPayment->amount <= $part_amount){
                     $installmentPayment = InstallmentOrderPayment :: where('sale_id', $sale->id)
                     ->update([
                         'status' => 'paid',
                        ]);}
                       
                    }else{
                        
                        $sale =  Sale :: where('buyer_id',$user->id)
                        ->where('webinar_id',$itemId)->first();
                        
                        Sale ::  where('id',$sale->id)
                        ->update([
                            'total_amount' => $part_amount,
                        ]);
                        
                        $order = Order :: where('id',$sale->order_id)
                        ->first();
                        
                        Order :: where('id',$order->id)
                        ->update([
                            'total_amount' => $part_amount,
                        ]);
                        
                        $OrderItem = OrderItem :: where('order_id',$order->id)
                        ->first();
                        
                        OrderItem :: where('id',$OrderItem->id)
                        ->update([
                            'total_amount' => $part_amount,
                        ]);
                        
                        $accounting = Accounting::where('order_item_id', $OrderItem->id)
                        ->where('user_id', $user->id)
                        ->update([
                            'amount' => $part_amount,
                        ]);
                        
                $installmentPayment = InstallmentOrderPayment :: where('sale_id', $sale->id)
                ->first();
                
                if($installmentPayment->amount <= $part_amount){
                    $installmentPayment = InstallmentOrderPayment :: where('sale_id', $sale->id)
                    ->update([
                        'status' => 'paid',
                    ]);}
                  
                }
                }
            }
    
            }
                }
            }
    
        }
        
    public function fullAccessForm(){
        
        if (Auth::check() && Auth::user()->role_id ==2) {
            $courses = Webinar::where('status', 'active')
            ->get();
                           
                return view('web.default2' . '.cart.full_access',compact('courses'));
            } else {
                return back();
            }
            
        }
    public function fullAccess(Request $request)
    {
        
         $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile' => 'required|string|max:15',
            'course' => 'required|string',
            'amount' => 'required|numeric',
            'paid_amount' => 'required|numeric',
            'pending_amount' => 'required|numeric',
        ]);
        
        if (!Auth::check() && Auth::user()->role_id !==2) {
            return back();
        }
        $data1 =$request->all();
        $name = $data1['name'];
        $email = $data1['email'];
        $contact = $data1['mobile'];
        //  session(['success'=>true]);
        
        if(!empty(User::where('email', $email)->orwhere('mobile', $contact)->first())){
            $user = User::where('email', $email)->orwhere('mobile', $contact)->first();
        }else{
            $user = User::create([
                'role_name' => 'user',
                'role_id' => 1,
                'mobile' => $contact ?? null,
                'email' => $email ?? null,
                'full_name' => $name,
                // 'status' => User::$pending,
                'status'=>'active',
                'access_content' => 1,
                'password' => Hash::make(123456),
                'affiliate' => 0,
                'timezone' => 'Asia/Kolkata' ?? null,
                'created_at' => time(),
                'enable_installments' =>1
            ]); 
        }
        $itemId = $data1['course'];
        $itemType = 'course';
        $courses = Webinar::where('id',$itemId)->where('status', 'active')
        ->first();
        $item = $this->getItem($itemId, $itemType, $user);
        $itemPrice = $item->getPrice();
        $totalDiscount= $itemPrice - $data1['amount'];
        $discountId= 0;
        // $installmentId= 16;
        $installmentPlans = new InstallmentPlans();
        $installments = $installmentPlans->getPlans('courses', $courses->id, $courses->type, $courses->category_id, $courses->teacher_id);
        $installmentId = $installments[0]->id;
        $payment_type ="part";
        // if($request->input('payment_type')){
            // $payment_type = $request->input('payment_type');

            $part_amount=0;
            // $this->shortPaymentSection($request,$user->id,$itemId);
            $WebinarPartPayment =  WebinarPartPayment :: where('user_id',$user->id)
            ->where('webinar_id',$itemId)
            ->get();
            if($WebinarPartPayment){
                foreach ($WebinarPartPayment as $WebinarPartPayment1){
                    $part_amount = $part_amount + $WebinarPartPayment1->amount;
                }
            }
            
            
            $order = InstallmentOrder:: where([
                'installment_id' => $installmentId,
                'user_id' => $user->id,
                'webinar_id' => $itemId,
                'status' => 'open',
                ])->first();
                $totalSaleAmount=0;
                if($order){
                    $orderPayments = InstallmentOrderPayment:: where(
                        'installment_order_id', $order->id)
                        ->get();
                        
                        
                        foreach($orderPayments as $orderPayment){
                            $saleId = $orderPayment->sale_id;
                            if($saleId){
                                $sale = Sale :: where(['id' => $saleId ,
                                'status' => null,])
                                ->first();
                                if($sale)
                                $totalSaleAmount+=$sale->total_amount;
                        }
                     
                    }
                }
                $paidAmount=$totalSaleAmount+$part_amount;   
                $amount = $data1['paid_amount']-$paidAmount;
              
                if(!($order)){ 
                    if($data1['pending_amount']==0){
                      
                        $order_main_table = Order::create([
                        'user_id' => $user->id,
                        'status' => Order::$paying,
                        'amount' => isset($amount)?$amount: $installment->getUpfront($order->getItemPrice()),
                        'tax' => 0,
                        'total_discount' => $totalDiscount,
                        'total_amount' => isset($amount)?$amount: $installment->getUpfront($order->getItemPrice()),
                        'product_delivery_fee' => null,
                        'created_at' => time(),
                    ]);
                    
                    
                    $discountCoupon = Discount::where('id', $discountId)->first();
                    
                    if (empty($discountCoupon) or $discountCoupon->checkValidDiscount() != 'ok') {
                        $discountCoupon = null;
                    }
                    
                    if($order_main_table){
                        
                        $order_item = OrderItem::create([
                            'user_id' => $user->id,
                            'order_id' => $order_main_table->id,
                            'webinar_id' => $itemId ?? null,
                            'bundle_id' => null,
                            'product_id' =>  null,
                            'product_order_id' =>null,
                            'reserve_meeting_id' => null,
                            'subscribe_id' =>null,
                            'promotion_id' => null,
                            'gift_id' =>null,
                            'installment_payment_id' => $installmentPayment->id ?? null,
                            'ticket_id' => null,
                            'discount_id' => !empty($discountId)?$discountId:($discountCoupon ? $discountCoupon->id : null),
                            'amount' =>  isset($amount)?$amount:  $installment->getUpfront($order->getItemPrice()),
                            'total_amount' =>  isset($amount)?$amount: $installment->getUpfront($order->getItemPrice()),
                            'tax' => 0,
                            'tax_price' => 0,
                            'commission' => 0,
                            'commission_price' => 0,
                            'product_delivery_fee' => 0,
                            'discount' => $totalDiscount,
                            'created_at' => time(),
                        ]);  
                    }
                $sales_account=new PaymentsController();
                $sales_account->paymentOrderAfterVerify($order_main_table);
                return $response=['status' => 'success', 'message' => 'Data received successfully!'];
            }
        }
        
            $WebinarPartPayment = WebinarPartPayment::where([
                'user_id' => $user->id,
                    'installment_id' => $installmentId,
                    'webinar_id' => $itemId,
                    ])->first();
                    
                    // if(!($WebinarPartPayment)){
                        // return $response=['status' => 'fail', 'message' => 'WebinarPartPayment'];
                        if($data1['paid_amount']>0){
                            
                            $installment = Installment::query()->where('id', $installmentId)
                            ->where('enable', true)
                            ->withCount([
                                'steps'
                                ])
                                ->first();
                
                if (!empty($installment)) {
                    if (!$installment->hasCapacity()) {
                        $toastData = [
                            'title' => trans('public.request_failed'),
                            'msg' => trans('update.installment_not_capacity'),
                            'status' => 'error'
                        ];
                        return back()->with(['toast' => $toastData]);
                    }

                        $data = $request->all();
                        $attachments = (!empty($data['attachments']) and count($data['attachments'])) ? array_map('array_filter', $data['attachments']) : [];
                        $attachments = !empty($attachments) ? array_filter($attachments) : [];
                        
                        if ($installment->request_uploads) {
                if (count($attachments) < 1) {
                    return redirect()->back()->withErrors([
                        'attachments' => trans('validation.required', ['attribute' => 'attachments'])
                    ]);
                }
            }

            if (!empty($installment->capacity)) {
                $openOrdersCount = InstallmentOrder::query()->where('installment_id', $installment->id)
                ->where('status', 'open')
                ->count();
                
                if ($openOrdersCount >= $installment->capacity) {
                    $toastData = [
                        'title' => trans('public.request_failed'),
                        'msg' => trans('update.installment_not_capacity'),
                        'status' => 'error'
                    ];
                    
                    return back()->with(['toast' => $toastData]);
                }
            }
            
          $item = $this->getItem($itemId, $itemType, $user);
          
          $installmentPlans = new InstallmentPlans($user);
                $installments = $installmentPlans->getPlans('courses', $item->id, $item->type, $item->category_id, $item->teacher_id);
                
                // echo 'get installment plan <br>';
                
                $itemPrice = $item->getPrice();
                $cash = $installments->sum('upfront');
                $plansCount = $installments->count();
                $minimumAmount = 0;
                foreach ($installments as $installment) {
                    if ($minimumAmount == 0 or $minimumAmount > $installment->totalPayments($itemPrice)) {
                        $minimumAmount = $installment->totalPayments($itemPrice);
                    }
                }
              
                if (!empty($item)) {
                    
                    $productOrder = null;
                    
                    if ($itemType == 'product') {
                        $hasPhysicalProduct = ($item->type == Product::$physical);
                        
                        $this->validate($request, [
                            'country_id' => Rule::requiredIf($hasPhysicalProduct),
                            'province_id' => Rule::requiredIf($hasPhysicalProduct),
                            'city_id' => Rule::requiredIf($hasPhysicalProduct),
                            'district_id' => Rule::requiredIf($hasPhysicalProduct),
                            'address' => Rule::requiredIf($hasPhysicalProduct),
                        ]);
                        
                        /* Product Order */
                        $productOrder = $this->handleProductOrder($request, $user, $item);
                    }
                    
                    $columnName = $this->getColumnByItemType($itemType);
                    
                    $status = 'paying';
                    
                    if (empty($installment->upfront)) {
                        $status = 'open';
                        
                        if ($installment->needToVerify()) {
                            $status = 'pending_verification';
                        }
                    }
                
                    $order = InstallmentOrder:: where([
                        'installment_id' => $installment->id,
                        'user_id' => $user->id,
                        'webinar_id' => $item->id,
                        'status' => 'open',
                        ])->first();
                       
                        $itemPrice = $item->getPrice();
                        $itemPrice1=$itemPrice-$totalDiscount;
                       
                                if(!$order){
                                    $order = InstallmentOrder::query()->updateOrCreate([
                                        'installment_id' => $installment->id,
                                        'user_id' => $user->id,
                                        'discount' => $totalDiscount,
                                        $columnName => $itemId,
                                        'product_order_id' => !empty($productOrder) ? $productOrder->id : null,
                                        'item_price' => $itemPrice1,
                                        'status' => $status,
                                    ], [
                                    'created_at' => time(),
                                 ]);
                                }
                
                
                $part_amount_status=true;
                if (!empty($payment_type)) {
                    $status = $payment_type;
                    
                    
                    WebinarPartPayment::Create([
                    'user_id' => $user->id,
                    'installment_id' => $installmentId,
                    'webinar_id' => $itemId,
                    'amount' => $amount,
                ]);
                $part_amount=0;
                
                // $this->shortPaymentSection($request,$user->id,$itemId);
                $WebinarPartPayment =  WebinarPartPayment :: where('user_id',$user->id)
                ->where('webinar_id',$itemId)
                ->get();
                
                foreach ($WebinarPartPayment as $WebinarPartPayment1){
                    $part_amount = $part_amount + $WebinarPartPayment1->amount;
                }
                
                if($order->status == 'open'){
                    
                    $orderPayments = InstallmentOrderPayment:: where(
                        'installment_order_id', $order->id)
                        ->get();
                        $totalSaleAmount=0;
                        
                        foreach($orderPayments as $orderPayment){
                            $saleId = $orderPayment->sale_id;
                            if($saleId){
                                $sale = Sale :: where(['id' => $saleId ,
                                'status' => null,])
                                ->first();
                                if($sale)
                                $totalSaleAmount+=$sale->total_amount;
                        }
                      
                }
                $paidAmount=$totalSaleAmount+$part_amount;
                foreach($installments as $installment){
                    if($paidAmount > 0){
                        $orderPayments1 = InstallmentOrderPayment:: where([
                            'type' => 'upfront' ,
                            'installment_order_id' => $order->id
                            ])->first();
                        if($orderPayments1->status !='paid'){
                            if($paidAmount >= $order->item_price*$installment->upfront/100){
                                InstallmentOrderPayment:: where([
                        'id' => $orderPayments1 ->id
                        ])->update(['status'=>'paid']);
                        
                        //create order and order item also
                        $accounting = Accounting::where('installment_payment_id', $orderPayments1->id)
                                    ->where('user_id', $user->id)
                                    ->first();
                                    
                                $OrderItem = OrderItem :: where('id',$accounting->order_item_id)
                                    ->first();
                                    
                                OrderItem :: where('id',$OrderItem->id)
                                    ->update([
                                        'installment_type' => 'part' ?? null,
                                ]);
                                    
                                $order1 = Order :: where('id', $OrderItem->order_id)
                                    ->first();
                                    
                                    // $sales_account=new PaymentsController();
                                    // $sales_account->paymentOrderAfterVerify($order);
                    
                                }
                            }
                        
                            $paidAmount -=$order->item_price*$installment->upfront/100;
                           
                        foreach($installment->steps as $steps){
                                                        
                            $orderPayments1 = InstallmentOrderPayment:: where([
                                'step_id' => $steps->id,
                                'installment_order_id' => $order->id,
                                ])
                                ->first();
                                
                                if($orderPayments1){
                                    if($orderPayments1->status !='paid'){
                                        if($paidAmount >= $order->item_price*$steps->amount/100){
                                            $orderPayments1 -> update(['status'=>'paid']);
                                            
                                            //create order and order item also
                                            $accounting = Accounting::where('installment_payment_id', $orderPayments1->id)
                                            ->where('user_id', $user->id)
                                            ->first();
                                         if(!$accounting){  
                                            $order_main_table = Order::create([
                                            'user_id' => $user->id,
                                            'status' => Order::$paying,
                                            'amount' =>$order->item_price*$steps->amount/100,
                                            'tax' => 0,
                                            'total_discount' => $totalDiscount,
                                            'total_amount' => $order->item_price*$steps->amount/100,
                                            'product_delivery_fee' => null,
                                            'created_at' => time(),
                                        ]);
                                        
                                        $discountCoupon = Discount::where('id', $discountId)->first();
                    
                                        if (empty($discountCoupon) or $discountCoupon->checkValidDiscount() != 'ok') {
                                            $discountCoupon = null;
                                        }
                                        
                                            if($order_main_table){
                                                            
                                              $order_item = OrderItem::create([
                                                    'user_id' => $user->id,
                                                    'order_id' => $order_main_table->id,
                                                    'webinar_id' => $itemId ?? null,
                                                    'bundle_id' => null,
                                                    'product_id' =>  null,
                                                    'product_order_id' =>null,
                                                    'reserve_meeting_id' => null,
                                                    'subscribe_id' =>null,
                                                    'promotion_id' => null,
                                                    'gift_id' =>null,
                                                    'installment_payment_id' => $orderPayments1->id ?? null,
                                                    'installment_type' => 'part' ?? null,
                                                    'ticket_id' => null,
                                                    'discount_id' => !empty($discountId)?$discountId:($discountCoupon ? $discountCoupon->id : null),
                                                    'amount' =>  $order->item_price*$steps->amount/100,
                                                    'total_amount' =>  $order->item_price*$steps->amount/100,
                                                    'tax' => 0,
                                                    'tax_price' => 0,
                                                    'commission' => 0,
                                                    'commission_price' => 0,
                                                    'product_delivery_fee' => 0,
                                                    'discount' => $totalDiscount,
                                                    'created_at' => time(),
                                                ]);  
                                                }
                                                $sales_account=new PaymentsController();
                                                $sales_account->paymentOrderAfterVerify($order_main_table);
                                                
                                            }else{
                                            // echo ' accounting is already set so we will update order ,order item ,sale, and accounting price-'.$orderPayments1->id.'<br>';
                                            Accounting :: where('id',$accounting->id)
                                                ->update([
                                                'total_amount' => $order->item_price*$steps->amount/100,
                                            ]);
                                            
                                            $OrderItem = OrderItem :: where('id',$accounting->order_item_id)
                                                ->first();
                                                
                                                OrderItem :: where('id',$OrderItem->id)
                                                ->update([
                                                'total_amount' => $order->item_price*$steps->amount/100,
                                                'installment_type' => 'part' ?? null,
                                                
                                            ]);
                                                
                                            $order = Order :: where('id', $OrderItem->order_id)
                                                ->first();
                                                
                                                Order :: where('id',$order->id)
                                                ->update([
                                                    'total_amount' => $order->item_price*$steps->amount/100,
                                                ]);
                                                
                                                // $sales_account=new PaymentsController();
                                                // $sales_account->paymentOrderAfterVerify($order);
                                                
                                                $sale =  Sale :: where('order_id',$order->id)->first();
                                                
                                                Sale ::  where('id',$sale->id)
                                                ->update([
                                                'total_amount' => $order->item_price*$steps->amount/100,
                                            ]);
                                            
                                            
                                            $installmentPayment = InstallmentOrderPayment :: where('_id', $orderPayments1->id)
                                            ->update([
                                                'sale_id' => $sale->id,
                                            ]);
                                            
                                        }
               
                                    }
                                    
                                }
                                $paidAmount -=$order->item_price*$steps->amount/100;
                                // echo 'decrease total paid amount by this step '.$steps->id.' payment -'.($order->item_price*$steps->amount/100).' Rs now paid amount is'.$paidAmount.'<br>';
                                
                            }else{
                                
                                // create step in installment order payment
                                // echo 'there is no step in installment order payment so we will create it<br>';
                                
                                $orderPayments1 = InstallmentOrderPayment:: create([
                                    'installment_order_id' => $order->id,
                                    'sale_id' => null,
                                    'type' => 'step',
                                    'step_id' => $steps->id,
                                    'amount' => $order->item_price*$steps->amount/100,
                                    'status' => 'paying',
                                    'created_at' => time(),
                                ]);
                                
                                if($paidAmount >= $order->item_price*$steps->amount/100){
                                   
                                    $order_main_table = Order::create([
                                        'user_id' => $user->id,
                                        'status' => Order::$paying,
                                        'amount' =>$order->item_price*$steps->amount/100,
                                        'tax' => 0,
                                        'total_discount' => $totalDiscount,
                                        'total_amount' => $order->item_price*$steps->amount/100,
                                        'product_delivery_fee' => null,
                                        'created_at' => time(),
                                    ]);
                                        
                                    
                                    $discountCoupon = Discount::where('id', $discountId)->first();
                    
                                    if (empty($discountCoupon) or $discountCoupon->checkValidDiscount() != 'ok') {
                                        $discountCoupon = null;
                                    }
                                    
                                    if($order_main_table){
                                        
                                              $order_item = OrderItem::create([
                                                  'user_id' => $user->id,
                                                  'order_id' => $order_main_table->id,
                                                  'webinar_id' => $itemId ?? null,
                                                  'bundle_id' => null,
                                                  'product_id' =>  null,
                                                  'product_order_id' =>null,
                                                  'reserve_meeting_id' => null,
                                                  'subscribe_id' =>null,
                                                  'promotion_id' => null,
                                                  'gift_id' =>null,
                                                  'installment_payment_id' => $orderPayments1->id ?? null,
                                                  'installment_type' => 'part' ?? null,
                                                  'ticket_id' => null,
                                                  'discount_id' => !empty($discountId)?$discountId:($discountCoupon ? $discountCoupon->id : null),
                                                  'amount' =>  $order->item_price*$steps->amount/100,
                                                  'total_amount' =>  $order->item_price*$steps->amount/100,
                                                  'tax' => 0,
                                                  'tax_price' => 0,
                                                  'commission' => 0,
                                                  'commission_price' => 0,
                                                  'product_delivery_fee' => 0,
                                                  'discount' => $totalDiscount,
                                                  'created_at' => time(),
                                                ]);  
                                                }
                                                $sales_account=new PaymentsController();
                                                $sales_account->paymentOrderAfterVerify($order_main_table);
                                                
                                            }
                                
                                            $paidAmount -=$order->item_price*$steps->amount/100;
                                            
                                        }
                        }
                    }
                }
              
                return $response=['status' => 'success', 'message' => 'Data received successfully!'];
            }
                
            
            if($part_amount > $amount){
                $part_amount_status=false;
            }
                    
        }
      if($order->status != 'open'){
 
                /* Attachments */
                // $this->handleAttachments($attachments, $order);

                /* Update Product Order */
                if (!empty($productOrder)) {
                    $productOrder->update([
                        'installment_order_id' => $order->id
                    ]);
                }
                
                $notifyOptions = [
                    '[u.name]' => $order->user->full_name,
                    '[installment_title]' => $installment->main_title,
                    '[time.date]' => dateTimeFormat(time(), 'j M Y - H:i'),
                    '[amount]' => handlePrice($itemPrice),
                ];
                
                sendNotification("instalment_request_submitted", $notifyOptions, $order->user_id);
                sendNotification("instalment_request_submitted_for_admin", $notifyOptions, 1);
                
                // die($status);
                /* Payment and Cart */
                if($part_amount_status){
                if (!empty($installment->upfront)) {
                    $installmentPayment = InstallmentOrderPayment :: query()->updateOrCreate([
                        'installment_order_id' => $order->id,
                        'sale_id' => null,
                        'type' => 'upfront',
                        'step_id' => null,
                        'amount' => $installment->getUpfront($order->getItemPrice()),
                        'status' => $status,
                    ], [
                        'created_at' => time(),
                    ]);
                    
                    $order_main_table = Order::create([
                        'user_id' => $user->id,
                        'status' => ($status=='part')?$status:Order::$paying,
                        'amount' => isset($amount)?$amount: $installment->getUpfront($order->getItemPrice()),
                        'tax' => 0,
                        'total_discount' => $totalDiscount,
                        'total_amount' => isset($amount)?$amount: $installment->getUpfront($order->getItemPrice()),
                        'product_delivery_fee' => null,
                        'created_at' => time(),
                    ]);
                    
                    
                    $discountCoupon = Discount::where('id', $discountId)->first();
                    
                    if (empty($discountCoupon) or $discountCoupon->checkValidDiscount() != 'ok') {
                        $discountCoupon = null;
        }
        
        if($order_main_table){
            
            $order_item = OrderItem::create([
                    'user_id' => $user->id,
                    'order_id' => $order_main_table->id,
                    'webinar_id' => $itemId ?? null,
                    'bundle_id' => null,
                    'product_id' =>  null,
                    'product_order_id' =>null,
                    'reserve_meeting_id' => null,
                    'subscribe_id' =>null,
                    'promotion_id' => null,
                    'gift_id' =>null,
                    'installment_payment_id' => $installmentPayment->id ?? null,
                    'installment_type' => $status == 'part' ? $status : null,
                    'ticket_id' => null,
                    'discount_id' => !empty($discountId)?$discountId:($discountCoupon ? $discountCoupon->id : null),
                    'amount' =>  isset($amount)?$amount:  $installment->getUpfront($order->getItemPrice()),
                    'total_amount' =>  isset($amount)?$amount: $installment->getUpfront($order->getItemPrice()),
                    'tax' => 0,
                    'tax_price' => 0,
                    'commission' => 0,
                    'commission_price' => 0,
                    'product_delivery_fee' => 0,
                    'discount' => $totalDiscount,
                    'created_at' => time(),
                ]);  
            }
                $sales_account=new PaymentsController();
               $sales_account->paymentOrderAfterVerify($order_main_table);
              
            $this->shortPaymentSection($request,$user->id,$itemId);
         
            return $response=['status' => 'success', 'message' => 'Data received successfully!'];
   
                } else {
                    
                    if ($installment->needToVerify()) {
                        sendNotification("installment_verification_request_sent", $notifyOptions, $order->user_id);
                        sendNotification("admin_installment_verification_request_sent", $notifyOptions, 1); // Admin
                        
                        return redirect('/installments/request_submitted');
                    } else {
                        sendNotification("approve_installment_verification_request", $notifyOptions, $order->user_id);
                        
                        return $this->handleOpenOrder($item, $productOrder);
                    }
                }
            }else{
                
                $sale =  Sale :: where('buyer_id',$user->id)
                ->where('webinar_id',$itemId)->first();
                
                Sale ::  where('id',$sale->id)
                ->update([
                    'total_amount' => $part_amount,
                ]);
                
                $order = Order :: where('id',$sale->order_id)
                ->first();
                
                Order :: where('id',$order->id)
                ->update([
                    'total_amount' => $part_amount,
                ]);
                
                $OrderItem = OrderItem :: where('order_id',$order->id)
                ->first();
                
                OrderItem :: where('id',$OrderItem->id)
                ->update([
                    'total_amount' => $part_amount,
                    'installment_type' => 'part' ?? null,
                ]);
                
                $accounting = Accounting::where('order_item_id', $OrderItem->id)
                ->where('user_id', $user->id)
                ->update([
                    'amount' => $part_amount,
                ]);
            
                $installmentPayment = InstallmentOrderPayment :: where('sale_id', $sale->id)
                ->first();
                
                if($installmentPayment->amount <= $part_amount){
                 $installmentPayment = InstallmentOrderPayment :: where('sale_id', $sale->id)
                ->update([
                    'status' => 'paid',
                ]);}
              
                return $response=['status' => 'success', 'message' => 'Data received successfully!'];
                
            }
        }
    }
        }
        
        
        return $response=['status' => 'success', 'message' => 'Data received successfully!'];
    }
    
    // return $response=['status' => 'success', 'message' => 'Data received successfully!'];
    // }
    return $response=['status' => 'warning', 'message' => 'The selected course has already been purchased by this user.'];
}
    public function store1(Request $request, $installmentId)
    {
        $user = auth()->user();
        $itemId = $request->get('item');
        $itemType = $request->get('item_type');
        $totalDiscount= $request->get('totalDiscount');
        $discountId= $request->get('discount_id');
        
        if (empty($user) or !$user->enable_installments) {
            $toastData = [
                'title' => trans('public.request_failed'),
                'msg' => trans('update.you_cannot_use_installment_plans'),
                'status' => 'error'
            ];
            return back()->with(['toast' => $toastData]);
        }
        
        
        $installment = Installment::query()->where('id', $installmentId)
        ->where('enable', true)
        ->withCount([
            'steps'
            ])
            ->first();
            
            if (!empty($installment)) {
                if (!$installment->hasCapacity()) {
                    $toastData = [
                        'title' => trans('public.request_failed'),
                        'msg' => trans('update.installment_not_capacity'),
                        'status' => 'error'
                    ];
                    return back()->with(['toast' => $toastData]);
                }
                
                
                $this->validate($request, [
                    'item' => 'required',
                    'item_type' => 'required',
                ]);
                
                $data = $request->all();
                $attachments = (!empty($data['attachments']) and count($data['attachments'])) ? array_map('array_filter', $data['attachments']) : [];
                $attachments = !empty($attachments) ? array_filter($attachments) : [];
                
                if ($installment->request_uploads) {
                    if (count($attachments) < 1) {
                        return redirect()->back()->withErrors([
                            'attachments' => trans('validation.required', ['attribute' => 'attachments'])
                        ]);
                    }
                }
                
                if (!empty($installment->capacity)) {
                    $openOrdersCount = InstallmentOrder::query()->where('installment_id', $installment->id)
                    ->where('status', 'open')
                    ->count();
                    
                    if ($openOrdersCount >= $installment->capacity) {
                        $toastData = [
                            'title' => trans('public.request_failed'),
                            'msg' => trans('update.installment_not_capacity'),
                            'status' => 'error'
                        ];
                        
                        return back()->with(['toast' => $toastData]);
                    }
                }
                
                $item = $this->getItem($itemId, $itemType, $user);
                
                if (!empty($item)) {
    
                    $productOrder = null;
                    
                    if ($itemType == 'product') {
                        $hasPhysicalProduct = ($item->type == Product::$physical);
                        
                        $this->validate($request, [
                            'country_id' => Rule::requiredIf($hasPhysicalProduct),
                            'province_id' => Rule::requiredIf($hasPhysicalProduct),
                            'city_id' => Rule::requiredIf($hasPhysicalProduct),
                            'district_id' => Rule::requiredIf($hasPhysicalProduct),
                            'address' => Rule::requiredIf($hasPhysicalProduct),
                        ]);
                        
                        /* Product Order */
                        $productOrder = $this->handleProductOrder($request, $user, $item);
                    }
                    
                    $columnName = $this->getColumnByItemType($itemType);
                    
                    $status = 'paying';
                    
                    if (empty($installment->upfront)) {
                        $status = 'open';
                        
                        if ($installment->needToVerify()) {
                            $status = 'pending_verification';
                        }
                    }
                    
                    $itemPrice = $item->getPrice();
                    $itemPrice1=$itemPrice-$totalDiscount;
                    $order = InstallmentOrder::query()->updateOrCreate([
                        'installment_id' => $installment->id,
                        'user_id' => $user->id,
                        'discount' => $totalDiscount,
                        $columnName => $itemId,
                        'product_order_id' => !empty($productOrder) ? $productOrder->id : null,
                        'item_price' => $itemPrice1,
                        'status' => $status,
                    ], [
                        'created_at' => time(),
                    ]);
                    
                    /* Attachments */
                    $this->handleAttachments($attachments, $order);
                    
                    /* Update Product Order */
                    if (!empty($productOrder)) {
                        $productOrder->update([
                            'installment_order_id' => $order->id
                        ]);
                    }
                    
                    $notifyOptions = [
                        '[u.name]' => $order->user->full_name,
                        '[installment_title]' => $installment->main_title,
                        '[time.date]' => dateTimeFormat(time(), 'j M Y - H:i'),
                        '[amount]' => handlePrice($itemPrice),
                    ];
                    
                    sendNotification("instalment_request_submitted", $notifyOptions, $order->user_id);
                    sendNotification("instalment_request_submitted_for_admin", $notifyOptions, 1);
                    
                    
                    /* Payment and Cart */
                    if (!empty($installment->upfront)) {
                        $installmentPayment = InstallmentOrderPayment::query()->updateOrCreate([
                            'installment_order_id' => $order->id,
                            'sale_id' => null,
                            'type' => 'upfront',
                            'step_id' => null,
                            'amount' => $installment->getUpfront($order->getItemPrice()),
                            'status' => 'paying',
                        ], [
                            'created_at' => time(),
                        ]);
                        
                        // $result=Cart::where('creator_id', $user->id)->delete();
                        
                        
                        $cart = Cart::updateOrCreate([
                            'creator_id' => $user->id,
                            'installment_payment_id' => $installmentPayment->id,
                        ], [
                            'created_at' => time()
                        ]);
                        $installment_price=($itemPrice*$installment->upfront)/100;
                        $installment_price1=$installment_price-$installment->getUpfront($order->getItemPrice());
                        // Print_r($installment_price1);die();
                        if($discountId){
                            CartInstallment::updateOrCreate([
                                'cart_id' => $cart->id,
                                'user_id' => $user->id,
                                'discount_price' =>  $installment_price1,
                                'installment_price' => $installment_price,
                                'installment_payment_id' => $installmentPayment->id,
                                'discount_id' =>  $discountId ,
                                'total' => $installment->getUpfront($order->getItemPrice())
                            ], [
                                'created_at' => time()
                            ]);
                        }
                      
                        return redirect('/cart');
                    } else {
                        
                        if ($installment->needToVerify()) {
                            sendNotification("installment_verification_request_sent", $notifyOptions, $order->user_id);
                            sendNotification("admin_installment_verification_request_sent", $notifyOptions, 1); // Admin
                            
                            return redirect('/installments/request_submitted');
                        } else {
                            sendNotification("approve_installment_verification_request", $notifyOptions, $order->user_id);
                            
                            return $this->handleOpenOrder($item, $productOrder);
                        }
                    }
                }
            }
    
            abort(404);
        }
    
    private function handleOpenOrder($item, $productOrder)
    {
        if (!empty($productOrder)) {
            $productOrderStatus = ProductOrder::$waitingDelivery;
            
            if ($item->isVirtual()) {
                $productOrderStatus = ProductOrder::$success;
            }
            
            $productOrder->update([
                'status' => $productOrderStatus
            ]);
        }
        
        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('update.your_installment_purchase_has_been_successfully_completed'),
            'status' => 'success'
        ];
        
        return redirect('/panel/financial/installments')->with(['toast' => $toastData]);
    }
    
    private function handleProductOrder(Request $request, $user, $product)
    {
        $data = $request->all();
        
        $specifications = $data['specifications'] ?? null;
        $quantity = $data['quantity'] ?? 1;
        
        $order = ProductOrder::query()->create([
            'product_id' => $product->id,
            'seller_id' => $product->creator_id,
            'buyer_id' => $user->id,
            'sale_id' => null,
            'installment_order_id' => null,
            'status' => 'pending',
            'specifications' => $specifications ? json_encode($specifications) : null,
            'quantity' => $quantity,
            'discount_id' => null,
            'message_to_seller' => $data['message_to_seller'],
            'created_at' => time()
        ]);

        if ($product->type == Product::$physical) {
            $user->update([
                'country_id' => $data['country_id'] ?? $user->country_id,
                'province_id' => $data['province_id'] ?? $user->province_id,
                'city_id' => $data['city_id'] ?? $user->city_id,
                'district_id' => $data['district_id'] ?? $user->district_id,
                'address' => $data['address'] ?? $user->address,
            ]);
        }

        return $order;
    }
    
    private function handleAttachments($attachments, $order)
    {
        InstallmentOrderAttachment::query()->where('installment_order_id', $order->id)->delete();
        
        if (!empty($attachments)) {
            $attachmentsInsert = [];
            
            foreach ($attachments as $attachment) {
                if (!empty($attachment['title']) and !empty($attachment['file'])) {
                    $attachmentsInsert[] = [
                        'installment_order_id' => $order->id,
                        'title' => $attachment['title'],
                        'file' => $attachment['file'],
                    ];
                }
            }

            if (!empty($attachmentsInsert)) {
                InstallmentOrderAttachment::query()->insert($attachmentsInsert);
            }
        }
    }
    
    public function show($orderId)
    {
        $user = apiAuth();

        $order = InstallmentOrder::query()
            ->where('id', $orderId)
            ->where('user_id', $user->id)
            ->with([
                'installment' => function ($query) {
                    $query->with([
                        'steps' => function ($query) {
                            $query->orderBy('deadline', 'asc');
                        }
                    ]);
                }
            ])
            ->first();
//print_r($order);die;
        if (!empty($order) and !in_array($order->status, ['refunded', 'canceled'])) {

            $getRemainedInstallments = $this->getRemainedInstallments($order);
            $getOverdueOrderInstallments = $this->getOverdueOrderInstallments($order);

            $totalParts = $order->installment->steps->count();
            $remainedParts = $getRemainedInstallments['total'];
            $remainedAmount = $getRemainedInstallments['amount'];
            $overdueAmount = $getOverdueOrderInstallments['amount'];

            $data = [
                'pageTitle' => trans('update.installments'),
                'totalParts' => $totalParts,
                'remainedParts' => $remainedParts,
                'remainedAmount' => $remainedAmount,
                'overdueAmount' => $overdueAmount,
                'order' => $order,
                'payments' => $order->payments,
                'installment' => $order->installment,
                'itemPrice' => $order->getItemPrice(),
            ];
             return apiResponse2(1, 'retrieved', 'data retrieved successfully', $data);
           
        }

        abort(404);
    }
    private function getRemainedInstallments($order)
    {
        $total = 0;
        $amount = 0;

        $itemPrice = $order->getItemPrice();

        foreach ($order->installment->steps as $step) {
            $payment = InstallmentOrderPayment::query()
                ->where('installment_order_id', $order->id)
                ->where('step_id', $step->id)
                ->where('status', 'paid')
                ->whereHas('sale', function ($query) {
                    $query->whereNull('refund_at');
                })
                ->first();

            if (empty($payment)) {
                $total += 1;
                $amount += $step->getPrice($itemPrice);
            }
        }

        return [
            'total' => $total,
            'amount' => $amount,
        ];
    }
    private function getOverdueOrderInstallments($order)
    {
        $total = 0;
        $amount = 0;

        $time = time();
        $itemPrice = $order->getItemPrice();

        foreach ($order->installment->steps as $step) {
            $dueAt = ($step->deadline * 86400) + $order->created_at;

            if ($dueAt < $time) {
                $payment = InstallmentOrderPayment::query()
                    ->where('installment_order_id', $order->id)
                    ->where('step_id', $step->id)
                    ->where('status', 'paid')
                    ->first();

                if (empty($payment)) {
                    $total += 1;
                    $amount += $step->getPrice($itemPrice);
                }
            }
        }

        return [
            'total' => $total,
            'amount' => $amount,
        ];
    }

    private function getUpcomingInstallment($order)
    {
        $result = null;
        $deadline = 0;

        foreach ($order->installment->steps as $step) {
            $payment = InstallmentOrderPayment::query()
                ->where('installment_order_id', $order->id)
                ->where('step_id', $step->id)
                ->where('status', 'paid')
                ->first();

            if (empty($payment) and ($deadline == 0 or $deadline > $step->deadline)) {
                $deadline = $step->deadline;
                $result = $step;
            }
        }

        return $result;
    }

    private function getOverdueInstallments($user)
    {
        $orders = InstallmentOrder::query()
            ->where('user_id', $user->id)
            ->where('installment_orders.status', 'open')
            ->get();

        $count = 0;

        foreach ($orders as $order) {
            if ($order->checkOrderHasOverdue()) {
                $count += 1;
            }
        }

        return $count;
    }

    private function getFinishedInstallments($user)
    {
        $orders = InstallmentOrder::query()
            ->where('user_id', $user->id)
            ->where('installment_orders.status', 'open')
            ->get();

        $count = 0;

        foreach ($orders as $order) {
            $steps = $order->installment->steps;
            $paidAllSteps = true;

            foreach ($steps as $step) {
                $payment = InstallmentOrderPayment::query()
                    ->where('installment_order_id', $order->id)
                    ->where('step_id', $step->id)
                    ->where('status', 'paid')
                    ->whereHas('sale', function ($query) {
                        $query->whereNull('refund_at');
                    })
                    ->first();

                if (empty($payment)) {
                    $paidAllSteps = false;
                }
            }

            if ($paidAllSteps) {
                $count += 1;
            }
        }

        return $count;
    }
        }