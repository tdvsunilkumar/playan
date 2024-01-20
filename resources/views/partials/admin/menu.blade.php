
@php
$logo=asset('assets/storage/uploads/logo/');

$company_logo=Utility::getValByName('company_logo_dark');
$company_logos=Utility::getValByName('company_logo_light');
$setting = \App\Models\Utility::colorset();
$mode_setting = \App\Models\Utility::mode_layout();
$emailTemplate = \App\Models\EmailTemplate::first();

@endphp

<style type="text/css">
    .dash-item p{
        font-size: 10px;
        margin: unset;
    }
</style>                           

@if ($menus)
    <nav class="dash-sidebar light-sidebar">
        <div class="navbar-wrapper">
            <div class="m-header main-logo">
                <a href="#" class="b-brand">
                    @if($mode_setting['cust_darklayout'] && $mode_setting['cust_darklayout'] == 'on' )
                    <img src="{{ $logo . '/' . (isset($company_logos) && !empty($company_logos) ? $company_logos : 'new-logo.png') }}"
                    alt="{{ config('app.name', 'ERPGo') }}"  style="height: 70px; width: 200px;margin-top: 15px;" class="logo logo-lg">
                    @else
                    <img src="{{ $logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'new-logo.png') }}"
                    alt="{{ config('app.name', 'ERPGo') }}" style="height: 70px; width: 200px;margin-top: 15px;" class="logo logo-lg">
                    @endif
                </a>
            </div>
            <div class="navbar-content">
                <ul class="dash-navbar">
                @foreach ($menus['groups'] as $group)
                    <li class="dash-item dash-hasmenu {{ (Request::segment(1) == $group->slug) ? 'active dash-trigger' : '' }}">
                        @if (isset($menus['modules'][$group->id]))
                            <a href="javascript:;" class="dash-link">
                                @if(!empty($group->icon))
                                    <span class="dash-micon">
                                        <i class="{{ $group->icon }}"></i>
                                    </span>
                                @endif
                                <span class="dash-mtext {{ !empty($group->description) ? '' : 'dash-up' }}">
                                    {{ $group->name }}
                                    @if (!empty($group->description))
                                    <span class="desc">({{ $group->description }})</span>
                                    @endif
                                </span>
                                <span class="dash-arrow">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                </span>
                                <span class="clearfix"></span>
                            </a>
                            <ul class="dash-submenu">
                                @foreach ($menus['modules'][$group->id] as $module)
                                    @if (isset($menus['sub_modules'][$module->id]))
                                        <li class="dash-item dash-hasmenu {{ (Request::segment(2) == $module->slug) ? 'active dash-trigger' : '' }}">
                                            <a href="javascript:;" class="dash-link">
                                                <span>{{ $module->name }}</span> 
                                                <span class="dash-arrow">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                                </span>
                                                @if (!empty($module->description))
                                                <span class="desc">({{ $module->description }})</span>
                                                @endif
                                            </a>
                                            <ul class="dash-submenu">
                                                @foreach ($menus['sub_modules'][$module->id] as $sub_module)
                                                    <li class="dash-item {{ (Request::segment(3) == $sub_module->slug) ? 'active' : '' }}">
                                                        <a href="{{ url($sub_module->slug) }}" class="dash-link">
                                                            {{ $sub_module->name }}
                                                            @if (!empty($sub_module->description))
                                                            <span class="desc">({{ $sub_module->description }})</span>
                                                            @endif
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @else
                                        <li class="dash-item {{ (Request::segment(2) == $module->slug) ? 'active' : '' }}">
                                            <a href="{{ url($module->slug) }}" class="dash-link">
                                                {{ $module->name }}
                                                @if (!empty($module->description))
                                                <span class="desc">({{ $module->description }})</span>
                                                @endif
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @else
                            <a href="{{ url($group->slug) }}" class="dash-link">
                                @if(!empty($group->icon))
                                    <span class="dash-micon">
                                        <i class="{{ $group->icon }}"></i>
                                    </span>
                                @endif
                                <span class="dash-mtext {{ !empty($group->description) ? '' : 'dash-up' }}">
                                    {{ $group->name }}
                                    @if (!empty($group->description))
                                    <span class="desc">({{ $group->description }})</span>
                                    @endif
                                </span>
                                <span class="clearfix"></span>
                            </a>
                        @endif
                    </li>
                @endforeach
                </ul>
            </div>
        </div>
    </nav>
@endif