@php
$logo=asset(Storage::url('uploads/logo/'));

$company_logo=Utility::getValByName('company_logo_dark');
$company_logos=Utility::getValByName('company_logo_light');
$setting = \App\Models\Utility::colorset();
$mode_setting = \App\Models\Utility::mode_layout();
$emailTemplate     = \App\Models\EmailTemplate::first();

@endphp


<style type="text/css">
    .dash-item p{
        font-size: 10px;
        margin: unset;
    }
</style>
{{--<nav class="dash-sidebar light-sidebar {{(isset($mode_setting['cust_theme_bg']) && $mode_setting['cust_theme_bg'] == 'on')?'transprent-bg':''}}">--}}
    @if (isset($setting['cust_theme_bg']) && $setting['cust_theme_bg'] == 'on')
    <nav class="dash-sidebar light-sidebar transprent-bg">
        @else
        <nav class="dash-sidebar light-sidebar">
            @endif
            <div class="navbar-wrapper">
                <div class="m-header main-logo">
                    <a href="#" class="b-brand">
                        @if($mode_setting['cust_darklayout'] && $mode_setting['cust_darklayout'] == 'on' )
                        <img src="{{ $logo . '/' . (isset($company_logos) && !empty($company_logos) ? $company_logos : 'new-logo.png') }}"
                        alt="{{ config('app.name', 'ERPGo') }}" class="logo logo-lg">
                        @else
                        <img src="{{ $logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'new-logo.png') }}"
                        alt="{{ config('app.name', 'ERPGo') }}" class="logo logo-lg">
                        @endif
                    </a>
                </div>
                <div class="navbar-content">
                    @if(\Auth::user()->type != 'client')
                    <ul class="dash-navbar">
                        <!--------------------- Start Dashboard ----------------------------------->
                        <li class="dash-item dash-hasmenu
                        {{ ( Request::segment(1) == 'account-dashboard' || Request::segment(1) == 'income report'
                        || Request::segment(1) == 'report' || Request::segment(1) == 'reports-payroll' || Request::segment(1) == 'reports-leave' ||
                        Request::segment(1) == 'reports-monthly-attendance') ?'active dash-trigger':''}}">
                        <a href="{{route('dashboard')}}" class="dash-link "><span class="dash-micon"><i class="ti ti-home"></i></span><span class="dash-mtext">{{__('Dashboard')}}</span>
                        </a>

<!--------------------- End Dashboard ----------------------------------->

<!--------------------- Start BPLO ----------------------------------->
@if( Gate::check('manage allaplicant') || Gate::check('manage bplo applications') || Gate::check('manage bplo permit and licence') || Gate::check('manage inspection order'))
<li class="dash-item dash-hasmenu">
    <a href="#!" class="dash-link ">
        <span class="dash-micon"><i class="ti ti-layers-difference"></i></span><span class="dash-mtext">{{__('Business Permit')}}</span><span class="dash-arrow">
            <i data-feather="chevron-right"></i></span>
            <p style="padding-left: 50px;;margin-top: -8px;">(License)</p></span>
        </a>
        <ul class="dash-submenu">
            @if(Gate::check('manage allaplicant'))

                <li class="dash-item {{ (Request::segment(1) == 'allaplicant')?'active':''}}">
                 <a href="{{ route('allaplicant.index') }}" class="dash-link">{{__('Application')}}
                 </a>
                </li>
            @endif
            @if(Gate::check('manage bplo applications'))
            <li class="dash-item {{ (Request::segment(1) == 'bploapplication')?'active':''}}">
                <a href="{{ route('bploapplication.index') }}" class="dash-link">{{__('Directory')}}
                </a>
            </li>
            
            @endif
            <li class="dash-item {{ (Request::segment(1) == 'profileuser')?'active':''}}">
            <a href="{{ route('profileuser.index') }}" class="dash-link">{{__('Business Owners')}}
            </a>
           </li>
           <li class="dash-item {{ (Request::segment(1) == 'bplobusinesspermit')?'active':''}}">
            <a href="{{ route('bplobusinesspermit.index') }}" class="dash-link">{{__('Business Permit')}}
            </a>
           </li>

<!-- 
            <li class="dash-item">
                <a href="#" class="dash-link">{{__('Renewal')}}<p>of License & Permit</p>
                </a>
            </li>
            <li class="dash-item">
                <a href="#" class="dash-link">{{__('Additional')}}<p>Line of Business</p>
                </a>
            </li>
            <li class="dash-item">
                <a href="#" class="dash-link">{{__('Transfer')}}<p>of Ownership/Address</p>
                </a>
            </li>
            <li class="dash-item">
                <a href="#" class="dash-link">{{__('Inspections,')}}<p>Complaints & Violations</p>
                </a>
            </li>
            <li class="dash-item">
                <a href="#" class="dash-link">{{__('Retirement')}}<p>of Business License</p>
                </a>
            </li>
            <li class="dash-item">
                <a href="#" class="dash-link">{{__('Payment Histroy')}}<p>(Subsidiary Ledger)</p>
                </a>
            </li>
            <li class="dash-item">
                <a href="#" class="dash-link">{{__('Mayors Permit')}}<p>(Printing)</p>
                </a>
            </li>
            <li class="dash-item">
                <a href="#" class="dash-link">{{__('Inquiries')}}<p>(Business/Payments)</p>
                </a>
            </li>
            <li class="dash-item">
                <a href="#" class="dash-link">{{__('Work - Permit')}}
                </a>
            </li>
            <li class="dash-item">
                <a href="#" class="dash-link">{{__('Reports')}}
                </a>
            </li> -->

        </ul>
    </li>
@endif 

<li class="dash-item dash-hasmenu">
    <a href="#!" class="dash-link ">
        <span class="dash-micon"><i class="ti ti-layers-difference"></i></span><span class="dash-mtext">{{__('Real Property')}}</span><span class="dash-arrow">
            <i data-feather="chevron-right"></i></span>
            <!-- <p style="padding-left: 50px;;margin-top: -8px;">(License)</p></span> -->
        </a>
        <ul class="dash-submenu">
            <li class="dash-item {{ (Request::segment(1) == 'rptpropertyowner')?'active':''}}">
                     <a href="{{ route('rptpropertyowner.index') }}" class="dash-link">{{__('Owners')}}
                     </a>
            </li> 
          
            <li class="dash-item dash-hasmenu">
                <a href="#!" class="dash-link ">
                    {{__('Property Data')}}<span class="dash-arrow">
                        <i data-feather="chevron-right"></i></span>
                        <!-- <p style="padding-left: 50px;;margin-top: -8px;">(License)</p></span> -->
                    </a>
                <ul class="dash-submenu">
                   
                   <li class="dash-item {{ (Request::route()->getName() == 'rptproperty') ? ' active' : '' }}">
                        <a href="{{route('rptproperty.index')}}" class="dash-link">{{__('Land')}}
                        </a>
                    </li>
                     <li class="dash-item {{ (Request::route()->getName() == 'rptproperty') ? ' active' : '' }}">
                        <a href="{{route('rptproperty.index')}}" class="dash-link">{{__('Machinery')}}
                        </a>
                    </li>
                </ul>
            </li>
            
           
           

             <li class="dash-item {{ (Request::segment(1) == 'rptupdatecode')?'active':''}}">
             <a href="{{ route('rptupdatecode.index') }}" class="dash-link">{{__('Update Code')}}
             </a>
            </li>
           
            
            <li class="dash-item {{ (Request::segment(1) == 'rptrevisionyear')?'active':''}}">
             <a href="{{ route('revisionyear.index') }}" class="dash-link">{{__('Revision Setup')}}
             </a>
            </li>
             <li class="dash-item {{ (Request::segment(1) == 'rptappraisers')?'active':''}}">
             <a href="{{ route('rptappraisers.index') }}" class="dash-link">{{__('Appraisers')}}
             </a>
            </li>
             <li class="dash-item dash-hasmenu">
                <a href="#!" class="dash-link ">
                    {{__('Land')}}<span class="dash-arrow">
                        <i data-feather="chevron-right"></i></span>
                        <!-- <p style="padding-left: 50px;;margin-top: -8px;">(License)</p></span> -->
                    </a>
                <ul class="dash-submenu">

                   

                    <li class="dash-item {{ (Request::segment(1) == 'rptpropertykind')?'active':''}}">
                     <a href="{{ route('rptpropertykind.index') }}" class="dash-link">{{__('Kind')}}
                     </a>
                    </li>
                     <li class="dash-item {{ (Request::segment(1) == 'rptpropertyclass')?'active':''}}">
                     <a href="{{ route('rptpropertyclass.index') }}" class="dash-link">{{__('Class')}}
                     </a>
                    </li>
                    <li class="dash-item {{ (Request::segment(1) == 'rptPropertysubclassification')?'active':''}}">
                     <a href="{{ route('rptPropertysubclassification.index') }}" class="dash-link">{{__('Sub-Class')}}
                     </a>
                    </li>
                    <li class="dash-item {{ (Request::segment(1) == 'rptpropertyactualuse')?'active':''}}">
                     <a href="{{ route('rptpropertyactualuse.index') }}" class="dash-link">{{__('Actual Use')}}
                     </a>
                    </li>
                    <li class="dash-item {{ (Request::route()->getName() == 'rptlandstripping') ? ' active' : '' }}">
                    <a href="{{route('rptlandstripping.index')}}" class="dash-link">{{__('Stripping')}}
                    </a>
                   </li>
                    <li class="dash-item {{ (Request::segment(1) == 'rptplanttress')?'active':''}}">
                     <a href="{{ route('planttress.index') }}" class="dash-link">{{__('Plant|Trees')}}
                     </a>
                    </li>
                   <!--  <li class="dash-item">
                     <a href="#" class="dash-link">{{__('Location')}}
                     </a>
                    </li> -->
                </ul>
            </li>
            <li class="dash-item dash-hasmenu">
                        <a href="#!" class="dash-link ">
                            {{__('Building')}}<span class="dash-arrow">
                                <i data-feather="chevron-right"></i></span>
                                <!-- <p style="padding-left: 50px;;margin-top: -8px;">(License)</p></span> -->
                            </a>
                <ul class="dash-submenu">
                      <li class="dash-item">
                     <a href="#" class="dash-link">{{__('Kind| Structure')}}
                     </a>
                    </li>
                         
                             
                    <li class="dash-item {{ (Request::route()->getName() == 'rptbuildingtype') ? ' active' : '' }}">
                        <a href="{{route('rptbuildingtype.index')}}" class="dash-link">{{__('Type')}}
                        </a>
                    </li>
                    <li class="dash-item {{ (Request::segment(1) == 'rptbuildingroofing')?'active':''}}">
                     <a href="{{ route('buildingroofing.index') }}" class="dash-link">{{__('Roofing')}}
                     </a>
                    </li>
                    <li class="dash-item {{ (Request::route()->getName() == 'rptbuildingflooring') ? ' active' : '' }}">
                        <a href="{{route('rptbuildingflooring.index')}}" class="dash-link">{{__('Flooring')}}
                        </a>
                    </li>
                           
                    <li class="dash-item {{ (Request::route()->getName() == 'rptbuildingwalling') ? ' active' : '' }}">
                        <a href="{{route('rptbuildingwalling.index')}}" class="dash-link">{{__('Walling')}}
                        </a>
                    </li>
                    <li class="dash-item {{ (Request::route()->getName() == 'rptbuildingextraitem') ? ' active' : '' }}">
                        <a href="{{route('rptbuildingextraitem.index')}}" class="dash-link">{{__('Extra Item')}}
                        </a>
                    </li>
                     
                </ul>
            </li>
            
            <li class="dash-item dash-hasmenu">
                <a href="#!" class="dash-link ">
                    {{__('Machineries')}}<span class="dash-arrow">
                        <i data-feather="chevron-right"></i></span>
                        <!-- <p style="padding-left: 50px;;margin-top: -8px;">(License)</p></span> -->
                    </a>
                <ul class="dash-submenu">
                </ul>
            </li>
            

            
            <li class="dash-item dash-hasmenu ">
                <a class="dash-link" href="#">{{__('Unit Value')}}<span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
                <ul class="dash-submenu">
                     <li class="dash-item {{ (Request::route()->getName() == 'rptlandunitvalue') ? ' active' : '' }}">
                        <a href="{{route('rptlandunitvalue.index')}}" class="dash-link">{{__('Land ')}}
                        </a>
                   </li>
                    <li class="dash-item {{ (Request::route()->getName() == 'rptbuildingunitvalue') ? ' active' : '' }}">
                        <a href="{{route('rptbuildingunitvalue.index')}}" class="dash-link">{{__('Building')}}
                        </a>
                    </li>
                    <li class="dash-item {{ (Request::segment(1) == 'rptplanttressunitvalue')?'active':''}}">
                         <a href="{{ route('rptplanttressunitvalue.index') }}" class="dash-link">{{__('Plant/Trees ')}}
                         </a>
                    </li>
                   
                </ul>
            </li>
             
           
            
             <li class="dash-item {{ (Request::segment(1) == 'rptassessmentlevel')?'active':''}}">
             <a href="{{ route('assessmentlevel.index') }}" class="dash-link">{{__('Assessment Level')}}
             </a>
            </li>
            
           
            <li class="dash-item {{ (Request::segment(1) == 'rptctobasicseftaxrate')?'active':''}}">
             <a href="{{ route('rptctobasicseftaxrate.index') }}" class="dash-link">{{__('RPT CTO Basic Sef Taxrate')}}
             </a>
            </li>
            <li class="dash-item {{ (Request::segment(1) == 'ctopaymentschedule')?'active':''}}">
             <a href="{{ route('ctopaymentschedule.index') }}" class="dash-link">{{__('CTO Payment Schedule')}}
             </a>
            </li>
            <li class="dash-item {{ (Request::segment(1) == 'scheduledescription')?'active':''}}">
             <a href="{{ route('scheduledescription.index') }}" class="dash-link">{{__('Schedule Description')}}
             </a>
            </li>
           


            <!-- <li class="dash-item dash-hasmenu ">
                <a class="dash-link" href="#">{{__('District Selection')}}<span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
                <ul class="dash-submenu">
                    
                </ul>-->

             
           <!--  <li class="dash-item {{ (Request::route()->getName() == 'rptupdatecode') ? ' active' : '' }}">
                <a href="{{route('rptupdatecode.index')}}" class="dash-link">{{__('Tax Declaration')}}
                </a>
            </li> -->

            <!-- <li class="dash-item {{ (Request::route()->getName() == 'rptplanttressunitvalue') ? ' active' : '' }}">
                <a href="{{route('rptplanttressunitvalue.index')}}" class="dash-link">{{__('Plant Tress Unit Value')}}

                </a>
            </li>
           -->
            
           
            
             <li class="dash-item {{ (Request::route()->getName() == 'rptctopenaltyschedule') ? ' active' : '' }}">
                <a href="{{route('rptctopenaltyschedule.index')}}" class="dash-link">{{__('Rpt Cto Penalty Schedule')}}
                </a>
            </li>
            <li class="dash-item {{ (Request::route()->getName() == 'rptctopenaltytable') ? ' active' : '' }}">
                <a href="{{route('rptctopenaltytable.index')}}" class="dash-link">{{__('Rpt  Cto Penalty Table')}}
                </a>
            </li>
            
        </ul>
        
    </li>
    
  <!--------------------- End BPLO ----------------------------------->

  <li class="dash-item dash-hasmenu">
    <a href="#!" class="dash-link ">
        <span class="dash-micon"><i class="ti ti-layers-difference"></i></span><span class="dash-mtext">{{__('Certifications')}} </span></span><span class="dash-arrow">
            <i data-feather="chevron-right"></i></span>
           <p style="padding-left: 50px;margin-top: -8px;">(Inquiries)</p>
        </a>
        <ul class="dash-submenu">
            <li class="dash-item {{ (Request::segment(1) == 'rptrevisionyear')?'active':''}}">
             <a href="{{ route('rptproperty.certificateprint') }}" class="dash-link">{{__('Certificate Of Property Holding')}}
             </a>
            </li>
             <li class="dash-item {{ (Request::segment(1) == 'rptrevisionyear')?'active':''}}">
             <a href="{{ route('rptproperty.nolandcertificateprint') }}" class="dash-link">{{__('Certificate Of No Land Holding')}}
             </a>
            </li>
              <li class="dash-item">
            <a class="dash-link"  href="#">{{__('Certificate OF No Improvement')}}
            </a>
            </li>
             <li class="dash-item">
                <a class="dash-link"  href="#">{{__('Certification Record Listing')}}
                </a>
            </li>
        </ul>
    </li>
    <li class="dash-item dash-hasmenu">
    <a href="#!" class="dash-link ">
    <span class="dash-micon"><i class="ti ti-user-check"></i></span><span class="dash-mtext">{{__('Inquiries')}}</span><span class="dash-arrow">
        <i data-feather="chevron-right"></i></span>
    </a>
    <ul class="dash-submenu">
         <li class="dash-item">
            <a class="dash-link"  href="#">{{__('BY ARP NO.')}}
            </a>
        </li>
         <li class="dash-item">
            <a class="dash-link"  href="#">{{__('BY TCT NO(For Land Only))')}}
            </a>
        </li>
         <li class="dash-item">
            <a class="dash-link"  href="#">{{__('BY CCT NO(For Building Only))')}}
            </a>
        </li>
         <li class="dash-item">
            <a class="dash-link"  href="#">{{__('By Owners(Owners Name))')}}
            </a>
        </li>
         <li class="dash-item">
            <a class="dash-link"  href="#">{{__('BY TCT NO(For Land Only))')}}
            </a>
        </li>
         <li class="dash-item">
            <a class="dash-link"  href="#">{{__('BY Survey No(For Land Title Only))')}}
            </a>
        </li>
         <li class="dash-item">
            <a class="dash-link"  href="#">{{__('BY Building Kind(Classification))')}}
            </a>
        </li>
    </ul>
