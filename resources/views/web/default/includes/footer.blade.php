@php
    $socials = getSocials();
    if (!empty($socials) and count($socials)) {
        $socials = collect($socials)->sortBy('order')->toArray();
    }

    $footerColumns = getFooterColumns();
@endphp
<style>
    .active-tab-mobile {
     color: #32BA7C;
  }
  a.active-tab-mobile.bloc-icon span {
    font-weight: 800;
}
</style>
 <div class="footermobile">
    
</div>
<footer class="homehide footer bg-secondary position-relative user-select-none">
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

 <div class="mainfooter border-top" > 
      <nav class="mobile-nav text-center"> 
        <a href="/" class="bloc-icon {{ Request::path() == '/' ? 'active-tab-mobile' : '' }}" style="text-align:center !important;"> 
             {{-- <img src=" {{ config('app.img_dynamic_url') }}/store/1/default_images/footer/Home.svg" alt="">  --}}
             @if(Request::path() == '/')
             <svg width="29" height="28" viewBox="0 0 61 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M21.2625 59.9194V50.3003C21.272 49.1167 21.7488 47.9847 22.5892 47.1511C23.4295 46.3174 24.5653 45.8497 25.749 45.8497H34.7579C35.9442 45.861 37.0785 46.3379 37.9164 47.1777C38.3328 47.5849 38.6645 48.0704 38.8924 48.6063C39.1204 49.1423 39.24 49.718 39.2444 50.3003V59.9194C39.2397 60.4251 39.3357 60.9266 39.527 61.3947C39.7183 61.8629 40.001 62.2881 40.3586 62.6457C40.7162 63.0033 41.1415 63.286 41.6096 63.4773C42.0777 63.6686 42.5792 63.7647 43.0849 63.7599H49.2583C52.1141 63.7599 54.8529 62.6254 56.8722 60.6061C58.8915 58.5868 60.026 55.848 60.026 52.9922V25.6425C60.0226 24.5052 59.7703 23.3824 59.2866 22.3531C58.8029 21.3238 58.0997 20.4129 57.2264 19.6844L36.3372 3.06631C34.5611 1.66177 32.3534 0.915705 30.0894 0.954965C27.8254 0.994225 25.6448 1.81639 23.9185 3.28167L3.46001 19.6844C2.54244 20.39 1.79286 21.2904 1.2653 22.3207C0.737738 23.351 0.445339 24.4855 0.40918 25.6425V52.9922C0.40918 55.848 1.54362 58.5868 3.56295 60.6061C5.58227 62.6254 8.32106 63.7599 11.1768 63.7599H17.1708C18.1961 63.76 19.1804 63.3577 19.9121 62.6395C20.6438 61.9213 21.0642 60.9445 21.083 59.9194H21.2625Z" fill="#32BA7C"/>
                    </svg>
                @else
                
