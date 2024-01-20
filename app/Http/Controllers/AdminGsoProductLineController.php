<?php

namespace App\Http\Controllers;
use App\Models\GsoProductLine;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\GsoProductLineRepositoryInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AdminGsoProductLineController extends Controller
{
    private GsoProductLineRepositoryInterface $gsoProductLineRepository;
    private $carbon;
    private $slugs;

    public function __construct(GsoProductLineRepositoryInterface $gsoProductLineRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->gsoProductLineRepository = $gsoProductLineRepository;
        $this->carbon = $carbon;
        $this->slugs = 'administrative/general-services/product-lines';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $can_create = $this->is_permitted($this->slugs, 'create', 1);
        return view('administrative.general-services.product-lines.index')->with(compact('can_create'));
    }

    public function lists(Request $request) 
    { 
        $statusClass = [
            0 => (object) ['bg' => 'bg-secondary', 'status' => 'Inactive'],
            1 => (object) ['bg' => 'bg-info', 'status' => 'Active'],
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn me-05 btn-sm align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="edit this"><i class="ti-pencil text-white"></i></a>';
        }
        $canDelete = $this->is_permitted($this->slugs, 'delete', 1);
        $result = $this->gsoProductLineRepository->listItems($request);
        $res = $result->data->map(function($prodLine) use ($statusClass, $actions, $canDelete) {
            $description = wordwrap($prodLine->description, 25, "<br />\n"); 
            $remarks = wordwrap($prodLine->remarks, 25, "<br />\n");            
            if ($canDelete > 0) {
                $actions .= ($prodLine->is_active > 0) ? '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="remove this"><i class="ti-trash text-white"></i></a>' : '<a href="javascript:;" class="action-btn restore-btn bg-info btn m-1 btn-sm align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="restore this"><i class="ti-reload text-white"></i></a>';
            }
            return [
                'id' => $prodLine->id,
                'code' => $prodLine->code,
                'description' => '<div class="showLess" title="' . $prodLine->description . '">' . $description . '</div>',
                'remarks' => '<div class="showLess" title="' . $prodLine->remarks . '">' . $remarks . '</div>',
                'modified' => ($prodLine->updated_at !== NULL) ? date('d-M-Y', strtotime($prodLine->updated_at)).'<br/>'. date('h:i A', strtotime($prodLine->updated_at)) : date('d-M-Y', strtotime($prodLine->created_at)).'<br/>'. date('h:i A', strtotime($prodLine->created_at)),
                'status' => $statusClass[$prodLine->is_active]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$prodLine->is_active]->bg. ' p-2">' . $statusClass[$prodLine->is_active]->status . '</span>' ,
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
        $rows = $this->gsoProductLineRepository->validate($request->code);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot create a product line with an existing code.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }

        $details = array(
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'remarks' => $request->remarks,
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        );

        return response()->json(
            [
                'data' => $this->gsoProductLineRepository->create($details),
                'title' => 'Well done!',
                'text' => 'The product line has been successfully saved.',
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
            'data' => $this->gsoProductLineRepository->find($id)
        ]);
    }

    public function update(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update');
        $rows = $this->gsoProductLineRepository->validate($request->code, $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot update a product line with an existing code.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }

        $details = array(
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'remarks' => $request->remarks,
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );

        return response()->json([
            'data' => $this->gsoProductLineRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The product line has been successfully updated.',
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
            'data' => $this->gsoProductLineRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The product line has been successfully removed.',
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
            'data' => $this->gsoProductLineRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The product line has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }
}
