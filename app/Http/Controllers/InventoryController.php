<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HoInventoryCategory;
use App\Models\HoInventoryPosting;
use Carbon\Carbon;
use DB;

class InventoryController extends Controller
{

    public function __construct(){
        $this->__inventoryCategory = new HoInventoryCategory();  
        $this->__inventory = new HoInventoryPosting();  
        $this->slugs = 'Medicine-supplies-inventory';
    }

    public function index(){
        try {
            return view('inventory.index');
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

    public function store(){
        try {
            $gso_issuances = $this->__inventory->getControlNumber();
            $control_numbers = [
                '' => 'Select Control Number'
            ];
            foreach ($gso_issuances as $key => $value) {
                $verifyControl = $this->__inventory->verifyControlNumber($value->control_no);
                // if($verifyControl < 1){
                //     $control_numbers[$value->control_no] = $value->control_no;
                // }
                $control_numbers[$value->control_no] = $value->control_no;
            }

            $gso_suppliers = DB::table('gso_suppliers')->get();
            $suppliers = [
                '' => 'Select Supplier'
            ];
            foreach ($gso_suppliers as $key => $value) {
                $suppliers[$value->id] = ucwords($value->business_name);
            }

            $categories_inv = DB::table('ho_inventory_category')->get();
            $categories = [
                '' => 'Select Category'
            ];
            foreach ($categories_inv as $key => $value) {
                $categories[$value->id] = ucwords($value->inv_category);
            }
            
            return view('inventory.create', compact('control_numbers', 'suppliers', 'categories'));
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

    public function getItemDetailsByControl(request $request){
        try {
            $control_number = $request->control_number;
            $categories = DB::table('ho_inventory_category')->get();
            $item_details = DB::table('gso_issuances')
                            ->where('gso_issuances.control_no', $control_number)
                            ->join('gso_issuances_details', 'gso_issuances_details.issuance_id', 'gso_issuances.id')
                            ->join('gso_items', 'gso_issuances_details.item_id', 'gso_items.id')
                            ->join('gso_unit_of_measurements', 'gso_issuances_details.uom_id', 'gso_unit_of_measurements.id')
                            ->select('gso_items.id AS item_id',
                                     'gso_items.is_expirable AS is_expirable', 
                                     'gso_items.name AS item_name', 
                                     'gso_items.code AS item_code', 
                                     'gso_items.weighted_cost AS unit_cost', 
                                     'gso_items.description AS item_description', 
                                     'gso_unit_of_measurements.code AS uom_code',
                                     'gso_unit_of_measurements.id AS uom_id',
                                     'gso_issuances_details.quantity AS qty',
                                     'gso_issuances.control_no'
                            )
                            ->get();

            $supplier_details = DB::table('gso_issuances')
            ->where('gso_issuances.control_no', $control_number)
            ->join('gso_purchase_orders', 'gso_issuances.purchase_order_id', 'gso_purchase_orders.id')
            ->join('gso_suppliers', 'gso_purchase_orders.supplier_id', 'gso_suppliers.id')
            ->select('gso_suppliers.id AS supplier_id', 
                        'gso_suppliers.business_name AS supplier_name'
            )
            ->first();
            
            $html = '';
            $readonly = '';
            if($request->receive_type == 1){
                $readonly = 'readonly';
            }
            
            foreach ($item_details as $key => $value) {
                if($value->is_expirable == 1){
                    $expirable = 'Yes';
                    $required = 'required';
                }else{
                    $expirable = 'No';
                    $required = '';
                }
                $html.= '<div class="row mt-1 int-row-'.$key.'">
                    <div class="col-md-1 text-center" style="font-size:10px">
                        '.($key+1).'
                    </div>
                    <div class="col-md-2 text-center" style="font-size:10px">
                        <input type="text" 
                        style="padding-right: 6px;" 
                        class="form-control" 
                        '.$readonly.' 
                        value="'.$value->item_name.'"> 
                        <input type="hidden" name="items['.$key.'][item_id]" value="'.$value->item_id.'">
                        <input type="hidden" name="items['.$key.'][item_name]" value="'.$value->item_name.'">
                        <input type="hidden" name="items['.$key.'][item_code]" value="'.$value->item_code.'">
                        <input type="hidden" name="items['.$key.'][uom_code]" value="'.$value->uom_id.'">

                    </div>
                    <div class="col-md-1" style="font-size:10px">
                        <select class="form-control cat" name="items['.$key.'][category]">
                            <option value="">Category</option>';
                            foreach ($categories as $category) {
                                $html.= '<option value="'. $category->id .'">'. ucwords($category->inv_category) .'</option>';
                            }
                        $html.= '</select>
                    </div>
                    <div class="col-md-2 text-center" style="font-size:10px">
                        <input type="text" 
                            style="padding-right: 6px;" 
                            onkeyup="updateCost('.$key.')"
                            '.$readonly.'
                            name="items['.$key.'][item_quantity]" 
                            class="form-control quantity'.$key.'" 
                            value="'.$value->qty.'">
                            <span class="validation-error-'.$key.'" style="display:none;color:red;">Breakdown quantity must be equal to Received quantity</span>
                    </div>
                    <div class="col-md-1 text-center" style="font-size:10px"> 
                        <input type="text" 
                        style="padding-right: 6px;" 
                        '.$readonly.' 
                        name="items['.$key.'][uom]" 
                        class="form-control" 
                        value="'.$value->uom_code.'"> 
                    </div>
                    <div class="col-md-1 text-center" style="font-size:10px">
                        <input type="date" 
                        style="padding-right: 6px;" 
                        name="items['.$key.'][expiry_date]" 
                        class="form-control expiry-'.$key.'" 
                        '.$required.'
                        value=""> 
                    </div>
                    <div class="col-md-1 text-center" style="font-size:10px">
                        <input type="text" 
                        style="padding-right: 6px;"
                        '.$readonly.'
                        class="form-control" 
                        value="'.$expirable.'"> 
                    </div>
                    <div class="col-md-1 text-center" style="font-size:10px">
                        <input type="text" 
                        style="padding-right: 6px;" 
                        onkeyup="updateCost('.$key.')" 
                        '.$readonly.' 
                        name="items['.$key.'][unit_cost]" 
                        class="form-control unit_cost'.$key.'" 
                        value="'.$value->unit_cost.'"> 
                    </div>
                    <div class="col-md-1 text-center" style="font-size:10px">
                        <input type="text" 
                        style="padding-right: 6px;" 
                        readonly 
                        name="items['.$key.'][total_cost]" 
                        class="form-control" 
                        value="'.($value->unit_cost * $value->qty).'"> 
                    </div>';
                    if($value->is_expirable == 1){
                        $html.='
                        <div class="col-md-1 text-center" style="font-size:10px">
                            <a type="button" 
                                onclick="addBreakDown('.$value->item_id.', '.$control_number.', '.$key.')" 
                                class="btn btn-sm btn-primary">
                                <i class="ti-plus"></i>
                            </a>
                        </div>';
                    }
                    $html.='</div>';
            }
            return response()->json(['status' => 200, 'data' => ['item_details' => $html, 'supplier' => $supplier_details]]);
        } catch (\Exception $e) {
            return ($e->getMessage());
        } 
    }

    public function getSingleItemDetails(request $request,$item_id){
        try {
            $brk_down_current_index = $request->brk_down_current_index;
            $key = $request->key;
            $readonly = 'readonly';
            $categories = DB::table('ho_inventory_category')->get();
            $item_details = DB::table('gso_issuances')
            ->whereRaw('CAST(gso_issuances.control_no AS SIGNED) = ?', $request->control_number)
            ->where('gso_items.id', $item_id)
            ->join('gso_issuances_details', 'gso_issuances_details.issuance_id', 'gso_issuances.id')
            ->join('gso_items', 'gso_issuances_details.item_id', 'gso_items.id')
            ->join('gso_unit_of_measurements', 'gso_issuances_details.uom_id', 'gso_unit_of_measurements.id')
            ->select('gso_items.id AS item_id',
                     'gso_items.is_expirable AS is_expirable', 
                     'gso_items.name AS item_name', 
                     'gso_items.code AS item_code', 
                     'gso_items.weighted_cost AS unit_cost', 
                     'gso_items.description AS item_description', 
                     'gso_unit_of_measurements.code AS uom_code',
                     'gso_unit_of_measurements.id AS uom_id',
                     'gso_issuances_details.quantity AS qty',
                     'gso_issuances.control_no'
            )
            ->first();

            $html='<div class="row mt-1 brk-'.$brk_down_current_index.'-'.$key.'">
                    <div class="col-md-1">
                                
                    </div>
                    <div class="col-md-2 text-center">
                        <input type="hidden" name="items['.$key.'][breakdown]['.$brk_down_current_index.'][item_id]" value="'.$item_details->item_id.'">
                        <input type="hidden" name="items['.$key.'][breakdown]['.$brk_down_current_index.'][item_name]" value="'.$item_details->item_name.'">
                        <input type="hidden" name="items['.$key.'][breakdown]['.$brk_down_current_index.'][item_code]" value="'.$item_details->item_code.'">
                        <input type="hidden" name="items['.$key.'][breakdown]['.$brk_down_current_index.'][uom_code]" value="'.$item_details->uom_id.'">
                    </div>
                    <div class="col-md-1">
                        
                    </div>
                    <div class="col-md-2 text-center" style="font-size:10px">
                        <input type="text" 
                            style="padding-right: 6px;"
                            name="items['.$key.'][breakdown]['.$brk_down_current_index.'][item_quantity]" 
                            class="form-control quantity'.$key.' brk-qty-'.$key.' brk-key-'.$key.'-'.$brk_down_current_index.'" 
                            value="0" onkeyup="updateBreakDownQuantity('.$brk_down_current_index.', '.$key.', '.$item_details->qty.')">
                    </div>
                    <div class="col-md-1 text-center" style="font-size:10px"> 
                        <input type="text" 
                        style="padding-right: 6px;" 
                        '.$readonly.' 
                        name="items['.$key.'][breakdown]['.$brk_down_current_index.'][uom]" 
                        class="form-control" 
                        value="'.$item_details->uom_code.'"> 
                    </div>
                    <div class="col-md-1 text-center" style="font-size:10px">
                        <input type="date" 
                        style="padding-right: 6px;" 
                        name="items['.$key.'][breakdown]['.$brk_down_current_index.'][expiry_date]" 
                        class="form-control" 
                        required
                        value=""> 
                    </div>
                    <div class="col-md-1 text-center" style="font-size:10px">
                        <input type="text" 
                        style="padding-right: 6px;"
                        '.$readonly.'
                        class="form-control" 
                        value="Yes"> 
                    </div>
                    <div class="col-md-1 text-center" style="font-size:10px">
                        <input type="text" 
                        style="padding-right: 6px;" 
                        onkeyup="updateCost('.$key.')" 
                        '.$readonly.' 
                        name="items['.$key.'][breakdown]['.$brk_down_current_index.'][unit_cost]" 
                        class="form-control unit_cost'.$key.'" 
                        value="'.$item_details->unit_cost.'"> 
                    </div>
                    <div class="col-md-1 text-center" style="font-size:10px">
                        <input type="text" 
                        style="padding-right: 6px;"
                        readonly 
                        name="items['.$key.'][breakdown]['.$brk_down_current_index.'][total_cost]" 
                        class="form-control total-key-'.$key.'-brkkey-'.$brk_down_current_index.'"
                        value="0"> 
                    </div>';
                    if($item_details->is_expirable == 1){
                        $html.='
                        <div class="col-md-1 text-center" style="font-size:10px">
                            <a type="button" 
                                onclick="removeBreakDown('.$key.','.$brk_down_current_index.', '.$item_details->qty.')" 
                                class="btn btn-danger btn-sm text-white">
                                <i class="ti-trash"></i>
                            </a>
                        </div>';
                    }
                    $html.='</div>';
            return response()->json(['status' => 200, 'data' => $html, 'key' => $key]);
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }
    
    public function InventoryCategory(){
        try {
            return view('inventory.category_index');
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

    public function InventoryCategoryStore(){
        try {
            return view('inventory.category_create');
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'inv_category'=>'required',
            ],[
                'inv_category.required'=>'Category is required',
            ]
        );
        $arr=array('ESTATUS'=>0);
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['ESTATUS'] = 1;
        }
        echo json_encode($arr);exit;
    }

    public function InventoryformValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'cip_receiving'=>'required',
                // 'control_number'=>'required',
                'category_id'=>'required',
                'supplier_id'=>'required',
                'remarks' => 'required'
            ]
        );
        $arr=array('ESTATUS'=>0);
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['ESTATUS'] = 1;
        }
        echo json_encode($arr);exit;
    }

    public function addInventoryCategory(request $request){
        try {
            $data = [
                'inv_category' => $request->inv_category,
                'cat_is_active' => 1,
            ];
            $this->__inventoryCategory->addCategory($data);
            return redirect()->route('healthy-and-safety/setup-data/inventory-category')->with('success', __('Inventory Category successfully added.'));
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

    public function InventoryCategoryGetList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->__inventoryCategory->getCategoryList($request);
        $arr=array();
        $i="0";
        $sr_no=(int)$request->input('start')-1;
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a href="#" 
                        class="mx-3 btn btn-sm  align-items-center" 
                        data-url="'.url('/inventory-category/edit/'.$row->id).'" 
                        data-ajax-popup="true"  data-size="lg" 
                        data-bs-toggle="tooltip" 
                        title="Edit"  data-title="Inventory Category">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->cat_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';
            }
            $arr[$i]['srno']= $sr_no;
			$arr[$i]['inv_category']= $row->inv_category;
			$arr[$i]['cat_is_active']= ($row->cat_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']= $actions;
           
            $i++;
        }
        $totalRecords=$data['data_cnt'];
        $json_data = array(
            "recordsTotal"    => intval( $totalRecords ),  
            "recordsFiltered" => intval($totalRecords),
            "data"            => $arr   // total data array
        );
        echo json_encode($json_data);
    }
    
    public function InventoryCategoryEdit($id){
        try {
            $category = $this->__inventoryCategory->getSingleCategory($id);
            return view('inventory.category_edit', compact('category'));
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }
    
    public function InventoryCategoryUpdate(request $request){
        try {
            $data = [
                'inv_category' => $request->inv_category
            ];
            $this->__inventoryCategory->updateCategory($request->id, $data);
            return redirect()->route('healthy-and-safety/setup-data/inventory-category')->with('success', __('Inventory Category successfully updated.'));
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

    public function ActiveInactiveCategory(Request $request){
        try {
            $this->is_permitted($this->slugs, 'delete');
            $id = $request->input('id');
            $is_activeinactive = $request->input('is_activeinactive');
            $data=array('cat_is_active' => $is_activeinactive);
            $this->__inventoryCategory->updateActiveInactive($id,$data);
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

    public function addInventory(request $request){    
        try {
            // return $request->all();
            $request->cip_status = 0;
            if($request->submit == 'submit'){
                $request->cip_status = 1;
            }
            if($request->cip_receiving == 1){
                foreach ($request->items as $key => $value) {
                    // Conversion section
                    $base_uom = 0;
                    $based_quantity = 0;
                    $cip_qty_posted = $value['item_quantity']; 
                    $cip_unit_cost = $value['unit_cost'];
                    $cip_total_cost = $value['total_cost'];
                    $base_unit_cost = $value['unit_cost'];
                    $base_total_cost = $value['total_cost'];

                    $gso_item = DB::table('gso_items')->where('id', $value['item_id'])->first();
                    if($gso_item){
                        $base_uom = $gso_item->uom_id;
                    }
                    $conversion = DB::table('gso_items_conversions')
                    ->where('item_id', $value['item_id'])
                    ->where('conversion_uom', $value['uom_code'])
                    ->first();

                    if($conversion){
                        $based_quantity = $conversion->based_quantity;
                    }
                    
                    if($value['uom_code'] !== $base_uom){
                        $cip_qty_posted = $value['item_quantity'] * $based_quantity;
                        $base_unit_cost = $value['unit_cost'] / $based_quantity;
                        $base_total_cost = $cip_qty_posted * $cip_unit_cost;
                    }
                    // This was only for testing
                    // $data = [ 
                    //     'cip_qty_posted' => $cip_qty_posted,
                    //     'base_unit_cost' => $base_unit_cost,
                    //     'base_total_cost' => $base_total_cost
                    // ]; 
                    // if($key == 1){
                    //     return $data;
                    // }
                    $is_parent = 0;
                    $break_downs = [];
                    if(isset($value['breakdown'])){
                        $break_downs = $value['breakdown'];
                        $is_parent = 1;
                    }

                    $data = [
                        'item_id' => $value['item_id'],
                        'inv_cat_id' => $value['category'], 
                        'sup_id' => $request->supplier_id,
                        'cip_receiving' => $request->cip_receiving,
                        'cip_control_no' => $request->control_number,
                        'cip_date_received' => $request->cip_date_received,
                        'cip_item_code' => $value['item_code'],
                        'cip_item_name' => $value['item_name'],
                        'current_qty' => $value['item_quantity'],
                        'cip_qty_posted' => $cip_qty_posted,
                        'cip_unit_cost' => $cip_unit_cost,
                        'cip_total_cost' => $cip_total_cost,
                        'base_unit_cost' => $base_unit_cost,
                        'base_total_cost' => $base_total_cost,
                        'cip_issued_qty' => 0,
                        'cip_balance_qty' => $cip_qty_posted,
                        'cip_adjust_qty' => 0,
                        'cip_uom' => $value['uom_code'],
                        'cip_expiry_date' => $value['expiry_date'],
                        'cip_status' => $request->cip_status,
                        'cip_remarks' => $request->remarks,
                        'is_parent' => $is_parent,
                        'created_by' => 1,
                        'updated_by' => 1,
                        'civ_is_active' => 1
                    ];
                    $this->__inventory->addInv($data, $break_downs);
                }
            }else{
                $control_number = "00001";
                $last_inventory = $this->__inventory->getMaxExternalRow();

                if($last_inventory != ""){
                    $control_number = sprintf('%05d', intval($last_inventory) + 1);
                }
                foreach ($request->items_external as $key => $value) {
                    $data = [
                        'item_id' => $value['item_id'],
                        'inv_cat_id' => $value['category'],
                        'sup_id' => $request->supplier_id,
                        'cip_receiving' => $request->cip_receiving,
                        'cip_control_no' => $control_number,
                        'cip_date_received' => $request->cip_date_received,
                        'cip_item_code' => $value['item_code'],
                        'cip_item_name' => $value['item_name'],
                        'cip_unit_cost' => $value['unit_cost'],
                        'cip_total_cost' => $value['total_cost'],
                        'cip_qty_posted' => $value['item_quantity'] == null ? 0 : $value['item_quantity'],
                        'cip_issued_qty' => 0,
                        'cip_balance_qty' => $value['item_quantity'] == null ? 0 : $value['item_quantity'],
                        'cip_adjust_qty' => 0,
                        'cip_uom' => $value['uom_code'],
                        'cip_expiry_date' => $value['expiry_date'],
                        'cip_status' => $request->cip_status,
                        'cip_remarks' => $request->remarks,
                        'is_parent' => 0,
                        'created_by' => 1,
                        'updated_by' => 1,
                        'civ_is_active' => 1
                    ];
                    $this->__inventory->addInv($data, array());
                }
            }
            return redirect()->route('Medicine-supplies-inventory')->with('success', __('Inventory successfully added.'));
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

    public function editInventory($id){
        try {
            $gso_units = $this->__inventory->getUnitList();
            $category_items = DB::table('ho_inventory_category')->get();
            $inventory = $this->__inventory->getSingleInventory($id);
            $inventories = $this->__inventory->getInventories($inventory->cip_receiving, $inventory->cip_control_no);
            
            // Setting Up the structure for breakdown rows...
            // For Internal Receiving Creating the Breakdown Structure
            if($inventory->cip_receiving == 1){
                $parent_qty = 0;
                foreach ($inventories as $key => $value) {
                    // if($value->is_parent == 1){
                    //     $chil_ids = $this->__inventory->getChildInventories($inventory->cip_receiving, $inventory->cip_control_no, $value->id);
                    //     $value->children = $chil_ids;
                    // }
                    $chil_ids = $this->__inventory->getChildInventories($value->id);
                    $value->children = $chil_ids;
                }
            }

            $gso_issuances = DB::table('gso_issuances')->select('id', 'control_no')->get();
            $control_numbers = [
                '' => 'Select Control Number'
            ];
            foreach ($gso_issuances as $key => $value) {
                $control_numbers[$value->control_no] = $value->control_no;
            }

            $gso_suppliers = DB::table('gso_suppliers')->select('id', 'business_name')->get();
            $suppliers = [
                '' => 'Select Supplier'
            ];
            foreach ($gso_suppliers as $key => $value) {
                $suppliers[$value->id] = ucwords($value->business_name);
            }

            $categories_inv = DB::table('ho_inventory_category')->select('id', 'inv_category')->get();
            $categories = [
                '' => 'Select Category'
            ];
            foreach ($categories_inv as $key => $value) {
                $categories[$value->id] = ucwords($value->inv_category);
            }
            // This is for external
            $item_details = DB::table('gso_issuances')
                            ->join('gso_issuances_details', 'gso_issuances_details.issuance_id', 'gso_issuances.id')
                            ->rightJoin('gso_items', 'gso_issuances_details.item_id', 'gso_items.id')
                            ->join('gso_unit_of_measurements', 'gso_items.uom_id', 'gso_unit_of_measurements.id')
                            ->select('gso_items.id AS item_id', 
                                     'gso_items.name AS item_name', 
                                     'gso_items.code AS item_code', 
                                     'gso_items.weighted_cost AS unit_cost', 
                                     'gso_items.description AS item_description', 
                                     'gso_unit_of_measurements.code AS uom_code',
                                     'gso_unit_of_measurements.id AS uom_id',
                                     'gso_issuances_details.quantity AS qty'
                            )
                            ->get();
            return view('inventory.edit', compact('category_items', 'gso_units', 'control_numbers', 'suppliers', 'categories', 'inventory', 'inventories', 'item_details'));
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

    public function getBreakDowns(request $request){
        $inventory = $this->__inventory->getSingleInventory($request->id);
        $inventories = $this->__inventory->getInventories($inventory->cip_receiving, $inventory->cip_control_no);
        $brk_array = [];
        // Setting Up the structure for breakdown rows...
        // For Internal Receiving Creating the Breakdown Structure
        $parent_qty = 0;
        foreach ($inventories as $key => $value) {
            $chil_ids = $this->__inventory->getChildInventories($value->id);
            if(count($chil_ids) > 0){
                $value->children = $chil_ids;
                $totalQuantity = collect($chil_ids)->sum('hrb_current_qty');
                $break_downs = [];
                for ($i=0; $i < count($chil_ids); $i++) { 
                    $break_downs[] = $i;
                }
                $brk_array[] = [
                    'column' => $key,
                    'break_downs' => $break_downs, 
                    'validation' => $value->current_qty == $totalQuantity ? true : false,
                ];
            }
        }
        return $brk_array;
    }
    
    public function InventoryGetList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->__inventory->getInventoryList($request);
        $arr=array();
        $i="0";
        $sr_no=(int)$request->input('start')-1;
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) {   
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a href="#" 
                        class="mx-3 btn btn-sm  align-items-center" 
                        data-url="'.url('/Medicine-supplies-inventory/edit/'.$row->id).'" 
                        data-ajax-popup="true"  
                        data-size="xxl" 
                        data-bs-toggle="tooltip" 
                        title="Edit"  
                        data-title="Mangage City Health Inventory Posting">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->cip_status == 0) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
                    '';   
            }
            $arr[$i]['srno']= $sr_no;
            $arr[$i]['cip_control_no']= $row->cip_control_no;
            $arr[$i]['cip_expiry_date']= $row->cip_expiry_date != null ? Carbon::parse($row->cip_expiry_date)->format('M d, Y') : null;
            $arr[$i]['cip_item_name']= $row->cip_item_name;
            $arr[$i]['cip_uom']= $row->uom_code;
            $arr[$i]['qty']= $row->cip_balance_qty.'/'.$row->cip_qty_posted;
            $arr[$i]['cip_issued_qty']= $row->cip_issued_qty;
            $arr[$i]['cip_adjust_qty']= 0;
            $arr[$i]['cip_unit_cost']= $row->cip_unit_cost;
            $arr[$i]['cip_status']= ($row->cip_status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Posted</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">Draft</span>');	
            $arr[$i]['civ_is_active']= ($row->civ_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']= $actions;
            $i++;
        } 
        $totalRecords=$data['data_cnt'];
        $json_data = array(
            "recordsTotal"    => intval( $totalRecords ),  
            "recordsFiltered" => intval($totalRecords),
            "data"            => $arr   // total data array
        );
        echo json_encode($json_data);
    }

    public function getItemDetailsExternal(request $request){
        try {
            $gso_units = $this->__inventory->getUnitList();
            $categories_inv = DB::table('ho_inventory_category')->get();
            $item_details = DB::table('gso_issuances')
                            ->join('gso_issuances_details', 'gso_issuances_details.issuance_id', 'gso_issuances.id')
                            ->rightJoin('gso_items', 'gso_issuances_details.item_id', 'gso_items.id')
                            ->join('gso_unit_of_measurements', 'gso_items.uom_id', 'gso_unit_of_measurements.id')
                            ->select('gso_items.id AS item_id', 
                                     'gso_items.name AS item_name', 
                                     'gso_items.code AS item_code', 
                                     'gso_items.weighted_cost AS unit_cost', 
                                     'gso_items.description AS item_description', 
                                     'gso_unit_of_measurements.code AS uom_code',
                                     'gso_unit_of_measurements.id AS uom_id',
                                     'gso_issuances_details.quantity AS qty'
                            )
                            ->get();
            $html = '<tr>
                <td>
                    1
                </td>
                <td>
                    <div class="row">
                        <div class="col-md-10">
                            <input type="hidden" 
                            class="item-id'.$request->external_sr_no.'" 
                            name="items_external['.$request->external_sr_no.'][item_id]">
                            
                            <input type="hidden" 
                                class="item-name'.$request->external_sr_no.'" 
                                name="items_external['.$request->external_sr_no.'][item_name]">
                            
                            <input type="hidden" 
                                class="item-code'.$request->external_sr_no.'" 
                                name="items_external['.$request->external_sr_no.'][item_code]">

                            <input type="hidden"
                                class="uom'.$request->external_sr_no.'" 
                                name="items_external['.$request->external_sr_no.'][uom]">
                            <div class="parent" id="cip-external-items'.$request->external_sr_no.'">
                                <select name="" required
                                    class="form-control external-items'.$request->external_sr_no.'"
                                    id="external-items'.$request->external_sr_no.'"
                                    onchange="updateItem(this.value, '.$request->external_sr_no.')">
                                    <option value="">Select Items</option> ';
                                    foreach ($item_details as $key => $value) {
                                        $html.='<option value="'.$value->item_id.'">'. $value->item_code .'=>['.$value->item_name.']'.'</opion>';
                                    }
                        $html.= '</select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="action-btn bg-info">
                                <a class="btn btn-sm  
                                    align-items-center refeshbuttonselect1 ti-reload text-white" 
                                    onclick="getAllItems('.$request->external_sr_no.')" 
                                    name="stp_print" title="Refesh"></a>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <select name="items_external['.$request->external_sr_no.'][category]" required
                        class="form-control cat external-categories'.$request->external_sr_no.'"
                        id="external-categories'.$request->external_sr_no.'">
                            <option value="">Category</option> ';
                            foreach ($categories_inv as $key => $value) {
                                $html.= '<option value="'. $value->id .'">'.ucwords($value->inv_category).'</option>';
                            }
            $html.= '</select>
                </td>
                <td>
                    <input type="text" 
                        required
                        onkeyup="updateCost('.$request->external_sr_no.')"
                        name="items_external['.$request->external_sr_no.'][item_quantity]" 
                        value="" class="form-control qty'.$request->external_sr_no.'" />
                </td>
                <td>
                    <span id="uom-code'.$request->external_sr_no.'"">
                    <select 
                        required
                        name="items_external['.$request->external_sr_no.'][uom_code]" value="" 
                        class="form-control external-categories'.$request->external_sr_no.' uom-code'.$request->external_sr_no.'">
                        <option value="">Select Unit</option>';
                        foreach ($gso_units as $key => $value) {
                            $html.= '<option value="'. $value->id .'">'. $value->code .'</option>';
                        }
            $html.= '</select>
                        </span>
                </td>
                <td>
                    <input type="date"
                    name="items_external['.$request->external_sr_no.'][expiry_date]" 
                    class="form-control" />
                </td>
                <td>
                    <input type="text" 
                    required
                    onkeyup="updateCost('.$request->external_sr_no.')"
                    name="items_external['.$request->external_sr_no.'][unit_cost]" 
                    value="0" class="form-control unit-cost'.$request->external_sr_no.'" />
                </td>
                <td>
                    <input type="text"  required
                    name="items_external['.$request->external_sr_no.'][total_cost]" readonly
                    value="0" class="form-control total-cost'.$request->external_sr_no.'" />
                </td>
                <td>
                <div class="action-btn bg-danger">
                    <a class="mx-3 btn btn-sm 
                    align-items-center ti-trash text-white btnDelete" name="stp_print" 
                    title="Refesh"></a>
                </div>
                </td>
            </tr>';
            
            return response()->json(['status' => 200, 'data' => ['item_details' => $html]]);
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

    public function getItemDetailsByItemId(request $request){
        try {
            $item_details = DB::table('gso_issuances')
                            ->join('gso_issuances_details', 'gso_issuances_details.issuance_id', 'gso_issuances.id')
                            ->rightJoin('gso_items', 'gso_issuances_details.item_id', 'gso_items.id')
                            ->join('gso_unit_of_measurements', 'gso_items.uom_id', 'gso_unit_of_measurements.id')
                            ->where('gso_items.id', $request->item_id)
                            ->select('gso_items.id AS item_id', 
                                     'gso_items.name AS item_name', 
                                     'gso_items.code AS item_code', 
                                     'gso_items.weighted_cost AS unit_cost', 
                                     'gso_items.description AS item_description', 
                                     'gso_unit_of_measurements.code AS uom_code',
                                     'gso_unit_of_measurements.id AS uom_id',
                                     'gso_issuances_details.quantity AS qty'
                            )
                            ->first();
            
            return response()->json(['status' => 200, 'data' => ['item_details' => $item_details]]);
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

    public function updateInventory(request $request){
        try {
            $data = []; 
            $request->cip_status = 0;
            if($request->submit == 'submit'){
                $request->cip_status = 1;
            }
            if($request->cip_receiving == 1){
                foreach ($request->items as $key => $del_data) {
                    $delete_existing_inventory = $this->__inventory->deleteInventory($del_data);
                }
                
                foreach ($request->items as $key => $value) {
                    $is_parent = 0;
                    $break_downs = [];
                    if(isset($value['breakdown'])){
                        $break_downs = $value['breakdown'];
                        $is_parent = 1;
                    }
                    
                    // Conversion section
                    $base_uom = 0;
                    $based_quantity = 0;
                    $cip_qty_posted = $value['item_quantity'];
                    $cip_unit_cost = $value['unit_cost'];
                    $cip_total_cost = $value['total_cost'];
                    $base_unit_cost = $value['unit_cost'];
                    $base_total_cost = $value['total_cost'];

                    $gso_item = DB::table('gso_items')->where('id', $value['item_id'])->first();
                    if($gso_item){
                        $base_uom = $gso_item->uom_id;
                    }
                    $conversion = DB::table('gso_items_conversions')
                    ->where('item_id', $value['item_id'])
                    ->where('conversion_uom', $value['uom_code'])
                    ->first();

                    if($conversion){
                        $based_quantity = $conversion->based_quantity;
                    }
                    
                    if($value['uom_code'] !== $base_uom){
                        $cip_qty_posted = $value['item_quantity'] * $based_quantity;
                        $base_unit_cost = $value['unit_cost'] / $based_quantity;
                        $base_total_cost = $cip_qty_posted * $cip_unit_cost;
                    }
                    
                    $is_parent = 0;
                    $break_downs = [];
                    if(isset($value['breakdown'])){
                        $break_downs = $value['breakdown'];
                        $is_parent = 1;
                    }

                    $data = [
                        'item_id' => $value['item_id'],
                        'inv_cat_id' => $value['category'], 
                        'sup_id' => $request->supplier_id,
                        'cip_receiving' => $request->cip_receiving,
                        'cip_control_no' => $request->control_number,
                        'cip_date_received' => $request->cip_date_received,
                        'cip_item_code' => $value['item_code'],
                        'cip_item_name' => $value['item_name'],
                        'cip_qty_posted' => $cip_qty_posted,
                        'cip_unit_cost' => $cip_unit_cost,
                        'cip_total_cost' => $cip_total_cost,
                        'base_unit_cost' => $base_unit_cost,
                        'base_total_cost' => $base_total_cost,
                        'cip_issued_qty' => 0,
                        'cip_balance_qty' => $cip_qty_posted,
                        'cip_adjust_qty' => 0,
                        'cip_uom' => $base_uom,
                        'cip_expiry_date' => $value['expiry_date'],
                        'cip_status' => $request->cip_status,
                        'cip_remarks' => $request->remarks,
                        'is_parent' => $is_parent,
                        'created_by' => 1,
                        'updated_by' => 1,
                        'civ_is_active' => 1
                    ];
                   $this->__inventory->addInv($data, $break_downs);
                }
            }else{
                foreach ($request->items_external as $key => $value) {
                    $data = [
                        'item_id' => $value['item_id'],
                        'inv_cat_id' => $value['category'],
                        'sup_id' => $request->supplier_id,
                        'cip_receiving' => $request->cip_receiving,
                        'cip_control_no' => $request->inv_control_number,
                        'cip_date_received' => $request->cip_date_received,
                        'cip_item_code' => $value['item_code'],
                        'cip_item_name' => $value['item_name'],
                        'cip_unit_cost' => $value['unit_cost'],
                        'cip_total_cost' => $value['total_cost'],
                        'cip_qty_posted' => $value['item_quantity'] == null ? 0 : $value['item_quantity'],
                        'cip_issued_qty' => 0,
                        'cip_balance_qty' => $value['item_quantity'] == null ? 0 : $value['item_quantity'],
                        'cip_adjust_qty' => 0,
                        'cip_uom' => $value['uom_code'],
                        'cip_expiry_date' => $value['expiry_date'],
                        'cip_status' => $request->cip_status,
                        'cip_remarks' => $request->remarks,
                        'created_by' => 1,
                        'updated_by' => 1
                    ];

                    if(isset($value['inventory_id'])){
                        $this->__inventory->updateInv($value['inventory_id'], $data);
                    }else{
                        $this->__inventory->addInv($data);
                    }
                }
            }
            return redirect()->route('Medicine-supplies-inventory')->with('success', __('Inventory successfully updated.'));
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

    public function ActiveInactive(Request $request){
        try {
            $this->is_permitted($this->slugs, 'delete');
            $id = $request->input('id');
            $is_activeinactive = $request->input('is_activeinactive');
            $data=array('civ_is_active' => $is_activeinactive);
            $this->__inventory->updateActiveInactive($id,$data);
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

    public function getAllSuppliersInventory(){
        try {
            $gso_suppliers = DB::table('gso_suppliers')->select('id', 'business_name')->get();
            $html = '<option value="" selected="selected">Select Suppliers</option>';
            foreach ($gso_suppliers as $key => $value) {
                $html.= '<option value="'.$value->id.'">'.$value->business_name.'</option>';
            }
            return response()->json(['status' => 200, 'data' => ['supplier' => $html]]);
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

    public function getAllItemsInventory(){
        try {
            
            $item_details = DB::table('gso_items')
                            ->select('gso_items.id AS item_id', 
                                     'gso_items.name AS item_name')->get();

            $html = '<option value="">Select Item</option>';
            foreach ($item_details as $key => $value) {
                $html.= '<option value="'.$value->item_id.'">'.$value->item_name.'</option>';
            }
            return response()->json(['status' => 200, 'data' => ['items' => $html]]);
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }
}
