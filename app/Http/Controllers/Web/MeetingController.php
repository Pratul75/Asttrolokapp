<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Meeting;
use App\Models\MeetingTime;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentChannel;
use App\Models\ReserveMeeting;
use App\Models\Sale;
use App\Models\Setting;
use App\Models\Discount;
use Illuminate\Http\Request;
use App\Models\UserZoomLink;
use App\User;
use App\Models\UserSession;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Mixins\RegistrationBonus\RegistrationBonusAccounting;
use App\Models\Accounting;
use App\Models\Affiliate;
use App\Models\AffiliateCode;
use App\Models\Reward;
use App\Models\RewardAccounting;
use App\Models\Role;
use App\Models\UserMeta;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Cookie;



class MeetingController extends Controller
{
    public function reserve(Request $request)
    {
        // $user = auth()->user();
        $this->validate($request, [
            'full_name' => 'required|string|max:60',
            'email' => 'required|string|max:60',
            'mobile' => 'required|string|max:10',
        ]);
        

        // if (empty($user)) {
            $name = $request->input('full_name');
            $email = $request->input('email');
            $contact = $request->input('mobile');
            
             if (empty($name) or empty($contact) or empty($email)) {
            
            
            $toastData = [
                            'title' => trans('public.request_failed'),
                            'msg' => 'Please enter Name, Email and Contact Details',
                            'status' => 'error'
                        ];
                        return response()->json($toastData);
                        
             }else{
                 
                 
                 
                 
                 
                 
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
            'pwd_hint' => 123456,
            'affiliate' => 0,
            'timezone' => 'Asia/Kolkata' ?? null,
            'created_at' => time()
            ]);
            
            
        }
        $timeId = $request->input('time');
            $day = $request->input('day');
            $studentCount = $request->get('student_count', 1);
            $selectedMeetingType = $request->get('meeting_type', 'online');
            $description = $request->get('description');
            
            
            

            if (empty($studentCount)) {
                $studentCount = 1;
            }

            if (!in_array($selectedMeetingType, ['in_person', 'online'])) {
                $selectedMeetingType = 'online';
            }

            if (!empty($timeId)) {
                $meetingTime = MeetingTime::where('id', $timeId)
                    ->with('meeting')
                    ->first();

                if (!empty($meetingTime)) {
                    $meeting = $meetingTime->meeting;

                    if ($meeting->creator_id == $user->id) {
                        $toastData = [
                            'title' => trans('public.request_failed'),
                            'msg' => trans('update.cant_reserve_your_appointment'),
                            'status' => 'error'
                        ];
                        return response()->json($toastData);
                    }

                    if (!empty($meeting) and !$meeting->disabled) {
                        if (!empty($meeting->amount) and $meeting->amount > 0) {

                            $reserveMeeting = ReserveMeeting::where('meeting_time_id', $meetingTime->id)
                                ->where('day', $day)
                                ->first();

                            if (!empty($reserveMeeting) and $reserveMeeting->locked_at) {
                                $toastData = [
                                    'title' => trans('public.request_failed'),
                                    'msg' => trans('meeting.locked_time'),
                                    'status' => 'error'
                                ];
                                return response()->json($toastData);
                            }

                            if (!empty($reserveMeeting) and $reserveMeeting->reserved_at) {
                                $toastData = [
                                    'title' => trans('public.request_failed'),
                                    'msg' => trans('meeting.reserved_time'),
                                    'status' => 'error'
                                ];
                                return response()->json($toastData);
                            }

                            $hourlyAmountResult = $this->handleHourlyMeetingAmount($meeting, $meetingTime, $studentCount, $selectedMeetingType);
                            
                            
                            

                            if (!$hourlyAmountResult['status']) {
                                return $hourlyAmountResult['result']; // json response
                            }

                            $hourlyAmount = $hourlyAmountResult['result'];
                            $discountAmount = 0;
                            $discount_id=0;
                            date_default_timezone_set('Asia/Kolkata');
                            if(isset($meetingTime->meeting->discount)){
                                
                                $discountAmount = ($hourlyAmount * $meetingTime->meeting->discount)/100;
                                
                            }
                            if(session('meeting_discount_id')){
                                $discount_id=session('meeting_discount_id');
                                session(['meeting_discount_id' => 0]);
                                $discount = Discount::where('id', $discount_id)->where('source', 'meeting')->where('status', 'active')->first();
                                
                                 if($discount->expired_at>time()){
                                $discountCouponAmount = ($hourlyAmount * $discount->percent)/100;
                                $discountAmount = $discountAmount +  ($discountCouponAmount);
                                 }
                                }

                            $explodetime = explode('-', $meetingTime->time);

                            $hours = (strtotime($explodetime[1]) - strtotime($explodetime[0])) / 1800;

                            $instructorTimezone = $meeting->getTimezone();

                            $startAt = $this->handleUtcDate($day, $explodetime[0], $instructorTimezone);
                            $endAt = $this->handleUtcDate($day, $explodetime[1], $instructorTimezone);

                            $reserveMeeting = ReserveMeeting::updateOrCreate([
                                'user_id' => $user->id,
                                'meeting_time_id' => $meetingTime->id,
                                'meeting_id' => $meetingTime->meeting_id,
                                'status' => ReserveMeeting::$pending,
                                'day' => $day,
                                'meeting_type' => $selectedMeetingType,
                                'student_count' => $studentCount
                            ], [
                                'date' => strtotime($day),
                                'start_at' => $startAt,
                                'end_at' => $endAt,
                                'paid_amount' => (!empty($hourlyAmount) and $hourlyAmount > 0) ? ($hourlyAmount * $hours) - $discountAmount : 0,
                                'discount' => $meetingTime->meeting->discount,
                                'description' => $description,
                                'created_at' => time(),
                            ]);

                            // $cart = Cart::where('creator_id', $user->id)
                            //     ->where('reserve_meeting_id', $reserveMeeting->id)
                            //     ->first();

                            // if (empty($cart)) {
                            //     $cart= Cart::create([
                            //         'creator_id' => $user->id,
                            //         'reserve_meeting_id' => $reserveMeeting->id,
                            //         'created_at' => time()
                            //     ]);
                            // }
                            
                            $order = Order::create([
                                    'user_id' => $user->id,
                                    'status' => Order::$paying,
                                    'amount' => (!empty($hourlyAmount) and $hourlyAmount > 0) ? $hourlyAmount * $hours : 0,
                                    'tax' => 0,
                                    'total_discount' => $discountAmount,
                                    'total_amount' => (!empty($hourlyAmount) and $hourlyAmount > 0) ? ($hourlyAmount * $hours) - $discountAmount : 0,
                                    'product_delivery_fee' => 0,
                                    'created_at' => time(),
                                ]);
        
        
        
        
        
                                $orderitem =    OrderItem::create([
                                            'user_id' => $user->id,
                                            'order_id' => $order->id,
                                            'webinar_id' => null,
                                            'bundle_id' => null,
                                            'product_id' => null,
                                            'product_order_id' =>  null,
                                            'reserve_meeting_id' => $reserveMeeting->id ?? null,
                                            'subscribe_id' =>  null,
                                            'promotion_id' =>  null,
                                            'gift_id' =>  null,
                                            'installment_payment_id' =>  null,
                                            'ticket_id' =>  null,
                                            'discount_id' =>  $discount_id !=0 ? $discount->id : null,
                                            'amount' => (!empty($hourlyAmount) and $hourlyAmount > 0) ? $hourlyAmount * $hours : 0,
                                            'total_amount' => (!empty($hourlyAmount) and $hourlyAmount > 0) ? ($hourlyAmount * $hours)-$discountAmount : 0,
                                            'tax' => 0,
                                            'tax_price' => 0,
                                            'commission' => 0,
                                            'commission_price' => 0,
                                            'product_delivery_fee' => 0,
                                            'discount' => $discountAmount,
                                            'created_at' => time(),
                                        ]);
                            
                            // Print_r($order);
                            // Print_r('<br>');
                            // Print_r($orderitem);
                            
                            

                            $toastData = [
                                'title' => trans('Please Wait'),
                            // 'msg' => trans('meeting.meeting_disabled'),
                            'status' => 'success',
                                'name' => $name,
                                'email' => $email,
                                'contact' => $contact,
                                'amount' => (!empty($hourlyAmount) and $hourlyAmount > 0) ? ($hourlyAmount * $hours)-$discountAmount : 0,
                                'redirect' => '\razorpay\bookmeeting?name='.$name.'&email='.$email.'&amount='.((!empty($hourlyAmount) and $hourlyAmount > 0) ? ($hourlyAmount * $hours)-$discountAmount : 0).'&orderid='.$order->id.'&contact='.$contact,
                                
                            ];
                            
                            $orderItem=OrderItem::where('order_id', $order->id)->first();
$creater = User::where('id', $orderItem->reserveMeeting->meeting->creator_id)->first();

// $webhookurl='https://connect.pabbly.com/workflow/sendwebhookdata/IjU3NjUwNTY4MDYzNTA0M2M1MjZmNTUzNjUxMzci_pc';
$gohighlevel='https://services.leadconnectorhq.com/hooks/eAE21tVIbkFC6dUHwja9/webhook-trigger/8fcecb81-cd75-406e-990e-e1a348edfa17';
// ```````````````````````````````````````object
$webhookdata = [
                              'student_name' => $name,
                              'student_mobile' => $contact,
                              'student_email' => $email,
                              'consultant_id' => $creater->id,
                              'consultant_name' => $creater->full_name,
                              'consultant_mobile' => $creater->mobile,
                              'consultant_email' => $creater->email,
                              'meeting_start_at' => date('m/d/Y H:i:s', $orderItem->reserveMeeting->start_at),
                              'meeting_end_at' => date('m/d/Y H:i:s', $orderItem->reserveMeeting->end_at),
                              'paying_amount' => $orderItem->reserveMeeting->paid_amount,
                              'status' => 'paying',
                              'create_at' => date("Y/m/d H:i")
                            ];
// // Initializes a new cURL session
// $webhookcurl = curl_init($webhookurl);
// // Set the CURLOPT_RETURNTRANSFER option to true
// curl_setopt($webhookcurl, CURLOPT_RETURNTRANSFER, true);
// // Set the CURLOPT_POST option to true for POST request
// curl_setopt($webhookcurl, CURLOPT_POST, true);
// // Set the request data as JSON using json_encode function
// curl_setopt($webhookcurl, CURLOPT_POSTFIELDS,  json_encode($webhookdata));
// // Set custom headers for RapidAPI Auth and Content-Type header

// // Execute cURL request with all previous settings
// $webhookresponse = curl_exec($webhookcurl);
// // Close cURL session
// curl_close($webhookcurl);// Initializes a new cURL session






$gohighlevelcurl = curl_init($gohighlevel);
// Set the CURLOPT_RETURNTRANSFER option to true
curl_setopt($gohighlevelcurl, CURLOPT_RETURNTRANSFER, true);
// Set the CURLOPT_POST option to true for POST request
curl_setopt($gohighlevelcurl, CURLOPT_POST, true);
// Set the request data as JSON using json_encode function
curl_setopt($gohighlevelcurl, CURLOPT_POSTFIELDS,  json_encode($webhookdata));
// Set custom headers for RapidAPI Auth and Content-Type header
curl_setopt($gohighlevelcurl, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json', // Ensure JSON data is being sent
    'Accept: application/json' // Accept JSON response if needed
]);
// Execute cURL request with all previous settings
$gohighlevelresponse = curl_exec($gohighlevelcurl);
// Close cURL session
curl_close($gohighlevelcurl);
                    



                            
                            return response()->json($toastData);
                            
                        //   return redirect()->route('razorpay', ['id' => 1]);
                            
                            return view('web.default.razorpay.index',$toastData);
                        } else {
                            return $this->handleFreeMeetingReservation($user, $meeting, $meetingTime, $day, $selectedMeetingType, $studentCount);
                        }
                    } else {
                        $toastData = [
                            'title' => trans('public.request_failed'),
                            'msg' => trans('meeting.meeting_disabled'),
                            'status' => 'error'
                        ];
                        return response()->json($toastData);
                    }
                }
            }

            $toastData = [
                'title' => trans('public.request_failed'),
                'msg' => trans('meeting.select_time_to_reserve'),
                'status' => 'error'
            ];
            return response()->json($toastData);
                 
                 
                 
                 
                 
                 
                 
                 
                 
                 
                 
                 
                 
                 
                 
                 
                 
                 
                 
                 
                 
        //          $user = User::create([
        //     'role_name' => 'user',
        //     'role_id' => 1,
        //     'mobile' => $contact ?? null,
        //     'email' => $email ?? null,
        //     'full_name' => $name,
        //     // 'status' => User::$pending,
        //     'status'=>'active',
        //     'access_content' => 1,
        //     'password' => Hash::make(123456),
        //     'affiliate' => 0,
        //     'timezone' => 'Asia/Kolkata' ?? null,
        //     'created_at' => time()
        // ]);
        
        // session()->put('register_user',"yes");
        
        // $user = (new RegisterController)->register($request->all());
        
        
        
                // $toastData = [
                //             'title' => trans('public.request_failed'),
                //             'msg' => 'Mayank',
                //             'status' => 'error'
                //         ];
                //         return response()->json($toastData);
                 
             }
             
            
        // }
        
        
        
        
        
        
        
        // if (!empty($user)) {

        //     $timeId = $request->input('time');
        //     $day = $request->input('day');
        //     $studentCount = $request->get('student_count', 1);
        //     $selectedMeetingType = $request->get('meeting_type', 'online');
        //     $description = $request->get('description');

        //     if (empty($studentCount)) {
        //         $studentCount = 1;
        //     }

        //     if (!in_array($selectedMeetingType, ['in_person', 'online'])) {
        //         $selectedMeetingType = 'online';
        //     }

        //     if (!empty($timeId)) {
        //         $meetingTime = MeetingTime::where('id', $timeId)
        //             ->with('meeting')
        //             ->first();

        //         if (!empty($meetingTime)) {
        //             $meeting = $meetingTime->meeting;

        //             if ($meeting->creator_id == $user->id) {
        //                 $toastData = [
        //                     'title' => trans('public.request_failed'),
        //                     'msg' => trans('update.cant_reserve_your_appointment'),
        //                     'status' => 'error'
        //                 ];
        //                 return response()->json($toastData);
        //             }

        //             if (!empty($meeting) and !$meeting->disabled) {
        //                 if (!empty($meeting->amount) and $meeting->amount > 0) {

        //                     $reserveMeeting = ReserveMeeting::where('meeting_time_id', $meetingTime->id)
        //                         ->where('day', $day)
        //                         ->first();

        //                     if (!empty($reserveMeeting) and $reserveMeeting->locked_at) {
        //                         $toastData = [
        //                             'title' => trans('public.request_failed'),
        //                             'msg' => trans('meeting.locked_time'),
        //                             'status' => 'error'
        //                         ];
        //                         return response()->json($toastData);
        //                     }

        //                     if (!empty($reserveMeeting) and $reserveMeeting->reserved_at) {
        //                         $toastData = [
        //                             'title' => trans('public.request_failed'),
        //                             'msg' => trans('meeting.reserved_time'),
        //                             'status' => 'error'
        //                         ];
        //                         return response()->json($toastData);
        //                     }

        //                     $hourlyAmountResult = $this->handleHourlyMeetingAmount($meeting, $meetingTime, $studentCount, $selectedMeetingType);

        //                     if (!$hourlyAmountResult['status']) {
        //                         return $hourlyAmountResult['result']; // json response
        //                     }

        //                     $hourlyAmount = $hourlyAmountResult['result'];

        //                     $explodetime = explode('-', $meetingTime->time);

        //                     $hours = (strtotime($explodetime[1]) - strtotime($explodetime[0])) / 1800;

        //                     $instructorTimezone = $meeting->getTimezone();

        //                     $startAt = $this->handleUtcDate($day, $explodetime[0], $instructorTimezone);
        //                     $endAt = $this->handleUtcDate($day, $explodetime[1], $instructorTimezone);

        //                     $reserveMeeting = ReserveMeeting::updateOrCreate([
        //                         'user_id' => $user->id,
        //                         'meeting_time_id' => $meetingTime->id,
        //                         'meeting_id' => $meetingTime->meeting_id,
        //                         'status' => ReserveMeeting::$pending,
        //                         'day' => $day,
        //                         'meeting_type' => $selectedMeetingType,
        //                         'student_count' => $studentCount
        //                     ], [
        //                         'date' => strtotime($day),
        //                         'start_at' => $startAt,
        //                         'end_at' => $endAt,
        //                         'paid_amount' => (!empty($hourlyAmount) and $hourlyAmount > 0) ? $hourlyAmount * $hours : 0,
        //                         'discount' => $meetingTime->meeting->discount,
        //                         'description' => $description,
        //                         'created_at' => time(),
        //                     ]);

        //                     $cart = Cart::where('creator_id', $user->id)
        //                         ->where('reserve_meeting_id', $reserveMeeting->id)
        //                         ->first();

        //                     if (empty($cart)) {
        //                         Cart::create([
        //                             'creator_id' => $user->id,
        //                             'reserve_meeting_id' => $reserveMeeting->id,
        //                             'created_at' => time()
        //                         ]);
        //                     }

        //                     $toastData = [
        //                         'status' => 'success',
        //                         'title' => trans('public.request_success'),
        //                         'msg' => trans('update.meeting_added_to_cart'),
        //                         'redirect' => '/cart'
        //                     ];
        //                     return response()->json($toastData);
        //                 } else {
        //                     return $this->handleFreeMeetingReservation($user, $meeting, $meetingTime, $day, $selectedMeetingType, $studentCount);
        //                 }
        //             } else {
        //                 $toastData = [
        //                     'title' => trans('public.request_failed'),
        //                     'msg' => trans('meeting.meeting_disabled'),
        //                     'status' => 'error'
        //                 ];
        //                 return response()->json($toastData);
        //             }
        //         }
        //     }

        //     $toastData = [
        //         'title' => trans('public.request_failed'),
        //         'msg' => trans('meeting.select_time_to_reserve'),
        //         'status' => 'error'
        //     ];
        //     return response()->json($toastData);
        // }

        // $toastData = [
        //     'title' => trans('public.request_failed'),
        //     'msg' => trans('public.not_login_toast_msg_lang'),
        //     'status' => 'error'
        // ];
        // return response()->json($toastData);
    }
    
    public function reserve15(Request $request)
    {
        // $user = auth()->user();
        $this->validate($request, [
            'full_name' => 'required|string|max:60',
            'email' => 'required|string|max:60',
            'mobile' => 'required|string|max:10',
        ]);
        

        // if (empty($user)) {
            $name = $request->input('full_name');
            $email = $request->input('email');
            $contact = $request->input('mobile');
            
             if (empty($name) or empty($contact) or empty($email)) {
            
            
            $toastData = [
                            'title' => trans('public.request_failed'),
                            'msg' => 'Please enter Name, Email and Contact Details',
                            'status' => 'error'
                        ];
                        return response()->json($toastData);
                        
             }else{
                 
                 
                 
                 
                 
                 
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
            'pwd_hint' => 123456,
            'affiliate' => 0,
            'timezone' => 'Asia/Kolkata' ?? null,
            'created_at' => time()
            ]);
            
            
        }
        $timeId = $request->input('time');
            $day = $request->input('day');
            $studentCount = $request->get('student_count', 1);
            $selectedMeetingType = $request->get('meeting_type', 'online');
            $description = $request->get('description');

            if (empty($studentCount)) {
                $studentCount = 1;
            }

            if (!in_array($selectedMeetingType, ['in_person', 'online'])) {
                $selectedMeetingType = 'online';
            }
 
