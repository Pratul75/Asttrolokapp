@extends(getTemplate().'.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="{{ config('app.js_css_url') }}/assets/default/vendors/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="{{ config('app.js_css_url') }}/assets/default/vendors/owl-carousel2/owl.carousel.min.css">
    <link rel="stylesheet" href="{{ config('app.js_css_url') }}/assets/default/css/mobile-courses.css">
    <link rel="stylesheet" href="{{ config('app.js_css_url') }}/assets/default/css/mobile-home.css">
    <link rel="canonical" href="https://www.asttrolok.com" />
    <style>
    .rewardss{
     position: absolute;
    width: 100%;
    left: -24px !important;
    top: 8px !important;   
}

.news{
    border-radius: 0px 0px 10px 10px;
    background-color: #32ba7c;
    text-align: center;
}
.news-text{
    line-height: 1 !important;
}

</style>
@endpush


@section('content')

    @if(!empty($heroSectionData))

        @if(!empty($heroSectionData['has_lottie']) and $heroSectionData['has_lottie'] == "1")
            @push('scripts_bottom')
                <script src="{{ config('app.js_css_url') }}/assets/default/vendors/lottie/lottie-player.js"></script>
            @endpush
        @endif
       <div>
    <!--<section class="container">-->
    <section class="">
        <div class="row">
    <div class="col-12 col-lg-12  mt-lg-0 ">
      
<div class="feature-slider-container position-relative d-flex justify-content-center ">
<div class="swiper-container slider-home-banner-section2">
    <div class="swiper-wrapper ">
        @foreach ($HomeSlider as $key=> $value)
            <div class="mobile-home-slider swiper-slide slider-height" style="background-image: url('{{ config('app.img_dynamic_url') }}{{ $value->hero_background }}') ; background-size: cover!important; background-repeat: no-repeat!important;">

    <section class="slider-container  {{ ($heroSection == "2") ? 'slider-hero-section2' : '' }}" @if(empty($heroSectionData['is_video_background']))  @endif>

        @if($heroSection == "1")
            @if(!empty($heroSectionData['is_video_background']))
                <video playsinline autoplay muted loop id="homeHeroVideoBackground" class="img-cover">
                    <source src="{{ $value->hero_background }}" type="video/mp4">
                </video>
            @endif

            <div class="mask"></div>
        @endif
                 
    
                            <div class="container user-select-none">

                                @if($heroSection == "2")
                                    <div class="row slider-content align-items-center hero-section2 flex-column-reverse flex-md-row">
                                        
                                        <div class="col-12 col-md-7 col-lg-8 deskdesc">
                                            <div style="margin-left:100px;">
                                                <!--<h1 class="text-secondary font-weight-bold" style="font-size:55px;">{{ $heroSectionData['title'] }}</h1>-->
                                                @if($key ==0)
                                                <h1 class="text-secondary font-weight-bold" style="font-size:53px;">{!! $value->title !!}</h1>
                                                @else
                                                <h2 class="text-secondary font-weight-bold" style="font-size:53px;">{!! $value->title !!}</h2>
                                                @endif
                                                <div >
                                                   {{-- @if(empty($authUser))
                                                     
                                                    <p class="slide-hint text-gray mt-20" style="font-size: 16.497px;margin-left:10px;line-height: 1.5;">{!! nl2br($heroSectionData['description']) !!} </p><br>
                                                    <a href="/register" class="btn btn-primary rounded-pill">Join Now</a>
                                                    @else
                                                    
                                                    <p class="slide-hint text-gray mt-20" style="font-size: 16.497px;margin-left:10px; line-height: 1.5;">{!! nl2br($heroSectionData['description']) !!}</p><br>
                                                    <a href="/classes?sort=newest" class="btn btn-primary rounded-pill">Explore</a>
                                                    @endif --}}
                                                   <p class="slide-hint text-gray mt-20" style="font-size: 16.497px;margin-left:10px; line-height: 1.5;">{!! $value->description !!}</p>
                                                <br>
                                                <!--<p class="slide-hint text-gray mt-20" style="font-size: 16.497px;margin-left:10px; line-height: 1.5;">{!! nl2br($heroSectionData['description']) !!}</p><br>-->
                                                    @if($key ==1)
                                                    <a href="{{ $value->button_url }}" class="btn btn-primary rounded-pill" style="background-color:#a96f21;border:none!important;box-shadow:inset 0 1px 0 rgb(169 111 33 / 24%), 0 1px 1px rgb(169 111 33 / 0%)!important;" >{{  $value->button_text }}</a>
                                                    @else
                                                    <a href="{{ $value->button_url }}" class="btn btn-primary rounded-pill"  >{{  $value->button_text }}</a>
                                                    @endif
                                                    <!--<a href="#" class="btn btn-primary rounded-pill" style="background-color: ##a96f21;color: #343434;"><b>Watch Video</b></a>-->
                                                </div>
                                                
                    
                                                {{-- <form action="/search" method="get" class="d-inline-flex mt-30 mt-lg-30 w-100">
                                                    <div class="form-group d-flex align-items-center m-0 slider-search p-10 bg-white w-100">
                                                        <input type="text" name="search" class="form-control border-0 mr-lg-50" placeholder="{{ trans('home.slider_search_placeholder') }}"/>
                                                        <button type="submit" class="btn btn-primary rounded-pill">{{ trans('home.find') }}</button>
                                                    </div>
                                                </form> --}}
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-5 col-lg-4 mobilehome  pr-0" >
                                            <!--<h1 class="text-secondary font-weight-bold  mobiledesc" style="font-size:18px;">{{ $heroSectionData['title'] }}</h1>-->
                                            <div class="col-6 col-md-5 col-lg-4 px-0 mb-10">
                                            <h2 class="main-heading-home  font-weight-bold  mobiledesc" >{!! $value->title !!}</h2>
                                            <p class="main-text-home slide-hint text-gray mt-10 mb-10 mobiledesc" >{!! $value->description !!}{{--<br>
                                                     {{ $banner_disription1[$key] }} --}}</p>
                                            
                                                    <a href="{{ $value->button_url }}" class="btn btn-primary rounded-pill " style="background-color:{{  $value->button_color }};border:none!important;box-shadow:inset 0 1px 0 rgb(169 111 33 / 24%), 0 1px 1px rgb(169 111 33 / 0%)!important; font-size:13px !important; padding: 0px 22px;" >{{  $value->button_text }}</a>
                                                   
                                                    </div>
                                                    <div class="col-6 col-md-5 col-lg-4 px-0" >
                                            @if(!empty($heroSectionData['has_lottie']) and $heroSectionData['has_lottie'] == "1")
                                                <lottie-player src="{{ $value->hero_vector }}" background="transparent" speed="1" class="w-100" loop autoplay></lottie-player>
                                            @else
                                                <img src="{{ config('app.img_dynamic_url') }}{{ $value->hero_vector }}" alt="{{ $value->title }}" class="main-home-img img-cover" style="border-bottom-right-radius: 20px;">
                                            @endif
                                        </div>
                                        </div>
                                       
                                    </div>
                                @else
                                    <div class="text-center slider-content">
                                        <h1>{{ $value->title }}</h1>
                                        <div class="row h-100 align-items-center justify-content-center text-center">
                                            <div class="col-12 col-md-9 col-lg-7">
                                                <p class="mt-30 slide-hint">{!! $value->description !!}</p>
                
                                                {{-- <form action="/search" method="get" class="d-inline-flex mt-30 mt-lg-50 w-100">
                                                    <div class="form-group d-flex align-items-center m-0 slider-search p-10 bg-white w-100">
                                                        <input type="text" name="search" class="form-control border-0 mr-lg-50" placeholder="{{ trans('home.slider_search_placeholder') }}"/>
                                                        <button type="submit" class="btn btn-primary rounded-pill">{{ trans('home.find') }}</button>
                                                    </div>
                                                </form> --}}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                        
      
    </section>
</div>
@endforeach
</div>
</div>

<div class="swiper-pagination home-banner-swiper-pagination"></div>
</div>
   {{-- @include('web.default.pages.includes.home_statistics') --}}
    @include('web.default.pages.includes.category_statics')
    
    @include('web.default.pages.includes.mobile_cat')

      
      
            <section class="home-sections container hide-mobile">
                <div class="d-flex justify-content-between">
                    <div class="">
                        <h2 class="section-title">Astrologers</h2>
                    </div>

                <!--<a href="/consult-with-astrologers" class="btn btn-border-white mobile-btn">{{ trans('home.view_all') }}<img width="20px" class="ml-5" src="/assets/default/mobile/right-arrow.png" alt="Right Arrow Icon - Asttrolok"></a>-->
                </div>

                {{-- <div class="scroll-bar"> --}}
                <div class="mx-0">
                    @php
                    $id_i=0;
                    @endphp
                @foreach($consultant as $instructor)
                @php
                if($id_i==4)
                {
                break;
                }
                $canReserve = false;
                if(!empty($instructor->meeting) and !$instructor->meeting->disabled and !empty($instructor->meeting->meetingTimes) ) {
                    $canReserve = true;
                }
                if($canReserve){
                    $id_i=$id_i+1;
                    }
                @endphp
                @if($canReserve)
                        <div class=" col-12 col-md-6 col-lg-4 " style="padding-right: 0px !important; padding-left: 0px !important; ">
                            @include('web.default.pages.instructor_card1',['instructor' => $instructor ])
                        </div>
                @endif
            @endforeach
                </div>
               <center> <a href="/consult-with-astrologers" class="mt-20 btn btn-border-white mobile-btn" style="margin-left: 0px !important; font-size:16px !important; border: 2px solid #7e7e7e !important;">{{ trans('home.view_all') }}
                <!--<img width="20px" class="ml-5" src="/assets/default/mobile/right-arrow.png" alt="Right Arrow Icon - Asttrolok">-->
                </a></center>
            </section>
            
            <div class="rounded-lg sidebar-ads1 m-10 mt-30" style="width:95%;">
            <a href="{{$sidebanner['home1']['link']}}">
            <img src="{{ config('app.img_dynamic_url') }}{{$sidebanner['home1']['image']}}" class="w-100  shadow-sm rounded-lg" alt="Reserve a meeting - Course page">
            
            </a>
         </div>
            <!--<div class="m-10 mt-30" style="width:95%;">-->
            <!--    <img  style="width:100%; border-radius:7px;" src="{{ config('app.img_dynamic_url') }}{{$sidebanner['home1']['image']}}">-->
            <!--</div>-->

            <section class="home-sections home-sections-swiper container hide-mobile" style=" margin-top: 36px;">
                <div class="d-flex justify-content-between ">
                    <div >
                        <h2 class="section-title">Courses</h2>
                    </div>
                    <div class="mob-tab1">
                        <ul class="btn nav nav-tabs rounded-sm  d-flex align-items-center justify-content-between" id="tabs-tab" role="tablist">
                            <li class="nav-item" style="margin: auto;">
                                <a class="eng-1 position-relative font-14 text-white1 {{ (request()->get('tab','') == 'content') ? 'active' : '' }}" id="content-tab" data-toggle="tab"
                                   href="#content" role="tab" aria-controls="content"
                                   aria-selected="false">ENG</a>
                            </li>
                            <li class="nav-item" style="margin: auto;">
                                <a  class="hindi-1 position-relative font-14 text-white1 {{ (empty(request()->get('tab','')) or request()->get('tab','') == 'reviews') ? 'active' : '' }}" id="reviews-tab" data-toggle="tab"
                                   href="#reviews" role="tab" aria-controls="reviews"
                                   aria-selected="false">हिंदी</a>
                            </li>
                           
                           
                        </ul>
            </div>
                    <!--<a href="/classes" class="btn btn-border-white mobile-btn">{{ trans('home.view_all') }}<img width="20px" class="ml-5" src="/assets/default/mobile/right-arrow.png" alt="Right Arrow Icon - Asttrolok"></a>-->
                </div>

                <div class="mt-10 position-relative">
                    <div class=" ">
                        <div class="row  pt-20">
                            <div class="tab-content " id="nav-tabContent">
                            <div class="tab-pane fade {{ (request()->get('tab','') == 'content') ? 'show active' : '' }}" id="content" role="tabpanel" aria-labelledby="content-tab" >
                                <div class="row mx-0">
                                    @foreach($englishclasses as $englishclasse)
                                <div class="col-md-6 col-lg-4 mt-20 loadid mobilegrid1">
                                    @include('web.default.includes.webinar.grid-card',['webinar' => $englishclasse])
                                </div>
                            @endforeach
                                    
                                    
                            </div></div>
                            <div class=" tab-pane fade  {{ (empty(request()->get('tab','')) or request()->get('tab','') == 'reviews') ? 'show active' : '' }}" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                                <div class="row mx-0">
                                    @foreach($hindiWebinars as $hindiWebinar)
                                <div class="col-md-6 col-lg-4 mt-20 loadid mobilegrid1">
                                    @include('web.default.includes.webinar.grid-card',['webinar' => $hindiWebinar])
                                </div>
                            @endforeach
                                    
                                    
                            </div></div></div>
                            

                        </div>
                    </div>

                 
                </div>
                <center> <a href="/classes" class="mt-20 btn btn-border-white mobile-btn" style="margin-left: 0px !important; font-size:16px !important; border: 2px solid #7e7e7e !important;">{{ trans('home.view_all') }}
                <!--<img width="20px" class="ml-5" src="/assets/default/mobile/right-arrow.png" alt="Right Arrow Icon - Asttrolok">-->
                </a></center>
            </section>
</div>
</div> </section></div>
    
    <div class="mobileteacher">
@foreach($homeSections as $homeSection)
 @if($homeSection->name == \App\Models\HomeSection::$featured_classes and !empty($featureWebinars) and !$featureWebinars->isEmpty())
<section class="home-sections Featured-section home-sections-swiper container">
    <div class="px-20 px-md-0">
        <h2 class="section-title">{{ trans('home.featured_classes') }}</h2>
        <p class="section-hint">{{ trans('home.featured_classes_hint') }}</p>
    </div>

    <div class="feature-slider-container position-relative d-flex justify-content-center mt-10">
        <div class="swiper-container features-swiper-container">
            <div class="swiper-wrapper py-10">
                @foreach($featureWebinars as $feature)
                    <div class="swiper-slide">

                        <a href="{{ $feature->webinar->getUrl() }}"><span>
                            <div class="feature-slider d-flex h-100" onclick="featurjquery({{ $feature->webinar->getUrl() }});" style="background-image: url('{{ config('app.img_dynamic_url') }}{{ $feature->webinar->getImageCover() }}')">
                                <div class="mask1"></div>
                                <div class="p-5 p-md-25 feature-slider-card">
                                     <div class="feature-price-box">
                                                @if(!empty($feature->webinar->price ) and $feature->webinar->price > 0)
                                                    @if($feature->webinar->bestTicket() < $feature->webinar->price)
                                                        <span class="real">{{ handlePrice($feature->webinar->bestTicket(), true, true, false, null, true) }}</span>
                                                    @else
                                                        {{ handlePrice($feature->webinar->price, true, true, false, null, true) }}
                                                    @endif
                                                @else
                                                    {{ trans('public.free') }}
                                                @endif


                                            </div>
                                    <div class="d-flex flex-column feature-slider-body position-relative h-100">
                                        @if($feature->webinar->bestTicket() < $feature->webinar->price)
                                            <span class="badge badge-danger mb-2 ">{{ trans('public.offer',['off' => $feature->webinar->bestTicket(true)['percent']]) }}</span>
                                        @endif
                                        <a href="{{ $feature->webinar->getUrl() }}" class=" mt-auto">
                                            <h3 class="card-title  ">{{ $feature->webinar->title }}</h3>
                                        </a>

                                        <div class="user-inline-avatar mt-5 d-flex align-items-center">
                                            <div class="avatar bg-gray200">
                                                <img src="{{ config('app.img_dynamic_url') }}{{ $feature->webinar->teacher->getAvatar() }}" class="img-cover" alt="{{ $feature->webinar->teacher->full_naem }}">
                                            </div>
                                            <a href="{{ $feature->webinar->teacher->getProfileUrl() }}" target="_blank" class="user-name font-14 ml-5">{{ $feature->webinar->teacher->full_name }}</a>
                                        </div>

                                        <p class="mt-25 feature-desc text-gray">{{ $feature->description }}</p>

                                        @include('web.default.includes.webinar.rate',['rate' => $feature->webinar->getRate()])

                                        <div class="feature-footer d-flex align-items-center justify-content-between">
                                            <div class="d-flex justify-content-between">
                                                <div class="d-flex align-items-center">
                                                    <i data-feather="clock" width="20" height="20" class="webinar-icon"></i>
                                                    <span class="duration ml-5 text-dark-blue font-14">{{ convertMinutesToHourAndMinute($feature->webinar->duration) }} {{ trans('home.hours') }}</span>
                                                </div>

                                                <div class="vertical-line mx-10"></div>

                                                <div class="d-flex align-items-center">
                                                    <i data-feather="calendar" width="20" height="20" class="webinar-icon"></i>
                                                    <span class="date-published ml-5 text-dark-blue font-14">{{ dateTimeFormat(!empty($feature->webinar->start_date) ? $feature->webinar->start_date : $feature->webinar->created_at,'j M Y') }}</span>
                                                </div>
                                            </div>

                                          
                                        </div>
                                         
                                    </div>
                                </div>
                            </div>
                       </span> </a>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="swiper-pagination features-swiper-pagination"></div>
    </div>
</section>
@endif
@endforeach
    </div>
   
    @endif


    {{-- Statistics --}}
    {{-- @foreach($homeSections as $homeSection)


    @endforeach --}}
    <?php
$count_homesection=0;
?>
    @foreach($homeSections as $homeSection)
    <?php $count_homesection++; ?>
@if($count_homesection==1)
<section class="container" >
    <div class="row">
<div class="col-12 col-lg-12 mt-25 mt-lg-0 mobilefirst">
    
@endif
@if($count_homesection==2)
<section class="container homehide">
    <div class="row">
<div class="col-12 col-lg-12 mt-25 mt-lg-0 ">
@endif

@if($count_homesection==3)
<section class="container homehide">
    <div class="row">
<div class="col-12 col-lg-12 mt-25 mt-lg-0 ">
@endif
@if($homeSection->name == \App\Models\HomeSection::$featured_classes and !empty($featureWebinars) and !$featureWebinars->isEmpty())
<section class="home-sections Featured-section home-sections-swiper container homehide">
    <div class="px-20 px-md-0">
        <h2 class="section-title">{{ trans('home.featured_classes') }}</h2>
        <p class="section-hint">{{ trans('home.featured_classes_hint') }}</p>
    </div>

    <div class="feature-slider-container position-relative d-flex justify-content-center mt-10">
        <div class="swiper-container features-swiper-container pb-25">
            <div class="swiper-wrapper py-10">
                @foreach($featureWebinars as $feature)
                    <div class="swiper-slide ">

                        <a href="{{ $feature->webinar->getUrl() }}"><span>
                            <div class="feature-slider d-flex h-100"  onclick="featurjquery({{ $feature->webinar->getUrl() }});" style="background-image: url('{{ config('app.img_dynamic_url') }}{{ $feature->webinar->getImageCover() }}')">
                                <div class="mask"></div>
                                <div class="p-5 p-md-25 feature-slider-card">
                                    <div class="d-flex flex-column feature-slider-body position-relative h-100">
                                        @if($feature->webinar->bestTicket() < $feature->webinar->price)
                                            <span class="badge badge-danger mb-2 ">{{ trans('public.offer',['off' => $feature->webinar->bestTicket(true)['percent']]) }}</span>
                                        @endif
                                        <a href="{{ $feature->webinar->getUrl() }}">
                                            <h3 class="card-title mt-1">{{ $feature->webinar->title }}</h3>
                                        </a>

                                        <div class="user-inline-avatar mt-15 d-flex align-items-center">
                                            <div class="avatar bg-gray200">
                                                <img src="{{ config('app.img_dynamic_url') }}{{ $feature->webinar->teacher->getAvatar() }}" class="img-cover" alt="{{ $feature->webinar->teacher->full_naem }}">
                                            </div>
                                            <a href="{{ $feature->webinar->teacher->getProfileUrl() }}" target="_blank" class="user-name font-14 ml-5">{{ $feature->webinar->teacher->full_name }}</a>
                                        </div>

                                        <p class="mt-25 feature-desc text-gray">{{ $feature->description }}</p>

                                        @include('web.default.includes.webinar.rate',['rate' => $feature->webinar->getRate()])

                                        <div class="feature-footer mt-auto d-flex align-items-center justify-content-between">
                                            <div class="d-flex justify-content-between">
                                                <div class="d-flex align-items-center">
                                                    <i data-feather="clock" width="20" height="20" class="webinar-icon"></i>
                                                    <span class="duration ml-5 text-dark-blue font-14">{{ convertMinutesToHourAndMinute($feature->webinar->duration) }} {{ trans('home.hours') }}</span>
                                                </div>

                                                <div class="vertical-line mx-10"></div>

                                                <div class="d-flex align-items-center">
                                                    <i data-feather="calendar" width="20" height="20" class="webinar-icon"></i>
                                                    <span class="date-published ml-5 text-dark-blue font-14">{{ dateTimeFormat(!empty($feature->webinar->start_date) ? $feature->webinar->start_date : $feature->webinar->created_at,'j M Y') }}</span>
                                                </div>
                                            </div>

                                            <div class="feature-price-box">
                                                @if(!empty($feature->webinar->price ) and $feature->webinar->price > 0)
                                                    @if($feature->webinar->bestTicket() < $feature->webinar->price)
                                                        <span class="real">{{ handlePrice($feature->webinar->bestTicket(), true, true, false, null, true) }}</span>
                                                    @else
                                                        {{ handlePrice($feature->webinar->price, true, true, false, null, true) }}
                                                    @endif
                                                @else
                                                    {{ trans('public.free') }}
                                                @endif


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </span></a>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="swiper-pagination features-swiper-pagination"></div>
    </div>
</section>
@endif

 @if($homeSection->name == \App\Models\HomeSection::$english_classes and !empty($englishclasses) and !$englishclasses->isEmpty())
            <section class="homehide home-sections home-sections-swiper container" style=" margin-top: 36px;">
                <div class="d-flex justify-content-between ">
                    <div >
                        <h2 class="section-title">{{ trans('home.english_classes') }}</h2>
                        <!--<h2 class="section-title">English Course</h2>-->
                        <p class="section-hint">{{ trans('home.english_classes_hint') }}</p>
                        <!--<p class="section-hint">Never miss english learning opportunities</p>-->
                    </div>

                    <a href="/classes" class="btn btn-border-white mobile-btn">{{ trans('home.view_all') }}<img width="20px" class="ml-5" src="/assets/default/mobile/right-arrow.png" alt="Right Arrow Icon - Asttrolok"></a>
                </div>

                <div class="mt-10 position-relative">
                    <div class="swiper-container latest-bundle-swiper ">
                        <div class="swiper-wrapper pt-20">
                            @foreach($englishclasses as $englishclasse)
                                <div class="swiper-slide">
                                    @include('web.default.includes.webinar.grid-card',['webinar' => $englishclasse])
                                </div>
                            @endforeach

                        </div>
                    </div>

                    <div class="d-flex justify-content-center">
                        <div class="swiper-pagination bundle-webinars-swiper-pagination"></div>
                    </div>
                    <!--<a href="/classes" class="btn btn-border-white" style="float: right;">{{ trans('home.view_all') }}</a>-->
                </div>
            </section>
            
            
        @endif

        <!--@if($homeSection->name == \App\Models\HomeSection::$latest_bundles and !empty($latestBundles) and !$latestBundles->isEmpty())-->
        <!--    <section class="home-sections home-sections-swiper container">-->
        <!--        <div class="d-flex justify-content-between ">-->
        <!--            <div>-->
                        <!--<h2 class="section-title">{{ trans('update.latest_bundles') }}</h2>-->
        <!--                <h2 class="section-title">English Course</h2>-->
                        <!--<p class="section-hint">{{ trans('update.latest_bundles_hint') }}</p>-->
        <!--                <p class="section-hint">Never miss english learning opportunities</p>-->
        <!--            </div>-->

        <!--            <a href="/classes?type[]=bundle" class="btn btn-border-white">{{ trans('home.view_all') }}</a>-->
        <!--        </div>-->

        <!--        <div class="mt-10 position-relative">-->
        <!--            <div class="swiper-container latest-bundle-swiper px-12">-->
        <!--                <div class="swiper-wrapper py-20">-->
        <!--                    @foreach($latestBundles as $latestBundle)-->
        <!--                        <div class="swiper-slide">-->
        <!--                            @include('web.default.includes.webinar.grid-card',['webinar' => $latestBundle])-->
        <!--                        </div>-->
        <!--                    @endforeach-->

        <!--                </div>-->
        <!--            </div>-->

        <!--            <div class="d-flex justify-content-center">-->
        <!--                <div class="swiper-pagination bundle-webinars-swiper-pagination"></div>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </section>-->
        <!--@endif-->

        {{-- Upcoming Course --}}
        @if($homeSection->name == \App\Models\HomeSection::$upcoming_courses and !empty($upcomingCourses) and !$upcomingCourses->isEmpty())
            <section class="home-sections home-sections-swiper container">
                <div class="d-flex justify-content-between ">
                    <div>
                        <h2 class="section-title">{{ trans('update.upcoming_courses') }}</h2>
                        <p class="section-hint">{{ trans('update.upcoming_courses_home_section_hint') }}</p>
                    </div>

                    <a href="/upcoming_courses?sort=newest" class="btn btn-border-white mobile-btn">{{ trans('home.view_all') }}<img width="20px" class="ml-5" src="/assets/default/mobile/right-arrow.png" alt="Right Arrow Icon - Asttrolok"></a>
                </div>

                <div class="mt-10 position-relative">
                    <div class="swiper-container upcoming-courses-swiper px-12">
                        <div class="swiper-wrapper py-20">
                            @foreach($upcomingCourses as $upcomingCourse)
                                <div class="swiper-slide">
                                    @include('web.default.includes.webinar.upcoming_course_grid_card',['upcomingCourse' => $upcomingCourse])
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="d-flex justify-content-center">
                        <div class="swiper-pagination upcoming-courses-swiper-pagination"></div>
                    </div>
                </div>
            </section>
        @endif

        @if($homeSection->name == \App\Models\HomeSection::$latest_classes and !empty($latestWebinars) and !$latestWebinars->isEmpty())
            <section class="home-sections home-sections-swiper container">
                <div class="d-flex justify-content-between ">
                    <div>
                        <h2 class="section-title">{{ trans('home.latest_classes') }}</h2>
                        <p class="section-hint">{{ trans('home.latest_webinars_hint') }}</p>
                    </div>

                    <a href="/classes?sort=newest" class="btn btn-border-white mobile-btn">{{ trans('home.view_all') }}<img width="20px" class="ml-5" src="/assets/default/mobile/right-arrow.png" alt="Right Arrow Icon - Asttrolok"></a>
                </div>

                <div class="mt-10 position-relative">
                    <div class="swiper-container latest-webinars-swiper px-12">
                        <div class="swiper-wrapper py-20">
                            @foreach($latestWebinars as $latestWebinar)
                                <div class="swiper-slide">
                                    @include('web.default.includes.webinar.grid-card',['webinar' => $latestWebinar])
                                </div>
                            @endforeach

                        </div>
                    </div>

                    <div class="d-flex justify-content-center">
                        <div class="swiper-pagination latest-webinars-swiper-pagination"></div>
                    </div>
                </div>
            </section>
        @endif

        @if($homeSection->name == \App\Models\HomeSection::$best_rates and !empty($bestRateWebinars) and !$bestRateWebinars->isEmpty())
            <section class="home-sections home-sections-swiper container">
                <div class="d-flex justify-content-between">
                    <div>
                        <h2 class="section-title">{{ trans('home.best_rates') }}</h2>
                        <p class="section-hint">{{ trans('home.best_rates_hint') }}</p>
                    </div>

                    <a href="/classes?sort=best_rates" class="btn btn-border-white mobile-btn">{{ trans('home.view_all') }}<img width="20px" class="ml-5" src="/assets/default/mobile/right-arrow.png" alt="Right Arrow Icon - Asttrolok"></a>
                </div>

                <div class="mt-10 position-relative">
                    <div class="swiper-container best-rates-webinars-swiper px-12">
                        <div class="swiper-wrapper py-20">
                            @foreach($bestRateWebinars as $bestRateWebinar)
                                <div class="swiper-slide">
                                    @include('web.default.includes.webinar.grid-card',['webinar' => $bestRateWebinar])
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="d-flex justify-content-center">
                        <div class="swiper-pagination best-rates-webinars-swiper-pagination"></div>
                    </div>
                </div>
            </section>
        @endif

        @if($homeSection->name == \App\Models\HomeSection::$trend_categories and !empty($trendCategories) and !$trendCategories->isEmpty())
            <section class="home-sections home-sections-swiper container">
                <h2 class="section-title">{{ trans('home.trending_categories') }}</h2>
                <p class="section-hint">{{ trans('home.trending_categories_hint') }}</p>


                <div class="swiper-container trend-categories-swiper px-12 mt-40">
                    <div class="swiper-wrapper py-20">
                        @foreach($trendCategories as $trend)
                            <div class="swiper-slide">
                                <a href="{{ $trend->category->getUrl() }}">
                                    <div class="trending-card d-flex flex-column align-items-center w-100">
                                        <div class="trending-image d-flex align-items-center justify-content-center w-100" style="background-color: {{ $trend->color }}">
                                            <div class="icon mb-3">
                                                <img src="{{ config('app.img_dynamic_url') }}{{ $trend->getIcon() }}" width="10" class="img-cover" alt="{{ $trend->category->title }}">
                                            </div>
                                        </div>

                                        <div class="item-count px-10 px-lg-20 py-5 py-lg-10">{{ $trend->category->webinars_count }} {{ trans('product.course') }}</div>

                                        <h3>{{ $trend->category->title }}</h3>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="d-flex justify-content-center">
                    <div class="swiper-pagination trend-categories-swiper-pagination"></div>
                </div>
            </section>
        @endif

        {{-- Ads Bannaer --}}
        @if($homeSection->name == \App\Models\HomeSection::$full_advertising_banner and !empty($advertisingBanners1) and count($advertisingBanners1))
            <div class="home-sections container">
                <div class="row">
                    @foreach($advertisingBanners1 as $banner1)
                        <div class="col-{{ $banner1->size }}">
                            <a href="{{ $banner1->link }}">
                                
                                <img src="{{ config('app.img_dynamic_url') }}{{ $banner1->image }}" class="img-cover rounded-sm" alt="{{ $banner1->title }}">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        {{-- ./ Ads Bannaer --}}

        @if($homeSection->name == \App\Models\HomeSection::$best_sellers and !empty($bestSaleWebinars) and !$bestSaleWebinars->isEmpty())
            <section class="home-sections container">
                <div class="d-flex justify-content-between">
                    <div>
                        <h2 class="section-title">{{ trans('home.best_sellers') }}</h2>
                        <p class="section-hint">{{ trans('home.best_sellers_hint') }}</p>
                    </div>

                    <a href="/classes?sort=bestsellers" class="btn btn-border-white mobile-btn">{{ trans('home.view_all') }}<img width="20px" class="ml-5" src="/assets/default/mobile/right-arrow.png" alt="Right Arrow Icon - Asttrolok"></a>
                </div>

                <div class="mt-10 position-relative">
                    <div class="swiper-container best-sales-webinars-swiper px-12">
                        <div class="swiper-wrapper py-20">
                            @foreach($bestSaleWebinars as $bestSaleWebinar)
                                <div class="swiper-slide">
                                    @include('web.default.includes.webinar.grid-card',['webinar' => $bestSaleWebinar])
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="d-flex justify-content-center">
                        <div class="swiper-pagination best-sales-webinars-swiper-pagination"></div>
                    </div>
                </div>
            </section>
        @endif

        @if($homeSection->name == \App\Models\HomeSection::$discount_classes and !empty($hasDiscountWebinars) and !$hasDiscountWebinars->isEmpty())
            <section class="home-sections container">
                <div class="d-flex justify-content-between">
                    <div>
                        <h2 class="section-title">{{ trans('home.discount_classes') }}</h2>
                        <p class="section-hint">{{ trans('home.discount_classes_hint') }}</p>
                    </div>

                    <a href="/classes?discount=on" class="btn btn-border-white mobile-btn">{{ trans('home.view_all') }}<img width="20px" class="ml-5" src="/assets/default/mobile/right-arrow.png" alt="Right Arrow Icon - Asttrolok"></a>
                </div>

                <div class="mt-10 position-relative">
                    <div class="swiper-container has-discount-webinars-swiper px-12">
                        <div class="swiper-wrapper py-20">
                            @foreach($hasDiscountWebinars as $hasDiscountWebinar)
                                <div class="swiper-slide">
                                    @include('web.default.includes.webinar.grid-card',['webinar' => $hasDiscountWebinar])
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="d-flex justify-content-center">
                        <div class="swiper-pagination has-discount-webinars-swiper-pagination"></div>
                    </div>
                </div>
            </section>
        @endif

        @if($homeSection->name == \App\Models\HomeSection::$hindi_classes and !empty($hindiWebinars) and !$hindiWebinars->isEmpty())
            <section class="homehide home-sections home-sections-swiper container">
                <div class="d-flex justify-content-between">
                    <div >
                        <h2 class="section-title">{{ trans('home.hindi_classes') }}</h2>
                        <p class="section-hint">{{ trans('home.hindi_classes_hint') }}</p>
                    </div>
           <a href="/classes?hindi=on" class="btn btn-border-white mobile-btn"  style="float: right;">{{ trans('home.view_all') }}<img width="20px" class="ml-5" src="/assets/default/mobile/right-arrow.png" alt="Right Arrow Icon - Asttrolok"></a>
                    <!--<a href="/classes?hindi=on" class="btn btn-border-white">{{ trans('home.view_all') }}</a>-->
                </div>

                <div class="mt-10 position-relative">
                    <div class="swiper-container free-webinars-swiper px-12">
                        <div class="swiper-wrapper py-20">

                            @foreach($hindiWebinars as $hindiWebinar)
                                <div class="swiper-slide">
                                    @include('web.default.includes.webinar.grid-card',['webinar' => $hindiWebinar])
                                </div>
                            @endforeach

                        </div>
                    </div>

                    <div class="d-flex justify-content-center">
                        <div class="swiper-pagination free-webinars-swiper-pagination"></div>
                   
                    </div>
                    <!--<div class="r-flex">-->
                    <!--   <a href="/classes?hindi=on" class="btn btn-border-white">{{ trans('home.view_all') }}</a>-->
                   
                    <!--</div>-->
                     <!--<a href="/classes?hindi=on" class="btn btn-border-white">{{ trans('home.view_all') }}</a>-->
                </div>
            </section>
            
            <!--<section class="home-sections container">-->
                <!--<div class="d-flex justify-content-between ">-->
                <!--    <div>-->
                <!--        <h2 class="section-title">OUR ASTROLOGERS</h2>-->
                        <!--<h2 class="section-title">English Course</h2>-->
                <!--        <p class="section-hint">100+ Best Astrologers from India for Online Consultation</p>-->
                        <!--<p class="section-hint">Never miss english learning opportunities</p>-->
                <!--    </div>-->

                <!--    <a href="/instructors" class="btn btn-border-white">{{ trans('home.view_all') }}</a>-->
                <!--</div>-->

                <!--<div class="mt-10 position-relative col-12 col-lg-12 " style="">-->
                <!--    <div class="row" style="text-align:center;background: #43d377;padding: 60px;border-radius: 15px;">-->
                <!--        <div class=" position-relative col-12 col-lg-3" >-->
                <!--            <div style="background: #fdfdfd7a;border-radius: 50%;height: 200px;padding-top: 56px;box-shadow:0 5px 12px 0 rgba(0, 0, 0, 0.1);">-->
                <!--                <h2 class="section-title" style="font-size: 30px !important;">25000+</h2>-->
                <!--                <h2 class="section-title" >Happy Customers</h2>-->
                <!--            </div>-->
                <!--        </div>-->
                <!--        <div class=" position-relative col-12 col-lg-3 ">-->
                <!--            <div style="background: #fdfdfd7a;border-radius: 50%;height: 200px;padding-top: 56px;box-shadow:0 5px 12px 0 rgba(0, 0, 0, 0.1);">-->
                <!--            <h2 class="section-title" style="font-size: 30px !important;">50000+</h2>-->
                <!--            <h2 class="section-title">Consultations</h2>-->
                <!--        </div>-->
                <!--        </div>-->
                <!--        <div class=" position-relative col-12 col-lg-3 " >-->
                <!--            <div style="background: #fdfdfd7a;border-radius: 50%;height: 200px;padding-top: 56px;box-shadow:0 5px 12px 0 rgba(0, 0, 0, 0.1);">-->
                <!--            <h2 class="section-title" style="font-size: 30px !important;">50+</h2>-->
                <!--            <h2 class="section-title">Countries</h2>-->
                <!--        </div>-->
                <!--        </div>-->
                <!--        <div class=" position-relative col-12 col-lg-3 ">-->
                <!--            <div style="background: #fdfdfd7a;border-radius: 50%;height: 200px;padding-top: 56px;box-shadow:0 5px 12px 0 rgba(0, 0, 0, 0.1);">-->
                <!--            <h2 class="section-title" style="font-size: 30px !important;">100+</h2>-->
                <!--            <h2 class="section-title">Panel Astrologers</h2>-->
                <!--        </div>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->
            <!--</section>-->
        @endif

        @if($homeSection->name == \App\Models\HomeSection::$free_classes and !empty($freeWebinars) and !$freeWebinars->isEmpty())
            <section class="home-sections home-sections-swiper container">
                <div class="d-flex justify-content-between">
                    <div>
                        <h2 class="section-title">{{ trans('home.free_classes') }}</h2>
                        <p class="section-hint">{{ trans('home.free_classes_hint') }}</p>
                    </div>

                    <a href="/classes?free=on" class="btn btn-border-white mobile-btn">{{ trans('home.view_all') }}<img width="20px" class="ml-5" src="/assets/default/mobile/right-arrow.png" alt="Right Arrow Icon - Asttrolok"></a>
                </div>

                <div class="mt-10 position-relative">
                    <div class="swiper-container free-webinars-swiper px-12">
                        <div class="swiper-wrapper py-20">

                            @foreach($freeWebinars as $freeWebinar)
                                <div class="swiper-slide">
                                    @include('web.default.includes.webinar.grid-card',['webinar' => $freeWebinar])
                                </div>
                            @endforeach

                        </div>
                    </div>

                    <div class="d-flex justify-content-center">
                        <div class="swiper-pagination free-webinars-swiper-pagination"></div>
                    </div>
                </div>
            </section>
        @endif

        @if($homeSection->name == \App\Models\HomeSection::$store_products and !empty($newProducts) and !$newProducts->isEmpty())
            <section class="home-sections home-sections-swiper container">
                <div class="d-flex justify-content-between">
                    <div>
                        <h2 class="section-title">{{ trans('update.store_products') }}</h2>
                        <p class="section-hint">{{ trans('update.store_products_hint') }}</p>
                    </div>

                    <a href="/products" class="btn btn-border-white">{{ trans('update.all_products') }}</a>
                </div>

                <div class="mt-10 position-relative">
                    <div class="swiper-container new-products-swiper px-12">
                        <div class="swiper-wrapper py-20">

                            @foreach($newProducts as $newProduct)
                                <div class="swiper-slide">
                                    @include('web.default.products.includes.card',['product' => $newProduct])
                                </div>
                            @endforeach

                        </div>
                    </div>

                    <!--<div class="d-flex justify-content-center">-->
                    <!--    <div class="swiper-pagination new-products-swiper-pagination"></div>-->
                    <!--</div>-->
                </div>
            </section>
        @endif
        
         @if($homeSection->name == \App\Models\HomeSection::$testimonials and !empty($testimonials) and !$testimonials->isEmpty())
       {{--  @include('web.default.pages.includes.category_statics') --}}
            <!--<div class="position-relative home-sections testimonials-container" style=" background-size: contain; background-image: url('{{ config('app.img_dynamic_url') }}/store/1/default_images/home_sections_banners/Testimonial Background.png')"style="background-image: url('{{ config('app.img_dynamic_url') }}/store/1/default_images/home_sections_banners/Testimonial Background.png')">-->
            <div class="homehide position-relative testimonials-container mt-responsive">

                <!--<div id="parallax1" class="ltr">-->
                <!--    <div data-depth="0.2" class="gradient-box left-gradient-box"></div>-->
                <!--</div>-->

                <section class="container home-sections home-sections-swiper">
                    <div class="text-center">
                        <h2 class="section-title">{{ trans('home.testimonials') }}</h2>
                        <p class="section-hint">{{ trans('home.testimonials_hint') }}</p>
                    </div>

                    <div class="position-relative">
                        <div class="swiper-container testimonials-swiper px-12">
                            <div class="swiper-wrapper">

                                @foreach($testimonials as $testimonial)
                                    <div class="swiper-slide">
                                        <div class="testimonials-card position-relative py-15 py-lg-30 px-10 px-lg-20 rounded-sm shadow bg-white text-center">
                                            <div class="d-flex flex-column align-items-center">
                                                <div class="testimonials-user-avatar">
                                                    <img src="{{ config('app.img_dynamic_url') }}{{ $testimonial->user_avatar }}" alt="{{ $testimonial->user_name }}" class="img-cover rounded-circle">
                                                </div>
                                                <h4 class="font-16 font-weight-bold text-secondary mt-30">{{ $testimonial->user_name }}</h4>
                                                <span class="d-block font-14 text-gray">{{ $testimonial->user_bio }}</span>
                                                @include('web.default.includes.webinar.rate',['rate' => $testimonial->rate, 'dontShowRate' => true])
                                            </div>
                                            <div  class="mt-25 testimonials-p scrollbar-width-thin">

                                            <p class=" text-gray font-14 pr-5">{!! nl2br($testimonial->comment) !!}</p>
</div>
                                            <div class="bottom-gradient"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>

                        <div class="d-flex justify-content-center">
                            <div class="swiper-pagination testimonials-swiper-pagination"></div>
                        </div>
                    </div>
                </section>

                <!--<div id="parallax2" class="ltr">-->
                <!--    <div data-depth="0.4" class="gradient-box right-gradient-box"></div>-->
                <!--</div>-->

                <!--<div id="parallax3" class="ltr">-->
                <!--    <div data-depth="0.8" class="gradient-box bottom-gradient-box"></div>-->
                <!--</div>-->
            </div>
        @endif

         @if($homeSection->name == \App\Models\HomeSection::$testimonials and !empty($testimonials) and !$testimonials->isEmpty())
                    <!--<div class="position-relative home-sections testimonials-container">-->
        <!--<script src="https://static.elfsight.com/platform/platform.js" data-use-service-core defer></script>-->
        <!--<div class="elfsight-app-dbf2d1b5-428f-4b95-8b4d-2a56fd433bf1" data-elfsight-app-lazy></div>-->
                        
                    <!--</div>-->
                @endif
                
                @if($homeSection->name == \App\Models\HomeSection::$testimonials and !empty($testimonials) and !$testimonials->isEmpty())
          
            <section class="home-sections container homehide">
                <style>
                    .blog-grid-card{
                        min-height:340px !important;
                    }
                    .blog-grid-image  {
                      height: 127.66px !important;
                    }
                     @media (min-width: 900px) {
  
                 .video-section {
                     width: 269px;
                    height: -webkit-fill-available;
                 }
                     }
                 @media (width: 600px) {
  
                 .video-section {
                     min-width: 269px;
                    height: -webkit-fill-available;
                 }
                 }
                </style>
                <div class="d-flex justify-content-between">
                    <!--<div class="text-center">-->
                    <!--    <h2 class="section-title">{{ trans('home.testimonials') }}</h2>-->
                    <!--    <p class="section-hint">{{ trans('home.testimonials_hint') }}</p>-->
                    <!--</div>-->

                   
                </div>

                <div class="row mt-35">

                     @foreach ($testimonial_video as $post)
                        <div class="col-12 col-md-3 mt-20 mt-lg-0 video-section-mobilehide">
                            <div class="video-section" >
                               {{-- {!!$post!!} --}}
                               <a href="#" class="" data-toggle="modal" data-target="#import" onclick="changeImageAndShowPopup('{{$post['link']}}')"><img src="{{ config('app.img_dynamic_url') }}{{$post['image']}}" class="  shadow-sm rounded-lg" style="height: auto;width: 100%;" alt="{{$post['title']}}"></a>
                               
                            </div>
                        </div>
                    @endforeach

                </div>
                
                
                
            </section>
        @endif
                    

        @if($homeSection->name == \App\Models\HomeSection::$subscribes and !empty($subscribes) and !$subscribes->isEmpty())
            <div class="home-sections position-relative subscribes-container pe-none user-select-none">
                <div id="parallax4" class="ltr d-none d-md-block">
                    <div data-depth="0.2" class="gradient-box left-gradient-box"></div>
                </div>

                <section class="container home-sections home-sections-swiper">
                    <div class="text-center">
                        <h2 class="section-title">{{ trans('home.subscribe_now') }}</h2>
                        <p class="section-hint">{{ trans('home.subscribe_now_hint') }}</p>
                    </div>

                    <div class="position-relative mt-30">
                        <div class="swiper-container subscribes-swiper px-12">
                            <div class="swiper-wrapper py-20">

                                @foreach($subscribes as $subscribe)
                                    @php
                                        $subscribeSpecialOffer = $subscribe->activeSpecialOffer();
                                    @endphp

                                    <div class="swiper-slide">
                                        <div class="subscribe-plan position-relative bg-white d-flex flex-column align-items-center rounded-sm shadow pt-50 pb-20 px-20">
                                            @if($subscribe->is_popular)
                                                <span class="badge badge-primary badge-popular px-15 py-5">{{ trans('panel.popular') }}</span>
                                            @elseif(!empty($subscribeSpecialOffer))
                                                <span class="badge badge-danger badge-popular px-15 py-5">{{ trans('update.percent_off', ['percent' => $subscribeSpecialOffer->percent]) }}</span>
                                            @endif

                                            <div class="plan-icon">
                                                <img src="{{ config('app.img_dynamic_url') }}{{ $subscribe->icon }}" class="img-cover" alt="">
                                            </div>

                                            <h3 class="mt-20 font-30 text-secondary">{{ $subscribe->title }}</h3>
                                            <p class="font-weight-500 text-gray mt-10">{{ $subscribe->description }}</p>

                                            <div class="d-flex align-items-start mt-30">
                                                @if(!empty($subscribe->price) and $subscribe->price > 0)
                                                    @if(!empty($subscribeSpecialOffer))
                                                        <div class="d-flex align-items-end line-height-1">
                                                            <span class="font-36 text-primary">{{ handlePrice($subscribe->getPrice()) }}</span>
                                                            <span class="font-14 text-gray ml-5 text-decoration-line-through">{{ handlePrice($subscribe->price) }}</span>
                                                        </div>
                                                    @else
                                                        <span class="font-36 text-primary line-height-1">{{ handlePrice($subscribe->price) }}</span>
                                                    @endif
                                                @else
                                                    <span class="font-36 text-primary line-height-1">{{ trans('public.free') }}</span>
                                                @endif
                                            </div>

                                            <ul class="mt-20 plan-feature">
                                                <li class="mt-10">{{ $subscribe->days }} {{ trans('financial.days_of_subscription') }}</li>
                                                <li class="mt-10">
                                                    @if($subscribe->infinite_use)
                                                        {{ trans('update.unlimited') }}
                                                    @else
                                                        {{ $subscribe->usable_count }}
                                                    @endif
                                                    <span class="ml-5">{{ trans('update.subscribes') }}</span>
                                                </li>
                                            </ul>

                                            @if(auth()->check())
                                                <form action="/panel/financial/pay-subscribes" method="post" class="w-100">
                                                    {{ csrf_field() }}
                                                    <input name="amount" value="{{ $subscribe->price }}" type="hidden">
                                                    <input name="id" value="{{ $subscribe->id }}" type="hidden">

                                                    <div class="d-flex align-items-center mt-50 w-100">
                                                        <button type="submit" class="btn btn-primary {{ !empty($subscribe->has_installment) ? '' : 'btn-block' }}">{{ trans('update.purchase') }}</button>

                                                        @if(!empty($subscribe->has_installment))
                                                            <a href="/panel/financial/subscribes/{{ $subscribe->id }}/installments" class="btn btn-outline-primary flex-grow-1 ml-10">{{ trans('update.installments') }}</a>
                                                        @endif
                                                    </div>
                                                </form>
                                            @else
                                                <a href="/login" class="btn btn-primary btn-block mt-50">{{ trans('update.purchase') }}</a>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                        <!--<div class="d-flex justify-content-center">-->
                        <!--    <div class="swiper-pagination subscribes-swiper-pagination"></div>-->
                        <!--</div>-->

                    </div>
                </section>

                <!--<div id="parallax5" class="ltr d-none d-md-block">-->
                <!--    <div data-depth="0.4" class="gradient-box right-gradient-box"></div>-->
                <!--</div>-->

                <!--<div id="parallax6" class="ltr d-none d-md-block">-->
                <!--    <div data-depth="0.6" class="gradient-box bottom-gradient-box"></div>-->
                <!--</div>-->
            </div>
        @endif

        @if($homeSection->name == \App\Models\HomeSection::$find_instructors and !empty($findInstructorSection))
        
            
            <section class="home-sections home-sections-swiper container find-instructor-section position-relative homehide">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-6">
                        <div class="">
                            <h2 class="font-36 font-weight-bold text-dark">{{ $findInstructorSection['title'] ?? '' }}</h2>
                            <p class="font-16 font-weight-normal text-gray mt-10">{{ $findInstructorSection['description'] ?? '' }}</p>

                            <div class="mt-35 d-flex align-items-center">
                                @if(!empty($findInstructorSection['button1']))
                                    <a href="{{ $findInstructorSection['button1']['link'] }}" class="btn btn-primary">{{ $findInstructorSection['button1']['title'] }}</a>
                                @endif

                                @if(!empty($findInstructorSection['button2']))
                                    <a href="{{ $findInstructorSection['button2']['link'] }}" class="btn btn-outline-primary ml-15">{{ $findInstructorSection['button2']['title'] }}</a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 mt-20 mt-lg-0">
                        <div class="position-relative ">
                            <img src="{{ config('app.img_dynamic_url') }}{{ $findInstructorSection['image'] }}" class="find-instructor-section-hero" alt="{{ $findInstructorSection['title'] }}">
                            <img src="{{ config('app.js_css_url') }}/assets/default/img/home/circle-4.png" class="find-instructor-section-circle" alt="circle">
                            <img src="{{ config('app.js_css_url') }}/assets/default/img/home/dot.png" class="find-instructor-section-dots" alt="dots">

                            <div class="example-instructor-card bg-white rounded-sm shadow-lg  p-5 p-md-15 d-flex align-items-center">
                                <div class="example-instructor-card-avatar">
                                    <img src="{{ config('app.js_css_url') }}/assets/default/img/home/toutor_finder.svg" class="img-cover rounded-circle" alt="user name">
                                </div>

                                <div class="flex-grow-1 ml-15">
                                    <span class="font-14 font-weight-bold text-secondary d-block">{{ trans('update.looking_for_an_instructor') }}</span>
                                    <span class="text-gray font-12 font-weight-500">{{ trans('update.find_the_best_instructor_now') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
             
        @endif

        @if($homeSection->name == \App\Models\HomeSection::$reward_program and !empty($rewardProgramSection))
            <section class="home-sections home-sections-swiper container reward-program-section position-relative">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-6">
                        <div class="position-relative reward-program-section-hero-card">
                            <img src="{{ config('app.img_dynamic_url') }}{{ $rewardProgramSection['image'] }}" class="rewardss" alt="{{ $rewardProgramSection['title'] }}">

                            <div class="example-reward-card bg-white rounded-sm shadow-lg p-5 p-md-5 d-flex align-items-center">
                                <div class="example-reward-card-medal">
                                    <img src="{{ config('app.js_css_url') }}/assets/default/img/rewards/medal.png" class="img-cover rounded-circle" alt="medal">
                                </div>

                                <div class="flex-grow-1 ml-15">
                                    <span class="font-14 font-weight-bold text-secondary d-block">{{ trans('update.you_got_50_points') }}</span>
                                    <span class="text-gray font-12 font-weight-500">{{ trans('update.for_completing_the_course') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 mt-20 mt-lg-0">
                        <div class="">
                            <h2 class="font-36 font-weight-bold text-dark">{{ $rewardProgramSection['title'] ?? '' }}</h2>
                            <p class="font-16 font-weight-normal text-gray mt-10">{{ $rewardProgramSection['description'] ?? '' }}</p>

                            <div class="mt-35 d-flex align-items-center">
                                @if(!empty($rewardProgramSection['button1']))
                                    <a href="{{ $rewardProgramSection['button1']['link'] }}" class="btn btn-primary">{{ $rewardProgramSection['button1']['title'] }}</a>
                                @endif

                                @if(!empty($rewardProgramSection['button2']))
                                    <a href="{{ $rewardProgramSection['button2']['link'] }}" class="btn btn-outline-primary ml-15">{{ $rewardProgramSection['button2']['title'] }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        @if($homeSection->name == \App\Models\HomeSection::$become_instructor and !empty($becomeInstructorSection))
        
            <section class="home-sections home-sections-swiper container find-instructor-section position-relative homehide">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-6">
                        <div class="">
                            <h2 class="font-36 font-weight-bold text-dark">{{ $becomeInstructorSection['title'] ?? '' }}</h2>
                            <p class="font-16 font-weight-normal text-gray mt-10">{{ $becomeInstructorSection['description'] ?? '' }}</p>

                            <div class="mt-35 d-flex align-items-center">
                                @if(!empty($becomeInstructorSection['button1']))
                                    <a href="{{ empty($authUser) ? '/login' : (($authUser->isUser()) ? $becomeInstructorSection['button1']['link'] : '/panel/financial/registration-packages') }}" class="btn btn-primary">{{ $becomeInstructorSection['button1']['title'] }}</a>
                                @endif

                                @if(!empty($becomeInstructorSection['button2']))
                                    <a href="{{ empty($authUser) ? '/login' : (($authUser->isUser()) ? $becomeInstructorSection['button2']['link'] : '/panel/financial/registration-packages') }}" class="btn btn-outline-primary ml-15">{{ $becomeInstructorSection['button2']['title'] }}</a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 mt-20 mt-lg-0">
                        <div class="position-relative ">
                            <img src="{{ config('app.img_dynamic_url') }}{{ $becomeInstructorSection['image'] }}" class="find-instructor-section-hero" alt="{{ $becomeInstructorSection['title'] }}">
                            <img src="{{ config('app.js_css_url') }}/assets/default/img/home/circle-4.png" class="find-instructor-section-circle" alt="circle">
                            <img src="{{ config('app.js_css_url') }}/assets/default/img/home/dot.png" class="find-instructor-section-dots" alt="dots">

                            <div class="example-instructor-card bg-white rounded-sm shadow-lg border p-5 p-md-15 d-flex align-items-center">
                                <div class="example-instructor-card-avatar">
                                    <img src="{{ config('app.js_css_url') }}/assets/default/img/home/become_instructor.svg" class="img-cover rounded-circle" alt="user name">
                                </div>

                                <div class="flex-grow-1 ml-15">
                                    <span class="font-14 font-weight-bold text-secondary d-block">{{ trans('update.become_an_instructor') }}</span>
                                    <span class="text-gray font-12 font-weight-500">{{ trans('update.become_instructor_tagline') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        @if($homeSection->name == \App\Models\HomeSection::$forum_section and !empty($forumSection))
            <section class="home-sections home-sections-swiper container find-instructor-section position-relative">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-6 mt-20 mt-lg-0">
                        <div class="position-relative ">
                            <img src="{{ config('app.img_dynamic_url') }}{{ $forumSection['image'] }}" class="find-instructor-section-hero" alt="{{ $forumSection['title'] }}">
                            <img src="{{ config('app.js_css_url') }}/assets/default/img/home/circle-4.png" class="find-instructor-section-circle" alt="circle">
                            <img src="{{ config('app.js_css_url') }}/assets/default/img/home/dot.png" class="find-instructor-section-dots" alt="dots">
                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="">
                            <h2 class="font-36 font-weight-bold text-dark">{{ $forumSection['title'] ?? '' }}</h2>
                            <p class="font-16 font-weight-normal text-gray mt-10">{{ $forumSection['description'] ?? '' }}</p>

                            <div class="mt-35 d-flex align-items-center">
                                @if(!empty($forumSection['button1']))
                                    <a href="{{ $forumSection['button1']['link'] }}" class="btn btn-primary">{{ $forumSection['button1']['title'] }}</a>
                                @endif

                                @if(!empty($forumSection['button2']))
                                    <a href="{{ $forumSection['button2']['link'] }}" class="btn btn-outline-primary ml-15">{{ $forumSection['button2']['title'] }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        @if($homeSection->name == \App\Models\HomeSection::$video_or_image_section and !empty($boxVideoOrImage))
            <section class="home-sections home-sections-swiper position-relative">
                <div class="home-video-mask"></div>
                <div class="container home-video-container d-flex flex-column align-items-center justify-content-center position-relative" style="background-image: url('{{ config('app.img_dynamic_url') }}{{ $boxVideoOrImage['background'] ?? '' }}')">
                    <a href="{{ $boxVideoOrImage['link'] ?? '' }}" class="home-video-play-button d-flex align-items-center justify-content-center position-relative">
                        <i data-feather="play" width="36" height="36" class=""></i>
                    </a>

                    <div class="mt-50 pt-10 text-center">
                        <h2 class="home-video-title">{{ $boxVideoOrImage['title'] ?? '' }}</h2>
                        <p class="home-video-hint mt-10">{{ $boxVideoOrImage['description'] ?? '' }}</p>
                    </div>
                </div>
            </section>
        @endif


 @if($homeSection->name == \App\Models\HomeSection::$consultant and !empty($consultant) and !$consultant->isEmpty())
        <section class="homehide home-sections container ">
                <div class="d-flex justify-content-between">
                    <div class="">
                        <h2 class="section-title">Astrologers</h2>
                        <p class="section-hint">#Discover your path with top astrologers – <b>Book an astrology consultation</b></p>
                    </div>

                <a href="/consult-with-astrologers" class="btn btn-border-white mobile-btn">{{ trans('home.view_all') }}<img width="20px" class="ml-5" src="/assets/default/mobile/right-arrow.png" alt="Right Arrow Icon - Asttrolok"></a>
                </div>

                <!--<div class="position-relative mt-20 ltr">-->
                <!--    <div class="owl-carousel customers-testimonials instructors-swiper-container ">-->

                        <div class="deckteacher teacher-swiper-container position-relative d-flex justify-content-center mt-0">
            <div class="swiper-container teacher-swiper-container pb-25">
               <div class="swiper-wrapper py-0">
                @foreach($consultant as $instructor)
                   <div class="swiper-slide">
                    <div class="rounded-lg shadow-sm mt-15  p-5 course-teacher-card d-flex align-items-center flex-column">
                       <div class="teacher-avatar mt-15">
                          <img src="{{ config('app.img_dynamic_url') }}{{ $instructor->getAvatar(108) }}" class="img-cover" alt="{{ $instructor->full_name }}">
                          <span class="user-circle-badge has-verified d-flex align-items-center justify-content-center">
                             <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check text-white">
                                <polyline points="20 6 9 17 4 12"></polyline>
                             </svg>
                          </span>
                       </div>
                       <h3 class="mt-10 font-16 font-weight-bold text-secondary text-center swiper-container1-title">{{ $instructor->full_name }}</h3>
                       <span class="mt-5 font-14 font-weight-500 text-gray text-center swiper-container1-desc">{{ $instructor->bio }}</span>
                        <div class="stars-card d-flex align-items-center mt-10">
                          {{--  @include('web.default.includes.webinar.rate',['rate' => $instructor->rates()]) --}}
                            @include('web.default.includes.webinar.rate',['rate' => $instructor->rating])
                        </div>
                       <div class="stars-card d-none align-items-center  mt-15">
                                                @php
                                                    $i = 5;
                                                @endphp
                                                @while(--$i >= 5 - $instructor->rating)
                                                    <i data-feather="star" width="13" height="13" class="active"></i>
                                                @endwhile
                                                @while($i-- >= 0)
                                                    <i data-feather="star" width="13" height="13" class=""></i>
                                                @endwhile
                           </div>
                       <div class="my-15   align-items-center text-center  w-100">
                           <a href="{{ $instructor->getProfileUrl() }}?tab=appointments" class="btn btn-sm btn-primary swiper-container1-btn">Book a Consultation</a>
                       </div>
                    </div>
                 </div>
                 @endforeach  
                   
                 
                 
                 
               </div>
            </div>
            <div class="swiper-pagination teacher-swiper-pagination ast-pagination"></div>
         </div>

            </section>
            
        @endif



        @if($homeSection->name == \App\Models\HomeSection::$instructors and !empty($instructors) and !$instructors->isEmpty())
       {{-- <section class="home-sections container ">
                <div class="d-flex justify-content-between">
                    <div class="">
                        <h2 class="section-title">Astrologers</h2>
                        <p class="section-hint">#Discover your path with top astrologers – <b>Book an astrology consultation</b></p>
                    </div>

                <a href="/consult-with-astrologers" class="btn btn-border-white mobile-btn">{{ trans('home.view_all') }}</a>
                </div>

                <!--<div class="position-relative mt-20 ltr">-->
                <!--    <div class="owl-carousel customers-testimonials instructors-swiper-container ">-->

                        <div class="deckteacher teacher-swiper-container position-relative d-flex justify-content-center mt-0">
            <div class="swiper-container teacher-swiper-container pb-25">
               <div class="swiper-wrapper py-0">
                @foreach($consultant as $instructor)
                   <div class="swiper-slide">
                    <div class="rounded-lg shadow-sm mt-15  p-5 course-teacher-card d-flex align-items-center flex-column">
                       <div class="teacher-avatar mt-15">
                          <img src="{{ config('app.img_dynamic_url') }}{{ $instructor->getAvatar(108) }}" class="img-cover" alt="{{ $instructor->full_name }}">
                          <span class="user-circle-badge has-verified d-flex align-items-center justify-content-center">
                             <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check text-white">
                                <polyline points="20 6 9 17 4 12"></polyline>
                             </svg>
                          </span>
                       </div>
                       <h3 class="mt-10 font-16 font-weight-bold text-secondary text-center swiper-container1-title">{{ $instructor->full_name }}</h3>
                       <span class="mt-5 font-14 font-weight-500 text-gray text-center swiper-container1-desc">{{ $instructor->bio }}</span>
                       <div class="stars-card d-none align-items-center  mt-15">
                                                @php
                                                    $i = 5;
                                                @endphp
                                                @while(--$i >= 5 - $instructor->rating)
                                                    <i data-feather="star" width="13" height="13" class="active"></i>
                                                @endwhile
                                                @while($i-- >= 0)
                                                    <i data-feather="star" width="13" height="13" class=""></i>
                                                @endwhile
                           </div>
                       <div class="my-15   align-items-center text-center  w-100">
                           <a href="{{ $instructor->getProfileUrl() }}" class="btn btn-sm btn-primary swiper-container1-btn">Book a Consultation</a>
                       </div>
                    </div>
                 </div>
                 @endforeach  
                   
                 
                 
                 
               </div>
            </div>
            <div class="swiper-pagination teacher-swiper-pagination ast-pagination"></div>
         </div>

                <!--    </div>-->
                        
                <!--</div>-->
            </section> --}}
            <section class="home-sections container homehide">
                <div class="d-flex justify-content-between">
                    <div class="">
                        <h2 class="section-title">{{ trans('home.instructors') }}</h2>
                        <p class="section-hint">{{ trans('home.instructors_hint') }}</p>
                    </div>

                <a href="/consult-with-astrologers?tab=content" class="btn btn-border-white mobile-btn">{{ trans('home.view_all') }}<img width="20px" class="ml-5" src="/assets/default/mobile/right-arrow.png" alt="Right Arrow Icon - Asttrolok"></a>
                </div>

                <div class="position-relative mt-20 ltr">
                    <div class="owl-carousel customers-testimonials instructors-swiper-container">

                        @foreach($instructors as $instructor)
                            <div class="item">
                                <div class="shadow-effect">
                                    <div class="instructors-card d-flex flex-column align-items-center justify-content-center">
                                        <div class="instructors-card-avatar">
                                            <img src="{{ config('app.img_dynamic_url') }}{{ $instructor->getAvatar(108) }}" alt="{{ $instructor->full_name }}" class="rounded-circle img-cover">
                                        </div>
                                        <div class="instructors-card-info mt-10 text-center">
                                            <a href="{{ $instructor->getProfileUrl() }}" target="_blank">
                                                <h3 class="font-16 font-weight-bold text-dark-blue">{{ $instructor->full_name }}</h3>
                                            </a>

                                            <p class="font-14 text-gray mt-5">{{ $instructor->bio }}</p>
                                            <div class="stars-card d-flex align-items-center justify-content-center mt-10">
                                                @php
                                                    $i = 5;
                                                @endphp
                                                @while(--$i >= 5 - $instructor->rating)
                                                    <!--<i data-feather="star" width="20" height="20" class="active"></i>-->
                                                @endwhile
                                                @while($i-- >= 0)
                                                    <!--<i data-feather="star" width="20" height="20" class=""></i>-->
                                                @endwhile
                                            </div>

                                            <!--@if(!empty($instructor->hasMeeting()))-->
                                            <!--    <a href="{{ $instructor->getProfileUrl() }}?tab=appointments" class="btn btn-primary btn-sm rounded-pill mt-15">{{ trans('home.reserve_a_live_class') }}</a>-->
                                            <!--@else-->
                                            <!--    <a href="{{ $instructor->getProfileUrl() }}" class="btn btn-primary btn-sm rounded-pill mt-15">{{ trans('public.profile') }}</a>-->
                                            <!--@endif-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                        
                </div>
            </section>
            
        @endif

        {{-- Ads Bannaer --}}
        @if($homeSection->name == \App\Models\HomeSection::$half_advertising_banner and !empty($advertisingBanners2) and count($advertisingBanners2))
            <div class="home-sections container">
                <div class="row">
                    @foreach($advertisingBanners2 as $banner2)
                        <div class="col-{{ $banner2->size }}">
                            <a href="{{ $banner2->link }}">
                                <img src="{{ config('app.img_dynamic_url') }}{{ $banner2->image }}" class="img-cover rounded-sm" alt="{{ $banner2->title }}">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        {{-- ./ Ads Bannaer --}}

        @if($homeSection->name == \App\Models\HomeSection::$organizations and !empty($organizations) and !$organizations->isEmpty())
            <section class="d-none home-sections home-sections-swiper container homehide">
                <div class="d-flex justify-content-between">
                    <div>
                        <h2 class="section-title">Organizations</h2>
                        <p class="section-hint">Greatest organizations are here to help you</p>
                          <h2 class="section-title">{{ trans('home.organizations') }}</h2>
                        <p class="section-hint">{{ trans('home.organizations_hint') }}</p>
                    </div>
 <a href="/organizations" class="btn btn-border-white">{{ trans('home.all_organizations') }}</a>
                </div>

                <div class="position-relative mt-20">
                    <div class=" px-12">
                        <div class="swiper-wrapper py-20">

                            @foreach($organizations as $organization)
                                <div class="swiper-slide">
                                    <div class="home-organizations-card d-flex flex-column align-items-center justify-content-center">
                                        <div class="home-organizations-avatar">
                                            <img src="{{ config('app.img_dynamic_url') }}{{ $organization->getAvatar(120) }}" class="img-cover rounded-circle" alt="{{ $organization->full_name }}">
                                        </div>
                                        <a href="{{ $organization->getProfileUrl() }}" class="mt-25 d-flex flex-column align-items-center justify-content-center">
                                            <h3 class="home-organizations-title">{{ $organization->full_name }}</h3>
                                            <p class="home-organizations-desc mt-10">{{ $organization->bio }}</p>
                                            <span class="home-organizations-badge badge mt-15">{{ $organization->webinars_count }} {{ trans('panel.classes') }}</span>
                                            <span class="home-organizations-badge badge mt-15">0 Courses</span>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                            
                        </div>
                    </div>

                </div>
            </section>
        @endif
        
        
       
        @if($homeSection->name == \App\Models\HomeSection::$remedies and !empty($remedies) and !$remedies->isEmpty())
            <section class="d-none home-sections  home-sections-swiper container Remedies">
                <div class="d-flex justify-content-between">
                    <div>
                        <h2 class="section-title">Remedies</h2>
                        <p class="section-hint">Greatest Remedies are here to help you</p>
                    </div>
                    <a href="/remedies?sort=newest" class="btn btn-border-white">All Remedies</a>  
                </div>

                <div class="position-relative mt-20">
                    <div class="swiper-container organization-swiper-container px-12">
                        <div class="swiper-wrapper py-20">

                            @foreach($remedies as $remedy)
                            <div class="swiper-slide">
                                    <!--<div class="home-organizations-card d-flex flex-column align-items-center justify-content-center">-->
                                        <div class="">
                                    @include('web.default.includes.remedy.grid-card',['remedy' => $remedy]  )
                                    </div>
                                        
                                </div>
                                
                            @endforeach
                        </div>
                    </div>

                    <div class="d-flex justify-content-center">
                        <div class="swiper-pagination organization-swiper-pagination"></div>
                    </div>
                </div>
            </section>
            <section class="home-sections  hide-mobile">
                <div class="d-flex justify-content-between">
                    <div class="">
                        <h2 class="section-title">Remedies</h2>
                    </div>

                <a href="/remedies?sort=newest" class="btn btn-border-white mobile-btn">{{ trans('home.view_all') }}<img width="20px" class="ml-5" src="/assets/default/mobile/right-arrow.png" alt="Right Arrow Icon - Asttrolok"></a>
                </div>

                {{-- <div class="scroll-bar1"> --}}
                <div>
                @foreach($remedies as $remedy)
                
                        <div class="col-12 col-md-6 col-lg-4 px-0 loadid">
                            @include('web.default.includes.remedy.list-card1',['remedy' => $remedy]  )
                        </div>
            @endforeach
                </div>
            </section>
        @endif
        

        @if($homeSection->name == \App\Models\HomeSection::$blog and !empty($blog) and !$blog->isEmpty())
        <section class="home-sections  home-sections-swiper ">
                <div class="d-flex justify-content-between">
                    <div>
                        <h2 class="section-title">Coverage of Media Presence</h2>
                        <!--<p class="section-hint">#Astrolok's presence in news logs</p>-->
                        <!--  <h2 class="section-title">{{ trans('home.organizations') }}</h2>-->
                        <!--<p class="section-hint">{{ trans('home.organizations_hint') }}</p>-->
                    </div>
 <!--<a href="/organizations" class="btn btn-border-white">{{ trans('home.all_organizations') }}</a>-->
                    <!--<a href="/remedies?sort=newest" class="btn btn-border-white">All Remedies</a>  -->
                </div>

                <div class="position-relative mt-20">
                    <div class="swiper-container news-swiper-container px-12">
                        <div class="swiper-wrapper py-20">
                            <div class="swiper-slide">
                                        <div class="">
                                       <div class="webinar-card">
                                            <figure>
                                                <div class="image-box" style="    height: 95px !important;">
                                                    <a href="https://zeenews.india.com/lifestyle/homeandkitchen/vastu-tips-for-holi-2024-7-things-you-must-avoid-to-let-go-of-negative-energy-from-home-2733462.html" target="_blank">
                                                        <img src="{{ config('app.js_css_url') }}/assets/default/img/home/news/Zee_news.svg.png" class="img-cover" style="object-fit: contain; padding: 5px;"alt="img">
                                                    </a>
                                                </div>
                                                <figcaption class="webinar-card-body news">
                                                    <a href="https://zeenews.india.com/lifestyle/homeandkitchen/vastu-tips-for-holi-2024-7-things-you-must-avoid-to-let-go-of-negative-energy-from-home-2733462.html" target="_blank">
                                                        <h3 class="mt-5 font-weight-bold font-16 text-white news-text">Zee News</h3>
                                                    </a>
                                                </figcaption>
                                            </figure>
                                        </div>
                                    </div>
                                </div>
                            <div class="swiper-slide">
                                        <div class="">
                                       <div class="webinar-card">
                                            <figure>
                                                <div class="image-box" style="    height: 95px !important;">
                                                    <a href="https://news.abplive.com/astro/vedic-science-vastu-shastra-in-improving-health-and-wellness-1680193" target="_blank">
                                                        <img src="{{ config('app.js_css_url') }}/assets/default/img/home/news/abp News-min.png" class="img-cover" style="object-fit: contain; padding: 5px;"alt="img">
                                                    </a>
                                                </div>
                                                <figcaption class="webinar-card-body news">
                                                    <a href="https://news.abplive.com/astro/vedic-science-vastu-shastra-in-improving-health-and-wellness-1680193" target="_blank">
                                                        <h3 class="mt-5 font-weight-bold font-16 text-white news-text">ABP News</h3>
                                                    </a>
                                                </figcaption>
                                            </figure>
                                        </div>
                                    </div>
                                </div>
                            <div class="swiper-slide">
                                        <div class="">
                                       <div class="webinar-card">
                                            <figure>
                                                <div class="image-box" style="    height: 95px !important;">
                                                    <a href="https://timesofindia.indiatimes.com/astrology/vastu-feng-shui/from-enhancing-natural-light-to-creating-a-sacred-space-vastu-remedies-for-enhancing-positive-personality-traits/articleshow/109692065.cms" target="_blank">
                                                        <img src="{{ config('app.js_css_url') }}/assets/default/img/home/news/TOI-min.png" class="img-cover" style="object-fit: contain; padding: 5px;"alt="img">
                                                    </a>
                                                </div>
                                                <figcaption class="webinar-card-body news">
                                                    <a href="https://timesofindia.indiatimes.com/astrology/vastu-feng-shui/from-enhancing-natural-light-to-creating-a-sacred-space-vastu-remedies-for-enhancing-positive-personality-traits/articleshow/109692065.cms" target="_blank">
                                                        <h3 class="mt-5 font-weight-bold font-16 text-white news-text">TOI</h3>
                                                    </a>
                                                </figcaption>
                                            </figure>
                                        </div>
                                    </div>
                                </div>
                            <div class="swiper-slide">
                                        <div class="">
                                       <div class="webinar-card">
                                            <figure>
                                                <div class="image-box" style="    height: 95px !important;">
                                                    <a href="https://www.moneycontrol.com/news/technology/what-the-stars-foretell-lok-sabha-elections-are-boom-time-for-astrologers-12706862.html" target="_blank">
                                                        <img src="{{ config('app.js_css_url') }}/assets/default/img/home/news/Money Control-min.png" class="img-cover" style="object-fit: contain; padding: 5px;"alt="img">
                                                    </a>
                                                </div>
                                                <figcaption class="webinar-card-body news">
                                                    <a href="https://www.moneycontrol.com/news/technology/what-the-stars-foretell-lok-sabha-elections-are-boom-time-for-astrologers-12706862.html" target="_blank">
                                                        <h3 class="mt-5 font-weight-bold font-16 text-white news-text">Moneycontrol</h3>
                                                    </a>
                                                </figcaption>
                                            </figure>
                                        </div>
                                    </div>
                                </div>
                            <div class="swiper-slide">
                                        <div class="">
                                       <div class="webinar-card">
                                            <figure>
                                                <div class="image-box" style="    height: 95px !important;">
                                                    <a href="https://sugermint.com/alok-khandelwal/" target="_blank">
                                                        <img src="{{ config('app.js_css_url') }}/assets/default/img/home/news/sugar-mint.png" class="img-cover" style="object-fit: contain; padding: 5px;"alt="img">
                                                    </a>
                                                </div>
                                                <figcaption class="webinar-card-body news">
                                                    <a href="https://sugermint.com/alok-khandelwal/" target="_blank">
                                                        <h3 class="mt-5 font-weight-bold font-16 text-white news-text">Sugarmint</h3>
                                                    </a>
                                                </figcaption>
                                            </figure>
                                        </div>
                                    </div>
                                </div>
                            
                              
                        </div>
                    </div>

                    <!--<div class="d-flex justify-content-center">-->
                    <!--    <div class="swiper-pagination organization-swiper-pagination"></div>-->
                    <!--</div>-->
                </div>
            </section> 
            
            <section class="home-sections container homehide">
                <style>
                    .blog-grid-card{
    min-height:340px !important;
}
.blog-grid-image  {
  height: 127.66px !important;
}
                </style>
                <div class="d-flex justify-content-between">
                    <div>
                        <h2 class="section-title">{{ trans('home.blog') }}</h2>
                        <p class="section-hint">{{ trans('home.blog_hint') }}</p>
                    </div>

                    <a href="/blog" class="btn btn-border-white">{{ trans('home.all_blog') }}</a>
                </div>

                <div class="row mt-35">

                    @foreach($blog as $post)
                        <div class="col-12 col-md-3 col-lg-3 mt-20 mt-lg-0">
                            @include('web.default.blog.grid-list',['post' =>$post]) 
                        </div>
                    @endforeach

                </div>
                
            </section>
            <section class="home-sections  hide-mobile">
                <div class="d-flex justify-content-between">
                    <div class="">
                        <h2 class="section-title">{{ trans('home.blog') }}</h2>
                    </div>

                <!--<a href="/remedies?sort=newest" class="btn btn-border-white mobile-btn">{{ trans('home.view_all') }}<img width="20px" class="ml-5" src="/assets/default/mobile/right-arrow.png" alt="Right Arrow Icon - Asttrolok"></a>-->
                </div>

                {{-- <div class="scroll-bar1"> --}}
                <div>
                @foreach($blog as $post)
                
                        <div class="col-12 col-md-6 col-lg-4 px-0 loadid">
                            @include('web.default.blog.list-card1',['post' => $post]  )
                        </div>
            @endforeach
                </div>
                <center> <a href="/blog" class="mt-20 btn btn-border-white mobile-btn" style="margin-left: 0px !important; font-size:16px !important; border: 2px solid #7e7e7e !important;">{{ trans('home.view_all') }}
                <!--<img width="20px" class="ml-5" src="/assets/default/mobile/right-arrow.png" alt="Right Arrow Icon - Asttrolok">-->
                </a></center>
            </section>
            
        @endif
        
       
        
        @if($count_homesection==2)
    </div>
    <div class="col-12 col-lg-4 mt-25 mt-lg-0 homehide">
        <div class="d-flex justify-content-between mt-15">
            <div>
                <!--<h2 class="section-title">Get Your Kundli by Date of Birth</h2>-->
                 </div>

             </div>
             <!--<div class="rounded-lg sidebar-ads1 mt-40">-->
             <!--   <a href="">-->
             <!--       <img src="store\1\Home\Revised-2-min.gif" class="shadow-sm  shadow-sm mt-40 adds rounded-lg" alt="Reserve a meeting - Course page">-->
             <!--       </a>-->
             <!--</div>-->
             <!--<div class="rounded-lg sidebar-ads1 mt-40">-->
             <!--   <a href="{{$sidebanner['home2']['link']}}">-->
             <!--       <img src="{{ config('app.img_dynamic_url') }}{{$sidebanner['home2']['image']}}" class="shadow-sm  shadow-sm mt-40 adds rounded-lg" alt="Reserve a meeting - Course page">-->
             <!--       </a>-->
             <!--</div>-->
       
        
    </div> </div> </section>
    @endif
     @if($count_homesection==3)
    
    
        </div>
         <div class="col-12 col-lg-4 mt-25 mt-lg-0 ">
        <!--<div class="rounded-lg sidebar-ads1 mt-15">-->
        <!--    <a href="/instructors">-->
        <!--    <img src="store\1\Home\Consultation(1).jpg" class="w-100  shadow-sm rounded-lg" alt="Reserve a meeting - Course page">-->
        <!--    </a>-->
        <!-- </div>-->
        
        <!--<div class="rounded-lg sidebar-ads1 mt-15">-->
        <!--    <a href="{{$sidebanner['home1']['link']}}">-->
        <!--    <img src="{{ config('app.img_dynamic_url') }}{{$sidebanner['home1']['image']}}" class="w-100  shadow-sm rounded-lg" alt="Reserve a meeting - Course page">-->
            
        <!--    </a>-->
        <!-- </div>-->
       
         <div class="deckteacher teacher-swiper-container position-relative d-flex justify-content-center mt-0">
            <div class="swiper-container teacher-swiper-container pb-25">
               <div class="swiper-wrapper py-0">
               {{--    @foreach($consultant as $instructor)
                   <div class="swiper-slide">
                    <div class="rounded-lg shadow-sm mt-15  p-5 course-teacher-card d-flex align-items-center flex-column">
                       <div class="teacher-avatar mt-15">
                          <img src="{{ config('app.img_dynamic_url') }}{{ $instructor->getAvatar(108) }}" class="img-cover" alt="{{ $instructor->full_name }}">
                          <span class="user-circle-badge has-verified d-flex align-items-center justify-content-center">
                             <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check text-white">
                                <polyline points="20 6 9 17 4 12"></polyline>
                             </svg>
                          </span>
                       </div>
                       <h3 class="mt-10 font-16 font-weight-bold text-secondary text-center swiper-container1-title">{{ $instructor->full_name }}</h3>
                       <span class="mt-5 font-14 font-weight-500 text-gray text-center swiper-container1-desc">{{ $instructor->bio }}</span>
                       <div class="stars-card d-none align-items-center  mt-15">
                                                @php
                                                    $i = 5;
                                                @endphp
                                                @while(--$i >= 5 - $instructor->rating)
                                                    <i data-feather="star" width="13" height="13" class="active"></i>
                                                @endwhile
                                                @while($i-- >= 0)
                                                    <i data-feather="star" width="13" height="13" class=""></i>
                                                @endwhile
                           </div>
                       <div class="my-15   align-items-center text-center  w-100">
                           <a href="{{ $instructor->getProfileUrl() }}" class="btn btn-sm btn-primary swiper-container1-btn">Book a Consultantion</a>
                       </div>
                    </div>
                 </div>
                 @endforeach  --}}
                   
                 
                 
                 
               </div>
            </div>
            <!--<div class="swiper-pagination teacher-swiper-pagination ast-pagination"></div>-->
         </div>
       
         
    </div>
 </section>
    @endif
    @endforeach
    <div class="modal fade" id="import" tabindex="-1" aria-labelledby="import" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered">
            
            <div class="modal-content py-20" id="videolink">
                {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i data-feather="x" width="25" height="25" style="float: right;"></i>
                </button> --}}
                {{-- <img src="/store/1/maxresdefault.jpg" class="w-100  shadow-sm rounded-lg" alt="Reserve a meeting - Course page"> --}}
                <iframe width="-webkit-fill-available" id="videoiframe" height="300" allow="autoplay"  title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            </div>
        </div>
    </div>
@endsection

@push('scripts_bottom')
 
    <script src="{{ config('app.js_css_url') }}/assets/default/vendors/swiper/swiper-bundle.min.js"></script>
    <script src="{{ config('app.js_css_url') }}/assets/default/vendors/owl-carousel2/owl.carousel.min.js"></script>
    <script src="{{ config('app.js_css_url') }}/assets/default/vendors/parallax/parallax.min.js"></script>
    <script src="{{ config('app.js_css_url') }}/assets/default/js/parts/home.min.js"></script>
     <script src="{{ config('app.js_css_url') }}/assets/default/js/parts/webinar_show.min.js"></script>
    <script>
       function featurjquery(urls){
            window.location.href = urls;
        }
    </script>
    <script>
    
    function changeImageAndShowPopup(data) {
    console.log('data',data);
      var imageElement = document.getElementById('videoiframe');
      imageElement.src = data; 
      document.getElementById('popup').style.display = 'block';
    }
    function closePopup() {
      // Hide the popup
      document.getElementById('popup').style.display = 'none';
    }
  </script>
@endpush


