@extends('layouts.admin')
 <style type="text/css">
    .right_side{
        text-align: right;
    }
</style> 
@section('page-title')
    {{__('Records of Real Property Tax Collection')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Records of Real Property Tax Collection')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">

            <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i></a>
            <button onclick="ExportToExcel('xlsx')" class="btn btn-sm btn-primary action-item" title="Download Spreadsheet"> <i class="ti-files"></i></button>
        
    </div>
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
@endsection
@section('content')
    <div class="row" id="this_is_filter">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-end" >
                            <div class="col-lg-2 col-md-2 col-sm-2 pdr-20">
                              <div class="form-group">
                                @php
                                    $fromDate = date('Y-m-d', strtotime(date('Y-m-d').' -1 months'));
                                @endphp
                                {{Form::label('fromdate',__('From Date'),array('class'=>'form-label')) }}
                                <div class="form-icon-user">
                                      {{ Form::date('fromdate',$fromDate, array('class' => 'form-control','placeholder'=>'From','id'=>'fromdate')) }}
                                </div>
                              </div>
                           </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 pdr-20">
                              <div class="form-group">
                                {{Form::label('todate',__('To Date'),array('class'=>'form-label')) }}
                                <div class="form-icon-user">
                                      {{ Form::date('todate',date("Y-m-d"), array('class' => 'form-control','placeholder'=>'To date','id'=>'todate')) }}
                                </div>
                              </div>
                           </div>

                            <div class="col-auto float-end ms-2" style="padding-top: 7px;">
                                <a href="#" class="btn btn-sm btn-primary" id="btn_search">
                                    <span class="btn-inner--icon"><i class="ti-search"></i></span>
                                </a>
                                <a href="#" class="btn btn-sm btn-danger" id="btn_clear">
                                    <span class="btn-inner--icon"><i class="ti-trash "></i></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                         <table class="table" id="Jq_datatablelist">
                            <thead>
                            <tr>
                                <th rowspan="3" style="border:1px solid #fff;text-align: center;">{{__('No.')}}</th>
                                <th rowspan="3" style="border:1px solid #fff;text-align: center;">{{__('DATE')}}</th>
                                <th rowspan="3" style="border:1px solid #fff;text-align: center;">{{__('OR NO.')}}</th>
                                <th rowspan="3" style="border:1px solid #fff;text-align: center;">{{__('PAYEE')}}</th>
                                <th rowspan="3" style="border:1px solid #fff;text-align: center;">{{__('ASSESSED VALUE')}}</th>
                              
                                <th colspan="9" style="border:1px solid #fff;text-align: center;">{{__('Real Property Tax')}}</th>

                                <th rowspan="3" style="border:1px solid #fff;text-align: center;">{{__('Penalty')}}</th>
                                <th rowspan="3" style="border:1px solid #fff;text-align: center;">{{__('Tax Credit')}}</th>
                                <th rowspan="3" style="border:1px solid #fff;text-align: center;">{{__('Advance Payment')}}</th>
                                <th rowspan="3" style="border:1px solid #fff;text-align: center;">{{__('Amount Collected')}}</th>
                            </tr>
                            <tr>
                                
                                <th  colspan="3" style="text-align: center;border:1px solid #fff;">{{__('BASIC')}}</th>
                                <th  colspan="3" style="text-align: center;border:1px solid #fff;">{{__('Special Education Tax')}}</th>
                                <th  colspan="3" style="text-align: center;border:1px solid #fff;">{{__('Socialize Housing')}}</th>
                                
                            </tr>
                            <tr>
                                
                                <th  style="border:1px solid #fff;text-align: center;">{{__('Current Year')}}</th>
                                <th  style="border:1px solid #fff;text-align: center;">{{__('Previous Year')}}</th>
                                <th  style="border:1px solid #fff;text-align: center;">{{__('Prior Year')}}</th>

                                <th  style="border:1px solid #fff;text-align: center;">{{__('Current Year')}}</th>
                                <th  style="border:1px solid #fff;text-align: center;">{{__('Previous Year')}}</th>
                                <th  style="border:1px solid #fff;text-align: center;">{{__('Prior Year')}}</th>

                                <th  style="border:1px solid #fff;text-align: center;">{{__('Current Year')}}</th>
                                <th  style="border:1px solid #fff;text-align: center;">{{__('Previous Year')}}</th>
                                <th  style="border:1px solid #fff;text-align: center;">{{__('Prior Year')}}</th> 
                                
                            </tr> 
                            </thead>
                            <tfoot>
                                <tr>
                                    <th colspan="4" style="border:1px solid #fff;text-align: center;">Total</th>
                                    <th style="border:1px solid #fff;text-align: right;"></th>
                                    <th style="border:1px solid #fff;text-align: right;"></th>
                                    <th style="border:1px solid #fff;text-align: right;"></th>
                                    <th style="border:1px solid #fff;text-align: right;"></th>
                                    <th style="border:1px solid #fff;text-align: right;"></th>
                                    <th style="border:1px solid #fff;text-align: right;"></th>
                                    <th style="border:1px solid #fff;text-align: right;"></th>
                                    <th style="border:1px solid #fff;text-align: right;"></th>
                                    <th style="border:1px solid #fff;text-align: right;"></th>
                                    <th style="border:1px solid #fff;text-align: right;"></th>
                                    <th style="border:1px solid #fff;text-align: right;"></th>
                                    <th style="border:1px solid #fff;text-align: right;"></th>
                                    <th style="border:1px solid #fff;text-align: right;"></th>
                                    <th style="border:1px solid #fff;text-align: right;"></th>
                                </tr>
                            </tfoot>
                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="takeExportTableFromHere" style="display: none;"></div>
    <script src="{{ asset('js/cashierrealproperty/taxcollection.js') }}?rand={{ rand(000,999) }}"></script>

    <script>
    function ExportToExcel() {
        var fromDate = $('#fromdate').val();
        var toDate = $('#todate').val();
        $.ajax({
        url: DIR+'rpt-tax-collection/download-excel'+'?fromdate='+fromDate+'&todate='+toDate,
        success: function (data) {
            console.log(data);
            hideLoader();
            $('#takeExportTableFromHere').html(data);
            var elt = document.getElementById('dataToExport');
            console.log(elt);
            var allDataWorkbook = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
             console.log(allDataWorkbook);
            // Export all data to a single Excel file
            XLSX.writeFile(allDataWorkbook, 'All_Data.xlsx');
        },
        error: function (data) {
            hideLoader();
        }
    });
            
        }   
    
</script>
@endsection

