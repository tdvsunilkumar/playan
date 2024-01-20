
<!-- [ Main Content ] end -->
<footer class="dash-footer">
    <div class="footer-wrapper justify-content-center">
        <div class="py-1">
            <span class="text-muted">  &copy; {{(Utility::getValByName('footer_text')) ? Utility::getValByName('footer_text') :  __('Copyright TRIX') }} {{ date('Y') }}</span>
        </div>

    </div>
</footer>

<!-- <div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <p>Some text in the modal.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div> -->

<!-- Warning Section Ends -->
<!-- Required Js -->
<script src="{{ asset('js/yearpicker/yearpicker.js') }}"></script>
<script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/dash.js') }}"></script>
<script src="{{ asset('js/moment.min.js') }}"></script>
<script src="{{ asset('assets/vendors/jquery-ui/1.13.2/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap-switch-button.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('assets/vendors/shorten/jquery.shorten.1.0.js') }}"></script>
<!-- <script src="{{ asset('assets/js/plugins/simple-datatables.js') }}"></script> -->

<!-- Apex Chart -->
<script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/main.min.js') }}"></script>
<!-- <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script> -->
<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/flatpickr.min.js') }}"></script>
<script src="{{ asset('js/jquery.numeric.js') }}"></script>

<script src="{{ asset('js/jscolor.js') }}"></script>
<script src="{{ asset('js/remort.js') }}"></script>
<script src="{{ asset('js/datetimepicker/jquery.datetimepicker.full.min.js') }}"></script> 

<script src="{{ asset('js/partials/digital-sign-loader.js?v='.filemtime(getcwd().'/js/partials/digital-sign-loader.js').'') }}"></script>
@if (
    !\Request::is('accounting/*') && 
    !\Request::is('human-resource/*') && 
    !\Request::is('administrative/general-services/*') && 
    !\Request::is('general-services/*') && 
    !\Request::is('components/*') && 
    !\Request::is('finance/*') && 
    !\Request::is('for-approvals/*') && 
    !\Request::is('treasury/*') &&
    !\Request::is('health-and-safety/setup-data/item-managements') &&
    !\Request::is('business-permit/application') &&
    !\Request::is('reports/accounting') && 
    !\Request::is('economic-and-investment/*') &&
    !\Request::is('profile')
)
<script src="{{ asset('assets/js/plugins/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
@endif

@if($message = Session::get('success'))
    <script>
        show_toastr('success', '{!! $message !!}');
    </script>
@endif
@if($message = Session::get('error'))
    <script>
        show_toastr('error', '{!! $message !!}');
    </script>
@endif

@stack('script-page')

@if (
    !\Request::is('accounting/*') && 
    !\Request::is('human-resource/*') && 
    !\Request::is('administrative/general-services/*') && 
    !\Request::is('general-services/*') && 
    !\Request::is('components/*') && 
    !\Request::is('finance/*') && 
    !\Request::is('for-approvals/*') && 
    !\Request::is('treasury/*') &&
    !\Request::is('health-and-safety/setup-data/item-managements') &&
    !\Request::is('business-permit/application') &&
    !\Request::is('reports/accounting') &&
    !\Request::is('economic-and-investment/*') &&
    !\Request::is('profile')
)
    @if (App\Models\Utility::getValByName1('gdpr_cookie') == 'on')
        <script type="text/javascript">
            var defaults = {
                'messageLocales': {
                    /*'en': 'We use cookies to make sure you can have the best experience on our website. If you continue to use this site we assume that you will be happy with it.'*/
                    'en': "{{ App\Models\Utility::getValByName1('cookie_text') }}"
                },
                'buttonLocales': {
                    'en': 'Ok'
                },
                'cookieNoticePosition': 'bottom',
                'learnMoreLinkEnabled': false,
                'learnMoreLinkHref': '/cookie-banner-information.html',
                'learnMoreLinkText': {
                    'it': 'Saperne di più',
                    'en': 'Learn more',
                    'de': 'Mehr erfahren',
                    'fr': 'En savoir plus'
                },
                'buttonLocales': {
                    'en': 'Ok'
                },
                'expiresIn': 30,
                'buttonBgColor': '#d35400',
                'buttonTextColor': '#fff',
                'noticeBgColor': '#000000',
                'noticeTextColor': '#fff',
                'linkColor': '#009fdd'
            };
        </script>
        <script src="{{ asset('js/cookie.notice.js') }}"></script>
    @endif
