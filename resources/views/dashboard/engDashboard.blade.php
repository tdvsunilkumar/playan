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
                                                <i class="ti-pencil-alt"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Application')}}</p>
                                            <h6 class="mb-3">{{__('New')}}</h6>
                                            <h3 class="mb-0">{{$new}}

                                            </h3>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-lg-3 col-6">
                                   
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-warning">
                                                <i class="ti-clipboard"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Total')}}</p>
                                            <h6 class="mb-3">{{__('Pending')}}</h6>
                                            <h3 class="mb-0">{{$pending}}
                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                  
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-secondary">
                                                <i class="ti-wallet"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Pending')}}</p>
                                            <h6 class="mb-3">{{__('Payment')}}</h6>
                                            <h3 class="mb-0">{{$payment}}</h3>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-success">
                                                <i class="ti-save"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Total Issued')}}</p>
                                            <h6 class="mb-3">{{__('Permit')}}</h6>
                                            <h3 class="mb-0">{{$permit}}</h3>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Service Status')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('SERVICES')}}</th>
                                                <th>{{__('DRAFT')}}</th>
                                                <th>{{__('SUBMITTED')}}</th>
                                                <th>{{__('APPROVED')}}</th>
                                                <th>{{__('PAID')}}</th>
                                                <th>{{__('RELEASE')}}</th>
                                                <th>{{__('TOTAL')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($service_status)) 
                                                @foreach($service_status as $item)
                                                <tr>
                                                    <td>{{$item->SERVICES}}</td>
                                                    <td>{{$item->Draft}}</td>
                                                    <td>{{$item->Submitted}}</td>
                                                    <td>{{$item->Approved}}</td>
                                                    <td>{{$item->Paid}}</td>
                                                    <td>{{$item->Released}}</td>
                                                    <td>{{$item->Total}}</td>
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
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg-3 col-6">
                                   
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-primary">
                                                <i class="ti-book"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Consultant')}}</p>
                                            <h6 class="mb-3">{{__('Employee')}}</h6>
                                            <h3 class="mb-0">0

                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-info">
                                                <i class="ti-files"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Consultant')}}</p>
                                            <h6 class="mb-3">{{__('External')}}</h6>
                                            <h3 class="mb-0">0
                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-secondary">
                                                <i class="ti-zip"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Scope of Work')}}</p>
                                            <h6 class="mb-3">{{__('New Construction')}}</h6>
                                            <h3 class="mb-0">0</h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                   
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-success">
                                                <i class="ti-wallet"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Scope of Work')}}</p>
                                            <h6 class="mb-3">{{__('Addition')}}</h6>
                                            <h3 class="mb-0">0 </h3>
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
                                                <i class="ti-settings"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Scope of Work')}}</p>
                                            <h6 class="mb-3">{{__('Repair')}}</h6>
                                            <h3 class="mb-0">
                                               0
                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-danger">
                                                <i class="ti-ruler-pencil"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Scope of Work')}}</p>
                                            <h6 class="mb-3">{{__('Renovation')}}</h6>
                                            <h3 class="mb-0"> 0
                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">      
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-primary">
                                                <i class="ti-hummer"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Scope of Work')}}</p>
                                            <h6 class="mb-3">{{__('Demolition')}}</h6>
                                            <h3 class="mb-0">0 </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-info">
                                                <i class="ti-trash "></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Scope of Work')}}</p>
                                            <h6 class="mb-3">{{__('Removal')}}</h6>
                                            <h3 class="mb-0">0 </h3>
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
                                                <i class="ti-bookmark-alt"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Scope of Work')}}</p>
                                            <h6 class="mb-3">{{__('New Installation')}}</h6>
                                            <h3 class="mb-0">
                                               0
                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-success">
                                                <i class="ti-layers-alt"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Scope of Work')}}</p>
                                            <h6 class="mb-3">{{__('Annual Inspection')}}</h6>
                                            <h3 class="mb-0"> 0
                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">      
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-warning">
                                                <i class="ti-folder"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Scope of Work')}}</p>
                                            <h6 class="mb-3">{{__('Erection')}}</h6>
                                            <h3 class="mb-0">0 </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-danger">
                                                <i class="ti ti-users"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Scope of Work')}}</p>
                                            <h6 class="mb-3">{{__('Alteration')}}</h6>
                                            <h3 class="mb-0">0 </h3>
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
                                                <i class="ti-ruler"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Scope of Work')}}</p>
                                            <h6 class="mb-3">{{__('Conversion')}}</h6>
                                            <h3 class="mb-0">
                                               0
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
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Scope of Work')}}</p>
                                            <h6 class="mb-3">{{__('Moving')}}</h6>
                                            <h3 class="mb-0"> 0
                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">      
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-secondary">
                                                <i class="ti-camera"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Scope of Work')}}</p>
                                            <h6 class="mb-3">{{__('Raising')}}</h6>
                                            <h3 class="mb-0">0 </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-success">
                                                <i class="ti-ruler-pencil"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Scope of Work')}}</p>
                                            <h6 class="mb-3">{{__('(Accessory|Building Structure')}}</h6>
                                            <h3 class="mb-0">0 </h3>
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
                                                <i class="ti-ruler-alt"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Scope of Work')}}</p>
                                            <h6 class="mb-3">{{__('Others')}}</h6>
                                            <h3 class="mb-0">
                                               0
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
                                    <h5 class="mt-1 mb-0">{{__('Top 5 Barangays(Services)')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Barangay')}}</th>
                                                <th>{{__('Total Count')}}</th>
                                            </tr>
                                            </thead>
                                             <tbody>
                                                @if(!empty($top_fiveservices)) 
                                                @foreach($top_fiveservices as $item)
                                                <tr>
                                                    <td>{{$item->Barangays}}</td>
                                                    <td>{{$item->TotalCount}}</td>
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
                                    <h5 class="mt-1 mb-0">{{__('Statistics')}}</h5>
                                    <div class="row mt-4">

                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-primary">
                                                    <i class="ti-save"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Active')}}</p>
                                                    <h4 class="mb-0 text-success">{{$active}}</h4>

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
                                                    <h4 class="mb-0 text-info">{{$online}}</h4>

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
                                                    <h4 class="mb-0 text-warning">{{$cancelled}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-secondary">
                                                    <i class="ti-desktop "></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Walkin')}}</p>
                                                    <h4 class="mb-0 text-secondary">{{$walkin}}</h4>

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
                                    <h5 class="mt-1 mb-0">{{__('Top 3 Taxpayers')}}</h5>
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
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Top 3 Services')}}</h5>
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
                                                @if(!empty($top_services)) 
                                                @foreach($top_services as $item)
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
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Top 5 Barangays[Building Permit]')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Barangay')}}</th>
                                                <th>{{__('Total Count')}}</th>
                                            </tr>
                                            </thead>
                                             <tbody>
                                                @if(!empty($top_barangaysbuilding)) 
                                                @foreach($top_barangaysbuilding as $item)
                                                <tr>
                                                    <td>{{$item->barangay}}</td>
                                                    <td>{{$item->totalcount}}</td>
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
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Top 5 Barangays[Demolition Permit]')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Barangay')}}</th>
                                                <th>{{__('Total Count')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($top_barangaysdemolition)) 
                                                @foreach($top_barangaysdemolition as $item)
                                                <tr>
                                                    <td>{{$item->barangay}}</td>
                                                    <td>{{$item->totalcount}}</td>
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
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Top 5 Barangays[Electrical Permit]')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Barangay')}}</th>
                                                <th>{{__('Total Count')}}</th>
                                            </tr>
                                            </thead>
                                             <tbody>
                                                @if(!empty($top_barangayelectric)) 
                                                @foreach($top_barangayelectric as $item)
                                                <tr>
                                                    <td>{{$item->barangay}}</td>
                                                    <td>{{$item->totalcount}}</td>
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
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Top 5 Barangays[Fencing Permit]')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Barangay')}}</th>
                                                <th>{{__('Total Count')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($top_barangayfencing)) 
                                                @foreach($top_barangayfencing as $item)
                                                <tr>
                                                    <td>{{$item->barangay}}</td>
                                                    <td>{{$item->totalcount}}</td>
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
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Top 5 Barangays[Sign Permit]')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Barangay')}}</th>
                                                <th>{{__('Total Count')}}</th>
                                            </tr>
                                            </thead>
                                           <tbody>
                                                @if(!empty($top_barangaysign)) 
                                                @foreach($top_barangaysign as $item)
                                                <tr>
                                                    <td>{{$item->barangay}}</td>
                                                    <td>{{$item->totalcount}}</td>
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
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Top 5 Barangays[Water Permit]')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Barangay')}}</th>
                                                <th>{{__('Total Count')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($top_barangaywater)) 
                                                @foreach($top_barangaywater as $item)
                                                <tr>
                                                    <td>{{$item->barangay}}</td>
                                                    <td>{{$item->totalcount}}</td>
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
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Top 5 Barangays[Sanitary|Plumbing Permit]')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Barangay')}}</th>
                                                <th>{{__('Total Count')}}</th>
                                            </tr>
                                            </thead>
                                             <tbody>
                                                @if(!empty($top_barangaysanitary)) 
                                                @foreach($top_barangaysanitary as $item)
                                                <tr>
                                                    <td>{{$item->barangay}}</td>
                                                    <td>{{$item->totalcount}}</td>
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
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Top 5 Barangays[Mechanical Permit]')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Barangay')}}</th>
                                                <th>{{__('Total Count')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($top_barangaymechanical)) 
                                                @foreach($top_barangaymechanical as $item)
                                                <tr>
                                                    <td>{{$item->barangay}}</td>
                                                    <td>{{$item->totalcount}}</td>
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
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Top 5 Barangays[Excavation Permit]')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Barangay')}}</th>
                                                <th>{{__('Total Count')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($top_barangayexcavation)) 
                                                @foreach($top_barangayexcavation as $item)
                                                <tr>
                                                    <td>{{$item->barangay}}</td>
                                                    <td>{{$item->totalcount}}</td>
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
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Top 5 Barangays[Electronics Permit]')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Barangay')}}</th>
                                                <th>{{__('Total Count')}}</th>
                                            </tr>
                                            </thead>
                                             <tbody>
                                                @if(!empty($top_barangayelectronic)) 
                                                @foreach($top_barangayelectronic as $item)
                                                <tr>
                                                    <td>{{$item->barangay}}</td>
                                                    <td>{{$item->totalcount}}</td>
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
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Top 5 Barangays[Civil|Structural Permit]')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Barangay')}}</th>
                                                <th>{{__('Total Count')}}</th>
                                            </tr>
                                            </thead>
                                           <tbody>
                                                @if(!empty($top_barangaycivil)) 
                                                @foreach($top_barangaycivil as $item)
                                                <tr>
                                                    <td>{{$item->barangay}}</td>
                                                    <td>{{$item->totalcount}}</td>
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
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Top 5 Barangays[Architectural Permit]')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Barangay')}}</th>
                                                <th>{{__('Total Count')}}</th>
                                            </tr>
                                            </thead>
                                           <tbody>
                                                @if(!empty($top_barangayarchitect)) 
                                                @foreach($top_barangayarchitect as $item)
                                                <tr>
                                                    <td>{{$item->barangay}}</td>
                                                    <td>{{$item->totalcount}}</td>
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