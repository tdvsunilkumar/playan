<?php

namespace App\Http\Controllers;
use App\Models\CommonModelmaster;
use App\Models\HrEmployee; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\EconRentalInterface;
use Carbon\Carbon;
use File;
use Carbon\CarbonPeriod;
use PDF;

class EconRentalApplicationController extends Controller
{
    private EconRentalInterface $econRentalRepository;
    private $carbon;
    private $slugs;

    public function __construct(EconRentalInterface $econRentalRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->econRentalRepository = $econRentalRepository;
        $this->_commonmodel = new CommonModelmaster();
        $this->carbon = $carbon;
        $this->slugs = 'economic-and-investment/rental-application';
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
        $requestor = $this->econRentalRepository->allCitizens();
        $locations = $this->econRentalRepository->allReceptionLocations();
        $services = $this->econRentalRepository->allServices(0);
        $discounts = $this->econRentalRepository->allDiscounts();
        $receptions = ['' => 'select a receptions'];
        $classifications = ['' => 'select a reception class'];
        return view('economic-and-investment.rental.index')->with(compact('discounts', 'permission', 'requestor', 'services', 'locations', 'receptions', 'classifications'));
    }
    
    public function lists(Request $request) 
    { 
        $statusClass = [
            'draft' => (object) ['bg' => 'draft-bg', 'status' => 'draft'],
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'pending'],
            'requested' => (object) ['bg' => 'requested-bg', 'status' => 'requested'],
            'partial' => (object) ['bg' => 'purchased-bg', 'status' => 'partial'],
            'completed' => (object) ['bg' => 'completed-bg', 'status' => 'completed'],
            'cancelled' => (object) ['bg' => 'cancelled-bg', 'status' => 'cancelled'],
        ];
        $actionClass = [
            'draft' => '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="modify this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-pencil text-white"></i></a><a href="javascript:;" class="action-btn send-btn btn-blue btn m-1 btn-sm align-items-center" title="send this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-arrow-right text-white"></i></a>',
            'for approval' => '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>',
            'requested' => '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a><a href="javascript:;" class="action-btn print-btn btn-blue btn m-1 btn-sm align-items-center" title="print this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-printer text-white"></i></a>',
            'partial' => '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a><a href="javascript:;" class="action-btn view-btn2 bg-info btn m-1 btn-sm align-items-center" title="view summary" data-bs-toggle="tooltip" data-bs-placement="top"><i class="la la-file-text text-white"></i></a>',
            'completed' => '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>',
            'cancelled' => '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a><a href="javascript:;" class="action-btn view-disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="view this" aria-label="view this"><i class="ti-comment-alt text-white"></i></a>',
        ];
        $result = $this->econRentalRepository->listItems($request);
        $res = $result->data->map(function($econ) use ($statusClass, $actionClass) {
            $requestor = $econ->requestor ? wordwrap($econ->requestor->cit_fullname, 25, "\n") : '';
            $address = $econ->full_address ? wordwrap($econ->full_address, 25, "\n") : '';
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
                'status' => $statusClass[$econ->status]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$econ->status]->bg. ' p-2">' . $statusClass[$econ->status]->status . '</span>' ,
                'actions' => $actionClass[$econ->status]
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

    public function reload_reception_name(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->econRentalRepository->reload_reception_name($request->get('location'))
        ]);
    }

    public function reload_reception_class(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->econRentalRepository->reload_reception_class($id, $request->get('location'), $request->get('reception'))
        ]);
    }

    public function fetch_multiplier_amount(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->econRentalRepository->fetch_multiplier_amount($request->get('location'), $request->get('reception'), trim($request->get('reception_class')))->first()
        ]);
    }

    public function store(Request $request) 
    {
        $services = $this->econRentalRepository->find_services($request->service_id);
        $details = array(
            'top_transaction_type_id' => $services->top_transaction_type_id,
            'tfoc_id' => $services->tfoc->id,
            'gl_account_id' => $services->tfoc->gl_account_id,
            'sl_account_id' => $services->tfoc->sl_id,
            'requestor_id' => $request->requestor_id,
            'business_employer_name' => $request->business_employer_name,
            'transaction_no' => $this->econRentalRepository->generate(),
            'transaction_date' => date('Y-m-d', strtotime($request->transaction_date)),
            'contact_no' => $request->contact_no,
            'full_address' => $request->full_address,
            'particulars' => $request->particulars,
            'event_title' => $request->event_title,
            'event_start' => date('Y-m-d H:i:s', strtotime($request->event_start)),
            'event_end' => date('Y-m-d H:i:s', strtotime($request->event_end)),
            'location_id' => $request->location_id,
            'reception_id' => $request->reception_id,
            'reception_class_id' => $request->reception_class_id,
            'reception_class_text' => $request->get('reception_class_text'),
            'reception_class_value' => $request->reception_class_value,
            'discount_id' => $request->discount_id,
            'service_id' => $request->service_id,
            'total_amount' => ($request->is_free > 0) ? 0 : $request->get('total_amount'),
            'remaining_amount' => ($request->is_free > 0) ? 0 : $request->get('total_amount'),
            'is_free' => $request->is_free,
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        );

        return response()->json([
            'data' => $this->econRentalRepository->create($details),
            'title' => 'Well done!',
            'text' => 'The rental application has been successfully added.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function update(Request $request, $id) 
    {
        $services = $this->econRentalRepository->find_services($request->service_id);
        $details = array(
            'top_transaction_type_id' => $services->top_transaction_type_id,
            'tfoc_id' => $services->tfoc->id,
            'gl_account_id' => $services->tfoc->gl_account_id,
            'sl_account_id' => $services->tfoc->sl_id,
            'requestor_id' => $request->requestor_id,
            'business_employer_name' => $request->business_employer_name,
            'transaction_date' => date('Y-m-d', strtotime($request->transaction_date)),
            'contact_no' => $request->contact_no,
            'full_address' => $request->full_address,
            'particulars' => $request->particulars,
            'event_title' => $request->event_title,
            'event_start' => date('Y-m-d H:i:s', strtotime($request->event_start)),
            'event_end' => date('Y-m-d H:i:s', strtotime($request->event_end)),
            'location_id' => $request->location_id,
            'reception_id' => $request->reception_id,
            'reception_class_id' => $request->reception_class_id,
            'reception_class_text' => $request->get('reception_class_text'),
            'reception_class_value' => $request->reception_class_value,
            'discount_id' => $request->discount_id,
            'service_id' => $request->service_id,
            'total_amount' => ($request->is_free > 0) ? 0 : $request->get('total_amount'),
            'remaining_amount' => ($request->is_free > 0) ? 0 : $request->get('total_amount'),
            'is_free' => $request->is_free,
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );

        return response()->json([
            'data' => $this->econRentalRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The rental application has been successfully modified.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function fetch_data(Request $request, $citizenID)
    {   
        $key = ($request->get('key') == 'full_address') ? 'cit_full_address' : 'cit_mobile_no';
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->econRentalRepository->find_column($key, $citizenID)
        ]);
    }

    public function find(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->econRentalRepository->find($id)
        ]);
    }

    public function send(Request $request, $status, $appID)
    {   
        $res = $this->econRentalRepository->find($appID);
        $timestamp = $this->carbon::now();
        if ($status == 'for-approval' && $res->status == 'draft') {
            $details = array(
                'status' => str_replace('-', ' ', $status),
                'sent_at' => $timestamp,
                'sent_by' => Auth::user()->id
            );
            return response()->json([
                'data' => $this->econRentalRepository->update($appID, $details),
                'text' => 'The request has been successfully sent.',
                'type' => 'success',
                'requisition' => $appID,
                'status' => 'success',
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'res' => $res->status,
                'stats' => $status,
                'status' => 'failed',
                'text' => 'Technical error.',
            ]);
        }
    }

    public function fetch_status(Request $request, $appID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->econRentalRepository->find($appID)->status
        ]);
    }

    public function fetch_remarks(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'remarks' => $this->econRentalRepository->find($id)->disapproved_remarks
        ]);
    }

    public function fetch_discount(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'discount' => $this->econRentalRepository->fetch_discount($id)->code
        ]);
    }

    public function print(Request $request, $trans)
    {
        // $data = $this->econCemeteryRepository->find($trans);
        $res = $this->econRentalRepository->find_application_via_column($trans);
        // dd($res);
        $date = Carbon::parse($res->transaction_date)->format('F d, Y');;
        $requestor = $res->requestor->cit_fullname;
        $contact = $res->contact_no;
        // $requestor_brgy = $res->requestor->brgy->brgy_name;
        // $requestor_mun = $res->requestor->brgy->municipality->mun_desc;
        $full_address = $res->full_address;
        // $cemetery_brgy = $res->cemetery_location->brgy_name;
        // $cemetery_mun = $res->cemetery_location->municipality->mun_desc;
        $amount_to_pay = $res->total_amount;
        $or_no = ($res->or_no) ? $res->or_no : "";
        $service_name = $res->service_name;
        $service_amount = $res->amount;
        $total_amount = $res->total_amount;
        $officer = strtoupper($res->officer->fullname);
        $officer_designation = strtoupper($res->officer->designation->description);
        // dd($res->officer->designation->description);
        // echo 'Hello Kenneth, I am transaction '. $trans;
        PDF::SetTitle('Rental Application PDF');    
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('P', 'A4');
        
        $palayan_logo1 = public_path('assets/images/issuanceLogo.png');
        PDF::Image( $palayan_logo1, $x = 20, $y = 5, $w = 22, $h = 0, $type = 'PNG');

        $border = 0;
        $line_height = 5;
        PDF::SetFont('helvetica','',9);
        // 190 max width
        PDF::Cell(0,4,'Republic of the Philippines',0,0,'C');
        PDF::ln();
        PDF::Cell(0,4,'Province of Nueva Ecija',0,0,'C');
        PDF::ln();
        PDF::Cell(0,4,'City of Palayan',0,0,'C');
        PDF::ln(10);
        PDF::MultiCell(0, 4, "<B>LOCAL ECONOMIC INVESTMENT PROMOTION OFFICE</B>", 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::ln(10);

        //165
        

        PDF::Cell(43,$line_height,'NAME :',$border,0,'L');
        PDF::Cell(0,$line_height,$requestor,'B',1,'L');

        PDF::Cell(43,$line_height,'ACTIVITY :',$border,0,'L');
        PDF::Cell(0,$line_height,$date,'B',1,'L');

        PDF::Cell(43,$line_height,'DATE/TIME OF ACTIVITY :',$border,0,'L');
        PDF::Cell(0,$line_height,$date,'B',1,'L');

        PDF::Cell(43,$line_height,'ADDRESS :',$border,0,'L');
        PDF::Cell(0,$line_height,$full_address ,'B',1,'L');
        PDF::ln();
        
        PDF::Cell(43,$line_height,'FIRST 3 HOURS :',$border,0,'L');
        PDF::Cell(50,$line_height, '','B',0,'C');
        PDF::Cell(43,$line_height,'P5,000.00',$border,0,'L');
        PDF::ln();

        PDF::Cell(43,$line_height,'SUCCEEDING HOURS :',$border,0,'L');
        PDF::Cell(50,$line_height, '','B',0,'C');
        PDF::Cell(43,$line_height,'P1,500.00',$border,0,'L');
        PDF::ln(10);

        PDF::Cell(43,$line_height,'AMOUNT TO BE PAID :',$border,0,'L');
        PDF::Cell(50,$line_height,$amount_to_pay,'B',1,'C');
        PDF::Cell(43,$line_height,'OR NO. :',$border,0,'L');
        PDF::Cell(50,$line_height,$or_no    ,'B',1,'C');
        PDF::ln(5);

        PDF::Cell(95,$line_height,'',0,0,'L');
        PDF::Cell(95,$line_height,'Approved By:',0,1,'C');
        PDF::ln(15);

        PDF::Cell(10,$line_height,'',0,0,'C');
        PDF::Cell(75,$line_height,strtoupper($requestor),"B",0,'C');
        PDF::Cell(10,$line_height,'',0,0,'C');

        PDF::Cell(10,$line_height,'',0,0,'C');
        PDF::Cell(75,$line_height,$officer,"B",0,'C');
        PDF::Cell(10,$line_height,'',0,0,'C');
        PDF::ln();
        // PDF::Cell(95,$line_height,'',1,1,'C');

        PDF::Cell(95,$line_height,'Signature over printed name',0,0,'C');
        PDF::Cell(95,$line_height,$officer_designation,0,1,'C');

        PDF::ln(10);

        PDF::Cell(0,4,'- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ',$border,1,'L');

        PDF::ln(25);

        $palayan_logo = public_path('assets/images/issuanceLogo.png');
        PDF::Image( $palayan_logo, $x = 20, $y = 165, $w = 22, $h = 0, $type = 'PNG');

        PDF::Cell(0,4,'Republic of the Philippines',0,0,'C');
        PDF::ln();
        PDF::Cell(0,4,'Province of Nueva Ecija',0,0,'C');
        PDF::ln();
        PDF::Cell(0,4,'City of Palayan',0,0,'C');
        PDF::ln(10);

        PDF::SetFont('helvetica','B',9);
        PDF::Cell(0,4,'LOCAL ECONOMIC INVESTMENT PROMOTION OFFICE',0,0,'C');
        PDF::ln();

        PDF::Cell(0,4,'TAX ORDER OF PAYMENT',0,0,'C');
        PDF::ln(15);

        PDF::SetFont('helvetica','',9);
        PDF::Cell(17, $line_height, 'Name:', $border, 0, 'L');
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(154, $line_height, $requestor, 'B', 0, 'L');
        PDF::ln();

        PDF::SetFont('helvetica','',9);
        PDF::ln();

        PDF::SetFont('helvetica','',9);
        PDF::Cell(17, $line_height, 'Address:', $border, 0, 'L');
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(0, $line_height, $full_address, 'B', 0, 'L');
        PDF::ln(10);

        PDF::SetFont('helvetica','',9);
        PDF::Cell(40, $line_height, '', 0, 0, 'R');
        PDF::Cell(20, $line_height, 'Particulars', 0, 0, 'C');
        PDF::Cell(80, $line_height, '', 0, 0, 'R');
        PDF::Cell(20, $line_height, 'Amount', 0, 0, 'C');
        PDF::ln(10);

        PDF::SetFont('helvetica','B',9);
        PDF::Cell(30, $line_height, '', 0, 0, 'R');
        PDF::Cell(20, $line_height, $service_name, 0, 0, 'L');
        PDF::Cell(90, $line_height, '', 0, 0, 'R');
        PDF::Cell(20, $line_height, $total_amount, 0, 0, 'C');
        PDF::ln(10);

        PDF::SetFont('helvetica','',9);
        PDF::Cell(100, $line_height, '', 0, 0, 'R');
        PDF::Cell(40, $line_height, 'Total Amount:', 0, 0, 'L');
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(20, $line_height, $total_amount, 0, 0, 'C');
        PDF::ln(15);

        PDF::Cell(100, $line_height, '', 0, 0, 'R');
        PDF::Cell(40, $line_height, 'Prepared By:', 0, 0, 'L');
        PDF::ln(15);

        PDF::Cell(120, $line_height, '', 0, 0, 'R');
        PDF::Cell(60, $line_height, $officer, 'B', 0, 'C');
        PDF::ln();

        PDF::Cell(120, $line_height, '', 0, 0, 'R');
        PDF::Cell(60, $line_height, $officer_designation, 0, 0, 'C');
        // PDF::Cell(20, 4, '', 0, 0, 'R');

        $style = array(
            'border' => true,
            'vpadding' => 3,
            'hpadding' => 3,
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
        PDF::write2DBarcode($res->transaction->transaction_no, 'QRCODE,H', 185, 190, 15, 15, $style, 'N');
        PDF::ln(1);
        PDF::SetFont('helvetica','',7);
        if ($res->transaction->transaction_no) {
            PDF::MultiCell(0, $line_height, "  ".$res->transaction->transaction_no , $border, 'L', 0, 0, 187, '', true, 0, true);
        }


            //$inspectedId = HrEmployee::where('id', Auth::user()->hr_employee->id)->first();
            $filename = $res->id."-rental_application.pdf";
            $arrSign= $this->_commonmodel->isSignApply('econ_investment_rental_app_prepared_by');
            $isSignVeified = isset($arrSign)?$arrSign->status:0;

            $arrCertified= $this->_commonmodel->isSignApply('econ_investment_rental_app_approved_by');
            $isSignCertified = isset($arrCertified)?$arrCertified->status:0;

            $signType = $this->_commonmodel->getSettingData('sign_settings');
            
            $folder =  public_path().'/uploads/digital_certificates/';
            if(!File::exists($folder)) { 
                File::makeDirectory($folder, 0755, true, true);
            }
            if($signType==2){
               PDF::Output($folder.$filename,'F');
                @chmod($folder.$filename, 0777);
            }
            $arrData['filename'] = $filename;
            $arrData['isMultipleSign'] = 1;
            $arrData['isDisplayPdf'] = 0;
            $arrData['isSavePdf'] = 0;
            
            $varifiedSignature = $this->_commonmodel->getuserSignature($res->officer->user_id);
            $varifiedPath =  public_path().'/uploads/e-signature/'.$varifiedSignature;

            if($isSignVeified==1 && $signType==2){
                if(!empty($varifiedSignature) && File::exists($varifiedPath)){
                    $arrData['isSavePdf'] = 1;
                    $arrData['signerXyPage'] = $arrSign->pos_x.','.$arrSign->pos_y.','.$arrSign->pos_x_end.','.$arrSign->pos_y_end.','.$arrSign->d_page_no;
                    $arrData['signaturePath'] = $varifiedSignature;
                    if($isSignCertified==0 && $signType==2){
                        $arrData['isDisplayPdf'] = 1;
                        return $this->_commonmodel->applyDigitalSignature($arrData);
                    }else{
                        
                         $this->_commonmodel->applyDigitalSignature($arrData);
                    }
                    
                }
            }

            $certifiedSignature = $this->_commonmodel->getuserSignature($res->officer->user_id);
            $certifiedPath =  public_path().'/uploads/e-signature/'.$certifiedSignature;

            if($isSignCertified==1 && $signType==2){
                if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                    $arrData['isSavePdf'] = ($arrData['isSavePdf']==1)?0:1;
                    $arrData['signerXyPage'] = $arrCertified->pos_x.','.$arrCertified->pos_y.','.$arrCertified->pos_x_end.','.$arrCertified->pos_y_end.','.$arrCertified->d_page_no;
                    $arrData['isDisplayPdf'] = 1;
                    $arrData['signaturePath'] = $certifiedSignature;
                    return $this->_commonmodel->applyDigitalSignature($arrData);
                }
            }

            if($isSignCertified==1 && $signType==1){
                // Apply E-sign Here
                if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                   PDF::Image($certifiedPath,$arrCertified->esign_pos_x, $arrCertified->esign_pos_y, $arrCertified->esign_resolution);
                }
            }
            if($isSignVeified==1 && $signType==1){
                // Apply E-sign Here
                if(!empty($varifiedSignature) && File::exists($varifiedPath)){
                   PDF::Image($varifiedPath,$arrSign->esign_pos_x, $arrSign->esign_pos_y, $arrSign->esign_resolution);
                }
            }
            if($signType==2){
                if(File::exists($folder.$filename)) { 
                    File::delete($folder.$filename);
                }
            }
           PDF::Output($filename,"I");
    }

    public function calendar(Request $request)
    {   
        $this->is_permitted('economic-and-investment/calendar', 'read');
        $permission = array(
            'create' => $this->is_permitted('economic-and-investment/calendar', 'create', 1),
            'read' => $this->is_permitted('economic-and-investment/calendar', 'read', 1),
            'update' => $this->is_permitted('economic-and-investment/calendar', 'update', 1),
            'delete' => $this->is_permitted('economic-and-investment/calendar', 'delete', 1),
            'approve' => $this->is_permitted('economic-and-investment/calendar', 'approve', 1),
            'disapprove' => $this->is_permitted('economic-and-investment/calendar', 'disapprove', 1),
            'download' => $this->is_permitted('economic-and-investment/calendar', 'download', 1)
        );
        return view('economic-and-investment.rental.calendar')->with(compact('permission'));
    }

    public function calendar_lists(Request $request)
    {  
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->econRentalRepository->get_calendar()
            ->map(function($event) {
                return (object) [
                    'title' => $event->event_title,
                    'start' => $event->event_start,
                    'end' => $event->event_end,
                    'description' => $event->particulars,
                    'className' => 'm-fc-event--danger m-fc-event--solid-warning'  
                ];
            })
        ]);
    }
}
