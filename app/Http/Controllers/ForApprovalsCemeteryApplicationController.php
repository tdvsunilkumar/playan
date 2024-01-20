<?php

namespace App\Http\Controllers;
use App\Models\GsoDepartmentalRequisition;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\GsoDepartmentalRequisitionRepositoryInterface;
use App\Interfaces\EconCemeteryInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PDF;

class ForApprovalsCemeteryApplicationController extends Controller
{   
    private EconCemeteryInterface $econCemeteryRepository;
    private $carbon;
    private $slugs;

    public function __construct(
        EconCemeteryInterface $econCemeteryRepository,
        Carbon $carbon
    ) {
        date_default_timezone_set('Asia/Manila');
        $this->econCemeteryRepository = $econCemeteryRepository;
        $this->carbon = $carbon;
        $this->slugs = 'for-approvals/budget-allocation';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $requestor = $this->econCemeteryRepository->allCitizens();
        $expired = $this->econCemeteryRepository->allCitizens();
        $locations = $this->econCemeteryRepository->allCemeteryLocations();
        $services = $this->econCemeteryRepository->allServices(1);
        $cemeteries = ['' => 'select a cemetery'];
        $styles = $this->econCemeteryRepository->allCemeteryStyles();
        $services_fees = ['' => 'select a service fee', '1000' => '1000', '5000' => '5000', '15000' => '15000', '20000' => '20000'];
        $lots = ['' => 'select a lot'];
        return view('for-approvals.economic-and-investment.cemetery.index')->with(compact('requestor', 'expired', 'locations', 'services', 'services_fees', 'cemeteries', 'styles', 'lots'));
    }

    public function lists(Request $request)
    {   
        $statusClass = [
            'draft' => (object) ['bg' => 'draft-bg', 'status' => 'draft'],
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'pending'],
            'requested' => (object) ['bg' => 'completed-bg', 'status' => 'Approved'],
            'partial' => (object) ['bg' => 'completed-bg', 'status' => 'Approved'],
            'completed' => (object) ['bg' => 'completed-bg', 'status' => 'Approved'],
            'cancelled' => (object) ['bg' => 'cancelled-bg', 'status' => 'cancelled']
        ];
        $actions = ''; $actions2 = '';
        if ($this->is_permitted($this->slugs, 'read', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="view this"><i class="ti-search text-white"></i></a>';
            $actions2 .= '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="view this"><i class="ti-search text-white"></i></a>';
            $actions2 .= '<a href="javascript:;" class="action-btn view-disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="view this"><i class="ti-comment-alt text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn approve-btn bg-success btn m-1 btn-sm align-items-center" title="approve this"><i class="ti-thumb-up text-white"></i></a>';
            
        }
        if ($this->is_permitted($this->slugs, 'disapprove', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="disapprove this"><i class="ti-thumb-down text-white"></i></a>';
        }        
        $result = $this->econCemeteryRepository->approval_listItems($request);
        $res = $result->data->map(function($econ) use ($statusClass, $actions, $actions2) {             
            
            $requestor = $econ->requestor ? wordwrap($econ->requestor->cit_fullname, 25, "\n") : '';
            $address = $econ->full_address ? wordwrap($econ->full_address, 25, "\n") : '';
            if ($econ->disapproved_by !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($econ->disapproved_by).'</strong><br/>'.date('d-M-Y H:i a', strtotime($econ->disapproved_at));
            } else if($econ->approved_by !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($econ->approved_by).'</strong><br/>'.date('d-M-Y H:i a', strtotime($econ->approved_at));
            } else {
                $approvedBy = '';
            }
            return [
                'id' => $econ->id,
                'transaction_no' => $econ->transaction_no,
                'transaction_no_label' => '<strong class="text-primary">' . $econ->transaction_no . '</strong><br/>'.date('d-M-Y', strtotime($econ->transaction_date)),
                'transaction_date' => date('d-M-Y', strtotime($econ->transaction_date)),
                'reference_no' => '<strong>'.($econ->transaction ? $econ->transaction->transaction_no : '').'</strong>',
                'requestor' => '<div class="showLess" title="' . ($econ->requestor ? $econ->requestor->cit_fullname : '') . '">' . $requestor . '</div>',
                'address' => '<div class="showLess" title="' . ($econ->full_address ? $econ->full_address : '') . '">' . $address . '</div>',
                'or_no' => '<strong class="text-primary">' . $econ->or_no . '</strong>',
                'or_no_label' => $econ->or_no ? '<strong class="text-primary">' . $econ->or_no . '</strong><br/>'.date('d-M-Y', strtotime($econ->or_date)) : '',
                'total' => $this->money_format($econ->total_amount),
                'modified' => ($econ->updated_at !== NULL) ? date('d-M-Y', strtotime($econ->updated_at)).'<br/>'. date('h:i A', strtotime($econ->updated_at)) : date('d-M-Y', strtotime($econ->created_at)).'<br/>'. date('h:i A', strtotime($econ->created_at)),
                'approved_by' => $approvedBy,
                'status' => $statusClass[$econ->status]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$econ->status]->bg. ' p-2">' . $statusClass[$econ->status]->status . '</span>' ,
                'actions' => ($econ->status == 'cancelled') ? $actions2 : $actions
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function money_format($money)
    {
        return '₱' . number_format(floor(($money*100))/100, 2);
    }

    public function fetchApprovedBy($approvers)
    {
        if (!empty($approvers)) {
            return $this->econCemeteryRepository->fetchApprovedBy($approvers);
        }
        return '';
    }

    public function fetch_status(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->econCemeteryRepository->find($id)->status
        ]);
    }

    public function fetch_remarks(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'remarks' => $this->econCemeteryRepository->find($id)->disapproved_remarks
        ]);
    }

