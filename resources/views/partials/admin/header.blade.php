@php
    $users=\Auth::user();
    $profile=asset(Storage::url('uploads/avatar/'));
    $languages=\App\Models\Utility::languages();
    $lang = isset($users->lang)?$users->lang:'en';
    $setting = \App\Models\Utility::colorset();
    $mode_setting = \App\Models\Utility::mode_layout();

    $unseenCounter=App\Models\ChMessage::where('to_id', Auth::user()->id)->where('seen', 0)->count();
@endphp
<link rel="stylesheet" href="{{ asset('assets/vendors/jquery-ui/1.9.1/jquery-ui.css') }}" />
<script src="{{ asset('js/jquery.min.js') }}"></script>
{{--<header class="dash-header  {{(isset($mode_setting['cust_theme_bg']) && $mode_setting['cust_theme_bg'] == 'on')?'transprent-bg':''}}">--}}
{{ Form::hidden('DIR',URL::asset(''), array('id' => 'DIR')) }}
{{ Form::hidden('_csrf_token',csrf_token(), array('id' => '_csrf_token')) }}
@if (isset($setting['cust_theme_bg']) && $setting['cust_theme_bg'] == 'on')
    <header class="dash-header transprent-bg">
        @else
            <header class="dash-header">
                @endif

    <div class="header-wrapper">
        <div class="me-auto dash-mob-drp">
            <ul class="list-unstyled">
                <li class="dash-h-item mob-hamburger">
                    <a href="#!" class="dash-head-link" id="mobile-collapse">
                        <div class="hamburger hamburger--arrowturn">
                            <div class="hamburger-box">
                                <div class="hamburger-inner"></div>
                            </div>
                        </div>
                    </a>
                </li>

                <li class="dropdown dash-h-item drp-company">
                    <a
                        class="dash-head-link dropdown-toggle arrow-none me-0"
                        data-bs-toggle="dropdown"
                        href="#"
                        role="button"
                        aria-haspopup="false"
                        aria-expanded="false"
                    >
                        <span class="theme-avtar">
                             <img src="{{(!empty(\Auth::user()->avatar))? asset("uploads/uploads/avatar/".\Auth::user()->avatar): asset("assets/storage/uploads/avatar/avatar.png")}}" class="img-fluid rounded-circle">
                           </span>
                        <span class="hide-mob ms-2">{{__('Hi, ')}}{{\Auth::user()->name }}!</span>
                        <i class="ti ti-chevron-down drp-arrow nocolor hide-mob"></i>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown">

                        <!-- <a href="{{ route('change.mode') }}" class="dropdown-item">
                            <i class="ti ti-circle-plus"></i>
                            <span>{{(Auth::user()->mode == 'light') ? __('Dark Mode') : __('Light Mode')}}</span>
                        </a> -->

                        <a href="{{route('profile')}}" class="dropdown-item">
                            <i class="ti ti-user"></i>
                            <span>{{__('Profile')}}</span>
                        </a>

                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();" class="dropdown-item">
                            <i class="ti ti-power"></i>
                            <span>{{__('Logout')}}</span>
                        </a>
                        <form id="frm-logout" action="{{ route('logout') }}" method="POST" class="d-none">
                            {{ csrf_field() }}
                        </form>

                    </div>
                </li>

            </ul>
        </div>
        <div class="ms-auto">
            <ul class="list-unstyled">
                @if( \Auth::user()->type !='client' && \Auth::user()->type !='super admin' )
                       <!--  <li class="dropdown dash-h-item drp-notification">
                            <a class="dash-head-link arrow-none me-0" href="{{ url('chats') }}" aria-haspopup="false" aria-expanded="false">
                                <i class="ti ti-brand-hipchat"></i>
                                <span class="bg-danger dash-h-badge dots"><span class="sr-only">{{$unseenCounter}}</span></span>
                            </a>

                        </li> -->

                    @endif





                <li class="dropdown dash-h-item drp-language">
                    <a
                        class="dash-head-link dropdown-toggle arrow-none me-0"
                        data-bs-toggle="dropdown"
                        href="#"
                        role="button"
                        aria-haspopup="false"
                        aria-expanded="false"
                    >
                        <i class="ti ti-world nocolor"></i>
                        <span class="drp-text hide-mob">{{Str::upper(isset($lang)?$lang:'en')}}</span>
                        <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">

                        @foreach($languages as $language)
                            <a href="{{route('change.language',$language)}}" class="dropdown-item @if($language == $lang) text-danger @endif">
                                <span>{{Str::upper($language)}}</span>
                            </a>
                        @endforeach
                        <h></h>

                                <a class="dropdown-item text-primary" href="{{route('manage.language',[isset($lang)?$lang:'en'])}}">{{ __('Manage Language ') }}</a>

                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@if (!\Request::is('accounting/*') && !\Request::is('human-resource/*') && !\Request::is('administrative/general-services/*') && !\Request::is('general-services/*') && !\Request::is('components/*') && !\Request::is('finance/*') && !\Request::is('for-approvals/*') && !\Request::is('treasury/*'))
<script src="{{ asset('js/common.js') }}?rand={{ rand(0000,9999) }}"></script>
@endif
<link href="{{ asset('css/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('css/yearpicker/yearpicker.css') }}" rel="stylesheet">
 <!-- Please don't remove this fields because its used for update taxpayer server -->
@if(session()->has('remort_serv_session_det'))
    {{ Form::hidden('method',Session::get('remort_serv_session_det')['table'], array('id' => 'method')) }}
    {{ Form::hidden('action',Session::get('remort_serv_session_det')['action'], array('id' => 'action')) }}
    {{ Form::hidden('method_id',Session::get('remort_serv_session_det')['id'], array('id' => 'method_id')) }}
@endif

@include('partials.admin.remote.BploRemoteFile')