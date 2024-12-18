<!--<div class="installment-card p-15 mt-20">-->
<div class=" p-15">
    <div class="row">
      {{--   <div class="col-8">
         <h4 class="font-16 font-weight-bold text-dark-blue">{{ $installment->main_title }}</h4>

            <div class="">
                <p class="text-gray font-14 text-ellipsis">{{ nl2br($installment->description) }}</p>
            </div>
           

            @if(!empty($installment->capacity))
                @php
                    $reachedCapacityPercent = $installment->reachedCapacityPercent();
                @endphp

                @if($reachedCapacityPercent > 0)
                    <div class="mt-20 d-flex align-items-center">
                        <div class="progress card-progress flex-grow-1">
                            <span class="progress-bar rounded-sm {{ $reachedCapacityPercent > 50 ? 'bg-danger' : 'bg-primary' }}" style="width: {{ $reachedCapacityPercent }}%"></span>
                        </div>
                        <div class="ml-10 font-12 text-danger">{{ trans('update.percent_capacity_reached',['percent' => $reachedCapacityPercent]) }}</div>
                    </div>
                @endif
            @endif

            @if(!empty($installment->banner))
                <div class="mt-20">
                    <img src="{{ $installment->banner }}" alt="{{ $installment->main_title }}" class="img-fluid">
                </div>
            @endif

            @if(!empty($installment->options))
                <div class="mt-20">
                    @php
                        $installmentOptions = explode(\App\Models\Installment::$optionsExplodeKey, $installment->options);
                    @endphp

                    @foreach($installmentOptions as $installmentOption)
                        <div class="d-flex align-items-center mb-1">
                            <i data-feather="check" width="25" height="25" class="text-primary"></i>
                            <span class="ml-10 font-14 text-gray">{{ $installmentOption }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>   --}}

        <div class="col-12">
            <div class="installment-card__payments d-flex flex-column w-100 h-100" style="background-color: #ffffff;">
               
                @php
                    $totalPayments = $installment->totalPayments($itemPrice ?? 1);
                    $installmentTotalInterest = $installment->totalInterest($itemPrice, $totalPayments);
                @endphp

               {{-- <div class="d-flex align-items-center justify-content-center flex-column">
                    <span class="font-36 font-weight-bold text-primary">{{ handlePrice($totalPayments) }}</span>
                    <span class="mt-10 font-12 text-gray">{{ trans('update.total_payment') }} @if($installmentTotalInterest > 0)
                            ({{ trans('update.percent_interest',['percent' => $installmentTotalInterest]) }})
                        @endif</span>
                </div> --}}
<div class="price-box p-10 shadow-xs mb-20" style="border: 1px solid #e1e1e1;border-radius: 10px;"> 
<span class="font-30 font-weight-bold">Total Amount </span> 
<span class="f-right font-30 text-primary" style="    float: right;">{{ handlePrice($totalPayments) }}/-</span> 
</div>
                <!--<div class="mt-25 mb-15">-->
                <!--    <div class="installment-step d-flex align-items-center font-14 text-primary" style="font-weight: 600;">{{ !empty($installment->upfront) ? (trans('update.amount_upfront',['amount' => handlePrice($installment->getUpfront($itemPrice))]) . ($installment->upfront_type == "percent" ? " ({$installment->upfront}%)" : '')) : trans('update.no_upfront') }}</div>-->

                <!--    @foreach($installment->steps as $installmentStep)-->
                <!--        <div class="installment-step d-flex align-items-center font-14 text-gray" style="font-weight: 600;">{{ $installmentStep->getDeadlineTitle($itemPrice) }}</div>-->
                <!--    @endforeach-->
                <!--</div>-->
                @if($item->slug!='astrology-basic-level')
                <div class=" p-15 mt-20 mx-20 shadow-sm " style="border: 1px solid #e1e1e1;border-radius: 10px;">
                <p class="text-gray font-12 text-ellipsis">{!! nl2br($installment->description) !!}</p>
            </div>
            
                
                <form action="/cart/coupon/validate1" method="Post">
                    <div class="col-12 col-lg-12">
                    <section>
                    {{ csrf_field() }}
                    <div class="row" style="display: flex;justify-content: space-evenly;"> 
                    <div class="col-11 col-lg-9"> 
                    <div class="form-group" style="border-radius: 20px;">
                        <input type="text" name="coupon" id="coupon_input" class="form-control mt-25 " style="border-radius: 20px !important;"
                         placeholder="{{ trans('cart.enter_your_code_here') }}" style="border-radius: 20px;">
                        <input type="hidden" name="web_id1" id="web_id1" value="{{$itemId}}" class="form-control mt-25" >
                        <input type="hidden" name="webinsta_id1" id="webinsta_id1" value="{{$installmentRow->id}}" class="form-control mt-25" >
                        <span class="invalid-feedback">{{ trans('cart.coupon_invalid') }}</span>
                        <span class="valid-feedback">{{ trans('cart.coupon_valid') }}</span>
                    </div>
                    </div><div class="col-5 col-lg-3 botton-1" style="position: absolute;margin-top: 3px;margin-right: -231px;">
                    <button type="submit" id="checkCoupon1" style="height: 35px !important; border-radius: 20px !important;" class="btn btn-sm btn-primary mt-25">{{ trans('cart.validate') }}</button></div></div>
                    </section></div>
                </form>
                @endif
                <div id="Payment-Option" class=" bg-gray200 mt-30 rounded-lg border p-15">
            
         <!--<h2 class="section-title">Payment Option</h2>-->
          <form action="/payments/payment-request" method="post" id="razor-pay-request" class=" mt-25 " >
            {{ csrf_field() }}
            <input type="hidden" name="order_id" value="{{ $order->id ?? 0 }}">
            <input type="hidden" name="installment_id" value="{{ $installment->id ?? null }}">
             <input type="hidden" name="discountId" value="{{!empty($discountId) ? $discountId : 0}}"  class="form-control mt-25 mb-25 " required>
             <input type="hidden" name="price" value="<?php echo (number_format(((($installments->first()->upfront)*$itemPrice) /100), 2, '.', '')); ?>">
             <input type="hidden" name="item" value="{{!empty($item) ? $item->id : null}}"  placeholder="Contact Number" class="form-control mt-25 mb-25 " >
            <input type="hidden" name="item_type" value="{{!empty($itemType) ? $itemType : null}}"  placeholder="Contact Number" class="form-control mt-25 mb-25 ">
            <div class="form-group">
                            
                            <input name="name" type="text" value="{{ auth()->check() ? auth()->user()->full_name :'' }}" id='customer_name'  placeholder="Name" class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                           
                            <input name="email" id='customer_email'  placeholder="Email"  type="text" value="{{ auth()->check() ? auth()->user()->email :'' }}"  class="form-control @error('email') is-invalid @enderror">
                            @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <input name="number" id='customer_number'  placeholder="Contact Number" type="text" value="{{ auth()->check() ? auth()->user()->mobile :'' }}" class="form-control @error('number') is-invalid @enderror">
                            @error('number')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
            <!--<input type="text" name="name" id='customer_name'  placeholder="Name" class="form-control mt-25 " >-->
            <!--<input type="email" name="email" id='customer_email'  placeholder="Email" class="form-control mt-25 " >-->
            <!--<input type="number" name="number" id='customer_number'  placeholder="Contact Number" class="form-control mt-25 mb-25" > -->
            
                        <div class="form-group">
                            @if($item->slug=='astrology-basic-level')
                            @php
                            
                            @endphp
                            <input name="amount" id='amount'  placeholder="Amount" type="text" value="{{$item->slug=='astrology-basic-level'?round($totalPayments):null}}" class="form-control @error('number') is-invalid @enderror"  readonly >
                            @else
                            <input name="amount" id='amount'  placeholder="Amount" type="text" value="" class="form-control @error('number') is-invalid @enderror"   >
                            @endif
                            @error('amount')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
           

            @if(!empty($invalidChannels))
                <div class="d-flex align-items-center mt-30 rounded-lg border p-15">
                    <div class="size-40 d-flex-center rounded-circle bg-gray200">
                        <i data-feather="info" class="text-gray" width="20" height="20"></i>
                    </div>
                    <div class="ml-5">
                        <h4 class="font-14 font-weight-bold text-gray">{{ trans('update.disabled_payment_gateways') }}</h4>
                        <p class="font-12 text-gray">{{ trans('update.disabled_payment_gateways_hint') }}</p>
                    </div>
                </div>

                <div class="row mt-20">
                    @foreach($invalidChannels as $invalidChannel)
                        <div class="col-6 col-lg-4 mb-40 charge-account-radio">
                            <div class="disabled-payment-channel bg-white border rounded-sm p-20 p-lg-45 d-flex flex-column align-items-center justify-content-center">
                                <img src="{{ $invalidChannel->image }}" width="120" height="60" alt="">

                                <p class="mt-30 mt-lg-50 font-weight-500 text-dark-blue">
                                    {{ trans('financial.pay_via') }}
                                    <span class="font-weight-bold font-14">{{ $invalidChannel->title }}</span>
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
<center><div class="loader mt-50" id="loader" style="dispay:none ">
    <img width= '80px' height= '80px' src="{{ asset('public/assets/default/img/loading.gif')}}">
    <br>
    <h3>Please do not refresh or close the page while your payment is being processed...</h3>
    </div></center>
<button type="button" id="razor-pay-now"  class="{{$item->slug=='astrology-basic-level'?'':'d-none'}} btn btn-sm btn-primary loading">{{ trans('public.start_payment') }}</button>

        </form>
        </div>
<div style="
    display: flex;
    justify-content: center;
">
                <!--<a style="width: 138px;" href="/installments/{{ $installment->id }}?item={{ $itemId }}&item_type={{ $itemType }}&{{ http_build_query(request()->all()) }}#Payment-Option" class=" btn btn-primary btn-block mt-auto">Pay Now</a>-->
            </div></div>
        </div>
    </div>
</div>



@push('scripts_bottom')
<script type="text/javascript">

        var couponInvalidLng = '{{ trans('cart.coupon_invalid') }}';
        var selectProvinceLang = '{{ trans('update.select_province') }}';
        var selectCityLang = '{{ trans('update.select_city') }}';
        var selectDistrictLang = '{{ trans('update.select_district') }}';
    </script>

    <script src="/assets/default/js/parts/get-regions.min.js"></script>
    <script src="/assets/default/js/parts/cart.min.js"></script>
    <script src="/assets/default/js/parts/payment.min.js"></script>
@endpush