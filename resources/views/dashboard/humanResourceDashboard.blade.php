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
                                                <i class="ti ti-users"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Total')}}</p>
                                            <h6 class="mb-3">{{__('Employees')}}</h6>
                                            <h3 class="mb-0">{{\Auth::user()->countCustomers()}}

                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                   
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-info">
                                                <i class="ti-plus"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Total (Current Month)')}}</p>
                                            <h6 class="mb-3">{{__('New Hires')}}</h6>
                                            <h3 class="mb-0">{{\Auth::user()->countVenders()}}
                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                  
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-warning">
                                                <i class=" ti-key"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Total Employees')}}</p>
                                            <h6 class="mb-3">{{__('Male')}}</h6>
                                            <h3 class="mb-0">{{\Auth::user()->countInvoices()}} </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-danger">
                                                <i class=" ti-heart "></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Total Employees')}}</p>
                                            <h6 class="mb-3">{{__('Female')}}</h6>
                                            <h3 class="mb-0">{{\Auth::user()->countBills()}} </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-secondary">
                                                <i class=" ti-gallery"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Total (Current Day)')}}</p>
                                            <h6 class="mb-3">{{__('Vacation Leave')}}</h6>
                                            <h3 class="mb-0">{{\Auth::user()->countCustomers()}}

                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-success">
                                                <i class="ti-pulse "></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Total (Current Day) ')}}</p>
                                            <h6 class="mb-3">{{__('Sick Leave')}}</h6>
                                            <h3 class="mb-0">{{\Auth::user()->countBills()}} </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                   
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-primary">
                                                <i class="ti-alarm-clock "></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Total (Current Day) ')}}</p>
                                            <h6 class="mb-3">{{__(' Lates / Undertime')}}</h6>
                                            <h3 class="mb-0">{{\Auth::user()->countVenders()}}
                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                  
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-warning">
                                                <i class="ti-files "></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Total (Current Day)')}}</p>
                                            <h6 class="mb-3">{{__('Other Leaves')}}</h6>
                                            <h3 class="mb-0">{{\Auth::user()->countInvoices()}} </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-danger">
                                                <i class="ti-gallery "></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Average(Monthly)')}}</p>
                                            <h6 class="mb-3">{{__('Vacation Leave')}}</h6>
                                            <h3 class="mb-0">{{\Auth::user()->countCustomers()}}

                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                   
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-warning">
                                                <i class="ti-pulse "></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Average(Monthly) ')}}</p>
                                            <h6 class="mb-3">{{__('Sick Leave')}}</h6>
                                            <h3 class="mb-0">{{\Auth::user()->countVenders()}}
                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                  
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-info">
                                                <i class="ti-alarm-clock"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Average(Monthly) ')}}</p>
                                            <h6 class="mb-3">{{__('Lates / Undertime')}}</h6>
                                            <h3 class="mb-0">{{\Auth::user()->countInvoices()}} </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-secondary">
                                                <i class="ti-files "></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Average(Monthly) ')}}</p>
                                            <h6 class="mb-3">{{__('Other Leaves')}}</h6>
                                            <h3 class="mb-0">{{\Auth::user()->countBills()}} </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-info">
                                                <i class="ti-eraser"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Total (Current Day)')}}</p>
                                            <h6 class="mb-3">{{__('Missed Log')}}</h6>
                                            <h3 class="mb-0">{{\Auth::user()->countCustomers()}}

                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                   
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-secondary">
                                                <i class="ti-time  "></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Total (Current Day)')}}</p>
                                            <h6 class="mb-3">{{__('Overtime')}}</h6>
                                            <h3 class="mb-0">{{\Auth::user()->countVenders()}}
                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                  
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-danger">
                                                <i class="ti-exchange-vertical"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Total (Current Day)')}}</p>
                                            <h6 class="mb-3">{{__('Change of Schedule')}}</h6>
                                            <h3 class="mb-0">{{\Auth::user()->countInvoices()}} </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-primary">
                                                <i class="ti-id-badge "></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Total (Current Day)')}}</p>
                                            <h6 class="mb-3">{{__('Official Work')}}</h6>
                                            <h3 class="mb-0">{{\Auth::user()->countBills()}} </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-success">
                                                <i class="ti-share-alt"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Total (Current Day)')}}</p>
                                            <h6 class="mb-3">{{__('Offset')}}</h6>
                                            <h3 class="mb-0">{{\Auth::user()->countCustomers()}}

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
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Employment Status')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Status Type')}}</th>
                                                <th>{{__('Total Employee')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            
                                                <tr>
                                                    <td colspan="2">
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
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Employees by Tenure')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Years')}}</th>
                                                <th>{{__('Total Employee')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            
                                                <tr>
                                                    <td colspan="2">
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
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Number of Employee by Year')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Year')}}</th>
                                                <th>{{__('Total Employee')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            
                                                <tr>
                                                    <td colspan="2">
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
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Employee Age Bracket')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Age Bracket')}}</th>
                                                <th>{{__('Total Employee')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            
                                                <tr>
                                                    <td colspan="2">
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
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Total Employees by Designation')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Designation')}}</th>
                                                <th>{{__('Total Employee')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            
                                                <tr>
                                                    <td colspan="2">
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
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Total Employee per Department')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Department')}}</th>
                                                <th>{{__('Total Employee')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            
                                                <tr>
                                                    <td colspan="2">
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
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Birthday Celebrant(Current Month)')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Employee Name')}}</th>
                                                <th>{{__('Department')}}</th>
                                                <th>{{__('Birth date')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            
                                                <tr>
                                                    <td colspan="2">
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
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Employees Loan')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Loan Type')}}</th>
                                                <th>{{__('Count')}}</th>
                                                <th>{{__('Total')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            
                                                <tr>
                                                    <td colspan="2">
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
                        </div>
                        
                    </div>
                </div>
               





            </div>
        </div>