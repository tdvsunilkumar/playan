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
                                            <div class="theme-avtar bg-danger">
                                                <i class="ti-pencil-alt "></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('For Inspection Report')}}</p>
                                            <h6 class="mb-3">{{__('Pending')}}</h6>
                                            <h3 class="mb-0">{{$inspectionRptPending}}

                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                   
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-warning">
                                                <i class="ti-save-alt"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Inspection Report')}}</p>
                                            <h6 class="mb-3">{{__('Draft')}}</h6>
                                            <h3 class="mb-0">{{$inspectionRptDraft}}
                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                  
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-success">
                                                <i class="ti-bookmark-alt"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Inspection Report')}}</p>
                                            <h6 class="mb-3">{{__('Inspected')}}</h6>
                                            <h3 class="mb-0">{{$inspectionRptInspected}}</h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-secondary">
                                                <i class="ti-save"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Inspection Report')}}</p>
                                            <h6 class="mb-3">{{__('Approved')}}</h6>
                                            <h3 class="mb-0">{{ $inspectionRptApproved }} </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg-3 col-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-info">
                                                <i class="ti-marker-alt"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('For Environmental Clearance')}}</p>
                                            <h6 class="mb-3">{{__('Pending')}}</h6>
                                            <h3 class="mb-0">{{$envClearancePending}}

                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                   
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-primary">
                                                <i class="ti-clipboard"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Environmental Clearance')}}</p>
                                            <h6 class="mb-3">{{__('Draft')}}</h6>
                                            <h3 class="mb-0">{{$envClearanceDraft}}
                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                  
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-danger">
                                                <i class=" ti-blackboard"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Environmental Clearance')}}</p>
                                            <h6 class="mb-3">{{__('Cancelled')}}</h6>
                                            <h3 class="mb-0">{{$envClearanceCancelled}} </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-success">
                                                <i class=" ti-shield"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Environmental Clearance')}}</p>
                                            <h6 class="mb-3">{{__('Approved')}}</h6>
                                            <h3 class="mb-0">0 </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Business Endorsements')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Barangay')}}</th>
                                                <th>{{__('Not Started')}}</th>
                                                <th>{{__('In-Progress')}}</th>
                                                <th>{{__('Completed')}}</th>
                                                <th>{{__('Declined')}}</th>
                                                <th>{{__('Total')}}</th>
                                            </tr>
                                            </thead>     
                                            <tbody>
                                                @if(!empty($top_business_endorsment)) 
                                                    @php
                                                        // Sort the $top_business_endorsment array by $ends_total in descending order
                                                        $top_business_endorsment = $top_business_endorsment->sortByDesc(function($item) use ($bplo_business_endorsment) {
                                                            $ends_total = $bplo_business_endorsment
                                                                ->where('endorsing_dept_id', 4)
                                                                ->where('busn_office_barangay_id', $item->barangay_id)
                                                                ->count();
                                                            return $ends_total;
                                                        });
                                                    @endphp

                                                    @foreach($top_business_endorsment as $item)
                                                        @php
                                                            $ends_not_started = $bplo_business_endorsment->where('endorsing_dept_id', 4)->where('busn_office_barangay_id', $item->barangay_id)->where('bend_status', 0)->count();
                                                            $ends_in_progress = $bplo_business_endorsment->where('endorsing_dept_id', 4)->where('busn_office_barangay_id', $item->barangay_id)->where('bend_status', 1)->count();
                                                            $ends_completed = $bplo_business_endorsment->where('endorsing_dept_id', 4)->where('busn_office_barangay_id', $item->barangay_id)->where('bend_status', 2)->count();
                                                            $ends_declined = $bplo_business_endorsment->where('endorsing_dept_id', 4)->where('busn_office_barangay_id', $item->barangay_id)->where('bend_status', 3)->count();
                                                            $ends_total = $bplo_business_endorsment->where('endorsing_dept_id', 4)->where('busn_office_barangay_id', $item->barangay_id)->count();
                                                        @endphp
                                                    
                                                        <tr>
                                                            <td>{{$item->barangay}}</td>
                                                            <td>{{$ends_not_started}}</td>
                                                            <td>{{$ends_in_progress}}</td>
                                                            <td>{{$ends_completed}}</td>
                                                            <td>{{$ends_declined}}</td>
                                                            <td>{{$ends_total}}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="6">
                                                            <div class="text-center">
                                                                <h6>{{__('there is no barangay')}}</h6>
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
                <div class="col-xxl-5">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Top 5 Barangays[Inspection Report]')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Barangay')}}</th>
                                                <th>{{__('Total')}}</th>
                                            </tr>
                                            </thead>     
                                            <tbody>
                                                @if(!empty($top5Barangay))
                                                    @foreach($top5Barangay as $row)
                                                    <tr>
                                                        <td>{{$row->barangay}}</td>
                                                        <td>{{ $row->total }}</td>
                                                    </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="4">
                                                            <div class="text-center">
                                                                <h6>{{__('there is no barangay')}}</h6>
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
                                    <h5 class="mt-1 mb-0">{{__('Inspection Report Status')}}</h5>
                                    <div class="row mt-4">

                                        <div class="col-md-12 col-12 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-primary">
                                                    <i class="ti-save"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Active')}}</p>
                                                    <h4 class="mb-0 text-primary">{{$inspStatusRptActive}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                      
                                        
                                        <div class="col-md-12 col-12 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-danger">
                                                    <i class="ti-trash"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Cancelled')}}</p>
                                                    <h4 class="mb-0 text-danger">{{$inspStatusRptCancelled}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                       
                                        
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Top 5 Barangays[Environmental Clearance]')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Barangay')}}</th>
                                                <th>{{__('Total')}}</th>
                                            </tr>
                                            </thead>     
                                            <tbody>
                                                @if(!empty($top5BarangayEnvClearance))
                                                    @foreach($top5BarangayEnvClearance as $row)
                                                    <tr>
                                                        <td>{{$row->barangay}}</td>
                                                        <td>{{ $row->total }}</td>
                                                    </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="4">
                                                            <div class="text-center">
                                                                <h6>{{__('there is no barangay')}}</h6>
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
                                    <h5 class="mt-1 mb-0">{{__('Environmental Clearance Status')}}</h5>
                                    <div class="row mt-4">

                                        <div class="col-md-12 col-12 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-primary">
                                                    <i class="ti-save"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Active')}}</p>
                                                    <h4 class="mb-0 text-primary">{{$env_clearance_active}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                      
                                        
                                        <div class="col-md-12 col-12 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-danger">
                                                    <i class="ti-trash"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Cancelled')}}</p>
                                                    <h4 class="mb-0 text-danger">{{$env_clearance_cancelled}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                       
                                        
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