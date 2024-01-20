@php
    if(!Auth::check()){
        header("Location: " . URL::to('/login'), true, 302);
        exit();
    }

    $logo=asset('assets/storage/uploads/logo/');
    $company_favicon=Utility::getValByName('company_favicon');

     $SITE_RTL = Utility::getValByName('SITE_RTL');
     $setting = \App\Models\Utility::colorset();
        $color = 'theme-3';
        if (!empty($setting['color'])) {
            $color = $setting['color'];
        }
     $mode_setting = \App\Models\Utility::mode_layout();

@endphp
<!DOCTYPE html>
<html lang="en" dir="{{$SITE_RTL == 'on'?'rtl':''}}">
<head>
    <title>{{(Utility::getValByName('title_text')) ? Utility::getValByName('title_text') : config('app.name', 'ERPGO')}} - @yield('page-title')</title>
    <!-- <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script> -->

    <!-- Meta -->
    <meta name="description" content="TRIX CRM">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="_token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ url('/') }}">
    <meta name="url" content="{{ url('').'/'.config('chatify.path') }}" data-user="{{ Auth::user()->id }}">

    <link rel="icon" href="{{$logo.'/'.(isset($company_favicon) && !empty($company_favicon)?$company_favicon:'favicon.png')}}" type="image" sizes="16x16">

    <!-- Favicon icon -->
    <!-- {{--<link rel="icon" href="{{ asset('assets/images/favicon.svg') }}" type="image/x-icon"/>--}} -->
    <!-- Calendar-->
    <link rel="stylesheet" href="{{ asset('assets/css/vendor.css') }}">
    @if (
    !\Request::is('accounting/*') && 
    !\Request::is('human-resource/*') && 
    !\Request::is('administrative/general-services/*') && 
    !\Request::is('general-services/*') && 
    !\Request::is('components/*') && 
    !\Request::is('finance/*') && 
    !\Request::is('for-approvals/*') && 
    !\Request::is('treasury/journal-entries') && 
    !\Request::is('treasury/petty-cash') && 
    !\Request::is('health-and-safety/setup-data/item-managements') &&
    !\Request::is('business-permit/application') &&
    !\Request::is('reports/accounting') &&
    !\Request::is('economic-and-investment/*') &&
    !\Request::is('profile') 
    )
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/main.css') }}">
    @endif
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/flatpickr.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/plugins/animate.min.css') }}">

    <!-- font css -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}"> -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome-free-6.5.1-web/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome-free-6.5.1-web/css/regular.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome-free-6.5.1-web/css/solid.min.css') }}">
    
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/themify/themify-icons.css') }}">

    <!--bootstrap switch-->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/bootstrap-switch-button.min.css') }}">

     <!--Full calender css-->
     <link rel="stylesheet" href="{{ asset('assets/css/plugins/calender.css') }}" />
     <link rel="stylesheet" href="{{ asset('js/datetimepicker/jquery.datetimepicker.min.css') }}"/> 
    <!-- vendor css -->
    @if ($SITE_RTL == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}">
    @endif
    @if ($setting['cust_darklayout'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    @endif

    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}" id="main-style-link">

    <!--begin::append style-->
    @stack('styles')
    <!--end::append style-->
    @stack('css-page')
    <script>
        var _baseUrl = "{{ url('/') }}/";
        var _token   = "{{ csrf_token() }}";
        var _segment = "{{ request()->segment(count(request()->segments())) }}";
    </script>
</head>
<body class="{{ $color }}">
<!-- [ Pre-loader ] start -->
<div id="overlay">
  <div class="cv-spinner">
    <span class="spinner"></span>
  </div>
</div>
<!-- @if (
    !\Request::is('rptlandunitvalue') &&
    !\Request::is('rptplanttressunitvalue') &&
    !\Request::is('rptbuildingunitvalue') &&
    !\Request::is('assessmentlevel') 
    )
    @include('partials.admin.menu')
@endif -->
@include('partials.admin.menu')

<!-- [ navigation menu ] end -->
<!-- [ Header ] start -->
@include('partials.admin.header')

<!-- Modal -->
<div class="modal notification-modal fade"
     id="notification-modal"
     tabindex="-1"
     role="dialog"
     aria-hidden="true"
>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button
                    type="button"
                    class="btn-close float-end"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
                <h6 class="mt-2">
                    <i data-feather="monitor" class="me-2"></i>Desktop settings
                </h6>
                <hr/>
                <div class="form-check form-switch">
                    <input
                        type="checkbox"
                        class="form-check-input"
                        id="pcsetting1"
                        checked
                    />
                    <label class="form-check-label f-w-600 pl-1" for="pcsetting1"
                    >Allow desktop notification</label
                    >
                </div>
                <p class="text-muted ms-5">
                    you get lettest content at a time when data will updated
                </p>
                <div class="form-check form-switch">
                    <input type="checkbox" class="form-check-input" id="pcsetting2"/>
                    <label class="form-check-label f-w-600 pl-1" for="pcsetting2"
                    >Store Cookie</label
                    >
                </div>
                <h6 class="mb-0 mt-5">
                    <i data-feather="save" class="me-2"></i>Application settings
                </h6>
                <hr/>
                <div class="form-check form-switch">
                    <input type="checkbox" class="form-check-input" id="pcsetting3"/>
                    <label class="form-check-label f-w-600 pl-1" for="pcsetting3"
                    >Backup Storage</label
                    >
                </div>
                <p class="text-muted mb-4 ms-5">
                    Automaticaly take backup as par schedule
                </p>
                <div class="form-check form-switch">
                    <input type="checkbox" class="form-check-input" id="pcsetting4"/>
                    <label class="form-check-label f-w-600 pl-1" for="pcsetting4"
                    >Allow guest to print file</label
                    >
                </div>
                <h6 class="mb-0 mt-5">
                    <i data-feather="cpu" class="me-2"></i>System settings
                </h6>
                <hr/>
                <div class="form-check form-switch">
                    <input
                        type="checkbox"
                        class="form-check-input"
                        id="pcsetting5"
                        checked
                    />
                    <label class="form-check-label f-w-600 pl-1" for="pcsetting5"
                    >View other user chat</label
                    >
                </div>
                <p class="text-muted ms-5">Allow to show public user message</p>
            </div>
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-light-danger btn-sm"
                    data-bs-dismiss="modal"
                >
                    Close
                </button>
                <button type="button" class="btn btn-light-primary btn-sm">
                    Save changes
                </button>
            </div>
        </div>
    </div>
</div>
<!-- [ Header ] end -->

<!-- [ Main Content ] start -->
<div class="dash-container">
    <div class="dash-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="page-header-title">
                            <h4 class="m-b-10">@yield('page-title')</h4>
                        </div>
                        <ul class="breadcrumb">
                            @yield('breadcrumb')
                        </ul>
                    </div>
                    <div class="col">
                        @yield('action-btn')
                    </div>
                </div>
            </div>
        </div>
    @yield('content')
    <!-- [ Main Content ] end -->
    </div>
</div>
<div class="modal fade" id="commonModal" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="body" style="overflow: auto;">
            </div>
        </div>
    </div>
</div>
<div class="position-fixed top-0 end-0 p-3" style="z-index: 99999">
    <div id="liveToast" class="toast text-white  fade" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body"> </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
@include('partials.admin.footer')
@if (
    !\Request::is('accounting/*') && 
    !\Request::is('human-resource/*') && 
    !\Request::is('administrative/general-services/*') && 
    !\Request::is('general-services/*') && 
    !\Request::is('components/*') && 
    !\Request::is('finance/*') && 
    !\Request::is('for-approvals/*') && 
    !\Request::is('treasury/journal-entries') && 
    !\Request::is('treasury/petty-cash') && 
    !\Request::is('health-and-safety/setup-data/item-managements') &&
    !\Request::is('business-permit/application') &&
    !\Request::is('reports/accounting') &&
    !\Request::is('economic-and-investment/*') &&
    !\Request::is('profile')
    )
@include('Chatify::layouts.footerLinks')  @php   $uptcodes = config('constants.update_codes_land');  @endphp
@endif
<script type="text/javascript">
    function preloader(immune, background, color) {
        $("body").prepend('<div class="preloader immune white"><span class="loading-bar blue-colored"></span><i class="radial-loader blue-colored"></i></div>');
    };
    preloader(true, 'white', 'blue');
    $(document).ready( function() {
        setTimeout(function () {
            $('.preloader').fadeOut();
        }, 500 + 300 * (Math.random() * 5));
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    function getLandUpdateCodes(){
        return '<?php echo json_encode(array_flip(config('constants.update_codes_land'))) ?>';
    }
    
    $(window).scroll(function() {    
        var scroll = $(window).scrollTop();

        if (scroll >= 170) {
            $('.page-header').addClass("fixed");
        } else {
            $('.page-header').removeClass("fixed");
        }
    });
</script>
<!--begin::append script-->
@stack('scripts')
<!--end::append script-->
</body>
</html>
