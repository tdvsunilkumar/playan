<?php

namespace App\Http\Controllers;
use App\Models\AcctgAccountSubsidiaryLedger;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\AcctgAccountSubsidiaryLedgerInterface;
use App\Interfaces\AcctgAccountGeneralLedgerRepositoryInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AcctgAccountSubsidiaryLedgerController extends Controller
{
    private AcctgAccountSubsidiaryLedgerInterface $accountSubsidiaryRepository;
    private AcctgAccountGeneralLedgerRepositoryInterface $accountGeneralLedgerRepository;
    private $carbon;
    private $slugs;

    public function __construct(AcctgAccountSubsidiaryLedgerInterface $accountSubsidiaryRepository, AcctgAccountGeneralLedgerRepositoryInterface $accountGeneralLedgerRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->accountSubsidiaryRepository = $accountSubsidiaryRepository;
        $this->accountGeneralLedgerRepository = $accountGeneralLedgerRepository;
        $this->carbon = $carbon;
        $this->slugs = 'accounting/chart-of-accounts/subsidiary-ledgers';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        return view('accounting.chart-of-accounts.subsidiary-ledgers.index');
    }
    
    public function lists(Request $request)
    {
        $statusClass = [
            0 => (object) ['bg' => 'bg-secondary', 'status' => 'Inactive'],
            1 => (object) ['bg' => 'bg-info', 'status' => 'Active'],
        ];
        $canDelete = $this->is_permitted($this->slugs, 'delete', 1);
        $result = $this->accountSubsidiaryRepository->listItems($request);
        $increment = 1;
        $res = $result->data->map(function($subsidiary) use ($statusClass, $canDelete, $increment) {
            $glAccountDesc = $subsidiary->gl_account ? $subsidiary->gl_account->code . ' - ' . $subsidiary->gl_account->description : '';
            $glAccount = wordwrap($glAccountDesc, 25, "\n");
            $description = wordwrap($subsidiary->subDesc, 25, "\n");
            if ($canDelete > 0) {
                $actions = ($subsidiary->subStatus == 1) ? '<a href="javascript:;" class="action-btn delete-btn bg-danger btn m-1 btn-sm  align-items-center" title="Remove"><i class="ti-trash text-white"></i></a>' : '<a href="javascript:;" class="action-btn delete-btn bg-info btn m-1 btn-sm  align-items-center" title="Restore"><i class="ti-reload text-white"></i></a>';
            }
            return [
                'id' => $subsidiary->subId,
                'increment' => $increment++,
                'gl_account' => '<div class="showLess" title="' . $glAccountDesc . '">' . $glAccount . '</div>',
                'prefix' => $subsidiary->subPrefix,
                'code' => $subsidiary->subCode,
                'description' => '<div class="showLess" title="' . $subsidiary->subDesc . '">' . $description . '</div>', 
                'modified' => ($subsidiary->subUpdatedAt !== NULL) ? date('d-M-Y', strtotime($subsidiary->subUpdatedAt)).'<br/>'. date('h:i A', strtotime($subsidiary->subUpdatedAt)) : date('d-M-Y', strtotime($subsidiary->subCreatedAt)).'<br/>'. date('h:i A', strtotime($subsidiary->subCreatedAt)),
                'status' => $statusClass[$subsidiary->subStatus]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$subsidiary->subStatus]->bg. ' p-2">' . $statusClass[$subsidiary->subStatus]->status . '</span>' ,
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
    
    public function store(Request $request, $gl_account): JsonResponse 
    {       
        $this->is_permitted($this->slugs, 'create');
        $timestamp = $this->carbon::now();

        $rows = $this->accountSubsidiaryRepository->validate($request->code, $gl_account);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot create a subsidiary with existing prefix no.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }

        $details = array(
            'gl_account_id' => $gl_account,
            'prefix' => $request->code,
            'code' => $this->accountGeneralLedgerRepository->find($gl_account)->code . '-' . $request->code,
            'description' => $request->name,
            'created_at' => $timestamp,
            'created_by' => Auth::user()->id
        );

        return response()->json(
            [
                'data' => $this->accountSubsidiaryRepository->create($details),
                'title' => 'Well done!',
                'text' => 'The subsidiary has been successfully saved.',
                'type' => 'success',
                'class' => 'btn-brand',
                'status' =>  '<span class="badge badge-status rounded-pill bg-info p-2">Active</span>',
                'modified_at' => date('d-M-Y', strtotime($timestamp)).'<br/>'. date('h:i A', strtotime($timestamp))
            ],
            Response::HTTP_CREATED
        );
    }

    public function update(Request $request, $gl_account, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update');
        $timestamp = $this->carbon::now();
        
        $rows = $this->accountSubsidiaryRepository->validate($request->code, $gl_account, $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot update a subsidiary with existing prefix no.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }

        $details = array(
            'prefix' => $request->code,
            'code' => $this->accountGeneralLedgerRepository->find($gl_account)->code . '-' . $request->code,
            'description' => $request->name,
            'updated_at' => $timestamp,
            'updated_by' => Auth::user()->id
        );
        
        return response()->json([
            'data' => $this->accountSubsidiaryRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The subsidiary has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand',
            'status' => ($this->accountSubsidiaryRepository->find($id)->is_active > 0) ? '<span class="badge badge-status rounded-pill bg-info p-2">Active</span>' : '<span class="badge badge-status rounded-pill bg-secondary p-2">Inactive</span>',
            'modified_at' => date('d-M-Y', strtotime($timestamp)).'<br/>'. date('h:i A', strtotime($timestamp))
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
            'data' => $this->accountSubsidiaryRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The subsidiary ledger has been successfully removed.',
            'type' => 'success',
            'class' => 'btn-brand',
            'icon' => '<i class="ti-reload"></i>',
            'status' => '<span class="badge badge-status rounded-pill bg-secondary p-2">Inactive</span>'
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
            'data' => $this->accountSubsidiaryRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The subsidiary ledger has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand',
            'icon' => '<i class="ti-trash"></i>',
            'status' => '<span class="badge badge-status rounded-pill bg-info p-2">Active</span>'
        ]);
    }
}