    public function validate_approver(Request $request, $id)
    {
        $approvers = explode(',',$this->econCemeteryRepository->find($id)->approved_by);
        if (in_array(Auth::user()->id, $approvers)) {
            return true;
        }
        return false;
    }

    public function approve(Request $request, $appID)
    {
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            $cemetery = $this->econCemeteryRepository->find($appID);
            $timestamp = $this->carbon::now();
            $details2 = array(
                'top_transaction_type_id' => $cemetery->top_transaction_type_id,
                'transaction_ref_no' => $appID,
                'tfoc_is_applicable' => $cemetery->top_transaction->tfoc_is_applicable,
                'tfoc_id' => 0,
                'amount' => $cemetery->total_amount,                
                'created_at' => $timestamp,
                'created_by' => Auth::user()->id
            );
            $transaction = $this->econCemeteryRepository->create_transactions($details2);
            $this->econCemeteryRepository->update_transactions($transaction->id, [
                'transaction_no' => str_pad($transaction->id, 6, '0', STR_PAD_LEFT)
            ]);            
            $details = array(
                'top_transaction_id' => $transaction->id,
                'remaining_amount' => $cemetery->total_amount, 
                'approved_at' => $timestamp,
                'approved_by' =>  Auth::user()->id,
                'status' => 'requested',
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );
            $this->econCemeteryRepository->update($appID, $details);  

            // INSERT RECEIVABLE            
            /*if ($cemetery->terms == 0) {
                $receivable_details = array(
                    'category' => 'cemetery',
                    'application_id' => $appID,
                    'top_transaction_type_id' => $cemetery->top_transaction_type_id,
                    'fund_code_id' => $cemetery->tfoc->fund_id,
                    'gl_account_id' => $cemetery->gl_account_id,
                    'sl_account_id' => $cemetery->sl_account_id,
                    'description' => $cemetery->service->tfoc_name,
                    'top_no' => $cemetery->top_transaction->transaction_no,
                    'due_date' => date('Y-m-d', strtotime($timestamp)),
                    'amount_due' => $cemetery->total_amount,
                    'remaining_amount' => $cemetery->total_amount
                );
                $this->econCemeteryRepository->create_receivable($receivable_details); 
            } else {
                $amount = floatval($cemetery->total_amount) / floatval($cemetery->terms);
                $i = 0;
                while ($i < $cemetery->terms) {
                    $receivable_details = array(
                        'category' => 'cemetery',
                        'application_id' => $appID,
                        'top_transaction_type_id' => $cemetery->top_transaction_type_id,
                        'fund_code_id' => $cemetery->tfoc->fund_id,
                        'gl_account_id' => $cemetery->gl_account_id,
                        'sl_account_id' => $cemetery->sl_account_id,
                        'description' => $cemetery->service->tfoc_name,
                        'top_no' => $cemetery->top_transaction->transaction_no,
                        'due_date' => date('Y-m-d', strtotime($timestamp)),
                        'amount_due' => $amount,
                        'remaining_amount' => $amount
                    );
                    $this->econCemeteryRepository->create_receivable($receivable_details); 
                    $i++;
                }
            }
            */
            
            // $details3 = array(
            //     'cemetery_application_id' => $appID,
            //     'citizen_id' => $cemetery->requestor_id,
            //     'total_amount' => $cemetery->total_amount,                
            //     'created_at' => $timestamp,
            //     'created_by' => Auth::user()->id
            // );
            // $transaction = $this->econCemeteryRepository->create_payments($details3);
            return response()->json([
                'text' => 'The request has been successfully approved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }

    public function disapprove(Request $request, $appID)
    {
        if ($this->is_permitted($this->slugs, 'disapprove', 1) > 0) {
            $timestamp = $this->carbon::now();
            $details = array(
                'status' => 'cancelled',
                'disapproved_at' => $timestamp,
                'disapproved_by' => Auth::user()->id,
                'disapproved_remarks' => $request->disapproved_remarks
            );            
            return response()->json([
                'data' => $this->econCemeteryRepository->update($appID, $details),
                'text' => 'The request has been successfully disapproved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }

    public function find(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->econCemeteryRepository->find($id)
        ]);
    }

    public function reload_cemetery_lot(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->econCemeteryRepository->reload_cemetery_lot($id, $request->get('location'), $request->get('cemetery'), $request->get('style'))
        ]);
    }

    public function reload_cemetery_name(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->econCemeteryRepository->reload_cemetery_name($request->get('location'))
        ]);
    }
}
