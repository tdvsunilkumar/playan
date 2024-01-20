<?php

namespace App\Http\Controllers;
use App\Models\CtoDenominationBill;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\CtoCollectionInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PDF;
use NumberToWords\NumberToWords;

class TreasuryCollectionController extends Controller
{
    private CtoCollectionInterface $ctoCollectionRepository;
    private $carbon;
    private $slugs;

    public function __construct(CtoCollectionInterface $ctoCollectionRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->ctoCollectionRepository = $ctoCollectionRepository;
        $this->carbon = $carbon;
        $this->slugs = 'treasury/collections';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $permission = array(
            'create' => $this->is_permitted($this->slugs, 'create', 1),
            'read' => $this->is_permitted($this->slugs, 'read', 1),
            'update' => $this->is_permitted($this->slugs, 'update', 1),
            'delete' => $this->is_permitted($this->slugs, 'delete', 1),
            'approve' => $this->is_permitted($this->slugs, 'approve', 1),
            'disapprove' => $this->is_permitted($this->slugs, 'disapprove', 1),
            'download' => $this->is_permitted($this->slugs, 'download', 1)
        );
        $fund_codes = $this->ctoCollectionRepository->allFundCodes();
        $officers = $this->ctoCollectionRepository->allCtoOfficers();
        return view('treasury.collections.index')->with(compact('permission', 'fund_codes', 'officers'));
    }
    