</li>
<li class="dash-item dash-hasmenu">
    <a href="#!" class="dash-link ">
    <span class="dash-micon"><i class="ti ti-user-check"></i></span><span class="dash-mtext">{{__('Reports')}}</span><span class="dash-arrow">
        <i data-feather="chevron-right"></i></span>
    </a>
    <ul class="dash-submenu">
         <li class="dash-item">
            <a class="dash-link"  href="#">{{__('Assessment and Tax Map Control (Roll)')}}
            </a>
        </li>
         <li class="dash-item">
            <a class="dash-link"  href="#">{{__('Report on Real Property (Assessment Quarterly)')}}
            </a>
        </li>
    </ul>
</li>
  <!--------------------- End BPLO ----------------------------------->




<!--------------------- Start CTO ----------------------------------->
@if( Gate::check('manage cto applications') || Gate::check('manage penalty rates'))
<li class="dash-item dash-hasmenu">
    <a href="#!" class="dash-link ">
    <span class="dash-micon"><i class="ti ti-user-check"></i></span><span class="dash-mtext">{{__('Treasurer')}}</span><span class="dash-arrow">
        <i data-feather="chevron-right"></i></span>
    </a>
    <ul class="dash-submenu">
        @if( Gate::check('manage cto applications'))
        <li class="dash-item {{ (Request::route()->getName() == 'bploassessment') ? ' active' : '' }}">
            <a class="dash-link"  href="{{route('bploassessment.index')}}">{{__('Applications')}}<p>(New. Renewal Addl.)</p>
            </a>
        </li>
        @endif
        @if( Gate::check('manage penalty rates'))
         <li class="dash-item {{ (Request::route()->getName() == 'bplopenaltyrates') ? ' active' : '' }}">
            <a href="{{route('bplopenaltyrates.index')}}" class="dash-link">{{__('Penalty Rates')}}<p></p>
            </a>
        </li>
        @endif
         <li class="dash-item dash-hasmenu">
            <a href="#!" class="dash-link ">
                {{__('Billing (Assessment)')}}<span class="dash-arrow">
                    <i data-feather="chevron-right"></i></span>
                    <!-- <p style="padding-left: 50px;;margin-top: -8px;">(License)</p></span> -->
                </a>
            <ul class="dash-submenu">
                <li class="dash-item ">
                 <a href="#" class="dash-link">{{__('Single Property')}}
                 </a>
                </li>
                <li class="dash-item">
                 <a href="#" class="dash-link">{{__('Multiple Property')}}
                 </a>
                </li>
            </ul>
        </li>
       
        <li class="dash-item">
         <a href="#" class="dash-link">{{__('Assessment List')}}
         </a>
        </li>
        <li class="dash-item">
         <a href="#" class="dash-link">{{__('Tax Clearance')}}
         </a>
        </li>
        <li class="dash-item">
         <a href="#" class="dash-link">{{__('Paymnet File (Subsid1ary Ledger)')}}
         </a>
        </li>
         <li class="dash-item">
         <a href="#" class="dash-link">{{__('Delinguency')}}
         </a>
        </li>
         <li class="dash-item">
         <a href="#" class="dash-link">{{__('Compromised')}}
         </a>
        </li>
         <li class="dash-item">
         <a href="#" class="dash-link">{{__('Tax Credit File (Over Payment)')}}
         </a>
        </li>
         <li class="dash-item">
         <a href="#" class="dash-link">{{__('Short Collection')}}
         </a>
        </li>
         <li class="dash-item">
         <a href="#" class="dash-link">{{__('Partial Collection')}}
         </a>
        </li>
    </ul>
</li>

@endif
     @if( Gate::check('manage planning applications'))
    <li class="dash-item dash-hasmenu">
        <a href="#!" class="dash-link ">
        <span class="dash-micon"><i class="ti ti-user-check"></i></span><span class="dash-mtext">{{__('Planning & Devt.')}}</span><span class="dash-arrow">
            <i data-feather="chevron-right"></i></span>
        </a>
        <ul class="dash-submenu">
              @if( Gate::check('manage planning applications'))
             <li class="dash-item {{ (Request::route()->getName() == 'pdobploappclearance') ? ' active' : '' }}">
                    <a  class="dash-link" href="{{route('pdobploappclearance.index')}}">{{__('Application')}}
                    </a>
            </li>
            @endif
            
        </ul>
    </li>
     @endif


<!--------------------- End CTO ----------------------------------->
<!--------------------- Start CTO ----------------------------------->

    <!--------------------- Start CTO ----------------------------------->
@if( Gate::check('manage health certificate') || Gate::check('manage app sanitary'))
<li class="dash-item dash-hasmenu">
    <a href="#!" class="dash-link ">
    <span class="dash-micon"><i class="ti ti-user-check"></i></span><span class="dash-mtext">{{__('Health & Safety')}}</span><span class="dash-arrow">
        <i data-feather="chevron-right"></i></span>
    </a>
    <ul class="dash-submenu">
        @if( Gate::check('manage health certificate'))
        <li class="dash-item {{ (Request::route()->getName() == 'hoapphealthcert') ? ' active' : '' }}">
            <a class="dash-link"  href="{{route('hoapphealthcert.index')}}">{{__('Health Certificate')}}
            </a>
        </li>
        @endif
         @if( Gate::check('manage app sanitary'))
        <li class="dash-item {{ (Request::route()->getName() == 'hoappsanitary') ? ' active' : '' }}">
            <a class="dash-link"  href="{{route('hoappsanitary.index')}}">{{__('App Sanitary')}}
            </a>
        </li>
        @endif
    </ul>
</li>

@endif
<!--------------------- End CTO ----------------------------------->

<!--------------------- Start Fire Bureau ----------------------------------->
@if( Gate::check('manage fsic applications') || Gate::check('manage inspection report') || Gate::check('manage bplo permit and licence') || Gate::check('manage pblo clearance'))
<li class="dash-item dash-hasmenu">
    <a href="#!" class="dash-link ">
        <span class="dash-micon"><i class="ti ti-box"></i></span><span class="dash-mtext">{{__('Fire Protection')}}</span><span class="dash-arrow">
            <i data-feather="chevron-right"></i></span>
        </a>
        <ul class="dash-submenu">
            @if( Gate::check('manage fsic applications'))
            <li class="dash-item {{ (Request::route()->getName() == 'bfpapplicationform') ? ' active' : '' }}">
                <a  class="dash-link" href="{{route('bfpapplicationform.index')}}">{{__('Applications')}}
                </a>
            </li>
            @endif
            
            
            <li class="dash-item dash-hasmenu ">
                <a class="dash-link" href="#">{{__('Cashiering')}}<span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
                <ul class="dash-submenu">
                    @if(Gate::check('manage bplo permit and licence'))
                    <li class="dash-item {{ (Request::segment(1) == 'bplopermitandlicence')?'active':''}}">
                        <a href="{{ route('bplopermitandlicence.index') }}" class="dash-link">{{__('Business')}}<p>(Permit and License)</p>
                        </a>
                    </li>
                    @endif
              </ul>
          </li>
            @if(Gate::check('manage inspection order')) 
                <li class="dash-item {{ request()->routeIs('bfpinspectionorder.index') ? 'active' : '' }}">
                    <a href="{{ route('bfpinspectionorder.index') }}" class="dash-link">{{__('Inspection Order')}}</a>
                </li>
            @endif
            @if(Gate::check('manage inspection report'))
            <li class="dash-item {{ (Request::route()->getName() == 'inspectionreportfile') ? ' active' : '' }}">
                <a  class="dash-link" href="{{route('inspectionreportfile')}}">{{__('Inspection Report')}}
                </a>
            </li>
            @endif 
          
        </ul>
    </li>
    @endif
   @if(Gate::check('manage environmental application'))
    <li class="dash-item dash-hasmenu">
        <a href="#!" class="dash-link ">
        <span class="dash-micon"><i class="ti ti-user-check"></i></span><span class="dash-mtext">{{__('Environmental')}}</span><span class="dash-arrow">
            <i data-feather="chevron-right"></i></span>
        </a>
        <ul class="dash-submenu">
             @if(Gate::check('manage environmental application'))
           <li class="dash-item {{ (Request::route()->getName() == 'bploappclearance') ? ' active' : '' }}">
                <a  class="dash-link" href="{{route('bploappclearance.index')}}">{{__('Application')}}
                </a>
            </li>
            @endif
            
        </ul>
    </li>
    @endif
    <!--------------------- End Fire Bureau ----------------------------------->

    <!--------------------- Start Other Details ----------------------------------->
    <!-- <li class="dash-item dash-hasmenu">
        <a href="#!" class="dash-link ">
            <span class="dash-micon"><i class="ti ti-box"></i></span><span class="dash-mtext">{{__('Other Details')}}</span><span class="dash-arrow">
                <i data-feather="chevron-right"></i></span>
        </a>
        <ul class="dash-submenu">
            <li class="dash-item">
                <a href="{{route('bplosystemparameters.index')}}" class="dash-link">{{__('System Parameters')}}
                </a>
            </li>

        </ul>
    </li>  -->






