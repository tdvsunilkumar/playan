<?php

namespace App\Http\Controllers;
use App\Models\AcctgFundCode;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\AcctgFundCodeRepositoryInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AcctgFundCodeController extends Controller
{
    private AcctgFundCodeRepositoryInterface $fundCodeRepository;
    private $carbon;
    private $slugs;

    public function __construct(AcctgFundCodeRepositoryInterface $fundCodeRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->fundCodeRepository = $fundCodeRepository;
        $this->carbon = $carbon;
        $this->slugs = 'accounting/fund-codes';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $can_create = $this->is_permitted($this->slugs, 'create', 1);
        return view('accounting.fund-codes.index')->with(compact('can_create'));
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
        $result = $this->fundCodeRepository->listItems($request);
        $res = $result->data->map(function($fundcode) use ($statusClass, $actions, $canDelete) {
            $description = wordwrap($fundcode->description, 25, "<br />\n"); 
            if ($canDelete > 0) {
                $actions .= ($fundcode->is_active > 0) ? '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-trash text-white"></i></a>' : '<a href="javascript:;" class="action-btn restore-btn bg-info btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-reload text-white"></i></a>';
            }
            return [
                'id' => $fundcode->id,
                'code' => $fundcode->code,
                'description' => '<div class="showLess" title="' . $fundcode->description . '">' . $description . '</div>',
                'modified' => ($fundcode->updated_at !== NULL) ? date('d-M-Y', strtotime($fundcode->updated_at)).'<br/>'. date('h:i A', strtotime($fundcode->updated_at)) : date('d-M-Y', strtotime($fundcode->created_at)).'<br/>'. date('h:i A', strtotime($fundcode->created_at)),
                'status' => $statusClass[$fundcode->is_active]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$fundcode->is_active]->bg. ' p-2">' . $statusClass[$fundcode->is_active]->status . '</span>' ,
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
        $rows = $this->fundCodeRepository->validate($request->code);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot create a fund-code with an existing code.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }

        $details = array(
            'code' => $request->code,
            'description' => $request->description,
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        );

        return response()->json(
            [
                'data' => $this->fundCodeRepository->create($details),
                'title' => 'Well done!',
                'text' => 'The fund-code has been successfully saved.',
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
            'data' => $this->fundCodeRepository->find($id)
        ]);
    }

    public function update(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update');
        $rows = $this->fundCodeRepository->validate($request->code, $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot update a fund-code with an existing code.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }

        $details = array(
            'code' => $request->code,
            'description' => $request->description,
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );

        return response()->json([
            'data' => $this->fundCodeRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The fund-code has been successfully updated.',
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
            'data' => $this->fundCodeRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The fund-code has been successfully removed.',
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
            'data' => $this->fundCodeRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The fund-code has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }
}