    public function lists(Request $request) 
    { 
        $statusClass = [
            'draft' => (object) ['bg' => 'draft-bg', 'status' => 'draft'],   
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'pending'],
            'posted' => (object) ['bg' => 'completed-bg', 'status' => 'posted'],
            'cancelled' => (object) ['bg' => 'cancelled-bg', 'status' => 'cancelled'],
        ];
        $actions = ''; $actions2 = '';
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="edit this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-pencil text-white"></i></a>';
            $actions .= '<a href="javascript:;" class="action-btn print-btn bg-print btn m-1 btn-sm align-items-center" title="print this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-printer text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'read', 1) > 0) {
            $actions2 .= '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
            $actions2 .= '<a href="javascript:;" class="action-btn print-btn bg-print btn m-1 btn-sm align-items-center" title="print this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-printer text-white"></i></a>';
        }
        $result = $this->ctoCollectionRepository->listItems($request);
        $res = $result->data->map(function($collection) use ($statusClass, $actions, $actions2) {
            $officer = $collection->officer ? wordwrap($collection->officer->name, 25, "\n") : ''; 
            $fund = $collection->fund ? wordwrap($collection->fund->code.' - '.$collection->fund->description, 25, "\n") : ''; 

            return [
                'id' => $collection->id,
                'fund' => '<div class="showLess" title="' . ($collection->fund ? $collection->fund->code.' - '.$collection->fund->description : '') . '">' . $fund . '</div>',
                'transaction_no' => $collection->trans_no,
                'transaction_label' => '<strong class="text-primary">'.$collection->trans_no.'</strong>',
                'transaction_date' => date('d-M-Y', strtotime($collection->trans_date)),
                'officer' => '<div class="showLess" title="' . ($collection->officer ? $collection->officer->name : '') . '">' . $officer . '</div>',
                'total_amount' => $this->money_format($collection->total_amount),
                'modified' => ($collection->updated_at !== NULL) ? 
                '<strong>'.$collection->modified->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($collection->updated_at)) : 
                '<strong>'.$collection->inserted->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($collection->created_at)),
                'status' => $statusClass[$collection->status]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$collection->status]->bg. ' p-2">' . $statusClass[$collection->status]->status . '</span>' ,
                'actions' => ($collection->status != 'draft') ? $actions2 : $actions
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function transaction_lists(Request $request, $id) 
    { 
        $statusClass = [
            'draft' => (object) ['bg' => 'draft-bg', 'status' => 'draft'],   
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'pending'],
            'posted' => (object) ['bg' => 'completed-bg', 'status' => 'posted'],
            'cancelled' => (object) ['bg' => 'cancelled-bg', 'status' => 'cancelled'],
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'read', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn me-05 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
        }
        $result = $this->ctoCollectionRepository->transaction_listItems($request, $id);
        $res = $result->data->map(function($trans) use ($statusClass, $actions) {
            $taxpayer = $trans->taxpayer_name ? wordwrap($trans->taxpayer_name, 25, "\n") : ''; 
            $form_code = $trans->form_code ? wordwrap($trans->form_code, 25, "\n") : ''; 
            $sl_account = $trans->sl_account ? wordwrap($trans->sl_account, 25, "\n") : ''; 
            return [
                'id' => $trans->identity,
                'trans_date' => $trans->cashier_or_date,
                'or_no' => $trans->or_no,
                'or_label' => '<strong class="text-primary">'. $trans->or_no .'</strong>',
                'taxpayer' => '<div class="showLess" title="' . ($trans->taxpayer_name ? $trans->taxpayer_name : '') . '">' . $taxpayer . '</div>',
                'form_code' => '<div class="showLess" title="' . ($trans->form_code ? $trans->form_code : '') . '">' . $form_code . '</div>',
                'sl_account' => '<div class="showLess" title="' . ($trans->sl_account ? $trans->sl_account : '') . '">' . $sl_account . '</div>',
                'credit' => ($trans->is_discount > 0) ? '<span class="text-danger">('.$this->money_formats($trans->amount).')</span>' : $this->money_formats($trans->amount),
                'actions' => $actions
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
            'collections' => $result->collections
        ]);
    }

    public function receipt_lists(Request $request, $id) 
    { 
        $statusClass = [
            'draft' => (object) ['bg' => 'draft-bg', 'status' => 'draft'],   
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'pending'],
            'posted' => (object) ['bg' => 'completed-bg', 'status' => 'posted'],
            'cancelled' => (object) ['bg' => 'cancelled-bg', 'status' => 'cancelled'],
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'read', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn me-05 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
        }
        $result = $this->ctoCollectionRepository->receipt_listItems($request, $id);
        $res = $result->data->map(function($receipt) use ($statusClass, $actions) {
            return [
                'id' => $receipt->id,
                'form_no' => $receipt->form_code,
                'or_dept' => $receipt->or_dept,
                'or_from' => $receipt->or_from,
                'or_to' => $receipt->or_to,
                'total_amount' => floatval($receipt->total_amount) - floatval($receipt->total_discount),
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
            'total_amount' => number_format($result->total, 2, '.', ''),
            'total_discount' => number_format($result->discount, 2, '.', '')
        ]);
    }

    public function get_denominations(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->ctoCollectionRepository->get_denominations($id),
            'title' => 'Well done!',
            'text' => 'The request has been sucessfully fetched.',
            'type' => 'success',
        ]);
    }

    public function money_formatx($money)
    {
        return floor($money*100)/100;
    }

    public function money_formats($money)
    {
        // return number_format(floor(($money*100))/100, 2);
        return number_format($money, 2);
    }

    public function money_format($money)
    {
        return '₱' . number_format(floor(($money*100))/100, 2);
    }

    public function store(Request $request)
    {
        $this->is_permitted($this->slugs, 'create');  
        $rows = $this->ctoCollectionRepository->validate($request->transaction_date, $request->officer_id, $request->fund_code_id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'This transaction is already existing.',
                'label' => 'This is already existing.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => ['transaction_date', 'officer_id']
            ]);
        }
        $details = array(
            'fund_code_id' => $request->fund_code_id,
            'officer_id' => $request->officer_id,
            'trans_no' => $this->ctoCollectionRepository->generate(),
            'trans_date' => date('Y-m-d', strtotime($request->transaction_date)),
            'total_amount' => $request->total_amount,
            'data_collections' => $request->collections,
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        );
        $collections = $this->ctoCollectionRepository->create($details);

        foreach ($request->counter as $key => $val) {
            $bill = CtoDenominationBill::find($key);
            $details = array(
                'collection_id' => $collections->id,
                'denomination_id' => $key,
                'counter' => ($val != NULL) ? $val : NULL,
                'amount' => ($val != NULL) ? (floatval($val) * floatval($bill->code)) : NULL,
                'created_at' => $this->carbon::now(),
                'created_by' => Auth::user()->id
            );
            $collections_details = $this->ctoCollectionRepository->create_details($details);
        }

        $collectionDetails = array(
            'is_collected' => ($request->officer_id > 0) ? 1 : 0
        );
        $this->ctoCollectionRepository->update_collections($collectionDetails, $request->collections, 0);
        
        return response()->json(
            [
                'data' => $collections,
                'title' => 'Well done!',
                'text' => 'The collection has been successfully saved.',
                'type' => 'success',
                'class' => 'btn-brand'
            ],
            Response::HTTP_CREATED
        );
    }

    public function update(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'update');  
        $rows = $this->ctoCollectionRepository->validate($request->transaction_date, $request->officer_id, $request->fund_code_id, $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'This transaction is already existing.',
                'label' => 'This is already existing.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => ['transaction_date', 'officer_id']
            ]);
        }

        $details = array(
            'fund_code_id' => $request->fund_code_id,
            'officer_id' => $request->officer_id,
            'trans_date' => date('Y-m-d', strtotime($request->transaction_date)),
            'total_amount' => $request->total_amount,
            'data_collections' => $request->collections,
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );
        $this->ctoCollectionRepository->update($id, $details);

        foreach ($request->counter as $key => $val) {
            $bill = CtoDenominationBill::find($key);
            $exist = $this->ctoCollectionRepository->check_if_details_exist($id, $key);
            if ($exist->count() > 0) {
                $detailsId = $exist->first()->id;
                $details = array(
                    'counter' => ($val != NULL) ? $val : NULL,
                    'amount' => ($val != NULL) ? (floatval($val) * floatval($bill->code)) : NULL,
                    'updated_at' => $this->carbon::now(),
                    'updated_by' => Auth::user()->id
                );
                $this->ctoCollectionRepository->update_details($detailsId, $details);
            } else {
                $details = array(
                    'collection_id' => $id,
                    'denomination_id' => $key,
                    'counter' => ($val != NULL) ? $val : NULL,
                    'amount' => ($val != NULL) ? (floatval($val) * floatval($bill->code)) : NULL,
                    'created_at' => $this->carbon::now(),
                    'created_by' => Auth::user()->id
                );
                $collections_details = $this->ctoCollectionRepository->create_details($details);
            }
        }

        $collectionDetails = array(
            'is_collected' => ($request->officer_id > 0) ? 1 : 0
        );
        $this->ctoCollectionRepository->update_collections($collectionDetails, $request->collections, $id);

        return response()->json([
            'title' => 'Well done!',
            'text' => 'The collection has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function unset(Request $request, $id)
    {   
        $collections = $this->ctoCollectionRepository->find($id);
        $collectionDetails = array(
            'is_collected' => 0
        );
        $this->ctoCollectionRepository->update_collections($collectionDetails, $collections->data_collections, $id);

        return response()->json([
            'title' => 'Well done!',
            'text' => 'The collection has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function find(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->ctoCollectionRepository->get($id)->map(function($trans) {
                return (object) [
                    'fund_code_id' => $trans->fund_code_id,
                    'officer_id' => $trans->officer_id,
                    'transaction_date' => $trans->trans_date,
                    'transaction_no' => $trans->trans_no,
                    'status' => $trans->status,
                    'total_amount' => $trans->total_amount
                ];
            })
        ]);
    }

    public function validate_collection(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read'); 
        $collection = $this->ctoCollectionRepository->find($id);
        return response()->json([
            'validate' => ($collection->trans_date != NULL && $collection->officer_id > 0 && $collection->fund_code_id > 0) ? 1 : 0
        ]);
    }

    public function send(Request $request, $status, $collectionID)
    {   
        $res = $this->ctoCollectionRepository->find($collectionID);
        $timestamp = $this->carbon::now();
        if ($status == 'for-approval' && $res->status == 'draft') {
            $details = array(
                'status' => str_replace('-', ' ', $status),
                'sent_at' => $timestamp,
                'sent_by' => Auth::user()->id
            );
                
            return response()->json([
                'data' => $this->ctoCollectionRepository->update($collectionID, $details),
                'text' => 'The request has been successfully sent.',
                'type' => 'success',
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'res' => $res->status,
                'stats' => $status,
                'status' => 'failed',
                'text' => 'Technical error.',
            ]);
        }
    }

    public function print(Request $request, $transNo)
    {
        $res = $this->ctoCollectionRepository->find_collection_via_column($transNo);
        $fund_code = $res->fund->description; //
        $trans_no = $res->trans_no; //
        $trans_date = Carbon::parse($res->trans_date)->format('F d, Y'); //
        $total_amount = $res->total_amount; //
        $officer = $res->officer->hr_employee->fullname; //

        PDF::SetTitle('Collections And Deposits');    
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('P', 'LEGAL');
        PDF::SetFont('helvetica','',9);

        $border = 0;
        // $cell_height = 5;
        // 185.9 max width

        PDF::MultiCell(0, 0, "<B>REPORT OF COLLECTION AND DEPOSITS</B>", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "PALAYAN CITY", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln();

        PDF::MultiCell(50, 0, "Fund:", $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(60, 0, "<b>".$fund_code."</b>", 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, "Date:", $border, 'R', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "<b>".$trans_date."</b>", 'B', 'C', 0, 1, '', '', true, 0, true);
        
        PDF::MultiCell(50, 0, "Name of Accountable Officer:", $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(60, 0, "<b>".$officer."</b>", 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, "Report No.:", $border, 'R', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "<b>".$trans_no."</b>", 'B', 'C', 0, 1, '', '', true, 0, true);
        PDF::ln();

        PDF::MultiCell(0, 0, "A. COLLECTION", $border, 'L', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(5, 0, "", $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "1. For Collectors", $border, 'L', 0, 1, '', '', true, 0, true);

        $collector_table = '<table id="table-collector" width="100%" cellspacing="0" cellpadding="1" border="0">
                        <tr>
                            <td colspan="1" align="center" width="30%" style="border-top: 0.7px solid black; border-left: 0.7px solid black;">
                                
                            </td>
                            <td colspan="2" align="center" width="40%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                                OFFICIAL RECEIPTS / SERIAL NUMBERS
                            </td>
                            <td colspan="1" align="center" width="30%" style="border-top: 0.7px solid black; border-right: 0.7px solid black;">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="1" align="center" width="30%" style="border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                                (Form No.)
                            </td>
                            <td colspan="1" align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                                From
                            </td>
                            <td colspan="1" align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                                To
                            </td>
                            <td colspan="1" align="center" width="30%" style="border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                                Amount
                            </td>
                        </tr>
                        ';

        $collections = $this->ctoCollectionRepository->get_or_receipts($res);        
        $collection_row = 0;
        foreach ($collections as $collection) {
            $collector_table .= '
            <tr>
                <td colspan="1" align="center" width="30%" style="border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                    <b>'.$collection->form_code.'</b>
                </td>
                <td colspan="1" align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                    <b>'.$collection->or_from.'</b>
                </td>
                <td colspan="1" align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                    <b>'.$collection->or_to.'</b>
                </td>
                <td colspan="1" align="right" width="30%" style="border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                    <b>'. $this->money_formats(floatval($collection->total_amount) - floatval($collection->total_discount)) .'</b>
                </td>
            </tr>';
            $collection_row++;
        }
        while ($collection_row < 7)
            {
                $collector_table .= '
                <tr>
                    <td colspan="1" align="center" width="30%" style="border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        
                    </td>
                    <td colspan="1" align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        
                    </td>
                    <td colspan="1" align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        
                    </td>
                    <td colspan="1" align="center" width="30%" style="border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        
                    </td>
                </tr>';
                $collection_row++;
            }
            $collector_table .= '
                <tr>
                    <td colspan="1" align="center" width="30%" style="border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        TOTAL COLLECTIONS
                    </td>
                    <td colspan="1" align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        
                    </td>
                    <td colspan="1" align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        
                    </td>
                    <td colspan="1" align="center" width="5%" style="border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        P
                    </td>
                    <td colspan="1" align="right" width="25%" style="border-right: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        <b>'.$this->money_formats($total_amount).'</b>
                    </td>
                </tr>';
        
        $collector_table .= '</table>';
        PDF::writeHTML($collector_table, false, false, false, false, '');

        // PDF::MultiCell(185.9, 4, 'B. REMITTANCES / DEPOSIT', 1, 'C', 0, 1, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=4, $valign='M');
        PDF::MultiCell(0, 4, 'B. REMITTANCES / DEPOSIT', 1, 'L', 0, 1, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=4, $valign='M');
        PDF::MultiCell(93, 10, 'Name of Accountable Officer', 'L', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=10, $valign='M');
        
        PDF::MultiCell(46.5, 5, 'Reference', 1, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(0, 5, 'Amount', 1, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::ln();
        PDF::MultiCell(93, 5, ' ', 'B', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');

        PDF::MultiCell(15, 5, 'RCO #', 1, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(31.5, 5, '<b></b>', 1, 'C', 0, 0, '', '', true, $strech=0, $ishtml=true, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(8, 5, '', 'B', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(0, 5, '<b></b>', 'BR', 'R', 0, 0, '', '', true, $strech=0, $ishtml=true, $autopadding=true, $maxh=5, $valign='M');
        PDF::ln();
        $denomination_table = '<table id="table-denomination" width="100%" cellspacing="0" cellpadding="1" border="0">
                        <tr>
                            <td colspan="1" align="center" width="50%" style="border-top: 0.7px solid black; border-left: 0.7px solid black;">
                                Bills
                            </td>
                            <td colspan="1" align="center" width="50%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                                Coins
                            </td>
                        </tr>
                        <tr>
                            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-left: 0.7px solid black;">
                                Denominations
                            </td>
                            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                                No. of Pieces
                            </td>
                            <td colspan="1" align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black;">
                                Amount
                            </td>
                            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-left: 0.7px solid black;">
                                Denominations
                            </td>
                            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                                No. of Pieces
                            </td>
                            <td colspan="1" align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black;">
                                Amount
                            </td>
                        </tr>';
        $bills_height = PDF::gety();


        $denomination_table .= '
        <tr>
            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                '.$this->ctoCollectionRepository->get_denomination_value($res->id, '1000 Bill')->name.'
            </td>
            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                '.$this->ctoCollectionRepository->get_denomination_value($res->id, '1000 Bill')->counter.'
            </td>
            <td colspan="1" align="right" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                <b>'.$this->ctoCollectionRepository->get_denomination_value($res->id, '1000 Bill')->amount.'</b>
            </td>
            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                '.$this->ctoCollectionRepository->get_denomination_value($res->id, '20 Coin')->name.'
            </td>
            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                '.$this->ctoCollectionRepository->get_denomination_value($res->id, '20 Coin')->counter.'
            </td>
            <td colspan="1" align="right" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                <b>'.$this->ctoCollectionRepository->get_denomination_value($res->id, '20 Coin')->amount.'</b>
            </td>
        </tr>
        <tr>
            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                '.$this->ctoCollectionRepository->get_denomination_value($res->id, '500 Bill')->name.'
            </td>
            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                '.$this->ctoCollectionRepository->get_denomination_value($res->id, '500 Bill')->counter.'
            </td>
            <td colspan="1" align="right" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                <b>'.$this->ctoCollectionRepository->get_denomination_value($res->id, '500 Bill')->amount.'</b>
            </td>
            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                '.$this->ctoCollectionRepository->get_denomination_value($res->id, '10 Coin')->name.'
            </td>
            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                '.$this->ctoCollectionRepository->get_denomination_value($res->id, '10 Coin')->counter.'
            </td>
            <td colspan="1" align="right" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                <b>'.$this->ctoCollectionRepository->get_denomination_value($res->id, '10 Coin')->amount.'</b>
            </td>
        </tr>
        <tr>
            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                '.$this->ctoCollectionRepository->get_denomination_value($res->id, '200 Bill')->name.'
            </td>
            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                '.$this->ctoCollectionRepository->get_denomination_value($res->id, '200 Bill')->counter.'
            </td>
            <td colspan="1" align="right" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                <b>'.$this->ctoCollectionRepository->get_denomination_value($res->id, '200 Bill')->amount.'</b>
            </td>
            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                '.$this->ctoCollectionRepository->get_denomination_value($res->id, '5 Coin')->name.'
            </td>
            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                '.$this->ctoCollectionRepository->get_denomination_value($res->id, '5 Coin')->counter.'
            </td>
            <td colspan="1" align="right" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                <b>'.$this->ctoCollectionRepository->get_denomination_value($res->id, '5 Coin')->amount.'</b>
            </td>
        </tr>
        <tr>
            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                '.$this->ctoCollectionRepository->get_denomination_value($res->id, '100 Bill')->name.'
            </td>
            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                '.$this->ctoCollectionRepository->get_denomination_value($res->id, '100 Bill')->counter.'
            </td>
            <td colspan="1" align="right" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                <b>'.$this->ctoCollectionRepository->get_denomination_value($res->id, '100 Bill')->amount.'</b>
            </td>
            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                '.$this->ctoCollectionRepository->get_denomination_value($res->id, '1 Coin')->name.'
            </td>
            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                '.$this->ctoCollectionRepository->get_denomination_value($res->id, '1 Coin')->counter.'
            </td>
            <td colspan="1" align="right" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                <b>'.$this->ctoCollectionRepository->get_denomination_value($res->id, '1 Coin')->amount.'</b>
            </td>
        </tr>
        <tr>
            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                '.$this->ctoCollectionRepository->get_denomination_value($res->id, '50 Bill')->name.'
            </td>
            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                '.$this->ctoCollectionRepository->get_denomination_value($res->id, '50 Bill')->counter.'
            </td>
            <td colspan="1" align="right" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                <b>'.$this->ctoCollectionRepository->get_denomination_value($res->id, '50 Bill')->amount.'</b>
            </td>
            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                '.$this->ctoCollectionRepository->get_denomination_value($res->id, '25 Cent')->name.'
            </td>
            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                '.$this->ctoCollectionRepository->get_denomination_value($res->id, '25 Cent')->counter.'
            </td>
            <td colspan="1" align="right" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                <b>'.$this->ctoCollectionRepository->get_denomination_value($res->id, '25 Cent')->amount.'</b>
            </td>
        </tr>
        <tr>
            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                '.$this->ctoCollectionRepository->get_denomination_value($res->id, '20 Bill')->name.'
            </td>
            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                '.$this->ctoCollectionRepository->get_denomination_value($res->id, '20 Bill')->counter.'
            </td>
            <td colspan="1" align="right" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                <b>'.$this->ctoCollectionRepository->get_denomination_value($res->id, '20 Bill')->amount.'</b>
            </td>
            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                '.$this->ctoCollectionRepository->get_denomination_value($res->id, '5 Cent')->name.'
            </td>
            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                '.$this->ctoCollectionRepository->get_denomination_value($res->id, '5 Cent')->counter.'
            </td>
            <td colspan="1" align="right" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                <b>'.$this->ctoCollectionRepository->get_denomination_value($res->id, '5 Cent')->amount.'</b>
            </td>
        </tr>
        <tr>
            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
            </td>
            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
            </td>
            <td colspan="1" align="right" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
            </td>
            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                '.$this->ctoCollectionRepository->get_denomination_value($res->id, '1 Cent')->name.'
            </td>
            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                '.$this->ctoCollectionRepository->get_denomination_value($res->id, '1 Cent')->counter.'
            </td>
            <td colspan="1" align="right" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                <b>'.$this->ctoCollectionRepository->get_denomination_value($res->id, '1 Cent')->amount.'</b>
            </td>
        </tr>';
        
        $denomination_table .= '
                <tr>
                    <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-left: 0.7px solid black;">
                        
                    </td>
                    <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                        
                    </td>
                    <td colspan="1" align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black;">
                        
                    </td>
                    <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-left: 0.7px solid black;">
                        
                    </td>
                    <td colspan="1" align="Right" width="15%" style="border-top: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        Total Cash
                    </td>
                    <td colspan="1" align="Right" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        <b>'.$this->money_formats($total_amount).'</b>
                    </td>
                </tr>';
        $denomination_table .= '</table>';
        PDF::writeHTML($denomination_table, false, false, false, false, '');

        $bank_table = '<table id="table-bank" width="100%" cellspacing="0" cellpadding="1" border="0">
                <tr>
                    <td colspan="1" align="center" width="40%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        Bank Code
                    </td>
                    <td colspan="1" align="center" width="30%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        Check Number
                    </td>
                    <td colspan="1" align="Center" width="30%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        Amount
                    </td>
                </tr>';
                
            
        $banks = $this->ctoCollectionRepository->get_deposits($res->trans_no);
        $bank_row = 0; $amount = 0;
        foreach ($banks as $bank) {
        $amount += floatval($bank->amount);
        $bank_table .= '
                <tr>
                    <td colspan="1" align="center" width="40%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        <b>'. $bank->bank_account_no .'</b>
                    </td>
                    <td colspan="1" align="center" width="30%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        <b>'. $bank->cheque_no .'</b>
                    </td>
                    <td colspan="1" align="right" width="30%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        <b>'. $this->money_formats($bank->amount) .'</b>
                    </td>
                </tr>';
            $bank_row++;
        }
        $bank_table .= '
                <tr>
                    <td colspan="1" align="center" width="40%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        
                    </td>
                    <td colspan="1" align="center" width="30%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        
                    </td>
                    <td colspan="1" align="Left" width="15%" style="border-top: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        Total Check
                    </td>
                    <td colspan="1" align="Right" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        <b>'.$this->money_formats($amount).'</b>
                    </td>
                </tr>';
        $bank_table .= '</table>';
        PDF::writeHTML($bank_table, false, false, false, false, '');
        $accountability_table = '
                <table id="table-bank" width="100%" cellspacing="0" cellpadding="1" border="0">
                    <tr>
                        <td colspan="1" align="left" width="100%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                            C. ACCOUNTABILITY FOR ACCOUNTABLE FORMS
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                            Beginning Balance
                        </td>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                            Receipt
                        </td>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                            Issued
                        </td>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                            Ending Balance
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                            NAME OF FORMS
                        </td>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                            Qty Serial Number
                        </td>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                            Qty Serial Number
                        </td>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                            Qty Serial Number
                        </td>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                            Qty Serial Number
                        </td>
                    </tr>';
                    
                    $accountable_forms = (object)[
                        // [
                        //     'form_name'=>'AF056, #039',
                        //     'beg_bal'=>'18 9941483-150',
                        //     'receipt'=>'',
                        //     'issued'=>'1 9941483-1483',
                        //     'end_bal'=>'17 9941483-150',
                        // ],
                        // [
                        //     'form_name'=>'AF056, #039',
                        //     'beg_bal'=>'18 9941483-150',
                        //     'receipt'=>'',
                        //     'issued'=>'1 9941483-1483',
                        //     'end_bal'=>'17 9941483-150',
                        // ],
                        // [
                        //     'form_name'=>'AF056, #039',
                        //     'beg_bal'=>'18 9941483-150',
                        //     'receipt'=>'',
                        //     'issued'=>'1 9941483-1483',
                        //     'end_bal'=>'17 9941483-150',
                        // ],
                    ];
                    $accountable_row = 0;
        foreach ($accountable_forms as $accountable_form) {
            $accountability_table .= '
                <tr>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                            '.$accountable_form['form_name'].'
                        </td>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                            '.$accountable_form['beg_bal'].'
                        </td>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                            '.$accountable_form['receipt'].'
                        </td>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                            '.$accountable_form['issued'].'
                        </td>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                            '.$accountable_form['end_bal'].'
                        </td>
                </tr>';
            $accountable_row++;
        }
        while ($accountable_row < 7)
            {
                $accountability_table .= '
                    <tr>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        
                        </td>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        
                        </td>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        
                        </td>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        
                        </td>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        
                        </td>
                    </tr>';
                $accountable_row++;
            }
        $accountability_table .= '</table>';
        PDF::writeHTML($accountability_table, false, false, false, false, '');
        
        $chequeAmount = $this->ctoCollectionRepository->get_deposits_detail($res->trans_no, 'check');
        $cashAmount = $this->ctoCollectionRepository->get_deposits_detail($res->trans_no, 'cash');
        $totalAmt = floatval($chequeAmount) + floatval($cashAmount);
        $summary_table = '
            <table id="table-summary" width="100%" cellspacing="0" cellpadding="1" border="0">
                <tr>
                    <td colspan="1" align="left" width="100%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;">
                        D. ASUMMARY OF COLLECTIONS AND REMITTANCES
                    </td>
                </tr>
                <tr>
                    <td colspan="1" align="left" width="100%" style="border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;">
                        
                    </td>
                </tr>
                <tr>
                    <td colspan="1" rowspan="1" align="Center" width="20%" style="border-left: 0.7px solid black;">
                        
                    </td>
                    <td colspan="1" rowspan="1" align="Right" width="20%">
                        Begginning Balance
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%">
                        P
                    </td>
                    <td colspan="1" rowspan="1" align="Right" width="20%" style="border-bottom: 0.7px solid black;">
                        '.$this->money_formats($total_amount).'                    
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%" style="  border-right: 0.7px solid black;">
                    </td>
                </tr>
                <tr>
                    <td colspan="1" rowspan="1" align="Center" width="20%" style="border-left: 0.7px solid black;">
                        
                    </td>
                    <td colspan="1" rowspan="1" align="Right" width="20%">
                        Add : Collections
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%">
                        
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%">
                    
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%" style="  border-right: 0.7px solid black;">
                    
                    </td>
                </tr>
                <tr>
                    <td colspan="1" rowspan="1" align="Center" width="20%" style="border-left: 0.7px solid black;">
                        
                    </td>
                    <td colspan="1" rowspan="1" align="Right" width="20%">
                        Cash
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%" style="border-bottom: 0.7px solid black;">
                        '.( ($cashAmount > 0) ? $this->money_formats($cashAmount) : '').'
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%">
                    
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%" style="border-right: 0.7px solid black;">
                    
                    </td>
                </tr>
                <tr>
                    <td colspan="1" rowspan="1" align="Center" width="20%" style="border-left: 0.7px solid black;">
                        
                    </td>
                    <td colspan="1" rowspan="1" align="Right" width="20%">
                        Check(s)
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%" style="border-bottom: 0.7px solid black;">
                        '.( ($chequeAmount > 0) ? $this->money_formats($chequeAmount) : '').'
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%">
                    
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%" style="border-right: 0.7px solid black;">
                    
                    </td>
                </tr>
                <tr>
                    <td colspan="1" rowspan="1" align="Center" width="20%" style="border-left: 0.7px solid black;">
                        
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%">
                        Total
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%">
                        
                    </td>
                    <td colspan="1" rowspan="1" align="Right" width="20%" style="border-bottom: 0.7px solid black;">
                        '.$this->money_formats($totalAmt).'
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%" style="border-right: 0.7px solid black;">
                    
                    </td>
                </tr>
                <tr>
                    <td colspan="1" rowspan="1" align="Center" width="60%" style="border-left: 0.7px solid black;">
                        Less : Remittance / Deposit to Cashier
                    </td>
                    <td colspan="1" rowspan="1" align="Right" width="20%" style="border-bottom: 0.7px solid black;">
                        ('.$this->money_formats($totalAmt).')
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%" style="border-right: 0.7px solid black;">
                    
                    </td>
                </tr>
                <tr>
                    <td colspan="1" align="left" width="100%" style="border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;">
                        
                    </td>
                </tr>
                <tr>
                    <td colspan="1" rowspan="1" align="Center" width="20%" style="border-left: 0.7px solid black;">
                        
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%">
                        Balance
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%">
                        P
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%" style="border-bottom: 0.7px solid black;">
                        
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%" style="border-right: 0.7px solid black;">
                    
                    </td>
                </tr>
                <tr>
                    <td colspan="1" align="left" width="100%" style="border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        
                    </td>
                </tr>';
        $summary_table .= '</table>';
        PDF::writeHTML($summary_table, false, false, false, false, '');

        $footer_table = '
            <table id="table-summary" width="100%" cellspacing="0" cellpadding="1" border="0">
                <tr>
                    <td colspan="1" align="left" width="100%" >
                        
                    </td>
                </tr>
                <tr>
                    <td colspan="1" align="center" width="50%" style="font-size: 9px;">
                        CERTIFICATION
                    </td>
                    <td colspan="1" align="center" width="50%" style="font-size: 9px;">
                        VERIFICATION AND ACKNOWLEDGEMENT
                    </td>
                </tr>
                <tr>
                    <td colspan="1" align="left" width="100%" >
                        
                    </td>
                </tr>

                <tr>
                    <td colspan="1" align="left" width="3%" >
                        
                    </td>
                    <td colspan="1" align="right" width="44%" style="font-size: 8px; font-family: "Times New Roman";">
                        I hereby certify that the foregoing report of collections and
                    </td>
                    <td colspan="1" align="left" width="6%" >
                        
                    </td>
                    <td colspan="1" align="left" width="3%" >
                        
                    </td>
                    <td colspan="1" align="right" width="44%" style="font-size: 8px;">
                        I hereby certify that the foregoing report of collection has been
                    </td>
                </tr>

                <tr>
                    <td colspan="1" align="right" width="47%" style="font-size: 8px;">
                        deposits and accountability of accountable forms is true and correct
                    </td>
                    <td colspan="1" align="left" width="6%" >
                        
                    </td>
                    <td colspan="1" align="left" width="25%" style="font-size: 8px;">
                        verify and acknowledge receipt of
                    </td>
                    <td colspan="1" align="left" width="4%" >
                        P
                    </td>
                    <td colspan="1" align="right" width="18%" >
                        '.$this->money_formats($totalAmt).'
                    </td>
                </tr>
                <tr>
                    <td colspan="1" align="right" width="50%" >                        
                    </td>
                    <td colspan="1" align="Center" width="50%" style="font-size: 8px;">
                        <b>'.trim(ucfirst(strtolower(
                            NumberToWords::transformNumber('en', $totalAmt).''.$this->ctoCollectionRepository->numberTowords($totalAmt)))).'</b>
                    </td>
                </tr>
                <tr>
                    <td colspan="1" align="left" width="100%" >
                        
                    </td>
                </tr>
                <tr>
                    <td colspan="1" align="center" width="30%" style="border-bottom: 0.7px solid black; font-size: 8px;">
                        <b>'.$officer.'</b>
                    </td>
                    <td colspan="1" align="left" width="2%" >
                        
                    </td>
                    <td colspan="1" align="center" width="15%" style="border-bottom: 0.7px solid black; font-size: 8px;">
                        <b>'.date('m/d/Y', strtotime($trans_date)).'</b>
                    </td>
                    <td colspan="1" align="left" width="6%">                        
                    </td>

                    <td colspan="1" align="center" width="30%" style="font-size: 8px; border-bottom: 0.7px solid black;">
                        <b>'.$res->approved->hr_employee->fullname.'</b>
                    </td>
                    <td colspan="1" align="left" width="2%" >
                        
                    </td>
                    <td colspan="1" align="center" width="15%" style="border-bottom: 0.7px solid black; font-size: 8px;">
                        <b>'.date('m/d/Y', strtotime($res->approved_at)).'</b>
                    </td>
                </tr>
                <tr>
                    <td colspan="1" align="center" width="30%" style="font-size: 8px;">
                        COLLECTOR
                    </td>
                    <td colspan="1" align="left" width="2%">
                        
                    </td>
                    <td colspan="1" align="center" width="15%" style="font-size: 8px;">
                        DATE
                    </td>
                    <td colspan="1" align="left" width="6%">                        
                    </td>
                    <td colspan="1" align="center" width="30%" style="font-size: 8px;">
                        SIGNATURE CASHIER/TREASURER
                    </td>
                    <td colspan="1" align="left" width="2%">                       
                    </td>
                    <td colspan="1" align="center" width="15%">
                        DATE
                    </td>
                </tr>
                ';
        $footer_table .= '</table>';
        PDF::writeHTML($footer_table, false, false, false, false, '');


        PDF::AddPage('P', 'LEGAL');
        PDF::SetMargins(10, 10, 10,true);    
        $border = 0;
        $cell_height = 5;

        $official = "Susan A. Fajardo";
        $collection_date = Carbon::parse($res->trans_date)->format('F d, Y'); 

        PDF::SetFont('helvetica','',9);
        PDF::MultiCell(0, 0, "OFFICE OF THE CITY TREASURER", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "<B>DAILY COLLECTION REPORT</B>", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, $collection_date, '', 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(10);

        // column header
        PDF::MultiCell(13, 0, "<B>No.</B>", "B", 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(3, 0, "", 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(20, 0, "<B>O.R. No.</B>", "B", 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(3, 0, "", 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(60, 0, "<B>Taxpayer's Name</B>", "B", 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(3, 0, "", 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(60, 0, "<B>Address / Account Description</B>", "B", 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(3, 0, "", 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30.9, 0, "<B>Amount</B>", "B", 'C', 0, 1, '', '', true, 0, true);
        PDF::ln();
        
        $collections = $this->ctoCollectionRepository->get_details($transNo);
        if (!empty($collections)) {
            $rows = 0; $totalAmt = 0;
            foreach ($collections as $collection) {
                $rows++;
                PDF::SetFont('helvetica','B',9);
                PDF::MultiCell(13, 0, $rows, 0, 'C', 0, 0, '', '', true, 0, true);
                PDF::MultiCell(3, 0, "", 0, 'C', 0, 0, '', '', true, 0, true);
                PDF::MultiCell(20, 0, $collection->or_no, 0, 'L', 0, 0, '', '', true, 0, true);
                PDF::MultiCell(3, 0, "", 0, 'C', 0, 0, '', '', true, 0, true);
                PDF::MultiCell(60, 0, ucwords(strtolower($collection->taxpayer_name)), 0, 'L', 0, 0, '', '', true, 0, true);
                PDF::MultiCell(3, 0, "", 0, 'C', 0, 0, '', '', true, 0, true);
                PDF::Cell(60, 0, ($collection->barangay ? strtoupper($collection->barangay->brgy_name) : ''), 0, 0, 'L');
                PDF::SetFont('helvetica','',9);
                PDF::ln();
                $totalBreakdown = 0;
                $breakdowns = $this->ctoCollectionRepository->get_breakdown_details($collection->or_no);
                foreach ($breakdowns as $breakdown) {
                    PDF::MultiCell(102, 0, "", 0, 'L', 0, 0, '', '', true, 0, true);
                    PDF::MultiCell(60, 0, $breakdown->sl_account->description, 0, 'L', 0, 0, '', '', true, 0, true);
                    PDF::MultiCell(3, 0, "", 0, 'C', 0, 0, '', '', true, 0, true);
                    PDF::MultiCell(30.9, 0, number_format($breakdown->amount,2), 0, 'R', 0, 1, '', '', true, 0, true);
                    $totalBreakdown += floatval($breakdown->amount);
                }
                PDF::MultiCell(165, 0, "", 0, 'L', 0, 0, '', '', true, 0, true);
                PDF::MultiCell(30.9, 0, "<b>".number_format($totalBreakdown,2)."</b>", "T", 'R', 0, 0, '', '', true, 0, true);
                PDF::ln();
                $totalAmt += floatval($totalBreakdown);
            }
        }       
        PDF::MultiCell(0, 0, "", "B", 'C', 0, 1, '', '', true, 0, true);
        PDF::ln();
        PDF::MultiCell(50, 0, "No. of Receipts : ".$rows."", "B", 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(90, 0, "Total : ", "B", 'R', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "<b>".number_format($totalAmt,2)."</b>", "B", 'R', 0, 1, '', '', true, 0, true);
        PDF::ln();
        PDF::MultiCell(0, 0, "By:", 0, 'L', 0, 1, '', '', true, 0, true);
        PDF::ln(10);
        PDF::MultiCell(0, 0, $officer, 0, 'L', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "Collector", 0, 'L', 0, 1, '', '', true, 0, true);
        PDF::Output('collection_and_deposits.pdf');
    }
}