@endif

@if (
    !\Request::is('accounting/*') && 
    !\Request::is('human-resource/*') && 
    !\Request::is('administrative/general-services/*') && 
    !\Request::is('general-services/*') && 
    !\Request::is('components/*') && 
    !\Request::is('finance/*') && 
    !\Request::is('for-approvals/*') && 
    !\Request::is('treasury/*') &&
    !\Request::is('health-and-safety/setup-data/item-managements') &&
    !\Request::is('business-permit/application') && 
    !\Request::is('reports/accounting') && 
    !\Request::is('economic-and-investment/*') &&
    !\Request::is('profile')
)
<script>

    feather.replace();
    var pctoggle = document.querySelector("#pct-toggler");
    if (pctoggle) {
        pctoggle.addEventListener("click", function () {
            if (
                !document.querySelector(".pct-customizer").classList.contains("active")
            ) {
                document.querySelector(".pct-customizer").classList.add("active");
            } else {
                document.querySelector(".pct-customizer").classList.remove("active");
            }
        });
    }

    var themescolors = document.querySelectorAll(".themes-color > a");
    for (var h = 0; h < themescolors.length; h++) {
        var c = themescolors[h];

        c.addEventListener("click", function (event) {
            var targetElement = event.target;
            if (targetElement.tagName == "SPAN") {
                targetElement = targetElement.parentNode;
            }
            var temp = targetElement.getAttribute("data-value");
            removeClassByPrefix(document.querySelector("body"), "theme-");
            document.querySelector("body").classList.add(temp);
        });
    }
    //
    // var custthemebg = document.querySelector("#cust-theme-bg");
    // custthemebg.addEventListener("click", function () {
    //     if (custthemebg.checked) {
    //         document.querySelector(".dash-sidebar").classList.add("transprent-bg");
    //         document
    //             .querySelector(".dash-header:not(.dash-mob-header)")
    //             .classList.add("transprent-bg");
    //     } else {
    //         document.querySelector(".dash-sidebar").classList.remove("transprent-bg");
    //         document
    //             .querySelector(".dash-header:not(.dash-mob-header)")
    //             .classList.remove("transprent-bg");
    //     }
    // });

    // {{--var custdarklayout = document.querySelector("#cust-darklayout");--}}
    // {{--custdarklayout.addEventListener("click", function () {--}}
    // {{--    if (custdarklayout.checked) {--}}
    // {{--        document--}}
    // {{--            .querySelector(".m-header > .b-brand > .logo-lg")--}}
    // {{--            .setAttribute("src", "{{ asset('js/chatify/autosize.js') }}../assets/images/logo.svg");--}}
    // {{--        document--}}
    // {{--            .querySelector("#main-style-link")--}}
    // {{--            .setAttribute("href", "{{ asset('js/chatify/autosize.js') }}../assets/css/style-dark.css");--}}
    // {{--    } else {--}}
    // {{--        document--}}
    // {{--            .querySelector(".m-header > .b-brand > .logo-lg")--}}
    // {{--            .setAttribute("src", "{{ asset('js/chatify/autosize.js') }}../assets/images/logo-dark.svg");--}}
    // {{--        document--}}
    // {{--            .querySelector("#main-style-link")--}}
    // {{--            .setAttribute("href", "{{ asset('js/chatify/autosize.js') }}../assets/css/style.css");--}}
    // {{--    }--}}
    // {{--});--}}

    function removeClassByPrefix(node, prefix) {
        for (let i = 0; i < node.classList.length; i++) {
            let value = node.classList[i];
            if (value.startsWith(prefix)) {
                node.classList.remove(value);
            }
        }
    }
</script>
@endif