<!--------------------- End Other Details ----------------------------------->
@if(Gate::check('manage income accounts'))
        <li class="dash-item dash-hasmenu">
            <a href="{{route('incomeaccount.index')}}" class="dash-link">
                <span class="dash-micon"><i class="ti ti-box"></i></span><span class="dash-mtext">{{__('Tax Revenue')}}</span>

            </a>
        </li>
@endif



@if( Gate::check('manage check type master') || Gate::check('manage system setup') || Gate::check('setup pop receipts') || Gate::check('manage collectors') || Gate::check('manage income accounts'))
<li class="dash-item dash-hasmenu">
    <a href="#!" class="dash-link "><span class="dash-micon"><i class="ti ti-box"></i></span><span class="dash-mtext">{{__('Payment System')}} </span>
        <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
        <!-- <p style="padding-left: 50px;;margin-top: -8px;">Payment System</p></span> -->
    </a>

    <ul class="dash-submenu">
        <!-- <li class="dash-item dash-hasmenu ">
            <a class="dash-link" href="#">{{__('Main Menu')}}<span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
            <ul class="dash-submenu">
                <li class="dash-item">
                    <a href="#" class="dash-link">{{__('Business(Permit and Licenses)')}}
                    </a>
                </li>
                <li class="dash-item">
                    <a href="#" class="dash-link">{{__('Miscellaneous')}}
                    </a>
                </li>
            </ul>
        </li> -->

        <li class="dash-item dash-hasmenu ">
            <a class="dash-link" href="#">{{__('Side Menu')}}<span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
            <ul class="dash-submenu">
                <!-- <li class="dash-item">
                    <a href="#" class="dash-link">{{__('Setup Locality')}}
                    </a>
                </li>
                <li class="dash-item">
                    <a href="#" class="dash-link">{{__('Income Accounts File')}}
                    </a>
                </li> -->
                @if( Gate::check('manage check type master'))
                <li class="dash-item {{ (Request::route()->getName() == 'checktypemaster') ? ' active' : '' }}">
                    <a href="{{route('checktypemaster.index')}}" class="dash-link">{{__('Check Type Master File')}}
                    </a>
                </li>
                @endif
                @if( Gate::check('manage system setup'))

                <li class="dash-item {{ (Request::route()->getName() == 'configuration') ? ' active' : '' }}">
                    <a href="{{route('configuration.index')}}" class="dash-link">{{__('System Setup')}}<p></p>
                    </a>
                </li>
                @endif
                @if( Gate::check('manage setup pop receipts'))


                <li class="dash-item {{ (Request::route()->getName() == 'setuppopreceipts') ? ' active' : '' }}">
                    <a href="{{route('setuppopreceipts.index')}}" class="dash-link">{{__('Setup Receipts')}}
                    </a>
                </li>
                @endif
                <!-- <li class="dash-item">
                    <a href="#" class="dash-link">{{__('Edit Receipts')}}
                    </a>
                </li> -->
                @if(Gate::check('manage collectors'))
                <li class="dash-item {{ (Request::route()->getName() == 'collectors') ? ' active' : '' }}">
                    <a href="{{route('collectors.index')}}" class="dash-link">{{__('Collector’s File')}}
                    </a>
                </li>
                @endif
            </ul>
        </li>
        
        
    </ul>
</li>
@endif

<!--------------------- End Other Details ----------------------------------->

<!--------------------- Start HRM ----------------------------------->

<!--  @if(\Auth::user()->show_hrm() == 1)
@if( Gate::check('manage employee') || Gate::check('manage setsalary'))
<li class="dash-item dash-hasmenu {{ (Request::segment(1) == 'holiday-calender' || Request::segment(1) == 'reports-monthly-attendance' ||
Request::segment(1) == 'reports-leave' || Request::segment(1) == 'reports-payroll' || Request::segment(1) == 'leavetype' || Request::segment(1) == 'leave' ||
Request::segment(1) == 'attendanceemployee' || Request::segment(1) == 'document-upload' || Request::segment(1) == 'document' || Request::segment(1) == 'performanceType'  ||
Request::segment(1) == 'branch' || Request::segment(1) == 'department' || Request::segment(1) == 'designation' || Request::segment(1) == 'employee'
|| Request::segment(1) == 'leave_requests' || Request::segment(1) == 'holidays' || Request::segment(1) == 'policies' || Request::segment(1) == 'leave_calender'
|| Request::segment(1) == 'award' || Request::segment(1) == 'transfer' || Request::segment(1) == 'resignation' || Request::segment(1) == 'training' || Request::segment(1) == 'travel' ||
Request::segment(1) == 'promotion' || Request::segment(1) == 'complaint' || Request::segment(1) == 'warning'
|| Request::segment(1) == 'termination' || Request::segment(1) == 'announcement' || Request::segment(1) == 'job' || Request::segment(1) == 'job-application' ||
Request::segment(1) == 'candidates-job-applications' || Request::segment(1) == 'job-onboard' || Request::segment(1) == 'custom-question'
|| Request::segment(1) == 'interview-schedule' || Request::segment(1) == 'career' || Request::segment(1) == 'holiday' || Request::segment(1) == 'setsalary' ||
Request::segment(1) == 'payslip' || Request::segment(1) == 'paysliptype' || Request::segment(1) == 'company-policy' || Request::segment(1) == 'job-stage'
|| Request::segment(1) == 'job-category' || Request::segment(1) == 'terminationtype' || Request::segment(1) == 'awardtype' || Request::segment(1) == 'trainingtype' ||
Request::segment(1) == 'goaltype' || Request::segment(1) == 'paysliptype' || Request::segment(1) == 'allowanceoption' || Request::segment(1) == 'competencies' || Request::segment(1) == 'loanoption'
|| Request::segment(1) == 'deductionoption')?'active dash-trigger':''}}">
<a href="#!" class="dash-link "><span class="dash-micon"><i class="ti ti-user"></i></span><span class="dash-mtext">{{__('HRM System')}}</span><span class="dash-arrow">
<i data-feather="chevron-right"></i></span>
</a>
<ul class="dash-submenu">
<li class="dash-item  {{ (Request::segment(1) == 'employee' ? 'active dash-trigger' : '')}}   ">
@if(\Auth::user()->type =='Employee')
@php
$employee=App\Models\Employee::where('user_id',\Auth::user()->id)->first();
@endphp
<a class="dash-link" href="{{route('employee.show',\Illuminate\Support\Facades\Crypt::encrypt(\Auth::user()->id))}}">{{__('Employee')}}</a>
@else
<a href="{{route('employee.index')}}" class="dash-link">
{{ __('Employee Setup') }}
</a>
@endif
</li>
@if( Gate::check('manage set salary') || Gate::check('manage pay slip'))
<li class="dash-item dash-hasmenu  {{ (Request::segment(1) == 'setsalary' || Request::segment(1) == 'payslip') ? 'active dash-trigger' : ''}}">
<a class="dash-link" href="#">{{__('Payroll Setup')}}<span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
<ul class="dash-submenu">
@can('manage set salary')
<li class="dash-item {{ (request()->is('setsalary*') ? 'active' : '')}}">
<a class="dash-link" href="{{ route('setsalary.index') }}">{{__('Set salary')}}</a>
</li>
@endcan
@can('manage pay slip')
<li class="dash-item {{ (request()->is('payslip*') ? 'active' : '')}}">
<a class="dash-link" href="{{route('payslip.index')}}">{{__('Payslip')}}</a>
</li>
@endcan
</ul>
</li>
@endif

@if( Gate::check('manage leave') || Gate::check('manage attendance'))
<li class="dash-item dash-hasmenu  {{ (Request::segment(1) == 'leave' || Request::segment(1) == 'attendanceemployee') ? 'active dash-trigger' :''}}">
<a class="dash-link" href="#">{{__('Leave Management Setup')}}<span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
<ul class="dash-submenu">
@can('manage leave')
<li class="dash-item {{ (Request::route()->getName() == 'leave.index') ?'active' :''}}">
<a class="dash-link" href="{{route('leave.index')}}">{{__('Manage Leave')}}</a>
</li>
@endcan
@can('manage attendance')
<li class="dash-item dash-hasmenu {{ (Request::segment(1) == 'attendanceemployee') ? 'active dash-trigger' : ''}}" href="#navbar-attendance" data-toggle="collapse" role="button" aria-expanded="{{ (Request::segment(1) == 'attendanceemployee') ? 'true' : 'false'}}">
<a class="dash-link" href="#">{{__('Attendance')}}<span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
<ul class="dash-submenu">
<li class="dash-item {{ (Request::route()->getName() == 'attendanceemployee.index' ? 'active' : '')}}">
<a class="dash-link" href="{{route('attendanceemployee.index')}}">{{__('Mark Attendance')}}</a>
</li>
@can('create attendance')
<li class="dash-item {{ (Request::route()->getName() == 'attendanceemployee.bulkattendance' ? 'active' : '')}}">
<a class="dash-link" href="{{ route('attendanceemployee.bulkattendance') }}">{{__('Bulk Attendance')}}</a>
</li>
@endcan
</ul>
</li>
@endcan
</ul>
</li>
@endif

@if( Gate::check('manage indicator') || Gate::check('manage appraisal') || Gate::check('manage goal tracking'))
<li class="dash-item dash-hasmenu {{ (Request::segment(1) == 'indicator' || Request::segment(1) == 'appraisal' || Request::segment(1) == 'goaltracking') ? 'active dash-trigger' : ''}}" href="#navbar-performance" data-toggle="collapse" role="button" aria-expanded="{{ (Request::segment(1) == 'indicator' || Request::segment(1) == 'appraisal' || Request::segment(1) == 'goaltracking') ? 'true' : 'false'}}">
<a class="dash-link" href="#">{{__('Performance Setup')}}<span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
<ul class="dash-submenu {{ (Request::segment(1) == 'indicator' || Request::segment(1) == 'appraisal' || Request::segment(1) == 'goaltracking') ? 'show' : 'collapse'}}">
@can('manage indicator')
<li class="dash-item {{ (request()->is('indicator*') ? 'active' : '')}}">
<a class="dash-link" href="{{route('indicator.index')}}">{{__('Indicator')}}</a>
</li>
@endcan
@can('manage appraisal')
<li class="dash-item {{ (request()->is('appraisal*') ? 'active' : '')}}">
<a class="dash-link" href="{{route('appraisal.index')}}">{{__('Appraisal')}}</a>
</li>
@endcan
@can('manage goal tracking')
<li class="dash-item  {{ (request()->is('goaltracking*') ? 'active' : '')}}">
<a class="dash-link" href="{{route('goaltracking.index')}}">{{__('Goal Tracking')}}</a>
</li>
@endcan
</ul>
</li>
@endif

