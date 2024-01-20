<div class="col-lg-8 widgets accounting">
    <div class="card">
        <div class="card-header">
            <h5 class="mt-1 mb-0">{{__('Incomes vs Payables')}}</h5>
        </div>
        <div class="card-body">
            <div id="area-chart" style="height:500px;"></div>
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
   const monthNames = ["", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    Morris.Area({
        element: 'area-chart',
        data: [
            {y: 1, incomes: 10, payables: 20},
            {y: 2, incomes: 20, payables: 20},
            {y: 3, incomes: 30, payables: 20},
            {y: 4, incomes: 40, payables: 20},
            {y: 5, incomes: 50, payables: 20},
            {y: 6, incomes: 60, payables: 20},
            {y: 7, incomes: 60, payables: 20},
            {y: 8, incomes: 60, payables: 20},
            {y: 9, incomes: 60, payables: 20},
            {y: 10, incomes: 60, payables: 20},
            {y: 11, incomes: 60, payables: 20},
            {y: 12, incomes: 60, payables: 20},
        ],
        xkey: 'y',
        parseTime: false,
        ykeys: ['incomes', 'payables'],
        xLabelFormat: function (x) {
            var index = parseInt(x.src.y);
            return monthNames[index];
        },
        xLabels: "month",
        labels: ['Incomes', 'Payables'],
        // lineColors: ['#a0d0e0', '#3dbeee'],
        hideHover: 'auto'
    });
</script>
@endpush