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
use App\Interfaces\GsoItemRepositoryInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class GsoInventoryControllerBackup extends Controller
{
    private GsoItemRepositoryInterface $gsoItemRepositoryInterface;
    private $carbon;

    public function __construct(GsoItemRepositoryInterface $gsoItemRepositoryInterface, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->gsoItemRepositoryInterface = $gsoItemRepositoryInterface;
        $this->carbon = $carbon;
    }

    public function validated($permission) 
    {
        
        return redirect()->back()->with('error', __('Permission denied.'));
        
    }

    public function index(Request $request)
    {   
        // $this->validated('manage acctg account group submajor');
        $filter_type = '';
        $departments = $this->gsoItemRepositoryInterface->allDepartments();
        $divisions = ['' => 'select a division'];
        $employees = $this->gsoItemRepositoryInterface->allEmployees();
        $designations = $this->gsoItemRepositoryInterface->allDesignations();
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
        return view('general-services.inventory.index')->with(compact('filter_type','issue_month','issue_year','departments','employees','designations','divisions','issue_type'));
    }
    public function lists(Request $request) 
    { 
        $statusClass = [
            0 => (object) ['bg' => 'bg-secondary', 'status' => 'Inactive'],
            1 => (object) ['bg' => 'bg-info', 'status' => 'Active'],
        ];
        $result = $this->gsoItemRepositoryInterface->newlistItems($request);
        $res = $result->data->map(function($gsoItem) use ($statusClass) {
            $description = wordwrap($gsoItem->itemDesc, 25, "<br />\n");
            $select='<input type="checkbox" class="select_item" name="selected_items['. $gsoItem->itemId .']" value="'. $gsoItem->itemId .'">';

            $actions = '<a href="javascript:;" class="action-btn edit-btn bg-warning btn me-05 btn-sm align-items-center" title="View">
                            <i class="ti-eye"></i>
                        </a>
                        <a href="javascript:;" class="action-btn issue-btn bg-danger btn ms-05 btn-sm align-items-center" title="Issue">
                                <i class="ti-plus"></i>
                        </a>';
            return [
                'select' => $select,
                'itemId' => $gsoItem->itemId,
                'catCode' => $gsoItem->catCode,
                'itemCode' => $gsoItem->itemCode,
                'itemName' => $gsoItem->itemName,
                'itemDesc' => '<div class="showLess" title="' . $gsoItem->description . '">' . $description . '</div>',
                'itemUOM' => $gsoItem->itemUOM,
                'itemInventory' => $gsoItem->itemInventory,
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
    public function getPageCount($keywords) 
    {
        return $this->gsoItemRepositoryInterface->listCount($keywords);
    }
    public function find(Request $request, $id): JsonResponse 
    {   
        return response()->json([
            'data' => $this->gsoItemRepositoryInterface->showData($id)
        ]);
    }
    public function item_history_lists(Request $request,$id,$filter_type) 
    { 
        $statusClass = [
            0 => (object) ['bg' => 'bg-secondary', 'status' => 'Inactive'],
            1 => (object) ['bg' => 'bg-info', 'status' => 'Active'],
        ];
        $result = $this->gsoItemRepositoryInterface->itemHistoryList($request,$id,$filter_type);
        $res = $result->data->map(function($itemHistory) use ($statusClass) {
            // if ($itemHistory->trans_type == 1) { $trans_type = "Issuance"; }
            // if ($itemHistory->trans_type == 2) { $trans_type = "Acceptance"; }
            // if ($itemHistory->trans_type == 3) { $trans_type = "Return"; }    
        
            return [
                'trans_type' => $itemHistory->trans_type,
                'trans_date' => date('d-M-Y', strtotime($itemHistory->trans_date)),
                'trans_by' => $itemHistory->trans_by,
                'rcv_by' => $itemHistory->rcv_by,
                'based_qty' => $itemHistory->based_qty,
                'posted_qty' => $itemHistory->posted_qty,
                'reserved_qty' => $itemHistory->reserved_qty,
                'balance_qty' => $itemHistory->balance_qty
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function issue_checked_item(Request $request) 
    { 
        $result = $this->gsoItemRepositoryInterface->issue_checked_item($request);
        $res = $result->data->map(function($gsoItem){
            $description = wordwrap($gsoItem->itemDesc, 25, "<br />\n");
            $select='<input type="hidden" name="selected_items['. $gsoItem->itemId .']" class="selected_items" value="'. $gsoItem->itemId .'">';
            return [
                'select' => $select,
                'itemId' => $gsoItem->itemId,
                'accCode' => $gsoItem->accCode,
                'itemType' => $gsoItem->itemType,
                'itemCode' => $gsoItem->itemCode,
                'itemName' => $gsoItem->itemName,
                'itemDesc' => '<div class="showLess" title="' . $gsoItem->description . '">' . $description . '</div>',
                'itemUOM' => $gsoItem->itemUOM,
                'itemInventory' => $gsoItem->itemInventory,
                'estLifeSpan' => $gsoItem->estLifeSpan,
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

        // $rows = $this->gsoItemRepositoryInterface->validate(strtolower(str_replace(' ', '-', $request->name)));
        // if ($rows > 0) {
        //     return response()->json([
        //         'title' => 'Oh snap!',
        //         'text' => 'You cannot create a group menu with an existing code.',
        //         'type' => 'error',
        //         'class' => 'btn-danger'
        //     ]);
        // }

        // $countOrder = floatval(floatval($this->gsoItemRepositoryInterface->count()) + floatval(1));
        $designation=$this->gsoItemRepositoryInterface->reload_designation(Auth::user()->id);
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
            'issue_requestor' =>  Auth::user()->id,
            'issue_requestor_position' =>  $designation->id,
            'issue_remarks' =>  $request->issue_remarks,
            'issue_requestor_date' =>  carbon::now()->format('Y-m-d'),
            'created_at' => $this->carbon::now(),
            'issue_registered_by' => Auth::user()->id
        );
        $data= $this->gsoItemRepositoryInterface->createIssuance($details);
        $selected_items= $request->selected_items;
        foreach($selected_items as $item_id)
        {
            $gso_item=$this->gsoItemRepositoryInterface->findGsoItem($item_id);
            $last_issu_item_det=$this->gsoItemRepositoryInterface->lastIssueItemDet();
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
            $data_issu_det= $this->gsoItemRepositoryInterface->createIssuanceDetails($ins_detail_data);

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

    public function reload_divisions_employees(Request $request, $department) 
    {   
        // $this->validated('edit acctg account group submajor');
        return response()->json([
            'employees' => $this->gsoItemRepositoryInterface->reload_employees($department),
            'divisions' => $this->gsoItemRepositoryInterface->reload_divisions($department)
        ]);
    }

    

}
