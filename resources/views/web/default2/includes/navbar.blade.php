@php
    if (empty($authUser) and auth()->check()) {
        $authUser = auth()->user();
    }

    $navBtnUrl = null;
    $navBtnText = null;

    if(request()->is('forums*')) {
        $navBtnUrl = '/forums/create-topic';
        $navBtnText = trans('update.create_new_topic');
    } else {
        $navbarButton = getNavbarButton(!empty($authUser) ? $authUser->role_id : null, empty($authUser));

        if (!empty($navbarButton)) {
            $navBtnUrl = $navbarButton->url;
            $navBtnText = $navbarButton->title;
        }
    }
@endphp

<div id="navbarVacuum"></div>
<nav id="navbar" class="navbar navbar-expand-lg navbar-light">
    @if (empty($authUser)) 
      <div class="{{ (!empty($isPanel) and $isPanel) ? 'container-fluid' : 'container'}}">
    
     @else
      @if($authUser->isUser())
  <div class="{{ (!empty($isPanel) and $isPanel) ? 'container' : 'container'}}">
        @else
         <div class="{{ (!empty($isPanel) and $isPanel) ? 'container-fluid' : 'container'}}">
        
            @endif
    @endif
        <div class="d-flex align-items-center justify-content-between w-100">
<div class="navdesk">
            <a class="navbar-brand navbar-order d-flex align-items-center justify-content-center mr-0 {{ (empty($navBtnUrl) and empty($navBtnText)) ? 'ml-auto' : '' }}" href="/">
                @if(!empty($generalSettings['logo']))
                    <img src="{{ config('app.img_dynamic_url') }}{{ $generalSettings['logo'] }}" class="img-cover" alt="site logo">
                @endif
            </a>

            <button class="navbar-toggler navbar-order" type="button" id="navbarToggle">
                <span class="navbar-toggler-icon"></span>
            </button>
             </div>
<div class="navmobile navmobiledisplay">
    
     <a class="navbar-brand navbar-order d-flex align-items-center justify-content-center mr-0 " href="/">
                @if(!empty($generalSettings['logo']))
                    <img src="{{ config('app.img_dynamic_url') }}{{ $generalSettings['logo'] }}" class="img-cover" alt="site logo">
                @endif
            </a>
