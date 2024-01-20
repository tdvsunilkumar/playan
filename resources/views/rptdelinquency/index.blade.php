@extends('layouts.admin')
@section('page-title')
    {{__('Real Property Tax: Delinquency')}}
@endsection
@push('script-page')
@endpush

<div>
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Treasure')}}</li>
    <li class="breadcrumb-item">{{__('Real Property')}}</li>
    <li class="breadcrumb-item">{{__('Delinquency')}}</li>
@endsection
</div>
@section('action-btn')
<style type="text/css">
    .dropdown-toggle::after {
        display: contents;
        margin-left: 0.255em;
        vertical-align: 0.255em;
        content: "";
        border-top: 0.3em solid;
        border-right: 0.3em solid transparent;
        border-bottom: 0;
        border-left: 0.3em solid transparent;
    }
</style>
    <div class="float-end">
        <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown2" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
        <a href="#" class="btn btn-sm btn-primary dropdown-toggle" type="button" id="columnDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
           <i class="ti-menu"></i>                                     
        </a>
        <div class="dropdown-menu" aria-labelledby="columnDropdown">
            <div class="card card-body">
                <label><input type="checkbox" id="chk_srno" class="toggle-column" data-column="0" checked> NO.</label>
                <label><input type="checkbox" id="chk_arpno" class="toggle-column" data-column="1" checked> TD NO.</label>
                <label><input type="checkbox" id="chk_taxpayer" class="toggle-column" data-column="2" checked> TAXPAYER NAME</label>
                <label><input type="checkbox" id="chk_Address" class="toggle-column" data-column="3" > ADDRESS</label>   
                <label><input type="checkbox" id="chk_Address" class="toggle-column" data-column="4" checked> LOCATION</label>
                <label><input type="checkbox" id="chk_arpno" class="toggle-column" data-column="5" checked> PIN</label>
                <label><input type="checkbox" id="chk_taxpayer" class="toggle-column" data-column="6" checked> LOT|CCT|UNIT NO.|DESCRIPTION</label>
                <label><input type="checkbox" id="chk_Address" class="toggle-column" data-column="7" checked> CLASS</label>   
                <label><input type="checkbox" id="chk_Address" class="toggle-column" data-column="8" checked> UPDATE CODE</label>
                <label><input type="checkbox" id="chk_arpno" class="toggle-column" data-column="9" checked> EFECTIVITY</label>
                <label><input type="checkbox" id="chk_taxpayer" class="toggle-column" data-column="10" checked> ASSESSED VALUE</label>
                <label><input type="checkbox" id="chk_Address" class="toggle-column" data-column="11" > Last OR NO.</label>   
                <label><input type="checkbox" id="chk_Address" class="toggle-column" data-column="12" > Last OR DATE</label>
                <label><input type="checkbox" id="chk_arpno" class="toggle-column" data-column="13" > Last OR AMOUNT</label>
                
                <label><input type="checkbox" id="chk_Address" class="toggle-column" data-column="14" checked> BASIC TAX</label>   
                <label><input type="checkbox" id="chk_Address" class="toggle-column" data-column="15" checked> SEF</label>
                <label><input type="checkbox" id="chk_Address" class="toggle-column" data-column="16" checked> SHT</label>
                <label><input type="checkbox" id="chk_Address" class="toggle-column" data-column="17" checked> TOTAL</label>
                <label><input type="checkbox" id="chk_Address" class="toggle-column" data-column="18" checked> TOTAL</label>
                <label><input type="checkbox" id="chk_Address" class="toggle-column" data-column="19" checked> NOTICE RESPONSE</label>
                <label><input type="checkbox" id="chk_Address" class="toggle-column" data-column="20" checked> ACTION</label>
                <!-- Add more checkboxes as needed -->
            </div>
        </div>
       <button onclick="ExportToExcel('xlsx')" class="btn btn-sm btn-primary action-item" title="Download Quarterly Assessment Sheet"> <i class="ti-files"></i></button>

    </div>
    </div>
