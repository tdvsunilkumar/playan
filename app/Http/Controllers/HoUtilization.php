<?php

namespace App\Http\Controllers;

use App\Models\HoInventoryUtilization;
use App\Models\HoMedicalCertificate;
use Illuminate\Http\Request;
use App\Models\HoIssuance;
use Auth;
use DB;
use Carbon\Carbon;

class HoUtilization extends Controller
{
    public $arrcategory = array(""=>"Please Select");

    private $slugs;
    public function __construct(){
        $this->_HoInventoryUtilization = new HoInventoryUtilization(); 
        $this->slugs = 'reports-inventory-utilization';

        foreach ($this->_HoInventoryUtilization->getcategoryId() as $val) {
            $this->arrcategory[$val->id]=$val->inv_category;
        }
    }
 
    public function index(){
        try{
            $suppliers = $this->_HoInventoryUtilization->getSuppliers();
            $select_suppliers = ['' => 'Select Suppliers'];
            foreach ($suppliers as $key => $value) {
                $select_suppliers[$value->id] = $value->business_name;
            };

            $arrcategory = $this->arrcategory;
            $date_ranges = config('constants.reportingDateRanges');
            $export_as = ['' => 'Select export type', 'pageview' => 'Page View', 'excel' => 'Excel', 'pdf' => 'PDF'];
            $orders = ['' => 'select order by', 'ASC' => 'Ascending', 'DESC' => 'Descending'];
            $select_date_ranges = ['' => 'Select Date Range'];
            foreach ($date_ranges as $key => $value) {
                $select_date_ranges[json_encode($value['data'])] = $value['name'];
            };
            $this->is_permitted($this->slugs, 'read');
            return view('medicalUtilization.index',compact('select_suppliers', 'select_date_ranges','export_as','orders','arrcategory'));
        }catch(Exception $e){
            return ($e->getMessage());
        }
    }

