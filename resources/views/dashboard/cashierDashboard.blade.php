@push('script-page')
    <script>
       
        (function () {
            var chartBarOptions = {
                series: [
                    {
                        name: "{{__('Income')}}",
                        data:{!! json_encode($incExpLineChartData['income']) !!}
                    },
                    {
                        name: "{{__('Expense')}}",
                        data: {!! json_encode($incExpLineChartData['expense']) !!}
                    }
                ],

                chart: {
                    height: 250,
                    type: 'area',
                    // type: 'line',
                    dropShadow: {
                        enabled: true,
                        color: '#000',
                        top: 18,
                        left: 7,
                        blur: 10,
                        opacity: 0.2
                    },
                    toolbar: {
                        show: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: 2,
                    curve: 'smooth'
                },
                title: {
                    text: '',
                    align: 'left'
                },
                xaxis: {
                    categories:{!! json_encode($incExpLineChartData['day']) !!},
                    title: {
                        text: '{{ __("Days") }}'
                    }
                },
                colors: ['#6fd944', '#6fd944'],

                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: false,
                },
                // markers: {
                //     size: 4,
                //     colors: ['#ffa21d', '#FF3A6E'],
                //     opacity: 0.9,
                //     strokeWidth: 2,
                //     hover: {
                //         size: 7,
                //     }
                // },
                yaxis: {
                    title: {
                        text: '{{ __("Amount") }}'
                    },

                }

            };
            var arChart = new ApexCharts(document.querySelector("#cash-flow"), chartBarOptions);
            arChart.render();
        })();
        (function () {
            var options = {
                chart: {
                    height: 180,
                    type: 'bar',
                    toolbar: {
                        show: false,
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: 2,
                    curve: 'smooth'
                },
                series: [{
                    name: "{{__('Income')}}",
                    data: {!! json_encode($incExpBarChartData['income']) !!}
                }, {
                    name: "{{__('Expense')}}",
                    data: {!! json_encode($incExpBarChartData['expense']) !!}
                }],
                xaxis: {
                    categories: {!! json_encode($incExpBarChartData['month']) !!},
                },
                colors: ['#3ec9d6', '#FF3A6E'],
                fill: {
                    type: 'solid',
                },
                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: true,
                    position: 'top',
                    horizontalAlign: 'right',
                },
                // markers: {
                //     size: 4,
                //     colors: ['#3ec9d6', '#FF3A6E',],
                //     opacity: 0.9,
                //     strokeWidth: 2,
                //     hover: {
                //         size: 7,
                //     }
                // }
            };
            var chart = new ApexCharts(document.querySelector("#incExpBarChart"), options);
            chart.render();
        })();

        (function () {
            var options = {
                chart: {
                    height: 140,
                    type: 'donut',
                },
                dataLabels: {
                    enabled: false,
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                        }
                    }
                },
                series: {!! json_encode($expenseCatAmount) !!},
                colors: {!! json_encode($expenseCategoryColor) !!},
                labels: {!! json_encode($expenseCategory) !!},
                legend: {
                    show: true
                }
            };
            var chart = new ApexCharts(document.querySelector("#expenseByCategory"), options);
            chart.render();
        })();

        (function () {
            var options = {
                chart: {
                    height: 140,
                    type: 'donut',
                },
                dataLabels: {
                    enabled: false,
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                        }
                    }
                },
                series: {!! json_encode($incomeCatAmount) !!},
                colors: {!! json_encode($incomeCategoryColor) !!},
                labels:  {!! json_encode($incomeCategory) !!},
                legend: {
                    show: true
                }
            };
            var chart = new ApexCharts(document.querySelector("#incomeByCategory"), options);
            chart.render();
        })();
        
    </script>
