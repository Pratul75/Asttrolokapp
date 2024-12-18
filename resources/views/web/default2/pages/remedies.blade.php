@extends('web.default2'.'.layouts.app')
<script>console.log($appFooter);</script>
@push('styles_top')
    <link rel="stylesheet" href="{{ config('app.js_css_url') }}/assets2/default/vendors/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="{{ config('app.js_css_url') }}/assets2/default/vendors/select2/select2.min.css">
@endpush

@section('content')
    <section class="site-top-banner search-top-banner opacity-04 position-relative">
        <img src="{{ config('app.img_dynamic_url') }}{{ getPageBackgroundSettings('categories') }}" class="img-cover" alt=""/>
        

        <!--<div class="container h-100">-->
        <!--    <div class="row h-100 align-items-center justify-content-center text-center">-->
        <!--        <div class="col-12 col-md-9 col-lg-7">-->
        <!--            <div class="top-search-categories-form">-->
        <!--                <h1 class="text-white font-30 mb-15">{{ $pageTitle }} </h1>-->
        <!--                <span class="course-count-badge py-5 px-10 text-white rounded">{{ $coursesCount }} Remedies</span>-->

        <!--                <div class="search-input bg-white p-10 flex-grow-1">-->
        <!--                    <form action="/search" method="get">-->
        <!--                        <div class="form-group d-flex align-items-center m-0">-->
        <!--                            <input type="text" name="search" class="form-control border-0" placeholder="Search Remedies"/>-->
        <!--                            <button type="submit" class="btn btn-primary rounded-pill">{{ trans('home.find') }}</button>-->
        <!--                        </div>-->
        <!--                    </form>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->
    </section>

    <div class="container mt-30">

        <section class="mt-lg-50 pt-lg-20 mt-md-40 pt-md-40">
            <form action="/classes" method="get" id="filtersForm">

              

                <div class="row mt-20">
                    <div class="col-12 col-lg-12">

                        @if(empty(request()->get('card')) or request()->get('card') == 'grid')
                            <div class="row">
                                @foreach($remedies as $remedy)
                                    <div class="col-6 col-lg-3 mt-20">
                                        @include('web.default2.includes.remedy.grid-card',['remedy' => $remedy])
                                    </div>
                                @endforeach
                            </div>

                        @elseif(!empty(request()->get('card')) and request()->get('card') == 'list')

                            @foreach($remedies as $remedy)
                                @include('web.default2.includes.remedy.list-card',['remedy' => $remedy])
                            @endforeach
                        @endif

                    </div>


                    
                </div>

            </form>
            <div class="mt-50 pt-30">
                {{ $remedies->appends(request()->input())->links('vendor.pagination.panel') }}
            </div>
        </section>
    </div>

@endsection

@push('scripts_bottom')
    <script src="{{ config('app.js_css_url') }}/assets2/default/vendors/select2/select2.min.js"></script>
    <script src="{{ config('app.js_css_url') }}/assets2/default/vendors/swiper/swiper-bundle.min.js"></script>

    <script src="{{ config('app.js_css_url') }}/assets2/default/js/parts/categories.min.js"></script>
@endpush
