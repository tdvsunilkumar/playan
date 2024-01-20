<?php

namespace App\Http\Controllers;
use App\Models\BacPocurementMode;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\BacProcurementModeInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AdminBacProcurementModeController extends Controller
{
    private BacProcurementModeInterface $bacProcurementModeRepository;
    private $carbon;
    private $slugs;

    public function __construct(BacProcurementModeInterface $bacProcurementModeRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->bacProcurementModeRepository = $bacProcurementModeRepository;
        $this->carbon = $carbon;
        $this->slugs = 'administrative/bac/procurement-modes';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $permissions = explode(',', $this->load_privileges($this->slugs));
        return view('administrative.bac.procurement-modes.index')->with(compact('permissions'));
    }
    
    public function lists(Request $request)
    {      
        $statusClass = [
            0 => (object) ['bg' => 'bg-secondary', 'status' => 'Inactive'],
            1 => (object) ['bg' => 'bg-info', 'status' => 'Active'],
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="edit this"><i class="ti-pencil text-white"></i></a>';
        }
        $canDelete = $this->is_permitted($this->slugs, 'delete', 1);
        $result = $this->bacProcurementModeRepository->listItems($request);
        $res = $result->data->map(function($proc) use ($actions, $statusClass, $canDelete) {
            $description = wordwrap($proc->description, 25, "\n");
            $remarks     = wordwrap($proc->remarks, 25, "\n");
            if ($canDelete > 0) {
                $actions .= ($proc->is_active > 0) ? '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="remove this "><i class="ti-trash text-white"></i></a>' : '<a href="javascript:;" class="action-btn restore-btn bg-info btn m-1 btn-sm align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="restore this"><i class="ti-reload text-white"></i></a>';
            }
            return [
                'id' => $proc->id,
                'code' => $proc->code,
                'description' => '<div class="showLess" title="' . $proc->description . '">' . $description . '</div>',
                'minimum' => $proc->minimum_amount,
                'maximum' => $proc->maximum_amount,
                'remarks' => '<div class="showLess" title="' . $proc->remarks . '">' . $remarks . '</div>',
                'modified' => ($proc->updated_at !== NULL) ? date('d-M-Y', strtotime($proc->updated_at)).'<br/>'. date('h:i A', strtotime($proc->updated_at)) : date('d-M-Y', strtotime($proc->created_at)).'<br/>'. date('h:i A', strtotime($proc->created_at)),
                'status' => $statusClass[$proc->is_active]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$proc->is_active]->bg. ' p-2">' . $statusClass[$proc->is_active]->status . '</span>' ,
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
        $rows = $this->bacProcurementModeRepository->validate($request->code);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot create a procurement mode with an existing code.',
                'label' => 'This is an existing code.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => 'code',
            ]);
        }

        $details = array(
            'code' => $request->code,
            'description' => $request->description,
            'minimum_amount' => $request->minimum_amount,
            'maximum_amount' => $request->maximum_amount,
            'remarks' => $request->remarks,
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        );
        $procurement_mode = $this->bacProcurementModeRepository->create($details);

        return response()->json(
            [
                'data' => $procurement_mode,
                'title' => 'Well done!',
                'text' => 'The procurement mode has been successfully saved.',
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
            'data' => $this->bacProcurementModeRepository->find($id)
        ]);
    }

    public function update(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update'); 
        $rows = $this->bacProcurementModeRepository->validate($request->code, $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot update a procurement mode with an existing code.',
                'label' => 'This is an existing code.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => 'code'
            ]);
        }

        $details = array(
            'code' => $request->code,
            'description' => $request->description,
            'minimum_amount' => $request->minimum_amount,
            'maximum_amount' => $request->maximum_amount,
            'remarks' => $request->remarks,
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );

        return response()->json([
            'data' => $this->bacProcurementModeRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The procurement mode has been successfully updated.',
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
            'data' => $this->bacProcurementModeRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The procurement mode has been successfully removed.',
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
            'data' => $this->bacProcurementModeRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The procurement mode has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }
}