<div class="top-navbar">
            
    
    <div class="xs-w-100 d-flex align-items-center justify-content-between ">
            <div class="d-flex">

                @include(getTemplate().'.includes.shopping-cart-dropdwon')

                <div class="border-left mx-5 mx-lg-15"></div>

                @include(getTemplate().'.includes.notification-dropdown')
            </div>
 <button class="navbar-toggler navbar-order" type="button" id="navbarToggle">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
            <!--{{-- User Menu --}}-->
            <!--@include('web.default.includes.top_nav.user_menu')-->
        </div>
       
        
        
    </div>
            <div class="mx-lg-30 d-none d-lg-flex flex-grow-1 navbar-toggle-content " id="navbarContent">
                <div class="navbar-toggle-header text-right d-lg-none">
                    <button class="btn-transparent" id="navbarClose">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>

                <ul class="navbar-nav mr-auto d-flex align-items-center">
                    <!--@if(!empty($categories) and count($categories))-->
                    <!--    <li class="mr-lg-25">-->
                    <!--        <div class="menu-category">-->
                    <!--            <ul>-->
                    <!--                <li class="cursor-pointer user-select-none d-flex xs-categories-toggle">-->
                    <!--                    <i data-feather="grid" width="20" height="20" class="mr-10 d-none d-lg-block"></i>-->
                    <!--                    {{ trans('categories.categories') }}-->

                    <!--                    <ul class="cat-dropdown-menu">-->
                    <!--                        @foreach($categories as $category)-->
                    <!--                        @if($category->title != "Uncategories")-->
                    <!--                            <li>-->
                    <!--                                <a href="{{ $category->getUrl() }}">-->
                    <!--                                    <div class="d-flex align-items-center">-->
                    <!--                                        <img src="{{ $category->icon }}" class="cat-dropdown-menu-icon mr-10" alt="{{ $category->title }} icon">-->
                    <!--                                        {{ $category->title }}-->
                    <!--                                    </div>-->

                    <!--                                    @if(!empty($category->subCategories) and count($category->subCategories))-->
                    <!--                                        <i data-feather="chevron-right" width="20" height="20" class="d-none d-lg-inline-block ml-10"></i>-->
                    <!--                                        <i data-feather="chevron-down" width="20" height="20" class="d-inline-block d-lg-none"></i>-->
                    <!--                                    @endif-->
                    <!--                                </a>-->

                    <!--                                @if(!empty($category->subCategories) and count($category->subCategories))-->
                    <!--                                    <ul class="sub-menu" data-simplebar @if((!empty($isRtl) and $isRtl)) data-simplebar-direction="rtl" @endif>-->
                    <!--                                        @foreach($category->subCategories as $subCategory)-->
                    <!--                                            <li>-->
                    <!--                                                <a href="{{ $subCategory->getUrl() }}">-->
                    <!--                                                    @if(!empty($subCategory->icon))-->
                    <!--                                                        <img src="{{ $subCategory->icon }}" class="cat-dropdown-menu-icon mr-10" alt="{{ $subCategory->title }} icon">-->
                    <!--                                                    @endif-->

                    <!--                                                    {{ $subCategory->title }}-->
                    <!--                                                </a>-->
                    <!--                                            </li>-->
                    <!--                                        @endforeach-->
                    <!--                                    </ul>-->
                    <!--                                @endif-->
                    <!--                            </li>-->
                    <!--                            @endif-->
                    <!--                        @endforeach-->
                    <!--                    </ul>-->
                    <!--                </li>-->
                    <!--            </ul>-->
                    <!--        </div>-->
                    <!--    </li>-->
                    <!--@endif-->
                      @if(empty($authUser))
