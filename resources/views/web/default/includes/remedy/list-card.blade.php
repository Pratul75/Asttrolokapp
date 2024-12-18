<div class="webinar-card webinar-list webinar-list-2 d-flex mt-15">
    

    <div class="col-8 col-md-6 col-lg-8 webinar-card-body w-100 d-flex flex-column">
        <div class="d-flex align-items-center justify-content-between">
            <a href="{{ $remedy->getUrl() }}">
                <h3 class=" webinar-title1 font-weight-bold font-16 text-dark-blue">{{ clean($remedy->title,'title') }}</h3>
            </a>
            
        </div>
        <div style="max-height: 38px; overflow:hidden;font-size: 9px;">
        <p class="duration font-14 ml-5">{!! $remedy->description !!}</p>
        </div>

        

        @include(getTemplate() . '.includes.remedy.rate',['rate' => $remedy->getRate()])

        <div class="ml-10 d-flex justify-content-between mt-auto">
            <div class="d-flex justify-content-between mt-5">
                <div class="d-flex align-items-center">
                    
<svg width="18" height="18" viewBox="0 0 45 45" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M45 22.5C45 18.1875 44.8125 14.25 44.0625 12C43.3125 9.375 42.375 6.9375 40.125 4.6875C37.3125 2.0625 34.875 1.3125 31.125 0.5625C28.5 0.1875 25.3125 0 23.4375 0C22.875 0 22.3125 0 21.75 0C19.6875 0 16.6875 0.1875 13.875 0.5625C10.125 1.3125 7.5 2.25 4.875 4.6875C2.4375 6.9375 1.6875 9.375 0.9375 12C0.1875 14.25 0 18.1875 0 22.5C0 26.8125 0.1875 30.75 0.9375 33C1.6875 35.625 2.625 38.0625 4.875 40.3125C7.6875 42.9375 10.125 43.6875 13.875 44.4375C16.875 45 20.625 45 22.5 45C24.375 45 28.125 45 31.3125 44.4375C34.875 43.6875 37.5 42.9375 40.3125 40.3125C42.5625 38.25 43.5 35.8125 44.25 33C44.8125 30.75 45 26.8125 45 22.5Z" fill="url(#paint0_linear_1110_4234)"/>
<path d="M14.4898 13.4118L31.336 23.1176C31.6944 23.2941 31.8736 23.8235 31.6944 24.1765C31.5152 24.3529 31.5152 24.3529 31.336 24.5294L14.3106 34.4118C14.1314 34.4118 13.7729 34.4118 13.4145 33.8824C13.2353 33.7059 13.2353 33.5294 13.2353 33.3529V13.9412C13.2353 13.4118 13.5937 13.2353 13.9522 13.2353C14.3106 13.4118 14.4898 13.4118 14.4898 13.4118Z" fill="white"/>
<defs>
<linearGradient id="paint0_linear_1110_4234" x1="22.5" y1="0" x2="22.5" y2="45" gradientUnits="userSpaceOnUse">
<stop stop-color="#43D477"/>
<stop offset="1" stop-color="#32BA7C"/>
</linearGradient>
</defs>
</svg>


                    <span class="duration font-14 ml-5">: Videos ({{ $remedy->files->where('file_type','video')->count() }})  </span>
                </div>
                <div class="d-flex align-items-center ml-5">
                    <span class="duration font-14 ml-5"> | </span>
                </div>
                <div class="d-flex align-items-center ml-10">
                    <svg width="14" height="18" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M3.16835 0.0149536H8.86365L13.6906 5.04623V14.8321C13.6906 16.058 12.6988 17.0498 11.4772 17.0498H3.16835C1.94246 17.0498 0.950684 16.058 0.950684 14.8321V2.23262C0.950662 1.00673 1.94244 0.0149536 3.16835 0.0149536Z" fill="#E5252A"/>
<path opacity="0.302" fill-rule="evenodd" clip-rule="evenodd" d="M8.85938 0.0149536V5.00792H13.6906L8.85938 0.0149536Z" fill="white"/>
<path d="M3.41504 12.725V9.61346H4.73884C5.0666 9.61346 5.32626 9.70285 5.52206 9.88589C5.71786 10.0647 5.81577 10.3073 5.81577 10.6095C5.81577 10.9117 5.71786 11.1544 5.52206 11.3331C5.32626 11.5162 5.0666 11.6055 4.73884 11.6055H4.21102V12.725H3.41504ZM4.21102 10.9288H4.64945C4.76863 10.9288 4.86228 10.9032 4.92614 10.8436C4.98997 10.7883 5.02405 10.7117 5.02405 10.6095C5.02405 10.5074 4.99 10.4308 4.92614 10.3754C4.8623 10.3158 4.76865 10.2903 4.64945 10.2903H4.21102V10.9288ZM6.1435 12.725V9.61346H7.24596C7.46304 9.61346 7.66736 9.64325 7.8589 9.70711C8.05044 9.77095 8.22497 9.86036 8.3782 9.9838C8.53145 10.103 8.65488 10.2647 8.74427 10.469C8.82939 10.6734 8.87623 10.9075 8.87623 11.1714C8.87623 11.431 8.82942 11.6651 8.74427 11.8694C8.65488 12.0738 8.53145 12.2355 8.3782 12.3547C8.22495 12.4781 8.05044 12.5675 7.8589 12.6314C7.66736 12.6952 7.46304 12.725 7.24596 12.725H6.1435ZM6.92246 12.0482H7.15231C7.27575 12.0482 7.39068 12.0355 7.49709 12.0057C7.59924 11.9759 7.69715 11.929 7.7908 11.8652C7.88019 11.8014 7.95255 11.712 8.00362 11.5928C8.0547 11.4736 8.08025 11.3331 8.08025 11.1714C8.08025 11.0054 8.0547 10.8649 8.00362 10.7457C7.95255 10.6266 7.88019 10.5372 7.7908 10.4733C7.69715 10.4095 7.59926 10.3626 7.49709 10.3328C7.39068 10.3031 7.27575 10.2903 7.15231 10.2903H6.92246V12.0482ZM9.27635 12.725V9.61346H11.4898V10.2903H10.0723V10.7883H11.2046V11.4608H10.0723V12.725H9.27635Z" fill="white"/>
</svg>

                    <span class="date-published font-14 ml-5">: PDFs ({{ $remedy->files->where('file_type','pdf')->count()+$remedy->files->where('file_type','powerpoint')->count()+$remedy->files->where('file_type','image')->count()+$remedy->files->where('file_type','document')->count() }})</span>
                </div>
                
                
            </div>

            <div class="webinar-price-box d-flex flex-column justify-content-center align-items-center">
            
            </div>
        </div>
    </div>

    <div class="remedies-list image-box">


        <a href="{{ $remedy->getUrl() }}">
            <img src="{{ config('app.img_dynamic_url') }}{{ $remedy->getImage() }}" class="img-cover" alt="{{ $remedy->title }}" style="border-radius:10px;">
        </a>

    </div>
</div>
