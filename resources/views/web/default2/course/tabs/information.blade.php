@php
    $learningMaterialsExtraDescription = !empty($course->webinarExtraDescription) ? $course->webinarExtraDescription->where('type','learning_materials') : null;
    $companyLogosExtraDescription = !empty($course->webinarExtraDescription) ? $course->webinarExtraDescription->where('type','company_logos') : null;
    $requirementsExtraDescription = !empty($course->webinarExtraDescription) ? $course->webinarExtraDescription->where('type','requirements') : null;
@endphp


{{-- Installments --}}
@if(!empty($installments) and count($installments) and getInstallmentsSettings('installment_plans_position') == 'top_of_page')
    @foreach($installments as $installmentRow)
        @include('web.default2.installment.card',['installment' => $installmentRow, 'itemPrice' => $course->getPrice(), 'itemId' => $course->id, 'itemType' => 'course'])
    @endforeach
@endif

 <style>
/*  .course-description p span{*/
/*      font-family:'Poppins', Sans-serif !important;*/
/*      color: #000 !important;*/
/*  }*/
/*     body{*/
/*      font-family:'Poppins', Sans-serif !important;*/
/*      color: #000 !important;*/
/*          background-color: #ffffff !important;*/
/*  }  */
/*  .course-description p{*/
/*      font-family:'Poppins', Sans-serif !important;*/
/*      color: #000 !important;*/
/*  }*/
/*  .text-gray {*/
/*    color: #000000 !important;*/
/*}*/
/*.text-dark {*/
/*    color: #000000 !important;*/
/*}*/
 </style>

{{--course description--}}
@if($course->description)
    <div class="mt-20">
        <h2 class="section-title after-line">{{ trans('product.Webinar_description') }}</h2>
        <div class="mt-15 course-description">
            {!! $course->description !!}
        </div>
    </div>
@endif
{{-- ./ course description--}}
@if(!empty($learningMaterialsExtraDescription) and count($learningMaterialsExtraDescription))
    <div class="mt-40">
        <h2 class="section-title after-line">What You will get?</h2>

        @foreach($learningMaterialsExtraDescription as $learningMaterial)
     
        <!--<div class="d-flex align-items-center mt-20 shadow-lg " style="    border-top-left-radius: 30px;     border-bottom-left-radius: 30px;">-->
        <!--                <div class="" style="width: 130px; min-width: 130px;border-radius: 30px; ">-->
        <!--                    <img src="https://asttrolok.in/assets/default/css/landingPage/resources/img/32-Live-Classes-min.png" class="img-cover rounded-circle1" alt="Robert Ransdell" style="border-radius: 30px; ">-->
        <!--                </div>-->
        <!--                <div class="ml-10 mw-100">-->
        <!--                   <h3 class="font-16 text-secondary font-weight-bold">{{ $learningMaterial->value }}</h3>-->
        <!--                         <span class="d-flex align-items-start font-14 text-gray ">Dive deep into astrology with dynamic live sessions taught by the renowned astrologer & trainer.</span>-->
        <!--                </div>-->
        <!--            </div>-->
                 <div class="forums-featured-card d-flex align-items-center bg-white p-20 p-md-35 shadow-lg rounded-lg mt-15">
                    <!--  <div class="forums-featured-card-icon">-->
                    <!--    <img src="/store/1/default_images/forums/icons/marketing.svg" alt="What is social media?" class="img-cover">-->
                    <!--</div>-->
                    <div class="forums-featured-card-icon" style="width: 130px; min-width: 130px; ">
                        <img src="{{ config('app.img_dynamic_url') }}{{ $learningMaterial->img }}" alt="What is social media?" class="img-cover">
                    </div>

                    <div class="ml-15">
                        
                            <h4 class="font-16 font-weight-bold text-dark">{{ $learningMaterial->value }}</h4>
                       
                        <p class="font-16 text-gray">{{ $learningMaterial->description }}</p>
                       
                    </div>
                </div>     
                    
                    
                    
                    
            <!--<p class="d-flex align-items-start font-14 text-gray mt-10">-->
                <!--<i data-feather="check" width="18" height="18" class="mr-10 webinar-extra-description-check-icon"></i>-->
            <!--    <img src="https://asttrolok.in/assets/default/css/landingPage/resources/img/32-Live-Classes-min.png" alt="32 Live Classes - Asttrolok">-->
            <!--    <span class="">{{ $learningMaterial->value }}</span>-->
            <!--     <span class="">Dive deep into astrology with dynamic live sessions taught by the renowned astrologer & trainer.</span>-->
            <!--</p>-->
        @endforeach
    </div>