@if( Gate::check('manage training') || Gate::check('manage trainer'))
<li class="dash-item dash-hasmenu {{ (Request::segment(1) == 'trainer' || Request::segment(1) == 'training') ? 'active dash-trigger' : ''}}" href="#navbar-training" data-toggle="collapse" role="button" aria-expanded="{{ (Request::segment(1) == 'trainer' || Request::segment(1) == 'training') ? 'true' : 'false'}}">
<a class="dash-link" href="#">{{__('Training Setup')}}<span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
<ul class="dash-submenu">
@can('manage training')
<li class="dash-item {{ (request()->is('training*') ? 'active' : '')}}">
<a class="dash-link" href="{{route('training.index')}}">{{__('Training List')}}</a>
</li>
@endcan
@can('manage trainer')
<li class="dash-item {{ (request()->is('trainer*') ? 'active' : '')}}">
<a class="dash-link" href="{{route('trainer.index')}}">{{__('Trainer')}}</a>
</li>
@endcan

</ul>
</li>
@endif

@if( Gate::check('manage job') || Gate::check('create job') || Gate::check('manage job application') || Gate::check('manage custom question') || Gate::check('show interview schedule') || Gate::check('show career'))
<li class="dash-item dash-hasmenu {{ (Request::segment(1) == 'job' || Request::segment(1) == 'job-application' || Request::segment(1) == 'candidates-job-applications' || Request::segment(1) == 'job-onboard' || Request::segment(1) == 'custom-question' || Request::segment(1) == 'interview-schedule' || Request::segment(1) == 'career') ? 'active dash-trigger' : ''}}    ">
<a class="dash-link" href="#">{{__('Recruitment Setup')}}<span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
<ul class="dash-submenu">
@can('manage job')
<li class="dash-item {{ (Request::route()->getName() == 'job.index' || Request::route()->getName() == 'job.create' || Request::route()->getName() == 'job.edit' || Request::route()->getName() == 'job.show'   ? 'active' : '')}}">
<a class="dash-link" href="{{route('job.index')}}">{{__('Jobs')}}</a>
</li>
@endcan
@can('create job')
<li class="dash-item {{ ( Request::route()->getName() == 'job.create' ? 'active' : '')}} ">
<a class="dash-link" href="{{route('job.create')}}">{{__('Job Create')}}</a>
</li>
@endcan
@can('manage job application')
<li class="dash-item {{ (request()->is('job-application*') ? 'active' : '')}}">
<a class="dash-link" href="{{route('job-application.index')}}">{{__('Job Application')}}</a>
</li>
@endcan
@can('manage job application')
<li class="dash-item {{ (request()->is('candidates-job-applications') ? 'active' : '')}}">
<a class="dash-link" href="{{route('job.application.candidate')}}">{{__('Job Candidate')}}</a>
</li>
@endcan
@can('manage job application')
<li class="dash-item {{ (request()->is('job-onboard*') ? 'active' : '')}}">
<a class="dash-link" href="{{route('job.on.board')}}">{{__('Job On-boarding')}}</a>
</li>
@endcan
@can('manage custom question')
<li class="dash-item  {{ (request()->is('custom-question*') ? 'active' : '')}}">
<a class="dash-link" href="{{route('custom-question.index')}}">{{__('Custom Question')}}</a>
</li>
@endcan
@can('show interview schedule')
<li class="dash-item {{ (request()->is('interview-schedule*') ? 'active' : '')}}">
<a class="dash-link" href="{{route('interview-schedule.index')}}">{{__('Interview Schedule')}}</a>
</li>
@endcan
@can('show career')
<li class="dash-item {{ (request()->is('career*') ? 'active' : '')}}">
<a class="dash-link" href="{{route('career',[\Auth::user()->creatorId(),'en'])}}">{{__('Career')}}</a></li>
@endcan
</ul>
</li>
@endif

@if( Gate::check('manage award') || Gate::check('manage transfer') || Gate::check('manage resignation') || Gate::check('manage travel') || Gate::check('manage promotion') || Gate::check('manage complaint') || Gate::check('manage warning') || Gate::check('manage termination') || Gate::check('manage announcement') || Gate::check('manage holiday') )
<li class="dash-item dash-hasmenu {{ (Request::segment(1) == 'holiday-calender' || Request::segment(1) == 'holiday' || Request::segment(1) == 'policies' || Request::segment(1) == 'award' || Request::segment(1) == 'transfer' || Request::segment(1) == 'resignation' || Request::segment(1) == 'travel' || Request::segment(1) == 'promotion' || Request::segment(1) == 'complaint' || Request::segment(1) == 'warning' || Request::segment(1) == 'termination' || Request::segment(1) == 'announcement' || Request::segment(1) == 'competencies') ? 'active dash-trigger' : ''}}">
<a class="dash-link" href="#">{{__('HR Admin Setup')}}<span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
<ul class="dash-submenu">
@can('manage award')
<li class="dash-item {{ (request()->is('award*') ? 'active' : '')}}">
<a class="dash-link" href="{{route('award.index')}}">{{__('Award')}}</a>
</li>
@endcan
@can('manage transfer')
<li class="dash-item  {{ (request()->is('transfer*') ? 'active' : '')}}">
<a class="dash-link" href="{{route('transfer.index')}}">{{__('Transfer')}}</a>
</li>
@endcan
@can('manage resignation')
<li class="dash-item {{ (request()->is('resignation*') ? 'active' : '')}}">
<a class="dash-link" href="{{route('resignation.index')}}">{{__('Resignation')}}</a>
</li>
@endcan
@can('manage travel')
<li class="dash-item {{ (request()->is('travel*') ? 'active' : '')}}">
<a class="dash-link" href="{{route('travel.index')}}">{{__('Trip')}}</a>
</li>
@endcan
@can('manage promotion')
<li class="dash-item {{ (request()->is('promotion*') ? 'active' : '')}}">
<a class="dash-link" href="{{route('promotion.index')}}">{{__('Promotion')}}</a>
</li>
@endcan
@can('manage complaint')
<li class="dash-item {{ (request()->is('complaint*') ? 'active' : '')}}">
<a class="dash-link" href="{{route('complaint.index')}}">{{__('Complaints')}}</a>
</li>
@endcan
@can('manage warning')
<li class="dash-item {{ (request()->is('warning*') ? 'active' : '')}}">
<a class="dash-link" href="{{route('warning.index')}}">{{__('Warning')}}</a>
</li>
@endcan
@can('manage termination')
<li class="dash-item {{ (request()->is('termination*') ? 'active' : '')}}">
<a class="dash-link" href="{{route('termination.index')}}">{{__('Termination')}}</a>
</li>
@endcan
@can('manage announcement')
<li class="dash-item {{ (request()->is('announcement*') ? 'active' : '')}}">
<a class="dash-link" href="{{route('announcement.index')}}">{{__('Announcement')}}</a>
</li>
@endcan
@can('manage holiday')
<li class="dash-item {{ (request()->is('holiday*') || request()->is('holiday-calender') ? 'active' : '')}}">
<a class="dash-link" href="{{route('holiday.index')}}">{{__('Holidays')}}</a>
</li>
@endcan
</ul>
</li>
@endif



@can('manage event')
<li class="dash-item {{ (request()->is('event*') ? 'active' : '')}}">
<a class="dash-link" href="{{route('event.index')}}">{{__('Event Setup')}}</a>
</li>
@endcan
@can('manage meeting')
<li class="dash-item {{ (request()->is('meeting*') ? 'active' : '')}}">
<a class="dash-link" href="{{route('meeting.index')}}">{{__('Meeting')}}</a>
</li>
@endcan
@can('manage assets')
<li class="dash-item {{ (request()->is('account-assets*') ? 'active' : '')}}">
<a class="dash-link" href="{{route('account-assets.index')}}">{{__('Employees Asset Setup ')}}</a>
</li>
@endcan
@can('manage document')
<li class="dash-item {{ (request()->is('document-upload*') ? 'active' : '')}}">
<a class="dash-link" href="{{route('document-upload.index')}}">{{__('Document Setup')}}</a>
</li>
@endcan
@can('manage company policy')
<li class="dash-item {{ (request()->is('company-policy*') ? 'active' : '')}}">
<a class="dash-link" href="{{route('company-policy.index')}}">{{__('Company policy')}}</a>
</li>
@endcan

<li class="dash-item {{ (Request::segment(1) == 'leavetype' || Request::segment(1) == 'document' || Request::segment(1) == 'performanceType' || Request::segment(1) == 'branch' || Request::segment(1) == 'department'
|| Request::segment(1) == 'designation' || Request::segment(1) == 'job-stage'|| Request::segment(1) == 'performanceType'  || Request::segment(1) == 'job-category' || Request::segment(1) == 'terminationtype' ||
Request::segment(1) == 'awardtype' || Request::segment(1) == 'trainingtype' || Request::segment(1) == 'goaltype' || Request::segment(1) == 'paysliptype' ||
Request::segment(1) == 'allowanceoption' || Request::segment(1) == 'loanoption' || Request::segment(1) == 'deductionoption') ? 'active dash-trigger' : ''}}">
<a class="dash-link" href="{{route('branch.index')}}">{{__('HRM System Setup')}}</a>
</li>
</ul>
</li>
@endif
@endif -->

<!--------------------- End HRM ----------------------------------->

<!--------------------- Start Account ----------------------------------->

