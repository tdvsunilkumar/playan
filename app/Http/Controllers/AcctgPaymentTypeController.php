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
use App\Interfaces\AcctgPaymentTypeInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AcctgPaymentTypeController extends Controller
{
    private AcctgPaymentTypeInterface $acctgPaymentTypeRepository;
    private $carbon;
    private $slugs;

    public function __construct(AcctgPaymentTypeInterface $acctgPaymentTypeRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->acctgPaymentTypeRepository = $acctgPaymentTypeRepository;
        $this->carbon = $carbon;
        $this->slugs = 'accounting/setup-data/payment-types';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        return view('accounting.setup-data.payment-types.index');
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
        $result = $this->acctgPaymentTypeRepository->listItems($request);
        $res = $result->data->map(function($evat) use ($statusClass, $actions, $canDelete) {
            $description = wordwrap($evat->description, 25, "<br />\n");            
            if ($canDelete > 0) {
                $actions .= ($evat->is_active > 0) ? '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm align-items-center" title="remove this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-trash text-white"></i></a>' : '<a href="javascript:;" class="action-btn restore-btn bg-info btn m-1 btn-sm align-items-center" title="restore this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-reload text-white"></i></a>';
            }
            $slug = wordwrap(url('/'.$evat->slug), 25, "<br />\n");
            return [
                'id' => $evat->id,
                'code' => $evat->code,
                'name' => $evat->name,
                'description' => '<div class="showLess" title="' . $evat->description . '">' . $description . '</div>',
                'modified' => ($evat->updated_at !== NULL) ? date('d-M-Y', strtotime($evat->updated_at)).'<br/>'. date('h:i A', strtotime($evat->updated_at)) : date('d-M-Y', strtotime($evat->created_at)).'<br/>'. date('h:i A', strtotime($evat->created_at)),
                'status' => $statusClass[$evat->is_active]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$evat->is_active]->bg. ' p-2">' . $statusClass[$evat->is_active]->status . '</span>' ,
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
        $rows = $this->acctgPaymentTypeRepository->validate($request->code);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot create a payment type with an existing code.',
                'label' => 'This is an existing code.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => 'code',
            ]);
        }

        $details = array(
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        );
        $group = $this->acctgPaymentTypeRepository->create($details);

        return response()->json(
            [
                'data' => $group,
                'title' => 'Well done!',
                'text' => 'The payment type has been successfully saved.',
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
            'data' => $this->acctgPaymentTypeRepository->find($id)
        ]);
    }

    public function update(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update'); 
        $rows = $this->acctgPaymentTypeRepository->validate($request->code, $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot update a payment type with an existing code.',
                'label' => 'This is an existing code.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => 'code'
            ]);
        }

        $details = array(
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );

        return response()->json([
            'data' => $this->acctgPaymentTypeRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The payment type has been successfully updated.',
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
            'data' => $this->acctgPaymentTypeRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The payment type has been successfully removed.',
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
            'data' => $this->acctgPaymentTypeRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The payment type has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }
}