@endif
 <div class="mt-40">
      <h2 class="section-title after-line">What all you will learn?</h2>
        <!--<h3 class="font-16 text-secondary font-weight-bold mb-15">What all you will learn?</h3>-->
 @include('web.default2'.'.course.tabs.content')
 </div>
@if(!empty($requirementsExtraDescription) and count($requirementsExtraDescription))
    <div class="mt-30">
        <!--<h3 class="font-16 text-secondary font-weight-bold mb-15">{{ trans('update.requirements') }}</h3>-->
        <h2 class="section-title after-line">Bonuses with this Astrology Course</h2>
<!--<h3 class="font-16 text-secondary font-weight-bold mb-15">Bonuses with this Astrology Course</h3>-->
        @foreach($requirementsExtraDescription as $requirementExtraDescription)
         <!--<div class="d-flex align-items-center mt-20">-->
         <!--               <div class="" style="width: 100px; min-width: 100px;height: 100px;">-->
         <!--                   <img src="/store/1015/avatar/617a4f2fb8a6d.png" class="img-cover rounded-circle" alt="Robert Ransdell">-->
         <!--               </div>-->
         <!--               <div class="ml-10 mw-100">-->
         <!--                  <p class="">{{ $requirementExtraDescription->value }}</p>-->
         <!--                        <span class="d-flex align-items-start font-14 text-gray mt-10">Dive deep into astrology with dynamic live sessions taught by the renowned astrologer & trainer.</span>-->
         <!--               </div>-->
         <!--           </div>-->
                    
                    
                    
                    
                    
                    
                    
                    <div class="forums-featured-card d-flex align-items-center bg-white p-20 p-md-35 shadow-lg rounded-lg mt-15">
                        <div class="forums-featured-card-icon" style="width: 130px; min-width: 130px; ">
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
            <!--<p class="d-flex align-items-start font-14 text-gray mt-10">-->
            <!--    <i data-feather="check" width="18" height="18" class="mr-10 webinar-extra-description-check-icon"></i>-->
            <!--    <span class="">{{ $requirementExtraDescription->value }}</span>-->
            <!--</p>-->
        @endforeach
    </div>
@endif
@if(!empty($companyLogosExtraDescription) and count($companyLogosExtraDescription))
    <!--<div class="mt-20 ">-->
       
    <!--              <h2 class="section-title after-line">About Asttrolok</h2>-->
    <!--               <div class="mb-15">-->
            <!--<h3 class="font-16 text-secondary font-weight-bold">{{ trans('update.suggested_by_top_companies') }}</h3>-->
            <!--<p class="font-14 text-gray mt-5">{{ trans('update.suggested_by_top_companies_hint') }}</p>-->
                        
    <!--    </div>-->
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
{{-- course prerequisites --}}
@if(!empty($course->prerequisites) and $course->prerequisites->count() > 0)

    <div class="mt-20">
        <h2 class="section-title after-line">{{ trans('public.prerequisites') }}</h2>

        @foreach($course->prerequisites as $prerequisite)
            @if($prerequisite->prerequisiteWebinar)
                @include('web.default2.includes.webinar.list-card',['webinar' => $prerequisite->prerequisiteWebinar])
            @endif
        @endforeach
    </div>
@endif
{{-- ./ course prerequisites --}}

<!-- <div class="mt-20">-->
        <!--<h2 class="section-title after-line">{{ trans('public.prerequisites') }}</h2>-->
<!--@include('web.default2'.'.course.tabs.reviews')-->
<!--</div>-->
{{-- Installments --}}
@if(!empty($installments) and count($installments) and getInstallmentsSettings('installment_plans_position') == 'bottom_of_page' and 1==2)
    @foreach($installments as $installmentRow)
        @include('web.default2.installment.card',['installment' => $installmentRow, 'itemPrice' => $course->getPrice(), 'itemId' => $course->id, 'itemType' => 'course'])
    @endforeach
@endif

{{-- course Comments --}}
@include('web.default.includes.comments',[
        'comments' => $course->comments,
        'inputName' => 'webinar_id',
        'inputValue' => $course->id
    ])
{{-- ./ course Comments --}}