<!--      @if(\Auth::user()->show_account() == 1)
@if( Gate::check('manage customer') || Gate::check('manage vender') || Gate::check('manage customer') || Gate::check('manage vender') ||
Gate::check('manage proposal') ||  Gate::check('manage bank account') ||  Gate::check('manage bank transfer') ||  Gate::check('manage invoice')
||  Gate::check('manage revenue') ||  Gate::check('manage credit note') ||  Gate::check('manage bill')  ||  Gate::check('manage payment') ||
Gate::check('manage debit note') || Gate::check('manage chart of account') ||  Gate::check('manage journal entry') ||   Gate::check('balance sheet report')
|| Gate::check('ledger report') ||  Gate::check('trial balance report')  )
<li class="dash-item dash-hasmenu {{ (Request::route()->getName() == 'print-setting' || Request::segment(1) == 'customer' || Request::segment(1) == 'vender' || Request::segment(1) == 'proposal' || Request::segment(1) == 'bank-account' || Request::segment(1) == 'bank-transfer' || Request::segment(1) == 'invoice' || Request::segment(1) == 'revenue' || Request::segment(1) == 'credit-note' || Request::segment(1) == 'taxes' || Request::segment(1) == 'product-category' ||
Request::segment(1) == 'product-unit' || Request::segment(1) == 'payment-method' || Request::segment(1) == 'custom-field' || Request::segment(1) == 'chart-of-account-type' || (Request::segment(1) == 'transaction') &&  Request::segment(2) != 'ledger' &&  Request::segment(2) != 'balance-sheet' &&  Request::segment(2) != 'trial-balance' || Request::segment(1) == 'goal' || Request::segment(1) == 'budget'|| Request::segment(1) ==
'chart-of-account' || Request::segment(1) == 'journal-entry' || Request::segment(2) == 'ledger' ||  Request::segment(2) == 'balance-sheet' ||  Request::segment(2) == 'trial-balance' || Request::segment(1) == 'bill' || Request::segment(1) == 'payment' || Request::segment(1) == 'debit-note')?' active dash-trigger':''}}">
<a href="#!" class="dash-link"><span class="dash-micon"><i class="ti ti-box"></i></span><span class="dash-mtext">{{__('Accounting System ')}}</span><span class="dash-arrow">
<i data-feather="chevron-right"></i></span>
</a>
<ul class="dash-submenu">
@if(Gate::check('manage customer'))
<li class="dash-item {{ (Request::segment(1) == 'customer')?'active':''}}">
<a class="dash-link" href="{{route('customer.index')}}">{{__('Customer')}}</a>
</li>
@endif
@if(Gate::check('manage vender'))
<li class="dash-item {{ (Request::segment(1) == 'vender')?'active':''}}">
<a class="dash-link" href="{{ route('vender.index') }}">{{__('Vendor')}}</a>
</li>
@endif
@if(Gate::check('manage proposal'))
<li class="dash-item {{ (Request::segment(1) == 'proposal')?'active':''}}">
<a class="dash-link" href="{{ route('proposal.index') }}">{{__('Proposal')}}</a>
</li>
@endif
@if( Gate::check('manage bank account') ||  Gate::check('manage bank transfer'))
<li class="dash-item dash-hasmenu {{(Request::segment(1) == 'bank-account' || Request::segment(1) == 'bank-transfer')? 'active dash-trigger' :''}}">
<a class="dash-link" href="#">{{__('Banking')}}<span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
<ul class="dash-submenu">
<li class="dash-item {{ (Request::route()->getName() == 'bank-account.index' || Request::route()->getName() == 'bank-account.create' || Request::route()->getName() == 'bank-account.edit') ? ' active' : '' }}">
<a class="dash-link" href="{{ route('bank-account.index') }}">{{__('Account')}}</a>
</li>
<li class="dash-item {{ (Request::route()->getName() == 'bank-transfer.index' || Request::route()->getName() == 'bank-transfer.create' || Request::route()->getName() == 'bank-transfer.edit') ? ' active' : '' }}">
<a class="dash-link" href="{{route('bank-transfer.index')}}">{{__('Transfer')}}</a>
</li>
</ul>
</li>
@endif
@if( Gate::check('manage invoice') ||  Gate::check('manage revenue') ||  Gate::check('manage credit note'))
<li class="dash-item dash-hasmenu {{(Request::segment(1) == 'invoice' || Request::segment(1) == 'revenue' || Request::segment(1) == 'credit-note')? 'active dash-trigger' :''}}">
<a class="dash-link" href="#">{{__('Income')}}<span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
<ul class="dash-submenu">
<li class="dash-item {{ (Request::route()->getName() == 'invoice.index' || Request::route()->getName() == 'invoice.create' || Request::route()->getName() == 'invoice.edit' || Request::route()->getName() == 'invoice.show') ? ' active' : '' }}">
<a class="dash-link" href="{{ route('invoice.index') }}">{{__('Invoice')}}</a>
</li>
<li class="dash-item {{ (Request::route()->getName() == 'revenue.index' || Request::route()->getName() == 'revenue.create' || Request::route()->getName() == 'revenue.edit') ? ' active' : '' }}">
<a class="dash-link" href="{{route('revenue.index')}}">{{__('Revenue')}}</a>
</li>
<li class="dash-item {{ (Request::route()->getName() == 'credit.note' ) ? ' active' : '' }}">
<a class="dash-link" href="{{route('credit.note')}}">{{__('Credit Note')}}</a>
</li>
</ul>
</li>
@endif
@if( Gate::check('manage bill')  ||  Gate::check('manage payment') ||  Gate::check('manage debit note'))
<li class="dash-item dash-hasmenu {{(Request::segment(1) == 'bill' || Request::segment(1) == 'payment' || Request::segment(1) == 'debit-note')? 'active dash-trigger' :''}}">
<a class="dash-link" href="#">{{__('Expense')}}<span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
<ul class="dash-submenu">
<li class="dash-item {{ (Request::route()->getName() == 'bill.index' || Request::route()->getName() == 'bill.create' || Request::route()->getName() == 'bill.edit' || Request::route()->getName() == 'bill.show') ? ' active' : '' }}">
<a class="dash-link" href="{{ route('bill.index') }}">{{__('Bill')}}</a>
</li>
<li class="dash-item {{ (Request::route()->getName() == 'payment.index' || Request::route()->getName() == 'payment.create' || Request::route()->getName() == 'payment.edit') ? ' active' : '' }}">
<a class="dash-link" href="{{route('payment.index')}}">{{__('Payment')}}</a>
</li>
<li class="dash-item  {{ (Request::route()->getName() == 'debit.note' ) ? ' active' : '' }}">
<a class="dash-link" href="{{route('debit.note')}}">{{__('Debit Note')}}</a>
</li>
</ul>
</li>
@endif
@if( Gate::check('manage chart of account') ||  Gate::check('manage journal entry') ||   Gate::check('balance sheet report') ||  Gate::check('ledger report') ||  Gate::check('trial balance report'))
<li class="dash-item dash-hasmenu {{(Request::segment(1) == 'chart-of-account' || Request::segment(1) == 'journal-entry' || Request::segment(2) == 'ledger' ||  Request::segment(2) == 'balance-sheet' ||  Request::segment(2) == 'trial-balance')? 'active dash-trigger' :''}}">
<a class="dash-link" href="#">{{__('Double Entry')}}<span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
<ul class="dash-submenu">
<li class="dash-item {{ (Request::route()->getName() == 'chart-of-account.index') ? ' active' : '' }}">
<a class="dash-link" href="{{ route('chart-of-account.index') }}">{{__('Chart of Accounts')}}</a>
</li>
<li class="dash-item {{ (Request::route()->getName() == 'journal-entry.edit' || Request::route()->getName() == 'journal-entry.create' || Request::route()->getName() == 'journal-entry.index' || Request::route()->getName() == 'journal-entry.show') ? ' active' : '' }}">
<a class="dash-link" href="{{ route('journal-entry.index') }}">{{__('Journal Account')}}</a>
</li>
<li class="dash-item {{ (Request::route()->getName() == 'report.ledger' ) ? ' active' : '' }}">
<a class="dash-link" href="{{route('report.ledger')}}">{{__('Ledger Summary')}}</a>
</li>
<li class="dash-item {{ (Request::route()->getName() == 'report.balance.sheet' ) ? ' active' : '' }}">
<a class="dash-link" href="{{route('report.balance.sheet')}}">{{__('Balance Sheet')}}</a>
</li>
<li class="dash-item {{ (Request::route()->getName() == 'trial.balance' ) ? ' active' : '' }}">
<a class="dash-link" href="{{route('trial.balance')}}">{{__('Trial Balance')}}</a>
</li>
</ul>
</li>
@endif
@if(\Auth::user()->type =='company')
<li class="dash-item {{ (Request::segment(1) == 'budget')?'active':''}}">
<a class="dash-link" href="{{ route('budget.index') }}">{{__('Budget Planner')}}</a>
</li>
@endif
@if(Gate::check('manage goal'))
<li class="dash-item {{ (Request::segment(1) == 'goal')?'active':''}}">
<a class="dash-link" href="{{ route('goal.index') }}">{{__('Financial Goal')}}</a>
</li>
@endif
@if(Gate::check('manage constant tax') || Gate::check('manage constant category') ||Gate::check('manage constant unit') ||Gate::check('manage constant payment method') ||Gate::check('manage constant custom field') )
<li class="dash-item {{(Request::segment(1) == 'taxes' || Request::segment(1) == 'product-category' || Request::segment(1) == 'product-unit' || Request::segment(1) == 'payment-method' || Request::segment(1) == 'custom-field' || Request::segment(1) == 'chart-of-account-type')? 'active dash-trigger' :''}}">
<a class="dash-link" href="{{ route('taxes.index') }}">{{__('Accounting Setup')}}</a>
{{--                                            <ul class="dash-submenu">--}}
{{--                                                @can('manage constant tax')--}}
{{--                                                    <li class="dash-item {{ (Request::route()->getName() == 'taxes.index' ) ? ' active' : '' }}">--}}
{{--                                                        <a class="dash-link" href="{{ route('taxes.index') }}">{{__('Taxes')}}</a>--}}
{{--                                                    </li>--}}
{{--                                                @endcan--}}
{{--                                                @can('manage constant category')--}}
{{--                                                    <li class="dash-item {{ (Request::route()->getName() == 'product-category.index' ) ? 'active' : '' }}">--}}
{{--                                                        <a class="dash-link" href="{{route('product-category.index')}}">{{__('Category')}}</a>--}}
{{--                                                    </li>--}}
{{--                                                @endcan--}}
{{--                                                @can('manage constant unit')--}}
{{--                                                    <li class="dash-item {{ (Request::route()->getName() == 'product-unit.index' ) ? ' active' : '' }}">--}}
{{--                                                        <a class="dash-link" href="{{route('product-unit.index')}}">{{__('Unit')}}</a>--}}
{{--                                                    </li>--}}
{{--                                                @endcan--}}
{{--                                                @can('manage constant custom field')--}}
{{--                                                    <li class="dash-item {{ (Request::route()->getName() == 'custom-field.index' ) ? 'active' : '' }}   ">--}}
{{--                                                        <a class="dash-link" href="{{route('custom-field.index')}}">{{__('Custom Field')}}</a>--}}
{{--                                                    </li>--}}
{{--                                                @endcan--}}

{{--                                            </ul>--}}
</li>
@endif

@if(Gate::check('manage print settings'))
<li class="dash-item {{ (Request::route()->getName() == 'print-setting') ? ' active' : '' }}">
<a class="dash-link" href="{{ route('print.setting') }}">{{__('Print Settings')}}</a>
</li>
@endif

</ul>
</li>
@endif
@endif -->

<!--------------------- End Account ----------------------------------->

<!--------------------- Start CRM ----------------------------------->
<!-- 
@if(\Auth::user()->show_crm() == 1)
@if( Gate::check('manage lead') || Gate::check('manage deal') || Gate::check('manage form builder'))
<li class="dash-item dash-hasmenu {{ (Request::segment(1) == 'stages' || Request::segment(1) == 'labels' || Request::segment(1) == 'sources' || Request::segment(1) == 'lead_stages' || Request::segment(1) == 'pipelines' || Request::segment(1) == 'deals' || Request::segment(1) == 'leads'  || Request::segment(1) == 'form_builder' || Request::segment(1) == 'form_response')?' active dash-trigger':''}}">
<a href="#!" class="dash-link"
><span class="dash-micon"><i class="ti ti-layers-difference"></i></span
><span class="dash-mtext">{{__('CRM System')}}</span
><span class="dash-arrow"><i data-feather="chevron-right"></i></span
></a>
<ul class="dash-submenu {{ (Request::segment(1) == 'stages' || Request::segment(1) == 'labels' || Request::segment(1) == 'sources' || Request::segment(1) == 'lead_stages' || Request::segment(1) == 'leads'  || Request::segment(1) == 'form_builder' || Request::segment(1) == 'form_response' || Request::segment(1) == 'deals' || Request::segment(1) == 'pipelines')?'show':''}}">
@can('manage lead')
<li class="dash-item {{ (Request::route()->getName() == 'leads.list' || Request::route()->getName() == 'leads.index' || Request::route()->getName() == 'leads.show') ? ' active' : '' }}">
<a class="dash-link" href="{{ route('leads.index') }}">{{__('Leads')}}</a>
</li>
@endcan
@can('manage deal')
<li class="dash-item {{ (Request::route()->getName() == 'deals.list' || Request::route()->getName() == 'deals.index' || Request::route()->getName() == 'deals.show') ? ' active' : '' }}">
<a class="dash-link" href="{{route('deals.index')}}">{{__('Deals')}}</a>
</li>
@endcan
@can('manage form builder')
<li class="dash-item {{ (Request::segment(1) == 'form_builder' || Request::segment(1) == 'form_response')?'active open':''}}">
<a class="dash-link" href="{{route('form_builder.index')}}">{{__('Form Builder')}}</a>
</li>
@endcan
@if(\Auth::user()->type=='company' || \Auth::user()->type=='client')
<li class="dash-item  {{ (Request::segment(1) == 'contract')?'active':''}}">
<a class="dash-link" href="{{route('contract.index')}}">{{__('Contract')}}</a>
</li>
@endif
@if(Gate::check('manage lead stage') || Gate::check('manage pipeline') ||Gate::check('manage source') ||Gate::check('manage label') || Gate::check('manage stage'))
<li class="dash-item  {{(Request::segment(1) == 'stages' || Request::segment(1) == 'labels' || Request::segment(1) == 'sources' || Request::segment(1) == 'lead_stages' || Request::segment(1) == 'pipelines' || Request::segment(1) == 'product-category' || Request::segment(1) == 'product-unit' || Request::segment(1) == 'payment-method' || Request::segment(1) == 'custom-field' || Request::segment(1) == 'chart-of-account-type')? 'active dash-trigger' :''}}">
<a class="dash-link" href="{{ route('pipelines.index') }}   ">{{__('CRM System Setup')}}</a>
{{--                                            <ul class="dash-submenu">--}}
{{--                                                @can('manage pipeline')--}}
{{--                                                    <li class="dash-item  {{ (Request::route()->getName() == 'pipelines.index' ) ? ' active' : '' }}">--}}
{{--                                                        <a class="dash-link" href="{{ route('pipelines.index') }}">{{__('Pipeline')}}</a>--}}
{{--                                                    </li>--}}
{{--                                                @endcan--}}
{{--                                                @can('manage lead stage')--}}
{{--                                                    <li class="dash-item {{ (Request::route()->getName() == 'lead_stages.index' ) ? 'active' : '' }}">--}}
{{--                                                        <a class="dash-link" href="{{route('lead_stages.index')}}">{{__('Lead Stages')}}</a>--}}
{{--                                                    </li>--}}
{{--                                                @endcan--}}
{{--                                                @can('manage stage')--}}
{{--                                                    <li class="dash-item {{ (Request::route()->getName() == 'stages.index' ) ? 'active' : '' }}">--}}
{{--                                                        <a class="dash-link" href="{{route('stages.index')}}">{{__('Deal Stages')}}</a>--}}
{{--                                                    </li>--}}
{{--                                                @endcan--}}
{{--                                                @can('manage source')--}}
{{--                                                    <li class="dash-item {{ (Request::route()->getName() == 'sources.index' ) ? ' active' : '' }}">--}}
{{--                                                        <a class="dash-link" href="{{route('sources.index')}}">{{__('Sources')}}</a>--}}
{{--                                                    </li>--}}
{{--                                                @endcan--}}
{{--                                                @can('manage label')--}}
{{--                                                    <li class="dash-item {{ (Request::route()->getName() == 'labels.index' ) ? 'active' : '' }}">--}}
{{--                                                        <a class="dash-link" href="{{route('labels.index')}}">{{__('Labels')}}</a>--}}
{{--                                                    </li>--}}
{{--                                                @endcan--}}
{{--                                                <li class="dash-item {{ (Request::segment(1) == 'contractType')?'active open':''}}">--}}
{{--                                                    <a class="dash-link" href="{{ route('contractType.index') }}">{{__('Contract Type')}}</a>--}}
{{--                                                </li>--}}
{{--                                            </ul>--}}
</li>
@endif
</ul>
</li>
@endif
@endif
-->
<!--------------------- End CRM ----------------------------------->

