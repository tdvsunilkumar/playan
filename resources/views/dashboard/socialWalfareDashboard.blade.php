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
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Application(Assistance)')}}</p>
                                            <h6 class="mb-3">{{__('New')}}</h6>
                                            <h3 class="mb-0">{{$assistance_new}}
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
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Total(Assistance)')}}</p>
                                            <h6 class="mb-3">{{__('Draft')}}</h6>
                                            <h3 class="mb-0">{{$assistance_draft}}
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
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Total(Assistance)')}}</p>
                                            <h6 class="mb-3">{{__('Submitted')}}</h6>
                                            <h3 class="mb-0">{{$assistance_submitted}}</h3>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-success">
                                                <i class="ti-save"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Total(Assistance)')}}</p>
                                            <h6 class="mb-3">{{__('Approved')}}</h6>
                                            <h3 class="mb-0">{{$assistance_approved}}</h3>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Monthly Applications[For Assistance]')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Month')}}</th>
                                                <th>{{__('Count')}}</th>
                                                <th>{{__('Total')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @if($assistance_monthly)
                                                @foreach($assistance_monthly as $rows)
                                                <tr>
                                                    <td>
                                                        <div>
                                                            <h6>{{$rows->month}}</h6>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <p>{{$rows->count}}</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <p>{{$rows->count}}</p>
                                                        </div>
                                                    </td>
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
                                                <i class="ti-bookmark-alt"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Application(PWD)')}}</p>
                                            <h6 class="mb-3">{{__('New')}}</h6>
                                            <h3 class="mb-0">{{$pwd_new}}

                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-danger">
                                                <i class="ti-files"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Application(PWD)')}}</p>
                                            <h6 class="mb-3">{{__('Renewal')}}</h6>
                                            <h3 class="mb-0">{{$pwd_renewal}}
                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-primary">
                                                <i class="ti-zip"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('PWD Employed by')}}</p>
                                            <h6 class="mb-3">{{__('Government')}}</h6>
                                            <h3 class="mb-0">{{$pwd_gov}}</h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                   
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-info">
                                                <i class="ti-pin-alt"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('PWD Employed by')}}</p>
                                            <h6 class="mb-3">{{__('Private')}}</h6>
                                            <h3 class="mb-0">{{$pwd_priv}} </h3>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Type of Disability')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Description')}}</th>
                                                <th>{{__('Male')}}</th>
                                                <th>{{__('Female')}}</th>
                                                <th>{{__('Total')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @if($pwd_type_tbl)
                                                @foreach($pwd_type_tbl as $rows)
                                                <tr>
                                                    <td>
                                                        <div>
                                                            <h6>{{$rows->disability}}</h6>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <p>{{$rows->male}}</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <p>{{$rows->female}}</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <p>{{$rows->total}}</p>
                                                        </div>
                                                    </td>
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
                                    <h5 class="mt-1 mb-0">{{__('Monthly Applications[For PWD]')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Month')}}</th>
                                                <th>{{__('Count')}}</th>
                                                <th>{{__('Total')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @if($pwd_monthly)
                                                @foreach($pwd_monthly as $rows)
                                                <tr>
                                                    <td>
                                                        <div>
                                                            <h6>{{$rows->month}}</h6>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <p>{{$rows->count}}</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <p>{{$rows->count}}</p>
                                                        </div>
                                                    </td>
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
                                            <div class="theme-avtar bg-secondary">
                                                <i class="ti-bookmark-alt"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Application(Senior)')}}</p>
                                            <h6 class="mb-3">{{__('New')}}</h6>
                                            <h3 class="mb-0">
                                               {{$senior_new}}
                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-success">
                                                <i class="ti-filter"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Application(Senior)')}}</p>
                                            <h6 class="mb-3">{{__('Renewal')}}</h6>
                                            <h3 class="mb-0"> {{$senior_renew}}
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
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Senior Citizen')}}</p>
                                            <h6 class="mb-3">{{__('With Pension')}}</h6>
                                            <h3 class="mb-0">{{$senior_pension}} </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-primary">
                                                <i class="ti ti-users"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Senior Citizen')}}</p>
                                            <h6 class="mb-3">{{__('No Pension')}}</h6>
                                            <h3 class="mb-0">{{$senior_nopension}} </h3>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Monthly Applications[For Senior Citizens]')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Month')}}</th>
                                                <th>{{__('Count')}}</th>
                                                <th>{{__('Total')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @if($senior_monthly)
                                                @foreach($senior_monthly as $rows)
                                                <tr>
                                                    <td>
                                                        <div>
                                                            <h6>{{$rows->month}}</h6>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <p>{{$rows->count}}</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <p>{{$rows->count}}</p>
                                                        </div>
                                                    </td>
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
                         '                   </tbody>
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
                                            <div class="theme-avtar bg-success">
                                                <i class="ti-ruler-alt"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Application(Solo Parent)')}}</p>
                                            <h6 class="mb-3">{{__('New')}}</h6>
                                            <h3 class="mb-0">
                                               {{$solo_parent_new}}
                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-secondary">
                                                <i class="ti-files"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Application(Solo Parent)')}}</p>
                                            <h6 class="mb-3">{{__('Renewal')}}</h6>
                                            <h3 class="mb-0"> {{$solo_parent_renew}}
                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">      
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-warning">
                                                <i class="ti-medall "></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Solo Parent')}}</p>
                                            <h6 class="mb-3">{{__('With Income')}}</h6>
                                            <h3 class="mb-0">{{$solo_parent_income}} </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-danger">
                                                <i class="ti-pin-alt"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Solo Parent')}}</p>
                                            <h6 class="mb-3">{{__('No Income')}}</h6>
                                            <h3 class="mb-0">{{$solo_parent_noincome}} </h3>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Monthly Applications[For Solo Parent]')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Month')}}</th>
                                                <th>{{__('Count')}}</th>
                                                <th>{{__('Total')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @if($solo_parent_monthly)
                                                @foreach($solo_parent_monthly as $rows)
                                                <tr>
                                                    <td>
                                                        <div>
                                                            <h6>{{$rows->month}}</h6>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <p>{{$rows->count}}</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <p>{{$rows->count}}</p>
                                                        </div>
                                                    </td>
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
                <div class="col-xxl-5">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Top 3 Assistance[Based on Amount]')}}</h5>
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
                                                @if($assistance_type_amount_tbl)
                                                @foreach($assistance_type_amount_tbl as $rows)
                                                <tr>
                                                    <td>
                                                        <div>
                                                            <h6>{{$rows->type}}</h6>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <p>{{currency_format($rows->total)}}</p>
                                                        </div>
                                                    </td>
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
                                    <h5 class="mt-1 mb-0">{{__('Asistance Status')}}</h5>
                                    <div class="row mt-4">

                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-primary">
                                                    <i class="ti-save"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Active')}}</p>
                                                    <h4 class="mb-0 text-success">{{$assistance_active}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-info">
                                                    <i class="ti-world"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Release')}}</p>
                                                    <h4 class="mb-0 text-info">{{$assistance_released}}</h4>

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
                                                    <h4 class="mb-0 text-warning">{{$assistance_cancelled}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-secondary">
                                                    <i class="ti-desktop "></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Pending')}}</p>
                                                    <h4 class="mb-0 text-secondary">{{$assistance_pending}}</h4>

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
                                    <h5 class="mt-1 mb-0">{{__('Top 3 Assistance[Based on Count]')}}</h5>
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
                                                @if($assistance_type_count_tbl)
                                                @foreach($assistance_type_count_tbl as $rows)
                                                <tr>
                                                    <td>
                                                        <div>
                                                            <h6>{{$rows->type}}</h6>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <p>{{$rows->total}}</p>
                                                        </div>
                                                    </td>
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
                                    <h5 class="mt-1 mb-0">{{__('Top 5 Barangays[Assistance Based on Amount]')}}</h5>
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
                                                @if($assistance_brgy_amount_tbl)
                                                @foreach($assistance_brgy_amount_tbl as $rows)
                                                <tr>
                                                    <td>
                                                        <div>
                                                            <h6>{{$rows->brgy}}</h6>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <p>{{currency_format($rows->total)}}</p>
                                                        </div>
                                                    </td>
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
                                    <h5 class="mt-1 mb-0">{{__('Top 5 Barangays[Assistance Based on Count]')}}</h5>
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
                                                @if($assistance_brgy_count_tbl)
                                                @foreach($assistance_brgy_count_tbl as $rows)
                                                <tr>
                                                    <td>
                                                        <div>
                                                            <h6>{{$rows->brgy}}</h6>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <p>{{$rows->total}}</p>
                                                        </div>
                                                    </td>
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
                                    <h5 class="mt-1 mb-0">{{__('PWD Status')}}</h5>
                                    <div class="row mt-4">

                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-primary">
                                                    <i class="ti-save"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Active')}}</p>
                                                    <h4 class="mb-0 text-success">{{$pwd_active}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-info">
                                                    <i class="ti-flag-alt-2"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Inborn')}}</p>
                                                    <h4 class="mb-0 text-info">{{$pwd_inborn}}</h4>

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
                                                    <h4 class="mb-0 text-warning">{{$pwd_cancelled}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-secondary">
                                                    <i class="ti-image "></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Acquired')}}</p>
                                                    <h4 class="mb-0 text-secondary">{{$pwd_aquired}}</h4>

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
                                    <h5 class="mt-1 mb-0">{{__('Top 5 Barangays[PWD Population]')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Barangay')}}</th>
                                                <th>{{__('Male')}}</th>
                                                <th>{{__('Female')}}</th>
                                                <th>{{__('Total Count')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @if($pwd_brgy_tbl)
                                                @foreach($pwd_brgy_tbl as $rows)
                                                <tr>
                                                    <td>
                                                        <div>
                                                            <h6>{{$rows->brgy}}</h6>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <p>{{$rows->male}}</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <p>{{$rows->female}}</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <p>{{$rows->total}}</p>
                                                        </div>
                                                    </td>
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
                                    <h5 class="mt-1 mb-0">{{__('PWD Age Bracket')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Description')}}</th>
                                                <th>{{__('Male')}}</th>
                                                <th>{{__('Female')}}</th>
                                                <th>{{__('Total Count')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @if($pwd_age_tbl)
                                                @foreach($pwd_age_tbl as $rows)
                                                <tr>
                                                    <td>
                                                        <div>
                                                            <h6>{{$rows->age_range}}</h6>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <p>{{$rows->male}}</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <p>{{$rows->female}}</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <p>{{$rows->count}}</p>
                                                        </div>
                                                    </td>
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
                                    <h5 class="mt-1 mb-0">{{__('Senior Citizens Status')}}</h5>
                                    <div class="row mt-4">

                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-primary">
                                                    <i class="ti-save"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Active')}}</p>
                                                    <h4 class="mb-0 text-success">{{$senior_active}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-info">
                                                    <i class="ti-world"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Male')}}</p>
                                                    <h4 class="mb-0 text-info">{{$senior_male}}</h4>

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
                                                    <h4 class="mb-0 text-warning">{{$senior_cancelled}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-secondary">
                                                    <i class="ti-settings  "></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Female')}}</p>
                                                    <h4 class="mb-0 text-secondary">{{$senior_female}}</h4>

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
                                    <h5 class="mt-1 mb-0">{{__('Top 5 Barangays[Senior Citizen Population]')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>{{__('Barangay')}}</th>
                                                    <th>{{__('Male')}}</th>
                                                    <th>{{__('Female')}}</th>
                                                    <th>{{__('Total Count')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($senior_brgy_count_tbl)
                                                @foreach($senior_brgy_count_tbl as $rows)
                                                <tr>
                                                    <td>
                                                        <div>
                                                            <h6>{{$rows->brgy}}</h6>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <p>{{$rows->male}}</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <p>{{$rows->female}}</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <p>{{$rows->total}}</p>
                                                        </div>
                                                    </td>
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
                                    <h5 class="mt-1 mb-0">{{__('Solo Parent Status')}}</h5>
                                    <div class="row mt-4">

                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-primary">
                                                    <i class="ti-save"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Active')}}</p>
                                                    <h4 class="mb-0 text-success">{{$solo_parent_active}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-info">
                                                    <i class="ti-world"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Male')}}</p>
                                                    <h4 class="mb-0 text-info">{{$solo_parent_male}}</h4>

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
                                                    <h4 class="mb-0 text-warning">{{$solo_parent_cancelled}}</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-secondary">
                                                    <i class="ti-mobile"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Female')}}</p>
                                                    <h4 class="mb-0 text-secondary">{{$solo_parent_female}}</h4>

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
                                    <h5 class="mt-1 mb-0">{{__('Top 5 Barangays[Solo Parent Population]')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>{{__('Barangay')}}</th>
                                                    <th>{{__('Male')}}</th>
                                                    <th>{{__('Female')}}</th>
                                                    <th>{{__('Total Count')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($solo_parent_brgy_count_tbl)
                                                @foreach($solo_parent_brgy_count_tbl as $rows)
                                                <tr>
                                                    <td>
                                                        <div>
                                                            <h6>{{$rows->brgy}}</h6>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <p>{{$rows->male}}</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <p>{{$rows->female}}</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <p>{{$rows->total}}</p>
                                                        </div>
                                                    </td>
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