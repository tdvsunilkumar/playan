<?php

namespace App\Http\Controllers;
use App\Models\AcctgAccountGroupSubsubmajor;
use App\Models\CommonModelmaster;
use App\Models\HrEmployee;
use App\Models\GsoSupplierContactPerson;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\CboPayeeInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PDF;

class CboPayeeController extends Controller
{
    private CboPayeeInterface $cboPayeeRepository;
    private $carbon;
    private $params = [];
    private $slugs;

    public function __construct(CboPayeeInterface $cboPayeeRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->cboPayeeRepository = $cboPayeeRepository;
        $this->carbon = $carbon;
        $this->slugs = 'finance/payee';
    }

    public function index(Request $request)
    {           
        $this->is_permitted($this->slugs, 'read');
        $account_groups = $this->cboPayeeRepository->allAccountGroups();
        $paye_type = '';
        $barangays = $this->cboPayeeRepository->allBarangays();
        $major_account_groups = ['' => 'select a major account group'];
        $scp_id = $this->cboPayeeRepository->allSupplier();;
        $hr_employee_id = $this->cboPayeeRepository->allEmpData();
        return view('finance.cbopayee.index')->with(compact('account_groups','barangays','hr_employee_id','scp_id', 'major_account_groups','paye_type'));
    }
    
    public function lists(Request $request) 
    { 
        $keywords     = $request->get('keywords');   
        $sortBy       = $request->get('sortBy');  
        $orderBy      = $request->get('orderBy'); 
        $cur_page     = null != $request->post('page') ? $request->post('page') : 1;
        $per_page     = $request->get('perPage') == -1 ? 0 : $request->get('perPage');
        $page         = $cur_page !== null ? $cur_page : 1;
        $start_from   = ($page-1) * $per_page;

        $previous_btn = true;
        $next_btn = true;
        $value = 0;
        $first_btn = true;
        $pagess = 0;
        $last_btn = true;
        
        $query = $this->getLineItems($start_from, $per_page, $keywords, $sortBy, $orderBy);
        $count = $this->getPageCount($keywords);
        $no_of_paginations = ceil($count / $per_page);
        $assets = url('assets/images/illustrations/work.png');

        $sorting = ($orderBy !== '') ? ($orderBy == 'asc') ? 'sorting_asc' : 'sorting_desc' : 'sorting';

        $msg  = '';
        $msg .= '<div class="table-responsive">';
        $msg .= '<table data-row-count="'.$count.'" class="table table-striped align-middle table-row-dashed fs-6 gy-3" id="submajorAccountGroupTable">';
        $msg .= '<thead>';
        $msg .= '<tr class="text-start text-gray-400 fw-bolder fs-6 text-uppercase gs-0">';
        $msg .= ($sorting !== '' && $sortBy == 'id') ? '<th class="'. $sorting .'" data-row="id">ID</th>' : '<th class="sorting" data-row="id">ID</th>';
        $msg .= ($sorting !== '' && $sortBy == 'paye_name') ? '<th class="'. $sorting .'" data-row="paye_name">Reference Name</th>' : '<th class="sorting" data-row="paye_name">Payee Name</th>';
        $msg .= ($sorting !== '' && $sortBy == 'paye_type') ? '<th class="'. $sorting .'" data-row="paye_type">Type</th>' : '<th class="sorting" data-row="paye_type">Payee Type</th>';
        $msg .= '<th class="text-center">Last Modified</th>';
        $msg .= '<th class="text-center">Status</th>';
        $msg .= '<th class="text-center">Actions</th>';
        $msg .= '</tr>';
        $msg .= '</thead>';
        $msg .= '<tbody class="text-gray-600">';

        if ($count <= 0) {
            $msg .= '<tr>';
            $msg .= '<td colspan="20" class="text-center">there are no data has been displayed.<br/><br/><br/>';
            $msg .= '<img class="mw-100" alt="" src="'.$assets.'">';
            $msg .= '</td>';
            $msg .= '<tr>';
        } else {
            foreach ($query as $row) { 
                if($row->payeeType == 1)
                {
                    $type = "Employee | Office";
                }else if($row->payeeType == 2)
                {
                    $type = 'Supplier';
                }
                else{
                    $type = 'Other Entity';
                }
               
                $status = ($row->status == 1) ? '<span class="badge badge-status rounded-pill bg-info p-2">Active</span>' : '<span class="badge badge-status rounded-pill bg-secondary p-2">Inactive</span>';
                $icon = ($row->status == 1) ? '<i class="ti-trash text-white"></i>' : '<i class="ti-reload text-white"></i>';
                $msg .= '<tr data-row-id="' . $row->id . '" data-row-code="' . $row->payeeName . '" data-row-status="' . $row->status . '">';
                $msg .= '<td>' . $row->id . '</td>';
                $msg .= '<td>' . $row->payeeName . '</td>';
                $msg .= '<td>' . $type . '</td>';
                $msg .= '<td class="text-center">' . $row->modified . '</td>';
                $msg .= '<td class="text-center">' . $status . '</td>';
                $msg .= '<td class="action text-center">';
                $msg .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="Edit">';
                $msg .= '<i class="ti-pencil text-white"></i>';
                $msg .= '</a>';
                $msg .= '<a href="'.route('payee.print',['id'=>$row->id]).'" class="action-btn print-btn bg-primary btn m-1 btn-sm align-items-center" title="Print" target="_blank">';
                $msg .= '<i class="ti-printer text-white"></i>';
                $msg .= '</a>';
                $msg .= ($row->status == 1) ? '<a href="javascript:;" class="action-btn delete-btn bg-danger btn m-1 btn-sm  align-items-center" title="Remove">' : '<a href="javascript:;" class="action-btn delete-btn bg-info btn m-1 btn-sm  align-items-center" title="Restore">';
                $msg .= $icon;
                $msg .= '</a>';
                $msg .= '</td>';
                $msg .= '</tr>';
            }
        }
        $msg .= '</tbody>';
        $msg .= '</table>';
        $msg .= '</div>';

        if ($cur_page >= 5) {
            $start_loop = $cur_page - 2;
            if ($no_of_paginations > $cur_page + 2)
                $end_loop = $cur_page + 2;
            else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
                $start_loop = $no_of_paginations - 4;
                $end_loop = $no_of_paginations;
            } else {
                $end_loop = $no_of_paginations;
            }
        } else {
            $start_loop = 1;
            if ($no_of_paginations > 5)
                $end_loop = 5;
            else
                $end_loop = $no_of_paginations;
        }