@endpush

        <div class="col-sm-12">
            <div class="row">
                <div class="col-xxl-7">
                  
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-primary">
                                                <i class="ti-server "></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Transactions[Today]')}}</p>
                                            <h6 class="mb-3">{{__('Total')}}</h6>
                                            <h3 class="mb-0">{{$total_transaction_today}}

                                            </h3>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-lg-3 col-6">
                                   
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-info">
                                                <i class="ti-shield  "></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Transactions[Today]')}}</p>
                                            <h6 class="mb-3">{{__('Walkin')}}</h6>
                                            <h3 class="mb-0">{{$walkin_transaction_today}}
                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                  
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-secondary">
                                                <i class="ti-world "></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Transactions[Today]')}}</p>
                                            <h6 class="mb-3">{{__('Online')}}</h6>
                                            <h3 class="mb-0">{{$online_transaction_today}}</h3>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-danger">
                                                <i class="ti-trash  "></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Transactions[Today]')}}</p>
                                            <h6 class="mb-3">{{__('Cancelled')}}</h6>
                                            <h3 class="mb-0">{{$cancelled_transaction_today}}</h3>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__("Today's Income")}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Section')}}</th>
                                                <th>{{__('Transactions')}}</th>
                                                <th>{{__('Walkin')}}</th>
                                                <th>{{__('Online')}}</th>
                                                <th>{{__('Total')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($sections)) 
                                                @foreach($sections as $item)
                                                <tr>
                                                    <td>{{$item['section']}}</td>
                                                    <td>{{$item['transaction']}}</td>
                                                    <td>{{$item['walkin']}}</td>
                                                    <td>{{$item['online']}}</td>
                                                    <td>{{$item['total']}}</td>
                                                </tr>
                                                @endforeach
                                                @else
                                                <tr>
                                                    <td colspan="4">
                                                        <div class="text-center">
                                                            <h6>{{__('there is no data')}}</h6>
                                                        </div>
                                                    </td>
                                                </tr>
                                                 @endif
                               
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-warning">
                                                <i class="ti-tablet"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Transactions[This Month]')}}</p>
                                            <h6 class="mb-3">{{__('Total')}}</h6>
                                            <h3 class="mb-0">{{$total_transaction_this_month}}
                                            </h3>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-lg-3 col-6">
                                   
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-primary">
                                                <i class="ti-location-arrow  "></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Transactions[This Month]')}}</p>
                                            <h6 class="mb-3">{{__('Walkin')}}</h6>
                                            <h3 class="mb-0">{{$walkin_transaction_this_month}}
                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                  
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-info">
                                                <i class="ti-layers-alt "></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Transactions[This Month]')}}</p>
                                            <h6 class="mb-3">{{__('Online')}}</h6>
                                            <h3 class="mb-0">{{$online_transaction_this_month}}</h3>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-secondary">
                                                <i class="ti-pencil-alt  "></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Transactions[This Month]')}}</p>
                                            <h6 class="mb-3">{{__('Cancelled')}}</h6>
                                            <h3 class="mb-0">{{$cancelled_transaction_this_month}}</h3>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-5">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Top 5 Taxpayers')}}
                                    <span class="float-end text-muted">{{__('Year').' - '.$currentYear}}</span>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('TAXPAYER NAME')}}</th>
                                                <th>{{__('Total')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($top_taxpayers)) 
                                                @foreach($top_taxpayers as $item)
                                                <tr>
                                                    <td>{{$item->name}}</td>
                                                    @php $item->totalfee = number_format($item->totalfee, 2, '.', ','); @endphp
                                                    <td>{{$item->totalfee}}</td>
                                                </tr>
                                                @endforeach
                                                @else
                                                <tr>
                                                    <td colspan="4">
                                                        <div class="text-center">
                                                            <h6>{{__('there is no data')}}</h6>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endif
                                           
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                       
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="mt-1 mb-0">{{__('Income [Walkin vs Online]')}}</h5>
                                    <div class="row mt-4">

                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-primary">
                                                    <i class="ti-receipt"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__("Today[Walkin]")}}</p>
                                                    <h4 class="mb-0 text-primary">{{($TodayWalkin[0]->total) ? number_format($TodayWalkin[0]->total, 2, '.', ','):'0.00'}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-info">
                                                    <i class="ti-cloud-down"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Today[Online]')}}</p>
                                                    <h4 class="mb-0 text-info">{{($TodayOnline[0]->total) ? number_format($TodayOnline[0]->total, 2, '.', ','):'0.00'}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                       
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-secondary">
                                                    <i class="ti-wallet"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('This Month[Walkin]')}}</p>
                                                    <h4 class="mb-0 text-secondary">{{($MonthWalkin[0]->total) ? number_format($MonthWalkin[0]->total, 2, '.', ',') :'0.00'}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-success">
                                                    <i class="ti-harddrive"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('This Month[Online]')}}</p>
                                                    <h4 class="mb-0 text-success">{{($MonthOnline[0]->total) ? number_format($MonthOnline[0]->total, 2, '.', ','):'0.00'}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-warning">
                                                    <i class="ti-mobile"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('This Year[Walkin]')}}</p>
                                                    <h4 class="mb-0 text-warning">{{($YearWalkin[0]->total) ? number_format($YearWalkin[0]->total, 2, '.', ','):'0.00'}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-danger">
                                                    <i class="ti-bookmark-alt"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('This Year[Online]')}}</p>
                                                    <h4 class="mb-0 text-danger">{{($YearOnline[0]->total) ? number_format($YearOnline[0]->total, 2, '.', ','):'0.00'}}</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                       <!--  <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Top 5 Barangays')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Barangay')}}</th>
                                                <th>{{__('Transactions')}}</th>
                                                <th>{{__('Amount Due')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                          
                                               
                                                <tr>
                                                    <td colspan="4">
                                                        <div class="text-center">
                                                             <h6>{{__('there is no data')}}</h6>
                                                        </div>
                                                    </td>
                                                </tr>
                                                
                                           
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Collection Officers')}}
                                    <span class="float-end text-muted">{{__('Year').' - '.$currentYear}}</span>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Cashier Name')}}</th>
                                                <th>{{__('Last OR No.')}}</th>
                                                <th>{{__('Accountable Form')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                               @if(!empty($CollOfficer)) 
                                                @foreach($CollOfficer as $val)
                                                <tr>
                                                    <td>{{$val->Name}}</td>
                                                    <td>{{$val->Last_OR_No}}</td>
                                                    <td>{{$val->Accountable_Forms}}</td>
                                                </tr>
                                                @endforeach
                                                @else
                                               
                                                <tr>
                                                    <td colspan="4">
                                                        <div class="text-center">
                                                             <h6>{{__('there is no data')}}</h6>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endif
                                           
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Top 3 Collector')}}
                                    <span class="float-end text-muted">{{__('Year').' - '.$currentYear}}</span>
                                    </h5>
                                    
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Cashier Name')}}</th>
                                                <th>{{__('Transactions')}}</th>
                                                <th>{{__('Amount Due')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                               @if(!empty($top_collector)) 
                                                @foreach($top_collector as $val)
                                                <tr>
                                                    <td>{{$val->Name}}</td>
                                                    <td>{{$val->totalcount}}</td>
                                                    <td>{{Helper::decimal_format($val->totalamt)}} </td>
                                                </tr>
                                                @endforeach
                                                @else
                                                <tr>
                                                    <td colspan="4">
                                                        <div class="text-center">
                                                             <h6>{{__('there is no data')}}</h6>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                        
                    </div>
                </div>
                <div class="col-xxl-12">
                    <div class="card">
                        <div class="card-header">

                            <h5>{{__('Goal')}}</h5>
                        </div>
                        <div class="card-body">
                            @forelse($goals as $goal)
                                @php
                                    $total= $goal->target($goal->type,$goal->from,$goal->to,$goal->amount)['total'];
                                    $percentage=$goal->target($goal->type,$goal->from,$goal->to,$goal->amount)['percentage'];
                                    $per=number_format($goal->target($goal->type,$goal->from,$goal->to,$goal->amount)['percentage'], Utility::getValByName('decimal_number'), '.', '');

                                @endphp
                                <div class="card border-success border-2 border-bottom-0 border-start-0 border-end-0">
                                    <div class="card-body">
                                        <div class="form-check">
                                            <label class="form-check-label d-block" for="customCheckdef1">
                                                <span>
                                                    <span class="row align-items-center">
                                                        <span class="col">
                                                            <span class="text-muted text-sm">{{__('Name')}}</span>
                                                            <h6 class="text-nowrap mb-3 mb-sm-0">{{$goal->name}}</h6>
                                                        </span>
                                                        <span class="col">
                                                            <span class="text-muted text-sm">{{__('Type')}}</span>
                                                            <h6 class="mb-3 mb-sm-0">{{ __(\App\Models\Goal::$goalType[$goal->type]) }}</h6>
                                                        </span>
                                                        <span class="col">
                                                            <span class="text-muted text-sm">{{__('Duration')}}</span>
                                                            <h6 class="mb-3 mb-sm-0">{{$goal->from .' To '.$goal->to}}</h6>
                                                        </span>
                                                        <span class="col">
                                                            <span class="text-muted text-sm">{{__('Target')}}</span>
                                                            <h6 class="mb-3 mb-sm-0">{{\Auth::user()->priceFormat($total).' of '. \Auth::user()->priceFormat($goal->amount)}}</h6>
                                                        </span>
                                                        <span class="col">
                                                            <span class="text-muted text-sm">{{__('Progress')}}</span>
                                                            <h6 class="mb-2 d-block">{{number_format($goal->target($goal->type,$goal->from,$goal->to,$goal->amount)['percentage'], Utility::getValByName('decimal_number'), '.', '')}}%</h6>
                                                            <div class="progress mb-0">
                                                                @if($per<=33)
                                                                    <div class="progress-bar bg-danger" style="width: {{$per}}%"></div>
                                                                @elseif($per>=33 && $per<=66)
                                                                    <div class="progress-bar bg-warning" style="width: {{$per}}%"></div>
                                                                @else
                                                                    <div class="progress-bar bg-primary" style="width: {{$per}}%"></div>
                                                                @endif
                                                            </div>
                                                        </span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="card pb-0">
                                    <div class="card-body text-center">
                                        <h6>{{__('There is no goal.')}}</h6>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>





            </div>
        </div>