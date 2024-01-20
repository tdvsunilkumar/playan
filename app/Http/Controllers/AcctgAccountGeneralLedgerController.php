<?php

namespace App\Http\Controllers;
use App\Models\AcctgAccountGeneralLedger;
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

class AcctgAccountGeneralLedgerController extends Controller
{
    private AcctgAccountGeneralLedgerRepositoryInterface $accountGeneralLedgerRepository;
    private AcctgAccountSubsidiaryLedgerInterface $accountSubsidiaryRepository;
    private $carbon;
    private $slugs;

    public function __construct(AcctgAccountGeneralLedgerRepositoryInterface $accountGeneralLedgerRepository, AcctgAccountSubsidiaryLedgerInterface $accountSubsidiaryRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->accountGeneralLedgerRepository = $accountGeneralLedgerRepository;
        $this->accountSubsidiaryRepository = $accountSubsidiaryRepository;
        $this->carbon = $carbon;
        $this->slugs = 'accounting/chart-of-accounts/general-ledgers';
    }

    public function index(Request $request)
    {           
        $this->is_permitted($this->slugs, 'read');
        $account_groups = $this->accountGeneralLedgerRepository->allAccountGroups();
        $major_account_groups = ['' => 'select a major group'];
        $submajor_account_groups = ['' => 'select a sub-major group'];
        $fund_codes = $this->accountGeneralLedgerRepository->allFundCodes();
        $parents = ['' => 'select a parent'];
        $normal_balance = ['' => 'select a normal balance', 'Debit' => 'Debit', 'Credit' => 'Credit'];
        $banks = $this->accountGeneralLedgerRepository->allBanks();
        $sl_accounts = $this->accountGeneralLedgerRepository->allGLSLAccounts();
        return view('accounting.chart-of-accounts.general-ledgers.index')->with(compact('banks', 'sl_accounts', 'normal_balance', 'parents', 'account_groups', 'major_account_groups', 'submajor_account_groups', 'fund_codes'));
    }
    
