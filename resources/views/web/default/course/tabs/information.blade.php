@php
    $learningMaterialsExtraDescription = !empty($course->webinarExtraDescription) ? $course->webinarExtraDescription->where('type','learning_materials') : null;
    $companyLogosExtraDescription = !empty($course->webinarExtraDescription) ? $course->webinarExtraDescription->where('type','company_logos') : null;
    $requirementsExtraDescription = !empty($course->webinarExtraDescription) ? $course->webinarExtraDescription->where('type','requirements') : null;
@endphp


{{--course description--}}
@if($course->description)
    <div class="mt-20">
        <!--<h2 class="section-title after-line">{{ trans('product.Webinar_description') }}</h2>-->
        <div class="mt-15 course-description">
            {!! $course->description !!}
        </div>
    </div>
@endif
{{-- ./ course description--}}

<div style="text-align: center;">
@if($canSale and !empty(getFeaturesSettings('direct_classes_payment_button_status')))
@if($course->price > 0)
    <button type="button" data-toggle="modal" data-target="#buynow_modal" class=" btn btn-primary btn-sm px-25 mt-20" >
        BUY NOW
    </button>
    @else
    
        @if($course->slug == 'learn-free-vedic-astrology-course-online' )
        <a href="/register-free" class=" btn btn-primary btn-sm mt-20 {{ (!$canSale) ? (' disabled ' . $course->cantSaleStatus($hasBought)) : '' }}">{{ trans('public.enroll_on_webinar') }}</a>
        @else
        <a href="{{ $canSale ? '/course/'. $course->slug .'/free' : '#' }}" class="mt-20 btn btn-primary btn-sm {{ (!$canSale) ? (' disabled ' . $course->cantSaleStatus($hasBought)) : '' }}">{{ trans('public.enroll_on_webinar') }}</a>
        @endif
        
    @endif

@endif
</div>

@if(!empty($learningMaterialsExtraDescription) and count($learningMaterialsExtraDescription))
    <div class="mt-20 ">
    <h2 class="section-title after-line">What You will get?</h2>

        @foreach($learningMaterialsExtraDescription as $learningMaterial)
            <!--<p class="d-flex align-items-start font-14 text-gray mt-10">-->
            <!--    <i data-feather="check" width="18" height="18" class="mr-10 webinar-extra-description-check-icon"></i>-->
            <!--    <span class="">{{ $learningMaterial->value }}</span>-->
            <!--</p>-->
            <div class="forums-featured-card d-flex align-items-center bg-white p-20 p-md-35 shadow-lg rounded-lg mt-15">
                 <!--<div class="forums-featured-card-icon">-->
                 <!--       <img src="/store/1/default_images/forums/icons/marketing.svg" alt="What is social media?" class="img-cover">-->
                 <!--   </div>-->
                    <div class="forums-featured-card-icon col-4" style="padding: 0;">
                        <img src="{{ config('app.img_dynamic_url') }}{{ $learningMaterial->img }}" alt="What is social media?" class="img-cover">
                    </div>

                    <div class="ml-15">
                        
                            <h4 class="font-16 font-weight-bold text-dark">{{ $learningMaterial->value }}</h4>
                       
                        <p class="font-16 text-gray">{{ $learningMaterial->description }}</p>
                       
                    </div>
                </div>    
        @endforeach
    </div>
@endif

 <div class="mt-40">
      <h2 class="section-title after-line">What all you will learn?</h2>
        <!--<h3 class="font-16 text-secondary font-weight-bold mb-15">What all you will learn?</h3>-->
 @include('web.default2'.'.course.tabs.content')
 </div>
 