<svg width="25" height="25" viewBox="0 0 60 64" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M18.8534 50.2843L18.8533 50.2923V50.3003V57.9194H18.7102L18.6742 59.8827C18.665 60.3838 18.4595 60.8612 18.1019 61.2123C17.7442 61.5633 17.2631 61.76 16.762 61.7599H16.7616H10.7676C8.44231 61.7599 6.21223 60.8361 4.56798 59.1919C2.92373 57.5476 2 55.3176 2 52.9922V25.676C2.03084 24.8248 2.248 23.9906 2.63631 23.2322C3.02903 22.4653 3.58701 21.795 4.27004 21.2698L4.28609 21.2574L4.30189 21.2448L24.7604 4.84207L24.7822 4.82457L24.8036 4.80646C26.1775 3.64026 27.913 2.98591 29.7149 2.95466C31.516 2.92343 33.2723 3.51668 34.6856 4.6336C34.6862 4.63408 34.6868 4.63457 34.6874 4.63506L55.5526 21.234C56.1941 21.7731 56.7109 22.4451 57.0673 23.2036C57.4264 23.9679 57.614 24.8015 57.6168 25.6459V52.9922C57.6168 55.3176 56.6931 57.5476 55.0488 59.1919C53.4046 60.8361 51.1745 61.7599 48.8492 61.7599H42.6757H42.6662L42.6568 61.76C42.4169 61.7622 42.179 61.7167 41.957 61.6259C41.735 61.5352 41.5332 61.4011 41.3636 61.2315C41.194 61.0619 41.0599 60.8602 40.9692 60.6381C40.8785 60.4161 40.8329 60.1782 40.8352 59.9384L40.8353 59.9289V59.9194V50.3003V50.2927L40.8352 50.285C40.8287 49.4389 40.6549 48.6023 40.3237 47.8236C39.9942 47.0487 39.5153 46.3463 38.9144 45.7565C37.7068 44.5506 36.0745 43.866 34.3677 43.8498L34.3582 43.8497H34.3488H25.3399C25.3399 43.8497 25.3399 43.8497 25.3398 43.8497C23.6285 43.8497 21.9864 44.5259 20.7715 45.7312C19.5565 46.9364 18.8671 48.573 18.8534 50.2843Z" stroke="#61646B" stroke-width="4"/>
</svg>

                @endif
                
             <br><span class="font-12">Home</span>
         </a> 
         <a href="/classes?sort=newest" class="bloc-icon {{ (Request::path() == 'classes' or (isset($course)? $course->type == 'course': false )) ? 'active-tab-mobile' : '' }}" style="text-align:center !important;"> 
             {{-- <img src=" {{ config('app.img_dynamic_url') }}/store/1/default_images/footer/courses.svg" alt="">  --}}
             
                @if((Request::path() == 'classes' or (isset($course)? $course->type == 'course': false )))
                             <svg width="28" height="28" viewBox="0 0 66 67" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M33.2502 66.0211C26.8085 66.0282 20.5096 64.124 15.1506 60.5497C9.79159 56.9753 5.61348 51.8914 3.14509 45.9414C0.676692 39.9915 0.0289738 33.443 1.28394 27.1247C2.5389 20.8065 5.64014 15.0026 10.1951 10.4477C14.75 5.89275 20.5539 2.79153 26.8721 1.53657C33.1904 0.281601 39.7389 0.929307 45.6888 3.3977C51.6388 5.8661 56.7227 10.0442 60.297 15.4032C63.8714 20.7622 65.7756 27.0612 65.7685 33.5028C65.7495 42.1214 62.3174 50.3815 56.2231 56.4757C50.1289 62.57 41.8687 66.0021 33.2502 66.0211ZM29.661 29.8777L26.3589 40.43L36.8394 37.1279L40.1415 26.6115L29.661 29.8777ZM22.7697 46.3522C22.46 46.352 22.1537 46.2882 21.8697 46.1647C21.5856 46.0412 21.33 45.8607 21.1187 45.6343C20.8103 45.3331 20.593 44.9513 20.4914 44.5324C20.3899 44.1135 20.4082 43.6744 20.5444 43.2655L25.5334 27.2935C25.6425 26.9284 25.8407 26.5962 26.1101 26.3268C26.3795 26.0574 26.7117 25.8592 27.0768 25.7501L43.0487 20.7611C43.4577 20.6249 43.8967 20.6066 44.3156 20.7081C44.7345 20.8097 45.1164 21.0271 45.4176 21.3354C45.7286 21.6351 45.9504 22.0152 46.0581 22.4334C46.1658 22.8517 46.1553 23.2916 46.0278 23.7043L41.0029 39.6763C40.8999 40.0444 40.7038 40.3798 40.4334 40.6502C40.1631 40.9205 39.8277 41.1166 39.4595 41.2196L23.4876 46.2445C23.2498 46.2821 23.0075 46.2821 22.7697 46.2445V46.3522Z" fill="#32BA7C"/>
                </svg>

                @else
                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M13.0803 13.0806L11.8588 16.9854L15.7624 15.7627L16.9851 11.8579L13.0803 13.0806ZM10.5241 19.1939C10.2954 19.1939 10.0714 19.1041 9.90458 18.9384C9.67475 18.7074 9.59075 18.3679 9.68875 18.0587L11.5473 12.1216C11.6324 11.8462 11.8471 11.6327 12.1201 11.5476L18.0573 9.68907C18.3687 9.58991 18.7071 9.67507 18.9381 9.90491C19.1679 10.1359 19.2519 10.4754 19.1539 10.7846L17.2966 16.7217C17.2114 16.9959 16.9956 17.2106 16.7226 17.2957L10.7854 19.1542C10.6991 19.1811 10.6104 19.1939 10.5241 19.1939Z" fill="#61646B"/>
                <mask id="mask0_864_9697" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="2" y="2" width="25" height="25">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M2.3335 2.33334H26.5089V26.5087H2.3335V2.33334Z" fill="white"/>
                </mask>
                <g mask="url(#mask0_864_9697)">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M14.4213 4.08334C8.721 4.08334 4.0835 8.72201 4.0835 14.4212C4.0835 20.1215 8.721 24.759 14.4213 24.759C20.1217 24.759 24.7592 20.1215 24.7592 14.4212C24.7592 8.72201 20.1217 4.08334 14.4213 4.08334ZM14.4213 26.509C7.75616 26.509 2.3335 21.0863 2.3335 14.4212C2.3335 7.75601 7.75616 2.33334 14.4213 2.33334C21.0865 2.33334 26.5092 7.75601 26.5092 14.4212C26.5092 21.0863 21.0865 26.509 14.4213 26.509Z" fill="#61646B"/>
                </g>
                </svg>
                @endif
             <br><span class="font-12">Courses</span>
         </a> 
         <a href="/consult-with-astrologers" class="bloc-icon {{ (Request::path() == 'consult-with-astrologers' or isset($meeting)) ? 'active-tab-mobile' : '' }}" style="text-align:center !important;"> 
             {{-- <img src=" {{ config('app.img_dynamic_url') }}/store/1/default_images/footer/consultation.svg" alt=""> --}}
            
                @if(Request::path() == 'consult-with-astrologers' or isset($meeting))
                            <svg width="29" height="28" viewBox="0 0 68 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M55.1363 55.1806H13.0349C9.68698 55.1902 6.47136 53.8744 4.09061 51.5205C1.70986 49.1667 0.35754 45.9662 0.329102 42.6184V34.4709C0.338368 33.8455 0.590928 33.2484 1.03319 32.8061C1.47544 32.3638 2.07256 32.1113 2.69794 32.102C3.87219 32.1116 5.00268 31.6568 5.84305 30.8366C6.68341 30.0164 7.16555 28.8973 7.18445 27.7232C7.171 27.1543 7.04311 26.594 6.80831 26.0757C6.57352 25.5574 6.23666 25.0917 5.81791 24.7065C5.39915 24.3212 4.90706 24.0243 4.37102 23.8334C3.83497 23.6426 3.26594 23.5617 2.69794 23.5956C2.38285 23.5961 2.07096 23.5327 1.78103 23.4093C1.4911 23.2859 1.2292 23.1051 1.01106 22.8777C0.577 22.4375 0.332255 21.845 0.329102 21.2267V12.828C0.338502 11.1641 0.67551 9.5184 1.32093 7.98482C1.96636 6.45123 2.90754 5.05978 4.09072 3.88993C5.2739 2.72008 6.6759 1.79474 8.2167 1.16673C9.75751 0.538729 11.407 0.220365 13.0708 0.229819H55.1363C58.4904 0.220263 61.7114 1.54107 64.0932 3.90266C66.475 6.26425 67.8232 9.47391 67.8422 12.828V20.9755C67.8487 21.2828 67.7915 21.5882 67.6741 21.8724C67.5568 22.1566 67.3818 22.4133 67.1602 22.6265C66.7199 23.0606 66.1274 23.3053 65.5091 23.3084C64.8984 23.2532 64.2828 23.3258 63.7016 23.5217C63.1204 23.7175 62.5865 24.0323 62.1337 24.446C61.681 24.8596 61.3194 25.3631 61.072 25.9243C60.8246 26.4854 60.6968 27.092 60.6968 27.7052C60.6968 28.3185 60.8246 28.9251 61.072 29.4862C61.3194 30.0474 61.681 30.5508 62.1337 30.9645C62.5865 31.3781 63.1204 31.6929 63.7016 31.8888C64.2828 32.0846 64.8984 32.1573 65.5091 32.102C66.1312 32.1114 66.7246 32.3652 67.1611 32.8084C67.5976 33.2516 67.8422 33.8488 67.8422 34.4709V42.6184C67.8328 44.2775 67.4966 45.9186 66.853 47.4478C66.2094 48.977 65.2709 50.3645 64.0911 51.531C62.9112 52.6976 61.5131 53.6203 59.9767 54.2465C58.4402 54.8727 56.7955 55.1901 55.1363 55.1806ZM39.9181 13.2587C39.6087 13.2634 39.3015 13.2066 39.0143 13.0915C38.7271 12.9764 38.4656 12.8053 38.2452 12.5882C38.0247 12.371 37.8496 12.1122 37.7302 11.8268C37.6107 11.5414 37.5492 11.2351 37.5492 10.9257V3.31654C37.508 2.98343 37.538 2.64536 37.6373 2.32472C37.7366 2.00409 37.9029 1.70822 38.1252 1.45672C38.3475 1.20522 38.6206 1.00383 38.9267 0.865892C39.2327 0.727956 39.5645 0.656625 39.9001 0.656625C40.2358 0.656625 40.5676 0.727956 40.8736 0.865892C41.1796 1.00383 41.4529 1.20522 41.6751 1.45672C41.8974 1.70822 42.0637 2.00409 42.163 2.32472C42.2623 2.64536 42.2923 2.98343 42.2511 3.31654V10.9257C42.2511 11.5444 42.0053 12.1378 41.5677 12.5753C41.1302 13.0129 40.5368 13.2587 39.9181 13.2587ZM39.9181 37.3064C39.6087 37.3111 39.3015 37.2543 39.0143 37.1392C38.7271 37.0241 38.4656 36.853 38.2452 36.6359C38.0247 36.4188 37.8496 36.16 37.7302 35.8745C37.6107 35.5891 37.5492 35.2828 37.5492 34.9734V19.8269C37.508 19.4938 37.538 19.1557 37.6373 18.8351C37.7366 18.5145 37.9029 18.2186 38.1252 17.9671C38.3475 17.7156 38.6206 17.5142 38.9267 17.3763C39.2327 17.2383 39.5645 17.167 39.9001 17.167C40.2358 17.167 40.5676 17.2383 40.8736 17.3763C41.1796 17.5142 41.4529 17.7156 41.6751 17.9671C41.8974 18.2186 42.0637 18.5145 42.163 18.8351C42.2623 19.1557 42.2923 19.4938 42.2511 19.8269V34.9734C42.2511 35.5921 42.0053 36.1855 41.5677 36.6231C41.1302 37.0606 40.5368 37.3064 39.9181 37.3064ZM39.9181 54.4628C39.2898 54.4628 38.6873 54.2132 38.243 53.769C37.7988 53.3247 37.5492 52.7222 37.5492 52.0939V45.741C37.6202 45.1675 37.8983 44.6398 38.3313 44.2571C38.7643 43.8744 39.3223 43.6632 39.9001 43.6632C40.478 43.6632 41.036 43.8744 41.4689 44.2571C41.9019 44.6398 42.1801 45.1675 42.2511 45.741V52.0939C42.2511 52.716 42.0065 53.3132 41.57 53.7564C41.1335 54.1996 40.5401 54.4534 39.9181 54.4628Z" fill="#32BA7C"/>
                        </svg>

                @else
                <svg width="29" height="28" viewBox="0 0 29 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M16.3662 9.53272C15.8832 9.53272 15.4912 9.14072 15.4912 8.65772V5.83322C15.4912 5.35022 15.8832 4.95822 16.3662 4.95822C16.8492 4.95822 17.2412 5.35022 17.2412 5.83322V8.65772C17.2412 9.14072 16.8492 9.53272 16.3662 9.53272Z" fill="#61646B"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M16.3662 24.8315C15.8832 24.8315 15.4912 24.4395 15.4912 23.9565V21.5964C15.4912 21.1122 15.8832 20.7214 16.3662 20.7214C16.8492 20.7214 17.2412 21.1122 17.2412 21.5964V23.9565C17.2412 24.4395 16.8492 24.8315 16.3662 24.8315Z" fill="#61646B"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M16.3662 18.4627C15.8832 18.4627 15.4912 18.0707 15.4912 17.5877V11.9632C15.4912 11.4802 15.8832 11.0882 16.3662 11.0882C16.8492 11.0882 17.2412 11.4802 17.2412 11.9632V17.5877C17.2412 18.0707 16.8492 18.4627 16.3662 18.4627Z" fill="#61646B"/>
                <mask id="mask0_864_9701" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="1" y="4" width="26" height="22">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M1.6665 4.66666H26.7498V25.083H1.6665V4.66666Z" fill="white"/>
                </mask>
                <g mask="url(#mask0_864_9701)">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M3.4165 18.146V20.4082C3.4165 22.0205 4.75 23.333 6.38917 23.333H22.0272C23.6663 23.333 24.9998 22.0205 24.9998 20.4082V18.146C23.5403 17.7622 22.46 16.4415 22.46 14.8758C22.46 13.309 23.5392 11.9895 24.9998 11.6057L24.9987 9.34115C24.9987 7.72882 23.6652 6.41632 22.026 6.41632H6.39034C4.75117 6.41632 3.41767 7.72882 3.41767 9.34115L3.4165 11.6955C4.89467 12.0583 5.95634 13.3253 5.95634 14.8758C5.95634 16.4415 4.876 17.7622 3.4165 18.146ZM22.0272 25.083H6.38917C3.78517 25.083 1.6665 22.9853 1.6665 20.4082V17.3842C1.6665 16.9012 2.0585 16.5092 2.5415 16.5092C3.45967 16.5092 4.20634 15.7765 4.20634 14.8758C4.20634 14.0008 3.49 13.3405 2.5415 13.3405C2.30934 13.3405 2.0865 13.2483 1.92317 13.0838C1.75867 12.9205 1.6665 12.6965 1.6665 12.4655L1.66767 9.34115C1.66767 6.76399 3.78634 4.66632 6.39034 4.66632H22.026C24.63 4.66632 26.7487 6.76399 26.7487 9.34115L26.7498 12.3675C26.7498 12.5985 26.6577 12.8225 26.4932 12.9858C26.3298 13.1503 26.107 13.2425 25.8748 13.2425C24.9567 13.2425 24.21 13.9752 24.21 14.8758C24.21 15.7765 24.9567 16.5092 25.8748 16.5092C26.3578 16.5092 26.7498 16.9012 26.7498 17.3842V20.4082C26.7498 22.9853 24.6312 25.083 22.0272 25.083Z" fill="#61646B"/>
                </g>
                </svg>
                @endif
                <br><span class="font-12">Consultation</span>
         </a> 
        {{-- <a href="/remedies" class="bloc-icon {{ (Request::path() == 'remedies' or (isset($course)? $course->type == 'remedy': false )) ? 'active-tab-mobile' : '' }}" style="text-align:center !important;"> 
             <img src=" {{ config('app.img_dynamic_url') }}/store/1/default_images/footer/remedies.svg" alt="">  
            
                 @if((Request::path() == 'remedies' or (isset($course)? $course->type == 'remedy': false )))
                          <svg width="29" height="28" viewBox="0 0 63 62" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0.97193 43.0192C1.53712 48.2167 3.99872 53.0234 7.88577 56.5198C11.7728 60.0162 16.8125 61.9567 22.0406 61.9702H40.884C46.1183 61.9655 51.1668 60.0289 55.0614 56.5317C58.956 53.0345 61.4226 48.2229 61.9885 43.0192C61.962 43.0727 61.9211 43.1179 61.8704 43.1496C61.8197 43.1812 61.7612 43.1982 61.7014 43.1987H39.7354C39.2143 44.9994 38.1222 46.5822 36.6236 47.7085C35.125 48.8349 33.3011 49.4439 31.4264 49.4439C29.5517 49.4439 27.7278 48.8349 26.2292 47.7085C24.7306 46.5822 23.6386 44.9994 23.1174 43.1987H0.97193V43.0192ZM61.9885 41.1887V21.9505C61.9698 16.349 59.7437 10.9807 55.7929 7.00982C51.8422 3.03889 46.4853 0.78548 40.884 0.738281H22.0406C16.4239 0.757249 11.0432 2.99933 7.07495 6.97432C3.10669 10.9493 0.873714 16.3338 0.864258 21.9505V38.1738C8.90409 38.1738 16.9798 38.1738 25.1632 38.1738C25.7853 38.1832 26.3786 38.4369 26.8151 38.8801C27.2516 39.3234 27.4963 39.9205 27.4962 40.5426C27.5897 41.5202 28.0442 42.4278 28.7709 43.0883C29.4976 43.7488 30.4444 44.1148 31.4264 44.1148C32.4084 44.1148 33.3552 43.7488 34.0819 43.0883C34.8086 42.4278 35.2631 41.5202 35.3566 40.5426C35.3658 39.9173 35.6184 39.3201 36.0607 38.8778C36.5029 38.4356 37.1001 38.183 37.7255 38.1738C45.9089 38.1738 54.0205 38.1738 62.0603 38.1738V40.6862C62.0438 40.8533 62.0438 41.0216 62.0603 41.1887H61.9885Z" fill="#32BA7C"/>
                        </svg>

                @else
                <svg width="29" height="28" viewBox="0 0 29 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M4.875 10.5C4.875 7.13324 7.63324 4.375 11 4.375H18C21.3668 4.375 24.125 7.13324 24.125 10.5V16.625H16.8333C16.3501 16.625 15.9583 17.0168 15.9583 17.5C15.9583 18.3001 15.3001 18.9583 14.5 18.9583C13.6999 18.9583 13.0417 18.3001 13.0417 17.5C13.0417 17.0168 12.6499 16.625 12.1667 16.625H4.875V10.5ZM3.125 17.5C3.125 21.8332 6.66675 25.375 11 25.375H18C22.3332 25.375 25.875 21.8332 25.875 17.5V10.5C25.875 6.16675 22.3332 2.625 18 2.625H11C6.66675 2.625 3.125 6.16675 3.125 10.5V17.5ZM4.93787 18.375H11.4134C11.796 19.7189 13.0365 20.7083 14.5 20.7083C15.9635 20.7083 17.204 19.7189 17.5866 18.375H24.0621C23.6338 21.3318 21.0698 23.625 18 23.625H11C7.93014 23.625 5.36619 21.3318 4.93787 18.375Z" fill="#61646B"/>
                </svg>
                @endif
                <br><span class="font-12">Remedies</span>
         </a> --}}
         <a href="/blog" class="bloc-icon {{ (Request::path() == 'blog' or (isset($course)? $course->type == 'remedy': false )) ? 'active-tab-mobile' : '' }}" style="text-align:center !important;"> 
             {{-- <img src=" {{ config('app.img_dynamic_url') }}/store/1/default_images/footer/remedies.svg" alt="">  --}}
            
                 @if((Request::path() == 'blog' or (isset($course)? $course->type == 'remedy': false )))
                          <svg width="29" height="28" viewBox="0 0 63 62" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0.97193 43.0192C1.53712 48.2167 3.99872 53.0234 7.88577 56.5198C11.7728 60.0162 16.8125 61.9567 22.0406 61.9702H40.884C46.1183 61.9655 51.1668 60.0289 55.0614 56.5317C58.956 53.0345 61.4226 48.2229 61.9885 43.0192C61.962 43.0727 61.9211 43.1179 61.8704 43.1496C61.8197 43.1812 61.7612 43.1982 61.7014 43.1987H39.7354C39.2143 44.9994 38.1222 46.5822 36.6236 47.7085C35.125 48.8349 33.3011 49.4439 31.4264 49.4439C29.5517 49.4439 27.7278 48.8349 26.2292 47.7085C24.7306 46.5822 23.6386 44.9994 23.1174 43.1987H0.97193V43.0192ZM61.9885 41.1887V21.9505C61.9698 16.349 59.7437 10.9807 55.7929 7.00982C51.8422 3.03889 46.4853 0.78548 40.884 0.738281H22.0406C16.4239 0.757249 11.0432 2.99933 7.07495 6.97432C3.10669 10.9493 0.873714 16.3338 0.864258 21.9505V38.1738C8.90409 38.1738 16.9798 38.1738 25.1632 38.1738C25.7853 38.1832 26.3786 38.4369 26.8151 38.8801C27.2516 39.3234 27.4963 39.9205 27.4962 40.5426C27.5897 41.5202 28.0442 42.4278 28.7709 43.0883C29.4976 43.7488 30.4444 44.1148 31.4264 44.1148C32.4084 44.1148 33.3552 43.7488 34.0819 43.0883C34.8086 42.4278 35.2631 41.5202 35.3566 40.5426C35.3658 39.9173 35.6184 39.3201 36.0607 38.8778C36.5029 38.4356 37.1001 38.183 37.7255 38.1738C45.9089 38.1738 54.0205 38.1738 62.0603 38.1738V40.6862C62.0438 40.8533 62.0438 41.0216 62.0603 41.1887H61.9885Z" fill="#32BA7C"/>
                        </svg>

                @else
                <svg width="29" height="28" viewBox="0 0 29 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M4.875 10.5C4.875 7.13324 7.63324 4.375 11 4.375H18C21.3668 4.375 24.125 7.13324 24.125 10.5V16.625H16.8333C16.3501 16.625 15.9583 17.0168 15.9583 17.5C15.9583 18.3001 15.3001 18.9583 14.5 18.9583C13.6999 18.9583 13.0417 18.3001 13.0417 17.5C13.0417 17.0168 12.6499 16.625 12.1667 16.625H4.875V10.5ZM3.125 17.5C3.125 21.8332 6.66675 25.375 11 25.375H18C22.3332 25.375 25.875 21.8332 25.875 17.5V10.5C25.875 6.16675 22.3332 2.625 18 2.625H11C6.66675 2.625 3.125 6.16675 3.125 10.5V17.5ZM4.93787 18.375H11.4134C11.796 19.7189 13.0365 20.7083 14.5 20.7083C15.9635 20.7083 17.204 19.7189 17.5866 18.375H24.0621C23.6338 21.3318 21.0698 23.625 18 23.625H11C7.93014 23.625 5.36619 21.3318 4.93787 18.375Z" fill="#61646B"/>
                </svg>
                @endif
                <br><span class="font-12">Blog</span>
         </a> 
         <a href="/panel" class="bloc-icon {{ Request::path() == 'login' ? 'active-tab-mobile' : '' }}" style="text-align:center !important;"> 
             {{-- <img src=" {{ config('app.img_dynamic_url') }}/store/1/default_images/footer/Profile.svg" alt="">  --}}
             
                @if(Request::path() == 'login')
                         <svg width="19" height="24" viewBox="0 0 51 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M25.2746 63.365C19.1011 63.365 0.401367 63.365 0.401367 51.736C0.401367 41.3991 14.5787 40.1787 25.2746 40.1787C31.4121 40.1787 50.1479 40.1787 50.1479 51.8078C50.1479 62.1447 35.9345 63.365 25.2746 63.365ZM25.2746 34.2924C21.9752 34.2782 18.7541 33.2864 16.0182 31.4423C13.2822 29.5982 11.1543 26.9846 9.90312 23.9316C8.65197 20.8786 8.3338 17.5233 8.98878 14.2896C9.64376 11.0558 11.2425 8.08878 13.583 5.76328C15.9236 3.43778 18.9008 1.85819 22.1387 1.22406C25.3766 0.589935 28.7298 0.929736 31.7747 2.20053C34.8195 3.47133 37.4194 5.6161 39.2459 8.36386C41.0723 11.1116 42.0433 14.3391 42.0362 17.6385C42.0411 19.8359 41.61 22.0125 40.7681 24.0423C39.9261 26.0721 38.6899 27.9147 37.1311 29.4635C35.5722 31.0124 33.7216 32.2366 31.6865 33.0655C29.6513 33.8944 27.472 34.3114 25.2746 34.2924Z" fill="#32BA7C"/>
                        </svg>


                @else
               <svg width="19" height="24" viewBox="0 0 19 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <mask id="mask0_963_560" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="0" y="14" width="19" height="10">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M0.166504 14.9121H18.6464V23.5151H0.166504V14.9121Z" fill="white"/>
                </mask>
                <g mask="url(#mask0_963_560)">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M9.40767 16.6621C4.4365 16.6621 1.9165 17.5161 1.9165 19.2019C1.9165 20.9029 4.4365 21.7651 9.40767 21.7651C14.3777 21.7651 16.8965 20.9111 16.8965 19.2253C16.8965 17.5243 14.3777 16.6621 9.40767 16.6621ZM9.40767 23.5151C7.12217 23.5151 0.166504 23.5151 0.166504 19.2019C0.166504 15.3566 5.441 14.9121 9.40767 14.9121C11.6932 14.9121 18.6465 14.9121 18.6465 19.2253C18.6465 23.0706 13.3732 23.5151 9.40767 23.5151Z" fill="#61646B"/>
                </g>
                <mask id="mask1_963_560" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="3" y="0" width="13" height="13">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M3.21143 0.333435H15.6015V12.7218H3.21143V0.333435Z" fill="white"/>
                </mask>
                <g mask="url(#mask1_963_560)">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M9.40763 1.99898C6.9098 1.99898 4.87746 4.03014 4.87746 6.52798C4.8693 9.01764 6.88646 11.0476 9.3738 11.057L9.40763 11.89V11.057C11.9043 11.057 13.9355 9.02464 13.9355 6.52798C13.9355 4.03014 11.9043 1.99898 9.40763 1.99898ZM9.40763 12.7218H9.3703C5.9613 12.7113 3.1998 9.93114 3.21146 6.52448C3.21146 3.11198 5.99046 0.332977 9.40763 0.332977C12.8236 0.332977 15.6015 3.11198 15.6015 6.52798C15.6015 9.94398 12.8236 12.7218 9.40763 12.7218Z" fill="#61646B"/>
                </g>
                </svg>
                @endif
                <br><span class="font-12">Profile</span>
         </a> 
     </nav> 
 </div> 
