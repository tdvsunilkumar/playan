<?php

namespace App\Http\Controllers;
use App\Models\GsoSupplier;
use App\Models\GsoSupplierProductLine;
use App\Models\FileUpload;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File; 
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\GsoSupplierRepositoryInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AdminGsoSupplierController extends Controller
{
    private GsoSupplierRepositoryInterface $gsoSupplierRepository;
    private $carbon;
    private $slugs;

    public function __construct(GsoSupplierRepositoryInterface $gsoSupplierRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->gsoSupplierRepository = $gsoSupplierRepository;
        $this->_commonmodel = new CommonModelmaster();
        $this->carbon = $carbon;
        $this->slugs = 'general-services/setup-data/suppliers';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $timestamp = $this->carbon::now()->format('Y');
        //$barangays = $this->gsoSupplierRepository->allBarangays();
        $barangays = array("select a barngay");
        $product_lines = $this->gsoSupplierRepository->allProductLines();
        $vat = ['' => 'select a vat type', 'Vatable' => 'Vatable', 'Non-Vatable' => 'Non-Vatable'];
        $ewt = $this->gsoSupplierRepository->allEWT();
        $evat = $this->gsoSupplierRepository->allEVAT();
        return view('general-services.setup-data.suppliers.index')->with(compact('barangays', 'product_lines', 'vat', 'ewt', 'evat'));
    }
    
    public function generate_code()
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'code' => $this->gsoSupplierRepository->generate_code()
        ]);
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
        $result = $this->gsoSupplierRepository->listItems($request);
        $res = $result->data->map(function($supplier) use ($statusClass, $actions, $canDelete) {
            $branchName = $supplier->supBranch ? wordwrap($supplier->supBranch, 25, "\n") : '';
            $businessName = $supplier->supBusiness ? wordwrap($supplier->supBusiness, 25, "\n") : '';
            $productLine = $this->gsoSupplierRepository->getProductLine($supplier->supId);
            $productLines = (strlen($productLine) > 0) ? wordwrap($productLine, 25, "\n") : '';
            $address = $supplier->supAddress ?  wordwrap($supplier->supAddress, 25, "\n") : '';
            if ($canDelete > 0) {
                $actions .= ($supplier->supStatus > 0) ? '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm align-items-center" title="remove this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-trash text-white"></i></a>' : '<a href="javascript:;" class="action-btn restore-btn bg-info btn m-1 btn-sm align-items-center" title="restore this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-reload text-white"></i></a>';
            }
            return [
                'id' => $supplier->supId,
                'code' => $supplier->supCode,
                'branch_name' => '<div class="showLess" title="' . $supplier->supBranch . '">' . $branchName . '</div>',
                'business_name' => '<div class="showLess" title="' . $supplier->supBusiness . '">' . $businessName . '</div>',
                'product_lines' => '<div class="showLess" title="' . $productLine . '">' . $productLines . '</div>',
                'contact_no' => $supplier->supTelno . '<br/>[' . $supplier->supMobile.']',
                'address' => '<div class="showLess" title="' . $supplier->supAddress . '">' . $address . '</div>',
                'modified' => ($supplier->supUpdatedAt !== NULL) ? date('d-M-Y', strtotime($supplier->supUpdatedAt)).'<br/>'. date('h:i A', strtotime($supplier->supUpdatedAt)) : date('d-M-Y', strtotime($supplier->supCreatedAt)).'<br/>'. date('h:i A', strtotime($supplier->supCreatedAt)),
                'status' => $statusClass[$supplier->supStatus]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$supplier->supStatus]->bg. ' p-2">' . $statusClass[$supplier->supStatus]->status . '</span>' ,
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

    public function contact_lists(Request $request, $id) 
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
        $result = $this->gsoSupplierRepository->contact_listItems($request, $id);
        $res = $result->data->map(function($contact) use ($statusClass, $actions, $canDelete) {
            $contact_person = $contact->contact_person ? wordwrap($contact->contact_person, 25, "\n") : '';
            if ($canDelete > 0) {
                $actions .= ($contact->is_active > 0) ? '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-trash text-white"></i></a>' : '<a href="javascript:;" class="action-btn restore-btn bg-info btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-reload text-white"></i></a>';
            }
            return [
                'id' => $contact->id,
                'contact' => $contact->contact_person,
                'contact_person' => '<div class="showLess" title="' . $contact->contact_person . '">' . $contact_person . '</div>',
                'contact_tel_no' => $contact->telephone_no,
                'contact_mobile_no' => $contact->mobile_no,
                'contactemail' => $contact->email_address,
                'modified' => ($contact->updated_at !== NULL) ? date('d-M-Y', strtotime($contact->updated_at)).'<br/>'. date('h:i A', strtotime($contact->updated_at)) : date('d-M-Y', strtotime($contact->created_at)).'<br/>'. date('h:i A', strtotime($contact->created_at)),
                'status' => $statusClass[$contact->is_active]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$contact->is_active]->bg. ' p-2">' . $statusClass[$contact->is_active]->status . '</span>' ,
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
    
    public function upload_lists(Request $request, $id) 
    { 
        $statusClass = [
            0 => (object) ['bg' => 'bg-secondary', 'status' => 'Inactive'],
            1 => (object) ['bg' => 'bg-info', 'status' => 'Active'],
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'download', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn download-btn bg-secondary btn m-1 btn-sm align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="download this"><i class="ti-download text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="remove this"><i class="ti-trash text-white"></i></a>';
        }
        $result = $this->gsoSupplierRepository->upload_listItems($request, $id);
        $res = $result->data->map(function($item) use ($statusClass, $actions) {
            $filename = ($item->name) ? wordwrap($item->name, 25, "\n") : '';
            return [
                'id' => $item->id,
                'file' => $item->name,
                'filename' => '<div class="showLess" title="' . $item->name . '">' . $filename . '</div>',
                'type' => $item->type,
                'size' => $this->gsoSupplierRepository->formatSizeUnits($item->size),
                'modified' => ($item->updated_at !== NULL) ? date('d-M-Y', strtotime($item->updated_at)).'<br/>'. date('h:i A', strtotime($item->updated_at)) : date('d-M-Y', strtotime($item->created_at)).'<br/>'. date('h:i A', strtotime($item->created_at)),
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

        $addr = array();
        if ($request->house_lot_no !== NULL) { $addr[] = $request->house_lot_no; }
        if ($request->street_name !== NULL) { $addr[] = $request->street_name; }
        if ($request->subdivision !== NULL) { $addr[] = $request->subdivision; }

        $timestamp = $this->carbon::now();
        $details = array(
            'code' => $this->gsoSupplierRepository->generate_code(),
            'vat_type' => $request->vat_type,
            'ewt_id' => $request->ewt_id,
            'evat_id' => $request->get('evat_id'),
            'barangay_id' => $request->barangay_id,
            'branch_name' => $request->branch_name,
            'business_name' => $request->business_name,
            'house_lot_no' => $request->house_lot_no,
            'street_name' => $request->street_name,
            'subdivision' => $request->subdivision,
            'address' => implode(', ',$addr) .' '. trim($request->get('address')),
            'email_address' => $request->email_address,
            'telephone_no' => $request->telephone_no,
            'mobile_no' => $request->mobile_no,
            'fax_no' => $request->fax_no,
            'tin_no' => $request->tin_no,
            'remarks' => $request->remarks,
            'created_at' => $timestamp,
            'created_by' => Auth::user()->id
        );

        return response()->json(
            [
                'data' => $this->gsoSupplierRepository->create($details, $request->product_line_id, $timestamp, Auth::user()->id),
                'title' => 'Well done!',
                'text' => 'The supplier has been successfully saved.',
                'type' => 'success',
                'class' => 'btn-brand'
            ],
            Response::HTTP_CREATED
        );
    }

    public function find(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        $data = $this->gsoSupplierRepository->find($id);
        foreach ($this->_commonmodel->getBarangay($data->barangay_id)['data'] as $val) {
            $data->barangay_id = "<option value='".$val->id."' selected>".$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region."</option>";
        }
        
        return response()->json([
            'data' => $data,
            'lines' => $this->gsoSupplierRepository->findLines($id),
            'contacts' => $this->gsoSupplierRepository->findContacts($id)
            ->map(function($contact) {
                $status = ($contact->is_active == 1) ? '<span class="badge badge-status rounded-pill bg-info p-2">Active</span>' : '<span class="badge badge-status rounded-pill bg-secondary p-2">Inactive</span>';
                $icon   = ($contact->is_active == 1) ? '<i class="ti-trash"></i>' : '<i class="ti-reload"></i>';
                return (object) [
                    'id' => $contact->id,
                    'contact_person' => $contact->contact_person,
                    'telephone_no' => ($contact->telephone_no !== NULL) ? $contact->telephone_no : '',
                    'mobile_no' => ($contact->mobile_no !== NULL) ? $contact->mobile_no : '',
                    'email_address' => ($contact->email_address !== NULL) ? $contact->email_address : '',
                    'modified' => ($contact->updated_at !== NULL) ? date('d-M-Y', strtotime($contact->updated_at)).'<br/>'. date('h:i A', strtotime($contact->updated_at)) : date('d-M-Y', strtotime($contact->created_at)).'<br/>'. date('h:i A', strtotime($contact->created_at)),
                    'status' => $status,
                    'icon' => $icon
                ];
            })
        ]);
    }

    public function update(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update');

        $addr = array();
        if ($request->house_lot_no !== NULL) { $addr[] = $request->house_lot_no; }
        if ($request->street_name !== NULL) { $addr[] = $request->street_name; }
        if ($request->subdivision !== NULL) { $addr[] = $request->subdivision; }

        $timestamp = $this->carbon::now();
        $details = array(
            'code' => $request->code,
            'vat_type' => $request->vat_type,
            'ewt_id' => $request->ewt_id,
            'evat_id' => $request->get('evat_id'),
            'barangay_id' => $request->barangay_id,
            'branch_name' => $request->branch_name,
            'business_name' => $request->business_name,
            'house_lot_no' => $request->house_lot_no,
            'street_name' => $request->street_name,
            'subdivision' => $request->subdivision,
            'address' => implode(', ',$addr) .' '. trim($request->get('address')),
            'email_address' => $request->email_address,
            'telephone_no' => $request->telephone_no,
            'mobile_no' => $request->mobile_no,
            'fax_no' => $request->fax_no,
            'tin_no' => $request->tin_no,
            'remarks' => $request->remarks,
            'created_at' => $timestamp,
            'created_by' => Auth::user()->id
        );

        return response()->json([
            'data' => $this->gsoSupplierRepository->update($id, $details, $request->product_line_id, $timestamp, Auth::user()->id),
            'title' => 'Well done!',
            'text' => 'The supplier has been successfully updated.',
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
            'data' => $this->gsoSupplierRepository->toggleUpdate($id, $details),
            'title' => 'Well done!',
            'text' => 'The supplier has been successfully removed.',
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
            'data' => $this->gsoSupplierRepository->toggleUpdate($id, $details),
            'title' => 'Well done!',
            'text' => 'The supplier has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function upload(Request $request, $id) 
    { 
        $this->is_permitted($this->slugs, 'upload');
        $timestamp = date('Y-m-d H:i:s');
        $uploaddir = $request->get('category') . '/' . $id;
        Storage::disk('uploads')->makeDirectory($uploaddir);

        foreach($_FILES as $file)
        {   
            if(Storage::put($uploaddir . '/' . $file['name'], (string) file_get_contents($file['tmp_name'])))
            {
                $files[] = $uploaddir . '/'. $file['name'];
                $exist = FileUpload::where(['name' => $file['name'], 'type' => $file['type'], 'category' => $request->get('category'), 'category_id' => $id])->get();
                if ($exist->count() > 0) {
                    $file = FileUpload::find($exist->first()->id);
                    $file->name = $file['name'];
                    $file->type = $file['type'];
                    $file->size = $file['size'];
                    $file->updated_at = $timestamp;
                    $file->updated_by = Auth::user()->id;

                    if (!$file->update()) {
                        throw new NotFoundHttpException();
                    }
                } else {
                    $file = FileUpload::create([
                        "category" => $request->get('category'),
                        "category_id" => $id,
                        "name" => $file['name'],
                        "type" => $file['type'],
                        "size" => $file['size'],
                        'created_at' => $timestamp,
                        'created_by' => Auth::user()->id
                    ]);

                    if(!$file) {
                        throw new NotFoundHttpException();
                    }
                }
            }
        }

        $data = array(
            'files' => $files,
            'message' => 'success'
        );

        echo json_encode( $data );

        exit();
    }
    
    public function download(Request $request, $id)
    {   
        $this->is_permitted($this->slugs, 'download');
        return response()->download(public_path('uploads/'.$request->get('category').'/'.$id.'/'.$request->get('file')));
    }

    public function delete(Request $request, $id)
    {   
        $this->is_permitted($this->slugs, 'remove');
        File::delete(public_path('uploads/'.$request->get('category').'/'.$id.'/'.$request->get('file')));
        return response()->json([
            'data' => $this->gsoSupplierRepository->delete($request->get('id')),
            'title' => 'Well done!',
            'text' => 'The uploaded file from item has been successfully deleted.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function storeContactPerson(Request $request, $supplierId): JsonResponse 
    {       
        $this->is_permitted($this->slugs, 'create');
        $timestamp = $this->carbon::now();

        $rows = $this->gsoSupplierRepository->validateContactPerson($supplierId, $request->contact_person);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot create a contact details with an existing person.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }

        $details = array(
            'supplier_id' => $supplierId,
            'contact_person' => $request->contact_person,
            'telephone_no' => $request->telephone_no,
            'mobile_no' => $request->mobile_no,
            'email_address' => $request->email_address,
            'created_at' => $timestamp,
            'created_by' => Auth::user()->id
        );

        return response()->json(
            [
                'data' => $this->gsoSupplierRepository->createContactPerson($details),
                'title' => 'Well done!',
                'text' => 'The contact details has been successfully saved.',
                'type' => 'success',
                'class' => 'btn-brand',
                'status' =>  '<span class="badge badge-status rounded-pill bg-info p-2">Active</span>',
                'modified_at' => date('d-M-Y', strtotime($timestamp)).'<br/>'. date('h:i A', strtotime($timestamp))
            ],
            Response::HTTP_CREATED
        );
    }

    public function findContactPerson(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->gsoSupplierRepository->findContactPerson($id)
        ]);
    }

    public function updateContactPerson(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update');
        $timestamp = $this->carbon::now();
        
        $rows = $this->gsoSupplierRepository->validateContactPerson($this->gsoSupplierRepository->findContactPerson($id)->supplier_id, $request->contact_person, $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot update a contact details with an existing person.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }

        $details = array(
            'contact_person' => $request->contact_person,
            'telephone_no' => $request->telephone_no,
            'mobile_no' => $request->mobile_no,
            'email_address' => $request->email_address,
            'updated_at' => $timestamp,
            'updated_by' => Auth::user()->id
        );
        
        return response()->json([
            'data' => $this->gsoSupplierRepository->updateContactPerson($id, $details),
            'title' => 'Well done!',
            'text' => 'The contact details has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand',
            'status' => ($this->gsoSupplierRepository->findContactPerson($id)->is_active > 0) ? '<span class="badge badge-status rounded-pill bg-info p-2">Active</span>' : '<span class="badge badge-status rounded-pill bg-secondary p-2">Inactive</span>',
            'modified_at' => date('d-M-Y', strtotime($timestamp)).'<br/>'. date('h:i A', strtotime($timestamp))
        ]);
    }

    public function removeContactPerson(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'delete');
        $details = array(
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id,
            'is_active' => 0
        );

        return response()->json([
            'data' => $this->gsoSupplierRepository->updateContactPerson($id, $details),
            'title' => 'Well done!',
            'text' => 'The contact details has been successfully removed.',
            'type' => 'success',
            'class' => 'btn-brand',
            'icon' => '<i class="ti-reload"></i>',
            'status' => '<span class="badge badge-status rounded-pill bg-secondary p-2">Inactive</span>'
        ]);
    }

    public function restoreContactPerson(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'delete');
        $details = array(
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id,
            'is_active' => 1
        );

        return response()->json([
            'data' => $this->gsoSupplierRepository->updateContactPerson($id, $details),
            'title' => 'Well done!',
            'text' => 'The contact details has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand',
            'icon' => '<i class="ti-trash"></i>',
            'status' => '<span class="badge badge-status rounded-pill bg-info p-2">Active</span>'
        ]);
    }
}
