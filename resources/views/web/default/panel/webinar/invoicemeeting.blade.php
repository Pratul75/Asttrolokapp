<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>{{ $pageTitle ?? '' }} </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- General CSS File -->
    <link rel="stylesheet" href="{{ config('app.js_css_url') }}/assets/admin/vendor/bootstrap/bootstrap.min.css"/>
    <link rel="stylesheet" href="{{ config('app.js_css_url') }}/assets/vendors/fontawesome/css/all.min.css"/>


    <link rel="stylesheet" href="{{ config('app.js_css_url') }}/assets/admin/css/style.css">
    <link rel="stylesheet" href="{{ config('app.js_css_url') }}/assets/admin/css/custom.css">
    <link rel="stylesheet" href="{{ config('app.js_css_url') }}/assets/admin/css/components.css">

    <style>
        {!! !empty(getCustomCssAndJs('css')) ? getCustomCssAndJs('css') : '' !!}
        body {
                font-family: 'main-font-family' !important;
                color: #000 !important;
        }
        .card .card-body .section-title {
    margin: 30px 0 10px 0;
    font-size: 16px;
    background-color: rgba(0, 0, 0, .03);
    border-bottom: 1px solid rgba(0, 0, 0, .125);
    padding: .5rem 1rem;
}
.invoice th{
    background-color: rgb(255 255 255 / 4%) !important;
    height: 0px !important;
}
    </style>
</head>
<body>

