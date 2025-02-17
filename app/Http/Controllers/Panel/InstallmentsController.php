<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Mixins\Installment\InstallmentAccounting;
use App\Models\Cart;
use App\Models\WebinarPartPayment;
use App\Models\Sale;
use App\Models\Webinar;
use App\Models\InstallmentOrder;
use App\Models\InstallmentOrderPayment;
use App\Models\InstallmentStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstallmentsController extends Controller
{

    public function index()
    {
        $user = auth()->user();

        $query = InstallmentOrder::query()
            ->where('user_id', $user->id)
            ->where('status', '!=', 'paying');

        $openInstallmentsCount = deepClone($query)->where('status', 'open')->count();
        $pendingVerificationCount = deepClone($query)->where('status', 'pending_verification')->count();
        $finishedInstallmentsCount = $this->getFinishedInstallments($user);


        $orders = $query->with([
            'installment' => function ($query) {
                $query->with([
                    'steps' => function ($query) {
                        $query->orderBy('deadline', 'asc');
                    }
                ]);
                $query->withCount([
                    'steps'
                ]);
            }
        ])->orderBy('created_at', 'desc')
            ->paginate(10);

        foreach ($orders as $order) {
            $getRemainedInstallments = $this->getRemainedInstallments($order);

            $order->remained_installments_count = $getRemainedInstallments['total'];
            $order->remained_installments_amount = $getRemainedInstallments['amount'];

            $order->upcoming_installment = $this->getUpcomingInstallment($order);


            // is overdue
            $hasOverdue = $order->checkOrderHasOverdue();
            $order->has_overdue = $hasOverdue;
            $order->overdue_count = 0;
            $order->overdue_amount = 0;

            if ($hasOverdue) {
                $getOrderOverdueCountAndAmount = $order->getOrderOverdueCountAndAmount();
                $order->overdue_count = $getOrderOverdueCountAndAmount['count'];
                $order->overdue_amount = $getOrderOverdueCountAndAmount['amount'];
            }

        }

        $overdueInstallmentsCount = $this->getOverdueInstallments($user);

        $data = [
            'pageTitle' => trans('update.installments'),
            'openInstallmentsCount' => $openInstallmentsCount,
            'pendingVerificationCount' => $pendingVerificationCount,
            'finishedInstallmentsCount' => $finishedInstallmentsCount,
            'overdueInstallmentsCount' => $overdueInstallmentsCount,
            'orders' => $orders,
        ];

        return view('web.default.panel.financial.installments.lists', $data);
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

    public function show($orderId)
    {
        $user = auth()->user();

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
            $webinar= Webinar :: where('id',$order->webinar_id)->first();
            $webinar_title= $webinar->slug;
            
            $WebinarPartPayment = WebinarPartPayment :: select('user_id', 'webinar_id', 'installment_id', DB::raw('sum(amount) as total_amount'))
        ->where('user_id',$user->id)
        ->where('webinar_id',$order->webinar_id)
    ->groupBy('user_id', 'webinar_id')
    ->first();
    
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
                 // echo '<pre>';
                // print_r($order->id);
        
                    
                }
              $paidAmount = $totalSaleAmount  + (isset($WebinarPartPayment)?$WebinarPartPayment->total_amount:0);
            //   print_r($paidAmount);
            //   die();

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
                'paidAmount' => $paidAmount,
                'webinar_title' => $webinar_title,
            ];

            return view('web.default.panel.financial.installments.details', $data);
        }

        abort(404);
    }

    public function cancelVerification($orderId)
    {
        if (getInstallmentsSettings("allow_cancel_verification")) {
            $user = auth()->user();

            $order = InstallmentOrder::query()
                ->where('id', $orderId)
                ->where('user_id', $user->id)
                ->where('status', "pending_verification")
                ->first();

            if (!empty($order)) {
                $installmentRefund = new InstallmentAccounting();
                $installmentRefund->refundOrder($order);

                return response()->json([
                    'code' => 200,
                    'title' => trans('public.request_success'),
                    'text' => trans('update.order_status_changes_to_canceled'),
                ]);
            }
        }

        abort(404);
    }

    public function payUpcomingPart($orderId)
    {
        // die();
        $user = auth()->user();

        $order = InstallmentOrder::query()
            ->where('id', $orderId)
            ->where('user_id', $user->id)
            ->first();
            
            // print_r($order);
            


        if (!empty($order)) {
            $upcomingStep = $this->getUpcomingInstallment($order);

            if (!empty($upcomingStep)) {
                return $this->handlePayStep($order, $upcomingStep);
            }
        }

        abort(404);
    }

    public function payStep($orderId, $stepId)
    {
        $user = auth()->user();

        $order = InstallmentOrder::query()
            ->where('id', $orderId)
            ->where('user_id', $user->id)
            ->first();

        if (!empty($order)) {
            $step = InstallmentStep::query()
                ->where('installment_id', $order->installment_id)
                ->where('id', $stepId)
                ->first();

            if (!empty($step)) {
                return $this->handlePayStep($order, $step);
            }
        }

        abort(404);
    }

    private function handlePayStep($order, $step)
    {$user = auth()->user();
                    $WebinarPartPayment = WebinarPartPayment :: select('user_id', 'webinar_id', 'installment_id', DB::raw('sum(amount) as total_amount'))
        ->where('user_id',$user->id)
        ->where('webinar_id',$order->webinar_id)
    ->groupBy('user_id', 'webinar_id')
    ->first();
    $paidAmount=null;
    if($WebinarPartPayment){
    // print_r($WebinarPartPayment);
    // die();
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
                 // echo '<pre>';
                // print_r($order->id);
        
                    
                }
              
              
             $orderPaymentsTotalPaidAmount = InstallmentOrderPayment :: select('*', DB::raw('sum(amount) as total_amount'))
        ->where('installment_order_id',$order->id)
        ->where('status','paid')
    ->groupBy('installment_order_id')
    ->first();
    // print_r($orderPaymentsTotalPaidAmount);
    
    $paidAmount = $totalSaleAmount  +  (isset($WebinarPartPayment)?$WebinarPartPayment->total_amount:0) - (isset($orderPaymentsTotalPaidAmount)?$orderPaymentsTotalPaidAmount->total_amount:0);
    }
        
            //   print_r($paidAmount);
            //   die();
        
        
        
        $installmentPayment = InstallmentOrderPayment::query()->updateOrCreate([
            'installment_order_id' => $order->id,
            'sale_id' => null,
            'type' => 'step',
            'step_id' => $step->id,
            'amount' => $step->getPrice($order->getItemPrice()),
            'status' => 'paying',
        ], [
            'created_at' => time(),
        ]);

        Cart::updateOrCreate([
            'creator_id' => $order->user_id,
            'installment_payment_id' => $installmentPayment->id,
            'extra_amount' => $paidAmount,
        ], [
            'created_at' => time()
        ]);

        return redirect('/cart');
    }
}
