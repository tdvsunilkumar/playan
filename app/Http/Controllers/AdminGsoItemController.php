<?php

namespace App\Http\Controllers;
use App\Models\GsoItem;
use App\Models\GsoItemConversion;
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
use App\Interfaces\GsoItemRepositoryInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AdminGsoItemController extends Controller
{
    private GsoItemRepositoryInterface $gsoItemRepository;
    private $carbon;
    private $slugs;

    public function __construct(GsoItemRepositoryInterface $gsoItemRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->gsoItemRepository = $gsoItemRepository;
        $this->carbon = $carbon;
        $this->slugs = 'general-services/setup-data/item-managements';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $gl_accounts = $this->gsoItemRepository->allGLAccounts();
        $item_categories = $this->gsoItemRepository->allItemCategories();
        $item_types = $this->gsoItemRepository->allItemTypes();
        $pur_types = $this->gsoItemRepository->allPurchaseTypes();
        $unit_of_measurements = $this->gsoItemRepository->allUOMs();
        $canDownload = $this->is_permitted($this->slugs, 'download', 1);
        return view('general-services.setup-data.item-managements.index')->with(compact('canDownload', 'gl_accounts', 'item_categories', 'item_types', 'pur_types', 'unit_of_measurements'));
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
        $result = $this->gsoItemRepository->listItems($request);
        $res = $result->data->map(function($item) use ($statusClass, $actions, $canDelete) {
            if ($canDelete > 0) {
                $actions .= ($item->identityStatus > 0) ? '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-trash text-white"></i></a>' : '<a href="javascript:;" class="action-btn restore-btn bg-info btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-reload text-white"></i></a>';
            }
            $gl_account = ($item->gl_account) ? wordwrap($item->gl_account->code . ' - '. $item->gl_account->description, 25, "\n") : '';
            $type = ($item->type) ? wordwrap($item->type->code . ' - '. $item->type->description, 25, "\n") : '';
            $category = ($item->category) ? wordwrap($item->category->code . ' - '. $item->category->description, 25, "\n") : '';
            $uom = ($item->uom) ? $item->uom->code : '';
            return [
                'id' => $item->identity,
                'gl_account' => '<div class="showLess" title="' . (($item->gl_account) ? $item->gl_account->code . ' - '. $item->gl_account->description : '') . '">' . $gl_account . '</div>',
                'type' => '<div class="showLess" title="' . (($item->type) ? $item->type->code . ' - '. $item->type->description : '') . '">' . $type . '</div>',
                'category' => '<div class="showLess" title="' . (($item->category) ? $item->category->code . ' - '. $item->category->description : ''). '">' . $category . '</div>',
                'code' => $item->identityCode,
                'name' => '<div class="showLess" title="' . $item->identityName . '">' . $item->identityName . '</div>',
                'quantity' => floatval($item->quantity_inventory) + floatval($item->quantity_reserved),
                'uom' => $uom,
                'unit_cost' => '<strong>'.$item->identityWeightedCost. ' / <span class="text-danger">' .$item->identityLatestCost.'</span></strong>',
                'latest_cost' => $item->identityLatestCost,
                'latest_cost_date' => $item->identityLatestCostDate,
                'modified' => ($item->updated_at !== NULL) ? date('d-M-Y', strtotime($item->updated_at)).'<br/>'. date('h:i A', strtotime($item->updated_at)) : date('d-M-Y', strtotime($item->created_at)).'<br/>'. date('h:i A', strtotime($item->created_at)),
                'status' => $statusClass[$item->identityStatus]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$item->identityStatus]->bg. ' p-2">' . $statusClass[$item->identityStatus]->status . '</span>' ,
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
            $actions .= '<a href="javascript:;" class="action-btn download-btn bg-secondary btn m-1 btn-sm align-items-center" title="Download"><i class="ti-download text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-trash text-white"></i></a>';
        }
        $canDelete = $this->is_permitted($this->slugs, 'delete', 1);
        $result = $this->gsoItemRepository->listItemsUpload($request, $id);
        $res = $result->data->map(function($item) use ($statusClass, $actions, $canDelete) {
            $filename = ($item->name) ? wordwrap($item->name, 25, "\n") : '';
            return [
                'id' => $item->id,
                'file' => $item->name,
                'filename' => $filename,
                'type' => $item->type,
                'size' => $this->gsoItemRepository->formatSizeUnits($item->size),
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
        $rows = $this->gsoItemRepository->validate($request->code);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot create an item with an existing code.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }
        
        $timestamp = $this->carbon::now();
        $details = array(
            'gl_account_id' => $request->get('gl_account_id'),
            'item_type_id' => $request->item_type_id,
            'item_category_id' => $request->get('item_category_id'),
            'uom_id' => $request->uom_id,
            'purchase_type_id' => $request->purchase_type_id,
            'code' => $this->gsoItemRepository->generate_item_code($request->get('item_category_id')),
            'name' => $request->name,
            'description' => $request->description,
            'remarks' => $request->remarks,
            'minimum_order_quantity' => ($request->minimum_order_quantity != NULL) ? $request->minimum_order_quantity : 0,
            'life_span' => ($request->life_span != NULL) ? $request->life_span : 0,
            'weighted_cost' => $request->weighted_cost,
            'latest_cost' => $request->latest_cost,
            'latest_cost_date' => date('Y-m-d', strtotime($timestamp)),
            'created_at' => $timestamp,
            'created_by' => Auth::user()->id
        );

        return response()->json(
            [
                'data' => $this->gsoItemRepository->create($details),
                'title' => 'Well done!',
                'text' => 'The item has been successfully saved.',
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
            'data' => $this->gsoItemRepository->find($id),
            'validate' => $this->gsoItemRepository->validate_item($id)
        ]);
    }

    public function update(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update');
        $rows = $this->gsoItemRepository->validate($request->code, $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot update an item with an existing code.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }

        $timestamp = $this->carbon::now();
        $exist = $this->gsoItemRepository->getAllConversion($id);
        if ($exist->count() > 0) {
            $validate = $this->gsoItemRepository->validate_item($id);
            if ($validate > 0) {
                $details = array(
                    'gl_account_id' => $request->get('gl_account_id'),
                    'item_type_id' => $request->item_type_id,
                    'purchase_type_id' => $request->purchase_type_id,
                    'name' => $request->name,
                    'description' => $request->description,
                    'remarks' => $request->remarks,
                    'minimum_order_quantity' => ($request->minimum_order_quantity != NULL) ? $request->minimum_order_quantity : 0,
                    'life_span' => ($request->life_span != NULL) ? $request->life_span : 0,
                    'updated_at' => $timestamp,
                    'updated_by' => Auth::user()->id
                );
            } else {
                $details = array(
                    'gl_account_id' => $request->get('gl_account_id'),
                    'item_type_id' => $request->item_type_id,
                    'purchase_type_id' => $request->purchase_type_id,
                    'name' => $request->name,
                    'description' => $request->description,
                    'remarks' => $request->remarks,
                    'minimum_order_quantity' => ($request->minimum_order_quantity != NULL) ? $request->minimum_order_quantity : 0,
                    'life_span' => ($request->life_span != NULL) ? $request->life_span : 0,
                    'weighted_cost' => $request->weighted_cost,
                    'latest_cost' => $request->latest_cost,
                    'latest_cost_date' => date('Y-m-d', strtotime($timestamp)),
                    'updated_at' => $timestamp,
                    'updated_by' => Auth::user()->id
                );
            }
        } else {
            $validate = $this->gsoItemRepository->validate_item($id);
            if ($validate > 0) {
                $details = array(
                    'gl_account_id' => $request->get('gl_account_id'),
                    'item_type_id' => $request->item_type_id,
                    'uom_id' => $request->uom_id,
                    'purchase_type_id' => $request->purchase_type_id,
                    'name' => $request->name,
                    'description' => $request->description,
                    'remarks' => $request->remarks,
                    'minimum_order_quantity' => ($request->minimum_order_quantity != NULL) ? $request->minimum_order_quantity : 0,
                    'life_span' => ($request->life_span != NULL) ? $request->life_span : 0,
                    'updated_at' => $timestamp,
                    'updated_by' => Auth::user()->id
                );
            } else {
                $details = array(
                    'gl_account_id' => $request->get('gl_account_id'),
                    'item_type_id' => $request->item_type_id,
                    'uom_id' => $request->uom_id,
                    'purchase_type_id' => $request->purchase_type_id,
                    'name' => $request->name,
                    'description' => $request->description,
                    'remarks' => $request->remarks,
                    'minimum_order_quantity' => ($request->minimum_order_quantity != NULL) ? $request->minimum_order_quantity : 0,
                    'life_span' => ($request->life_span != NULL) ? $request->life_span : 0,
                    'weighted_cost' => $request->weighted_cost,
                    'latest_cost' => $request->latest_cost,
                    'latest_cost_date' => date('Y-m-d', strtotime($timestamp)),
                    'updated_at' => $timestamp,
                    'updated_by' => Auth::user()->id
                );
            }
        }

        return response()->json([
            'data' => $this->gsoItemRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The item has been successfully updated.',
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
            'data' => $this->gsoItemRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The item has been successfully removed.',
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
            'data' => $this->gsoItemRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The item has been successfully restored.',
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
                    
                    // audit logs here
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

                    // audit logs here
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
            'data' => $this->gsoItemRepository->delete($request->get('id')),
            'title' => 'Well done!',
            'text' => 'The uploaded file from item has been successfully deleted.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function fetch_gl_via_item_category(Request $request, $item_category)
    {
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->gsoItemRepository->fetch_gl_via_item_category($item_category)
        ]);
    }

    public function generate_item_code(Request $request, $item_category)
    {
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'item_code' => $this->gsoItemRepository->generate_item_code($item_category)
        ]);
    }

    public function fetch_based_uom(Request $request, $item)
    {
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->gsoItemRepository->find($item)
        ]);
    }

    public function conversion_lists(Request $request, $id) 
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
        $result = $this->gsoItemRepository->listItemsConversion($request, $id);
        $res = $result->data->map(function($convert) use ($statusClass, $actions, $canDelete) {
            if ($canDelete > 0) {
                $actions .= ($convert->is_active > 0) ? '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-trash text-white"></i></a>' : '<a href="javascript:;" class="action-btn restore-btn bg-info btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-reload text-white"></i></a>';
            }
            return [
                'id' => $convert->id,
                'based_uom' => $convert->based->code,
                'based_qty' => $convert->based_quantity,
                'conversion_uom' => $convert->conversion->code,
                'conversion_qty' => $convert->conversion_quantity,
                'modified' => ($convert->updated_at !== NULL) ? date('d-M-Y', strtotime($convert->updated_at)).'<br/>'. date('h:i A', strtotime($convert->updated_at)) : date('d-M-Y', strtotime($convert->created_at)).'<br/>'. date('h:i A', strtotime($convert->created_at)),
                'status' => $statusClass[$convert->is_active]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$convert->is_active]->bg. ' p-2">' . $statusClass[$convert->is_active]->status . '</span>' ,
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

    public function store_conversion(Request $request, $id): JsonResponse 
    {       
        $this->is_permitted($this->slugs, 'create');
        $item = $this->gsoItemRepository->find($id);
        $rows = $this->gsoItemRepository->validate_conversion($id, $item->uom_id, $request->conversion_uom);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot create an item conversion with an existing code.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }
        
        $timestamp = $this->carbon::now();
        $details = array(
            'item_id' => $id,
            'based_uom' => $item->uom_id,
            'based_quantity' => $request->based_quantity,
            'conversion_uom' => $request->conversion_uom,
            'conversion_quantity' => $request->conversion_quantity,
            'created_at' => $timestamp,
            'created_by' => Auth::user()->id
        );
        $conversion = $this->gsoItemRepository->create_conversion($details);
        $this->insertLogs([
            'logs' => 'has created a gso item conversion.',
            'details' => GsoItemConversion::find($conversion->id),
            'entity' => 'gso_items_conversions',
            'entity_id' => $conversion->id,
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        ]);

        return response()->json(
            [
                'data' => $conversion,
                'title' => 'Well done!',
                'text' => 'The item conversion has been successfully saved.',
                'type' => 'success',
                'class' => 'btn-brand'
            ],
            Response::HTTP_CREATED
        );
    }

    public function update_conversion(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update');
        $conversion = $this->gsoItemRepository->find_conversion($id);
        $rows = $this->gsoItemRepository->validate_conversion($conversion->item_id, $conversion->based_uom, $request->conversion_uom, $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot update an item with conversion an existing code.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }

        $timestamp = $this->carbon::now();
        $details = array(
            'based_quantity' => $request->based_quantity,
            'conversion_uom' => $request->conversion_uom,
            'conversion_quantity' => $request->conversion_quantity,
            'updated_at' => $timestamp,
            'updated_by' => Auth::user()->id
        );
        $this->gsoItemRepository->update_conversion($id, $details);
        $this->insertLogs([
            'logs' => 'has modified a gso item conversion.',
            'details' => GsoItemConversion::find($id),
            'entity' => 'gso_items_conversions',
            'entity_id' => $id,
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        ]);

        return response()->json([
            'title' => 'Well done!',
            'text' => 'The item conversion has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function find_conversion(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->gsoItemRepository->find_conversion($id)
        ]);
    }

    public function remove_conversion(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'delete');
        $details = array(
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id,
            'is_active' => 0
        );
        $this->gsoItemRepository->update_conversion($id, $details);
        $this->insertLogs([
            'logs' => 'has removed a gso item conversion.',
            'details' => GsoItemConversion::find($id),
            'entity' => 'gso_items_conversions',
            'entity_id' => $id,
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        ]);

        return response()->json([
            'title' => 'Well done!',
            'text' => 'The item conversion has been successfully removed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function restore_conversion(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'delete');
        $details = array(
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id,
            'is_active' => 1
        );
        $this->gsoItemRepository->update_conversion($id, $details);
        $this->insertLogs([
            'logs' => 'has restored a gso item conversion.',
            'details' => GsoItemConversion::find($id),
            'entity' => 'gso_items_conversions',
            'entity_id' => $id,
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        ]);

        return response()->json([
            'title' => 'Well done!',
            'text' => 'The item conversion has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function preload_uom(Request $request, $type)
    {
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'uom' => $this->gsoItemRepository->preload_uom($type),
            'title' => 'Well done!',
            'text' => 'The item conversion has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }
}