<div style="text-align: center;">
@if($canSale and !empty(getFeaturesSettings('direct_classes_payment_button_status')))
@if($course->price > 0)
    <button type="button" data-toggle="modal" data-target="#buynow_modal" class=" btn btn-primary btn-sm px-25 mt-20" >
        BUY NOW
    </button>
    @else
    
        @if($course->slug == 'learn-free-vedic-astrology-course-online' )
        <a href="/register-free" class=" btn btn-primary btn-sm mt-20 {{ (!$canSale) ? (' disabled ' . $course->cantSaleStatus($hasBought)) : '' }}">{{ trans('public.enroll_on_webinar') }}</a>
        @else
        <a href="{{ $canSale ? '/course/'. $course->slug .'/free' : '#' }}" class="mt-20 btn btn-primary btn-sm {{ (!$canSale) ? (' disabled ' . $course->cantSaleStatus($hasBought)) : '' }}">{{ trans('public.enroll_on_webinar') }}</a>
        @endif
        
    @endif

@endif
</div>

@if(!empty($requirementsExtraDescription) and count($requirementsExtraDescription))
    <div class="mt-20">
        <!--<h3 class="font-16 text-secondary font-weight-bold mb-15">{{ trans('update.requirements') }}</h3>-->
<h2 class="section-title after-line">Bonuses with this Astrology Course</h2>
        @foreach($requirementsExtraDescription as $requirementExtraDescription)
            <!--<p class="d-flex align-items-start font-14 text-gray mt-10">-->
            <!--    <i data-feather="check" width="18" height="18" class="mr-10 webinar-extra-description-check-icon"></i>-->
            <!--    <span class="">{{ $requirementExtraDescription->value }}</span>-->
            <!--</p>-->
            
            <div class="forums-featured-card d-flex align-items-center bg-white p-20 p-md-35 shadow-lg rounded-lg mt-15">
                        <div class="forums-featured-card-icon col-3" style="padding: 0;">
                        <img src="{{ config('app.img_dynamic_url') }}{{ $requirementExtraDescription->img }}" alt="What is social media?" class="img-cover">
                    </div>
                    <!--<div class="forums-featured-card-icon">-->
                    <!--    <img src="/store/1/default_images/forums/icons/marketing.svg" alt="What is social media?" class="img-cover">-->
                    <!--</div>-->

                    <div class="ml-15">
                        
                            <h4 class="font-16 font-weight-bold text-dark">{{ $requirementExtraDescription->value }}</h4>
                       
                        <p class="font-16 text-gray">{{ $requirementExtraDescription->description }}.</p>
                       
                    </div>
                </div>
        @endforeach
    </div>
@endif



@if(!empty($companyLogosExtraDescription) and count($companyLogosExtraDescription))

 <div class="mt-40">
        <h2 class="section-title after-line">About Asttrolok</h2>
        <div class="mt-15 course-description">
            <!--{!! clean($course->description) !!}-->
<div>Asttrolok, founded in 2016, stands as one of the top three reputable online Vedic institutes in the country, dedicated to dispelling misconceptions and championing fact-based knowledge of Vedic Science in the fields of Astrology, Numerology, Palmistry, Yoga, Ayurveda & Scriptures. With students hailing from over 50+ countries, including professionals like lawyers, doctors, IITians, and actors, Asttrolok boasts a diverse and esteemed student body.</div>

<div class="mt-20">The institute's reputation is further enhanced by its association with the Founder, Renowned Astrologer & Trainer Mr. Alok Khandelwal & 50+ other mentors & panelists, who all bring their extensive expertise and experience to the teaching. Asttrolok's commitment to protecting & spreading the knowledge that liberates & transforms solidifies its standing as a leading institution in the realm of Vedic astrology.</div>

        </div>
    </div>
        <div class="row mt-20">
            @foreach($companyLogosExtraDescription as $companyLogo)
                <div class="col text-center">
                    <img src="{{ config('app.img_dynamic_url') }}{{ $companyLogo->value }}" class="webinar-extra-description-company-logos" alt="{{ trans('update.company_logos') }}">
                </div>
            @endforeach
        </div>
 
@endif
{{-- course prerequisites --}}
@if(!empty($course->prerequisites) and $course->prerequisites->count() > 0)

    <div class="mt-20">
        <h2 class="section-title after-line">{{ trans('public.prerequisites') }}</h2>

        @foreach($course->prerequisites as $prerequisite)
            @if($prerequisite->prerequisiteWebinar)
                @include('web.default.includes.webinar.list-card',['webinar' => $prerequisite->prerequisiteWebinar])
            @endif
        @endforeach
    </div>
