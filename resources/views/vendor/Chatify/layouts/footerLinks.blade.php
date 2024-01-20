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
    !\Request::is('economic-and-investment/*')
)
<script src="https://js.pusher.com/7.0.3/pusher.min.js"></script>
<script >
  // Enable pusher logging - don't include this in production
  Pusher.logToConsole = true;

  var pusher = new Pusher("{{ config('chatify.pusher.key') }}", {
    encrypted: true,
    cluster: "{{ config('chatify.pusher.options.cluster') }}",
    authEndpoint: '{{route("pusher.auth")}}',
    auth: {
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }
  });
</script>
<script src="{{ asset('js/chatify/code.js') }}"></script>
<script>
  // Messenger global variable - 0 by default
  messenger = "{{ @$id }}";
</script>
@endif