<!--------------------- Start Project ----------------------------------->

<!--  @if(\Auth::user()->show_project() == 1)
@if( Gate::check('manage project'))
<li class="dash-item dash-hasmenu {{ ( Request::segment(1) == 'project' || Request::segment(1) == 'bugs-report' || Request::segment(1) == 'bugstatus' || Request::segment(1) == 'project-task-stages' || Request::segment(1) == 'calendar' || Request::segment(1) == 'timesheet-list' || Request::segment(1) == 'taskboard' || Request::segment(1) == 'timesheet-list' || Request::segment(1) == 'taskboard' || Request::segment(1) == 'project' || Request::segment(1) == 'projects')
? 'active dash-trigger' : ''}}">
<a href="#!" class="dash-link"
><span class="dash-micon"><i class="ti ti-share"></i></span
><span class="dash-mtext">{{__('Project System')}}</span
><span class="dash-arrow"><i data-feather="chevron-right"></i></span
></a>
<ul class="dash-submenu">
@can('manage project')
<li class="dash-item  {{Request::segment(1) == 'project' || Request::route()->getName() == 'projects.list' || Request::route()->getName() == 'projects.list' ||Request::route()->getName() == 'projects.index' || Request::route()->getName() == 'projects.show' || request()->is('projects/*') ? 'active' : ''}}">
<a class="dash-link" href="{{route('projects.index')}}">{{__('Projects')}}</a>
</li>
@endcan
@can('manage project task')
<li class="dash-item {{ (request()->is('taskboard*') ? 'active' : '')}}">
<a class="dash-link" href="{{ route('taskBoard.view', 'list') }}">{{__('Tasks')}}</a>
</li>
@endcan
@can('manage timesheet')
<li class="dash-item {{ (request()->is('timesheet-list*') ? 'active' : '')}}">
<a class="dash-link" href="{{route('timesheet.list')}}">{{__('Timesheet')}}</a>
</li>
@endcan
@can('manage bug report')
<li class="dash-item {{ (request()->is('bugs-report*') ? 'active' : '')}}">
<a class="dash-link" href="{{route('bugs.view','list')}}">{{__('Bug')}}</a>
</li>
@endcan
@can('manage project task')
<li class="dash-item {{ (request()->is('calendar*') ? 'active' : '')}}">
<a class="dash-link" href="{{ route('task.calendar',['all']) }}">{{__('Task Calendar')}}</a>
</li>
@endcan
@if(\Auth::user()->type!='super admin')
<li class="dash-item  {{ (Request::segment(1) == 'time-tracker')?'active open':''}}">
<a class="dash-link" href="{{ route('time.tracker') }}">{{__('Tracker')}}</a>
</li>
@endif
@if(Gate::check('manage project task stage') || Gate::check('manage bug status'))
<li class="dash-item dash-hasmenu {{ (Request::segment(1) == 'bugstatus' || Request::segment(1) == 'project-task-stages') ? 'active dash-trigger' : ''}}">
<a class="dash-link" href="#">{{__('Project System Setup')}}<span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
<ul class="dash-submenu">
@can('manage project task stage')
<li class="dash-item  {{ (Request::route()->getName() == 'project-task-stages.index') ? 'active' : '' }}">
<a class="dash-link" href="{{route('project-task-stages.index')}}">{{__('Project Task Stages')}}</a>
</li>
@endcan
@can('manage bug status')
<li class="dash-item {{ (Request::route()->getName() == 'bugstatus.index') ? 'active' : '' }}">
<a class="dash-link" href="{{route('bugstatus.index')}}">{{__('Bug Status')}}</a>
</li>
@endcan
</ul>
</li>
@endif
</ul>
</li>
@endif
@endif
-->
<!--------------------- End Project ----------------------------------->




<!--------------------- Start User Managaement System ----------------------------------->

@if(\Auth::user()->type!='super admin' && ( Gate::check('manage user') || Gate::check('manage role') || Gate::check('manage client')))
<li class="dash-item dash-hasmenu">
    <a href="#!" class="dash-link {{ (Request::segment(1) == 'users' || Request::segment(1) == 'roles' || Request::segment(1) == 'clients')?' active dash-trigger':''}}"
    ><span class="dash-micon"><i class="ti ti-users"></i></span
    ><span class="dash-mtext">{{__('User Management')}}</span
    ><span class="dash-arrow"><i data-feather="chevron-right"></i></span
    ></a>
    <ul class="dash-submenu">
        
        @can('manage user')
        <li class="dash-item {{ (Request::route()->getName() == 'users.index' || Request::route()->getName() == 'users.create' || Request::route()->getName() == 'users.edit') ? ' active' : '' }}">
            <a class="dash-link" href="{{ route('users.index') }}">{{__('User')}}</a>
        </li>
        @endcan
        @can('manage role')
        <li class="dash-item {{ (Request::route()->getName() == 'roles.index' || Request::route()->getName() == 'roles.create' || Request::route()->getName() == 'roles.edit') ? ' active' : '' }} ">
            <a class="dash-link" href="{{route('roles.index')}}">{{__('Role')}}</a>
        </li>
        @endcan
        <!--  @can('manage client')
        <li class="dash-item {{ (Request::route()->getName() == 'clients.index' || Request::segment(1) == 'clients' || Request::route()->getName() == 'clients.edit') ? ' active' : '' }}">
        <a class="dash-link" href="{{ route('clients.index') }}">{{__('Client')}}</a>
        </li>
        @endcan -->
    </ul>
</li>
@endif

<!--------------------- End User Managaement System----------------------------------->


<!--------------------- Start Products System ----------------------------------->

<!-- @if( Gate::check('manage product & service') || Gate::check('manage product & service'))
<li class="dash-item dash-hasmenu">
<a href="#!" class="dash-link ">
<span class="dash-micon"><i class="ti ti-shopping-cart"></i></span><span class="dash-mtext">{{__('Products System')}}</span><span class="dash-arrow">
<i data-feather="chevron-right"></i></span>
</a>
<ul class="dash-submenu">
@if(Gate::check('manage product & service'))
<li class="dash-item {{ (Request::segment(1) == 'productservice')?'active':''}}">
<a href="{{ route('productservice.index') }}" class="dash-link">{{__('Product & Services')}}
</a>
</li>
@endif
@if(Gate::check('manage product & service'))
<li class="dash-item {{ (Request::segment(1) == 'productstock')?'active':''}}">
<a href="{{ route('productstock.index') }}" class="dash-link">{{__('Product Stock')}}
</a>
</li>
@endif
</ul>
</li>
@endif -->

<!--------------------- End Products System ----------------------------------->





<!--  <li class="dash-item dash-hasmenu {{ (Request::segment(1) == 'support')?'active':''}}">
<a href="{{route('support.index')}}" class="dash-link">
<span class="dash-micon"><i class="ti ti-headphones"></i></span><span class="dash-mtext">{{__('Support System')}}</span>
</a>
</li>
<li class="dash-item dash-hasmenu {{ (Request::segment(1) == 'zoom-meeting' || Request::segment(1) == 'zoom-meeting-calender')?'active':''}}">
<a href="{{route('zoom-meeting.index')}}" class="dash-link">
<span class="dash-micon"><i class="ti ti-user-check"></i></span><span class="dash-mtext">{{__('Zoom Meeting')}}</span>
</a>
</li>
<li class="dash-item dash-hasmenu {{ (Request::segment(1) == 'chats')?'active':''}}">
<a href="{{ url('chats') }}" class="dash-link">
<span class="dash-micon"><i class="ti ti-message-circle"></i></span><span class="dash-mtext">{{__('Messenger')}}</span>
</a>
</li>
@if(\Auth::user()->type =='company')

<li class="dash-item dash-hasmenu {{ Request::segment(1) == 'email_template' || Request::route()->getName() == 'manage.email.language' ? ' active dash-trigger' : 'collapsed' }}">
<a href="{{ route('manage.email.language',[$emailTemplate ->id,\Auth::user()->lang]) }}" class="dash-link">
<span class="dash-micon"><i class="ti ti-template"></i></span>
<span class="dash-mtext">{{ __('Email Template') }}</span></a>
</li>
@endif -->

<!--------------------- Start System Setup ----------------------------------->
                @if( Gate::check('manage company settings'))
                        <li class="dash-item dash-hasmenu">
                            <a href="#!" class="dash-link ">
                                <span class="dash-micon"><i class="ti ti-settings"></i></span><span class="dash-mtext">{{__('System Setup')}}</span><span class="dash-arrow">
                                        <i data-feather="chevron-right"></i></span>
                            </a>
                            
                            <ul class="dash-submenu">
                                @if(Gate::check('manage company settings'))
                                    <li class="dash-item dash-hasmenu {{ (Request::segment(1) == 'company-setting') ? ' active' : '' }}">
                                        <a href="{{ route('company.setting') }}" class="dash-link">{{__('System Settings')}}</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                @endif
    



<!-- <a href="#" data-size="lg" data-url="{{ url('/configuration/store?id=1') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Update Configuration')}}" class="btn btn-sm btn-primary">
<li class="dash-item dash-hasmenu">

Configuration


</li>
</a> -->
<!--------------------- End System Setup ----------------------------------->


