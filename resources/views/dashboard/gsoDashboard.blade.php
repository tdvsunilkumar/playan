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
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Request')}}</p>
                                            <h6 class="mb-3">{{__('Today')}}</h6>
                                            <h3 class="mb-0 text-primary">{{\Auth::user()->countCustomers()}}

                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                   
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-info">
                                                <i class="ti-shield"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('New')}}</p>
                                            <h6 class="mb-3">{{__('Request')}}</h6>
                                            <h3 class="mb-0 text-info">{{\Auth::user()->countVenders()}}
                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                  
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-secondary">
                                                <i class="ti-arrow-circle-right"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Dept.')}}</p>
                                            <h6 class="mb-3">{{__('Req.')}}</h6>
                                            <h3 class="mb-0 text-secondary">{{\Auth::user()->countInvoices()}} </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-success">
                                                <i class="ti-archive"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Dept.')}}</p>
                                            <h6 class="mb-3">{{__('Req.: Approval')}}</h6>
                                            <h3 class="mb-0 text-success">{{\Auth::user()->countBills()}} </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-warning">
                                                <i class="ti-agenda"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('For')}}</p>
                                            <h6 class="mb-3">{{__('Allocation')}}</h6>
                                            <h3 class="mb-0 text-warning">{{\Auth::user()->countCustomers()}}

                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-danger">
                                                <i class="ti-bookmark-alt"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Allocation ')}}</p>
                                            <h6 class="mb-3">{{__('Approval')}}</h6>
                                            <h3 class="mb-0 text-danger">{{\Auth::user()->countBills()}} </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                   
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-primary">
                                                <i class="ti-email"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('For ')}}</p>
                                            <h6 class="mb-3">{{__(' P.R. No')}}</h6>
                                            <h3 class="mb-0 text-primary">{{\Auth::user()->countVenders()}}
                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                  
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-info">
                                                <i class="ti-book "></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('P.R. No')}}</p>
                                            <h6 class="mb-3">{{__('Approval')}}</h6>
                                            <h3 class="mb-0 text-info">{{\Auth::user()->countInvoices()}} </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-secondary">
                                                <i class="ti-paint-roller"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('For')}}</p>
                                            <h6 class="mb-3">{{__('RFQ')}}</h6>
                                            <h3 class="mb-0 text-secondary">{{\Auth::user()->countCustomers()}}

                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                   
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-success">
                                                <i class="ti-lock"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('RFQ ')}}</p>
                                            <h6 class="mb-3">{{__('Approval')}}</h6>
                                            <h3 class="mb-0 text-success">{{\Auth::user()->countVenders()}}
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
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('For ')}}</p>
                                            <h6 class="mb-3">{{__('Abstract')}}</h6>
                                            <h3 class="mb-0 text-warning">{{\Auth::user()->countInvoices()}} </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-danger">
                                                <i class="ti-check"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Abstract ')}}</p>
                                            <h6 class="mb-3">{{__('Approval')}}</h6>
                                            <h3 class="mb-0 text-danger">{{\Auth::user()->countBills()}} </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-primary">
                                                <i class="ti-write "></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('For ')}}</p>
                                            <h6 class="mb-3">{{__('Resolution')}}</h6>
                                            <h3 class="mb-0 text-primary">{{\Auth::user()->countCustomers()}}

                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                   
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-info">
                                                <i class="ti-pencil"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Resolution ')}}</p>
                                            <h6 class="mb-3">{{__('Approval')}}</h6>
                                            <h3 class="mb-0 text-info">{{\Auth::user()->countVenders()}}
                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                  
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-secondary">
                                                <i class="ti-layout-media-center-alt"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('For')}}</p>
                                            <h6 class="mb-3">{{__('P.O')}}</h6>
                                            <h3 class="mb-0 text-secondary">{{\Auth::user()->countInvoices()}} </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-3 col-6">
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-success">
                                                <i class="ti-layers-alt"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Purchase Order ')}}</p>
                                            <h6 class="mb-3">{{__('Approval')}}</h6>
                                            <h3 class="mb-0 text-success">{{\Auth::user()->countBills()}} </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-warning">
                                                <i class="ti-import"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('')}}</p>
                                            <h6 class="mb-3">{{__('Completed')}}</h6>
                                            <h3 class="mb-0 text-warning">{{\Auth::user()->countCustomers()}}

                                            </h3>
                                        </div>
                                    </div>
                                   
                                </div>
                               
                            </div>
                        </div>

                        <div class="col-xxl-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Recent Invoices')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{__('Customer')}}</th>
                                                <th>{{__('Issue Date')}}</th>
                                                <th>{{__('Due Date')}}</th>
                                                <th>{{__('Amount')}}</th>
                                                <th>{{__('Status')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($recentInvoice as $invoice)
                                                <tr>
                                                    <td>{{\Auth::user()->invoiceNumberFormat($invoice->invoice_id)}}</td>
                                                    <td>{{!empty($invoice->customer)? $invoice->customer->name:'' }} </td>
                                                    <td>{{ Auth::user()->dateFormat($invoice->issue_date) }}</td>
                                                    <td>{{ Auth::user()->dateFormat($invoice->due_date) }}</td>
                                                    <td>{{\Auth::user()->priceFormat($invoice->getTotal())}}</td>
                                                    <td>
                                                        @if($invoice->status == 0)
                                                            <span class="p-2 px-3 rounded badge bg-secondary">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                        @elseif($invoice->status == 1)
                                                            <span class="p-2 px-3 rounded badge bg-warning">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                        @elseif($invoice->status == 2)
                                                            <span class="p-2 px-3 rounded badge bg-danger">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                        @elseif($invoice->status == 3)
                                                            <span class="p-2 px-3 rounded badge bg-info">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                        @elseif($invoice->status == 4)
                                                            <span class="p-2 px-3 rounded badge bg-success">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6">
                                                        <div class="text-center">
                                                            <h6>{{__('there is no recent invoice')}}</h6>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
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
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Expense Cashflow 2023')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div id="cash-flow"></div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Department Top 5 Most Requested')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Department')}}</th>
                                                <th>{{__('Division')}}</th>
                                                <th>{{__('Items')}}</th>
                                                <th>{{__('Amount')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($latestExpense as $expense)
                                                <tr>
                                                    <td>{{\Auth::user()->dateFormat($expense->date)}}</td>
                                                    <td>{{!empty($expense->customer)?$expense->customer->name:'-'}}</td>
                                                    <td>{{\Auth::user()->priceFormat($expense->amount)}}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4">
                                                        <div class="text-center">
                                                            <h6>{{__('there is no latest records')}}</h6>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Your Most Requested Items')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Code')}}</th>
                                                <th>{{__('Name')}}</th>
                                                <th>{{__('Amount')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($latestExpense as $expense)
                                                <tr>
                                                    <td>{{\Auth::user()->dateFormat($expense->date)}}</td>
                                                    <td>{{!empty($expense->customer)?$expense->customer->name:'-'}}</td>
                                                    <td>{{\Auth::user()->priceFormat($expense->amount)}}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4">
                                                        <div class="text-center">
                                                            <h6>{{__('there is no latest records')}}</h6>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Recent Repair Items')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('No')}}</th>
                                                <th>{{__('Department')}}</th>
                                                <th>{{__('Division')}}</th>
                                                <th>{{__('Items')}}</th>
                                                <th>{{__('Amount')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($latestExpense as $expense)
                                                <tr>
                                                    <td>{{\Auth::user()->dateFormat($expense->date)}}</td>
                                                    <td>{{!empty($expense->customer)?$expense->customer->name:'-'}}</td>
                                                    <td>{{\Auth::user()->priceFormat($expense->amount)}}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4">
                                                        <div class="text-center">
                                                            <h6>{{__('there is no latest records')}}</h6>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-12">
                            <div class="card">
                                <div class="card-body">

                                    <ul class="nav nav-pills mb-5" id="pills-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" href="#invoice_weekly_statistics" role="tab" aria-controls="pills-home" aria-selected="true">{{__('Invoices Weekly Statistics')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" href="#invoice_monthly_statistics" role="tab" aria-controls="pills-profile" aria-selected="false">{{__('Invoices Monthly Statistics')}}</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane fade show active" id="invoice_weekly_statistics" role="tabpanel" aria-labelledby="pills-home-tab">
                                            <div class="table-responsive">
                                                <table class="table align-items-center mb-0 ">
                                                    <tbody class="list">
                                                    <tr>
                                                        <td>
                                                            <h5 class="mb-0">{{__('Total')}}</h5>
                                                            <p class="text-muted text-sm mb-0">{{__('Invoice Generated')}}</p>

                                                        </td>
                                                        <td>
                                                            <h4 class="text-muted">{{\Auth::user()->priceFormat($weeklyInvoice['invoiceTotal'])}}</h4>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5 class="mb-0">{{__('Total')}}</h5>
                                                            <p class="text-muted text-sm mb-0">{{__('Paid')}}</p>
                                                        </td>
                                                        <td>
                                                            <h4 class="text-muted">{{\Auth::user()->priceFormat($weeklyInvoice['invoicePaid'])}}</h4>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5 class="mb-0">{{__('Total')}}</h5>
                                                            <p class="text-muted text-sm mb-0">{{__('Due')}}</p>
                                                        </td>
                                                        <td>
                                                            <h4 class="text-muted">{{\Auth::user()->priceFormat($weeklyInvoice['invoiceDue'])}}</h4>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="invoice_monthly_statistics" role="tabpanel" aria-labelledby="pills-profile-tab">
                                            <div class="table-responsive">
                                                <table class="table align-items-center mb-0 ">
                                                    <tbody class="list">
                                                    <tr>
                                                        <td>
                                                            <h5 class="mb-0">{{__('Total')}}</h5>
                                                            <p class="text-muted text-sm mb-0">{{__('Invoice Generated')}}</p>

                                                        </td>
                                                        <td>
                                                            <h4 class="text-muted">{{\Auth::user()->priceFormat($monthlyInvoice['invoiceTotal'])}}</h4>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5 class="mb-0">{{__('Total')}}</h5>
                                                            <p class="text-muted text-sm mb-0">{{__('Paid')}}</p>
                                                        </td>
                                                        <td>
                                                            <h4 class="text-muted">{{\Auth::user()->priceFormat($monthlyInvoice['invoicePaid'])}}</h4>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5 class="mb-0">{{__('Total')}}</h5>
                                                            <p class="text-muted text-sm mb-0">{{__('Due')}}</p>
                                                        </td>
                                                        <td>
                                                            <h4 class="text-muted">{{\Auth::user()->priceFormat($monthlyInvoice['invoiceDue'])}}</h4>
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
                        <div class="col-xxl-12">
                            <div class="card">
                                <div class="card-body">

                                    <ul class="nav nav-pills mb-5" id="pills-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" href="#bills_weekly_statistics" role="tab" aria-controls="pills-home" aria-selected="true">{{__('Bills Weekly Statistics')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" href="#bills_monthly_statistics" role="tab" aria-controls="pills-profile" aria-selected="false">{{__('Bills Monthly Statistics')}}</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane fade show active" id="bills_weekly_statistics" role="tabpanel" aria-labelledby="pills-home-tab">
                                            <div class="table-responsive">
                                                <table class="table align-items-center mb-0 ">
                                                    <tbody class="list">
                                                    <tr>
                                                        <td>
                                                            <h5 class="mb-0">{{__('Total')}}</h5>
                                                            <p class="text-muted text-sm mb-0">{{__('Bill Generated')}}</p>

                                                        </td>
                                                        <td>
                                                            <h4 class="text-muted">{{\Auth::user()->priceFormat($weeklyBill['billTotal'])}}</h4>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5 class="mb-0">{{__('Total')}}</h5>
                                                            <p class="text-muted text-sm mb-0">{{__('Paid')}}</p>
                                                        </td>
                                                        <td>
                                                            <h4 class="text-muted">{{\Auth::user()->priceFormat($weeklyBill['billPaid'])}}</h4>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5 class="mb-0">{{__('Total')}}</h5>
                                                            <p class="text-muted text-sm mb-0">{{__('Due')}}</p>
                                                        </td>
                                                        <td>
                                                            <h4 class="text-muted">{{\Auth::user()->priceFormat($weeklyBill['billDue'])}}</h4>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="bills_monthly_statistics" role="tabpanel" aria-labelledby="pills-profile-tab">
                                            <div class="table-responsive">
                                                <table class="table align-items-center mb-0 ">
                                                    <tbody class="list">
                                                    <tr>
                                                        <td>
                                                            <h5 class="mb-0">{{__('Total')}}</h5>
                                                            <p class="text-muted text-sm mb-0">{{__('Bill Generated')}}</p>

                                                        </td>
                                                        <td>
                                                            <h4 class="text-muted">{{\Auth::user()->priceFormat($monthlyBill['billTotal'])}}</h4>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5 class="mb-0">{{__('Total')}}</h5>
                                                            <p class="text-muted text-sm mb-0">{{__('Paid')}}</p>
                                                        </td>
                                                        <td>
                                                            <h4 class="text-muted">{{\Auth::user()->priceFormat($monthlyBill['billPaid'])}}</h4>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5 class="mb-0">{{__('Total')}}</h5>
                                                            <p class="text-muted text-sm mb-0">{{__('Due')}}</p>
                                                        </td>
                                                        <td>
                                                            <h4 class="text-muted">{{\Auth::user()->priceFormat($monthlyBill['billDue'])}}</h4>
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
                <div class="col-xxl-12">
                    <div class="card">
                        <div class="card-header">

                            <h5>{{__('Budget Monitoring For Your Department')}}</h5>
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