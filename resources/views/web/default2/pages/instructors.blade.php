@extends('web.default2'.'.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="{{ config('app.js_css_url') }}/assets2/default/vendors/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="{{ config('app.js_css_url') }}/assets2/default/vendors/select2/select2.min.css">
     <link rel="canonical" href="https://www.asttrolok.com/consult-with-astrologers" />
     
     <!-- Event snippet for Booking Button conversion page -->
    <script>
      gtag('event', 'conversion', {'send_to': 'AW-795191608/yJCeCNrvt5cZELjSlvsC'});
    </script>
    <style>
    .course-teacher-card.instructors-list .off-label1 {
    position: absolute;
    top: 7px;
    right: 7px;
    border-radius: 15px 15px 15px 15px !important;
    z-index: 10;
}
.course-teacher-card.instructors-list .off-label {
    position: absolute;
    top: 7px;
    left: 7px;
    border-radius: 15px 15px 15px 15px !important;
    z-index: 10;
}
.loadid {
    display:none;
   
}
.loadid.display {
	display: inline-block;
}
</style>
@endpush


@section('content')
    <!--<section class="mob-ban site-top-banner search-top-banner opacity-04 position-relative">-->
    <section class="cart-banner mob-ban search-top-banner opacity-04 position-relative">
        <!--<img src="https://storage.googleapis.com/astrolok/store/1/default_images/banners/02-min.jpg" class="img-cover" alt="{{ $title }}"/>-->

        <div class="container h-100">
            <div class="row h-100 align-items-center justify-content-center text-center">
                <div class="col-12 col-md-9 col-lg-7">
                    <div class="top-search-categories-form">
                        <h1 class="text-white font-30 mb-15">{{ $title }} </h1>
                        <!--<span class="course-count-badge py-5 px-10 text-white rounded">{{ $instructorsCount }} {{ $title }}</span>-->

                        <!--<div class="search-input bg-white p-10 flex-grow-1">-->
                        <!--    <form action="/{{ $page }}" method="get">-->
                        <!--        <div class="form-group d-flex align-items-center m-0">-->
                        <!--            <input type="text" name="search" class="form-control border-0" value="{{ request()->get('search') }}" placeholder="{{ trans('public.search') }} {{ $title }}"/>-->
                        <!--            <button type="submit" class="btn btn-primary rounded-pill">{{ trans('home.find') }}</button>-->
                        <!--        </div>-->
                        <!--    </form>-->
                        <!--</div>-->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="mob-cat container">

        <!--<form id="filtersForm" class="consult-filter" action="/{{ $page }}" method="get">-->

        <!--    <div id="topFilters" class="mt-25 shadow-lg border border-gray300 rounded-sm p-10 p-md-20">-->
        <!--        <div class="row align-items-center">-->
        <!--            <div class="col-lg-9 d-block d-md-flex align-items-center justify-content-start my-25 my-lg-0">-->
        <!--                <div class="d-flex align-items-center justify-content-between justify-content-md-center">-->
        <!--                    <label class="mb-0 mr-10 cursor-pointer" for="available_for_meetings">{{ trans('public.available_for_meetings') }}</label>-->
        <!--                    <div class="custom-control custom-switch">-->
        <!--                        <input type="checkbox" name="available_for_meetings" class="custom-control-input" id="available_for_meetings" @if(request()->get('available_for_meetings',null) == 'on') checked="checked" @endif>-->
        <!--                        <label class="custom-control-label" for="available_for_meetings"></label>-->
        <!--                    </div>-->
        <!--                </div>-->

        <!--                <div class="d-flex align-items-center justify-content-between justify-content-md-center mx-0 mx-md-20 my-20 my-md-0">-->
        <!--                    <label class="mb-0 mr-10 cursor-pointer" for="free_meetings">{{ trans('public.free_meetings') }}</label>-->
        <!--                    <div class="custom-control custom-switch">-->
        <!--                        <input type="checkbox" name="free_meetings" class="custom-control-input" id="free_meetings" @if(request()->get('free_meetings',null) == 'on') checked="checked" @endif>-->
        <!--                        <label class="custom-control-label" for="free_meetings"></label>-->
        <!--                    </div>-->
        <!--                </div>-->

        <!--                <div class="d-flex align-items-center justify-content-between justify-content-md-center">-->
        <!--                    <label class="mb-0 mr-10 cursor-pointer" for="discount">{{ trans('public.discount') }}</label>-->
        <!--                    <div class="custom-control custom-switch">-->
        <!--                        <input type="checkbox" name="discount" class="custom-control-input" id="discount" @if(request()->get('discount',null) == 'on') checked="checked" @endif>-->
        <!--                        <label class="custom-control-label" for="discount"></label>-->
        <!--                    </div>-->
        <!--                </div>-->

        <!--            </div>-->

        <!--            <div class="col-lg-3 d-flex align-items-center">-->
        <!--                <select name="sort" class="form-control">-->
        <!--                    <option disabled selected>{{ trans('public.sort_by') }}</option>-->
        <!--                    <option value="">{{ trans('public.all') }}</option>-->
        <!--                    <option value="top_rate" @if(request()->get('sort',null) == 'top_rate') selected="selected" @endif>{{ trans('site.top_rate') }}</option>-->
        <!--                    <option value="top_sale" @if(request()->get('sort',null) == 'top_sale') selected="selected" @endif>{{ trans('site.top_sellers') }}</option>-->
        <!--                </select>-->
        <!--            </div>-->

        <!--        </div>-->
        <!--    </div>-->

        <!--    <div class="mt-30 px-20 py-15 rounded-sm shadow-lg border border-gray300">-->
        <!--        <h3 class="category-filter-title font-20 font-weight-bold">{{ trans('categories.categories') }}</h3>-->

        <!--        <div class="p-10 mt-20 d-flex  flex-wrap">-->

        <!--            @foreach($categories as $category)-->
        <!--                @if(!empty($category->subCategories) and count($category->subCategories))-->
        <!--                    @foreach($category->subCategories as $subCategory)-->
        <!--                        <div class="checkbox-button bordered-200 mt-5 mr-15">-->
        <!--                            <input type="checkbox" name="categories[]" id="checkbox{{ $subCategory->id }}" value="{{ $subCategory->id }}" @if(in_array($subCategory->id,request()->get('categories',[]))) checked="checked" @endif>-->
        <!--                            <label for="checkbox{{ $subCategory->id }}">{{ $subCategory->title }}</label>-->
        <!--                        </div>-->
        <!--                    @endforeach-->
        <!--                @else-->
        <!--                    <div class="checkbox-button bordered-200 mr-20">-->
        <!--                        <input type="checkbox" name="categories[]" id="checkbox{{ $category->id }}" value="{{ $category->id }}" @if(in_array($category->id,request()->get('categories',[]))) checked="checked" @endif>-->
        <!--                        <label for="checkbox{{ $category->id }}">{{ $category->title }}</label>-->
        <!--                    </div>-->
        <!--                @endif-->
        <!--            @endforeach-->

        <!--        </div>-->
        <!--    </div>-->
        <!--</form>-->
        
        <div class="mt-35">
            <div class="mob-tab">
                        <ul class=" nav nav-tabs bg-secondary rounded-sm p-15 d-flex align-items-center justify-content-between" id="tabs-tab" role="tablist">
                            <li class="nav-item" style="margin: auto;">
                                <a class="position-relative font-14 text-white {{ (empty(request()->get('tab','')) or request()->get('tab','') == 'reviews') ? 'active' : '' }}" id="reviews-tab" data-toggle="tab"
                                   href="#reviews" role="tab" aria-controls="reviews"
                                   aria-selected="false">Astrologers</a>
                            </li>
                            <li class="nav-item" style="margin: auto;">
                                <a  class="position-relative font-14 text-white  {{ (request()->get('tab','') == 'content') ? 'active' : '' }}" id="content-tab" data-toggle="tab"
                                   href="#content" role="tab" aria-controls="content"
                                   aria-selected="false">Instructors</a>
                            </li>
                           
                           
                        </ul>
            </div>
    <section>
        @php
                                  
            $to_day=date("l");
        @endphp
        
            <div id="instructorsList" class=" mt-20">
                
                <div class="tab-content " id="nav-tabContent">
                    <div class="tab-pane fade  {{ (empty(request()->get('tab','')) or request()->get('tab','') == 'reviews') ? 'show active' : '' }}" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                        <div class="row">
                            @foreach($consult as $instructor2)
                                @php
                                    $canReserve2 = false;
                                    if(!empty($instructor2->meeting) and !$instructor2->meeting->disabled and !empty($instructor2->meeting->meetingTimes) and $instructor2->meeting->meeting_times_count > 0) {
                                        $canReserve2 = true;
                                    }
                                    
                                     if($canReserve2){
                                     
                                @endphp
                                @if($instructor2->consultant == 1)
                                        <!--<div class="col-12 col-md-6 col-lg-4 loadid">-->
                                        <div class="col-12 col-md-6 col-lg-4 ">
                                            @include('web.default2.pages.instructor_card1',['instructor' => $instructor2])
                                        </div>
                                        @endif
                                    @php
                                        }
                                    @endphp
                            @endforeach
                            
                            @foreach($consult as $instructor1)
                                @php
                                    $canReserve1 = false;
                                    if(!empty($instructor1->meeting) and !$instructor1->meeting->disabled and !empty($instructor1->meeting->meetingTimes) and $instructor1->meeting->meeting_times_count > 0) {
                                        $canReserve1 = true;
                                    }
                                    if(!$canReserve1){
                                @endphp
                                    @if($instructor1->consultant == 1)
                                    <div class="col-12 col-md-6 col-lg-4 ">
                                        @include('web.default2.pages.instructor_card1',['instructor' => $instructor1])
                                    </div>
                                    @endif
                                     @php
                                     }
                                    @endphp
                            @endforeach
                                </div>
            <!--                    <div style="display: flex;align-items: center;justify-content: center;" class="mt-30 ">-->
            <!--      <a  id="loadMore" class="btn btn-border-white mb-2" >View More</a>-->
            <!--</div> -->
            <br><br>
            <div class="px-20 px-md-0">
        <h3 class="section-title">Why stress
over your concerns when the solution is just a Book away?</h3>
<br>
        <p class="section-hint">Asttrolok connects
you with a diverse team of 100+ astrologers ready to address your problems
through online consultation. Whether it's matters of love, finance, Vastu,
career, luck, marriage, or more, life's journey is a mix of highs and lows.
While we relish the good times, challenges can leave us feeling anxious and
disheartened, affecting our relationships.<br><br>Asttrolok's exceptional astrology consultant services are
designed to provide solutions to the challenges you face in various aspects of
life. Some issues are influenced by cosmic factors determined at birth, such as
specific dashas like Shani Dasha or Rahu Dasha, which can lead to confidence
loss, financial troubles, and relationship woes. Asttrolok offers solutions to
navigate these challenges with the help of experienced astrologers.

<br><br>
Our team consists of knowledgeable astrologers specializing in
Vedic astrology, Numerology, Vastu, and more. Connect with these experts
through online chat to seek guidance and solutions tailored to your unique
situation.
<br>
<br>
Astrology extends beyond problem-solving—it assists in various
life events. Planning a wedding and need an auspicious muhurat? Consult with an
astrologer. Naming your baby or deciding on the right time for their mundan
ceremony? Expert astrologers can guide you. Curious about which gemstone suits
your rashi? An astrologer's advice can help you choose the perfect one.
<br>

Asttrolok offers paid online chat services with astrologers.
Simply search for "online consultation. Our goal is to ensure 100%,
offering services like kundali and match making. Embrace the power of astrology
to navigate life's journey with confidence and clarity.</p>
    </div>
                            </div>
                    
                            <div class=" tab-pane fade  {{ (request()->get('tab','') == 'content') ? 'show active' : '' }}" id="content" role="tabpanel" aria-labelledby="content-tab">
                                <div class="row">
                                    @foreach($instructors as $instructor)
                                        @if($instructor->consultant == 0)
                                            <div class="col-12 col-md-6 col-lg-4 ">
                                                @include('web.default2.pages.instructor_card',['instructor' => $instructor])
                                            </div>
                                        @endif
                                    @endforeach
                                    
                                    
                            </div></div>
                            
                            
                           
                            
                             
                </div>

                

            </div>

           
        </section>
                       

                    </div>

        


        @if(1==2 and !empty($bestRateInstructors) and !$bestRateInstructors->isEmpty() and (empty(request()->get('sort')) or !in_array(request()->get('sort'),['top_rate','top_sale'])))
            <section class="mt-30 pt-30">
                <div class="d-flex justify-content-between">
                    <div>
                        <h2 class="font-24 text-dark-blue">{{ trans('site.best_rated_instructors') }}</h2>
                        <span class="font-14 text-gray">{{ trans('site.best_rated_instructors_subtitle') }}</span>
                    </div>

                    <a href="/{{ $page }}?sort=top_rate" class="btn btn-border-white">{{ trans('home.view_all') }}</a>
                </div>

                <div class="position-relative mt-20">
                    <div id="bestRateInstructorsSwiper" class="swiper-container px-12">
                        <div class="swiper-wrapper pb-20">

                            @foreach($bestRateInstructors as $bestRateInstructor)
                                <div class="swiper-slide">
                                    @include('web.default2.pages.instructor_card',['instructor' => $bestRateInstructor])
                                </div>
                            @endforeach

                        </div>
                    </div>

                    <div class="d-flex justify-content-center">
                        <div class="swiper-pagination best-rate-swiper-pagination"></div>
                    </div>
                </div>

            </section>
        @endif

        @if(1==2 and !empty($bestSalesInstructors) and !$bestSalesInstructors->isEmpty() and (empty(request()->get('sort')) or !in_array(request()->get('sort'),['top_rate','top_sale'])))
            <section class="mt-50 pt-50">
                <div class="d-flex justify-content-between">
                    <div>
                        <h2 class="font-24 text-dark-blue">{{ trans('site.top_sellers') }}</h2>
                        <span class="font-14 text-gray">{{ trans('site.top_sellers_subtitle') }}</span>
                    </div>

                    <a href="/{{ $page }}?sort=top_sale" class="btn btn-border-white">{{ trans('home.view_all') }}</a>
                </div>

                <div class="position-relative mt-20">
                    <div id="topSaleInstructorsSwiper" class="swiper-container px-12">
                        <div class="swiper-wrapper pb-20">

                            @foreach($bestSalesInstructors as $bestSalesInstructor)
                                <div class="swiper-slide">
                                    @include('web.default2.pages.instructor_card',['instructor' => $bestSalesInstructor])
                                </div>
                            @endforeach

                        </div>
                    </div>

                    <div class="d-flex justify-content-center">
                        <div class="swiper-pagination best-sale-swiper-pagination"></div>
                    </div>
                </div>

            </section>
        @endif
    </div>

@endsection

@push('scripts_bottom')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script >
//   $(document).ready(function(){
//   $(".loadid").slice(0,10).show();
//   $("#loadMore").click(function(e){
//     e.preventDefault();
//     $(".loadid:hidden").slice(0,10).fadeIn("slow");
    
//     if($(".loadid:hidden").length == 0){
//       $("#loadMore").fadeOut("slow");
       
//       }
//   });
// })
</script>

    <script src="{{ config('app.js_css_url') }}/assets2/default/vendors/select2/select2.min.js"></script>
    <script src="{{ config('app.js_css_url') }}/assets2/default/vendors/swiper/swiper-bundle.min.js"></script>

    <script src="{{ config('app.js_css_url') }}/assets2/default/js/parts/instructors.min.js"></script>
@endpush
