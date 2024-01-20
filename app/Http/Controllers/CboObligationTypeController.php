<?php

namespace App\Http\Controllers;
use App\Models\CboObligationType;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\CboObligationTypeInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class CboObligationTypeController extends Controller
{
    private CboObligationTypeInterface $cboObligationTypeRepository;
    private $carbon;
    private $slugs;

    public function __construct(CboObligationTypeInterface $cboObligationTypeRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->cboObligationTypeRepository = $cboObligationTypeRepository;
        $this->carbon = $carbon;
        $this->slugs = 'finance/setup-data/obligation-types';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $fund_codes = $this->cboObligationTypeRepository->allFundCodes();
        $gl_accounts = $this->cboObligationTypeRepository->allGLAccounts();
        return view('finance.setup-data.obligation-types.index')->with(compact('fund_codes', 'gl_accounts'));
    }
    
    public function lists(Request $request) 
    { 
        $statusClass = [
            0 => (object) ['bg' => 'bg-secondary', 'status' => 'Inactive'],
            1 => (object) ['bg' => 'bg-info', 'status' => 'Active'],
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn me-05 btn-sm align-items-center" title="edit this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-pencil text-white"></i></a>';
        }
        $canDelete = $this->is_permitted($this->slugs, 'delete', 1);
        $result = $this->cboObligationTypeRepository->listItems($request);
        $res = $result->data->map(function($obligationType) use ($statusClass, $actions, $canDelete) {
            $fund = $obligationType->fund ? wordwrap($obligationType->fund->code.' - '.$obligationType->fund->description, 25, "\n") : '';  
            $gl_account = $obligationType->gl_account ? wordwrap($obligationType->gl_account->code.' - '.$obligationType->gl_account->description, 25, "\n") : '';  
            $description = wordwrap($obligationType->identityDescription, 25, "\n");  
            if ($canDelete > 0) {
                $actions .= ($obligationType->identityStatus > 0) ? '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm align-items-center" title="remove this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-trash text-white"></i></a>' : '<a href="javascript:;" class="action-btn restore-btn bg-info btn m-1 btn-sm align-items-center" title="restore this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-reload text-white"></i></a>';
            }
            return [
                'id' => $obligationType->identity,
                'fund' => '<div class="showLess" title="' . ($obligationType->fund ? $obligationType->fund->code . ' - ' . $obligationType->fund->description : '') . '">' . $fund . '</div>',
                'gl_account' => '<div class="showLess" title="' . ($obligationType->gl_account ? $obligationType->gl_account->code . ' - ' . $obligationType->gl_account->description : '') . '">' . $gl_account . '</div>',
                'code' => $obligationType->identityCode,
                'name' => $obligationType->identityName,
                'description' => '<div class="showLess" title="' . $obligationType->identityDescription . '">' . $description . '</div>',
                'modified' => ($obligationType->identityUpdatedAt !== NULL) ? date('d-M-Y', strtotime($obligationType->identityUpdatedAt)).'<br/>'. date('h:i A', strtotime($obligationType->identityUpdatedAt)) : date('d-M-Y', strtotime($obligationType->identityCreatedAt)).'<br/>'. date('h:i A', strtotime($obligationType->identityCreatedAt)),
                'status' => $statusClass[$obligationType->identityStatus]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$obligationType->identityStatus]->bg. ' p-2">' . $statusClass[$obligationType->identityStatus]->status . '</span>' ,
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
    
    public function store(Request $request): JsonResponse 
    {       
        $this->is_permitted($this->slugs, 'create');  
        $rows = $this->cboObligationTypeRepository->validate($request->code);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot create an obligation type with an existing code.',
                'label' => 'This is an existing code.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => 'code',
            ]);
        }

        $timestamp = $this->carbon::now();
        $details = array(
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
            'fund_code_id' => $request->fund_code_id,
            'gl_account_id' => $request->gl_account_id,
            'created_at' => $timestamp,
            'created_by' => Auth::user()->id
        );
        $obligation_type = $this->cboObligationTypeRepository->create($details);

        $this->insertLogs([
            'logs' => 'has created an obligation type.',
            'details' => CboObligationType::find($obligation_type->id),
            'entity' => 'cbo_obligation_types',
            'entity_id' => $obligation_type->id,
            'created_at' => $timestamp,
            'created_by' => Auth::user()->id
        ]);

        return response()->json(
            [
                'data' => $obligation_type,
                'title' => 'Well done!',
                'text' => 'The obligation type has been successfully saved.',
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
            'data' => $this->cboObligationTypeRepository->find($id)
        ]);
    }

    public function update(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update'); 
        $rows = $this->cboObligationTypeRepository->validate($request->code, $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot update an obligation type with an existing code.',
                'label' => 'This is an existing code.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => 'code'
            ]);
        }

        $timestamp = $this->carbon::now();
        $details = array(
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
            'fund_code_id' => $request->fund_code_id,
            'gl_account_id' => $request->gl_account_id,
            'updated_at' => $timestamp,
            'updated_by' => Auth::user()->id
        );
        $this->cboObligationTypeRepository->update($id, $details);

        $this->insertLogs([
            'logs' => 'has modified an obligation type.',
            'details' => CboObligationType::find($id),
            'entity' => 'cbo_obligation_types',
            'entity_id' => $id,
            'created_at' => $timestamp,
            'created_by' => Auth::user()->id
        ]);

        return response()->json([
            'title' => 'Well done!',
            'text' => 'The obligation type has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function remove(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'delete'); 
        $timestamp = $this->carbon::now();
        $details = array(
            'updated_at' => $timestamp,
            'updated_by' => Auth::user()->id,
            'is_active' => 0
        );
        $this->cboObligationTypeRepository->update($id, $details);

        $this->insertLogs([
            'logs' => 'has removed an obligation type.',
            'details' => CboObligationType::find($id),
            'entity' => 'cbo_obligation_types',
            'entity_id' => $id,
            'created_at' => $timestamp,
            'created_by' => Auth::user()->id
        ]);

        return response()->json([
            'title' => 'Well done!',
            'text' => 'The obligation type has been successfully removed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function restore(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'delete'); 
        $timestamp = $this->carbon::now();
        $details = array(
            'updated_at' => $timestamp,
            'updated_by' => Auth::user()->id,
            'is_active' => 1
        );
        $this->cboObligationTypeRepository->update($id, $details);

        $this->insertLogs([
            'logs' => 'has restored an obligation type.',
            'details' => CboObligationType::find($id),
            'entity' => 'cbo_obligation_types',
            'entity_id' => $id,
            'created_at' => $timestamp,
            'created_by' => Auth::user()->id
        ]);

        return response()->json([
            'title' => 'Well done!',
            'text' => 'The obligation type has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }
}