<div class="navmobile">
   
                             <li class="nav-item">
                   
 <a href="/login" style="font-weight: 800;" class="">{{ trans('auth.login') }}</a>
                  
                            </li>
                             <li class="nav-item">
        <a href="/register" style="font-weight: 800;" class="">{{ trans('auth.register') }}</a>
                   
                            </li>
                           
                             
                             
                             
                            </div>
                              @endif
                    @if(!empty($navbarPages) and count($navbarPages))
                        @foreach($navbarPages as $navbarPage)
                           
                            @if($navbarPage['title'] !="Courses")
                            <li class="nav-item ">
                                <a class="nav-link" href="{{ $navbarPage['link'] }}">{{ $navbarPage['title'] }}</a>
                            </li>
                             @endif   
                            @if($navbarPage['title'] =="Courses")
                           
                           
                            @if(!empty($categories) and count($categories))
                        <li class="nav-item ">
                            <div class="menu-category ">
                                <ul>
                                    <li class="cursor-pointer user-select-none d-flex xs-categories-toggle ">
                                        <!--<i data-feather="grid" width="20" height="20" class="mr-10 d-none d-lg-block"></i>-->
                                        <span class="dropdown-toggle nav-item nav-link">{{ $navbarPage['title'] }}</span>
                                        

                                        <ul class="cat-dropdown-menu">
                                            @foreach($categories as $category)
                                            @if($category->title != "Uncategories")
                                                <li>
                                                    <a href="{{ $category->getUrl() }}" >
                                                        <div class="d-flex align-items-center" style="    font-size: 14px;font-weight: 600;text-align:center;color: #171347;">
                                                            <img src="{{ config('app.img_dynamic_url') }}{{ $category->icon }}" class="cat-dropdown-menu-icon mr-10" alt="{{ $category->title }} icon">
                                                            {{ $category->title }}
                                                        </div>

                                                        @if(!empty($category->subCategories) and count($category->subCategories))
                                                            <!--<i data-feather="chevron-right" width="20" height="20" class="d-none d-lg-inline-block ml-10"></i>-->
                                                            <!--<i data-feather="chevron-down" width="20" height="20" class="d-inline-block d-lg-none"></i>-->
                                                        @endif
                                                    </a>

                                                    @if(!empty($category->subCategories) and count($category->subCategories))
                                                        <!--<ul class="sub-menu" data-simplebar @if((!empty($isRtl) and $isRtl)) data-simplebar-direction="rtl" @endif>-->
                                                            @foreach($category->subCategories as $subCategory)
                                                                <!--<li>-->
                                                                <!--    <a href="{{ $subCategory->getUrl() }}">-->
                                                                <!--        @if(!empty($subCategory->icon))-->
                                                                <!--            <img src="{{ $subCategory->icon }}" class="cat-dropdown-menu-icon mr-10" alt="{{ $subCategory->title }} icon">-->
                                                                <!--        @endif-->

                                                                <!--        {{ $subCategory->title }}-->
                                                                <!--    </a>-->
                                                                <!--</li>-->
                                                            @endforeach
                                                        <!--</ul>-->
                                                    @endif
                                                </li>
                                                @endif
                                            @endforeach
                                            <li style="float:right;">
                                                <a href="/classes" >
                                                        <div class="d-flex align-items-center" style="    font-size: 12px;font-weight: 600;text-align:center;color: #171347;">
                                                            <!--<img src="https://www.asttrolok.in/store/1/Home/icon/Astrology.png" class="cat-dropdown-menu-icon mr-10" alt="all icon">-->
                                                            View All
                                                        </div>

                                                    </a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!--<li class="nav-item homeshow">-->
                        <!--        <a class="nav-link" href="{{ $navbarPage['link'] }}">{{ $navbarPage['title'] }}</a>-->
                        <!--</li>-->
                        
                        
                    @endif      
                         
                           
                           @endif
                        @endforeach
                                
                         
                                
                        <div class="navmobile">
                             <li class="nav-item">


                @if(!empty($navBtnUrl))
                   

                    <a href="{{ $navBtnUrl }}" class="">
                        {{ $navBtnText }}
                    </a>
                @endif




                            </li>
                            @if(!empty($authUser))
                            <!--<li class="nav-item">-->
                            <!--    <a href="/panel" style="font-size: 14px;font-weight: 600;" >Dashboard</a> -->
                            <!--</li>-->
                            <li class="navbar-auth-user-dropdown-item">
                    <a href="/logout" class="d-flex align-items-center w-500 py-10 text-danger font-14 bg-transparent">
                        <img src="{{ config('app.js_css_url') }}/assets/default/img/icons/user_menu/logout.svg" class="icons">
                        <span class="ml-5">{{ trans('auth.logout') }}</span>
                    </a>
                </li>
                 @endif
                            </div>
                    @endif
                </ul>
            </div>
            <div class="d-flex px-10 homehide" style="float:right !important;"><a href="tel:09174822333" class="btn btn-primary" style="    padding-right: 14px;padding-left: 14px;border-radius: 30px;"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone text-white"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg></a></div>

            <div class="nav-icons-or-start-live navbar-order navbar-order1">

                @if(!empty($navBtnUrl))
                    <a href="{{ $navBtnUrl }}" class="d-none d-lg-flex btn btn-sm btn-primary nav-start-a-live-btn">
                        {{ $navBtnText }}
                    </a>

                    <a href="{{ $navBtnUrl }}" class="d-flex d-lg-none text-primary nav-start-a-live-btn font-14">
                        {{ $navBtnText }}
                    </a>
                @endif

                <div class="d-none nav-notify-cart-dropdown top-navbar ">
                    @include(getTemplate().'.includes.shopping-cart-dropdwon')

                    <div class="border-left mx-15"></div>

                    @include(getTemplate().'.includes.notification-dropdown')
                </div>

            </div>
        </div>
    </div>
</nav>

@push('scripts_bottom')
    <script src="{{ config('app.js_css_url') }}/assets/default/js/parts/navbar.min.js"></script>
@endpush
