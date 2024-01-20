@extends('layouts.admin')
<style type="text/css">
    .datefield{padding-top: 26px;}
    .swal2-container{
/*        z-index:9999999 !important;*/
    }
    .select3-container{
        /*z-index: 9999999 !important;*/
    }
    th {
  border-top: 1px solid #dddddd;
  border-bottom: 1px solid #dddddd;
  border-right: 1px solid #dddddd;
}
 
th:first-child {
  border-left: 1px solid #dddddd;
}
.align-right {
  text-align: right;
}

</style>

@section('page-title')
    {{__('Real Property: Assessment[Quarterly]')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Quarterly Assessment Lists')}}</li>
@endsection
@section('action-btn')
<script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>

    <div class="float-end">
        <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
       
         <button onclick="exportToExcel()"  class="btn btn-sm btn-primary action-item" title="Download Quarterly Assessment Sheet"> <i class="ti-files"></i></button>

   
       <!--  <a href="#"  data-url="{{ route('billing.showform') }}?cb_billing_mode=0" title="{{__('New Single Property Billing')}}" class="btn btn-sm btn-primary addNewProperty" {{ (session()->get('billingSelectedBrgy') == '')?'hidden':'' }}>
            <i class="ti-plus"></i>
        </a> -->
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
                        {{ Form::open(array('url' => '','id'=>"rptPropertySerachFilter")) }}
                        <div class="d-flex align-items-center  justify-content-end">
                          <div class="col-xl-1 col-lg-1 col-md-6 col-sm-12 col-12">
                                
                         </div>   
                        <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12 pdr-20" id="barangaydiv">
                                <div class="form-group">
                                    {{Form::label('year',__("Year"),['class'=>'form-label'])}}
                                    <div class="btn-box">
                                        <?php
                                        $currentYear = date('Y');
                                    ?>
                                    {{ Form::text('year', $currentYear, array('class' => 'yearpicker form-control','id'=>'year')) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 pdr-20" id="revisionyeardiv">
                                <div class="form-group">
                                    {{Form::label('revisionyear',__("Quarter"),['class'=>'form-label'])}}
                                    <div class="btn-box">
                                        {{ Form::select('quarter',array('1'=>'1st','2' =>'2nd','3'=>'3rd','4'=>'4th','all'=>'All'), '', array('class' => 'form-control spp_type','id'=>'quarter')) }}
                                    
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 pdr-20">
                              <div class="form-group"> 
                              {{Form::label('revisionyear',__("Search Details"),['class'=>'form-label'])}} 
                                <div class="btn-box">
                                    {{ Form::text('q', '', array('class' => 'form-control','placeholder'=>'Search...','id'=>'rptPropertySearchByText')) }}
                                </div>
                              </div>
                            </div>
                            <div class="col-auto float-end ms-2" style="margin-top:10px;">
                                <a href="#" class="btn btn-sm btn-primary" id="btn_search">
                                    <span class="btn-inner--icon"><i class="ti-search"></i></span>
                                </a>
                                <a href="#" class="btn btn-sm btn-danger" id="btn_clear">
                                    <span class="btn-inner--icon"><i class="ti-trash"></i></span>
                                </a>
                            </div>

                        </div>
                        {{Form::close()}}
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
                                <tr ><th style="text-align:center;border: 0px solid #fff;" colspan="16">SUmmary of Real Property assessments as of end of the quarter</th></tr>
                                <tr >
                                    <th style="text-align:center;border: 1px solid #fff;" rowspan="3">No.</th>
                                    <th style="text-align:center;border: 1px solid #fff;" rowspan="3">Classification</th>
                                    <th style="text-align:center;border: 1px solid #fff;" rowspan="3">No. Of Units</th>
                                    <th style="text-align:center;border: 1px solid #fff;" rowspan="3">{{__('Land(in sq.m.)')}}</th>
                                    <th style="text-align:center;border: 1px solid #fff;" colspan="6">Market Value</th>
                                    <th style="text-align:center;border: 1px solid #fff;" colspan="6">Assessed Value</th>
                                </tr>
                                <tr >
                                    <th style="text-align:center;border: 1px solid #fff;" rowspan="2">{{__('Land')}}</th>
                                    
                                    <th style="text-align:center;" colspan="2">Buildings</th>
                                    <th style="text-align:center;border: 1px solid #fff;" rowspan="2">{{__('Machine')}}</th>
                                    <th style="text-align:center;border: 1px solid #fff;" rowspan="2">{{__("Other Impr.")}}</th>
                                    <th style="text-align:center;border: 1px solid #fff;" rowspan="2">{{__('Total')}}</th>
                                    <th style="text-align:center;border: 1px solid #fff;" rowspan="2">{{__("Land")}}</th>
                                    <th style="text-align:center;" colspan="2">Buildings</th>
                                    <th style="text-align:center;border: 1px solid #fff;" rowspan="2">{{__("Machine")}}</th>
                                    <th style="text-align:center;border: 1px solid #fff;" rowspan="2">{{__("Other Impr")}}</th>
                                    <th style="text-align:center;border: 1px solid #fff;" rowspan="2">{{__("Total")}}</th>
                                </tr>


                                <tr>
                                    <th style="text-align:center;border: 1px solid #fff;">{{__('P175,000 (or less)')}}</th>
                                    <th style="text-align:center;border: 1px solid #fff;">{{__('above (P175,000)')}}</th>
                                    <th style="text-align:center;border: 1px solid #fff;">{{__('P175,000 (or less)')}}</th>
                                    <th style="text-align:center;border: 1px solid #fff;">{{__('above (P175,000)')}}</th>
                                    
                                </tr>
                            </thead>
                        </table>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <div class="modal" id="commonUpDateCodeIntermediateModal1" data-backdrop="static" style="z-index:9999999 !important;">
        <div class="modal-dialog " >
            <div class="modal-content" >
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="body">
            </div>
            </div>
        </div>
    </div> 
    <div class="modal" id="commonUpDateCodeIntermediateModal2" data-backdrop="static" style="z-index:9999999 !important;">
        <div class="modal-dialog " >
            <div class="modal-content" >
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="body">
            </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/yearpicker.js') }}"></script>
    <script src="{{ asset('js/assessment/assessmentqtrly.js') }}?rand={{rand(0,999)}}"></script>
      <script>
     function exportToExcel() {
    const table = document.getElementById("Jq_datatablelist");

    // Create a new workbook
    const workbook = new ExcelJS.Workbook();
    const worksheet = workbook.addWorksheet("Sheet 1");
    worksheet.getRow(1).height = 50; // Set the height to 20 (in points)
    worksheet.getColumn('A').width = 15; 
    // Add headers for the merged cells
    worksheet.getCell("A1").value = "Summary of Real Property assessments as of the quarter";
    worksheet.mergeCells("A1:P1");

    // Add headers for the individual cells
    worksheet.getCell("A2").value = "No.";
    worksheet.mergeCells("A2:A4");
    worksheet.getCell("B2").value = "Classification";
    worksheet.mergeCells("B2:B4");
    worksheet.getCell("C2").value = "No. Of Units";
    worksheet.mergeCells("C2:C4");
    worksheet.getCell("D2").value = "Land (in sq.m.)";
    worksheet.mergeCells("D2:D4");
    worksheet.getCell("E2").value = "Market Value";
    worksheet.mergeCells("E2:J2");
    worksheet.getCell("E3").value = "Land";
    worksheet.mergeCells("E3:E4");
    worksheet.getCell("F3").value = "Buildings";
    worksheet.mergeCells("F3:G3");
    worksheet.getCell("F4").value = "P175,000 (or less)";
    worksheet.getCell("G4").value = "above (P175,000)";
    worksheet.getCell("H3").value = "Machine";
    worksheet.mergeCells("H3:H4");
    worksheet.getCell("I3").value = "Other Impr.";
    worksheet.mergeCells("I3:I4");
    worksheet.getCell("J3").value = "Total";
    worksheet.mergeCells("J3:J4");
    worksheet.getCell("K2").value = "Assessed Value";
    worksheet.mergeCells("K2:P2");
    worksheet.getCell("K3").value = "Land";
    worksheet.mergeCells("K3:K4");
    worksheet.getCell("L3").value = "Buildings";
    worksheet.mergeCells("L3:M3");
    worksheet.getCell("L4").value = "P175,000 (or less)";
    worksheet.getCell("M4").value = "above (P175,000)";
    worksheet.getCell("N3").value = "Machine";
    worksheet.mergeCells("N3:N4");
    worksheet.getCell("O3").value = "Other Impr.";
    worksheet.mergeCells("O3:O4");
    worksheet.getCell("P3").value = "Total";
    worksheet.mergeCells("P3:P4");

    // Set cell borders for the headers
    for (let col = 1; col <= 16; col++) {
        const cell1 = worksheet.getCell(1, col);
        const cell2 = worksheet.getCell(2, col);
        const cell3 = worksheet.getCell(3, col);
        const cell4 = worksheet.getCell(4, col);
       cell1.style.font = {
            size: 16,
        };

      // Set padding to 5px
      cell1.alignment = {
        vertical: 'middle',
        horizontal: 'center',
      };
      cell1.border = {
        top: { style: 'thin' },
        left: { style: 'thin' },
        right: { style: 'thin' },
        bottom: { style: 'thin' },
      };
        cell2.style.font = {
            size: 12,
        };

      // Set padding to 5px
      cell2.alignment = {
        vertical: 'middle',
        horizontal: 'center',
      };
      cell2.border = {
        top: { style: 'thin' },
        left: { style: 'thin' },
        right: { style: 'thin' },
        bottom: { style: 'thin' },
      };

      // Apply the same styles to cells in rows 3 and 4
      cell3.style = { ...cell2.style };
      cell4.style = { ...cell2.style };

        worksheet.getCell(1, col).fill = {
        type: 'pattern',
        pattern: 'solid',
        fgColor: { argb: 'bfd1e0' } // Yellow background color (RGBA format)
    };
    worksheet.getCell(2, col).fill = {
        type: 'pattern',
        pattern: 'solid',
        fgColor: { argb: 'cdf5bc' } // Yellow background color (RGBA format)
    };
    worksheet.getCell(3, col).fill = {
        type: 'pattern',
        pattern: 'solid',
        fgColor: { argb: 'cdf5bc' } // Yellow background color (RGBA format)
    };
    worksheet.getCell(4, col).fill = {
        type: 'pattern',
        pattern: 'solid',
        fgColor: { argb: 'cdf5bc' } // Yellow background color (RGBA format)
    };
    }
    const dataRows = table.querySelectorAll("tr");
    dataRows.forEach((dataRow, rowIndex) => {
        // Skip header rows (1-4)
        if (rowIndex < 4) return;

        const dataCells = dataRow.querySelectorAll("td");
        dataCells.forEach((dataCell, colIndex) => {
            worksheet.getCell(rowIndex + 1, colIndex + 1).value = dataCell.textContent.trim();
        });
    });

    // Convert the workbook to a blob and trigger the download
    workbook.xlsx.writeBuffer().then(buffer => {
        const blob = new Blob([buffer], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });
        if (navigator.msSaveBlob) {
            // For IE 10 and 11
            navigator.msSaveBlob(blob, "Quarterly Assessment.xlsx");
        } else {
            // For other browsers
            const link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.style.display = "none";
            link.download = "Quarterly Assessment.xlsx";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    });
}



    </script>
@endsection

