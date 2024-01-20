<div class="col-lg-4 widgets accounting">
    <div class="card">
        <div class="card-header">
            <h5 class="mt-1 mb-0">{{__('Chart of Accounts')}}</h5>
        </div>
        <div class="card-body">
            <div id="pie-chart" style="height:500px;"></div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/morris.js-0.5.1/morris.css?v='.filemtime(getcwd().'/assets/vendors/morris.js-0.5.1/morris.css').'') }}"/>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/morris.js-0.5.1/raphael.min.js?v='.filemtime(getcwd().'/assets/vendors/morris.js-0.5.1/raphael.min.js').'') }}"></script>
<script src="{{ asset('assets/vendors/morris.js-0.5.1/morris.js?v='.filemtime(getcwd().'/assets/vendors/morris.js-0.5.1/morris.js').'') }}"></script>
<script>
    Morris.Donut({
        element: 'pie-chart',
        data: [
            {label: "Expenses", value: 17},
            {label: "Equity", value: 15},
            {label: "Income", value: 35},
            {label: "Assets", value: 18},
            {label: "Liabilities", value: 10}
        ]
    });
</script>
@endpush