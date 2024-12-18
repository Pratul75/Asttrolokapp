@extends(getTemplate().'.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="{{ config('app.js_css_url') }}/assets/default/vendors/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="{{ config('app.js_css_url') }}/assets/default/vendors/select2/select2.min.css">
    <link rel="canonical" href="https://www.asttrolok.com/classes" />
      <!--<link rel="stylesheet" href="{{ config('app.js_css_url') }}/assets/default/css/mobile-remedies.css">-->
   
   
    <link rel="stylesheet" href="{{ config('app.js_css_url') }}/assets/default/css/mobile-courses.css">
     <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
<style>
    .bg-secondary1 {
    background-color: #f6f5f5 !important;
}
.nav-tabs {
    border-bottom: 1px solid #ececec !important;
}
.rounded-sm1 {
    border-radius: 0.625rem !important;
}
.nav-tabs .nav-item a.active,.nav-tabs .nav-item a:hover {
    background-color: white !important;
    border: none !important;
    padding: 7px;
    border-radius: 6px;
    font-weight: 600;
    color: #32ba7c;
    padding-left:50px;
    padding-right:50px;
}
</style>
@endpush

@section('content')
    <section class="cart-banner mobile-home-slider search-top-banner opacity-04 position-relative">
    <!--<section class="mobile-home-slider site-top-banner search-top-banner opacity-04 position-relative">-->
    <!--    <img src="{{ config('app.img_dynamic_url') }}{{ getPageBackgroundSettings('categories') }}" class="banner-redius img-cover" alt="{{ $pageTitle }} "/>-->
        

        <div class="container">
            <div class="row h-100 align-items-center justify-content-center text-center">
                <div class="col-12 col-md-9 col-lg-7">
                    <div class="top-search-categories-form">
                        <h1 class="text-white font-30 mb-15">Courses</h1>
                        <!--<h1 class="text-white font-30 mb-15">{{ $pageTitle }} </h1>-->
                        <!--<span class="course-count-badge py-5 px-10 text-white rounded">{{ $coursesCount }} {{ trans('product.courses') }}</span>-->

                        <!--<div class="search-input bg-white p-10 flex-grow-1">-->
                        <!--    <form action="/search" method="get">-->
                        <!--        <div class="form-group d-flex align-items-center m-0">-->
                        <!--            <input type="text" name="search" class="form-control border-0" placeholder="{{ trans('home.slider_search_placeholder') }}"/>-->
                        <!--            <button type="submit" class="btn btn-primary rounded-pill">{{ trans('home.find') }}</button>-->
                        <!--        </div>-->
                        <!--    </form>-->
                        <!--</div>-->
                    </div>
                </div>
            </div>
        </div>
    </section>
     
    <div class="container mt-20 ">
        <form id="filtersForm" class="consult-filter" style="display:block;" action="/{{ $page }}" method="get">
        
        @include('web.default.pages.includes.top_filters')
         </form>
         
         <div class="mt-15">
            <div>
                {{-- <div class="mob-tab"> --}}
                        <ul class="col-12 nav nav-tabs p-15 d-flex align-items-center justify-content-between bg-secondary1 rounded-sm1" id="tabs-tab" role="tablist">
                            <li class="col-6 nav-item" style="display: flex;justify-content: center;">
                                <a class="position-relative font-14  {{ (empty(request()->get('tab','')) or request()->get('tab','') == 'reviews') ? 'active' : '' }}" id="reviews-tab" data-toggle="tab"
                                   href="#reviews" role="tab" aria-controls="reviews"
                                   aria-selected="false">Hindi</a>
                            </li>
                            <li class="col-6 nav-item" style="display: flex;justify-content: center;">
                                <a  class="position-relative font-14   {{ (request()->get('tab','') == 'content') ? 'active' : '' }}" id="content-tab" data-toggle="tab"
                                   href="#content" role="tab" aria-controls="content"
                                   aria-selected="false">English</a>
                            </li>
                           
                           
                        </ul>
            </div>
        <section class="mt-lg-50 pt-lg-20 mt-md-40 pt-md-40 mt-20">
            <!--<form action="/classes" method="get" id="filtersForm">-->
              

                <div class="row mt-10">
                    <div class="col-12 col-lg-12">
                        
                            
                                  <div class="tab-content " id="nav-tabContent">
                            <div class="tab-pane fade  {{ (empty(request()->get('tab','')) or request()->get('tab','') == 'reviews') ? 'show active' : '' }}" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                                @if(empty(request()->get('card')) or request()->get('card') == 'grid' )
                                <div class="row">
                                    @foreach($hindi_classes as $hindi_class)
                                <!--<div class="col-md-6 col-lg-4 mt-20 loadid mobilegrid">-->
                                <div class="col-md-6 col-lg-4 mt-20  mobilegrid">
                                    @include('web.default.includes.webinar.grid-card',['webinar' => $hindi_class])
                                </div>
                            @endforeach
                                    
                                    
                            </div>
                            @elseif(!empty(request()->get('card')) and request()->get('card') == 'list')
                            <div class="row">
                                 @foreach($hindi_classes as $webinar)
                                <!--<div class="col-12 col-lg-4 mt-20 loadid1 ">-->
                                <div class="col-12 col-lg-4 mt-20  ">
                                    @include('web.default.includes.webinar.list-card',['webinar' => $webinar])
                                </div>
                                @endforeach
                                 </div>
                            @endif
                            </div>
                            <div class=" tab-pane fade  {{ (request()->get('tab','') == 'content') ? 'show active' : '' }}" id="content" role="tabpanel" aria-labelledby="content-tab">
                                @if(empty(request()->get('card')) or request()->get('card') == 'grid' )
                                <div class="row">
                                    @foreach($englishclasses as $englishclass)
                                    <!--<div class="col-md-6 col-lg-4 mt-20 load-card-list mobilegrid">-->
                                    <div class="col-md-6 col-lg-4 mt-20  mobilegrid">
                                        @include('web.default.includes.webinar.grid-card',['webinar' => $englishclass])
                                    </div>
                                 @endforeach
                                    
                               </div>
                                @elseif(!empty(request()->get('card')) and request()->get('card') == 'list')
                                <div class="row">
                                    @foreach($englishclasses as $english)
                                    <!--<div class="col-12 col-lg-4 mt-20 load-card-list1">-->
                                    <div class="col-12 col-lg-4 mt-20 ">
                                         @include('web.default.includes.webinar.list-card',['webinar' => $english])
                                    </div>
                                 @endforeach
                                    
                               </div>
                                @endif
                            </div>
                            
                            
                            
                            </div>
                     </div>
                     
                    </div>
                        
                 

                    <!--<div class="col-12 col-lg-4 homehide">-->
                    <!--    <div class="mt-20 p-20 rounded-sm shadow-lg border border-gray300 filters-container">-->

                    <!--        <div class="">-->
                    <!--            <h3 class="category-filter-title font-20 font-weight-bold text-dark-blue">{{ trans('public.type') }}</h3>-->

                    <!--            <div class="pt-10">-->
                    <!--                @foreach(['bundle','webinar','course','text_lesson'] as $typeOption)-->
                    <!--                    <div class="d-flex align-items-center justify-content-between mt-20">-->
                    <!--                        <label class="cursor-pointer" for="filterLanguage{{ $typeOption }}">-->
                    <!--                            @if($typeOption == 'bundle')-->
                    <!--                                {{ trans('update.bundle') }}-->
                    <!--                            @else-->
                    <!--                                {{ trans('webinars.'.$typeOption) }}-->
                    <!--                            @endif-->
                    <!--                        </label>-->
                    <!--                        <div class="custom-control custom-checkbox">-->
                    <!--                            <input type="checkbox" name="type[]" id="filterLanguage{{ $typeOption }}" value="{{ $typeOption }}" @if(in_array($typeOption, request()->get('type', []))) checked="checked" @endif class="custom-control-input">-->
                    <!--                            <label class="custom-control-label" for="filterLanguage{{ $typeOption }}"></label>-->
                    <!--                        </div>-->
                    <!--                    </div>-->
                    <!--                @endforeach-->
                    <!--            </div>-->
                    <!--        </div>-->

                    <!--        <div class="mt-25 pt-25 border-top border-gray300">-->
                    <!--            <h3 class="category-filter-title font-20 font-weight-bold text-dark-blue">{{ trans('site.more_options') }}</h3>-->

                    <!--            <div class="pt-10">-->
                    <!--                @foreach(['subscribe','certificate_included','with_quiz','featured'] as $moreOption)-->
                    <!--                    <div class="d-flex align-items-center justify-content-between mt-20">-->
                    <!--                        <label class="cursor-pointer" for="filterLanguage{{ $moreOption }}">{{ trans('webinars.show_only_'.$moreOption) }}</label>-->
                    <!--                        <div class="custom-control custom-checkbox">-->
                    <!--                            <input type="checkbox" name="moreOptions[]" id="filterLanguage{{ $moreOption }}" value="{{ $moreOption }}" @if(in_array($moreOption, request()->get('moreOptions', []))) checked="checked" @endif class="custom-control-input">-->
                    <!--                            <label class="custom-control-label" for="filterLanguage{{ $moreOption }}"></label>-->
                    <!--                        </div>-->
                    <!--                    </div>-->
                    <!--                @endforeach-->
                    <!--            </div>-->
                    <!--        </div>-->


                    <!--        <button type="submit" class="btn btn-sm btn-primary btn-block mt-30">{{ trans('site.filter_items') }}</button>-->
                    <!--    </div>-->
                    <!--</div>-->
                </div>

            <!--</form>-->
         {{--   @if (request()->get('card') == 'grid')
            <div style="display: flex;align-items: center;justify-content: center; " class="mt-30 ">
                <a  id="loadMore" class="btn btn-border-white mb-2" style="font-size: 0.8rem !important; height:30px !important;">View More</a>
          </div>
          <div style="display: flex;align-items: center;justify-content: center; " class="mt-30 ">
                <a  id="loadMore1" class="btn btn-border-white mb-2" style="font-size: 0.8rem !important; height:30px !important;">View More</a>
          </div>
          @elseif (request()->get('card') == 'list')
           
            <div style="display: flex;align-items: center;justify-content: center;" class="mt-30 ">
                <a  id="listloadMore" class="btn btn-border-white mb-2" style="font-size: 0.8rem !important; height:30px !important;">View More</a>
          </div>
           <div style="display: flex;align-items: center;justify-content: center;" class="mt-30 ">
                <a  id="listloadMore1" class="btn btn-border-white mb-2" style="font-size: 0.8rem !important; height:30px !important;">View More</a>
          </div>
           @else
           <div style="display: flex;align-items: center;justify-content: center; " class="mt-30 ">
                <a  id="loadMore" class="btn btn-border-white mb-2" style="font-size: 0.8rem !important; height:30px !important;">View More</a>
          </div>
          @endif --}}
            <!--<div class="mt-50 pt-30">-->
            {{--    {{ $webinars->appends(request()->input())->links('vendor.pagination.panel') }}--}}
            <!--</div>-->
        </section>
    </div>
    
    <style>
    .dropdown-menu {
    position: absolute;
    right: 0 !important;
    z-index: 1000;
    left: unset !important;
    min-width: 10rem !important;
    top: 35% !important;
}
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
    .load-card-list {
        display:none;
    }
    .load-card-list.display {
        display: inline-block;
    }
    </style>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script >
    //   $(document).ready(function(){
    //     $(".load-card-list").slice(0,10).show();
    //   $("#listloadMore").click(function(e){
    //     e.preventDefault();
    //     $(".load-card-list:hidden").slice(0,10).fadeIn("slow");
    //     console.log($(".load-card-list:hidden").length);
    //     if($(".load-card-list:hidden").length == 0){
    //       $("#listloadMore").fadeOut("slow");
    //       }
    //   });
    //   if($(".load-card-list").length < 9){
    //   $("#listloadMore").hide();
       
    //   }
    //   $(".load-card-list1").slice(0,10).show();
    //   $("#listloadMore1").click(function(e){
    //     e.preventDefault();
    //     $(".load-card-list1:hidden").slice(0,10).fadeIn("slow");
    //     console.log($(".load-card-list1:hidden").length);
    //     if($(".load-card-list1:hidden").length == 0){
    //       $("#listloadMore1").fadeOut("slow");
    //       }
    //   });
    //   if($(".load-card-list1").length < 9){
    //   $("#listloadMore1").hide();
       
    //   }

    //   $(".loadid").slice(0,10).show();
    //   $("#loadMore").click(function(e){
    //     e.preventDefault();
    //     $(".loadid:hidden").slice(0,10).fadeIn("slow");
    //     console.log($(".loadid:hidden").length);
    //     if($(".loadid:hidden").length == 0){
    //       $("#loadMore").fadeOut("slow");
    //       }
    //   });
    //   if($(".loadid").length < 10){
    //   $("#loadMore").hide();
       
    //   }
      
    //   $(".loadid1").slice(0,10).show();
    //   $("#loadMore1").click(function(e){
    //     e.preventDefault();
    //     $(".loadid1:hidden").slice(0,10).fadeIn("slow");
    //     console.log($(".loadid1:hidden").length);
    //     if($(".loadid1:hidden").length == 0){
    //       $("#loadMore1").fadeOut("slow");
    //       }
    //   });
    //   if($(".loadid1").length < 10){
    //   $("#loadMore1").hide();
       
    //   }
    // })
    </script>

@endsection

@push('scripts_bottom')
    <script src="{{ config('app.js_css_url') }}/assets/default/vendors/select2/select2.min.js"></script>
    <script src="{{ config('app.js_css_url') }}/assets/default/vendors/swiper/swiper-bundle.min.js"></script>

    <script src="{{ config('app.js_css_url') }}/assets/default/js/parts/categories.min.js"></script>
       <script src="{{ config('app.js_css_url') }}/assets/default/js/parts/instructors.min.js"></script>
         <script src="{{ config('app.js_css_url') }}/assets/default/js/parts/home.min.js"></script>
          <script src="{{ config('app.js_css_url') }}/assets/default/js/parts/webinar_show.min.js"></script>
@endpush
