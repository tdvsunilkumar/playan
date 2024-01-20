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
                                    @php
                                    $total_walkin_zoning=$cpdo_application_forms->where('is_online',0)->count();
                                    $total_walkin_development_permit=$cpdo_development_permits->where('is_online',0)->count();
                                    @endphp
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-primary">
                                                <i class="ti-server "></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Application')}}</p>
                                            <h6 class="mb-3">{{__('Walk-In')}}</h6>
                                            <h3 class="mb-0">{{$total_walkin_zoning + $total_walkin_development_permit}}

                                            </h3>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-lg-3 col-6">
                                    @php
                                    $total_online_zoning=$cpdo_application_forms->where('is_online',1)->count();
                                    $total_online_development_permit=$cpdo_development_permits->where('is_online',1)->count();
                                    @endphp
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-info">
                                                <i class="ti-world"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Application')}}</p>
                                            <h6 class="mb-3">{{__('Online')}}</h6>
                                            <h3 class="mb-0">{{$total_online_zoning + $total_online_development_permit}}
                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                  
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-warning">
                                                <i class="ti-envelope"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Issued Clearance')}}</p>
                                            <h6 class="mb-3">{{__('Zoning')}}</h6>
                                            <h3 class="mb-0">{{$cpdo_application_forms->where('csd_id',9)->count()}}</h3>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-danger">
                                                <i class="ti-receipt"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Issued Permit')}}</p>
                                            <h6 class="mb-3">{{__('Development')}}</h6>
                                            <h3 class="mb-0">{{$cpdo_development_permits->where('csd_id',9)->count()}}</h3>
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
                                            <div class="theme-avtar bg-danger">
                                                <i class="ti-book"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Tenure')}}</p>
                                            <h6 class="mb-3">{{__('Permanent')}}</h6>
                                            <h3 class="mb-0">{{$cpdo_application_forms->where('cpt_id',1)->count()}}

                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-warning">
                                                <i class="ti-files"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Tenure')}}</p>
                                            <h6 class="mb-3">{{__('Temporary')}}</h6>
                                            <h3 class="mb-0">{{$cpdo_development_permits->where('cpt_id',1)->count()}}
                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-info">
                                                <i class="ti-zip"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Nature')}}</p>
                                            <h6 class="mb-3">{{__('Transaction')}}</h6>
                                            <h3 class="mb-0">{{$cpdo_application_forms->where('cna_id',1)->count()}}</h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                   
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-primary">
                                                <i class="ti-wallet"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Nature')}}</p>
                                            <h6 class="mb-3">{{__('Inspection')}}</h6>
                                            <h3 class="mb-0">{{$cpdo_application_forms->where('cna_id',2)->count()}} </h3>
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
                                            <div class="theme-avtar bg-primary">
                                                <i class="ti-archive"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Nature')}}</p>
                                            <h6 class="mb-3">{{__('Certificate')}}</h6>
                                            <h3 class="mb-0">
                                            {{$cpdo_application_forms->where('cna_id',3)->count()}}
                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-info">
                                                <i class="ti-agenda"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Nature')}}</p>
                                            <h6 class="mb-3">{{__('Others')}}</h6>
                                            <h3 class="mb-0">  {{$cpdo_application_forms->where('cna_id',4)->count()}}
                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">      
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-warning">
                                                <i class="ti-shield"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Payment')}}</p>
                                            <h6 class="mb-3">{{__('Exempted')}}</h6>
                                            <h3 class="mb-0"> {{$cpdo_application_forms->where('caf_excempted',1)->count()}}</h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-danger">
                                                <i class="ti-ink-pen"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Payment')}}</p>
                                            <h6 class="mb-3">{{__('Charges')}}</h6>
                                            <h3 class="mb-0">{{$cpdo_application_forms->where('caf_excempted',0)->count()}}</h3>
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
                                                                ->where('endorsing_dept_id', 2)
                                                                ->where('busn_office_barangay_id', $item->barangay_id)
                                                                ->count();
                                                            return $ends_total;
                                                        });
                                                    @endphp

                                                    @foreach($top_business_endorsment as $item)
                                                        @php
                                                            $ends_not_started = $bplo_business_endorsment->where('endorsing_dept_id', 2)->where('busn_office_barangay_id', $item->barangay_id)->where('bend_status', 0)->count();
                                                            $ends_in_progress = $bplo_business_endorsment->where('endorsing_dept_id', 2)->where('busn_office_barangay_id', $item->barangay_id)->where('bend_status', 1)->count();
                                                            $ends_completed = $bplo_business_endorsment->where('endorsing_dept_id', 2)->where('busn_office_barangay_id', $item->barangay_id)->where('bend_status', 2)->count();
                                                            $ends_declined = $bplo_business_endorsment->where('endorsing_dept_id', 2)->where('busn_office_barangay_id', $item->barangay_id)->where('bend_status', 3)->count();
                                                            $ends_total = $bplo_business_endorsment->where('endorsing_dept_id', 2)->where('busn_office_barangay_id', $item->barangay_id)->count();
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
                                    <h5 class="mt-1 mb-0">{{__('Top 5 Barangays[Zoning Clearance]')}}</h5>
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
                                                @if(!empty($top_barangays)) 
                                                @foreach($top_barangays as $item)
                                                <tr>
                                                    <td>{{$item->barangay}}</td>
                                                    <td>{{$item->total_zoning_clearance}}</td>
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
                                    <h5 class="mt-1 mb-0">{{__('Zoning Clearance Status')}}</h5>
                                    <div class="row mt-4">

                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-danger">
                                                    <i class="ti-layers-alt"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Draft')}}</p>
                                                    <h4 class="mb-0 text-danger">{{$cpdo_application_forms->where('csd_id',1)->count()}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-info">
                                                    <i class="ti-folder"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('For submission')}}</p>
                                                    <h4 class="mb-0 text-info">{{$cpdo_application_forms->where('csd_id',5)->count()}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-info">
                                                    <i class="ti-desktop"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Paid')}}</p>
                                                    <h4 class="mb-0 text-info">{{$cpdo_application_forms->where('csd_id',2)->count()}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-warning">
                                                    <i class="ti-pencil-alt"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Recommended Approved')}}</p>
                                                    <h4 class="mb-0 text-warning">{{$cpdo_application_forms->where('csd_id',6)->count()}}</h4>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-secondary">
                                                    <i class="ti-zip"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Pending')}}</p>
                                                    <h4 class="mb-0 text-secondary">{{$cpdo_application_forms->where('csd_id',3)->count()}}</h4>

                                                </div>
                                            </div>
                                        </div> 
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-primary">
                                                    <i class="ti-stamp"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Noted Approved')}}</p>
                                                    <h4 class="mb-0 text-primary">{{$cpdo_application_forms->where('csd_id',7)->count()}}</h4>

                                                </div>
                                            </div>
                                        </div> 
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-warning">
                                                    <i class="ti-alert"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Inspected')}}</p>
                                                    <h4 class="mb-0 text-warning">{{$cpdo_application_forms->where('csd_id',4)->count()}}</h4>

                                                </div>
                                            </div>
                                        </div> 
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-success">
                                                    <i class="ti-lock"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Completed')}}</p>
                                                    <h4 class="mb-0 text-success">{{$cpdo_application_forms->where('csd_id',9)->count()}}</h4>

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
                                    <h5 class="mt-1 mb-0">{{__('Top 5 Barangays[Development Permit]')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Barangay Name')}}</th>
                                                <th>{{__('Total')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                          
                                            
                                            @if(!empty($top_barangay_dev_permit)) 
                                                @foreach($top_barangay_dev_permit as $item)
                                                <tr>
                                                    <td>{{$item->barangay_name}}</td>
                                                    <td>{{$item->total}}</td>
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
                                    <h5 class="mt-1 mb-0">{{__('Development Permit Status')}}</h5>
                                    <div class="row mt-4">

                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-danger">
                                                    <i class="ti-layers-alt"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Draft')}}</p>
                                                    <h4 class="mb-0 text-danger">{{$cpdo_development_permits->where('csd_id',1)->count()}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-info">
                                                    <i class="ti-folder"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('For submission')}}</p>
                                                    <h4 class="mb-0 text-info">{{$cpdo_development_permits->where('csd_id',5)->count()}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-info">
                                                    <i class="ti-desktop"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Paid')}}</p>
                                                    <h4 class="mb-0 text-info">{{$cpdo_development_permits->where('csd_id',2)->count()}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-warning">
                                                    <i class="ti-pencil-alt"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Recommended Approved')}}</p>
                                                    <h4 class="mb-0 text-warning">{{$cpdo_development_permits->where('csd_id',6)->count()}}</h4>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-secondary">
                                                    <i class="ti-zip"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Pending')}}</p>
                                                    <h4 class="mb-0 text-secondary">{{$cpdo_development_permits->where('csd_id',3)->count()}}</h4>

                                                </div>
                                            </div>
                                        </div> 
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-primary">
                                                    <i class="ti-stamp"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Noted Approved')}}</p>
                                                    <h4 class="mb-0 text-primary">{{$cpdo_development_permits->where('csd_id',7)->count()}}</h4>

                                                </div>
                                            </div>
                                        </div> 
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-warning">
                                                    <i class="ti-alert"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Inspected')}}</p>
                                                    <h4 class="mb-0 text-warning">{{$cpdo_development_permits->where('csd_id',4)->count()}}</h4>

                                                </div>
                                            </div>
                                        </div> 
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-success">
                                                    <i class="ti-lock"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Completed')}}</p>
                                                    <h4 class="mb-0 text-success">{{$cpdo_development_permits->where('csd_id',9)->count()}}</h4>

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