@endif
{{-- ./ course prerequisites --}}

{{-- course FAQ --}}
@if(!empty($course->faqs) and $course->faqs->count() > 0)
    <div class="mt-20">
        <h2 class="section-title after-line">{{ trans('public.faq') }}</h2>

        <div class="accordion-content-wrapper mt-15" id="accordion" role="tablist" aria-multiselectable="true">
            @foreach($course->faqs as $faq)
                <div class="accordion-row rounded-sm shadow-lg border mt-20 py-20 px-35">
                    <div class="font-weight-bold font-14 text-secondary" role="tab" id="faq_{{ $faq->id }}">
                        <div href="#collapseFaq{{ $faq->id }}" aria-controls="collapseFaq{{ $faq->id }}" class="d-flex align-items-center justify-content-between" role="button" data-toggle="collapse" data-parent="#accordion" aria-expanded="true">
                            <span>{{ clean($faq->title,'title') }}</span>
                            <i class="collapse-chevron-icon" data-feather="chevron-down" width="25" class="text-gray"></i>
                        </div>
                    </div>
                    <div id="collapseFaq{{ $faq->id }}" aria-labelledby="faq_{{ $faq->id }}" class=" collapse" role="tabpanel">
                        <div class="panel-collapse text-gray">
                            {{ clean($faq->answer,'answer') }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
{{-- ./ course FAQ --}}

<!--<div class="rounded-lg d-none mt-35 px-25 py-20">-->
<!--    <h3 class="sidebar-title font-16 text-secondary font-weight-bold">{{ trans('webinars.'.$course->type) .' '. trans('webinars.specifications') }}</h3>-->

<!--    <div class="">-->
<!--        @if($course->isWebinar())-->
<!--            <div class="mt-20 d-flex align-items-center justify-content-between text-gray">-->
<!--                <div class="d-flex align-items-center">-->
<!--                    <i data-feather="calendar" width="20" height="20"></i>-->
<!--                    <span class="ml-5 font-14 font-weight-500">{{ trans('public.start_date') }}:</span>-->
<!--                </div>-->
<!--                <span class="font-14">{{ dateTimeFormat($course->start_date, 'j M Y | H:i') }}</span>-->
<!--            </div>-->
<!--        @endif-->

        <!--<div class="mt-20 d-flex align-items-center justify-content-between text-gray">-->
        <!--    <div class="d-flex align-items-center">-->
        <!--        <i data-feather="user" width="20" height="20"></i>-->
        <!--        <span class="ml-5 font-14 font-weight-500">{{ trans('public.capacity') }}:</span>-->
        <!--    </div>-->
        <!--    @if(!is_null($course->capacity))-->
        <!--        <span class="font-14">{{ $course->capacity }} {{ trans('quiz.students') }}</span>-->
        <!--    @else-->
        <!--        <span class="font-14">{{ trans('update.unlimited') }}</span>-->
        <!--    @endif-->
        <!--</div>-->

        <!--<div class="mt-20 d-flex align-items-center justify-content-between text-gray">-->
        <!--    <div class="d-flex align-items-center">-->
        <!--        <i data-feather="clock" width="20" height="20"></i>-->
        <!--        <span class="ml-5 font-14 font-weight-500">{{ trans('public.duration') }}:</span>-->
        <!--    </div>-->
        <!--    <span class="font-14">{{ convertMinutesToHourAndMinute(!empty($course->duration) ? $course->duration : 0) }} {{ trans('home.hours') }}</span>-->
        <!--</div>-->

        <!--<div class="mt-20 d-flex align-items-center justify-content-between text-gray">-->
        <!--    <div class="d-flex align-items-center">-->
        <!--        <i data-feather="users" width="20" height="20"></i>-->
        <!--        <span class="ml-5 font-14 font-weight-500">{{ trans('quiz.students') }}:</span>-->
        <!--    </div>-->
        <!--    @if(url()->current()=='https://lms.asttrolok.com/course/Free-Astrology-Course')-->
        <!--    <span class="font-14">3200</span>-->
            
        <!--    @else-->
        <!--    <span class="font-14">{{ $course->sales_count }}</span>-->
            
        <!--    @endif-->
            
        <!--</div>-->

<!--        @if($course->isWebinar())-->
<!--            <div class="mt-20 d-flex align-items-center justify-content-between text-gray">-->
<!--                <div class="d-flex align-items-center">-->
<!--                    <img src="{{ config('app.js_css_url') }}/assets/default/img/icons/sessions.svg" width="20" alt="sessions">-->
<!--                    <span class="ml-5 font-14 font-weight-500">{{ trans('public.sessions') }}:</span>-->
<!--                </div>-->
                <!--<span class="font-14">{{ $course->sessions->count() }}</span>-->
<!--            </div>-->
<!--        @endif-->

<!--        @if($course->isTextCourse())-->
<!--            <div class="mt-20 d-flex align-items-center justify-content-between text-gray">-->
<!--                <div class="d-flex align-items-center">-->
<!--                    <img src="{{ config('app.js_css_url') }}/assets/default/img/icons/sessions.svg" width="20" alt="sessions">-->
<!--                    <span class="ml-5 font-14 font-weight-500">{{ trans('webinars.text_lessons') }}:</span>-->
<!--                </div>-->
<!--                <span class="font-14">{{ $course->textLessons->count() }}</span>-->
<!--            </div>-->
<!--        @endif-->

<!--        @if($course->isCourse() or $course->isTextCourse())-->
<!--            <div class="mt-20  align-items-center justify-content-between text-gray">-->
<!--                <div class="d-flex align-items-center">-->
                    <!--<img src="{{ config('app.js_css_url') }}/assets/default/img/icons/sessions.svg" width="20" alt="sessions">-->
                    
<!--<svg width="14" height="18" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">-->
<!--<path d="M13.7109 4.74609L10.0195 3.69141L8.96484 0H1.58203C0.708293 0 0 0.708293 0 1.58203V16.418C0 17.2917 0.708293 18 1.58203 18H12.1289C13.0026 18 13.7109 17.2917 13.7109 16.418V4.74609Z" fill="#4086F4"/>-->
<!--<path d="M13.7109 4.74609V16.418C13.7109 17.2917 13.0026 18 12.1289 18H6.85547V0H8.96484L10.0195 3.69141L13.7109 4.74609Z" fill="#4175DF"/>-->
<!--<path d="M13.7109 4.74609H10.0195C9.43945 4.74609 8.96484 4.27148 8.96484 3.69141V0C9.10195 0 9.23906 0.0527344 9.33395 0.158238L13.5527 4.37699C13.6582 4.47187 13.7109 4.60898 13.7109 4.74609Z" fill="#80AEF8"/>-->
<!--<path d="M10.0195 8.47266H3.69141C3.39993 8.47266 3.16406 8.23679 3.16406 7.94531C3.16406 7.65383 3.39993 7.41797 3.69141 7.41797H10.0195C10.311 7.41797 10.5469 7.65383 10.5469 7.94531C10.5469 8.23679 10.311 8.47266 10.0195 8.47266Z" fill="#FFF5F5"/>-->
<!--<path d="M10.0195 10.582H3.69141C3.39993 10.582 3.16406 10.3462 3.16406 10.0547C3.16406 9.76321 3.39993 9.52734 3.69141 9.52734H10.0195C10.311 9.52734 10.5469 9.76321 10.5469 10.0547C10.5469 10.3462 10.311 10.582 10.0195 10.582Z" fill="#FFF5F5"/>-->
<!--<path d="M10.0195 12.6914H3.69141C3.39993 12.6914 3.16406 12.4555 3.16406 12.1641C3.16406 11.8726 3.39993 11.6367 3.69141 11.6367H10.0195C10.311 11.6367 10.5469 11.8726 10.5469 12.1641C10.5469 12.4555 10.311 12.6914 10.0195 12.6914Z" fill="#FFF5F5"/>-->
<!--<path d="M7.91016 14.8008H3.69141C3.39993 14.8008 3.16406 14.5649 3.16406 14.2734C3.16406 13.982 3.39993 13.7461 3.69141 13.7461H7.91016C8.20164 13.7461 8.4375 13.982 8.4375 14.2734C8.4375 14.5649 8.20164 14.8008 7.91016 14.8008Z" fill="#FFF5F5"/>-->
<!--<path d="M6.85547 14.8008H7.91016C8.20164 14.8008 8.4375 14.5649 8.4375 14.2734C8.4375 13.982 8.20164 13.7461 7.91016 13.7461H6.85547V14.8008Z" fill="#E3E7EA"/>-->
<!--<path d="M6.85547 12.6914H10.0195C10.311 12.6914 10.5469 12.4555 10.5469 12.1641C10.5469 11.8726 10.311 11.6367 10.0195 11.6367H6.85547V12.6914Z" fill="#E3E7EA"/>-->
<!--<path d="M6.85547 10.582H10.0195C10.311 10.582 10.5469 10.3462 10.5469 10.0547C10.5469 9.76321 10.311 9.52734 10.0195 9.52734H6.85547V10.582Z" fill="#E3E7EA"/>-->
<!--<path d="M6.85547 8.47266H10.0195C10.311 8.47266 10.5469 8.23679 10.5469 7.94531C10.5469 7.65383 10.311 7.41797 10.0195 7.41797H6.85547V8.47266Z" fill="#E3E7EA"/>-->
<!--</svg>-->

<!--                    <span class="ml-5 font-14 font-weight-500">{{ trans('public.files') }} : {{ $course->files->count() }}</span>-->
<!--                </div>-->
<!--                {{-- <span class="font-14"></span> --}}-->

<!--                @if(!empty($course->access_days))-->
                
<!--            {{-- <div class="mt-20  align-items-center justify-content-between text-gray"> --}}-->
<!--                <div class="d-flex align-items-center " style="margin-top:15px;">-->
                    <!--<i data-feather="alert-circle" width="20" height="20"></i>-->
                    
<!--<svg width="16" height="18" viewBox="0 0 16 18" fill="none" xmlns="http://www.w3.org/2000/svg">-->
<!--<path fill-rule="evenodd" clip-rule="evenodd" d="M12 12.375H9.71429C9.39886 12.375 9.14286 12.627 9.14286 12.9375V16.3125C9.14286 16.623 9.39886 16.875 9.71429 16.875H12V18C12 18 4.60857 18 1.71429 18C1.25943 18 0.82343 17.8223 0.502287 17.5056C0.180572 17.1894 0 16.7603 0 16.3125C0 13.2441 0 4.75594 0 1.6875C0 0.755438 0.767429 0 1.71429 0H8.57143C8.72286 0 8.86857 0.0590625 8.97543 0.164813L12.404 3.53981C12.5114 3.645 12.5714 3.78844 12.5714 3.9375V6.75C12.5714 7.0605 12.3154 7.3125 12 7.3125C11.0537 7.3125 10.2857 8.0685 10.2857 9V11.25H12V12.375Z" fill="#5490F2"/>-->
<!--<path fill-rule="evenodd" clip-rule="evenodd" d="M2.85714 5.0625H4.57143C4.88686 5.0625 5.14286 4.8105 5.14286 4.5C5.14286 4.1895 4.88686 3.9375 4.57143 3.9375H2.85714C2.54171 3.9375 2.28571 4.1895 2.28571 4.5C2.28571 4.8105 2.54171 5.0625 2.85714 5.0625Z" fill="white"/>-->
<!--<path fill-rule="evenodd" clip-rule="evenodd" d="M7.41714 6.1875H2.84514C2.52967 6.1875 2.27364 6.4395 2.27364 6.75C2.27364 7.0605 2.52967 7.3125 2.84514 7.3125H7.41714C7.73261 7.3125 7.98864 7.0605 7.98864 6.75C7.98864 6.4395 7.73261 6.1875 7.41714 6.1875Z" fill="white"/>-->
<!--<path fill-rule="evenodd" clip-rule="evenodd" d="M7.42857 8.4375H5.71429C5.39886 8.4375 5.14286 8.6895 5.14286 9C5.14286 9.3105 5.39886 9.5625 5.71429 9.5625H7.42857C7.744 9.5625 8 9.3105 8 9C8 8.6895 7.744 8.4375 7.42857 8.4375Z" fill="white"/>-->
<!--<path fill-rule="evenodd" clip-rule="evenodd" d="M6.84572 5.0625H7.41657C7.73169 5.0625 7.98743 4.8105 7.98743 4.5C7.98743 4.1895 7.73169 3.9375 7.41657 3.9375H6.84572C6.5306 3.9375 6.27486 4.1895 6.27486 4.5C6.27486 4.8105 6.5306 5.0625 6.84572 5.0625Z" fill="white"/>-->
<!--<path fill-rule="evenodd" clip-rule="evenodd" d="M3.44 8.4375H2.86914C2.55403 8.4375 2.29828 8.6895 2.29828 9C2.29828 9.3105 2.55403 9.5625 2.86914 9.5625H3.44C3.75511 9.5625 4.01086 9.3105 4.01086 9C4.01086 8.6895 3.75511 8.4375 3.44 8.4375Z" fill="white"/>-->
<!--<path fill-rule="evenodd" clip-rule="evenodd" d="M8.57143 0C8.72286 0 8.86857 0.0590625 8.97543 0.164813L12.404 3.53981C12.5114 3.645 12.5714 3.78844 12.5714 3.9375H9.71429C9.08286 3.9375 8.57143 3.43406 8.57143 2.8125V0Z" fill="black" fill-opacity="0.2"/>-->
<!--<path fill-rule="evenodd" clip-rule="evenodd" d="M10.2988 11.8171V9.00337C10.2988 8.0716 11.0672 7.31523 12.0137 7.31523C12.9603 7.31523 13.7291 8.0716 13.7291 9.00337V11.8175C13.7291 12.1279 13.9852 12.3801 14.3006 12.3801C14.616 12.3801 14.8725 12.1279 14.8725 11.8171V9.00337C14.8725 7.45029 13.5914 6.18966 12.0137 6.18966C10.436 6.18966 9.15534 7.45029 9.15534 9.00337V11.8175C9.15534 12.1279 9.41147 12.3801 9.72686 12.3801C10.0422 12.3801 10.2988 12.1279 10.2988 11.8171Z" fill="#FFB61B"/>-->
<!--<path fill-rule="evenodd" clip-rule="evenodd" d="M11.7143 6.20156C11.8086 6.192 11.9034 6.1875 12 6.1875C13.5771 6.1875 14.8571 7.4475 14.8571 9V11.8125C14.8571 12.123 14.6011 12.375 14.2857 12.375C14.1817 12.375 14.084 12.3474 14 12.2996C14.1709 12.2023 14.2857 12.0206 14.2857 11.8125V9C14.2857 7.54256 13.1571 6.34275 11.7143 6.20156Z" fill="black" fill-opacity="0.2"/>-->
<!--<path fill-rule="evenodd" clip-rule="evenodd" d="M16 12.9375C16 12.0054 15.2326 11.25 14.2857 11.25C13.0171 11.25 10.9829 11.25 9.71429 11.25C8.76743 11.25 8 12.0054 8 12.9375V16.3125C8 17.2446 8.76743 18 9.71429 18H14.2857C15.2326 18 16 17.2446 16 16.3125V12.9375ZM12 14.0625C12.3154 14.0625 12.5714 14.3145 12.5714 14.625C12.5714 14.9355 12.3154 15.1875 12 15.1875C11.6846 15.1875 11.4286 14.9355 11.4286 14.625C11.4286 14.3145 11.6846 14.0625 12 14.0625Z" fill="#FFB61B"/>-->
<!--<path fill-rule="evenodd" clip-rule="evenodd" d="M9.71429 11.25H14.2857C15.2326 11.25 16 12.0054 16 12.9375V16.3125C16 17.2446 15.2326 18 14.2857 18H13.7143C14.6611 18 15.4286 17.2446 15.4286 16.3125V12.9375C15.4286 12.0054 14.6611 11.25 13.7143 11.25H9.71429Z" fill="black" fill-opacity="0.2"/>-->
<!--</svg>-->

<!--                    <span class="ml-5 font-14 font-weight-500">{{ trans('update.access_period') }} : {{ $course->access_days }} {{ trans('public.days') }}</span>-->
<!--                </div>-->
<!--                {{-- <span class="font-14"></span> --}}-->
<!--            {{-- </div> --}}-->
<!--        @endif-->
<!--            </div>-->
<!--<div class=" align-items-center " style="display: flex !important;-->
<!--    flex-direction: row;-->
<!--    flex-wrap: nowrap;-->
<!--    margin-top:15px;" >-->
                    <!--<i data-feather="alert-circle" width="20" height="20"></i>-->
                    

<!--<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">-->
<!--<path d="M10.3894 4.6619e-08C7.98504 -0.000281599 5.84441 1.27562 4.62768 3.21533H4.53003C2.00023 3.21533 0 5.3057 0 7.85767C0 10.4096 2.02094 12.4988 4.53003 12.4988H6.87378C7.70793 12.4988 7.70793 11.2476 6.87378 11.2476H4.53003C2.71099 11.2476 1.25 9.7574 1.25 7.85767C1.25 5.9677 2.71099 4.46655 4.53003 4.46655H4.98291C5.20963 4.46655 5.41653 4.34086 5.52734 4.14307C6.53407 2.34623 8.40917 1.25 10.3894 1.25C13.2038 1.25 15.606 3.41361 15.9631 6.32569C16.0016 6.63964 16.2669 6.87378 16.5857 6.87378C17.9295 6.87378 18.75 7.9585 18.75 9.06128C18.75 10.2811 17.8117 11.2476 16.6418 11.2476H13.1262C12.2921 11.2476 12.2921 12.4988 13.1262 12.4988H16.6418C18.5017 12.4988 20 10.9723 20 9.06128C20 7.31954 18.7844 5.8723 17.1216 5.66162C16.5402 2.54576 13.8129 0.000401078 10.3894 4.6619e-08ZM11.0315 2.75024C10.2144 2.75024 10.1801 3.97497 11.0315 4.00025C12.0675 4.00025 12.8702 4.75125 12.9736 5.6189C13.081 6.44652 14.3225 6.28539 14.2151 5.45777C13.981 3.77125 12.518 2.74334 11.0315 2.75024ZM9.99999 7.5C9.64791 7.5 9.37499 7.78565 9.37499 8.125V15.3674L7.93823 13.9294C7.34324 13.334 6.45719 14.216 7.05932 14.8181L9.5581 17.3169C9.80261 17.5614 10.1986 17.5614 10.4431 17.3169L12.9468 14.8132C13.5584 14.2225 12.6419 13.3232 12.0605 13.9319L10.625 15.3674C10.625 15.3674 10.625 10.5391 10.625 8.125C10.625 7.78076 10.3521 7.5 9.99999 7.5ZM6.87377 20H13.1262C13.9624 20 13.9624 18.75 13.1262 18.75H6.87377C6.04003 18.75 6.04003 20 6.87377 20Z" fill="#A0A0A0"/>-->
<!--</svg>-->


<!--                    <span class="ml-5 font-14 font-weight-500">Downloadable Content</span>-->
<!--                </div>-->
<!--            {{-- <div class="mt-20 d-flex align-items-center justify-content-between text-gray">-->
<!--                <div class="d-flex align-items-center">-->
<!--                    <img src="{{ config('app.js_css_url') }}/assets/default/img/icons/sessions.svg" width="20" alt="sessions">-->
<!--                    <span class="ml-5 font-14 font-weight-500">{{ trans('public.created_at') }}:</span>-->
<!--                </div>-->
<!--                <span class="font-14">{{ dateTimeFormat($course->created_at,'j M Y') }}</span>-->
<!--            </div> --}}-->
<!--        @endif-->

        
<!--    </div>-->
<!--</div>-->




{{-- course Comments --}}
{{-- @include('web.default.includes.comments',[
        'comments' => $course->comments,
        'inputName' => 'webinar_id',
        'inputValue' => $course->id
    ]) --}}
{{-- ./ course Comments --}}