        $msg .= '<div class="row">';
        
        $pagination  = '<div class="dataTables_paginate paging_simple_numbers" id="kt_purchase_orders_table_paginate">';
        $pagination .= '<ul class="pagination mb-0">';
        // FOR ENABLING THE PREVIOUS BUTTON
        if ($previous_btn && $cur_page > 1) {
            $pre = $cur_page - 1;
            $pagination .= '<li class="paginate_button page-item" p="'.$pre.'">';
            $pagination .= '<a href="javascript:;" aria-label="Previous" class="page-link">';
            $pagination .= 'Prev';
            $pagination .= '</a>';
            $pagination .= '</li>';
        } else if ($previous_btn) {
            $pagination .= '<li class="paginate_button page-item disabled">';
            $pagination .= '<a href="javascript:;" aria-label="Previous" class="page-link">';
            $pagination .= 'Prev';
            $pagination .= '</a>';
            $pagination .= '</li>';
        }
        for ($i = $start_loop; $i <= $end_loop; $i++) {

            if ($cur_page == $i)
                $pagination .= '<li class="paginate_button page-item active" p="'.$i.'"><a href="javascript:;" class="page-link">'.$i.'</a></li>';
            else
                $pagination .= '<li class="paginate_button page-item ping" p="'.$i.'"><a href="javascript:;" class="page-link">'.$i.'</a></li>';
        }

        // TO ENABLE THE NEXT BUTTON
        if ($next_btn && $cur_page < $no_of_paginations) {
            $nex = $cur_page + 1;
            $pagination .= '<li class="paginate_button page-item" p="'.$nex.'">';
            $pagination .= '<a href="javascript:;" aria-label="Next" class="page-link">';
            $pagination .= 'Next';
            $pagination .= '</a>';
            $pagination .= '</li>';
        } else if ($next_btn) {
            $pagination .= '<li class="paginate_button page-item disabled">';
            $pagination .= '<a href="javascript:;" aria-label="Next" class="page-link">';
            $pagination .= 'Next';
            $pagination .= '</a>';
            $pagination .= '</li>';
        }
        $pagination .= '</ul>';
        $pagination .= '</div>';

