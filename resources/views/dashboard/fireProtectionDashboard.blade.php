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
                                                <i class="ti-files "></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Application Form')}}</p>
                                            <h6 class="mb-3">{{__('Pending Verification')}}</h6>
                                            <h3 class="mb-0">{{$afPendingVerificationCount->af_pending_verification}}

                                            </h3>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-lg-3 col-6">
                                   
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-info">
                                                <i class="ti-file"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Application Form')}}</p>
                                            <h6 class="mb-3">{{__('Pending Approval')}}</h6>
                                            <h3 class="mb-0">{{$afPendingApprovalCount->af_pending_approval}}
                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                  
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-secondary">
                                                <i class="ti-stamp "></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Application Form')}}</p>
                                            <h6 class="mb-3">{{__('Approved')}}</h6>
                                            <h3 class="mb-0">{{$applicationFormApprovedCount->application_form_approved}}</h3>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-success">
                                                <i class="ti-write "></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Pending')}}</p>
                                            <h6 class="mb-3">{{__('For Assessment')}}</h6>
                                            <h3 class="mb-0">{{$pendingForAssessment}}</h3>
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
                                            <div class="theme-avtar bg-warning">
                                                <i class="ti-credit-card "></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Payment Status')}}</p>
                                            <h6 class="mb-3">{{__('Pending')}}</h6>
                                            <h3 class="mb-0">{{$assessmentCount}}

                                            </h3>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-lg-3 col-6">
                                   
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-danger">
                                                <i class="ti-receipt "></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Payment Status')}}</p>
                                            <h6 class="mb-3">{{__('Paid')}}</h6>
                                            <h3 class="mb-0">{{$assessmentCountPending}}
                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                  
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-primary">
                                                <i class="ti-zip  "></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Total Issued')}}</p>
                                            <h6 class="mb-3">{{__('Certificate')}}</h6>
                                            <h3 class="mb-0">0</h3>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-info">
                                                <i class="ti-harddrives "></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Application Form')}}</p>
                                            <h6 class="mb-3">{{__('Total')}}</h6>
                                            <h3 class="mb-0">{{$applicationFormsCount}}</h3>
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
                                                                ->where('endorsing_dept_id', 1)
                                                                ->where('busn_office_barangay_id', $item->barangay_id)
                                                                ->count();
                                                            return $ends_total;
                                                        });
                                                    @endphp

                                                    @foreach($top_business_endorsment as $item)
                                                        @php
                                                            $ends_not_started = $bplo_business_endorsment->where('endorsing_dept_id', 1)->where('busn_office_barangay_id', $item->barangay_id)->where('bend_status', 0)->count();
                                                            $ends_in_progress = $bplo_business_endorsment->where('endorsing_dept_id', 1)->where('busn_office_barangay_id', $item->barangay_id)->where('bend_status', 1)->count();
                                                            $ends_completed = $bplo_business_endorsment->where('endorsing_dept_id', 1)->where('busn_office_barangay_id', $item->barangay_id)->where('bend_status', 2)->count();
                                                            $ends_declined = $bplo_business_endorsment->where('endorsing_dept_id', 1)->where('busn_office_barangay_id', $item->barangay_id)->where('bend_status', 3)->count();
                                                            $ends_total = $bplo_business_endorsment->where('endorsing_dept_id', 1)->where('busn_office_barangay_id', $item->barangay_id)->count();
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
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg-3 col-6">
                                   
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-secondary">
                                                <i class="ti-book"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Occupancy Type')}}</p>
                                            <h6 class="mb-3">{{__('Industrial')}}</h6>
                                            <h3 class="mb-0">{{$applicationFormsIndusCount}}

                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-success">
                                                <i class="ti-blackboard "></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Occupancy Type')}}</p>
                                            <h6 class="mb-3">{{__('Educational')}}</h6>
                                            <h3 class="mb-0">{{$applicationEduFormsCount}}
                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-warning">
                                                <i class="ti-clipboard "></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Occupancy Type')}}</p>
                                            <h6 class="mb-3">{{__('Detention & Correctional')}}</h6>
                                            <h3 class="mb-0">{{$occupancyDetan}}</h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                   
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-danger">
                                                <i class="ti-agenda "></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Occupancy Type')}}</p>
                                            <h6 class="mb-3">{{__('Mercantile')}}</h6>
                                            <h3 class="mb-0">{{ $occupancyMercantile}} </h3>
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
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Occupancy Type')}}</p>
                                            <h6 class="mb-3">{{__('Business')}}</h6>
                                            <h3 class="mb-0">
                                               {{$occupancyTypeBusiness}}
                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-info">
                                                <i class="ti-panel"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Occupancy Type')}}</p>
                                            <h6 class="mb-3">{{__('Healthcare')}}</h6>
                                            <h3 class="mb-0">  {{$occupancyTypeHealth}}
                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">      
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-secondary">
                                                <i class="ti-menu-alt"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Occupancy Type')}}</p>
                                            <h6 class="mb-3">{{__('Storage')}}</h6>
                                            <h3 class="mb-0">{{$occupancyTypeStorage}} </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-success">
                                                <i class="ti-ink-pen"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Occupancy Type')}}</p>
                                            <h6 class="mb-3">{{__('Single & Two-Family Dwelling')}}</h6>
                                            <h3 class="mb-0">{{$occupancyTypeStorage}} </h3>
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
                                            <div class="theme-avtar bg-warning">
                                                <i class="ti-briefcase "></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Occupancy Type')}}</p>
                                            <h6 class="mb-3">{{__('Miscellaneous')}}</h6>
                                            <h3 class="mb-0">
                                               {{$occupancyTypeMiscellaneous}}
                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-danger">
                                                <i class="ti-bag"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Occupancy Type')}}</p>
                                            <h6 class="mb-3">{{__('Theather')}}</h6>
                                            <h3 class="mb-0">  {{$occupancyTypeTheather}}
                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">      
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-primary">
                                                <i class="ti-home "></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Occupancy Type')}}</p>
                                            <h6 class="mb-3">{{__('Residential')}}</h6>
                                            <h3 class="mb-0"> {{$occupancyTypeResidential}} </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-info">
                                                <i class="ti-location-pin"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Occupancy Type')}}</p>
                                            <h6 class="mb-3">{{__('Small|General Business')}}</h6>
                                            <h3 class="mb-0"> {{$occupancyTypeSmall}} </h3>
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
                                            <div class="theme-avtar bg-secondary">
                                                <i class="ti-notepad "></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Occupancy Type')}}</p>
                                            <h6 class="mb-3">{{__('Gasoline Service Station')}}</h6>
                                            <h3 class="mb-0">
                                            {{$occupancyTypeGasoline}}
                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-success">
                                                <i class="ti-shield "></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Occupancy Type')}}</p>
                                            <h6 class="mb-3">{{__('Place of Assembly')}}</h6>
                                            <h3 class="mb-0">  {{$occupancyTypeAssembly}}
                                            </h3>
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
                                    <h5 class="mt-1 mb-0">{{__('Top 5 Barangays')}}</h5>
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
                                                    <td>{{$row->Barangay}}</td>
                                                    <td>{{ number_format($row->Total, 2, '.', ',') }}</td>
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
                                    <h5 class="mt-1 mb-0">{{__('Business Application Status')}}</h5>
                                    <div class="row mt-4">

                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-danger">
                                                    <i class="ti-layers-alt"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('New[Today]')}}</p>
                                                    <h4 class="mb-0 text-danger">{{$businessAppStatusToday->today}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-info">
                                                    <i class="ti-folder "></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Renew[Today]')}}</p>
                                                    <h4 class="mb-0 text-info">{{$businessAppStatusRenewToday->today}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                       
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-info">
                                                    <i class="ti-desktop"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('New[This Month]')}}</p>
                                                    <h4 class="mb-0 text-info">{{$businessAppStatusMonth->this_month}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-warning">
                                                    <i class="ti-pencil-alt"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Renew[This Month]')}}</p>
                                                    <h4 class="mb-0 text-warning">{{$businessAppStatusRenewToday->today}}</h4>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-warning">
                                                    <i class="ti-alert"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('New[This Year]')}}</p>
                                                    <h4 class="mb-0 text-warning">{{$businessAppStatusRenewMonth->this_month}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-success">
                                                    <i class="ti-lock "></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Renew[This Year]')}}</p>
                                                    <h4 class="mb-0 text-success">{{$businessAppStatusRenewYear->year}}</h4>

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
                                    <h5 class="mt-1 mb-0">{{__('Top 5 Taxpayers')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Taxpayer Name')}}</th>
                                                <th>{{__('Total')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(!empty($top5Taxpayer))
                                                @foreach($top5Taxpayer as $row)
                                                <tr>
                                                    <td>{{$row->taxpayer_name}}</td>
                                                    <td>{{ number_format($row->total_amt, 2, '.', ',') }}</td>
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
                                    <h5 class="mt-1 mb-0">{{__('Income vs Receipt Status')}}</h5>
                                    <div class="row mt-4">

                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-danger">
                                                    <i class="ti-layers-alt"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Income[Today]')}}</p>
                                                    <h4 class="mb-0 text-danger">{{number_format($incomeToday->today, 2, '.', ',') }}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-info">
                                                    <i class="ti-folder "></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Total Receipts[Today]')}}</p>
                                                    <h4 class="mb-0 text-info">{{ number_format($receiptToday->today, 0, '.', ',') }}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-info">
                                                    <i class="ti-desktop"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Income[This Month]')}}</p>
                                                    <h4 class="mb-0 text-info">{{number_format($incomeThisMonth->this_month, 2, '.', ',') }}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-warning">
                                                    <i class="ti-pencil-alt"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Total Receipts[This Month]')}}</p>
                                                    <h4 class="mb-0 text-warning">{{ number_format($receiptThisMonth->this_month, 0, '.', ',') }} </h4>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-secondary">
                                                    <i class="ti-zip "></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Income[This Year]')}}</p>
                                                    <h4 class="mb-0 text-secondary">{{number_format($incomeThisYear->this_year, 2, '.', ',') }}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-primary">
                                                    <i class="ti-stamp  "></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Total Receipts[This Year]')}}</p>
                                                    <h4 class="mb-0 text-primary">{{ number_format($receiptThisYear->this_year, 0, '.', ',') }} </h4>

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