    public function GetList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_HoInventoryUtilization->getList($request);
        $arr=array();
        $i="0";
        $sr_no=(int)$request->input('start')-1;
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->util_rep_status == 1) ? '<div class="action-btn bg-danger ms-2">
                            <a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'>
                            </a>' : 
                        '<div class="action-btn bg-info ms-2">
                            <a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'>
                            </a>';   
            }
            $arr[$i]['srno']= $sr_no;
            $arr[$i]['util_rep_type']= $row->util_rep_type == 1 ? 'Internal' : 'External';
            $arr[$i]['util_rep_path']= $row->util_rep_path;
            $arr[$i]['util_rep_name']= $row->util_rep_name;
            $arr[$i]['util_rep_range']= $row->util_rep_range;
            $arr[$i]['util_rep_year']= $row->util_rep_year;
            $arr[$i]['util_rep_status']= ($row->util_rep_status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']= $actions;
            $i++;
        }
        $totalRecords=$data['data_cnt'];
        $json_data = array(
            "recordsTotal"    => intval( $totalRecords ),  
            "recordsFiltered" => intval($totalRecords),
            "data"            => $arr   // total data array
        );
        echo json_encode($json_data);
    }

    public function ActiveInactive(Request $request){
        try {
            $this->is_permitted($this->slugs, 'delete');
            $id = $request->input('id');
            $is_activeinactive = $request->input('is_activeinactive');
            $data=array('util_rep_status' => $is_activeinactive);
            $this->_HoInventoryUtilization->updateActiveInactive($id,$data);
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

    public function store(request $request){

        $arrcategory = $this->arrcategory;

        try {
            $suppliers = $this->_HoInventoryUtilization->getSuppliers();
            $select_suppliers = ['' => 'Select Suppliers'];
            foreach ($suppliers as $key => $value) {
                $select_suppliers[$value->id] = $value->business_name;
            };

            $date_ranges = config('constants.reportingDateRanges');
            $select_date_ranges = ['' => 'Select Date Range'];
            foreach ($date_ranges as $key => $value) {
                $select_date_ranges[json_encode($value['data'])] = $value['name'];
            };

            // This Section Is For Add
            if($request->isMethod('post')!=""){
                // $data = $request->all();
                // $data['created_by'] = Auth::user()->id;
                // $data['updated_by'] = Auth::user()->id;

                // unset($data['submit']);
                // $message = "";
                
                // $data['util_rep_status'] = 1;
                // $this->_HoInventoryUtilization->addData($data);
                // $message = "Added.";
                // return redirect()->route('medicine-supplies-utilization-report')->with('success', __('Medical Utilization Successfully ' .$message));
                return redirect()->route('medicine-supplies-report',['year'=>$request->year,'type'=>$request->type,'category'=>$request->category]);

            }
            
            return view('medicalUtilization.create', compact('select_suppliers','select_date_ranges','arrcategory'));

        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), 
			[
                // 'util_rep_type'=>'required',
                // 'util_rep_range'=>'required',
                // 'util_rep_year'=>'required',
                // 'util_rep_remarks' => 'required'
                
			],[
				'util_rep_type.required' => 'Receive Type Is Required',
				'util_rep_range.required' => 'Date Range Required',
				'util_rep_year.required' => 'Year Is Required',
				'util_rep_remarks.required' => 'Remarks Is Required'
			]
        );
        $arr=array('ESTATUS'=>0);
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['ESTATUS'] = 1;
        }
        echo json_encode($arr);exit;
    }

    public function export_to_excel(Request $request)
    {
        $columns = ['A','B','C','D','E','F','G','H','I','J','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AY','AZ','BA','BB','BC','BD'];
        if ($request->get('ledger_type') == 'general-ledger') {
            $titles = !empty($request->code) ? AcctgAccountGeneralLedger::find($request->code)->description : '';
            $codes = !empty($request->code) ? AcctgAccountGeneralLedger::find($request->code)->code : '';
        } else {
            $titles = !empty($request->get('code')) ? AcctgAccountSubsidiaryLedger::find($request->get('code'))->description : '';
            $codes = !empty($request->get('code')) ? AcctgAccountSubsidiaryLedger::find($request->get('code'))->code : '';
        }
        $names = '';
        if ($request->category == 'Suppliers') {
            if (!empty($request->name)) {
                $suppliers = GsoSupplier::find($request->name);
                $names .= $suppliers->business_name ? ucwords($suppliers->business_name).' ' : '';
                $names .= $suppliers->branch_name ? '('.ucwords($suppliers->branch_name).')' : '';
            }
        } else if ($request->category == 'Suppliers') {
            if (!empty($request->name)) {
                $clients = Client::find($request->name);
                $names .= $clients->rpo_first_name ? ucwords($clients->rpo_first_name).' ' : '';
                $names .= $clients->rpo_middle_name ? ucwords($clients->rpo_middle_name).' ' : '';
                $names .= $clients->rpo_custom_last_name ? ucwords($clients->rpo_custom_last_name) : '';
            }
        }
        $ledgerType = $request->ledger_type == 'subsidiary-ledger' ? 'SUBSIDIARY LEDGER' : 'GENERAL LEDGER';
        $funds = $request->fund_code_id ? AcctgFundCode::find($request->fund_code_id)->description : '';
        $category = $request->category ? $request->category : '';
        $dates = 'As of '.date('d-M-Y', strtotime($request->date_from)).' to '.date('d-M-Y', strtotime($request->date_to));

        $style = [
            'borders' => [
                'allborders' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                ]
            ]
        ];

        $results = $this->reportAcctgLedgerRepository->get($request);
        $excel = PHPExcel_IOFactory::createReader('Excel2007');
        $excel = $excel->load('templates/excel/ledger.xls'); 

        $file = 'downloads/ledger.xls';
        if (file_exists($file)) {
            unlink($file);
        }

        $excel->setActiveSheetIndex(0);
        $excel->getActiveSheet()->setCellValue('A5', $ledgerType);
        $excel->getActiveSheet()->setCellValue('A6', $funds);
        $excel->getActiveSheet()->setCellValue('A7', $dates);
        $excel->getActiveSheet()->setCellValue('C10', $titles);
        $excel->getActiveSheet()->setCellValue('F10', $codes);
        $excel->getActiveSheet()->setCellValue('C11', $names);
        $excel->getActiveSheet()->setCellValue('F11', $category);

        if (!empty($results)) {
            $row = 14;
            foreach ($results as $res) {
                $column = 0;
                $excel->getActiveSheet()->setCellValue($columns[$column].''.$row, $res->jev_no)->getStyle($columns[$column].''.$row)->applyFromArray($style); $column++;
                $excel->getActiveSheet()->setCellValue($columns[$column].''.$row, $res->created)->getStyle($columns[$column].''.$row)->applyFromArray($style); $column++;
                $excel->getActiveSheet()->setCellValue($columns[$column].''.$row, $res->payee)->getStyle($columns[$column].''.$row)->applyFromArray($style); $column++;
                $excel->getActiveSheet()->setCellValue($columns[$column].''.$row, $res->particulars)->getStyle($columns[$column].''.$row)->applyFromArray($style); $column++;
                $excel->getActiveSheet()->setCellValue($columns[$column].''.$row, $res->debit)->getStyle($columns[$column].''.$row)->applyFromArray($style); $column++; 
                $excel->getActiveSheet()->setCellValue($columns[$column].''.$row, $res->credit)->getStyle($columns[$column].''.$row)->applyFromArray($style); $column++; 
                $excel->getActiveSheet()->setCellValue($columns[$column].''.$row, $res->balance)->getStyle($columns[$column].''.$row)->applyFromArray($style); $column++; 
                $row++;              
            }
        }

        $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $objWriter->save($file);

        if (file_exists($file)) {
            return response()->download($file);
        } else {
            return response()->noContent();
        }
    }

    public function export_to_pdf(Request $request)
    {
        $html = file_get_contents(resource_path('views\reports\pdf_views\ledger.html')); ///change this to the view file you will be using

        $mpdf  = new PDF( [
            'mode' => 'utf-8',
            'format' => 'Folio',
            'margin_header' => '3',
            'margin_top' => '20',
            'margin_bottom' => '20',
            'margin_footer' => '2',
        ]);    
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->debug = true;
        $mpdf->showImageErrors = true;
        $mpdf->text_input_as_HTML = true;

        // {{report_table}}
        $report_table = '';
        $rows = $this->reportAcctgLedgerRepository->get($request);
        foreach ($rows as $value) {
            $report_table .= '<tr>
                        <td>'.$value->jev_no.'</td>
                        <td>'.$value->created.'</td>
                        <td>'.$value->payee.'</td>
                        <td>'.$value->particulars.'</td>
                        <td>'.$value->debit.'</td>
                        <td>'.$value->credit.'</td>
                        <td>'.$value->balance.'</td>
                    </tr>
            ';
        }

        // {{title}} {{codes}}
        if ($request->get('ledger_type') == 'general-ledger') {
            $titles = !empty($request->code) ? AcctgAccountGeneralLedger::find($request->code)->description : '';
            $codes = !empty($request->code) ? AcctgAccountGeneralLedger::find($request->code)->code : '';
        } else {
            $titles = !empty($request->get('code')) ? AcctgAccountSubsidiaryLedger::find($request->get('code'))->description : '';
            $codes = !empty($request->get('code')) ? AcctgAccountSubsidiaryLedger::find($request->get('code'))->code : '';
        }

        // {{names}}
        $names = '';
        if ($request->category == 'Suppliers') {
            if (!empty($request->name)) {
                $suppliers = GsoSupplier::find($request->name);
                $names .= $suppliers->business_name ? ucwords($suppliers->business_name).' ' : '';
                $names .= $suppliers->branch_name ? '('.ucwords($suppliers->branch_name).')' : '';
            }
        } else if ($request->category == 'Suppliers') {
            if (!empty($request->name)) {
                $clients = Client::find($request->name);
                $names .= $clients->rpo_first_name ? ucwords($clients->rpo_first_name).' ' : '';
                $names .= $clients->rpo_middle_name ? ucwords($clients->rpo_middle_name).' ' : '';
                $names .= $clients->rpo_custom_last_name ? ucwords($clients->rpo_custom_last_name) : '';
            }
        }
        // {{funds}}
        $funds = $request->fund_code_id ? AcctgFundCode::find($request->fund_code_id)->description : '';
        // {{ledger_type}}
        $ledger_type = $request->ledger_type == 'subsidiary-ledger' ? 'SUBSIDIARY' : 'GENERAL';
        // {{date_from}} {{date_to}}
        $date_from = date('d-M-Y', strtotime($request->input('date_from')));
        $date_to = date('d-M-Y', strtotime($request->input('date_to')));

        //write data to html
        $html = str_replace('{{logo}}',public_path('/assets/images/logo.png'), $html);
        $html = str_replace('{{report_table}}',$report_table, $html);
        $html = str_replace('{{title}}',$titles, $html);
        $html = str_replace('{{codes}}',$codes, $html);
        $html = str_replace('{{names}}',$names, $html);
        $html = str_replace('{{funds}}',$funds, $html);
        $html = str_replace('{{ledger_type}}', $ledger_type, $html);
        $html = str_replace('{{date_from}}', $date_from, $html);
        $html = str_replace('{{date_to}}', $date_to, $html);
        $html = str_replace('{{prepared_by}}', 'Christy Fabro', $html);
        $html = str_replace('{{approved_by}}', 'Rogelmar Denopol', $html);
        $mpdf->WriteHTML($html);

        return $mpdf->Output('pdf_file.pdf','D');
    }

    public function export_to_pageview(Request $request)
    {  
        if ($request->get('ledger_type') == 'general-ledger') {
            $titles = !empty($request->code) ? AcctgAccountGeneralLedger::find($request->code)->description : '';
            $codes = !empty($request->code) ? AcctgAccountGeneralLedger::find($request->code)->code : '';
        } else {
            $titles = !empty($request->get('code')) ? AcctgAccountSubsidiaryLedger::find($request->get('code'))->description : '';
            $codes = !empty($request->get('code')) ? AcctgAccountSubsidiaryLedger::find($request->get('code'))->code : '';
        }
        $names = '';
        if ($request->category == 'Suppliers') {
            if (!empty($request->name)) {
                $suppliers = GsoSupplier::find($request->name);
                $names .= $suppliers->business_name ? ucwords($suppliers->business_name).' ' : '';
                $names .= $suppliers->branch_name ? '('.ucwords($suppliers->branch_name).')' : '';
            }
        } else if ($request->category == 'Suppliers') {
            if (!empty($request->name)) {
                $clients = Client::find($request->name);
                $names .= $clients->rpo_first_name ? ucwords($clients->rpo_first_name).' ' : '';
                $names .= $clients->rpo_middle_name ? ucwords($clients->rpo_middle_name).' ' : '';
                $names .= $clients->rpo_custom_last_name ? ucwords($clients->rpo_custom_last_name) : '';
            }
        }
        $funds = $request->fund_code_id ? AcctgFundCode::find($request->fund_code_id)->description : '';
        $rows = $this->reportAcctgLedgerRepository->get($request);
        return view('reports.accounting.ledgers.pageview')->with(compact('titles', 'codes', 'names', 'funds', 'rows'));
    }
}