$fields = explode(',', $timeId);
if (count($fields) == 2)
{
    // carry on here...
    $timeId = intval($fields[0]);
    $slot_id = intval($fields[1]);
    // etc...
}

            if (!empty($timeId)) {
                $meetingTime = MeetingTime::where('id', $timeId)
                    ->with('meeting')
                    ->first();

                if (!empty($meetingTime)) {
                    $meeting = $meetingTime->meeting;

                    if ($meeting->creator_id == $user->id) {
                        $toastData = [
                            'title' => trans('public.request_failed'),
                            'msg' => trans('update.cant_reserve_your_appointment'),
                            'status' => 'error'
                        ];
                        return response()->json($toastData);
                    }

                    if (!empty($meeting) and !$meeting->disabled) {
                        if (!empty($meeting->amount) and $meeting->amount > 0) {

                            $reserveMeeting = ReserveMeeting::where('meeting_time_id', $meetingTime->id)
                                ->where('day', $day)
                                ->first();

                            if (!empty($reserveMeeting) and $reserveMeeting->locked_at) {
                                $toastData = [
                                    'title' => trans('public.request_failed'),
                                    'msg' => trans('meeting.locked_time'),
                                    'status' => 'error'
                                ];
                                return response()->json($toastData);
                            }

                            if (!empty($reserveMeeting) and $reserveMeeting->reserved_at and empty($reserveMeeting->slotid)) {
                                $toastData = [
                                    'title' => trans('public.request_failed'),
                                    'msg' => trans('meeting.reserved_time'),
                                    'status' => 'error'
                                ];
                                return response()->json($toastData);
                            }

                            $hourlyAmountResult = $this->handleHourlyMeetingAmount($meeting, $meetingTime, $studentCount, $selectedMeetingType);

                            if (!$hourlyAmountResult['status']) {
                                return $hourlyAmountResult['result']; // json response
                            }

                            $hourlyAmount = $hourlyAmountResult['result'];
                            
                            $discountAmount = 0;
                            $discount_id=0;
                            date_default_timezone_set('Asia/Kolkata');
                            if(isset($meetingTime->meeting->discount)){
                                
                                $discountAmount = ($hourlyAmount * $meetingTime->meeting->discount)/100;
                                
                            }
                            if(session('meeting_discount_id')){
                                $discount_id=session('meeting_discount_id');
                                session(['meeting_discount_id' => 0]);
                                $discount = Discount::where('id', $discount_id)->where('source', 'meeting')->where('status', 'active')->first();
                                
                                 if($discount->expired_at>time()){
                                $discountCouponAmount = ($hourlyAmount * $discount->percent)/100;
                                $discountAmount = $discountAmount +  ($discountCouponAmount);
                                 }
                                }

                            $explodetime = explode('-', $meetingTime->time);

                            $hours = (strtotime($explodetime[1]) - strtotime($explodetime[0])) / 1800;

                            $instructorTimezone = $meeting->getTimezone();

                            $startAt = $this->handleUtcDate($day, $explodetime[0], $instructorTimezone);
                            $endAt = $this->handleUtcDate($day, $explodetime[1], $instructorTimezone);

                            // $startAt = $slot_id==1 ? $startAt : strtotime("+15 minutes", strtotime($startAt));
                            // $endAt = $slot_id!=1 ? $endAt : strtotime("-15 minutes", strtotime($endAt));
                            
                            $reserveMeeting = ReserveMeeting::updateOrCreate([
                                'user_id' => $user->id,
                                'meeting_time_id' => $meetingTime->id,
                                'meeting_id' => $meetingTime->meeting_id,
                                'status' => ReserveMeeting::$pending,
                                'day' => $day,
                                'meeting_type' => $selectedMeetingType,
                                'student_count' => $studentCount,
                                'slotid' => $slot_id
                            ], [
                                'date' => strtotime($day),
                                'start_at' => $startAt,
                                'end_at' => $endAt,
                                'paid_amount' => (!empty($hourlyAmount) and $hourlyAmount > 0) ? (($hourlyAmount * $hours)-$discountAmount) /2: 0,
                                'discount' => $meetingTime->meeting->discount,
                                'description' => $description,
                                'created_at' => time(),
                            ]);

                            // $cart = Cart::where('creator_id', $user->id)
                            //     ->where('reserve_meeting_id', $reserveMeeting->id)
                            //     ->first();

                            // if (empty($cart)) {
                            //     $cart= Cart::create([
                            //         'creator_id' => $user->id,
                            //         'reserve_meeting_id' => $reserveMeeting->id,
                            //         'created_at' => time()
                            //     ]);
                            // }
                            
                            $order = Order::create([
                                    'user_id' => $user->id,
                                    'status' => Order::$paying,
                                    'amount' => (!empty($hourlyAmount) and $hourlyAmount > 0) ? $hourlyAmount * $hours /2: 0,
                                    'tax' => 0,
                                    'total_discount' => $discountAmount/2,
                                    'total_amount' => (!empty($hourlyAmount) and $hourlyAmount > 0) ? (($hourlyAmount * $hours)-$discountAmount) /2: 0,
                                    'product_delivery_fee' => 0,
                                    'created_at' => time(),
                                ]);
        
        
        
        
        
                                $orderitem =    OrderItem::create([
                                            'user_id' => $user->id,
                                            'order_id' => $order->id,
                                            'webinar_id' => null,
                                            'bundle_id' => null,
                                            'product_id' => null,
                                            'product_order_id' =>  null,
                                            'reserve_meeting_id' => $reserveMeeting->id ?? null,
                                            'subscribe_id' =>  null,
                                            'promotion_id' =>  null,
                                            'gift_id' =>  null,
                                            'installment_payment_id' =>  null,
                                            'ticket_id' =>  null,
                                            'discount_id' =>  $discount_id !=0 ? $discount_id : null,
                                            'amount' => (!empty($hourlyAmount) and $hourlyAmount > 0) ? $hourlyAmount * $hours/2 : 0,
                                            'total_amount' => (!empty($hourlyAmount) and $hourlyAmount > 0) ? (($hourlyAmount * $hours)-$discountAmount) /2 : 0,
                                            'tax' => 0,
                                            'tax_price' => 0,
                                            'commission' => 0,
                                            'commission_price' => 0,
                                            'product_delivery_fee' => 0,
                                            'discount' => $discountAmount/2,
                                            'created_at' => time(),
                                        ]);
                            
                            // Print_r($order);
                            // Print_r('<br>');
                            // Print_r($orderitem);
                            
                            

                            $toastData = [
                                'title' => trans('Please Wait'),
                            // 'msg' => trans('meeting.meeting_disabled'),
                            'status' => 'success',
                                'name' => $name,
                                'email' => $email,
                                'contact' => $contact,
                                'amount' => (!empty($hourlyAmount) and $hourlyAmount > 0) ? (($hourlyAmount * $hours)-$discountAmount) /2 : 0,
                                'redirect' => '\razorpay\bookmeeting?name='.$name.'&email='.$email.'&amount='.((!empty($hourlyAmount) and $hourlyAmount > 0) ? (($hourlyAmount * $hours)-$discountAmount) /2 : 0).'&orderid='.$order->id.'&contact='.$contact,
                                
                            ];
                            
                            $orderItem=OrderItem::where('order_id', $order->id)->first();
$creater = User::where('id', $orderItem->reserveMeeting->meeting->creator_id)->first();
date_default_timezone_set('Asia/Kolkata');
// $webhookurl='https://connect.pabbly.com/workflow/sendwebhookdata/IjU3NjUwNTY4MDYzNTA0M2M1MjZmNTUzNjUxMzci_pc';

$gohighlevel='https://services.leadconnectorhq.com/hooks/eAE21tVIbkFC6dUHwja9/webhook-trigger/8fcecb81-cd75-406e-990e-e1a348edfa17';
// ```````````````````````````````````````object
$webhookdata = [
                              'student_name' => $name,
                              'student_mobile' => $contact,
                              'student_email' => $email,
                              'consultant_id' => $creater->id,
                              'consultant_name' => $creater->full_name,
                              'consultant_mobile' => $creater->mobile,
                              'consultant_email' => $creater->email,
                              'meeting_start_at' => date('m/d/Y H:i:s', $orderItem->reserveMeeting->start_at),
                              'meeting_end_at' => date('m/d/Y H:i:s', $orderItem->reserveMeeting->end_at),
                              'paying_amount' => $orderItem->reserveMeeting->paid_amount,
                              'status' => 'paying',
                              'create_at' => date("Y/m/d H:i")
                            ];
// // Initializes a new cURL session
// $webhookcurl = curl_init($webhookurl);
// // Set the CURLOPT_RETURNTRANSFER option to true
// curl_setopt($webhookcurl, CURLOPT_RETURNTRANSFER, true);
// // Set the CURLOPT_POST option to true for POST request
// curl_setopt($webhookcurl, CURLOPT_POST, true);
// // Set the request data as JSON using json_encode function
// curl_setopt($webhookcurl, CURLOPT_POSTFIELDS,  json_encode($webhookdata));
// // Set custom headers for RapidAPI Auth and Content-Type header

// // Execute cURL request with all previous settings
// $webhookresponse = curl_exec($webhookcurl);
// // Close cURL session
// curl_close($webhookcurl);
                    



$gohighlevelcurl = curl_init($gohighlevel);
// Set the CURLOPT_RETURNTRANSFER option to true
curl_setopt($gohighlevelcurl, CURLOPT_RETURNTRANSFER, true);
// Set the CURLOPT_POST option to true for POST request
curl_setopt($gohighlevelcurl, CURLOPT_POST, true);
// Set the request data as JSON using json_encode function
// curl_setopt($gohighlevelcurl, CURLOPT_POSTFIELDS,  json_encode($webhookdata));
curl_setopt($gohighlevelcurl, CURLOPT_POSTFIELDS, json_encode($webhookdata));
// Set custom headers for RapidAPI Auth and Content-Type header
curl_setopt($gohighlevelcurl, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json', // Ensure JSON data is being sent
    'Accept: application/json' // Accept JSON response if needed
]);
// Execute cURL request with all previous settings
$gohighlevelresponse = curl_exec($gohighlevelcurl);
// Close cURL session
curl_close($gohighlevelcurl);
                    
 



// Set the necessary headers

                            return response()->json($toastData);
                            
                        //   return redirect()->route('razorpay', ['id' => 1]);
                            
                            return view('web.default.razorpay.index',$toastData);
                        } else {
                            return $this->handleFreeMeetingReservation($user, $meeting, $meetingTime, $day, $selectedMeetingType, $studentCount);
                        }
                    } else {
                        $toastData = [
                            'title' => trans('public.request_failed'),
                            'msg' => trans('meeting.meeting_disabled'),
                            'status' => 'error'
                        ];
                        return response()->json($toastData);
                    }
                }
            }

            $toastData = [
                'title' => trans('public.request_failed'),
                'msg' => trans('meeting.select_time_to_reserve'),
                'status' => 'error'
            ];
            return response()->json($toastData);
                 
                 
                 
                 
                 
                 
                 
                 
                 
                 
                 
                 
                 
                 
                 
                 
                 
                 
                 
                 
                 
        //          $user = User::create([
        //     'role_name' => 'user',
        //     'role_id' => 1,
        //     'mobile' => $contact ?? null,
        //     'email' => $email ?? null,
        //     'full_name' => $name,
        //     // 'status' => User::$pending,
        //     'status'=>'active',
        //     'access_content' => 1,
        //     'password' => Hash::make(123456),
        //     'affiliate' => 0,
        //     'timezone' => 'Asia/Kolkata' ?? null,
        //     'created_at' => time()
        // ]);
        
        // session()->put('register_user',"yes");
        
        // $user = (new RegisterController)->register($request->all());
        
        
        
                // $toastData = [
                //             'title' => trans('public.request_failed'),
                //             'msg' => 'Mayank',
                //             'status' => 'error'
                //         ];
                //         return response()->json($toastData);
                 
             }
             
            
        // }
        
        
        
        
        
        
        
        // if (!empty($user)) {

        //     $timeId = $request->input('time');
        //     $day = $request->input('day');
        //     $studentCount = $request->get('student_count', 1);
        //     $selectedMeetingType = $request->get('meeting_type', 'online');
        //     $description = $request->get('description');

        //     if (empty($studentCount)) {
        //         $studentCount = 1;
        //     }

        //     if (!in_array($selectedMeetingType, ['in_person', 'online'])) {
        //         $selectedMeetingType = 'online';
        //     }

        //     if (!empty($timeId)) {
        //         $meetingTime = MeetingTime::where('id', $timeId)
        //             ->with('meeting')
        //             ->first();

        //         if (!empty($meetingTime)) {
        //             $meeting = $meetingTime->meeting;

        //             if ($meeting->creator_id == $user->id) {
        //                 $toastData = [
        //                     'title' => trans('public.request_failed'),
        //                     'msg' => trans('update.cant_reserve_your_appointment'),
        //                     'status' => 'error'
        //                 ];
        //                 return response()->json($toastData);
        //             }

        //             if (!empty($meeting) and !$meeting->disabled) {
        //                 if (!empty($meeting->amount) and $meeting->amount > 0) {

        //                     $reserveMeeting = ReserveMeeting::where('meeting_time_id', $meetingTime->id)
        //                         ->where('day', $day)
        //                         ->first();

        //                     if (!empty($reserveMeeting) and $reserveMeeting->locked_at) {
        //                         $toastData = [
        //                             'title' => trans('public.request_failed'),
        //                             'msg' => trans('meeting.locked_time'),
        //                             'status' => 'error'
        //                         ];
        //                         return response()->json($toastData);
        //                     }

        //                     if (!empty($reserveMeeting) and $reserveMeeting->reserved_at) {
        //                         $toastData = [
        //                             'title' => trans('public.request_failed'),
        //                             'msg' => trans('meeting.reserved_time'),
        //                             'status' => 'error'
        //                         ];
        //                         return response()->json($toastData);
        //                     }

        //                     $hourlyAmountResult = $this->handleHourlyMeetingAmount($meeting, $meetingTime, $studentCount, $selectedMeetingType);

        //                     if (!$hourlyAmountResult['status']) {
        //                         return $hourlyAmountResult['result']; // json response
        //                     }

        //                     $hourlyAmount = $hourlyAmountResult['result'];

        //                     $explodetime = explode('-', $meetingTime->time);

        //                     $hours = (strtotime($explodetime[1]) - strtotime($explodetime[0])) / 1800;

        //                     $instructorTimezone = $meeting->getTimezone();

        //                     $startAt = $this->handleUtcDate($day, $explodetime[0], $instructorTimezone);
        //                     $endAt = $this->handleUtcDate($day, $explodetime[1], $instructorTimezone);

        //                     $reserveMeeting = ReserveMeeting::updateOrCreate([
        //                         'user_id' => $user->id,
        //                         'meeting_time_id' => $meetingTime->id,
        //                         'meeting_id' => $meetingTime->meeting_id,
        //                         'status' => ReserveMeeting::$pending,
        //                         'day' => $day,
        //                         'meeting_type' => $selectedMeetingType,
        //                         'student_count' => $studentCount
        //                     ], [
        //                         'date' => strtotime($day),
        //                         'start_at' => $startAt,
        //                         'end_at' => $endAt,
        //                         'paid_amount' => (!empty($hourlyAmount) and $hourlyAmount > 0) ? $hourlyAmount * $hours : 0,
        //                         'discount' => $meetingTime->meeting->discount,
        //                         'description' => $description,
        //                         'created_at' => time(),
        //                     ]);

        //                     $cart = Cart::where('creator_id', $user->id)
        //                         ->where('reserve_meeting_id', $reserveMeeting->id)
        //                         ->first();

        //                     if (empty($cart)) {
        //                         Cart::create([
        //                             'creator_id' => $user->id,
        //                             'reserve_meeting_id' => $reserveMeeting->id,
        //                             'created_at' => time()
        //                         ]);
        //                     }

        //                     $toastData = [
        //                         'status' => 'success',
        //                         'title' => trans('public.request_success'),
        //                         'msg' => trans('update.meeting_added_to_cart'),
        //                         'redirect' => '/cart'
        //                     ];
        //                     return response()->json($toastData);
        //                 } else {
        //                     return $this->handleFreeMeetingReservation($user, $meeting, $meetingTime, $day, $selectedMeetingType, $studentCount);
        //                 }
        //             } else {
        //                 $toastData = [
        //                     'title' => trans('public.request_failed'),
        //                     'msg' => trans('meeting.meeting_disabled'),
        //                     'status' => 'error'
        //                 ];
        //                 return response()->json($toastData);
        //             }
        //         }
        //     }

        //     $toastData = [
        //         'title' => trans('public.request_failed'),
        //         'msg' => trans('meeting.select_time_to_reserve'),
        //         'status' => 'error'
        //     ];
        //     return response()->json($toastData);
        // }

        // $toastData = [
        //     'title' => trans('public.request_failed'),
        //     'msg' => trans('public.not_login_toast_msg_lang'),
        //     'status' => 'error'
        // ];
        // return response()->json($toastData);
    }

 public function reserve1(Request $request)
    {
        // $user = auth()->user();
        
        // if (empty($user)) {
            $name = $request->input('full_name');
            $email = $request->input('email');
            $contact = $request->input('mobile');
            $birthplace = $request->input('birthplace');
             
             $birthtime = $request->input('birthtime');
              $birthdate = $request->input('birthdate');
                
             if (empty($name) or empty($contact) or empty($email)) {
            
            
            $toastData = [
                            'title' => trans('public.request_failed'),
                            'msg' => 'Please enter Name, Email and Contact Details',
                            'status' => 'error'
                        ];
                return response()->json($toastData);
                        
             }else{
                 
              
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
            'pwd_hint' => 123456,
            'affiliate' => 0,
            'timezone' => 'Asia/Kolkata' ?? null,
            'created_at' => time()
            ]);
            
            
        }
            $timeId = $request->input('time');
            $day = $request->input('day');
            $studentCount = $request->get('student_count', 1);
            $selectedMeetingType = $request->get('meeting_type', 'online');
            $description = $request->get('description');

            if (empty($studentCount)) {
                $studentCount = 1;
            }

            if (!in_array($selectedMeetingType, ['in_person', 'online'])) {
                $selectedMeetingType = 'online';
            }

            if (!empty($timeId)) {
                $meetingTime = MeetingTime::where('id', $timeId)
                    ->with('meeting')
                    ->first();

                if (!empty($meetingTime)) {
                    $meeting = $meetingTime->meeting;

                    if ($meeting->creator_id == $user->id) {
                        $toastData = [
                            'title' => trans('public.request_failed'),
                            'msg' => trans('update.cant_reserve_your_appointment'),
                            'status' => 'error'
                        ];
                        return response()->json($toastData);
                    }

                    if (!empty($meeting) and !$meeting->disabled) {
                        if (!empty($meeting->amount) and $meeting->amount > 0) {

                            $reserveMeeting = ReserveMeeting::where('meeting_time_id', $meetingTime->id)
                                ->where('day', $day)
                                ->first();

                            if (!empty($reserveMeeting) and $reserveMeeting->locked_at) {
                                $toastData = [
                                    'title' => trans('public.request_failed'),
                                    'msg' => trans('meeting.locked_time'),
                                    'status' => 'error'
                                ];
                                return response()->json($toastData);
                            }

                            if (!empty($reserveMeeting) and $reserveMeeting->reserved_at) {
                                $toastData = [
                                    'title' => trans('public.request_failed'),
                                    'msg' => trans('meeting.reserved_time'),
                                    'status' => 'error'
                                ];
                                return response()->json($toastData);
                            }

                            $hourlyAmountResult = $this->handleHourlyMeetingAmount($meeting, $meetingTime, $studentCount, $selectedMeetingType);

                            if (!$hourlyAmountResult['status']) {
                                return $hourlyAmountResult['result']; // json response
                            }

                            $hourlyAmount = $hourlyAmountResult['result'];

                            $explodetime = explode('-', $meetingTime->time);

                            $hours = (strtotime($explodetime[1]) - strtotime($explodetime[0])) / 1800;

                            $instructorTimezone = $meeting->getTimezone();

                            $startAt = $this->handleUtcDate($day, $explodetime[0], $instructorTimezone);
                            $endAt = $this->handleUtcDate($day, $explodetime[1], $instructorTimezone);

                            $reserveMeeting = ReserveMeeting::updateOrCreate([
                                'user_id' => $user->id,
                                'meeting_time_id' => $meetingTime->id,
                                'meeting_id' => $meetingTime->meeting_id,
                                'status' => ReserveMeeting::$pending,
                                'day' => $day,
                                'meeting_type' => $selectedMeetingType,
                                'student_count' => $studentCount
                            ], [
                                'date' => strtotime($day),
                                'start_at' => $startAt,
                                'end_at' => $endAt,
                                'paid_amount' => (!empty($hourlyAmount) and $hourlyAmount > 0) ? $hourlyAmount * $hours : 0,
                                'discount' => $meetingTime->meeting->discount,
                                'description' => $description,
                                'created_at' => time(),
                            ]);

                            // $cart = Cart::where('creator_id', $user->id)
                            //     ->where('reserve_meeting_id', $reserveMeeting->id)
                            //     ->first();

                            // if (empty($cart)) {
                            //     $cart= Cart::create([
                            //         'creator_id' => $user->id,
                            //         'reserve_meeting_id' => $reserveMeeting->id,
                            //         'created_at' => time()
                            //     ]);
                            // }
                            
                            $order = Order::create([
                                    'user_id' => $user->id,
                                    'status' => Order::$paying,
                                    'amount' => (!empty($hourlyAmount) and $hourlyAmount > 0) ? $hourlyAmount * $hours : 0,
                                    'tax' => 0,
                                    'total_discount' => 0,
                                    'total_amount' => (!empty($hourlyAmount) and $hourlyAmount > 0) ? $hourlyAmount * $hours : 0,
                                    'product_delivery_fee' => 0,
                                    'created_at' => time(),
                                ]);
        
                                $orderitem =    OrderItem::create([
                                            'user_id' => $user->id,
                                            'order_id' => $order->id,
                                            'webinar_id' => null,
                                            'bundle_id' => null,
                                            'product_id' => null,
                                            'product_order_id' =>  null,
                                            'reserve_meeting_id' => $reserveMeeting->id ?? null,
                                            'subscribe_id' =>  null,
                                            'promotion_id' =>  null,
                                            'gift_id' =>  null,
                                            'installment_payment_id' =>  null,
                                            'ticket_id' =>  null,
                                            'discount_id' =>  null,
                                            'amount' => (!empty($hourlyAmount) and $hourlyAmount > 0) ? $hourlyAmount * $hours : 0,
                                            'total_amount' => (!empty($hourlyAmount) and $hourlyAmount > 0) ? $hourlyAmount * $hours : 0,
                                            'tax' => 0,
                                            'tax_price' => 0,
                                            'commission' => 0,
                                            'commission_price' => 0,
                                            'product_delivery_fee' => 0,
                                            'discount' => 0,
                                            'created_at' => time(),
                                        ]);
                            
                            // Print_r($order);
                            // Print_r('<br>');
                            // Print_r($orderitem);
                            
                            // $toastData = [
                            //     'title' => trans('public.request_failed'),
                            //     'msg' => trans('meeting.meeting_disabled'),
                            //     'status' => 'success',
                            //         'name' => $name,
                            //         'email' => $email,
                            //         'contact' => $contact,
                            //         'amount' => (!empty($hourlyAmount) and $hourlyAmount > 0) ? $hourlyAmount * $hours : 0,
                            //         'redirect' => '\razorpay\pay?name='.$name.'&email='.$email.'&amount='.((!empty($hourlyAmount) and $hourlyAmount > 0) ? $hourlyAmount * $hours : 0).'&orderid='.$order->id.'&contact='.$contact,
                                    
                            //     ];
                            // return response()->json($toastData);
                            
                        //   return redirect()->route('razorpay', ['id' => 1]);
                        
                        
                        // update pay details 
                        
                        
                        //  $order=Order::where('id', $data1['order_id'])
            // ->first();
            
         if (!empty($order)) {
             
             $order->update(['payment_method' => 'payment_channel']);
            
            $orderItem=OrderItem::where('order_id',  $order->id)->first();
            
            
            if ($order->status == Order::$paying) {
                
                $sale = Sale::createSales($orderItem, $order->payment_method);
                
                
                if (!empty($orderItem->reserve_meeting_id)) {
                    $reserveMeeting = ReserveMeeting::where('id', $orderItem->reserve_meeting_id)->first();
                    $creater = User::where('id', $orderItem->reserveMeeting->meeting->creator_id)->first();
                    

                    $reserveMeeting->update([
                        'sale_id' => $sale->id,
                        'reserved_at' => time()
                    ]);
 
                $order->update(['status' => Order::$paid]);
                
                Accounting::createAccounting($orderItem, null);
                }
                
                
        //     } else {
        //         if ($order->type === Order::$meeting) {
        //             $orderItem = OrderItem::where('order_id', $order->id)->first();

        //             if ($orderItem && $orderItem->reserve_meeting_id) {
        //                 $reserveMeeting = ReserveMeeting::where('id', $orderItem->reserve_meeting_id)->first();

        //                 if ($reserveMeeting) {
        //                     $reserveMeeting->update(['locked_at' => null]);
        //                 }
        //             }
        //         }
        
        
        //  $orderItem=OrderItem::where('order_id', $data1['order_id'])->first();
        $creater = User::where('id', $orderItem->reserveMeeting->meeting->creator_id)->first();
        // print_r($orderItem->reserveMeeting->meeting->creator_id);die;
        $createrzoom = UserZoomLink::where('user_id', $orderItem->reserveMeeting->meeting->creator_id)->first();
        
        
                           date_default_timezone_set('Asia/Kolkata');
  
 $mail_befor_1_hour=-1;
 $mail_befor_1_day=-1;
  if(!empty($createrzoom)){
      $start = strtotime(date("m/d/Y h:i"));
$stop = strtotime(date('m/d/Y H:i:s', $orderItem->reserveMeeting->start_at));
$diff = ($stop - $start);
$mail_befor_1_day = ($diff/60)-1440;                   
$mail_befor_1_hour = ($diff/60)-60;                   
                    
  } 
  
 $value = $contact;

$mobileregex = "/^[0-9]{10}$/";
if(preg_match($mobileregex, $value)===0){
   $value1= preg_replace('/[^0-9]/', '', $value);
    $len = strlen($value1);
    // print_r($len);
    
            if($len==13) {
    preg_match( '/^(\d{3})(\d{10})$/', $value1,  $matches );
    $result =$matches[2];
    $contact= $result;
} 
    
    if($len==12) {
    preg_match( '/^(\d{2})(\d{10})$/', $value1,  $matches );
    $result =$matches[2];
    $contact= $result;
}    
    if($len==11) {
    preg_match( '/^(\d{1})(\d{10})$/', $value1,  $matches );
    $result =$matches[2];
    $contact= $result;
}

}  

 $value2 = $creater->mobile;
$value23 = $creater->mobile;
$mobileregex = "/^[0-9]{10}$/";
if(preg_match($mobileregex, $value2)===0){
   $value21= preg_replace('/[^0-9]/', '', $value2);
    $len = strlen($value21);
    // print_r($len);
    
        if($len==13) {
    preg_match( '/^(\d{3})(\d{10})$/', $value21,  $matches );
    $result =$matches[2];
    $value23= $result;
}  
    
    if($len==12) {
    preg_match( '/^(\d{2})(\d{10})$/', $value21,  $matches );
    $result =$matches[2];
    $value23= $result;
}    
    if($len==11) {
    preg_match( '/^(\d{1})(\d{10})$/', $value21,  $matches );
    $result =$matches[2];
    $value23= $result;
}

}
  
  
  
  
  $name = $request->input('full_name');
            $email = $request->input('email');
  
// 	  $webhookurl='https://connect.pabbly.com/workflow/sendwebhookdata/IjU3NjUwNTY5MDYzNjA0M2Q1MjY4NTUzMDUxMzMi_pc';
	 $gohighlevel= 'https://services.leadconnectorhq.com/hooks/eAE21tVIbkFC6dUHwja9/webhook-trigger/6bcc434d-8597-4cce-ae5d-8110cf5dbff7';
// Collection object
$webhookdata = [
  'student_id' => $orderItem->user_id,
  'student_name' => $name,
  'student_mobile' => '91'.$contact,
  'student_email' => $email,
  'consultant_id' => $creater->id,
  'consultant_name' => $creater->full_name,
  'consultant_mobile' => $value23,
  'consultant_email' => $creater->email,
  'consultant_zoom_user' => !empty($createrzoom)?$createrzoom->gmail:'null',
  'consultant_zoom_pwd' => !empty($createrzoom)?$createrzoom->zoom_pwd:'null',
  'birth_date' => $birthdate,
  'birth_time' => $birthtime,
  'birth_place' => $birthplace,
  'meeting_start_at' => date('m/d/Y H:i:s', $orderItem->reserveMeeting->start_at),
  'meeting_end_at' => date('m/d/Y H:i:s', $orderItem->reserveMeeting->end_at),
  'meeting_link' => !empty($createrzoom)?$createrzoom->zoom_link:'null',
  'mail_befor_1_hour' => $mail_befor_1_hour>0?$mail_befor_1_hour:'null',
  'mail_befor_1_day' => $mail_befor_1_day>0?$mail_befor_1_day:'null',
  'paid_amount' => $orderItem->reserveMeeting->paid_amount,
  'create_at' => date("Y/m/d H:i")
];
print_r($webhookdata);
// // Initializes a new cURL session
// $webhookcurl = curl_init($webhookurl);
// // Set the CURLOPT_RETURNTRANSFER option to true
// curl_setopt($webhookcurl, CURLOPT_RETURNTRANSFER, true);
// // Set the CURLOPT_POST option to true for POST request
// curl_setopt($webhookcurl, CURLOPT_POST, true);
// // Set the request data as JSON using json_encode function
// curl_setopt($webhookcurl, CURLOPT_POSTFIELDS,  json_encode($webhookdata));
// // Set custom headers for RapidAPI Auth and Content-Type header

// // Execute cURL request with all previous settings
// $webhookresponse = curl_exec($webhookcurl);
// // Close cURL session
// curl_close($webhookcurl);
        
        
        
        
$gohighlevelcurl = curl_init($gohighlevel);
// Set the CURLOPT_RETURNTRANSFER option to true
curl_setopt($gohighlevelcurl, CURLOPT_RETURNTRANSFER, true);
// Set the CURLOPT_POST option to true for POST request
curl_setopt($gohighlevelcurl, CURLOPT_POST, true);
// Set the request data as JSON using json_encode function
// curl_setopt($gohighlevelcurl, CURLOPT_POSTFIELDS,  json_encode($webhookdata));
curl_setopt($gohighlevelcurl, CURLOPT_POSTFIELDS, json_encode($webhookdata));
// Set custom headers for RapidAPI Auth and Content-Type header
curl_setopt($gohighlevelcurl, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json', // Ensure JSON data is being sent
    'Accept: application/json' // Accept JSON response if needed
]);
// Execute cURL request with all previous settings
$gohighlevelresponse = curl_exec($gohighlevelcurl);
print_r($gohighlevelresponse);
// Close cURL session
curl_close($gohighlevelcurl);
        
        
        session()->forget('razorpay_payment_id');
        session()->forget('status_code');
        session()->forget('consult_id');
        session()->forget('email');
        session()->forget('name');
        session()->forget('contact');
        session()->forget('_token');
        
        //  return view('web.default.razorpay.thankyou');
          
            }

        }
                        
                        
                            // return view('web.default.razorpay.index',$toastData);
                        } else {
                            return $this->handleFreeMeetingReservation($user, $meeting, $meetingTime, $day, $selectedMeetingType, $studentCount);
                        }
                    } else {
                        $toastData = [
                            'title' => trans('public.request_failed'),
                            'msg' => trans('meeting.meeting_disabled'),
                            'status' => 'error'
                        ];
                        return response()->json($toastData);
                    }
                }
            }

            $toastData = [
                'title' => trans('public.request_failed'),
                'msg' => trans('meeting.select_time_to_reserve'),
                'status' => 'error'
            ];
            return response()->json($toastData);
                 
              
        //      $user = User::create([
        //     'role_name' => 'user',
        //     'role_id' => 1,
        //     'mobile' => $contact ?? null,
        //     'email' => $email ?? null,
        //     'full_name' => $name,
        //     // 'status' => User::$pending,
        //     'status'=>'active',
        //     'access_content' => 1,
        //     'password' => Hash::make(123456),
        //     'affiliate' => 0,
        //     'timezone' => 'Asia/Kolkata' ?? null,
        //     'created_at' => time()
        // ]);
        
        // session()->put('register_user',"yes");
        
        // $user = (new RegisterController)->register($request->all());
        
        
        
                // $toastData = [
                //             'title' => trans('public.request_failed'),
                //             'msg' => 'Mayank',
                //             'status' => 'error'
                //         ];
                //         return response()->json($toastData);
                 
             }
             
            
        // }
        
        
        
        
        
        
        
        // if (!empty($user)) {

        //     $timeId = $request->input('time');
        //     $day = $request->input('day');
        //     $studentCount = $request->get('student_count', 1);
        //     $selectedMeetingType = $request->get('meeting_type', 'online');
        //     $description = $request->get('description');

        //     if (empty($studentCount)) {
        //         $studentCount = 1;
        //     }

        //     if (!in_array($selectedMeetingType, ['in_person', 'online'])) {
        //         $selectedMeetingType = 'online';
        //     }

        //     if (!empty($timeId)) {
        //         $meetingTime = MeetingTime::where('id', $timeId)
        //             ->with('meeting')
        //             ->first();

        //         if (!empty($meetingTime)) {
        //             $meeting = $meetingTime->meeting;

        //             if ($meeting->creator_id == $user->id) {
        //                 $toastData = [
        //                     'title' => trans('public.request_failed'),
        //                     'msg' => trans('update.cant_reserve_your_appointment'),
        //                     'status' => 'error'
        //                 ];
        //                 return response()->json($toastData);
        //             }

        //             if (!empty($meeting) and !$meeting->disabled) {
        //                 if (!empty($meeting->amount) and $meeting->amount > 0) {

        //                     $reserveMeeting = ReserveMeeting::where('meeting_time_id', $meetingTime->id)
        //                         ->where('day', $day)
        //                         ->first();

        //                     if (!empty($reserveMeeting) and $reserveMeeting->locked_at) {
        //                         $toastData = [
        //                             'title' => trans('public.request_failed'),
        //                             'msg' => trans('meeting.locked_time'),
        //                             'status' => 'error'
        //                         ];
        //                         return response()->json($toastData);
        //                     }

        //                     if (!empty($reserveMeeting) and $reserveMeeting->reserved_at) {
        //                         $toastData = [
        //                             'title' => trans('public.request_failed'),
        //                             'msg' => trans('meeting.reserved_time'),
        //                             'status' => 'error'
        //                         ];
        //                         return response()->json($toastData);
        //                     }

        //                     $hourlyAmountResult = $this->handleHourlyMeetingAmount($meeting, $meetingTime, $studentCount, $selectedMeetingType);

        //                     if (!$hourlyAmountResult['status']) {
        //                         return $hourlyAmountResult['result']; // json response
        //                     }

        //                     $hourlyAmount = $hourlyAmountResult['result'];

        //                     $explodetime = explode('-', $meetingTime->time);

        //                     $hours = (strtotime($explodetime[1]) - strtotime($explodetime[0])) / 1800;

        //                     $instructorTimezone = $meeting->getTimezone();

        //                     $startAt = $this->handleUtcDate($day, $explodetime[0], $instructorTimezone);
        //                     $endAt = $this->handleUtcDate($day, $explodetime[1], $instructorTimezone);

        //                     $reserveMeeting = ReserveMeeting::updateOrCreate([
        //                         'user_id' => $user->id,
        //                         'meeting_time_id' => $meetingTime->id,
        //                         'meeting_id' => $meetingTime->meeting_id,
        //                         'status' => ReserveMeeting::$pending,
        //                         'day' => $day,
        //                         'meeting_type' => $selectedMeetingType,
        //                         'student_count' => $studentCount
        //                     ], [
        //                         'date' => strtotime($day),
        //                         'start_at' => $startAt,
        //                         'end_at' => $endAt,
        //                         'paid_amount' => (!empty($hourlyAmount) and $hourlyAmount > 0) ? $hourlyAmount * $hours : 0,
        //                         'discount' => $meetingTime->meeting->discount,
        //                         'description' => $description,
        //                         'created_at' => time(),
        //                     ]);

        //                     $cart = Cart::where('creator_id', $user->id)
        //                         ->where('reserve_meeting_id', $reserveMeeting->id)
        //                         ->first();

        //                     if (empty($cart)) {
        //                         Cart::create([
        //                             'creator_id' => $user->id,
        //                             'reserve_meeting_id' => $reserveMeeting->id,
        //                             'created_at' => time()
        //                         ]);
        //                     }

        //                     $toastData = [
        //                         'status' => 'success',
        //                         'title' => trans('public.request_success'),
        //                         'msg' => trans('update.meeting_added_to_cart'),
        //                         'redirect' => '/cart'
        //                     ];
        //                     return response()->json($toastData);
        //                 } else {
        //                     return $this->handleFreeMeetingReservation($user, $meeting, $meetingTime, $day, $selectedMeetingType, $studentCount);
        //                 }
        //             } else {
        //                 $toastData = [
        //                     'title' => trans('public.request_failed'),
        //                     'msg' => trans('meeting.meeting_disabled'),
        //                     'status' => 'error'
        //                 ];
        //                 return response()->json($toastData);
        //             }
        //         }
        //     }

        //     $toastData = [
        //         'title' => trans('public.request_failed'),
        //         'msg' => trans('meeting.select_time_to_reserve'),
        //         'status' => 'error'
        //     ];
        //     return response()->json($toastData);
        // }

        // $toastData = [
        //     'title' => trans('public.request_failed'),
        //     'msg' => trans('public.not_login_toast_msg_lang'),
        //     'status' => 'error'
        // ];
        // return response()->json($toastData);
    }

    private function handleUtcDate($day, $clock, $instructorTimezone)
    {
        $date = $day . ' ' . $clock;

        $utcDate = convertTimeToUTCzone($date, $instructorTimezone);

        return $utcDate->getTimestamp();
    }

    private function handleHourlyMeetingAmount(Meeting $meeting, MeetingTime $meetingTime, $studentCount, $selectedMeetingType)
    {
        if (empty($studentCount)) {
            $studentCount = 1;
        }

        $status = true;
        $hourlyAmount = $meeting->amount;

        if ($selectedMeetingType == 'in_person' and in_array($meetingTime->meeting_type, ['in_person', 'all'])) {
            if ($meeting->in_person) {
                $hourlyAmount = $meeting->in_person_amount;
            } else {
                $toastData = [
                    'status' => 'error',
                    'title' => trans('public.request_failed'),
                    'msg' => trans('update.in_person_meetings_unavailable'),
                ];
                $hourlyAmount = response()->json($toastData);
                $status = false;
            }
        }

        if ($meeting->group_meeting and $status) {
            $types = ['in_person', 'online'];

            foreach ($types as $type) {
                if ($selectedMeetingType == $type and in_array($meetingTime->meeting_type, ['all', $type])) {

                    $meetingMaxVar = $type . '_group_max_student';
                    $meetingMinVar = $type . '_group_min_student';
                    $meetingAmountVar = $type . '_group_amount';

                    if ($studentCount < $meeting->$meetingMinVar) {
                        $hourlyAmount = $hourlyAmount * $studentCount;
                    } else if ($studentCount > $meeting->$meetingMaxVar) {
                        $toastData = [
                            'status' => 'error',
                            'title' => trans('public.request_failed'),
                            'msg' => trans('update.group_meeting_max_student_count_hint', ['max' => $meeting->$meetingMaxVar]),
                        ];
                        $hourlyAmount = response()->json($toastData);
                        $status = false;
                    } else if ($studentCount >= $meeting->$meetingMinVar and $studentCount <= $meeting->$meetingMaxVar) {
                        $hourlyAmount = $meeting->$meetingAmountVar * $studentCount;
                    }
                }
            }
        }

        return [
            'status' => $status,
            'result' => $hourlyAmount
        ];
    }

    private function handleFreeMeetingReservation($user, $meeting, $meetingTime, $day, $selectedMeetingType, $studentCount)
    {
        $instructorTimezone = $meeting->getTimezone();
        $explodetime = explode('-', $meetingTime->time);

        $startAt = $this->handleUtcDate($day, $explodetime[0], $instructorTimezone);
        $endAt = $this->handleUtcDate($day, $explodetime[1], $instructorTimezone);

        $reserve = ReserveMeeting::updateOrCreate([
            'user_id' => $user->id,
            'meeting_time_id' => $meetingTime->id,
            'meeting_id' => $meetingTime->meeting_id,
            'status' => ReserveMeeting::$pending,
            'day' => $day,
            'meeting_type' => $selectedMeetingType,
            'student_count' => $studentCount
        ], [
            'date' => strtotime($day),
            'start_at' => $startAt,
            'end_at' => $endAt,
            'paid_amount' => 0,
            'discount' => $meetingTime->meeting->discount,
            'created_at' => time(),
        ]);

        if (!empty($reserve)) {
            $sale = Sale::create([
                'buyer_id' => $user->id,
                'seller_id' => $meeting->creator_id,
                'meeting_id' => $meeting->id,
                'type' => Sale::$meeting,
                'payment_method' => Sale::$credit,
                'amount' => 0,
                'total_amount' => 0,
                'created_at' => time(),
            ]);

            if (!empty($sale)) {
                $reserve->update([
                    'sale_id' => $sale->id,
                    'reserved_at' => time()
                ]);
            }
        }

        $toastData = [
            'title' => '',
            'msg' => trans('cart.success_pay_msg_for_free_meeting'),
            'status' => 'success'
        ];
        return response()->json($toastData);
    }
}
