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
use App\Interfaces\GsoPreRepairInspectionInterface;
use App\Interfaces\AcctgFixedAssetInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class GsoRepairManageController extends Controller
{
    private GsoPreRepairInspectionInterface $gsoPreRepairInspectionRepository;
    private AcctgFixedAssetInterface $acctgFixedAssetRepository;
    private $carbon;
    private $slugs;

    public function __construct(
        GsoPreRepairInspectionInterface $gsoPreRepairInspectionRepository, 
        AcctgFixedAssetInterface $acctgFixedAssetRepository,
        Carbon $carbon
    ) {
        date_default_timezone_set('Asia/Manila');
        $this->gsoPreRepairInspectionRepository = $gsoPreRepairInspectionRepository;
        $this->acctgFixedAssetRepository = $acctgFixedAssetRepository;
        $this->carbon = $carbon;
        $this->slugs = 'general-services/repairs-and-inspections/manage';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $permission = (object) array(
            'create' => $this->is_permitted($this->slugs, 'create', 1),
            'read' => $this->is_permitted($this->slugs, 'read', 1),
            'update' => $this->is_permitted($this->slugs, 'update', 1),
            'delete' => $this->is_permitted($this->slugs, 'delete', 1),
            'approve' => $this->is_permitted($this->slugs, 'approve', 1),
            'disapprove' => $this->is_permitted($this->slugs, 'disapprove', 1),
            'download' => $this->is_permitted($this->slugs, 'download', 1)
        );
        $fixed_assets = $this->gsoPreRepairInspectionRepository->allFixedAssets();
        $employees = $this->gsoPreRepairInspectionRepository->allEmployees();
        return view('general-services.repairs-and-inspections.manage.index')->with(compact('permission', 'fixed_assets', 'employees'));
    }
    
    public function lists(Request $request) 
    { 
        $statusClass = [
            'draft' => (object) ['bg' => 'draft-bg', 'status' => 'draft'],
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'for approval'],
            'requested' => (object) ['bg' => 'requested-bg', 'status' => 'requested'],
            'for inspection approval' => (object) ['bg' => 'completed-bg', 'status' => 'in-progress'],
            'completed' => (object) ['bg' => 'completed-bg', 'status' => 'completed'],
            'cancelled' => (object) ['bg' => 'cancelled-bg', 'status' => 'cancelled'],
        ];
        $actions = ''; $actions2 = '';
        if ($this->is_permitted($this->slugs, 'read', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn me-05 btn-sm align-items-center" title="edit this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-pencil text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'read', 1) > 0) {
            $actions2 .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn me-05 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
        }
        $result = $this->gsoPreRepairInspectionRepository->listItems($request);
        $res = $result->data->map(function($repair) use ($statusClass, $actions, $actions2) {
            $requested_by = $repair->employee ? wordwrap($repair->employee->fullname, 25, "\n") : ''; 
            $issues = $repair->issues ?  wordwrap($repair->issues, 25, "\n") : ''; 
            return [
                'id' => $repair->id,
                'repair_no' => $repair->repair_no,
                'repair_no_label' => '<strong class="text-primary">' . $repair->repair_no . '</strong>',
                'fa_no' => $repair->property ? $repair->property->fixed_asset_no : '',
                'fa_no_label' => '<strong>' . ($repair->property ? $repair->property->fixed_asset_no : '') . '</strong>',
                'requested_by' => '<div class="showLess" title="' . ($repair->employee ? $repair->employee->fullname : '') . '">' . $requested_by . '</div>',
                'requested_date' => date('d-M-Y', strtotime($repair->requested_date)),
                'issues' => '<div class="showLess" title="' . ($repair->issues ? $repair->issues : '') . '">' . $issues . '</div>',
                'modified' => ($repair->updated_at !== NULL) ? 
                '<strong>'.$repair->modified->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($repair->updated_at)) : 
                '<strong>'.$repair->inserted->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($repair->created_at)),
                'status' => $statusClass[$repair->status]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$repair->status]->bg. ' p-2">' . $statusClass[$repair->status]->status . '</span>' ,
                'actions' => ($repair->status == 'draft') ? $actions : $actions2
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function history_lists(Request $request, $id) 
    { 
        $statusClass = [
            'draft' => (object) ['bg' => 'draft-bg', 'status' => 'draft'],
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'pending'],
            'requested' => (object) ['bg' => 'requested-bg', 'status' => 'requested'],
            'cancelled' => (object) ['bg' => 'cancelled-bg', 'status' => 'cancelled'],
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn me-05 btn-sm align-items-center" title="edit this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-pencil text-white"></i></a>';
        }
        $result = $this->gsoPreRepairInspectionRepository->history_listItems($request, $id, $request->get('fixed_asset'));
        $res = $result->data->map(function($history) use ($statusClass, $actions) {
            $concerns = $history->concerns ? wordwrap($history->concerns, 25, "\n") : ''; 
            $remarks = $history->remarks ?  wordwrap($history->remarks, 25, "\n") : ''; 
            return [
                'id' => $history->id,
                'date_requested' => date('d-M-Y', strtotime($history->requested_date)),
                'concerns' => '<div class="showLess" title="' . ($history->concerns ? $history->concerns : '') . '">' . $concerns . '</div>',
                'date_accomplished' => date('d-M-Y', strtotime($history->completion_date)),
                'remarks' => '<div class="showLess" title="' . ($history->remarks ? $history->remarks : '') . '">' . $remarks . '</div>',
                'modified' => ($history->updated_at !== NULL) ? date('d-M-Y', strtotime($history->updated_at)).'<br/>'. date('h:i A', strtotime($history->updated_at)) : date('d-M-Y', strtotime($history->created_at)).'<br/>'. date('h:i A', strtotime($history->created_at)),
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function preload_fixed_asset($id)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->acctgFixedAssetRepository->get($id)->map(function($fixedAsset) {
                return (object) [
                    'requested_by' => $fixedAsset->received_by,
                    'model' => $fixedAsset->model,
                    'engine_no' => $fixedAsset->engine_no,
                    'plate_no' => $fixedAsset->plate_no,
                    'received_date' => $fixedAsset->received_date,
                    'unit_cost' => $fixedAsset->unit_cost,
                    'type' => $fixedAsset->type->code.' - '.$fixedAsset->type->name
                ];
            })
        ]);
    }

    public function find(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->gsoPreRepairInspectionRepository->find($id)
        ]);
    }

    public function fetch_status(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->gsoPreRepairInspectionRepository->find($id)->status
        ]);
    }

    public function update(Request $request, $requestID)
    {
        if ($requestID <= 0) {
            $this->is_permitted($this->slugs, 'create'); 
            $details = array(
                'property_id' => $request->property_id,
                'requested_by' => $request->requested_by ? $request->requested_by : NULL,
                'requested_date' => $request->requested_date ? $request->requested_date : NULL,
                'issues' => $request->issues ? $request->issues : NULL,
                'repair_no' => $this->gsoPreRepairInspectionRepository->generate(),
                'created_at' => $this->carbon::now(),
                'created_by' => Auth::user()->id
            );
            $inspection = $this->gsoPreRepairInspectionRepository->create($details);
            $requestID = $inspection->id;
        } else {
            $this->is_permitted($this->slugs, 'update'); 
            $details = array(
                'property_id' => $request->property_id,
                'requested_by' => $request->requested_by ? $request->requested_by : NULL,
                'requested_date' => $request->requested_date ? $request->requested_date : NULL,
                'issues' => $request->issues ? $request->issues : NULL,
                'updated_at' => $this->carbon::now(),
                'updated_by' => Auth::user()->id
            );
            $this->gsoPreRepairInspectionRepository->update($requestID, $details);
        }
        return response()->json([
            'data' => $this->gsoPreRepairInspectionRepository->find($requestID),
            'title' => 'Well done!',
            'text' => 'The request has been sucessfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function send(Request $request, $status, $requestID)
    {   
        $this->is_permitted($this->slugs, 'update'); 
        $timestamp = $this->carbon::now();
        $res = $this->gsoPreRepairInspectionRepository->find($requestID);
        if ($status == 'for-approval' && $res->status == 'draft') {
            $details = array(
                'status' => str_replace('-', ' ', $status),
                'sent_at' => $timestamp,
                'sent_by' => Auth::user()->id
            );
            return response()->json([
                'data' => $this->gsoPreRepairInspectionRepository->update($requestID, $details),
                'text' => 'The request has been successfully sent.',
                'type' => 'success',
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'res' => $res->status,
                'stats' => $status,
                'status' => 'failed',
                'text' => 'Technical error.',
            ]);
        }
    }
}