        $show = ($per_page < $count) ? (($per_page * $cur_page) <= $count) ? ($per_page * $cur_page) : $count : $count;  
        $cur_page = ($cur_page <= 1) ?  ($count != 0) ? $cur_page : $count : (($cur_page - 1) * $per_page) + 1;
        $total_string = '<div class="infos text-right">Showing '. $cur_page .' to '.$show.' of '.$count.' entries</div>';
        
        $msg .= '<div class="col-sm-6 d-flex justify-content-start align-items-center">'.$total_string.'</div>';
        $msg .= '<div class="col-sm-6 d-flex justify-content-end">' . $pagination . '</div>';
        $msg .= '</div>';
        echo $msg;
    }
    
    public function getLineItems($startFrom, $perPage, $keywords, $sortBy, $orderBy) 
    {
        $res = $this->cboPayeeRepository->listItems($startFrom, $perPage, $keywords, $sortBy, $orderBy);
        return $res->map(function($payeeData) {
            return (object) [
                'id' => $payeeData->payeeId,
                'payeeName' => $payeeData->payeeName,
                'payeeType' => $payeeData->payeeType,
                'modified' => ($payeeData->payeeUpdatedAt !== NULL) ? date('d-M-Y', strtotime($payeeData->payeeUpdatedAt)).'<br/>'. date('h:i A', strtotime($payeeData->payeeUpdatedAt)) : date('d-M-Y', strtotime($payeeData->payeeCreatedAt)).'<br/>'. date('h:i A', strtotime($payeeData->payeeCreatedAt)),
                'status' => $payeeData->status
            ];
        });
    }

    public function getPageCount($keywords) 
    {
        return $this->cboPayeeRepository->listCount($keywords);
    }
    
    public function store(Request $request): JsonResponse 
    {       
        $this->is_permitted($this->slugs, 'create');
        if($request->paye_type == 1)
        {
            $rows = $this->cboPayeeRepository->validate($request->paye_type,$request->hr_employee_id,);
            if ($rows > 0) {
                return response()->json([
                    'title' => 'Oh snap!',
                    'text' => 'You cannot create a payee with an existing employee id.',
                    'type' => 'error',
                    'class' => 'btn-danger'
                ]);
            }
            $hr_employee_id=$request->hr_employee_id;
            $scp_id=NULL;
        }
        elseif($request->paye_type == 2)
        {
            $rows = $this->cboPayeeRepository->validate($request->paye_type,$request->scp_id,);
            if ($rows > 0) {
                return response()->json([
                    'title' => 'Oh snap!',
                    'text' => 'You cannot create a payee with an existing supplier id.',
                    'type' => 'error',
                    'class' => 'btn-danger'
                ]);
            }
            $scp_id=$request->scp_id;
            $hr_employee_id=NULL;  
        }
        else{
            $hr_employee_id=NULL;
            $scp_id=NULL;
        }

        $addr = array();
        $full_brgy_det=$this->cboPayeeRepository->brgyDetails($request->brgy_code);
        if ($request->paye_address_lotno !== NULL) { $addr[] = $request->paye_address_lotno; }
        if ($request->paye_address_street !== NULL) { $addr[] = $request->paye_address_street; }
        if ($request->paye_address_subdivision !== NULL) { $addr[] = $request->paye_address_subdivision; }
        if ($full_brgy_det !== NULL) { $addr[] = $full_brgy_det; }

        $details = array(
            'paye_type' => $request->paye_type,
            'scp_id' => $scp_id,
            'hr_employee_id' => $hr_employee_id,
            'paye_name' => $request->paye_name,
            'paye_address_lotno' => $request->paye_address_lotno,
            'paye_address_street' => $request->paye_address_street,
            'paye_address_subdivision' => $request->paye_address_subdivision,
            'paye_full_address' => implode(', ',$addr) .' '. trim($request->get('address')),
            'brgy_code' => $request->brgy_code,
            'paye_telephone_no' => $request->paye_telephone_no,
            'paye_mobile_no' => $request->paye_mobile_no,
            'paye_email_address' => $request->paye_email_address,
            'paye_fax_no' => $request->paye_fax_no,
            'paye_tin_no' => $request->paye_tin_no,
            'paye_remarks' => $request->paye_remarks,
            'paye_status' => 1,
            'created_at' => $this->carbon::now(),
            'paye_generated_by' => Auth::user()->id
        );

        return response()->json(
            [
                'data' => $this->cboPayeeRepository->create($details),
                'title' => 'Well done!',
                'text' => 'Payee has been created successfully.',
                'type' => 'success',
                'class' => 'btn-brand'
            ],
            Response::HTTP_CREATED
        );
    }

    public function find(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->cboPayeeRepository->find($id)
        ]);
    }

    public function update(Request $request, $id): JsonResponse 
    {   
        // $this->validated('edit acctg account group submajor');

        // $rows = $this->cboPayeeRepository->validate($request->get('code'), $request->acctg_account_group_id, $request->acctg_account_group_major_id, $id);
        // if ($rows > 0) {
        //     return response()->json([
        //         'title' => 'Oh snap!',
        //         'text' => 'You cannot update a submajor account group with an existing code.',
        //         'type' => 'error',
        //         'class' => 'btn-danger'
        //     ]);
        // }
        $this->is_permitted('update');
        $o_data=$this->cboPayeeRepository->find($id);
        if($request->paye_type == 1)
        {
            if($o_data->hr_employee_id != $request->hr_employee_id)
            {
                $rows = $this->cboPayeeRepository->validate($request->paye_type,$request->hr_employee_id);
                if ($rows > 0) {
                    return response()->json([
                        'title' => 'Oh snap!',
                        'text' => 'You cannot update a payee with an existing employee id.',
                        'type' => 'error',
                        'class' => 'btn-danger'
                    ]);
                }
            }
           
            $hr_employee_id=$request->hr_employee_id;
            $scp_id=NULL;
        }
        elseif($request->paye_type == 2)
        {
            if($o_data->scp_id != $request->scp_id)
            {
                $rows = $this->cboPayeeRepository->validate($request->paye_type,$request->scp_id);
                if ($rows > 0) {
                    return response()->json([
                        'title' => 'Oh snap!',
                        'text' => 'You cannot update a payee with an existing supplier id.',
                        'type' => 'error',
                        'class' => 'btn-danger'
                    ]);
                }
            }
            $scp_id=$request->scp_id;
            $hr_employee_id=NULL;
        }
        else{
            $hr_employee_id=NULL;
            $scp_id=NULL;
        }

        $details = array(
            'paye_type' => $request->paye_type,
            'scp_id' => $scp_id,
            'hr_employee_id' => $hr_employee_id,
            'paye_name' => $request->paye_name,
            'paye_address_lotno' => $request->paye_address_lotno,
            'paye_address_street' => $request->paye_address_street,
            'paye_address_subdivision' => $request->paye_address_subdivision,
            'brgy_code' => $request->brgy_code,
            'paye_telephone_no' => $request->paye_telephone_no,
            'paye_mobile_no' => $request->paye_mobile_no,
            'paye_email_address' => $request->paye_email_address,
            'paye_fax_no' => $request->paye_fax_no,
            'paye_tin_no' => $request->paye_tin_no,
            'paye_remarks' => $request->paye_remarks,
            'paye_status' => 1,
            'updated_at' => $this->carbon::now(),
            'paye_modified_by' => Auth::user()->id
        );

        return response()->json([
            'data' => $this->cboPayeeRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The Payee has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function remove(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted('update');
        $details = array(
            'updated_at' => $this->carbon::now(),
            'paye_modified_by' => Auth::user()->id,
            'paye_status' => 0
        );

        return response()->json([
            'data' => $this->cboPayeeRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The Payee has been successfully removed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function restore(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted('update');
        $details = array(
            'updated_at' => $this->carbon::now(),
            'paye_modified_by' => Auth::user()->id,
            'paye_status' => 1
        );

        return response()->json([
            'data' => $this->cboPayeeRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The Payee has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function fetch_group_code(Request $request)
    {   
        // $account = (!empty($request->get('account'))) ? $this->cboPayeeRepository->findAcctGrp($request->get('account'))->code : '';
        // $major = (!empty($request->get('major'))) ? $this->cboPayeeRepository->findMajorAcctGrp($request->get('major'))->prefix : '';
        // return response()->json([
        //     'account' => $account,
        //     'major' => $major
        // ]);
        return (new HrEmployee)->empDataById($request->emp_id);
    }
    public function  fetch_sup_data(Request $request)
    {   
        return (new GsoSupplierContactPerson)->supDataById($request->sup_id);
    }
   

    public function reload_major_account(Request $request, $account) 
    {   
        $this->validated('edit acctg account group submajor');
        return response()->json([
            'data' => $this->cboPayeeRepository->reload_major_account($account)
        ]);
    }

    public function print(Request $request, $id): JsonResponse 
    {
        $data = $this->cboPayeeRepository->find($id);
        PDF::SetTitle('BIR 2307:');    
        PDF::SetMargins(0, 0, 0,true);    
        PDF::AddPage('P', 'FOLIO');
        $height = PDF::getPageHeight();
        $width = PDF::getPageWidth();
        PDF::Image('./assets/images/forms/bir2307-1.jpg',0, 0, $width, 0, '', '', '', false, 300, '', false, false, 0);
        $border = 1;
        PDF::SetLineStyle(array('width' => 0.50, 'cap' => 'butt', 'join' => 'miter'));
        PDF::SetFont('helvetica','B',8);

        // data
        // find field: where should data put
        $date = '11372023';//field: For the Period From
        PDF::SetY(39);
        PDF::SetX(53);
        foreach (str_split($date) as $value) {
            PDF::MultiCell(4.6, 0, $value, 0, 'C',0,0);
        }
        $date = '11172023';//field: For the Period To
        PDF::SetY(39);
        PDF::SetX(140);
        foreach (str_split($date) as $value) {
            PDF::MultiCell(4.6, 0, $value, 0, 'C',0,0);
        }

        $tin =  $data->paye_tin_no;//field: Part 1: Taxpayer Identification Number (TIN)
        $x = 68;
        PDF::SetY(50);
        PDF::SetX(72);
        foreach (str_split($tin) as $value) {
            if ($value != '-') {
                PDF::MultiCell(5, 0, $value, 0, 'C',0,0);
            } else {
                PDF::MultiCell(3.3, 0, '', 0, 'C',0,0);
            }
        }
        PDF::MultiCell(0, 0, $data->paye_name, 0, 'L', 0, 1, 12, 59, true);//field: Payee’s Name
        PDF::MultiCell(0, 0, $data->paye_full_address, 0, 'L', 0, 1, 12, 69, true);//field: Registered Address
        $zipcode = '3360';//field: 4A Zipcode
        PDF::SetY(69);
        PDF::SetX(190);
        foreach (str_split($zipcode) as $value) {
            PDF::MultiCell(4.6, 0, $value, 0, 'C',0,0);
        }
        PDF::MultiCell(0, 0, 'Change me', 0, 'L', 0, 1, 12, 79, true);//field: 5 Foreign Address, if applicable 

        $tin = '333-333-333-55555';//field: Part 2: Taxpayer Identification Number (TIN)
        $x = 68;
        PDF::SetY(90);
        PDF::SetX(72.5);
        foreach (str_split($tin) as $value) {
            if ($value != '-') {
                PDF::MultiCell(5, 0, $value, 0, 'C',0,0);
            } else {
                PDF::MultiCell(3.3, 0, '', 0, 'C',0,0);
            }
        }
        PDF::MultiCell(0, 0, 'Change me (LGU Name)', 0, 'L', 0, 1, 12, 99, true);//field: Payor's Name
        PDF::MultiCell(0, 0, 'Change me', 0, 'L', 0, 1, 12, 109, true);//field: Registered Address
        $zipcode = '3360';//field: 8A Zipcode
        PDF::SetY(109);
        PDF::SetX(190);
        foreach (str_split($zipcode) as $value) {
            PDF::MultiCell(4.6, 0, $value, 0, 'C',0,0);
        }

        // Part III – Details of Monthly Income Payments and Taxes Withheld
        $column_size ='
        <tr>
            <td width="154px"></td>
            <td width="42px"></td>
            <td width="72px"></td>
            <td width="74px"></td>
            <td width="72px"></td>
            <td width="72px"></td>
            <td></td>
        </tr>
        ';
        //field: Income Payments Subject to Expanded Withholding Tax
        $income = [1,2,3];
        $table = '
        <table style="padding-bottom:3.5px">
            '.$column_size;
            foreach ($income as $value) {
                $table .= '
                <tr>
                    <td>Income</td>
                    <td>WC680</td>
                    <td>10,000.00</td>
                    <td>10,000.00</td>
                    <td>10,000.00</td>
                    <td>10,000.00</td>
                    <td>10,000.00</td>
                </tr>';
            }
        $table .= '</table>';
        PDF::MultiCell(0, 0, $table, 0, 'L', 0, 1, 7, 125, true, 0, true);
        //field: total
        $table = '
        <table style="padding-bottom:3.5px">
            '.$column_size.'
            <tr>
                <td></td>
                <td>WC680</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
            </tr>
        </table>
        ';
        PDF::MultiCell(0, 0, $table, 0, 'L', 0, 1, 7, 172, true, 0, true);

        //field: Money Payments Subject to Withholding of Business Tax (Government & Private)
        $payments = [1,2,3];
        $table = '
        <table style="padding-bottom:3.5px">
            '.$column_size;
            foreach ($payments as $value) {
                $table .= '
                <tr>
                    <td>payment</td>
                    <td>WC680</td>
                    <td>10,000.00</td>
                    <td>10,000.00</td>
                    <td>10,000.00</td>
                    <td>10,000.00</td>
                    <td>10,000.00</td>
                </tr>';
            }
        $table .= '</table>';
        PDF::MultiCell(0, 0, $table, 0, 'L', 0, 1, 7, 184, true, 0, true);
        //field: total
        $table = '
        <table style="padding-bottom:3.5px">
            '.$column_size.'
            <tr>
                <td></td>
                <td>WC680</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
            </tr>
        </table>
        ';
        PDF::MultiCell(0, 0, $table, 0, 'L', 0, 1, 7, 232, true, 0, true);

        PDF::MultiCell(0, 0, 'Change Me (LGU Name)', 0, 'C', 0, 1, 0, 259, true, 0, true); //field:  Printed Name of Payor
        PDF::MultiCell(0, 0, 'Change Me', 0, 'L', 0, 1, 48, 272, true, 0, true); //field:  Tax Agent Accreditation No./ Attorney’s Roll No.
        $date = '11172023';//field: Date of Issue
        PDF::SetY(272);
        PDF::SetX(111);
        foreach (str_split($date) as $value) {
            PDF::MultiCell(4.6, 0, $value, 0, 'C',0,0);
        }
        $date = '11172023';//field: Date of Expiry
        PDF::SetY(272);
        PDF::SetX(171);
        foreach (str_split($date) as $value) {
            PDF::MultiCell(4.6, 0, $value, 0, 'C',0,0);
        }

        PDF::MultiCell(0, 0, $data->paye_name, 0, 'C', 0, 1, 0, 287, true, 0, true); //field:  Printed Name of Payee
        PDF::MultiCell(0, 0, 'Change Me', 0, 'L', 0, 1, 48, 299, true, 0, true); //field:  Tax Agent Accreditation No./ Attorney’s Roll No.
        $date = '11172023';//field: Date of Issue
        PDF::SetY(299);
        PDF::SetX(111);
        foreach (str_split($date) as $value) {
            PDF::MultiCell(4.6, 0, $value, 0, 'C',0,0);
        }
        $date = '11172023';//field: Date of Expiry
        PDF::SetY(299);
        PDF::SetX(171);
        foreach (str_split($date) as $value) {
            PDF::MultiCell(4.6, 0, $value, 0, 'C',0,0);
        }

        PDF::AddPage();
        PDF::Image('./assets/images/forms/bir2307-2.jpg',0, 0, $width, 0, '', '', '', false, 300, '', false, false, 0);

        PDF::Output('BIR_2307_.pdf');
    }
}