<!--------------------- Start Administrative ------------------------------->
@if( Gate::check('manage psic sections') || Gate::check('manage psic division') || Gate::check('manage psic group') || Gate::check('manage psic class') || Gate::check('manage psic subclass') || Gate::check('manage tax class') || Gate::check('manage tax category') || Gate::check('manage tax type') || Gate::check('manage requirements') || Gate::check('manage bplo requirements') || Gate::check('manage bussiness classifications') || Gate::check('manage bussiness activity') || Gate::check('manage app types') || Gate::check('manage bussiness types') || Gate::check('manage barangay') || Gate::check('manage occupancy type') || Gate::check('manage region') || Gate::check('manage province') || Gate::check('manage municipality') || Gate::check('manage country'))
<li class="dash-item dash-hasmenu">
    <a href="#!" class="dash-link ">
        <span class="dash-micon"><i class="ti ti-user"></i></span><span class="dash-mtext">{{__('Administrative')}}</span><span class="dash-arrow">
            <i data-feather="chevron-right"></i></span>
        </a>
        <ul class="dash-submenu">
        


            <li class="dash-item dash-hasmenu">
                
                <a class="dash-link" href="#">{{__('Business Permit')}}<span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
                <ul class="dash-submenu">
                            
                       <li class="dash-item dash-hasmenu">
                                <a class="dash-link" href="#">{{__('PSIC Libraries')}}<span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
                            <ul class="dash-submenu">
                                            @if( Gate::check('manage psic sections'))
                                    <li class="dash-item {{ (Request::segment(1) == 'psicsection')?'active':''}}">
                                        <a href="{{ route('psicsection.index') }}" class="dash-link">{{__('Section')}}</a>
                                    </li>
                                    @endif
                                    @if(Gate::check('manage psic division'))
                                    <li class="dash-item {{ (Request::segment(1) == 'psicdivision')?'active':''}}">
                                        <a href="{{ route('psicdivision.index') }}" class="dash-link">{{__('Division')}}</a>
                                    </li>
                                    @endif
                                    @if(Gate::check('manage psic group'))
                                    <li class="dash-item {{ (Request::segment(1) == 'psicgroup')?'active':''}}">
                                        <a href="{{ route('psicgroup.index') }}" class="dash-link">{{__('Group')}}</a>
                                    </li>
                                    @endif
                                    @if( Gate::check('manage psic class'))
                                    <li class="dash-item {{ (Request::segment(1) == 'psicclass')?'active':''}}">
                                        <a href="{{ route('psicclass.index') }}" class="dash-link">{{__('Class')}}
                                        </a>
                                    </li>
                                    @endif
                                    @if( Gate::check('manage psic subclass'))
                                    <li class="dash-item {{ (Request::segment(1) == 'psicsubclass')?'active':''}}">
                                        <a href="{{ route('psicsubclass.index') }}" class="dash-link">{{__('Sub-Class')}} <p>(Nature of Business )</p></a>
                                    </li>
                                    @endif
                            </ul>
                        </li>
                         <li class="dash-item dash-hasmenu ">
                            <a class="dash-link" href="#">{{__('Setup Locality')}}<span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
                              <ul class="dash-submenu"></ul>
                        </li>
                        <li class="dash-item dash-hasmenu ">
                            <a class="dash-link" href="#">{{__('Requirements')}}<span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
                            <ul class="dash-submenu">
                               @if( Gate::check('manage requirements'))
                                <li class="dash-item {{ (Request::segment(1) == 'requirements')?'active':''}}">
                                    <a href="{{ route('requirements.index') }}" class="dash-link">{{__('Manage')}}
                                    </a>
                                </li>
                                @endif
                                @if( Gate::check('manage bplo requirements'))
                                <li class="dash-item {{ (Request::segment(1) == 'bplorequirements')?'active':''}}">
                                    <a href="{{ route('bplorequirements.index') }}" class="dash-link">{{__('Business Permit')}}
                                    </a>
                                </li>
                                @endif
                                 
                            </ul>
                        </li>
                        @if(Gate::check('manage bussiness types'))
                        <li class="dash-item {{ (Request::segment(1) == 'typeofbussiness')?'active':''}}">
                            <a href="{{ route('typeofbussiness.index') }}" class="dash-link">{{__('Business Types')}}
                            </a>
                        </li>
                        @endif
                        <li class="dash-item dash-hasmenu ">
                            <a class="dash-link" href="#">{{__('Bus. Classification')}}<span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
                            <ul class="dash-submenu">
                               @if( Gate::check('manage bussiness classifications'))
                                <li class="dash-item {{ (Request::segment(1) == 'bplobusinessclassification')?'active':''}}">
                                    <a href="{{ route('bplobusinessclassification.index') }}" class="dash-link">{{__('Manage')}}
                                    </a>
                                </li>
                                @endif
                                 @if( Gate::check('manage bussiness activity'))
                                <li class="dash-item {{ (Request::segment(1) == 'bplobusinessactivity')?'active':''}}">
                                    <a href="{{ route('bplobusinessactivity.index') }}" class="dash-link">{{__('Activities')}}
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        <li class="dash-item dash-hasmenu ">
                            <a class="dash-link" href="#">{{__('Tax Libraries')}}<span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
                            <ul class="dash-submenu">
                                @if(Gate::check('manage tax class'))
                                <li class="dash-item {{ (Request::segment(1) == 'taxclass')?'active':''}}">
                                    <a href="{{ route('taxclass.index') }}" class="dash-link">{{__('Class')}}
                                    </a>
                                </li>
                                @endif
                                @if( Gate::check('manage tax category'))
                                <li class="dash-item {{ (Request::segment(1) == 'taxcategory')?'active':''}}">
                                    <a href="{{ route('taxcategory.index') }}" class="dash-link">{{__('Category')}}
                                    </a>
                                </li>
                                @endif
                                @if( Gate::check('manage tax type'))
                                <li class="dash-item {{ (Request::segment(1) == 'taxtype')?'active':''}}">
                                    <a href="{{ route('taxtype.index') }}" class="dash-link">{{__('Type')}}
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @if( Gate::check('manage bplo tax rate effectivity') || Gate::check('manage bplo fixed taxes & fees') || Gate::check('manage bplo graduated new tax'))
                        <li class="dash-item dash-hasmenu ">
                            <a class="dash-link" href="#">{{__('Taxation Schedule')}}<span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
                            <ul class="dash-submenu">
                                @if( Gate::check('manage bplo tax rate effectivity'))
                                <li class="dash-item {{ (Request::route()->getName() == 'bploassessetaxrateeffectivit') ? ' active' : '' }}">
                                    <a href="{{route('bploassessetaxrateeffectivit.index')}}" class="dash-link">{{__('Tax Rate Effectivity')}}
                                    </a>
                                </li>
                                @endif

                                @if( Gate::check('manage bplo fixed taxes & fees'))
                                <li class="dash-item {{ (Request::route()->getName() == 'bplobusinessfixedtax') ? ' active' : '' }}">
                                    <a href="{{route('bplobusinessfixedtax.index')}}" class="dash-link">{{__('Fixed Taxes & Fees')}}
                                    </a>
                                </li>
                                @endif
                                @if(Gate::check('manage bplo graduated new tax'))

                                <li class="dash-item {{ (Request::route()->getName() == 'bplobusinesstax') ? ' active' : '' }}">
                                    <a href="{{route('bplobusinesstax.index')}}" class="dash-link">{{__('Graduated/ New Tax')}}
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif
                        @if( Gate::check('manage app types'))
                        <li class="dash-item {{ (Request::segment(1) == 'pbloapptypes')?'active':''}}">
                            <a href="{{ route('pbloapptypes.index') }}" class="dash-link">{{__('Application Types')}}
                            </a>
                        </li>            
                        @endif 
                        @if( Gate::check('manage bplo system parameters'))
                        <li class="dash-item">
                            <a href="{{route('bplosystemparameters.index')}}" class="dash-link">{{__('System Parameters')}}
                            </a>
                        </li>
                        @endif
                        @if( Gate::check('manage bussiness permit fee') || Gate::check('manage bussiness sanitary fee') || Gate::check('manage bussiness garbage fee') || Gate::check('manage bussiness engineering fees') || Gate::check('manage bussiness environmental fee'))
                        
                    <li class="dash-item dash-hasmenu">
                        <a href="#!" class="dash-link ">
                            {{__('Fees Master')}}</span><span class="dash-arrow">
                                <i data-feather="chevron-right"></i></span>
                            </a>
                            <ul class="dash-submenu">
                                @if( Gate::check('manage bussiness permit fee'))
                                <li class="dash-item {{ (Request::segment(1) == 'bplobusinesspermitfee')?'active':''}}">
                                    <a href="{{ route('bplobusinesspermitfee.index') }}" class="dash-link">{{__('Business Permit Fee')}}</a>
                                </li>
                                @endif
                                @if( Gate::check('manage bussiness sanitary fee'))
                                <li class="dash-item {{ (Request::segment(1) == 'bplobusinesssanitaryfee')?'active':''}}">
                                    <a href="{{ route('bplobusinesssanitaryfee.index') }}" class="dash-link">{{__('Bussiness Sanitary Fee')}}</a>
                                </li>
                                @endif
                                @if( Gate::check('manage bussiness garbage fee'))
                                <li class="dash-item {{ (Request::segment(1) == 'bplobusinessgarbagefee')?'active':''}}">
                                    <a href="{{ route('bplobusinessgarbagefee.index') }}" class="dash-link">{{__('Bussiness Garbage Fee')}}</a>
                                </li>
                                @endif
                                @if( Gate::check('manage bussiness engineering fees'))
                                <li class="dash-item {{ (Request::segment(1) == 'bplobusinessenggfee')?'active':''}}">
                                    <a href="{{ route('bplobusinessenggfee.index') }}" class="dash-link">{{__('Bussiness Engineering Fee')}}</a>
                                </li>
                                @endif
                                @if( Gate::check('manage bussiness environmental fee'))
                                <li class="dash-item {{ (Request::segment(1) == 'bplobusinessenvfees')?'active':''}}">
                                    <a href="{{ route('bplobusinessenvfees.index') }}" class="dash-link">{{__('Environmental Fee')}}</a>
                                </li>
                                @endif
                            </ul>
                    </li>
                    @endif
                </ul>
            </li>
             
           
            <li class="dash-item dash-hasmenu ">
                <a class="dash-link" href="#">{{__('Address')}}<span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
                <ul class="dash-submenu">
                    @if( Gate::check('manage region'))
                    <li class="dash-item {{ (Request::segment(1) == 'profileregion')?'active':''}}">
                     <a href="{{ route('profileregion.index') }}" class="dash-link">{{__('Region')}}
                     </a>
                    </li>
                    @endif
                    @if( Gate::check('manage province'))
                    <li class="dash-item {{ (Request::segment(1) == 'profileprovince')?'active':''}}">
                        <a href="{{ route('profileprovince.index') }}" class="dash-link">{{__('Province')}}
                        </a>
                    </li>
                    @endif
                    @if( Gate::check('manage municipality'))
                    <li class="dash-item {{ (Request::segment(1) == 'profilemunicipalitie')?'active':''}}">
                        <a href="{{ route('profilemunicipalitie.index') }}" class="dash-link">{{__('Municipality')}}
                        </a>
                    </li>
                    @endif
                    @if( Gate::check('manage barangay'))
                    <li class="dash-item {{ (Request::segment(1) == 'barangay')?'active':''}}">
                        <a href="{{ route('barangay.index') }}" class="dash-link">{{__('Barangay')}}
                        </a>
                    </li>
                    @endif



                        <li class="dash-item {{ (Request::segment(1) == 'district')?'active':''}}">
                        <a href="{{ route('district.index') }}" class="dash-link">{{__('District')}}
                        </a>
                    </li>
                   <!--  <li class="dash-item {{ (Request::segment(1) == 'locality')?'active':''}}">
                        <a href="{{ route('locality.index') }}" class="dash-link">{{__('Locality')}}
                        </a>
                    </li> -->
                </ul>
            </li>
            
            
            <li class="dash-item dash-hasmenu ">
                <a class="dash-link" href="#">{{__('Payment Data')}}<span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
                <ul class="dash-submenu">
                     <li class="dash-item">
                     <a href="#" class="dash-link">{{__('Deadline')}}
                     </a>
                    </li>
                    <li class="dash-item">
                     <a href="#" class="dash-link">{{__('Basic | SEF Rate')}}
                     </a>
                    </li>
                </ul>
            </li>
            
            
            
                       
            <li class="dash-item dash-hasmenu ">
                <a class="dash-link" href="#">{{__('Fire Protection')}}<span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
                <ul class="dash-submenu">
                     @if(Gate::check('manage occupancy type'))
                    <li class="dash-item {{ (Request::route()->getName() == 'bfpoccupancytype') ? ' active' : '' }}">
                        <a href="{{route('bfpoccupancytype.index')}}" class="dash-link">{{__('Occupancy Type')}}
                        </a>
                    </li>
                    @endif
                </ul>
            </li> 
            @if(Gate::check('manage country'))
            <li class="dash-item {{ (Request::route()->getName() == 'country') ? ' active' : '' }}">
                <a href="{{route('country.index')}}" class="dash-link">{{__('Country')}}
                </a>
            </li>
            @endif
            
            <li class="dash-item dash-hasmenu">
                <a href="#!" class="dash-link ">
                    {{__('General Services')}}
                    <span class="dash-arrow">
                        <i data-feather="chevron-right"></i>
                    </span>
                </a>
                <ul class="dash-submenu">
                    <li class="dash-item {{ (Request::segment(2) == 'purchase-types') ? 'active' : '' }}">
                        <a href="{{ route('admin-gso.purchase-type.index') }}" class="dash-link">{{__('Purchase Type')}}
                        </a>
                    </li>
                    <li class="dash-item {{ (Request::segment(2) == 'item-categories') ? 'active' : '' }}">
                        <a href="{{ route('admin-gso.item-category.index') }}" class="dash-link">{{__('Item Category')}}
                        </a>
                    </li>
                    <li class="dash-item {{ (Request::segment(2) == 'item-type') ? 'active' : '' }}">
                        <a href="{{ route('admin-gso.item-type.index') }}" class="dash-link">{{__('Item Type')}}
                        </a>
                    </li>
                    <li class="dash-item {{ (Request::segment(2) == 'item-management') ? 'active' : '' }}">
                        <a href="{{ route('admin-gso.item.index') }}" class="dash-link">{{__('Item Management')}}
                        </a>
                    </li>
                    <li class="dash-item {{ (Request::segment(2) == 'unit-of-measurements') ? 'active' : '' }}">
                        <a href="{{ route('admin-gso.unit-of-measurement.index') }}" class="dash-link">{{__('Unit Of Measurement')}}
                        </a>
                    </li>
                    <li class="dash-item {{ (Request::segment(2) == 'product-lines') ? 'active' : '' }}">
                        <a href="{{ route('admin-gso.product-line.index') }}" class="dash-link">{{__('Product Line')}}
                        </a>
                    </li>
                    <li class="dash-item {{ (Request::segment(2) == 'suppliers') ? 'active' : '' }}">
                        <a href="{{ route('admin-gso.supplier.index') }}" class="dash-link">{{__('Supplier')}}
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
    @endif

    <!--------------------- End Administrative ----------------------------------->
    <!--------------------- Start Reports ----------------------------------->
    

        
    <li class="dash-item dash-hasmenu">
        <a href="#!" class="dash-link ">
        <span class="dash-micon"><i class="ti ti-user"></i></span><span class="dash-mtext">{{__('Reports')}}</span><span class="dash-arrow">
            <i data-feather="chevron-right"></i></span>
        </a>
        <ul class="dash-submenu">
            @if( Gate::check('manage bussiness permit fee'))
            <li class="dash-item {{ (Request::segment(1) == 'runningbalance')?'active':''}}">
                <a href="{{ route('runningbalance.index') }}" class="dash-link">{{__('Running Balance')}}</a>
            </li>
            @endif
                @if( Gate::check('manage bussiness permit fee'))
            <li class="dash-item {{ (Request::segment(1) == 'summaryreport')?'active':''}}">
                <a href="{{ route('summaryreport.index') }}" class="dash-link">{{__('Summary Report')}}</a>
            </li>
            @endif
            
        </ul>
    </li>

    </ul>
    <!--------------------- End Reports ----------------------------------->

    @endif
    @if((\Auth::user()->type == 'client'))
    <ul class="dash-navbar">
        @if(Gate::check('manage client dashboard'))

        <li class="dash-item dash-hasmenu {{ (Request::segment(1) == 'dashboard') ? ' active' : '' }}">
            <a href="{{ route('client.dashboard.view') }}" class="dash-link">
                <span class="dash-micon"><i class="ti ti-home"></i></span><span class="dash-mtext">{{__('Dashboard')}}</span>
            </a>
        </li>

        @endif

        @if(Gate::check('manage deal'))
        <li class="dash-item dash-hasmenu {{ (Request::segment(1) == 'deals') ? ' active' : '' }}">
            <a href="{{ route('deals.index') }}" class="dash-link">
                <span class="dash-micon"><i class="ti ti-rocket"></i></span><span class="dash-mtext">{{__('Deals')}}</span>
            </a>
        </li>
        @endif
        @if(Gate::check('manage contract'))
        <li class="dash-item dash-hasmenu{{ (Request::segment(1) == 'contract')?'active':''}}">
            <a href="{{ route('contract.index') }}" class="dash-link">
                <span class="dash-micon"><i class="ti ti-rocket"></i></span><span class="dash-mtext">{{__('Contract')}}</span>
            </a>
        </li>

        @endif


        @if(Gate::check('manage project'))
        <li class="dash-item dash-hasmenu  {{ (Request::segment(1) == 'projects') ? ' active' : '' }}">
            <a href="{{ route('projects.index') }}" class="dash-link">
                <span class="dash-micon"><i class="ti ti-share"></i></span><span class="dash-mtext">{{__('Project')}}</span>
            </a>
        </li>
        @endif


        @if(Gate::check('manage project task'))
        <li class="dash-item dash-hasmenu  {{ (Request::segment(1) == 'taskboard') ? ' active' : '' }}">
            <a href="{{ route('taskBoard.view', 'list') }}" class="dash-link">
                <span class="dash-micon"><i class="ti ti-list-check"></i></span><span class="dash-mtext">{{__('Tasks')}}</span>
            </a>
        </li>
        @endif

        @if(Gate::check('manage bug report'))
        <li class="dash-item dash-hasmenu {{ (Request::segment(1) == 'bugs-report') ? ' active' : '' }}">
            <a href="{{ route('bugs.view','list') }}" class="dash-link">
                <span class="dash-micon"><i class="ti ti-bug"></i></span><span class="dash-mtext">{{__('Bugs')}}</span>
            </a>
        </li>
        @endif

        @if(Gate::check('manage timesheet'))
        <li class="dash-item dash-hasmenu {{ (Request::segment(1) == 'timesheet-list') ? ' active' : '' }}">
            <a href="{{ route('timesheet.list') }}" class="dash-link">
                <span class="dash-micon"><i class="ti ti-clock"></i></span><span class="dash-mtext">{{__('Timesheet')}}</span>
            </a>
        </li>
        @endif

        @if(Gate::check('manage project task'))
        <li class="dash-item dash-hasmenu {{ (Request::segment(1) == 'calendar') ? ' active' : '' }}">
            <a href="{{ route('task.calendar',['all']) }}" class="dash-link">
                <span class="dash-micon"><i class="ti ti-calendar"></i></span><span class="dash-mtext">{{__('Task Calender')}}</span>
            </a>
        </li>
        @endif

        <li class="dash-item dash-hasmenu">
            <a href="{{route('support.index')}}" class="dash-link {{ (Request::segment(1) == 'support')?'active':''}}">
                <span class="dash-micon"><i class="ti ti-headphones"></i></span><span class="dash-mtext">{{__('Support')}}</span>
            </a>
        </li>
    </ul>
    @endif

    <!-- START OF ACCOUNTING -->
    <ul class="dash-navbar">
        <li class="dash-item dash-hasmenu">
            <a href="#!" class="dash-link">
                <span class="dash-micon"><i class="ti ti-user"></i></span><span class="dash-mtext">{{__('Accounting')}}</span><span class="dash-arrow">
                <i data-feather="chevron-right"></i></span>
            </a>
            <ul class="dash-submenu">
                <!-- @if( Gate::check('manage acctg fund code')) -->
                <li class="dash-item {{ (Request::segment(1) == 'fund-codes') ? 'active' : '' }}">
                    <a href="{{ route('fund-codes.index') }}" class="dash-link">{{__('Fund Codes')}}</a>
                </li>
                <!-- @endif -->
                <li class="dash-item dash-hasmenu">
                    <a class="dash-link" href="#">{{__('Chart of Accounts')}}<span class="dash-arrow">
                        <i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="dash-submenu">
                        <li class="dash-item {{ (Request::segment(2) == 'account-groups') ? 'active' : '' }}">
                            <a href="{{ route('account-groups.index') }}" class="dash-link">{{__('Account Group')}}
                            </a>
                        </li>
                        <li class="dash-item {{ (Request::segment(2) == 'major-account-groups') ? 'active' : '' }}">
                            <a href="{{ route('major-account-groups.index') }}" class="dash-link">{{__('Major Group')}}
                            </a>
                        </li>
                        <li class="dash-item {{ (Request::segment(2) == 'major-account-groups') ? 'active' : '' }}">
                            <a href="{{ route('submajor-account-groups.index') }}" class="dash-link">{{__('Sub-Major Group')}}
                            </a>
                        </li>
                        <li class="dash-item {{ (Request::segment(2) == 'general-ledger-accounts') ? 'active' : '' }}">
                            <a href="{{ route('general-ledger-accounts.index') }}" class="dash-link">{{__('General Ledger Account')}}
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- @if( Gate::check('manage acctg department')) -->
                <li class="dash-item {{ (Request::segment(1) == 'departments') ? 'active' : '' }}">
                    <a href="{{ route('departments.index') }}" class="dash-link">{{__('Departments')}}</a>
                </li>
                <!-- @endif -->
               
            </ul>
        </li>
    </ul>
    <!-- END OF ACCOUNTING -->

        <!-- START OF FINANCE -->
        <ul class="dash-navbar">
            <li class="dash-item dash-hasmenu">
                <a href="#!" class="dash-link ">
                    <span class="dash-micon"><i class="ti ti-user"></i></span><span class="dash-mtext">Finance</span><span class="dash-arrow">
                    <i data-feather="chevron-right"></i></span>
                </a>
                <ul class="dash-submenu">
                    @if( Gate::check('manage bussiness permit fee'))
                    <li class="dash-item {{ (Request::segment(1) == 'payee')?'active':''}}">
                        <a href="{{ route('payee.index') }}" class="dash-link">Payee[Creditors]</a>
                    </li>
                    @endif 
                    <li class="dash-item {{ (Request::segment(1) == 'cbobudget')?'active':''}}">
                        <a href="{{ route('cbobudget.index') }}" class="dash-link">Budget Proposal</a>
                    </li>     
                </ul>
            </li>
        </ul>
        <!-- END OF FINANCE -->

    <!-- START OF HR -->
    <ul class="dash-navbar">
        <li class="dash-item dash-hasmenu">
            <a href="#!" class="dash-link">
                <span class="dash-micon"><i class="ti ti-user"></i></span><span class="dash-mtext">{{__('Human Resource')}}</span><span class="dash-arrow">
                <i data-feather="chevron-right"></i></span>
            </a>
            <ul class="dash-submenu">
                <!-- @if( Gate::check('manage hr designation')) -->
                <li class="dash-item {{ (Request::segment(1) == 'designations') ? 'active' : '' }}">
                    <a href="{{ route('hr.designations.index') }}" class="dash-link">{{__('Designations')}}</a>
                </li>
                <!-- @endif
                @if( Gate::check('manage hr employee')) -->
                <li class="dash-item {{ (Request::segment(1) == 'employees') ? 'active' : '' }}">
                    <a href="{{ route('hr.employees.index') }}" class="dash-link">{{__('Employees')}}</a>
                </li>
                <!-- @endif -->
            </ul>
        </li>
    </ul>
    <!-- END OF HR -->

    <!-- START OF GSO -->
    <ul class="dash-navbar">
        <li class="dash-item dash-hasmenu">
            <a href="#!" class="dash-link">
                <span class="dash-micon"><i class="ti ti-user"></i></span><span class="dash-mtext">{{__('General Services')}}</span><span class="dash-arrow">
                <i data-feather="chevron-right"></i></span>
            </a>
            <ul class="dash-submenu">
                <li class="dash-item {{ (Request::segment(1) == 'departmental-requisitions') ? 'active' : '' }}">
                    <a href="{{ route('gso.departmental-requisition.index') }}" class="dash-link">{{__('Dept. Requisition')}}</a>
                </li>
                <li class="dash-item {{ (Request::segment(1) == 'inventory') ? 'active' : '' }}">
                    <a href="{{ route('gso.inventory.index') }}" class="dash-link">{{__('Inventory')}}</a>
                </li>
                
                <li class="dash-item dash-hasmenu">
                    <a href="#!" class="dash-link ">
                        {{__('Issuance')}}
                        <span class="dash-arrow">
                            <i data-feather="chevron-right"></i>
                        </span>
                    </a>
                    <ul class="dash-submenu">
                        <li class="dash-item {{ (Request::segment(2) == 'requestor') ? 'active' : '' }}">
                            <a href="{{ route('gso.issuance.requestor.index') }}" class="dash-link">{{__('Requestor')}}
                            </a>
                        </li>
                        <li class="dash-item {{ (Request::segment(2) == 'approver') ? 'active' : '' }}">
                            <a href="{{ route('gso.issuance.approver.index') }}" class="dash-link">{{__('Approver')}}
                            </a>
                        </li>
                    </ul>
                </li>


            </ul>
        </li>
    </ul>
    <!-- END OF GSO -->

    <!-- START OF COMPONENTS -->
    <ul class="dash-navbar">
        <li class="dash-item dash-hasmenu">
            <a href="#!" class="dash-link">
                <span class="dash-micon"><i class="ti ti-user"></i></span><span class="dash-mtext">{{__('Components')}}</span><span class="dash-arrow">
                <i data-feather="chevron-right"></i></span>
            </a>
            <ul class="dash-submenu">
                <li class="dash-item dash-hasmenu">
                    <a class="dash-link" href="#">{{__('Menus')}}<span class="dash-arrow">
                        <i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="dash-submenu">
                        <li class="dash-item {{ (Request::segment(2) == 'groups') ? 'active' : '' }}">
                            <a href="{{ route('component.menu-group.index') }}" class="dash-link">{{__('Group')}}
                            </a>
                        </li>
                        <li class="dash-item {{ (Request::segment(2) == 'modules') ? 'active' : '' }}">
                            <a href="{{ route('component.menu-module.index') }}" class="dash-link">{{__('Module')}}
                            </a>
                        </li>
                        <li class="dash-item {{ (Request::segment(2) == 'sub-modules') ? 'active' : '' }}">
                            <a href="{{ route('component.menu-sub-module.index') }}" class="dash-link">{{__('Sub Module')}}
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="dash-item {{ (Request::segment(1) == 'permisisons') ? 'active' : '' }}">
                    <a href="{{ route('components.permissions.index') }}" class="dash-link">{{__('Permissions')}}</a>
                </li>
                <li class="dash-item dash-hasmenu">
                    <a class="dash-link" href="#">{{__('Users')}}<span class="dash-arrow">
                        <i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="dash-submenu">
                        <li class="dash-item {{ (Request::segment(2) == 'accounts') ? 'active' : '' }}">
                            <a href="{{ route('components.users.accounts.index') }}" class="dash-link">{{__('Account')}}
                            </a>
                        </li>
                        <li class="dash-item {{ (Request::segment(2) == 'roles') ? 'active' : '' }}">
                            <a href="{{ route('components.users.roles.index') }}" class="dash-link">{{__('Role')}}
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
    </ul>
    <!-- END OF COMPONENTS -->
</div>
</div>
</nav>