    public function lists(Request $request)
    {
        $statusClass = [
            0 => (object) ['bg' => 'bg-secondary', 'status' => 'Inactive'],
            1 => (object) ['bg' => 'bg-info', 'status' => 'Active'],
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="View"><i class="ti-pencil text-white"></i></a>';
        }
        $canDelete = $this->is_permitted($this->slugs, 'delete', 1);
        $result = $this->accountGeneralLedgerRepository->listItems($request);
        $res = $result->data->map(function($glAccount) use ($statusClass, $actions, $canDelete) {
            $accountGroup = $glAccount->account_group ? $glAccount->account_group->code . ' - ' . $glAccount->account_group->description : '';
            $majorDesc = $glAccount->major_account_group ? $glAccount->major_account_group->prefix . ' - ' . $glAccount->major_account_group->description : '';
            $major = wordwrap($majorDesc, 25, "\n");
            $submajorDesc = $glAccount->submajor_account_group ? $glAccount->submajor_account_group->prefix . ' - ' . $glAccount->submajor_account_group->description : '';
            $submajor = wordwrap($submajorDesc, 25, "\n");
            $fundCodeDesc = $glAccount->fund_code ? $glAccount->fund_code->code . ' - ' . $glAccount->fund_code->description : '';
            $fundCode = wordwrap($fundCodeDesc, 25, "\n");
            $description = wordwrap($glAccount->aglDesc, 25, "\n");
            if ($canDelete > 0) {
                $actions .= ($glAccount->aglStatus == 1) ? '<a href="javascript:;" class="action-btn delete-btn bg-danger btn m-1 btn-sm  align-items-center" title="Remove"><i class="ti-trash text-white"></i></a>' : '<a href="javascript:;" class="action-btn delete-btn bg-info btn m-1 btn-sm  align-items-center" title="Restore"><i class="ti-reload text-white"></i></a>';
            }
            return [
                'id' => $glAccount->aglId,
                'group' => $accountGroup,
                'major' => '<div class="showLess" title="' . $majorDesc . '">' . $major . '</div>',
                'sub_major' => '<div class="showLess" title="' . $submajorDesc . '">' . $submajor . '</div>',
                'code' => $glAccount->aglCode,
                'description' => '<div class="showLess" title="' . $glAccount->aglDesc . '">' . $description . '</div>',                
                'fund_code' => '<div class="showLess" title="' . $fundCodeDesc . '">' . $fundCode . '</div>',
                'normal_balance' => $glAccount->normal_balance ? $glAccount->normal_balance : '',
                'with_sl' => ($glAccount->is_with_sl == 1) ? '<span class="badge badge-yes-no rounded-pill bg-info p-2">Yes</span>' : '<span class="badge badge-yes-no rounded-pill bg-secondary p-2">No</span>', 
                'mother_code' => $glAccount->mother_code,
                'modified' => ($glAccount->aglUpdatedAt !== NULL) ? date('d-M-Y', strtotime($glAccount->aglUpdatedAt)).'<br/>'. date('h:i A', strtotime($glAccount->aglUpdatedAt)) : date('d-M-Y', strtotime($glAccount->aglCreatedAt)).'<br/>'. date('h:i A', strtotime($glAccount->aglCreatedAt)),
                'status' => $statusClass[$glAccount->aglStatus]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$glAccount->aglStatus]->bg. ' p-2">' . $statusClass[$glAccount->aglStatus]->status . '</span>' ,
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

    public function subsidiary_lists(Request $request, $id)
    {
        $statusClass = [
            0 => (object) ['bg' => 'bg-secondary', 'status' => 'Inactive'],
            1 => (object) ['bg' => 'bg-info', 'status' => 'Active'],
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'read', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="edit this"><i class="ti-pencil text-white"></i></a>';
        }
        $canDelete = $this->is_permitted($this->slugs, 'delete', 1);
        $result = $this->accountGeneralLedgerRepository->sub_listItems($request, $id);
        $res = $result->data->map(function($subGL) use ($statusClass, $actions, $canDelete) {
            $description = wordwrap($subGL->description, 25, "\n");
            if ($canDelete > 0) {
                $actions .= ($subGL->is_active == 1) ? '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm  align-items-center" title="remove this"><i class="ti-trash text-white"></i></a>' : '<a href="javascript:;" class="action-btn restore-btn bg-info btn m-1 btn-sm  align-items-center" title="restore this"><i class="ti-reload text-white"></i></a>';
                $actions .= ($subGL->is_hidden == 1) ? '<a href="javascript:;" class="action-btn hide-btn bg-info btn m-1 btn-sm  align-items-center" title="unhide this"><i class="la la-eye text-white"></i></a>' : '<a href="javascript:;" class="action-btn unhide-btn bg-secondary btn m-1 btn-sm  align-items-center" title="hide this"><i class="la la-eye-slash text-white"></i></a>';
            }            
            return [
                'id' => $subGL->id,
                'prefix' => $subGL->prefix,
                'code' => $subGL->code,
                'description' => '<div class="showLess" title="' . $subGL->description . '">' . $description . '</div>',
                'parent' => $subGL->_is_parent,
                'is_parent' => ($subGL->is_parent == 1) ? '<span class="badge badge-yes-no rounded-pill bg-info p-2">Yes</span>' : '<span class="badge badge-yes-no rounded-pill bg-secondary p-2">No</span>', 
                'parent_id' => $subGL->sl_parent_id,
                'hidden' => $subGL->is_hidden,
                'visibility' => ($subGL->is_hidden > 0) ? '<span class="badge badge-status bg-secondary rounded-pill p-2">Hidden</span>' : '<span class="badge badge-status bg-info rounded-pill p-2">Visible</span>',                      
                'modified' => ($subGL->updated_at !== NULL) ? date('d-M-Y', strtotime($subGL->updated_at)).'<br/>'. date('h:i A', strtotime($subGL->updated_at)) : date('d-M-Y', strtotime($subGL->created_at)).'<br/>'. date('h:i A', strtotime($subGL->created_at)),
                'status' => $statusClass[$subGL->is_active]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$subGL->is_active]->bg. ' p-2">' . $statusClass[$subGL->is_active]->status . '</span>' ,
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

    public function current_lists(Request $request, $id)
    {
        $statusClass = [
            0 => (object) ['bg' => 'bg-secondary', 'status' => 'Inactive'],
            1 => (object) ['bg' => 'bg-info', 'status' => 'Active'],
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'read', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="edit this"><i class="ti-pencil text-white"></i></a>';
        }
        $canDelete = $this->is_permitted($this->slugs, 'delete', 1);
        $result = $this->accountGeneralLedgerRepository->current_listItems($request, $id);
        $res = $result->data->map(function($current) use ($statusClass, $actions, $canDelete) {
            $funds = $current->fund ? wordwrap($current->fund->code.' - '.$current->fund->description, 25, "\n") : '';    
            $gl_account = $current->gl_account ? wordwrap($current->gl_account->code.' - '.$current->gl_account->description, 25, "\n") : '';    
            $sl_account = $current->sl_account ? wordwrap($current->sl_account->code.' - '.$current->sl_account->description, 25, "\n") : '';      
            if ($canDelete > 0) {
                $actions .= ($current->is_active == 1) ? '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm  align-items-center" title="remove this"><i class="ti-trash text-white"></i></a>' : '<a href="javascript:;" class="action-btn restore-btn bg-info btn m-1 btn-sm  align-items-center" title="restore this"><i class="ti-reload text-white"></i></a>';
            }            
            return [
                'id' => $current->id,
                'funds' => '<div class="showLess" title="' . ($current->fund ? $current->fund->code.' - '.$current->fund->description : '') . '">' . $funds . '</div>',                
                'gl_account' => ($current->gl_account ? $current->gl_account->code.' - '.$current->gl_account->description : ''),
                'sl_account' => ($current->sl_account ? $current->sl_account->code.' - '.$current->sl_account->description : ''),
                'gl_account_label' => '<div class="showLess" title="' . ($current->gl_account ? $current->gl_account->code.' - '.$current->gl_account->description : '') . '">' . $gl_account . '</div>',
                'sl_account_label' => '<div class="showLess" title="' . ($current->sl_account ? $current->sl_account->code.' - '.$current->sl_account->description : '') . '">' . $sl_account . '</div>',
                'is_debit' => ($current->is_debit == 1) ? '<span class="badge badge-yes-no rounded-pill bg-info p-2">Yes</span>' : '<span class="badge badge-yes-no rounded-pill bg-secondary p-2">No</span>', 
                'modified' => ($current->updated_at !== NULL) ? date('d-M-Y', strtotime($current->updated_at)).'<br/>'. date('h:i A', strtotime($current->updated_at)) : date('d-M-Y', strtotime($current->created_at)).'<br/>'. date('h:i A', strtotime($current->created_at)),
                'status' => $statusClass[$current->is_active]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$current->is_active]->bg. ' p-2">' . $statusClass[$current->is_active]->status . '</span>' ,
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
        $rows = $this->accountGeneralLedgerRepository->validate($request->get('code'), $request->acctg_account_group_id, $request->acctg_account_group_major_id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot create a general account ledger with an existing code.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }

        $details = array(
            'acctg_account_group_id' => $request->acctg_account_group_id,
            'acctg_account_group_major_id' => $request->acctg_account_group_major_id,
            'acctg_account_group_submajor_id' => $request->acctg_account_group_submajor_id,
            'acctg_fund_code_id' => ($request->acctg_fund_code_id > 0) ? $request->acctg_fund_code_id : NULL,
            'normal_balance' => $request->normal_balance,
            'prefix' => $request->prefix,
            'code' => $request->get('code'),
            'description' => $request->description,
            'mother_code' => $request->mother_code,
            'is_with_sl' => ($request->get('is_with_sl') == 'Yes') ? 1: 0,
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        );

        return response()->json(
            [
                'data' => $this->accountGeneralLedgerRepository->create($details),
                'title' => 'Well done!',
                'text' => 'The general account ledger has been successfully saved.',
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
            'data' => $this->accountGeneralLedgerRepository->find($id),
            'subsidiaries' => $this->accountGeneralLedgerRepository->find($id)
            ->subsidiaries->map(function($subsidiary) {
                $status = ($subsidiary->is_active == 1) ? '<span class="badge badge-status rounded-pill bg-info p-2">Active</span>' : '<span class="badge badge-status rounded-pill bg-secondary p-2">Inactive</span>';
                $hidden = ($subsidiary->is_hidden > 0) ? '<span class="badge badge-status rounded-pill bg-secondary p-2">Hidden</span>' : '<span class="badge badge-status rounded-pill bg-info p-2">Visible</span>';
                $icon   = ($subsidiary->is_active == 1) ? '<i class="ti-trash"></i>' : '<i class="ti-reload"></i>';
                return (object) [
                    'id' => $subsidiary->id,
                    'prefix' => $subsidiary->prefix,
                    'code' => $subsidiary->code,
                    'description' => $subsidiary->description,
                    'modified' => ($subsidiary->updated_at !== NULL) ? date('d-M-Y', strtotime($subsidiary->updated_at)).'<br/>'. date('h:i A', strtotime($subsidiary->updated_at)) : date('d-M-Y', strtotime($subsidiary->created_at)).'<br/>'. date('h:i A', strtotime($subsidiary->created_at)),
                    'statusLabel' => $status,
                    'hidden' => $hidden,
                    'status' => $subsidiary->is_active,
                    'icon' => $icon
                ];
            })
        ]);
    }

    public function find_sl(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->accountGeneralLedgerRepository->find_sl($id)
        ]);
    }

    public function update(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update');
        $rows = $this->accountGeneralLedgerRepository->validate($request->get('code'), $request->acctg_account_group_id, $request->acctg_account_group_major_id, $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot update a general account ledger with an existing code.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }

        $details = array(
            'acctg_account_group_id' => $request->acctg_account_group_id,
            'acctg_account_group_major_id' => $request->acctg_account_group_major_id,
            'acctg_account_group_submajor_id' => $request->acctg_account_group_submajor_id,
            'acctg_fund_code_id' => ($request->acctg_fund_code_id > 0) ? $request->acctg_fund_code_id : NULL,
            'normal_balance' => $request->normal_balance,
            'prefix' => $request->prefix,
            'code' => $request->get('code'),
            'description' => $request->description,
            'mother_code' => $request->mother_code,
            'is_with_sl' => ($request->get('is_with_sl') == 'Yes') ? 1: 0,
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );

        return response()->json([
            'data' => $this->accountGeneralLedgerRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The general account ledger has been successfully updated.',
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
            'data' => $this->accountGeneralLedgerRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The general ledger account has been successfully removed.',
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
            'data' => $this->accountGeneralLedgerRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The general ledger account has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function remove_sl(Request $request, $id): JsonResponse 
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
            'text' => 'The subsidiary ledger account has been successfully removed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function restore_sl(Request $request, $id): JsonResponse 
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
            'text' => 'The subsidiary ledger account has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function hide_sl(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'delete');
        $details = array(
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id,
            'is_hidden' => 1
        );

        return response()->json([
            'data' => $this->accountSubsidiaryRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The subsidiary ledger account has been successfully hidden.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function show_sl(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'delete');
        $details = array(
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id,
            'is_hidden' => 0
        );

        return response()->json([
            'data' => $this->accountSubsidiaryRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The subsidiary ledger account has been successfully shown.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function fetch_group_code(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $account = (!empty($request->get('account'))) ? $this->accountGeneralLedgerRepository->findAcctGrp($request->get('account'))->code : '';
        $major = (!empty($request->get('major'))) ? $this->accountGeneralLedgerRepository->findMajorAcctGrp($request->get('major'))->prefix : '';
        $submajor = (!empty($request->get('submajor'))) ? $this->accountGeneralLedgerRepository->findSubMajorAcctGrp($request->get('submajor'))->prefix : '';
        return response()->json([
            'account' => $account,
            'major' => $major,
            'submajor' => $submajor
        ]);
    }

    public function reload_major_account(Request $request, $account) 
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->accountGeneralLedgerRepository->reload_major_account($account)
        ]);
    }

    public function reload_submajor_account(Request $request) 
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->accountGeneralLedgerRepository->reload_submajor_account($request->get('account_group'), $request->get('major_group'))
        ]);
    }

    public function reload_parent(Request $request, $gl, $sl) 
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->accountGeneralLedgerRepository->reload_parent($gl, $sl)
        ]);
    }

    public function store_sl(Request $request, $gl_account): JsonResponse 
    {       
        $this->is_permitted($this->slugs, 'create');
        $timestamp = $this->carbon::now();
        $rows = $this->accountSubsidiaryRepository->validate($request->get('code'), $gl_account);
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
            'bank_id' => $request->bank_id,
            'prefix' => $request->prefix,
            'code' => $request->get('code'),
            'description' => $request->description,
            'is_parent' => $request->is_parent,
            'sl_parent_id' => $request->sl_parent_id > 0 ? $request->sl_parent_id : 0,
            'created_at' => $timestamp,
            'created_by' => Auth::user()->id
        );

        return response()->json(
            [
                'data' => $this->accountSubsidiaryRepository->create($details),
                'title' => 'Well done!',
                'text' => 'The subsidiary has been successfully saved.',
                'type' => 'success',
                'class' => 'btn-brand'
            ],
            Response::HTTP_CREATED
        );
    }

    public function update_sl(Request $request, $gl_account, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update');
        $timestamp = $this->carbon::now();        
        $rows = $this->accountSubsidiaryRepository->validate($request->get('code'), $gl_account, $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot update a subsidiary with existing prefix no.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }

        $details = array(
            'bank_id' => $request->bank_id,
            'prefix' => $request->prefix,
            'code' => $request->get('code'),
            'description' => $request->description,
            'is_parent' => $request->is_parent,
            'sl_parent_id' => $request->sl_parent_id > 0 ? $request->sl_parent_id : 0,
            'updated_at' => $timestamp,
            'updated_by' => Auth::user()->id
        );
        
        return response()->json([
            'data' => $this->accountSubsidiaryRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The subsidiary has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function find_current(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->accountSubsidiaryRepository->find_current($id),
        ]);
    }

    public function store_current(Request $request, $slID): JsonResponse 
    {       
        $this->is_permitted($this->slugs, 'create');
        $is_debit = ($request->get('is_debit') > 0) ? 1 : 0;
        $glID = AcctgAccountSubsidiaryLedger::find($request->sl_account_id)->gl_account_id;
        $rows = $this->accountSubsidiaryRepository->validate_current($slID, $request->fund_code_id, $glID, $request->sl_account_id, $is_debit);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot create a receivable / contra with an existing debit.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }

        $details = array(
            'fund_code_id' => $request->fund_code_id,
            'income_sl' => $slID,
            'gl_account_id' => $glID,
            'sl_account_id' => $request->sl_account_id,
            'is_debit' => $is_debit,
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        );

        return response()->json(
            [
                'data' => $this->accountSubsidiaryRepository->create_current($details),
                'title' => 'Well done!',
                'text' => 'The receivable / contra has been successfully saved.',
                'type' => 'success',
                'class' => 'btn-brand'
            ],
            Response::HTTP_CREATED
        );
    }

    public function update_current(Request $request, $slID, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update');
        $is_debit = ($request->get('is_debit') > 0) ? 1 : 0;
        $glID = AcctgAccountSubsidiaryLedger::find($request->sl_account_id)->gl_account_id;
        $rows = $this->accountSubsidiaryRepository->validate_current($slID, $request->fund_code_id, $glID, $request->sl_account_id, $is_debit, $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot update a receivable / contra with an existing debit.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }

        $details = array(
            'fund_code_id' => $request->fund_code_id,
            'income_sl' => $slID,
            'gl_account_id' => $glID,
            'sl_account_id' => $request->sl_account_id,
            'is_debit' => $is_debit,
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );
        
        return response()->json([
            'data' => $this->accountSubsidiaryRepository->update_current($id, $details),
            'title' => 'Well done!',
            'text' => 'The receivable / contra has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }
}