<div id="app">
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-md-10 offset-md-1 col-lg-10 offset-lg-1">

                    <div class="card card-primary">
                        <div class="row m-0">
                            <div class="col-12 col-md-12">
                                <div class="card-body">

                                    <div class="section-body">
                                        <div class="invoice">
                                            <div class="invoice-print">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="invoice-title">
                                                            <!--<h2>{{ $generalSettings['site_name'] }}</h2>-->
                                                            <div style="width:200px;">             <h2><a class="navbar-brand navbar-order d-flex align-items-center justify-content-center mr-0 " href="/">
                                    <img src="https://storage.googleapis.com/astrolok/store/1/Home/Asttrolok-Logo.png" class="img-cover" alt="site logo">
                            </a></h2></div>
                                               
                                                            <!--<div class="invoice-number">Invoice<p>{{ trans('public.item_id') }}: #{{ $webinar->id }}</p></div>-->
                                                        <div class="invoice-number">Invoice<p style="color: #6c757d;">Invoice Number: #{{ $sale->id }}</p></div>
                                                        </div>
                                                       <hr style="color: #000;border-top: 1px solid;">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <address>
                                                                      <!--<strong> Invoiced To:</strong>-->
                                                                     <!--<br>-->
                                                                    <strong>{{ trans('quiz.student') }}:</strong>
                                                                    <br>
                                                                    {{ !empty($sale->gift_recipient) ? $sale->gift_recipient : $sale->buyer->full_name }}
                                                                    <!--<br>-->
                                                                </address>

                                                                <address>
                                                                    <strong>{{ trans('home.organization') }}:</strong><br>
                                                                    <!--@if($webinar->tracher_id != $webinar->creator_id)-->
                                                                    <!--    {{ $webinar->creator->full_name }}-->
                                                                    <!--@else-->
                                                                    <!--    --->
                                                                    <!--@endif-->
                                                                    Asttrolok
                                                                    <br>
                                                                </address>
                                                            </div>
                                                            <div class="col-md-6 text-md-right">
                                                                <address>
                                                                    <strong>{{ trans('home.platform_address') }}:</strong><br>
                                                                    {!! nl2br(getContactPageSettings('address')) !!}
                                                                </address>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <address>
                                                                    <strong>{{ trans('home.teachers') }}:</strong><br>
                                                                    {{ $webinar->meeting->creator->full_name }}
                                                                </address>
                                                            </div>

                                                            <div class="col-md-6 text-md-right">
                                                                <address>
                                                                    <strong>{{ trans('panel.purchase_date') }}:</strong><br>
                                                                    {{ dateTimeFormat($sale->created_at,'j M Y | H:i') }}<br><br>
                                                                </address>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row ">
                                                    <div class="col-md-12">
                                                        <div class="section-title">{{ trans('home.order_summary') }}</div>
                                                        <div class="table-responsive">
                                                            <table class="table table-striped table-hover table-md">
                                                                <tr>
                                                                    <th data-width="40">#</th>
                                                                    <th>Astrologers</th>
                                                                    <th class="text-center">{{ trans('admin/main.type') }}</th>
                                                                     <th class="text-center">Time</th>
                                                                      <th class="text-center">Slot</th>
                                                                    <th class="text-center">{{ trans('public.price') }}</th>
                                                                    <th class="text-center">{{ trans('panel.discount') }}</th>
                                                                    <th class="text-right">{{ trans('cart.total') }}</th>
                                                                </tr>

                                                                <tr>
                                                                    <td>1</td>
                                                                    <td>{{ $webinar->meeting->creator->full_name }}</td>
                                                                    <td class="text-center">Meeting</td>
                                                                     <td class="text-center">{{ dateTimeFormat($webinar->start_at, 'j M Y') }} </td>
                                                                     <td>
                                                                          @if($webinar->slotid == 1 || $webinar->slotid == 2)
                                                                          @php
                                                                          
                                                                            $start_time = $webinar->start_at;
                                                                            $end_time = $webinar->end_at;
                                                                            $time_difference = $end_time - $start_time;
                                                                            $time_difference_minutes = $time_difference / 60;

                                                                            // Calculate half of the time difference
                                                                            $half_time_minutes = $time_difference_minutes / 2;
                                                                            
                                                                            
                                                                            @endphp
                                             <span class="">{{ $half_time_minutes }} min</span>
                                            
                                             @else
                                                   @php
                                                                          
                                                                            $start_time = $webinar->start_at;
                                                                            $end_time = $webinar->end_at;
                                                                            $time_difference = $end_time - $start_time;
                                                                            $time_difference_minutes = $time_difference / 60;
                                                                            
                                                                            
                                                                            @endphp
                                             <span class="">{{ $time_difference_minutes }} min.</span>
                                            
                                             
                                                @endif                          
                                                                         
                                            <!--                             <span class="">{{ dateTimeFormat($webinar->start_at, 'H:i') }}</span>-->
                                            <!--<span class="mx-1">-</span>-->
                                            <!--<span class="">{{ dateTimeFormat($webinar->end_at, 'H:i') }}</span>-->
                                            </td>
                                                                    <td class="text-center">
                                                                        @if(!empty($sale->amount))
                                                                            {{ handlePrice($sale->amount) }}
                                                                        @else
                                                                            {{ trans('public.free') }}
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @if(!empty($sale->discount))
                                                                            {{ handlePrice($sale->discount) }}
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-right">
                                                                        @if(!empty($sale->total_amount))
                                                                            {{ handlePrice($sale->total_amount) }}
                                                                        @else
                                                                            0
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="row mt-4">

                                                            <div class="col-lg-12 text-right">
                                                                <div class="table-responsive">
                                                            <table class="table mb-0 invoice table-striped table-hover table-md">
                                                                <thead>
                                                                    <tr>
                                                                    <th ></th>
                                                                    <th></th>
                                                                    <th></th>
                                                                    <th ></th>
                                                                    <th ></th>
                                                                    <th ></th>
                                                                  </tr>
                                                               </thead>
                                                               <tbody>
                            
                                                  <tr>
                                                      <td colspan="6" ></td>
                                                     <td class="text-right"><strong>{{ trans('cart.sub_total') }}</strong></td>
                                                     <td class="text-right">{{ handlePrice($sale->amount) }}</td>
                                                  </tr>
                                                  <tr>
                                                       <td colspan="6" ></td>
                                                     <td  class="text-right"><strong>{{ trans('cart.tax') }} ({{ getFinancialSettings('tax') }}%)</strong></td>
                                                     <td class="text-right">
                                                          @if(!empty($sale->tax))
                                                                            {{ handlePrice($sale->tax) }}
                                                                        @else
                                                                            -
                                                                        @endif
                                                         </td>
                                                  </tr>
                                                  <tr>
                                                       <td colspan="6" ></td>
                                                     <td  class="text-right"><strong>{{ trans('public.discount') }}</strong></td>
                                                     <td class="text-right">@if(!empty($sale->discount))
                                                                            {{ handlePrice($sale->discount) }}
                                                                        @else
                                                                            -
                                                                        @endif
                                                                </td>
                                                  </tr>
                                                   <tr>
                                                       <td colspan="6" ></td>
                                                     <td  class="text-right"><strong>{{ trans('cart.total') }}</strong></td>
                                                     <td class="text-right">
                                                           @if(!empty($sale->total_amount))
                                                                            {{ handlePrice($sale->total_amount) }}
                                                                        @else
                                                                            -
                                                                        @endif
                                                                </td>
                                                  </tr>
                                               </tbody>
                                            </table>
                                                        </div>
                                                                
                                                                
                                                                
                                                                
                                                                <!--<div class="invoice-detail-item">-->
                                                                <!--    <div class="invoice-detail-name">{{ trans('cart.sub_total') }}</div>-->
                                                                <!--    <div class="invoice-detail-value">{{ handlePrice($sale->amount) }}</div>-->
                                                                <!--</div>-->
                                                                <!--<div class="invoice-detail-item">-->
                                                                <!--    <div class="invoice-detail-name">{{ trans('cart.tax') }} ({{ getFinancialSettings('tax') }}%)</div>-->
                                                                <!--    <div class="invoice-detail-value">-->
                                                                <!--        @if(!empty($sale->tax))-->
                                                                <!--            {{ handlePrice($sale->tax) }}-->
                                                                <!--        @else-->
                                                                <!--            --->
                                                                <!--        @endif-->
                                                                <!--    </div>-->
                                                                <!--</div>-->
                                                                <!--<div class="invoice-detail-item">-->
                                                                <!--    <div class="invoice-detail-name">{{ trans('public.discount') }}</div>-->
                                                                <!--    <div class="invoice-detail-value">-->
                                                                <!--        @if(!empty($sale->discount))-->
                                                                <!--            {{ handlePrice($sale->discount) }}-->
                                                                <!--        @else-->
                                                                <!--            --->
                                                                <!--        @endif-->
                                                                <!--    </div>-->
                                                                <!--</div>-->
                                                                <!--<hr class="mt-2 mb-2">-->
                                                                <!--<div class="invoice-detail-item">-->
                                                                <!--    <div class="invoice-detail-name">{{ trans('cart.total') }}</div>-->
                                                                <!--    <div class="invoice-detail-value invoice-detail-value-lg">-->
                                                                <!--        @if(!empty($sale->total_amount))-->
                                                                <!--            {{ handlePrice($sale->total_amount) }}-->
                                                                <!--        @else-->
                                                                <!--            --->
                                                                <!--        @endif-->
                                                                <!--    </div>-->
                                                                <!--</div>-->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="text-md-right">

                                                <button type="button" onclick="window.print()" class="btn btn-warning btn-icon icon-left"><i class="fas fa-print"></i> Print</button>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>
</body>
