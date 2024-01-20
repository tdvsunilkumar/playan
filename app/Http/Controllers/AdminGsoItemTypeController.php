<?php

namespace App\Http\Controllers;
use App\Models\GsoItemType;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\GsoItemTypeRepositoryInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AdminGsoItemTypeController extends Controller
{
    private GsoItemTypeRepositoryInterface $gsoItemTypeRepository;
    private $carbon;
    private $slugs;

    public function __construct(GsoItemTypeRepositoryInterface $gsoItemTypeRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->gsoItemTypeRepository = $gsoItemTypeRepository;
        $this->carbon = $carbon;
        $this->slugs = 'administrative/general-services/item-types';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $can_create = $this->is_permitted($this->slugs, 'create', 1);
        return view('administrative.general-services.item-types.index')->with(compact('can_create'));
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
        $result = $this->gsoItemTypeRepository->listItems($request);
        $res = $result->data->map(function($itemType) use ($statusClass, $actions, $canDelete) {
            $description = wordwrap($itemType->description, 25, "<br />\n"); 
            $remarks = wordwrap($itemType->remarks, 25, "<br />\n");            
            if ($canDelete > 0) {
                $actions .= ($itemType->is_active > 0) ? '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-trash text-white"></i></a>' : '<a href="javascript:;" class="action-btn restore-btn bg-info btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-reload text-white"></i></a>';
            }
            return [
                'id' => $itemType->id,
                'code' => $itemType->code,
                'description' => '<div class="showLess" title="' . $itemType->description . '">' . $description . '</div>',
                'remarks' => '<div class="showLess" title="' . $itemType->remarks . '">' . $remarks . '</div>',
                'modified' => ($itemType->updated_at !== NULL) ? date('d-M-Y', strtotime($itemType->updated_at)).'<br/>'. date('h:i A', strtotime($itemType->updated_at)) : date('d-M-Y', strtotime($itemType->created_at)).'<br/>'. date('h:i A', strtotime($itemType->created_at)),
                'status' => $statusClass[$itemType->is_active]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$itemType->is_active]->bg. ' p-2">' . $statusClass[$itemType->is_active]->status . '</span>' ,
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
        $rows = $this->gsoItemTypeRepository->validate($request->code);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot create an item type with an existing code.',
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

        return response()->json(
            [
                'data' => $this->gsoItemTypeRepository->create($details),
                'title' => 'Well done!',
                'text' => 'The item type has been successfully saved.',
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
            'data' => $this->gsoItemTypeRepository->find($id)
        ]);
    }

    public function update(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update');
        $rows = $this->gsoItemTypeRepository->validate($request->code, $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot update an item type with an existing code.',
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

        return response()->json([
            'data' => $this->gsoItemTypeRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The item type has been successfully updated.',
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

        return response()->json([
            'data' => $this->gsoItemTypeRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The item type has been successfully removed.',
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

        return response()->json([
            'data' => $this->gsoItemTypeRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The item type has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }
}
