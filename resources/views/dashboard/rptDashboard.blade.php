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
                                                <i class="ti-agenda"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Active Declarations')}}</p>
                                            <h6 class="mb-3">{{__('Land')}}</h6>
                                            <h3 class="mb-0">{{ (isset($activeLandTds->count))?$activeLandTds->count:0}}

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
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Active Declarations')}}</p>
                                            <h6 class="mb-3">{{__('Building')}}</h6>
                                            <h3 class="mb-0">{{ (isset($activeBuildTds->count))?$activeBuildTds->count:0}}
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
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Active Declarations')}}</p>
                                            <h6 class="mb-3">{{__('Machineries')}}</h6>
                                            <h3 class="mb-0">{{ (isset($activeMachineTds->count))?$activeMachineTds->count:0}}</h3>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-danger">
                                                <i class="ti-receipt"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Discovery')}}</p>
                                            <h6 class="mb-3">{{__('New Declaration')}}</h6>
                                            <h3 class="mb-0">{{ (isset($discoveryTds->count))?$discoveryTds->count:0}}</h3>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Newly Generated Declarations')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Taxpayer Name')}}</th>
                                                <th>{{__('ARP NO.')}}</th>
                                                <th>{{__('PIN')}}</th>
                                                <th>{{__('Assessed Value')}}</th>
                                                <th>{{__('Date')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @if(!$newTds->isEmpty())
                                                @foreach($newTds as $td)
                                               <tr>
                                                <td>{{ $td->full_name }}</td>
                                                <td>{{ $td->rp_tax_declaration_aform }}</td>
                                                <td>{{ $td->rp_pin_declaration_no }}</td>
                                                <td>{{ Helper::decimal_format($td->rp_assessed_value) }}</td>
                                                <td>{{ date("d/m/Y",strtotime($td->created_at)) }}</td>
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
                                            <div class="theme-avtar bg-danger">
                                                <i class="ti-book"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Direct')}}</p>
                                            <h6 class="mb-3">{{__('Cancellation')}}</h6>
                                            <h3 class="mb-0">{{ (isset($directCancelTds->count))?$directCancelTds->count:0}}

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
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('General')}}</p>
                                            <h6 class="mb-3">{{__('Revision')}}</h6>
                                            <h3 class="mb-0">{{ (isset($grCount->count))?$grCount->count:0}}
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
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Total')}}</p>
                                            <h6 class="mb-3">{{__('Agricultural')}}</h6>
                                            <h3 class="mb-0">{{ (isset($agCount->count))?$agCount->count:0}}</h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                   
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-primary">
                                                <i class="ti-wallet"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Total')}}</p>
                                            <h6 class="mb-3">{{__('Residential')}}</h6>
                                            <h3 class="mb-0">{{ (isset($resiCount->count))?$resiCount->count:0}} </h3>
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
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Total')}}</p>
                                            <h6 class="mb-3">{{__('Commercial')}}</h6>
                                            <h3 class="mb-0">
                                               {{ (isset($comCount->count))?$comCount->count:0}}
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
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Total')}}</p>
                                            <h6 class="mb-3">{{__('Industrial')}}</h6>
                                            <h3 class="mb-0"> {{ (isset($indCount->count))?$indCount->count:0}}
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
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Land With')}}</p>
                                            <h6 class="mb-3">{{__('Zoning Clearance')}}</h6>
                                            <h3 class="mb-0">{{ (isset($zClearanceLand->count))?$zClearanceLand->count:0}} </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-danger">
                                                <i class="ti-ink-pen"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Land With Building')}}</p>
                                            <h6 class="mb-3">{{__('Permit Applicantion')}}</h6>
                                            <h3 class="mb-0">{{ (isset($landWithBuilding->count))?$landWithBuilding->count:0}} </h3>
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
                                              @if(!$newTaxpayer->isEmpty())
                                              @foreach($newTaxpayer as $taxPayer)
                                              <tr>
                                              <td>{{$taxPayer->full_name}}</td>
                                              <td>{{Helper::decimal_format($taxPayer->rp_assessed_value)}}</td>
                                          </tr>
                                              @endforeach
                                               @else
                                                <tr>
                                                    <td colspan="4">
                                                        <div class="text-center">
                                                            <h6>{{__('there is no Taxpayer')}}</h6>
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
                                    <h5 class="mt-1 mb-0">{{__('Property Status')}}</h5>
                                    <div class="row mt-4">

                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-primary">
                                                    <i class="ti-desktop"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Active')}}</p>
                                                    <h4 class="mb-0 text-success">{{ (isset($active->count))?$active->count:0}}</h4>

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
                                                    <h4 class="mb-0 text-info">{{ (isset($onlineTds->count))?$onlineTds->count:0}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                       
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-warning">
                                                    <i class="ti-trash"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Cancelled')}}</p>
                                                    <h4 class="mb-0 text-warning">{{ (isset($cancelled->count))?$cancelled->count:0}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-danger">
                                                    <i class="ti-server"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Offline')}}</p>
                                                    <h4 class="mb-0 text-danger">{{ (isset($offlineTds->count))?$offlineTds->count:0}}</h4>

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
                                    <h5 class="mt-1 mb-0">{{__('Top 5 Barangays')}}</h5>
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
                                          
                                               @if(!$topBrgy->isEmpty())
                                               @foreach($topBrgy as $brgy)
                                               <tr>
                                                <td>{{$brgy->brgy_name}}</td>
                                                <td>{{Helper::decimal_format($brgy->rp_assessed_value)}}</td>
                                               </tr>
                                               @endforeach
                                               @else
                                                <tr>
                                                    <td colspan="4">
                                                        <div class="text-center">
                                                             <h6>{{__('there is no Baragay')}}</h6>
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
                                    <h5 class="mt-1 mb-0">{{__('Certification')}}</h5>
                                    <div class="row mt-4">

                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-primary">
                                                    <i class="ti-stats-down"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('No Land Holding')}}</p>
                                                    <h4 class="mb-0 text-success">{{ (isset($noLandHoldings->count))?$noLandHoldings->count:0}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-info">
                                                    <i class="ti-folder"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('No Improvement')}}</p>
                                                    <h4 class="mb-0 text-info">{{ (isset($noImprovment->count))?$noImprovment->count:0}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-warning">
                                                    <i class="ti-hummer"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Property Holding')}}</p>
                                                    <h4 class="mb-0 text-warning">{{ (isset($propHoldings->count))?$propHoldings->count:0}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-danger">
                                                    <i class="ti-stamp"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Tax Clearance')}}</p>
                                                    <h4 class="mb-0 text-danger">{{ (isset($taxClearance->count))?$taxClearance->count:0}}</h4>

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
                                    <h5 class="mt-1 mb-0">{{__('Top 5 Transaction')}}</h5>
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
                                            @if(!$topTxns->isEmpty())
                                            @foreach($topTxns as $txn)
                                            <tr>
                                                <td>{{ $txn->code}}</td>
                                                <td>{{ $txn->count}}</td>
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
                                    <h5 class="mt-1 mb-0">{{__('Building With Business')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Declaration No.')}}</th>
                                                <th>{{__('Total')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                          
                                               
                                                @if(!$buildWithBusiness->isEmpty())
                                            @foreach($buildWithBusiness as $build)
                                            <tr>
                                                <td>{{ $build->rp_tax_declaration_aform}}</td>
                                                <td>{{ $build->count}}</td>
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