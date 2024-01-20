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
use App\Interfaces\AcctgBankInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AcctgBankController extends Controller
{
    private AcctgBankInterface $acctgBankRepository;
    private $carbon;
    private $slugs;

    public function __construct(AcctgBankInterface $acctgBankRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->acctgBankRepository = $acctgBankRepository;
        $this->carbon = $carbon;
        $this->slugs = 'accounting/setup-data/banks';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        return view('accounting.setup-data.banks.index');
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
        $result = $this->acctgBankRepository->listItems($request);
        $res = $result->data->map(function($bank) use ($statusClass, $actions, $canDelete) {
            $bank_account_name = wordwrap($bank->bank_account_name, 25, "\n");            
            if ($canDelete > 0) {
                $actions .= ($bank->is_active > 0) ? '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm align-items-center" title="remove this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-trash text-white"></i></a>' : '<a href="javascript:;" class="action-btn restore-btn bg-info btn m-1 btn-sm align-items-center" title="restore this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-reload text-white"></i></a>';
            }
            return [
                'id' => $bank->id,
                'code' => $bank->bank_account_no,
                'name' => $bank->bank_name,
                'description' => '<div class="showLess" title="' . $bank->bank_account_name . '">' . $bank_account_name . '</div>',
                'modified' => ($bank->updated_at !== NULL) ? date('d-M-Y', strtotime($bank->updated_at)).'<br/>'. date('h:i A', strtotime($bank->updated_at)) : date('d-M-Y', strtotime($bank->created_at)).'<br/>'. date('h:i A', strtotime($bank->created_at)),
                'status' => $statusClass[$bank->is_active]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$bank->is_active]->bg. ' p-2">' . $statusClass[$bank->is_active]->status . '</span>' ,
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
        $rows = $this->acctgBankRepository->validate($request->bank_account_no);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot create a bank with an existing code.',
                'label' => 'This is an existing code.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => 'code',
            ]);
        }

        $details = array(
            'bank_account_no' => $request->bank_account_no,
            'bank_name' => $request->bank_name,
            'bank_account_name' => $request->bank_account_name,
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        );
        $group = $this->acctgBankRepository->create($details);

        return response()->json(
            [
                'data' => $group,
                'title' => 'Well done!',
                'text' => 'The bank has been successfully saved.',
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
            'data' => $this->acctgBankRepository->find($id)
        ]);
    }

    public function update(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update'); 
        $rows = $this->acctgBankRepository->validate($request->bank_account_no, $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot update a bank with an existing code.',
                'label' => 'This is an existing code.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => 'code'
            ]);
        }

        $details = array(
            'bank_account_no' => $request->bank_account_no,
            'bank_name' => $request->bank_name,
            'bank_account_name' => $request->bank_account_name,
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );

        return response()->json([
            'data' => $this->acctgBankRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The bank has been successfully updated.',
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
            'data' => $this->acctgBankRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The bank has been successfully removed.',
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
            'data' => $this->acctgBankRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The bank has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }
}
