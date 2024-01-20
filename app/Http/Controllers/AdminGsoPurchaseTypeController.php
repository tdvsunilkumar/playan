<?php

namespace App\Http\Controllers;
use App\Models\GsoPurchaseType;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\GsoPurchaseTypeRepositoryInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AdminGsoPurchaseTypeController extends Controller
{
    private GsoPurchaseTypeRepositoryInterface $gsoPurchaseTypeRepository;
    private $carbon;
    private $slugs;

    public function __construct(GsoPurchaseTypeRepositoryInterface $gsoPurchaseTypeRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->gsoPurchaseTypeRepository = $gsoPurchaseTypeRepository;
        $this->carbon = $carbon;
        $this->slugs = 'administrative/general-services/purchase-types';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $can_create = $this->is_permitted($this->slugs, 'create', 1);
        return view('administrative.general-services.purchase-types.index')->with(compact('can_create'));
    }

    public function lists(Request $request) 
    { 
        $statusClass = [
            0 => (object) ['bg' => 'bg-secondary', 'status' => 'Inactive'],
            1 => (object) ['bg' => 'bg-info', 'status' => 'Active'],
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn me-05 btn-sm align-items-center" title="Edit"><i class="ti-pencil text-white"></i></a>';
        }
        $canDelete = $this->is_permitted($this->slugs, 'delete', 1);
        $result = $this->gsoPurchaseTypeRepository->listItems($request);
        $res = $result->data->map(function($purType) use ($statusClass, $actions, $canDelete) {
            $description = wordwrap($purType->description, 25, "<br />\n"); 
            $remarks = wordwrap($purType->remarks, 25, "<br />\n");            
            if ($canDelete > 0) {
                $actions .= ($purType->is_active > 0) ? '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-trash text-white"></i></a>' : '<a href="javascript:;" class="action-btn restore-btn bg-info btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-reload text-white"></i></a>';
            }
            return [
                'id' => $purType->id,
                'code' => $purType->code,
                'description' => '<div class="showLess" title="' . $purType->description . '">' . $description . '</div>',
                'remarks' => '<div class="showLess" title="' . $purType->remarks . '">' . $remarks . '</div>',
                'modified' => ($purType->updated_at !== NULL) ? date('d-M-Y', strtotime($purType->updated_at)).'<br/>'. date('h:i A', strtotime($purType->updated_at)) : date('d-M-Y', strtotime($purType->created_at)).'<br/>'. date('h:i A', strtotime($purType->created_at)),
                'status' => $statusClass[$purType->is_active]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$purType->is_active]->bg. ' p-2">' . $statusClass[$purType->is_active]->status . '</span>' ,
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
        $rows = $this->gsoPurchaseTypeRepository->validate($request->code);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot create a purchase type with an existing code.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }

        $details = array(
            'code' => $request->code,
            'description' => $request->description,
            'remarks' => $request->remarks,
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        );
        $res = $this->gsoPurchaseTypeRepository->create($details);

        $this->insertLogs([
            'logs' => 'has created a gso purchase type.',
            'details' => GsoPurchaseType::find($res->id),
            'entity' => 'gso_purchase_types',
            'entity_id' => $res->id,
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        ]);

        return response()->json(
            [
                'data' => $res,
                'title' => 'Well done!',
                'text' => 'The purchase type has been successfully saved.',
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
            'data' => $this->gsoPurchaseTypeRepository->find($id)
        ]);
    }

    public function update(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update');
        $rows = $this->gsoPurchaseTypeRepository->validate($request->code, $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot update a purchase type with an existing code.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }

        $details = array(
            'code' => $request->code,
            'description' => $request->description,
            'remarks' => $request->remarks,
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );
        $res = $this->gsoPurchaseTypeRepository->update($id, $details);

        $this->insertLogs([
            'logs' => 'has modified a gso purchase type.',
            'details' => GsoPurchaseType::find($id),
            'entity' => 'gso_purchase_types',
            'entity_id' => $id,
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        ]);

        return response()->json([
            'data' => $res,
            'title' => 'Well done!',
            'text' => 'The purchase type has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function remove(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'delete');
        $details = array(
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id,
            'is_active' => 0
        );
        $res = $this->gsoPurchaseTypeRepository->update($id, $details);

        $this->insertLogs([
            'logs' => 'has removed a gso purchase type.',
            'details' => GsoPurchaseType::find($id),
            'entity' => 'gso_purchase_types',
            'entity_id' => $id,
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        ]);

        return response()->json([
            'data' => $res,
            'title' => 'Well done!',
            'text' => 'The purchase type has been successfully removed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function restore(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'delete');
        $details = array(
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id,
            'is_active' => 1
        );
        $res = $this->gsoPurchaseTypeRepository->update($id, $details);

        $this->insertLogs([
            'logs' => 'has restored a gso purchase type.',
            'details' => GsoPurchaseType::find($id),
            'entity' => 'gso_purchase_types',
            'entity_id' => $id,
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        ]);

        return response()->json([
            'data' => $res,
            'title' => 'Well done!',
            'text' => 'The purchase type has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }
}