@endsection
@section('content')
<link href="{{ asset('assets/js/yearpicker.css')}}" rel="stylesheet">
<style type="text/css">
    .yearpicker-container {
    position: fixed;
    color: var(--text-color);
    width: 280px;
    border: 1px solid var(--border-color);
    border-radius: 3px;
    font-size: 1rem;
    box-shadow: 1px 1px 8px 0px rgba(0, 0, 0, 0.2);
    background-color: var(--background-color);
    z-index: 10;
    margin-top: 0px;
}
</style>
    <div class="row" id="this_is_filter">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-end">
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12 pdr-20" id="barangaydiv">
                                <div class="form-group">
                                    {{Form::label('revisionyear',__("Barangay"),['class'=>'form-label'])}}
                                    <div class="btn-box">
                                        
                                     {{ Form::select('barangay',$barangay,'', array('class' => 'form-control','id'=>'barangay','required'=>'required')) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12 pdr-20" id="barangaydiv">
                                <div class="form-group">
                                    {{Form::label('taxpayer',__("Taxpayer Name"),['class'=>'form-label'])}}
                                    <div class="btn-box">
                                       
                                     {{ Form::select('taxpayer',$taxpayer,'', array('class' => 'form-control','id'=>'taxpayer','required'=>'required')) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12 pdr-20" id="barangaydiv">
                                <div class="form-group">
                                    {{Form::label('revisionyear',__("Tax Declaration Details"),['class'=>'form-label'])}}
                                    <div class="btn-box">
                                       
                                    {{ Form::select('tax',$taxDeclaration,'', array('class' => 'form-control','id'=>'tax','required'=>'required')) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12 pdr-20" >
                                <div class="form-group">
                                    {{Form::label('revisionyear',__("Kind"),['class'=>'form-label'])}}
                                    <div class="btn-box">
                                       
                                    {{ Form::select('kind_filter',$kinds,'', array('class' => 'form-control','id'=>'rptPropertySearchByKind','placeholder' => 'Select Kind')) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-1 col-lg-1 col-md-6 col-sm-12 col-12 pdr-20" id="barangaydiv">
                                <div class="form-group">
                                    {{Form::label('year',__("Year"),['class'=>'form-label'])}}
                                    <div class="btn-box">
                                        <?php
                                        $currentYear = date('Y');
                                    ?>
                                    {{ Form::text('year', $currentYear, array('class' => 'yearpicker form-control','id'=>'year','autocomplete' => 'off')) }}
                                    </div>
                                </div>
                            </div>
                           
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12 pdr-20" id="barangaydiv">
                              <div class="form-group"> 
                              {{Form::label('revisionyear',__("Search Details"),['class'=>'form-label'])}} 
                                <div class="btn-box">
                                    {{ Form::text('q', '', array('class' => 'form-control','placeholder'=>'Search...','id'=>'rptPropertySearchByText')) }}
                                </div>
                              </div>
                            </div>
                            <div class="col-auto float-end ms-2">
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
                                <th rowspan="2" style="border:1px solid #fff;text-align: center;">{{__('No.')}}</th>
                                <th rowspan="2" style="border:1px solid #fff;text-align: center;">{{__('TD NO.')}}</th>
                                <th rowspan="2" style="border:1px solid #fff;text-align: center;">{{__('TAXPAYER NAME')}}</th>
                                <th rowspan="2" style="border:1px solid #fff;text-align: center;">{{__('ADDRESS')}}</th>
                                <th rowspan="2" style="border:1px solid #fff;text-align: center;">{{__('LOCATION')}}</th>
                                <th rowspan="2" style="border:1px solid #fff;text-align: center;">{{__('PIN')}}</th>
                                <th rowspan="2" style="border:1px solid #fff;text-align: center;">{{__('LOT|CCT|UNIT NO.|DESCRIPTION')}}</th>
                                <th rowspan="2" style="border:1px solid #fff;text-align: center;">{{__('CLASS')}}</th>
                                <th rowspan="2" style="border:1px solid #fff;text-align: center;">{{__('UPDATE CODE')}}</th>
                                <th rowspan="2" style="border:1px solid #fff;text-align: center;">{{__('EFECTIVITY')}}</th>
                                <th rowspan="2" style="border:1px solid #fff;text-align: center;">{{__('ASSESSED VALUE')}}</th>
                                <th colspan="3" style="border:1px solid #fff;text-align: center;">{{__('Last Transaction')}}</th>
                                <th colspan="4" style="border:1px solid #fff;text-align: center;">{{__('delinquent')}}</th>
                                <th rowspan="2" style="border:1px solid #fff;text-align: center;">{{__('TOTAL')}}</th>
                                <th rowspan="2" style="border:1px solid #fff;text-align: center;">{{__('NOTICE RESPONSE')}}</th>
                                <th rowspan="2" style="border:1px solid #fff;text-align: center;">{{__('Action')}}</th>
                            </tr>
                            <tr>
                                
                                <th style="border:1px solid #fff;text-align: center;">{{__('Last OR NO.')}}</th>
                                <th style="border:1px solid #fff;text-align: center;">{{__('Last OR DATE')}}</th>
                                <th style="border:1px solid #fff;text-align: center;">{{__('Last OR AMOUNT')}}</th>
                                <th style="border:1px solid #fff;text-align: center;width: 15%;">{{__('BASIC TAX')}}</th>
                                <th style="border:1px solid #fff;text-align: center;width: 15%;">{{__('SEF')}}</th>
                                <th style="border:1px solid #fff;text-align: center;width: 15%;">{{__('SHT')}}</th>
                                <th style="border:1px solid #fff;text-align: center;width: 15%;">{{__('TOTAL')}}</th>
                            </tr>
                            </thead>
                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="{{ asset('js/rptdeliquency/delinquency.js') }}?rand={{ rand(0,999) }}"></script>
    <script>
    function ExportToExcel() {
            var elt = document.getElementById('Jq_datatablelist');
            var allDataWorkbook = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });

            // Export all data to a single Excel file
            XLSX.writeFile(allDataWorkbook, 'All_Data.xlsx');
        }   XLSX.writeFile(allPagesWorkbook, fn || ('Account_receive_Lists.' + (type || 'xlsx')));
    
</script>
@endsection
