<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\GsoDepartmentalRequisitionRepositoryInterface;
use App\Interfaces\CboBudgetAllocationInterface;
use App\Interfaces\GsoPurchaseRequestInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Mail\Notification;
use Illuminate\Support\Facades\Mail;

class NotificationController extends Controller
{
    private GsoDepartmentalRequisitionRepositoryInterface $gsoDepartmentalRequisitionRepository;
    private CboBudgetAllocationInterface $cboBudgetAllocationRepository;
    private GsoPurchaseRequestInterface $gsoPurchaseRequestRepository;
    private $carbon;
    private $slugs;

    public function __construct(
        GsoDepartmentalRequisitionRepositoryInterface $gsoDepartmentalRequisitionRepository, 
        CboBudgetAllocationInterface $cboBudgetAllocationRepository, 
        GsoPurchaseRequestInterface $gsoPurchaseRequestRepository, 
        Carbon $carbon
    ) {
        date_default_timezone_set('Asia/Manila');
        $this->gsoPurchaseRequestRepository = $gsoPurchaseRequestRepository;
        $this->gsoDepartmentalRequisitionRepository = $gsoDepartmentalRequisitionRepository;
        $this->cboBudgetAllocationRepository = $cboBudgetAllocationRepository;
        $this->carbon = $carbon;
    }

    public function departmental_request(Request $request, $id)
    {
        $departmentals = $this->gsoDepartmentalRequisitionRepository::fetch($id);
        $users = $this->gsoDepartmentalRequisitionRepository->get_departmental_request_approvers($id);

        if ($departmentals->count() > 0) {
            foreach ($departmentals as $departmental) {
                foreach ($users as $user) {
                    Mail::to($user->email)->send(new Notification($departmental, $user->email, $user->nickname, $user->id, 'You have some items for approval.', 
                    [
                        'request' => 'departmental-request',
                        'messages' => 'You have some items for approval.'
                    ] 
                    )); 
                }
            }
            
            return 'A message has been successfully sent!';
        } else {
            return 'There is no mail notifications today!';
        }
    }

    public function approve_departmental(Request $request, $requisitionID)
    {   
        $timestamp = $this->carbon::now(); 
        $requisition = $this->gsoDepartmentalRequisitionRepository->find($requisitionID);
        if ($requisition->status == 'for approval') {
            $details = array(
                'status' => 'requested',
                'approved_at' => $timestamp,
                'approved_by' => $request->get('user')
            );
            $allotmentDetail = array(
                'budget_control_no' => $this->cboBudgetAllocationRepository->generateBudgetControlNo(date('Y')),
                'departmental_request_id' => $requisitionID,
                'department_id' => $requisition->department_id,
                'division_id' => $requisition->division_id,
                'budget_year' => date('Y'),
                'created_at' => $timestamp,
                'created_by' => Auth::user()->id
            );
            $allotment = $this->cboBudgetAllocationRepository->create($allotmentDetail);
            $allotmentRequestDetail = array(
                'allotment_id' => $allotment->id,
                'status' => 'completed',                    
                'sent_at' => $timestamp,
                'sent_by' => $request->get('user'),
            );
            $allotmentRequest = $this->cboBudgetAllocationRepository->create_request($allotmentRequestDetail);
            $this->gsoDepartmentalRequisitionRepository->update($requisitionID, $details);
            $this->gsoDepartmentalRequisitionRepository->updateLines($requisitionID, $details);
            $update = 0;
        } else {
            $update = 1;
        }  
        $type = 'approve';      
        return view('mails.notify')->with(compact('update', 'type'));
    }

    public function disapprove_departmental(Request $request, $requisitionID)
    {
        $timestamp = $this->carbon::now();
        $requisition = $this->gsoDepartmentalRequisitionRepository->find($requisitionID);
        if ($requisition->status == 'for approval') {
            $details = array(
                'status' => 'cancelled',
                'disapproved_at' => $timestamp,
                'disapproved_by' => $request->get('user'),
                'disapproved_remarks' => 'This is disapproved through email.'
            );
            $this->gsoDepartmentalRequisitionRepository->update($requisitionID, $details);
            $details2 = array(
                'departmental_request_id' => $requisitionID,
                'disapproved_from' => 'Departmental Request',
                'disapproved_at' => $timestamp,
                'disapproved_by' => $request->get('user'),
                'disapproved_remarks' => 'This is disapproved through email.'
            );
            $this->gsoDepartmentalRequisitionRepository->disapprove_request($details2);
            $update = 0;
        } else {
            $update = 1;
        }
        $type = 'disapprove';
        return view('mails.notify')->with(compact('update', 'type'));
    }
}
