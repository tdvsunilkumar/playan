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
use App\Interfaces\GsoIssuanceApproverInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class GsoIssuanceApproverController extends Controller
{
    private GsoIssuanceApproverInterface $gsoIssuanceApproverRepository;
    private $carbon;

    public function __construct(GsoIssuanceApproverInterface $gsoIssuanceApproverRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->gsoIssuanceApproverRepository = $gsoIssuanceApproverRepository;
        $this->carbon = $carbon;
    }

    public function validated($permission) 
    {
      
            return redirect()->back()->with('error', __('Permission denied.'));
       
    }

    public function index(Request $request)
    {   
        // $this->validated('manage admin gso group menu');
        $departments = $this->gsoIssuanceApproverRepository->allDepartments();
        $divisions = ['' => 'select a division'];
        $employees = $this->gsoIssuanceApproverRepository->allEmployees();
        $designations = $this->gsoIssuanceApproverRepository->allDesignations();
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
        return view('general-services.issuance.approver.index')->with(compact('issue_month','issue_year','departments','employees','designations','divisions','issue_type'));
    }
    
    public function lists(Request $request) 
    { 
        $statusClass = [
            0 => (object) ['bg' => 'bg-secondary', 'status' => 'Inactive'],
            1 => (object) ['bg' => 'bg-info', 'status' => 'Active'],
        ];
        $result = $this->gsoIssuanceApproverRepository->listItems($request);
        $res = $result->data->map(function($issuDetails) use ($statusClass) {
            $actions = '<button type="button" class="btn btn-success btn-sm ction-btn edit-btn bg-warning btn m-1 btn-sm align-items-center">View</button>';
            $orderAction = '<button type="button" class="btn btn-secondary btn-sm ction-btn delete-btn bg-danger btn m-1 btn-sm  align-items-center">Issue</button>';
            return [
                'control_no' => $issuDetails->control_no,
                'gi_id' =>  $issuDetails->gi_id,
                'issuance_date' => date('d-M-Y', strtotime($issuDetails->issuance_date)),
                'item_name' => "",
                'item_desc' => "",
                'unit_code' => "",
                'inv_qty' => "",
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

        // $rows = $this->gsoIssuanceApproverRepository->validate(strtolower(str_replace(' ', '-', $request->name)));
        // if ($rows > 0) {
        //     return response()->json([
        //         'title' => 'Oh snap!',
        //         'text' => 'You cannot create a group menu with an existing code.',
        //         'type' => 'error',
        //         'class' => 'btn-danger'
        //     ]);
        // }

        // $countOrder = floatval(floatval($this->gsoIssuanceApproverRepository->count()) + floatval(1));
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
            'issue_requestor_position' =>  $request->designation_id,
            'issue_remarks' =>  $request->issue_remarks,
            'issue_requestor_date' =>  $request->issue_requestor_date,
            'created_at' => $this->carbon::now(),
            'issue_registered_by' => Auth::user()->id
        );
        $data= $this->gsoIssuanceApproverRepository->create($details);
        $selected_items= $request->selected_items;
        foreach($selected_items as $item_id)
        {
            $gso_item=$this->gsoIssuanceApproverRepository->findGsoItem($item_id);
            $last_issu_item_det=$this->gsoIssuanceApproverRepository->lastIssueItemDet();
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
            $data_issu_det= $this->gsoIssuanceApproverRepository->createIssuanceDetails($ins_detail_data);

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
            'data' => $this->gsoIssuanceApproverRepository->find($id)
        ]);
    }

    public function update(Request $request, $id): JsonResponse 
    {   
        // $this->validated('edit admin gso group menu');

        // $rows = $this->gsoIssuanceApproverRepository->validate(strtolower(str_replace(' ', '-', $request->name)), $id);
        // if ($rows > 0) {
        //     return response()->json([
        //         'title' => 'Oh snap!',
        //         'text' => 'You cannot update a group menu with an existing code.',
        //         'type' => 'error',
        //         'class' => 'btn-danger'
        //     ]);
        // }

        $details = array(
            'issue_approver' => $request->employee_id,
            'issue_approver_position' => $request->designation_id,
            'issue_approver_date' => $request->issue_approver_date,
            'updated_at' => $this->carbon::now(),
            'issue_modified_by' => Auth::user()->id
        );

        return response()->json([
            'data' => $this->gsoIssuanceApproverRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The group menu has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
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
            'data' => $this->gsoIssuanceApproverRepository->update($id, $details),
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
            'data' => $this->gsoIssuanceApproverRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The group menu has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function order(Request $request, $order, $id)
    {   
        $group_menu = $this->gsoIssuanceApproverRepository->find($id);
        $last_count = $this->gsoIssuanceApproverRepository->count();

        if ($order == 'up') {
            if ($group_menu->order > 1) {
                $prevOrder = floatval($group_menu->order) - floatval(1);    
                $prev_menu = $this->gsoIssuanceApproverRepository->findBy('order', $prevOrder);
                $details = array(
                    'order' => floatval($prev_menu->order) + floatval(1),
                    'updated_at' => $this->carbon::now(),
                    'updated_by' => Auth::user()->id,
                    'is_active' => 1
                );
                $this->gsoIssuanceApproverRepository->update($prev_menu->id, $details);
                $details = array(
                    'order' => $prevOrder,
                    'updated_at' => $this->carbon::now(),
                    'updated_by' => Auth::user()->id,
                    'is_active' => 1
                );
                $this->gsoIssuanceApproverRepository->update($id, $details);
            }
        } else {
            if ($group_menu->order < $last_count) {
                $prevOrder = floatval($group_menu->order) + floatval(1);    
                $prev_menu = $this->gsoIssuanceApproverRepository->findBy('order', $prevOrder);
                $details = array(
                    'order' => floatval($prev_menu->order) - floatval(1),
                    'updated_at' => $this->carbon::now(),
                    'updated_by' => Auth::user()->id,
                    'is_active' => 1
                );
                $this->gsoIssuanceApproverRepository->update($prev_menu->id, $details);
                $details = array(
                    'order' => $prevOrder,
                    'updated_at' => $this->carbon::now(),
                    'updated_by' => Auth::user()->id,
                    'is_active' => 1
                );
                $this->gsoIssuanceApproverRepository->update($id, $details);
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
            'employees' => $this->gsoIssuanceApproverRepository->reload_employees($department),
            'divisions' => $this->gsoIssuanceApproverRepository->reload_divisions($department)
        ]);
    }

    public function reload_designation(Request $request, $employee) 
    {   
        // $this->validated('edit acctg account group submajor');
        return response()->json([
            'data' => $this->gsoIssuanceApproverRepository->reload_designation($employee)
        ]);
    }

    public function item_lists(Request $request,$id) 
    { 
        $statusClass = [
            0 => (object) ['bg' => 'bg-secondary', 'status' => 'Inactive'],
            1 => (object) ['bg' => 'bg-info', 'status' => 'Active'],
        ];
        $result = $this->gsoIssuanceApproverRepository->newlistItems($request,$id);
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
                'inv_qty' => $issuDetails->inv_qty
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
