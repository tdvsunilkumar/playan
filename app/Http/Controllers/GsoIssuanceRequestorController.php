<?php

namespace App\Http\Controllers;
use App\Models\MenuGroup;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\GsoIssuanceRequestorInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class GsoIssuanceRequestorController extends Controller
{
    private GsoIssuanceRequestorInterface $gsoIssuanceRequestorRepository;
    private $carbon;

    public function __construct(GsoIssuanceRequestorInterface $gsoIssuanceRequestorRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->gsoIssuanceRequestorRepository = $gsoIssuanceRequestorRepository;
        $this->carbon = $carbon;
    }

    public function validated($permission) 
    {
        
        return redirect()->back()->with('error', __('Permission denied.'));
        
    }

    public function index(Request $request)
    {   
        // $this->validated('manage admin gso group menu');
        $departments = $this->gsoIssuanceRequestorRepository->allDepartments();
        $divisions = ['' => 'select a division'];
        $employees = $this->gsoIssuanceRequestorRepository->allEmployees();
        $designations = $this->gsoIssuanceRequestorRepository->allDesignations();
        $issue_type= ['' => 'select a Issuance Type'];
        $issue_month=[
                    '' => 'Select month',
                    '1' => 'January',
                    '2' => 'February',
                    '3' => 'March',
                    '4' => 'April',
                    '5' => 'May',
                    '6' => 'June',
                    '7' => 'July',
                    '8' => 'August',
                    '9' => 'September',
                    '10' => 'October',
                    '11' => 'November',
                    '12' => 'December',
                    ];
        $issue_year=[
                        '' => 'Select Year',
                        '2015' => '2015',
                        '2016' => '2016',
                        '2017' => '2017',
                        '2018' => '2018',
                        '2019' => '2019',
                        '2020' => '2020',
                        '2021' => '2021',
                        '2022' => '2022',
                        '2023' => '2023',
                        '2024' => '2024',
                        '2025' => '2025',
                        '2026' => '2026',
                        '2027' => '2027',
                        '2028' => '2028',
                        '2029' => '2029',
                        '2030' => '2030',
                        '2031' => '2031',
                        '2032' => '2032',
                        '2033' => '2033',
                        '2034' => '2034',
                        '2035' => '2035',
                        '2036' => '2036',
                        '2037' => '2037',
                        '2038' => '2038',
                        '2039' => '2039',
                        '2040' => '2040',
                        ];
        return view('general-services.issuance.index')->with(compact('issue_month','issue_year','departments','employees','designations','divisions','issue_type'));
    }
    
    public function lists(Request $request) 
    { 
        $result = $this->gsoIssuanceRequestorRepository->listItems($request);
        $res = $result->data->map(function($gsoIssuance) {
            $actions = '<a href="javascript:;" class="action-btn edit-btn bg-warning btn me-05 btn-sm align-items-center" title="View">
                            <i class="ti-eye"></i>
                        </a>
                        <a href="javascript:;" class="action-btn edit-btn bg-danger btn ms-05 btn-sm align-items-center" title="Issue">
                                <i class="ti-pencil"></i>
                        </a>';
            if($gsoIssuance->issue_status == 1){ $status="Pending";}
            if($gsoIssuance->issue_status == 2){ $status="Approved";}
            if($gsoIssuance->issue_status == 3){ $status="Issued";}            
            return [
                'issuance_id' => $gsoIssuance->issuance_id,
                'control_no' => $gsoIssuance->control_no,
                'issuance_date' => date('d-M-Y', strtotime($gsoIssuance->issuance_date)),
                'deptName' => $gsoIssuance->deptName,
                'divName' => $gsoIssuance->divName,
                'issue_status' => $status,
                'actions' => $actions
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function issuanceItemDetails(Request $request) 
    { 
        $statusClass = [
            0 => (object) ['bg' => 'bg-secondary', 'status' => 'Inactive'],
            1 => (object) ['bg' => 'bg-info', 'status' => 'Active'],
        ];
        $result = $this->gsoIssuanceRequestorRepository->issuanceItemDetails($request);
        $res = $result->data->map(function($issuDetails) use ($statusClass) {
            $description = wordwrap($issuDetails->item_desc, 25, "<br />\n");
            $actions = '<button type="button" class="btn btn-success btn-sm ction-btn edit-btn bg-warning btn m-1 btn-sm align-items-center">View</button>';
            $orderAction = '<button type="button" class="btn btn-secondary btn-sm ction-btn delete-btn bg-danger btn m-1 btn-sm  align-items-center">Issue</button>';
            return [
                'control_no' => $issuDetails->control_no,
                'issuance_date' => date('d-M-Y', strtotime($issuDetails->issuance_date)),
                'item_name' => $issuDetails->item_name,
                'item_desc' => '<div class="showLess" title="' . $issuDetails->item_desc . '">' . $description . '</div>',
                'unit_code' => $issuDetails->unit_code,
                'inv_qty' => $issuDetails->inv_qty,
                'actions' => $actions.'<br/>'.$orderAction
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }
    
    public function store(Request $request): JsonResponse 
    {       
        // $this->validated('create admin gso group menu');

        // $rows = $this->gsoIssuanceRequestorRepository->validate(strtolower(str_replace(' ', '-', $request->name)));
        // if ($rows > 0) {
        //     return response()->json([
        //         'title' => 'Oh snap!',
        //         'text' => 'You cannot create a group menu with an existing code.',
        //         'type' => 'error',
        //         'class' => 'btn-danger'
        //     ]);
        // }

        // $countOrder = floatval(floatval($this->gsoIssuanceRequestorRepository->count()) + floatval(1));
        $designation=$this->gsoIssuanceRequestorRepository->reload_designation($request->employee_id);
        $details = array(
            'issue_control_no' => $request->issue_control_no,
            'issue_date' => $request->issue_date,
            'issue_month' => $request->issue_month,
            'issue_year' => $request->issue_year,
            'issue_type' => $request->issue_type,
            'dept_code' =>  $request->department_id,
            'ddiv_code' =>  $request->division_id,
            'dept_idcode' =>  $request->department_id,
            'issue_fund_cluster' =>  $request->issue_fund_cluster,
            'issue_requestor' =>  $request->employee_id,
            'issue_requestor_position' =>  $designation->id,
            'issue_remarks' =>  $request->issue_remarks,
            'issue_requestor_date' =>  $request->issue_requestor_date,
            'created_at' => $this->carbon::now(),
            'issue_registered_by' => Auth::user()->id
        );
        $data= $this->gsoIssuanceRequestorRepository->create($details);
        $selected_items= $request->selected_items;
        foreach($selected_items as $item_id)
        {
            $gso_item=$this->gsoIssuanceRequestorRepository->findGsoItem($item_id);
            $last_issu_item_det=$this->gsoIssuanceRequestorRepository->lastIssueItemDet();
            if(!empty($last_issu_item_det))
            {
                $issue_no=$last_issu_item_det->id + 1;
            }
            else{
                $issue_no=1;
            }
            $ins_detail_data = array(
                'issue_id' => $data->id,
                'item_id' => $item_id,
                'item_type' => $gso_item->type->description,
                'item_code' => $gso_item->category->code."-".$gso_item->code,
                'item_name' =>  $gso_item->name,
                'item_desc' =>  $gso_item->description,
                'unit_code' =>  $gso_item->uom->code,
                'inv_qty' =>  $gso_item->quantity_inventory,
                'inv_unit_cost' =>  $gso_item->latest_cost,
                'issued_qty' =>  $gso_item->quantity_inventory,
                'issued_est_lifespan' =>  $gso_item->life_span,
                'issued_remarks' =>  $request->issue_remarks,
                'issue_type' =>  $request->issue_type,
                'issued_date' =>  $request->issue_date,
                'issued_year' =>  $request->issue_year,
                'issued_no' =>  $issue_no,
                'issued_property_no' =>  $request->issue_year."-".$issue_no,
                'created_at' => $this->carbon::now(),
                'issued_registered_by' => Auth::user()->id
            );
            $data_issu_det= $this->gsoIssuanceRequestorRepository->createIssuanceDetails($ins_detail_data);

        }
        return response()->json(
            [
                'data' => $data,
                'title' => 'Well done!',
                'text' => 'The Issuance Request has been successfully saved.',
                'type' => 'success',
                'class' => 'btn-brand'
            ],
            Response::HTTP_CREATED
        );
    }

    public function find(Request $request, $id): JsonResponse 
    {   
        // $this->validated('edit admin gso group menu');
        return response()->json([
            'data' => $this->gsoIssuanceRequestorRepository->find($id)
        ]);
    }

    public function update(Request $request, $id): JsonResponse 
    {   
        // $this->validated('edit admin gso group menu');

        // $rows = $this->gsoIssuanceRequestorRepository->validate(strtolower(str_replace(' ', '-', $request->name)), $id);
        // if ($rows > 0) {
        //     return response()->json([
        //         'title' => 'Oh snap!',
        //         'text' => 'You cannot update a group menu with an existing code.',
        //         'type' => 'error',
        //         'class' => 'btn-danger'
        //     ]);
        // }
        $designation=$this->gsoIssuanceRequestorRepository->reload_designation(Auth::user()->id);
        $gsoIssuance=$this->gsoIssuanceRequestorRepository->find($id);
        if($gsoIssuance->issue_status == 1)
        {
            $details = array(
                'issue_status' => 2,
                'issue_approver' => Auth::user()->id,
                'issue_approver_position' => $designation->id,
                'issue_approver_date' => $this->carbon::now()->format('Y-m-d'),
                'updated_at' => $this->carbon::now(),
                'issue_modified_by' => Auth::user()->id
            );
            return response()->json([
                'data' => $this->gsoIssuanceRequestorRepository->update($id, $details),
                'title' => 'Well done!',
                'text' => 'The issuance has been successfully Approved.',
                'type' => 'success',
                'class' => 'btn-brand'
            ]);
        }
        elseif($gsoIssuance->issue_status == 2)
        {
            $details = array(
                'issue_status' => 3,
                'issue_personnel' => Auth::user()->id,
                'issue_personnel_position' => $designation->id,
                'issue_personnel_date' => $this->carbon::now()->format('Y-m-d'),
                'updated_at' => $this->carbon::now(),
                'issue_modified_by' => Auth::user()->id
            ); 
            return response()->json([
                'data' => $this->gsoIssuanceRequestorRepository->update($id, $details),
                'title' => 'Well done!',
                'text' => 'The issuance has been successfully Issued.',
                'type' => 'success',
                'class' => 'btn-brand'
            ]);
        }
        else {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'The Issuance is already issued.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }
        
    }

    public function remove(Request $request, $id): JsonResponse 
    {   
        // $this->validated('delete admin gso group menu');
        $details = array(
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id,
            'is_active' => 0
        );

        return response()->json([
            'data' => $this->gsoIssuanceRequestorRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The group menu has been successfully removed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function restore(Request $request, $id): JsonResponse 
    {   
        // $this->validated('delete admin gso group menu');
        $details = array(
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id,
            'is_active' => 1
        );

        return response()->json([
            'data' => $this->gsoIssuanceRequestorRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The group menu has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function order(Request $request, $order, $id)
    {   
        $group_menu = $this->gsoIssuanceRequestorRepository->find($id);
        $last_count = $this->gsoIssuanceRequestorRepository->count();

        if ($order == 'up') {
            if ($group_menu->order > 1) {
                $prevOrder = floatval($group_menu->order) - floatval(1);    
                $prev_menu = $this->gsoIssuanceRequestorRepository->findBy('order', $prevOrder);
                $details = array(
                    'order' => floatval($prev_menu->order) + floatval(1),
                    'updated_at' => $this->carbon::now(),
                    'updated_by' => Auth::user()->id,
                    'is_active' => 1
                );
                $this->gsoIssuanceRequestorRepository->update($prev_menu->id, $details);
                $details = array(
                    'order' => $prevOrder,
                    'updated_at' => $this->carbon::now(),
                    'updated_by' => Auth::user()->id,
                    'is_active' => 1
                );
                $this->gsoIssuanceRequestorRepository->update($id, $details);
            }
        } else {
            if ($group_menu->order < $last_count) {
                $prevOrder = floatval($group_menu->order) + floatval(1);    
                $prev_menu = $this->gsoIssuanceRequestorRepository->findBy('order', $prevOrder);
                $details = array(
                    'order' => floatval($prev_menu->order) - floatval(1),
                    'updated_at' => $this->carbon::now(),
                    'updated_by' => Auth::user()->id,
                    'is_active' => 1
                );
                $this->gsoIssuanceRequestorRepository->update($prev_menu->id, $details);
                $details = array(
                    'order' => $prevOrder,
                    'updated_at' => $this->carbon::now(),
                    'updated_by' => Auth::user()->id,
                    'is_active' => 1
                );
                $this->gsoIssuanceRequestorRepository->update($id, $details);
            }
        }

        return response()->json([
            'title' => 'Well done!',
            'text' => 'The group menu has re-ordered successfully.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function reload_divisions_employees(Request $request, $department) 
    {   
        // $this->validated('edit acctg account group submajor');
        return response()->json([
            'employees' => $this->gsoIssuanceRequestorRepository->reload_employees($department),
            'divisions' => $this->gsoIssuanceRequestorRepository->reload_divisions($department)
        ]);
    }

    public function reload_designation(Request $request, $employee) 
    {   
        // $this->validated('edit acctg account group submajor');
        return response()->json([
            'data' => $this->gsoIssuanceRequestorRepository->reload_designation($employee)
        ]);
    }

    public function item_lists(Request $request) 
    { 
        $statusClass = [
            0 => (object) ['bg' => 'bg-secondary', 'status' => 'Inactive'],
            1 => (object) ['bg' => 'bg-info', 'status' => 'Active'],
        ];
        $result = $this->gsoIssuanceRequestorRepository->newlistItems($request);
        $res = $result->data->map(function($gsoItem) use ($statusClass) {
            $description = wordwrap($gsoItem->itemDesc, 25, "<br />\n");
            $select='<input type="checkbox" name="selected_items['. $gsoItem->itemId .']" value="'. $gsoItem->itemId .'">';
            $actions = '<button type="button" class="btn btn-success btn-sm ction-btn edit-btn bg-warning btn m-1 btn-sm align-items-center">View</button>';
            $orderAction = '<button type="button" class="btn btn-secondary btn-sm ction-btn delete-btn bg-danger btn m-1 btn-sm  align-items-center">Issue</button>';
            return [
                'select' => $select,
                'itemId' => $gsoItem->itemId,
                'catCode' => $gsoItem->catCode,
                'itemName' => $gsoItem->itemName,
                'itemDesc' => '<div class="showLess" title="' . $gsoItem->description . '">' . $description . '</div>',
                'itemUOM' => $gsoItem->itemUOM,
                'itemInventory' => $gsoItem->itemInventory,
                'actions' => $actions.'<br/>'.$orderAction
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }
}
