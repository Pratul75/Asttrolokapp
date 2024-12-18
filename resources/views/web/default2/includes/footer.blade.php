@php
    $socials = getSocials();
    if (!empty($socials) and count($socials)) {
        $socials = collect($socials)->sortBy('order')->toArray();
    }

    $footerColumns = getFooterColumns();
@endphp
 <div class="footermobile">
    
</div>
<footer class="footer bg-secondary position-relative user-select-none">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class=" footer-subscribe d-block d-md-flex align-items-center justify-content-between">
                    <div class="flex-grow-1">
                        <strong>{{ trans('footer.join_us_today') }}</strong>
                        <span class="d-block mt-5 text-white">{{ trans('footer.subscribe_content') }}</span>
                    </div>
                    <div class="subscribe-input bg-white p-10 flex-grow-1 mt-30 mt-md-0">
                        <form action="/newsletters" method="post">
                            {{ csrf_field() }}

                            <div class="form-group d-flex align-items-center m-0">
                                <div class="w-100">
                                    <input type="text" maxlength="60" name="newsletter_email" class="form-control border-0 @error('newsletter_email') is-invalid @enderror" placeholder="{{ trans('footer.enter_email_here') }}"/>
                                    @error('newsletter_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary rounded-pill">{{ trans('footer.join') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $columns = ['first_column','second_column','third_column','forth_column'];
    @endphp

    <div class="container">
       <div class="row">

         @foreach($columns as $column)
                @if($column=='first_column')
            <div class=" col-md-5 mt-10">
                @elseif($column=='third_column')
             <div class=" col-md-3 mt-10">  
                @else
             <div class=" col-md-2 mt-10"> 
            @endif
                    @if(!empty($footerColumns[$column]))
                        @if(!empty($footerColumns[$column]['title']))
                            <span class="header d-block text-white font-weight-bold d-none" style="font-size: 20px !important;">{{ $footerColumns[$column]['title'] }}</span>
                        @endif

                        @if(!empty($footerColumns[$column]['value']))
                          @if($column=='first_column')
            <div class="">
                @else
             <div class="mt-20"> 
            @endif
                            
                                {!! $footerColumns[$column]['value'] !!}
                            </div>
                        @endif
                    @endif
                </div>
            @endforeach
            
    
            

        </div> 
        
        {{-- <div class="row">
            <div class="col-md-5">
                <div class="">
                    <div class="footer-logo" style="width: 215px; height: 57px;">
                        <a href="/"><img src="{{ config('app.js_css_url') }}/store/1/Asttolok-White-Logo.png" class="img-cover" alt="footer logo" /></a>
                    </div>
                    <br />
                    <p>
                        <span style="font-family: Montserrat, sans-serif; font-size: 15px; letter-spacing: 0.04695px; text-align: justify;">
                            <font color="#ffffff">
                                Welcome to Asttrolok, recognized among India's top 3 astrology institutes, offering courses in astrology, numerology, palmistry, vastu, & ayurveda. Our goal:
                                guide to enlightenment, share wisdom globally.
                            </font>
                        </span>
                    </p>
                    
                </div>
                <div class="align-items-center justify-content-between mt-15">
            <div class="footer-social">
                <a href="https://www.instagram.com/asttrolok"> <img src="{{ config('app.js_css_url') }}/store/1/default_images/social/Instagram.png" alt="Instagram" class="mr-15" /></a>
                <a href="//api.whatsapp.com/send?phone=919174822333&text= "><img src="{{ config('app.js_css_url') }}/store/1/default_images/social/Whatsapp.png" alt="Whatsapp" class="mr-15" /></a>
                <a href="https://twitter.com/asttrolok"> <img src="{{ config('app.js_css_url') }}/store/1/default_images/social/Twitter.png" alt="Twitter" class="mr-15" /> </a>
                <a href="https://www.facebook.com/asttrolok"><img src="{{ config('app.js_css_url') }}/store/1/default_images/social/FB.png" alt="Facebook" class="mr-15" /></a>
                <!--<a href="https://www.youtube.com/@ASTTROLOKChannel"><img src="{{ config('app.js_css_url') }}/store/1/default_images/social/Youtube.png" alt="youtube" class="mr-15" /></a>-->
                <a href="https://in.pinterest.com/asttrolok/"><img src="{{ config('app.js_css_url') }}/store/1/default_images/social/Pinterest.png" alt="pinterest" class="mr-15" /></a>
            </div>
        </div>
            </div>
            <div class="col-md-2 mt-10">
                <span class="header d-block text-white font-weight-bold d-none" style="font-size: 20px !important;">Quick Links</span>
        
                <div class="mt-20 mg-left" style="font-family: Montserrat, sans-serif; font-size: 15px; letter-spacing: 0.04695px;">
                    <!--<p>-->
                    <!--    <a href="/pages/about"><font color="#ffffff">- About Us</font></a>-->
                    <!--</p>-->
                    <ul>
                         <li style="list-style: outside;color: #ffffff;">
                        
                        <p>
                            <a href="#"><font color="#ffffff">Career & Placement</font></a>
                        </p>
                        </li>
                        <li style="list-style: outside;color: #ffffff;">
                        <p>
                            <font color="#ffffff">
                                <a href="https://asttroveda.asttrolok.com/asttrolok/personalizedkundali"><font color="#ffffff">Kundali Reports</font></a><br />
                            </font>
                        </p>
                        </li>
                       
                        <li style="list-style: outside;color: #ffffff;">
                        <p>
                            <a href="/instructors"><font color="#ffffff">Astrologers</font></a>
                        </p>
                        </li>
                        <li style="list-style: outside;color: #ffffff;">
                        <p>
                            <a href="/classes"><font color="#ffffff">Courses</font></a>
                        </p>
                        </li>
                        <li style="list-style: outside;color: #ffffff;">
                        <p>
                            <font color="#ffffff">
                                <a href="/blog"><font color="#ffffff">Blog</font></a><br />
                            </font>
                        </p>
                        </li>
                    </ul>
                    <!--<p>-->
                    <!--    <a href="/instructors"><font color="#ffffff">- Astrologers</font></a>-->
                    <!--</p>-->
                    <!--<p>-->
                    <!--    <a href="/contact"><font color="#ffffff">- Contact us</font></a>-->
                    <!--</p>-->
                </div>
            </div>
            <!--<div class="col-6 col-md-2">-->
            <!--    <span class="header d-block text-white font-weight-bold d-none" style="font-size: 20px !important;">Services</span>-->
        
            <!--    <div class="mt-20">-->
                    
            <!--        <p>-->
            <!--            <a href="/classes"><font color="#ffffff">- Courses</font></a>-->
            <!--        </p>-->
                    
            <!--        <p>-->
            <!--            <a href="/instructors"><font color="#ffffff">- Consultation</font></a>-->
            <!--        </p>-->
            <!--        <p>-->
            <!--            <font color="#ffffff">-->
            <!--                <a href="https://asttroveda.asttrolok.com/asttrolok/personalizedkundali"><font color="#ffffff">- Personalized Reports</font></a><br />-->
            <!--            </font>-->
            <!--        </p>-->
            <!--        <p>-->
            <!--            <a href="https://asttroveda.asttrolok.com/numerology/order"><font color="#ffffff">- Numerology Report</font></a>-->
            <!--        </p>-->
            <!--        <p>-->
            <!--            <a href="https://asttroveda.asttrolok.com/varshaphal/report2024"><font color="#ffffff">- Varshpahl Report</font></a>-->
            <!--        </p>-->
            <!--    </div>-->
            <!--</div>-->
            
            
            <div class=" col-md-3 mt-10">
                <span class="header d-block text-white font-weight-bold d-none" style="font-family: Montserrat, sans-serif; font-size: 15px; letter-spacing: 0.04695px;font-size: 20px !important;">Get In Touch</span>
        
                <div class="mt-20 ">
                    <p class="mt-5" style="font-size: 15px !important;">
                        <font color="#ffffff"><a href="tel:09174822333"  style="color:#fff; margin-right:10px;" target="_blank">
                           <!--<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone text-white"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg> -->
                           <img src="{{ config('app.js_css_url') }}/store/1/default_images/social/Call Icon.svg" width="15" height="15" alt="Instagram" />
                           09174822333
                        </a></font>
                    </p>
                    <p class="mt-5" style="font-size: 15px !important;">
                        
                        <font color="#ffffff">
                            <a href="mailto:astrolok.vedic@gmail.com"  style="color:#fff" target="_blank">
                           <img src="{{ config('app.js_css_url') }}/store/1/default_images/social/mail_4314565 1.svg" width="15" height="15" alt="Instagram" />
                            <!--<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail text-white"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>-->
                        astrolok.vedic@gmail.com
                        </a>
                        </font>
                    </p>
                    <p class="mt-5" style="font-size: 15px !important;">
                        <a href="https://maps.app.goo.gl/SogMK8SxiX9eHbRc9" target="_blank">
                            <font color="#ffffff" style="display: flex;justify-content: space-between;flex-direction: row;">
                                <img src="{{ config('app.js_css_url') }}/store/1/default_images/social/Location Icon.svg" width="15" height="15" alt="Instagram" />
                                <span style="padding-left: 5px;"> 312, 3rd Floor, Vikram Urbane, 25-A Mechanic Nagar Extn. Sch# 54, Indore(MP) 452010 </span>
                            </font>
                        </a>
                    </p>
                    <!--<p>-->
                    <!--    <a href="/contact"><font color="#ffffff">- Contact us</font></a>-->
                    <!--</p>-->
                    
                    <!--<table>-->
                        
                    <!--<p>-->
                    <!--    <tr><td>-->
                    <!--    <font color="#ffffff"><a href="tel:09174822333"  style="color:#fff; margin-right:10px;">-->
                    <!--       <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone text-white"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg> -->
                    <!--    </a></font>-->
                    <!--    </td><td>-->
                    <!--    <font color="#ffffff"><a href="tel:09174822333"  style="color:#fff; margin-right:10px;">-->
                    <!--       09174822333-->
                    <!--    </a></font>-->
                    <!--    </td>-->
                    <!--    </tr>-->
                    <!--</p>-->
                    <!--<p>-->
                    <!--    <tr><td>-->
                    <!--    <font color="#ffffff">-->
                    <!--        <a href="mail:astrolok.vedic@gmail.com"  style="color:#fff">-->
                           
                    <!--        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail text-white"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>-->
                        
                    <!--    </a>-->
                    <!--    </font>-->
                    <!--    </td><td>-->
                    <!--        <font color="#ffffff">-->
                    <!--        <a href="mail:astrolok.vedic@gmail.com"  style="color:#fff">-->
                
                    <!--        astrolok.vedic@gmail.com-->
                    <!--    </a>-->
                    <!--    </font>-->
                    <!--    </td>-->
                    <!--</p>-->
                    <!--<p>-->
                    <!--    <tr><td>-->
                    <!--    <a href="https://maps.app.goo.gl/SogMK8SxiX9eHbRc9"><font color="#ffffff"><img src="{{ config('app.js_css_url') }}/store/1/default_images/social/location.png" width="15" height="15" alt="Instagram" /> </font></a>-->
                    <!--</td><td>-->
                    <!--    <a href="https://maps.app.goo.gl/SogMK8SxiX9eHbRc9"><font color="#ffffff">312, 3rd Floor, Vikram Urbane, 25-A Mechanic Nagar Extn. Sch# 54, Indore(MP) 452010</font></a>-->
                    <!--</td></p>-->
                    
                    <!--</table>-->
                </div>
            </div>
            <div class=" col-md-2 mt-10">
                <span class="header d-block text-white font-weight-bold d-none" style="font-size: 20px !important;">Subscribe Now</span>
        
                <div class="mt-20">
                    
                    <p style="font-family: Montserrat, sans-serif; font-size: 15px; letter-spacing: 0.04695px;">
                        <font color="#ffffff">
                            Join a global astrology network with <b>100K</b> diverse members.
                        </font>
                    </p>
                    
                    <a target="_blank" href="https://www.youtube.com/@ASTTROLOKChannel?sub_confirmation=1">
                        <img src="{{ config('app.js_css_url') }}/store/1/default_images/social/youtube.png"  alt="youtube" width="100" />
                    </a>
                    
                    <!--<a target="_blank" href="https://www.youtube.com/@ASTTROLOKChannel" class="mt-10 btn btn-primary rounded-pill" style="background-color:#ed1f24;padding: 9px;font-size: 10px;">Subscribe Now</a>-->
                    <!--<p>-->
                    <!--    <a href="/pages/terms"><font color="#ffffff">Terms &amp; Conditions</font></a>-->
                    <!--</p>-->
                    
                    <!--<p>-->
                    <!--    <a href="/pages/cancellation-and-refund-policy"><font color="#ffffff">Cancellation &amp; Refund Policy</font></a>-->
                    <!--</p>-->
                </div>
            </div>
        </div> --}}
        <!--<div class="align-items-right justify-content-between mt-5" style="float: right;">-->
        


        <div class="mt-40 border-blue py-25 d-flex align-items-center justify-content-between">
            <div class="">
                <p style="font-family: Montserrat, sans-serif; font-size: 15px; letter-spacing: 0.04695px; text-align: justify;color:#fff">© All copyrights reserved 2023 Asttrolok.com  | 
                        <font color="#ffffff">
                        <a href="/pages/privacy-policy"><font color="#ffffff">Privacy Policy</font></a> 
                        </font> | <a href="/pages/terms"><font color="#ffffff">Terms &amp; Conditions</font></a> | <a href="/pages/cancellation-and-refund-policy"><font color="#ffffff">Cancellation &amp; Refund Policy</font></a>
                    </p>
                </div>
            </div>
            <!--<div class="footer-social">-->
            <!--    <a href="tel:09174822333"  style="color:#fff; margin-right:10px;">-->
            <!--               <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone text-white"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg> 09174822333-->
            <!--            </a>-->
            <!--     <a href="mail:astrolok.vedic@gmail.com"  style="color:#fff">-->
                           
            <!--                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail text-white"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>-->
            <!--            astrolok.vedic@gmail.com-->
            <!--            </a>       -->
                        
            <!--</div>-->
        </div>
    </div>

    @if(getOthersPersonalizationSettings('platform_phone_and_email_position') == 'footer')
        <div class="footer-copyright-card">
            <div class="container d-flex align-items-center justify-content-between py-15">
                <div class="font-14 text-white">© All copyrights reserved 2023 Asttrolok.com</div>

                <div class="d-flex align-items-center justify-content-center">
                    @if(!empty($generalSettings['site_phone']))
                        <div class="d-flex align-items-center text-white font-14">
                            <i data-feather="phone" width="20" height="20" class="mr-10"></i>
                            {{ $generalSettings['site_phone'] }}
                        </div>
                    @endif

                    @if(!empty($generalSettings['site_email']))
                        <div class="border-left mx-5 mx-lg-15 h-100"></div>

                        <div class="d-flex align-items-center text-white font-14">
                            <i data-feather="mail" width="20" height="20" class="mr-10"></i>
                            {{ $generalSettings['site_email'] }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

</footer>
<!--<div class="mainfooter shadow-sm11 " style="display: none !important;">-->
<!--     <nav class="mobile-nav">-->
        
<!--        <a href="/classes?sort=newest" class="bloc-icon">-->
<!--            <img src="{{ config('app.js_css_url') }}/store/1/Home/Footer/Vector.svg" alt="">-->
<!--        </a>-->
<!--        <a href="/instructors" class="bloc-icon">-->
<!--            <img src="{{ config('app.js_css_url') }}/store/1/Home/Footer/Vector (1).svg" alt="">-->
<!--        </a>-->
<!--        <a href="/" class="bloc-icon homeicon">-->
<!--            <img src="{{ config('app.js_css_url') }}/store/1/Home/Footer/home.svg" alt="">-->
<!--        </a>-->
        <!--<a href="http://examsnotice.com/remedies?sort=newest" class="bloc-icon">-->
        <!--    <img src="{{ config('app.js_css_url') }}/store/1/Home/Footer/remdy.svg" alt="">-->
        <!--</a>-->
<!--        <a href="/blog" class="bloc-icon">-->
<!--            <img src="{{ config('app.js_css_url') }}/store/1/Home/Footer/blog.svg" alt="">-->
<!--        </a>-->
<!--        <a href="#" class="bloc-icon">-->
<!--            <img src="{{ config('app.js_css_url') }}/store/1/Home/Footer/Vector (2).svg" alt="">-->
<!--        </a>-->
<!--    </nav>-->
<!--</div>-->
<script>
    jivo_api.open = function() {
    return false;
};
    
</script>