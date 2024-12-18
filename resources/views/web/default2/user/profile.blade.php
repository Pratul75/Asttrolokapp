@extends('web.default2'.'.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="{{ config('app.js_css_url') }}/assets/default/vendors/persian-datepicker/persian-datepicker.min.css"/>
    <link rel="stylesheet" href="{{ config('app.js_css_url') }}/assets/default/css/css-stars.css">
        <style>
        @media (max-width: 991px)
.homehide {
    display: none!important;
}
    </style>
    <!-- Event snippet for Booked Appointment conversion page -->
<script>
  gtag('event', 'conversion', {
      'send_to': 'AW-795191608/8cYUCN3vt5cZELjSlvsC',
      'transaction_id': ''
  });
</script>
@endpush


{{session()->put('my_test_key',url()->full())}}

@if(!(auth()->check()))
    
    @push('scripts_top')
    <script>
       // window.location.href = "/login";
    </script>
    @endpush
    
@endif

@section('content')
<style>
    @media (max-width: 2561px) {
  .site-top-banner {
    height: 270px !important;
  }
}

@media (max-width: 1921px) {
  .site-top-banner {
    height: 530px !important;
  }
}

@media (max-width: 1441px) {
  .site-top-banner {
    height: 255px !important;
  }
}

@media (max-width: 1025px) {
  .site-top-banner {
    height: 350px !important;
  }
}

@media (max-width: 991px) {
  .site-top-banner {
    height: 100px !important;
  }
</style>
    <section class="site-top-banner position-relative">
        <!--<img src="{{ config('app.img_dynamic_url') }}{{ $user->getCover() }}" class="img-cover" alt=""/>-->
    </section>


    <section class="container">
        <div class="rounded-lg shadow-sm px-25 py-20 px-lg-50 py-lg-35 position-relative user-profile-info bg-white">
            <div class="profile-info-box d-flex align-items-start justify-content-between">
                <div class="user-details d-flex align-items-center">
                    <div class="user-profile-avatar bg-gray200">
                        <img src="{{ config('app.img_dynamic_url') }}{{ $user->getAvatar(190) }}" class="img-cover" alt="{{ $user["full_name"] }}"/>

                        @if($user->offline)
                            <span class="user-circle-badge unavailable d-flex align-items-center justify-content-center">
                                <i data-feather="slash" width="20" height="20" class="text-white"></i>
                            </span>
                        @elseif($user->verified)
                            <span class="user-circle-badge has-verified d-flex align-items-center justify-content-center">
                                <i data-feather="check" width="20" height="20" class="text-white"></i>
                            </span>
                        @endif
                    </div>
                    <div class=" ml-lg-40">
                        <h1 class="font-24 font-weight-bold text-dark-blue">{{ $user["full_name"] }} </h1>
                        <span class="text-gray">{{ $user["headline"] }}</span>

                        
                        <div class="ml-15 font-14 text-gray text-left align-items-center">
                            @if(!empty($user["bio"]))
                            <pre class="mt-10 font-14  text-dark-blue " style="font-family: var(--font-family-base) !important;">{{$user["bio"]}}</pre>
                        <div class="stars-card d-flex align-items-center">
                          {{--  @include('web.default2.includes.webinar.rate',['rate' => $ratings[$user["id"]]]) --}}
                            @include('web.default2.includes.webinar.rate',['rate' => $user["rating"]])
                        </div>
                            @endif
                            @if($meeting)
                            <div class="mt-5">
                            <span class=" font-20 text-primary font-weight-500">{{ handlePrice($meeting->amount/30) }}</span><span class="text-dark-blue" style="font-size: small!important;"> / Min</span> 
                            </div>
                            @endif
                        </div>
                        

                        <!--<div class="w-100 mt-10 d-none d-flex align-items-center justify-content-center justify-content-lg-start">-->
                        <div class="w-100 mt-10 d-none  align-items-center justify-content-center justify-content-lg-start">
                            <div class="d-flex flex-column followers-status">
                                <span class="font-20 font-weight-bold text-dark-blue">{{ $userFollowers->count() }}</span>
                                <span class="font-14 text-gray">{{ trans('panel.followers') }}</span>
                            </div>

                            <div class="d-flex flex-column ml-25 pl-5 following-status">
                                <span class="font-20 font-weight-bold text-dark-blue">{{ $userFollowing->count() }}</span>
                                <span class="font-14 text-gray">{{ trans('panel.following') }}</span>
                            </div>
                        </div>

                        <div class="user-reward-badges d-none flex-wrap align-items-center mt-15">
                        <!--<div class="user-reward-badges d-flex flex-wrap align-items-center mt-15">-->
                            @if(!empty($userBadges))
                                @foreach($userBadges as $userBadge)
                                    <div class="mr-15" data-toggle="tooltip" data-placement="bottom" data-html="true" title="{!! (!empty($userBadge->badge_id) ? nl2br($userBadge->badge->description) : nl2br($userBadge->description)) !!}">
                                        <img src="{{ config('app.img_dynamic_url') }}{{ !empty($userBadge->badge_id) ? $userBadge->badge->image : $userBadge->image }}" width="32" height="32" alt="{{ !empty($userBadge->badge_id) ? $userBadge->badge->title : $userBadge->title }}">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <!--<div class="user-actions d-flex flex-column">-->
                <div class="user-actions d-none flex-column">
                    <button type="button" id="followToggle" data-user-id="{{ $user['id'] }}" class="btn btn-{{ (!empty($authUserIsFollower) and $authUserIsFollower) ? 'danger' : 'primary' }} btn-sm">
                        @if(!empty($authUserIsFollower) and $authUserIsFollower)
                            {{ trans('panel.unfollow') }}
                        @else
                            {{ trans('panel.follow') }}
                        @endif
                    </button>

                    @if($user->public_message)
                        <button type="button" class="js-send-message btn btn-border-white rounded btn-sm mt-15">{{ trans('site.send_message') }}</button>
                    @endif
                </div>
            </div>
<div class=" homehide">
            <!--<div class="mt-40 border-top"></div>-->

            <!--<div class="row mt-30 w-100 d-flex align-items-center justify-content-around">-->
            <!--    <div class="col-6 col-md-3 user-profile-state d-flex flex-column align-items-center">-->
            <!--        <div class="state-icon orange p-15 rounded-lg">-->
            <!--            <img src="/assets/default/img/profile/students.svg" alt="">-->
            <!--        </div>-->
            <!--        <span class="font-20 text-dark-blue font-weight-bold mt-5">{{ $user->students_count }}</span>-->
            <!--        <span class="font-14 text-gray">{{ trans('quiz.students') }}</span>-->
            <!--    </div>-->

            <!--    <div class="col-6 col-md-3 user-profile-state d-flex flex-column align-items-center">-->
            <!--        <div class="state-icon blue p-15 rounded-lg">-->
            <!--            <img src="/assets/default/img/profile/webinars.svg" alt="">-->
            <!--        </div>-->
            <!--        <span class="font-20 text-dark-blue font-weight-bold mt-5">{{ count($webinars) }}</span>-->
            <!--        <span class="font-14 text-gray">{{ trans('webinars.classes') }}</span>-->
            <!--    </div>-->

            <!--    <div class="col-6 col-md-3 mt-20 mt-md-0 user-profile-state d-flex flex-column align-items-center">-->
            <!--        <div class="state-icon green p-15 rounded-lg">-->
            <!--            <img src="/assets/default/img/profile/reviews.svg" alt="">-->
            <!--        </div>-->
            <!--        <span class="font-20 text-dark-blue font-weight-bold mt-5">{{ $user->reviewsCount() }}</span>-->
            <!--        <span class="font-14 text-gray">{{ trans('product.reviews') }}</span>-->
            <!--    </div>-->


            <!--    <div class="col-6 col-md-3 mt-20 mt-md-0 user-profile-state d-flex flex-column align-items-center">-->
            <!--        <div class="state-icon royalblue p-15 rounded-lg">-->
            <!--            <img src="/assets/default/img/profile/appointments.svg" alt="">-->
            <!--        </div>-->
            <!--        <span class="font-20 text-dark-blue font-weight-bold mt-5">{{ $appointments }}</span>-->
            <!--        <span class="font-14 text-gray">{{ trans('site.appointments') }}</span>-->
            <!--    </div>-->

            <!--</div>-->
            </div>
        </div>
    </section>

    <div class="container mt-30">
        <section class="rounded-lg border px-10 pb-35 pt-5 position-relative">
            <ul class="nav nav-tabs d-flex align-items-center px-20 px-lg-50 pb-15" id="tabs-tab" role="tablist">
                <li class="nav-item mr-20 mr-lg-50 mt-30">
                    <a class="position-relative text-dark-blue font-weight-500 font-16 {{ (empty(request()->get('tab')) or request()->get('tab') == 'about') ? 'active' : ''  }}" id="about-tab" data-toggle="tab" href="#about" role="tab" aria-controls="about" aria-selected="true">{{ trans('site.about') }}</a>
                </li>
                <!--<li class="nav-item mr-20 mr-lg-50 mt-30">-->
                <!--    <a class="position-relative text-dark-blue font-weight-500 font-16 {{ (request()->get('tab') == 'webinars') ? 'active' : ''  }}" id="webinars-tab" data-toggle="tab" href="#webinars" role="tab" aria-controls="webinars" aria-selected="false">{{ trans('panel.classes') }}</a>-->
                <!--</li>-->

                @if($user->isOrganization())
                    <!--<li class="nav-item mr-20 mr-lg-50 mt-30">-->
                    <!--    <a class="position-relative text-dark-blue font-weight-500 font-16 {{ (request()->get('tab') == 'instructors') ? 'active' : ''  }}" id="instructors-tab" data-toggle="tab" href="#instructors" role="tab" aria-controls="instructors" aria-selected="false">{{ trans('home.instructors') }}</a>-->
                    <!--</li>-->
                @endif

                @if(!empty(getStoreSettings('status')) and getStoreSettings('status'))
                    <!--<li class="nav-item mr-20 mr-lg-50 mt-30">-->
                    <!--    <a class="position-relative text-dark-blue font-weight-500 font-16 {{ (request()->get('tab') == 'products') ? 'active' : ''  }}" id="webinars-tab" data-toggle="tab" href="#products" role="tab" aria-controls="products" aria-selected="false">{{ trans('update.products') }}</a>-->
                    <!--</li>-->
                @endif

                <!--<li class="nav-item mr-20 mr-lg-50 mt-30">-->
                <!--    <a class="position-relative text-dark-blue font-weight-500 font-16 {{ (request()->get('tab') == 'posts') ? 'active' : ''  }}" id="webinars-tab" data-toggle="tab" href="#posts" role="tab" aria-controls="posts" aria-selected="false">{{ trans('update.articles') }}</a>-->
                <!--</li>-->

                @if(!empty(getFeaturesSettings('forums_status')) and getFeaturesSettings('forums_status'))
                    <!--<li class="nav-item mr-20 mr-lg-50 mt-30">-->
                    <!--    <a class="position-relative text-dark-blue font-weight-500 font-16 {{ (request()->get('tab') == 'forum') ? 'active' : ''  }}" id="webinars-tab" data-toggle="tab" href="#forum" role="tab" aria-controls="forum" aria-selected="false">{{ trans('update.forum') }}</a>-->
                    <!--</li>-->
                @endif

                <!--<li class="nav-item mr-20 mr-lg-50 mt-30">-->
                <!--    <a class="position-relative text-dark-blue font-weight-500 font-16 {{ (request()->get('tab') == 'badges') ? 'active' : ''  }}" id="badges-tab" data-toggle="tab" href="#badges" role="tab" aria-controls="badges" aria-selected="false">{{ trans('site.badges') }}</a>-->
                <!--</li>-->

                <li class="nav-item mr-20 mr-lg-50 mt-30">
                    <a class="position-relative text-dark-blue font-weight-500 font-16 {{ (request()->get('tab') == 'appointments') ? 'active' : ''  }}" id="appointments-tab" data-toggle="tab" href="#appointments" role="tab" aria-controls="appointments" aria-selected="false">Book a Consultation</a>
                </li>
            </ul>

            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade px-20 px-lg-50 {{ (empty(request()->get('tab')) or request()->get('tab') == 'about') ? 'show active' : ''  }}" id="about" role="tabpanel" aria-labelledby="about-tab">
                    @include('web.default2.user.profile_tabs.about')
                </div>

                <div class="tab-pane fade" id="webinars" role="tabpanel" aria-labelledby="webinars-tab">
                    @include('web.default2.user.profile_tabs.webinars')
                </div>

                @if($user->isOrganization())
                    <div class="tab-pane fade" id="instructors" role="tabpanel" aria-labelledby="instructors-tab">
                        @include('web.default2.user.profile_tabs.instructors')
                    </div>
                @endif

                <div class="tab-pane fade" id="posts" role="tabpanel" aria-labelledby="posts-tab">
                    @include('web.default2.user.profile_tabs.posts')
                </div>

                @if(!empty(getFeaturesSettings('forums_status')) and getFeaturesSettings('forums_status'))
                    <div class="tab-pane fade" id="forum" role="tabpanel" aria-labelledby="forum-tab">
                        @include('web.default2.user.profile_tabs.forum')
                    </div>
                @endif

                @if(!empty(getStoreSettings('status')) and getStoreSettings('status'))
                    <div class="tab-pane fade" id="products" role="tabpanel" aria-labelledby="products-tab">
                        @include('web.default2.user.profile_tabs.products')
                    </div>
                @endif

                <div class="tab-pane fade" id="badges" role="tabpanel" aria-labelledby="badges-tab">
                    @include('web.default2.user.profile_tabs.badges')
                </div>

                <div class="tab-pane fade px-20 px-lg-50 {{ (request()->get('tab') == 'appointments') ? 'show active' : ''  }}" id="appointments" role="tabpanel" aria-labelledby="appointments-tab">
                    @include('web.default2.user.profile_tabs.appointments')
                </div>
            </div>
        </section>
    </div>

    @include('web.default2.user.send_message_modal')

@endsection

@push('scripts_bottom')
    <script>
        var unFollowLang = '{{ trans('panel.unfollow') }}';
        var followLang = '{{ trans('panel.follow') }}';
        var reservedLang = '{{ trans('meeting.reserved') }}';
        var availableDays = {{ json_encode($times) }};
        var messageSuccessSentLang = '{{ trans('site.message_success_sent') }}';
    </script>

    <script src="{{ config('app.js_css_url') }}/assets/default/vendors/persian-datepicker/persian-date.js"></script>
    <script src="{{ config('app.js_css_url') }}/assets/default/vendors/persian-datepicker/persian-datepicker.js"></script>

    <script src="{{ config('app.js_css_url') }}/assets/default/js/parts/profile.min.js"></script>

    @if(!empty($user->live_chat_js_code) and !empty(getFeaturesSettings('show_live_chat_widget')))
        <script>
            (function () {
                "use strict"

                {!! $user->live_chat_js_code !!}
            })(jQuery)
        </script>
    @endif
@endpush
