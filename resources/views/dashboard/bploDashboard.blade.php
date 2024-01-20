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
                    @php
                    $new_business_type=$bplo_business->where('app_code',1)->count();
                    $renew_business_type=$bplo_business->where('app_code',2)->count();
                    $license_issued_business=$bplo_business->where('busn_app_status',6)->count();
                    $retirement_isssued=$bplo_business->where('app_code',3)->where('busn_app_status',8)->count();
                    @endphp
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-primary">
                                                <i class="ti-agenda"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Business')}}</p>
                                            <h6 class="mb-3">{{__('New')}}</h6>
                                            <h3 class="mb-0">{{ number_format($new_business_type) }}

                                            </h3>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-lg-3 col-6">
                                   
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-info">
                                                <i class="ti-write"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Business')}}</p>
                                            <h6 class="mb-3">{{__('Renewal')}}</h6>
                                            <h3 class="mb-0">{{number_format($renew_business_type)}}
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
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Issued')}}</p>
                                            <h6 class="mb-3">{{__('License')}}</h6>
                                            <h3 class="mb-0">{{ number_format($license_issued_business) }} </h3>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-danger">
                                                <i class="ti-receipt"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Retirement Certificate')}}</p>
                                            <h6 class="mb-3">{{__('Issued')}}</h6>
                                            <h3 class="mb-0">{{ number_format($retirement_isssued) }} </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Endorsements')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Departments')}}</th>
                                                <th>{{__('Not Started')}}</th>
                                                <th>{{__('In-Progress')}}</th>
                                                <th>{{__('Completed')}}</th>
                                                <th>{{__('Declined')}}</th>
                                                <th>{{__('Total')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                
                                                @foreach ($bplo_endorsing_dept as $busnEnds)
                                                @php
                                                $ends_not_started=$bplo_business_endorsment->where('endorsing_dept_id',$busnEnds->id)->where('bend_status',0)->count();
                                                $ends_in_progress=$bplo_business_endorsment->where('endorsing_dept_id',$busnEnds->id)->where('bend_status',1)->count();
                                                $ends_completed=$bplo_business_endorsment->where('endorsing_dept_id',$busnEnds->id)->where('bend_status',2)->count();
                                                $ends_declined=$bplo_business_endorsment->where('endorsing_dept_id',$busnEnds->id)->where('bend_status',3)->count();
                                                $ends_total=$bplo_business_endorsment->where('endorsing_dept_id',$busnEnds->id)->count();
                                                @endphp
                                                <tr>
                                                    <th>{{ $busnEnds->edept_name }}</th>
                                                    <th>{{number_format($ends_not_started)}}</th>
                                                    <th>{{number_format($ends_in_progress)}}</th>
                                                    <th>{{number_format($ends_completed)}}</th>
                                                    <th>{{ number_format($ends_declined) }}</th>
                                                    <th>{{ number_format($ends_total) }}</th>
                                                </tr>
                                                @endforeach
                                                @if(empty($topBarangays))
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
                                            <div class="theme-avtar bg-danger">
                                                <i class="ti-book"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Pending')}}</p>
                                            <h6 class="mb-3">{{__('Verifications')}}</h6>
                                            <h3 class="mb-0">{{ number_format($bplo_business->where('busn_app_status',1)->count()) }}

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
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Pending')}}</p>
                                            <h6 class="mb-3">{{__('For Endorsements')}}</h6>
                                            <h3 class="mb-0">{{ number_format($bplo_business->where('busn_app_status',2)->count()) }}
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
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Pending')}}</p>
                                            <h6 class="mb-3">{{__('For Assessments')}}</h6>
                                            <h3 class="mb-0">{{ number_format($bplo_business->where('busn_app_status',3)->count()) }} </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                   
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-primary">
                                                <i class="ti-wallet"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Pending')}}</p>
                                            <h6 class="mb-3">{{__('For Payment')}}</h6>
                                            <h3 class="mb-0">{{ number_format($bplo_business->where('busn_app_status',4)->count() )}} </h3>
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
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Pending')}}</p>
                                            <h6 class="mb-3">{{__('For Issuance')}}</h6>
                                            <h3 class="mb-0">{{ number_format($bplo_business->where('busn_app_status',5)->count()) }}

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
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Applications')}}</p>
                                            <h6 class="mb-3">{{__('Declined')}}</h6>
                                            <h3 class="mb-0">{{ number_format($bplo_business->where('busn_app_status',7)->count() )}}
                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    @php
                                        $fire_not_started=$bplo_business_endorsment->where('endorsing_dept_id',1)->where('bend_status',0)->count();
                                        $fire_in_progress=$bplo_business_endorsment->where('endorsing_dept_id',1)->where('bend_status',1)->count();
                                        $fire_incomplete=$fire_not_started + $fire_in_progress;
                                    @endphp
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-warning">
                                                <i class="ti-shield"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Incomplete')}}</p>
                                            <h6 class="mb-3">{{__('Fire Protection')}}</h6>
                                            <h3 class="mb-0">{{number_format($fire_incomplete)}} </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    @php
                                        $plan_not_started=$bplo_business_endorsment->where('endorsing_dept_id',2)->where('bend_status',0)->count();
                                        $plan_in_progress=$bplo_business_endorsment->where('endorsing_dept_id',2)->where('bend_status',1)->count();
                                        $plan_incomplete=$plan_not_started + $plan_in_progress;
                                    @endphp
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-danger">
                                                <i class="ti-ink-pen"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Incomplete')}}</p>
                                            <h6 class="mb-3">{{__('Planning & Development')}}</h6>
                                            <h3 class="mb-0">{{ number_format($plan_incomplete) }} </h3>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg-3 col-6">
                                    @php
                                        $env_not_started=$bplo_business_endorsment->where('endorsing_dept_id',4)->where('bend_status',0)->count();
                                        $env_in_progress=$bplo_business_endorsment->where('endorsing_dept_id',4)->where('bend_status',1)->count();
                                        $env_incomplete=$env_not_started + $env_in_progress;
                                    @endphp
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-danger">
                                                <i class="ti-bookmark-alt"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Incomplete')}}</p>
                                            <h6 class="mb-3">{{__('Environmental')}}</h6>
                                            <h3 class="mb-0">{{ number_format($env_incomplete) }}

                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                            
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-warning">
                                                <i class="ti-layers-alt"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('No Health')}}</p>
                                            <h6 class="mb-3">{{__('Certificates')}}</h6>
                                            <h3 class="mb-0">{{ number_format($no_health_certificate->{'No Health Certificate'}) }}
                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-info">
                                                <i class="ti-folder"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('No Sanitary')}}</p>
                                            <h6 class="mb-3">{{__('Permits')}}</h6>
                                            <h3 class="mb-0">{{ number_format($no_sanitary_certificate->{'No Sanitary Permit'}) }}  </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                   
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-primary">
                                                <i class="ti ti-users"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Business Owned By')}}</p>
                                            <h6 class="mb-3">{{__('Male')}}</h6>
                                            <h3 class="mb-0">{{number_format( $business_owned_male->{'Business Owned by Male'} )}} </h3>
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
                                                <i class="ti ti-users"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Business Owned By')}}</p>
                                            <h6 class="mb-3">{{__('Female')}}</h6>
                                            <h3 class="mb-0">{{ number_format($business_owned_female->{'Business Owned by Female'} )}} </h3>

                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-info">
                                                <i class="ti-check-box"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Payment Mode')}}</p>
                                            <h6 class="mb-3">{{__('Annual')}}</h6>
                                            <h3 class="mb-0">
                                            {{ number_format($bplo_business->where('app_code','<',3)->where('pm_id',1)->whereNotIn('busn_app_status', [0, 7, 8])->count() )}} </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                   
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-primary">
                                                <i class="ti-camera"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Payment Mode')}}</p>
                                            <h6 class="mb-3">{{__('Semi-Annual')}}</h6>
                                            <h3 class="mb-0">{{ number_format($bplo_business->where('app_code','<',3)->where('pm_id',2)->whereNotIn('busn_app_status', [0, 7, 8])->count() )}} </h3>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-lg-3 col-6">
                                   
                                   <div class="card">
                                       <div class="card-body">
                                           <div class="theme-avtar bg-primary">
                                               <i class="ti-magnet"></i>
                                           </div>
                                           <p class="text-muted text-sm mt-4 mb-2">{{__('Payment Mode')}}</p>
                                           <h6 class="mb-3">{{__('Quarterly')}}</h6>
                                           <h3 class="mb-0">{{ number_format($bplo_business->where('app_code','<',3)->where('pm_id',3)->whereNotIn('busn_app_status', [0, 7, 8])->count() )}} </h3>
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
                                                @foreach ($topBarangays as $barangay)
                                                <tr>
                                                    <th>{{$barangay->busnBarangay->brgy_name}}</th>
                                                    <th>{{number_format($barangay->count)}}</th>
                                                </tr>
                                                @endforeach
                                                @if(empty($topBarangays))
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
                        @php
                        $active_bplo_business=$bplo_business->where('app_code','<',3)->count();
                        $walkin_bplo_business=$bplo_business->where('busn_app_method','Walk-In')->count();
                        $online_bplo_business=$bplo_business->where('busn_app_method','Online')->count();
                        $total_retired_bplo=$bplo_business->where('app_code',3)->count();
                        @endphp
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="mt-1 mb-0">{{__('Establishments')}}</h5>
                                    <div class="row mt-4">

                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-primary">
                                                    <i class="ti-desktop"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Active')}}</p>
                                                    <h4 class="mb-0 text-success">{{number_format($active_bplo_business)}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-info">
                                                    <i class="ti-world"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Online')}}</p>
                                                    <h4 class="mb-0 text-danger">{{number_format($online_bplo_business)}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-warning">
                                                    <i class="ti-trash"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Retired')}}</p>
                                                    <h4 class="mb-0 text-warning">{{number_format($total_retired_bplo)}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-danger">
                                                    <i class="ti-server"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Walkin')}}</p>
                                                    <h4 class="mb-0 text-danger">{{number_format($walkin_bplo_business)}}</h4>

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
                                    <h5 class="mt-1 mb-0">{{__('Top 5 Establishments')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Establishment Name')}}</th>
                                                <th>{{__('Total')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                          
                                                @foreach ($topBusinessEstablishment as $estlishment)
                                                <tr>
                                                    <th>{{$estlishment->busn_name}}</th>
                                                    <th>{{ number_format($estlishment->total_paid_amount, 2, '.', ',') }}</th>
                                                </tr>
                                                @endforeach
                                                @if(empty($topBusinessEstablishment))
                                                <tr>
                                                    <td colspan="4">
                                                        <div class="text-center">
                                                             <h6>{{__('there is no business type')}}</h6>
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
                                    <h5 class="mt-1 mb-0">{{__('Business Types')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Description')}}</th>
                                                <th>{{__('Total')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                          
                                                @foreach ($topBusinessType as $busnType)
                                                <tr>
                                                    <th>{{$busnType->busnType->btype_desc}}</th>
                                                    <th>{{number_format($busnType->count)}}</th>
                                                </tr>
                                                @endforeach
                                                @if(empty($topBusinessType))
                                                <tr>
                                                    <td colspan="4">
                                                        <div class="text-center">
                                                             <h6>{{__('there is no business type')}}</h6>
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
                                    <h5 class="mt-1 mb-0">{{__('Business Activity')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Location')}}</th>
                                                <th>{{__('Total')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                          
                                                @foreach ($bplo_business_location as $row)
                                                <tr>
                                                    <th>{{$row->LOCATION}}</th>
                                                    <th>{{ number_format($row->TOTAL) }}</th>
                                                </tr>
                                                @endforeach
                                                @if(empty($bplo_business_location))
                                                <tr>
                                                    <td colspan="4">
                                                        <div class="text-center">
                                                             <h6>{{__('there is no data available')}}</h6>
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