<?php

namespace App\Http\Controllers;

use App\Models\HoInventoryAdjustmentDetail;
use App\Models\HoInventoryAdjustment;
use Illuminate\Http\Request;
use App\Models\HoIssuance;
use App\Models\HoInventoryPosting;
use App\Models\HoInventoryBreakdown;
use Carbon\Carbon;
use Auth;
use DB;

class HoInventoryAdjustments extends Controller
{
    private $slugs;
	public $_HoIssuance;

    public function __construct(){
        $this->_HoIssuance = new HoIssuance(); 
        $this->HoInventoryAdjustment = new HoInventoryAdjustment(); 
        $this->HoInventoryAdjustmentDetails = new HoInventoryAdjustmentDetail(); 
        $this->slugs = 'medicine-supplies-issuance';
    }

    public function index(){
        try {
			$categories = $this->_HoIssuance->getCategories();
			$select_categories = ['' => 'Select Category'];
			foreach ($categories as $key => $value) {
				$select_categories[$value->id] = $value->inv_category;
			};
            return view('inventory_adjustment.index', compact('select_categories'));
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    } 

	public function getList(request $request){
		try {
			$this->is_permitted($this->slugs, 'read');
			$data = $this->HoInventoryAdjustmentDetails->getList($request);
			// return $data;
			$arr=array();
			$i="0";
			$sr_no=(int)$request->input('start')-1;
			$sr_no=$sr_no>0? $sr_no+1:0;
			foreach ($data['data'] as $row){
				$sr_no=$sr_no+1;
				$actions = '';
				if ($this->is_permitted($this->slugs, 'update', 1) > 0) {   
					$actions .= '<div class="action-btn bg-warning ms-2">
						<a href="#" class="mx-3 btn btn-sm  align-items-center" 
							data-url="'.url('/medicine-supplies-sdjustment/edit/'.$row->id).'" 
							data-ajax-popup="true" data-size="xxll" data-bs-toggle="tooltip" title="Manage Inventory Adjustments"  
							data-title="Manage Inventory Adjustments">
							<i class="ti-pencil text-white"></i>
						</a>
					</div>';
				}
				if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
					$actions .=($row->hiad_status == 0) ? '<div class="action-btn bg-danger ms-2">
						<a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
						'';   
				}
				$arr[$i]['srno']= $sr_no;
				$arr[$i]['hia_id']= $row->hiad_series;
				$arr[$i]['item_id']= $row->item_name;
				$arr[$i]['hiad_uom']= $row->uom_code;
				$arr[$i]['hiad_qty']= $row->hiad_qty;
				$arr[$i]['hiad_remarks']= $row->hiad_remarks;
				$arr[$i]['hiad_status']= ($row->hiad_status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Posted</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">Draft</span>');	
				$arr[$i]['created_by']= $row->user_name;
				$arr[$i]['created_at']= Carbon::parse($row->created_at)->format('M d, Y');
				$arr[$i]['is_active']= ($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
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
		} catch (\Exception $e) {
			return ($e->getMessage());
		}
	}
	public function getItems(request $request){
		try {
			$q = "";
			$category_search = "";
			if(isset($request->search)){
				$q = $request->search;
			}
			if(isset($request->category_search)){
				$category_search = $request->category_search;
			}
			// return $category_search;
			$items = $this->_HoIssuance->getItems($q, $category_search);
			foreach ($items as $key => $value) {
				$value->hrb_expiry_date = $value->hrb_expiry_date != null ? date('Y-m-d', strtotime($value->hrb_expiry_date)) : null;
			}
			return response()->json(['status' => 200, 'data' => $items]);
		} catch (\Throwable $th) {
			//throw $th;
		} 
    }

    public function add(request $request){
    	try{
			$q = "";
			$category_search = "";
			if(isset($request->search)){
				$q = $request->search;
			}
			if(isset($request->category_search)){
				$category_search = $request->category_search;
			}
			$items = $this->_HoIssuance->getItems($q, $category_search);
			foreach ($items as $key => $item) {
				// Here we are using this to get the Conversion details
				$gso_conversions = $this->_HoIssuance->getConversions($item->inv_item_id);
	
				// Here we are getting the base uom to compare with converted uom for calculation
				$gso_items = $this->_HoIssuance->getGSOItesms($item->inv_item_id);

				$items[$key]->gso_conversions = $gso_conversions;
				$items[$key]->base_uom = $gso_items->uom_id;
			}
			// return $items;
			$categories = $this->_HoIssuance->getCategories();
			return view('inventory_adjustment.create', compact('items','categories'));
    	}catch(Exception $e){
    		return ($e->getMessage());
    	}
    }

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), 
			[
                'remarks'=>'required',
                'hia_date'=>'required',
			],[
				'remarks.required' => 'Remarks is Required',
				'hia_date.required' => 'Date is Required',
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

    public function addData(request $request){
    	try{
			$series = "0001";
			$hia_no = 1;
			$last_series = $this->HoInventoryAdjustment->getLastRow();
			
			if($last_series != ""){
				$series = sprintf('%04d', intval($last_series->hia_series) + 1);
				$hia_no = ($last_series->id + 1);
			}
			$single_data = [
				'hia_year' => date('Y'),
				'hia_no' => $hia_no,
				'hia_series' => $series,
				'hia_remarks' => $request->remarks,
				'hia_status' => $request->button === 'submit' ? 1 : 0,
				'hia_date' => $request->hia_date,
				'is_active' => 1,
				'created_by' => Auth::user()->id,
				'updated_by' => Auth::user()->id
			];
			$this->HoInventoryAdjustment->addSingleData($single_data);

			foreach ($request->items as $key => $value) {

				$issuance_converted_quantity = $value['conversion_uom_qty']; // We are getting this to store and show it at UI
				$converted_uom = '';
				$balance_qty = '';
				$based_quantity = '';

				if($value['conversion_uom'] != null){
					$selections = explode(',', $value['conversion_uom']); // From this we will get conversion uom,  based_qty and balance qty
					$converted_uom = $selections[1] ? str_replace(' ', '', $selections[1]) : 0; // This is converted UOM (this is from gso_items_conversions table)
					$based_quantity = $selections[2] ? str_replace(' ', '', $selections[2]) : 0; // This is based_qty (this is from gso_items_conversions table)
					$balance_qty = $selections[3] ? str_replace(' ', '', $selections[3]) : 0; //(this is from inventory table)
				}
				
				if($converted_uom == $value['base_uom']){ // (Here the base_uom is from gso_items table)
					$issuance_base_quantity = abs($value['issuance_quantity']); // (Here the issuance_quantity is the user inputs)
				}else{
					$issuance_base_quantity = abs($value['issuance_quantity']) * intval($based_quantity);
				}
				$data = [
					'hia_id' => $hia_no,
					'is_parent' => $value['is_parent'],
					'ho_inv_posting_id' => $value['ho_inv_posting_id'],
					'inv_cat_id' => $value['inv_cat_id'],
					'item_id' => $value['item_id'],
					'hiad_series' => $series,
					'hiad_qty' => $value['issuance_quantity'],
					'hiad_uom' => $value['issuance_uom'],
					'hiad_base_uom' => $value['base_uom'],
					'hiad_base_qty' => $issuance_base_quantity,
					'current_uom' => $value['issuance_uom'],
					'current_qty' => $balance_qty,
					'hiad_converted_qty' => $issuance_converted_quantity,
					'hiad_status' => $single_data['hia_status'],
					'hiad_remarks' => $value['hiad_remarks'],
					'is_active' => 1,
					'created_by' =>Auth::user()->id,
					'updated_by' =>Auth::user()->id,
				];
				
				$this->HoInventoryAdjustmentDetails->addData($data);
			}
            return redirect()->route('medicine-supplies-sdjustment')->with('success', __('Adjustments Details successfully added.'));
    	}catch(Exception $e){
    		return ($e->getMessage());
    	}
    }

	public function edit($id, request $request){
    	try{
			$adjustment_details = $this->HoInventoryAdjustmentDetails->getAdjustmentDetails($id);
			foreach ($adjustment_details as $key => $item) {
				// Here we are using this to get the Conversion details
				$gso_conversions = $this->_HoIssuance->getConversions($item->item_id);
	
				// Here we are getting the base uom to compare with converted uom for calculation
				$gso_items = $this->_HoIssuance->getGSOItesms($item->item_id);

				$adjustment_details[$key]->gso_conversions = $gso_conversions;
				$adjustment_details[$key]->base_uom = $gso_items->uom_id;
			}
			// return $adjustment_details;
			if($adjustment_details != ""){
				$adjustments = $this->HoInventoryAdjustment->getAdjustments($adjustment_details[0]->hia_id);
			}else{
				$adjustments = $this->HoInventoryAdjustment->getAdjustments(0);
			}
			
			$q = "";
			$category_search = "";
			if(isset($request->search)){
				$q = $request->search;
			}
			if(isset($request->category_search)){
				$category_search = $request->category_search;
			}
			$items = $this->_HoIssuance->getItems($q, $category_search);
			foreach ($items as $key => $item) {
				$gso_conversions = $this->_HoIssuance->getConversions($item->inv_item_id);
	
				// Here we are getting the base uom to compare with converted uom for calculation
				$gso_items = $this->_HoIssuance->getGSOItesms($item->inv_item_id);

				$items[$key]->gso_conversions = $gso_conversions;
				$items[$key]->base_uom = $gso_items->uom_id;
			}
			$categories = $this->_HoIssuance->getCategories();
			return view('inventory_adjustment.edit', compact('items','categories', 'adjustments', 'adjustment_details'));
    	}catch(Exception $e){
    		return ($e->getMessage());
    	}
    }

	public function updateData(request $request){
		try{
			$single_data = [
				'hia_remarks' => $request->remarks,
				'hia_status' => $request->submit === 'submit' ? 1 : 0,
				'hia_date' => $request->hia_date,
			];

			$this->HoInventoryAdjustment->updateData($request->hia_id, $single_data);
			
			foreach ($request->items as $key => $value) {
				$issuance_converted_quantity = $value['conversion_uom_qty']; // We are getting this to store and show it at UI
				$converted_uom = '';
				$balance_qty = '';
				$based_quantity = '';

				if($value['conversion_uom'] != null){
					$selections = explode(',', $value['conversion_uom']); // From this we will get conversion uom,  based_qty and balance qty
					$converted_uom = $selections[1] ? str_replace(' ', '', $selections[1]) : 0; // This is converted UOM (this is from gso_items_conversions table)
					$based_quantity = $selections[2] ? str_replace(' ', '', $selections[2]) : 0; // This is based_qty (this is from gso_items_conversions table)
					$balance_qty = $selections[3] ? str_replace(' ', '', $selections[3]) : 0; //(this is from inventory table)
				}
				
				if($converted_uom == $value['base_uom']){ // (Here the base_uom is from gso_items table)
					$issuance_base_quantity = intval($value['issuance_quantity']); // (Here the issuance_quantity is the user inputs)
				}else{
					$issuance_base_quantity = intval($value['issuance_quantity']) * intval($based_quantity);
				}

				if(isset($value['adj_details_id'])){
					$data = [
						'hiad_qty' => $value['issuance_quantity'],
						'ho_inv_posting_id' => $value['ho_inv_posting_id'],
						'hiad_status' => $single_data['hia_status'],
						'hiad_remarks' => $value['hiad_remarks'],
						'hiad_base_uom' => $value['base_uom'],
						'hiad_base_qty' => $issuance_base_quantity,
						'current_uom' => $value['issuance_uom'],
						'current_qty' => $balance_qty,
						'hiad_converted_qty' => $issuance_converted_quantity,
						'is_parent' => $value['is_parent'],
						'parent_id' => $value['parent_id'],
					];
					
					$this->HoInventoryAdjustmentDetails->updateData($value['adj_details_id'], $data);
				}else{
					$series = $request->hiad_series;
					$hia_no = $request->hia_id;
					
					$data = [
						'hia_id' => $hia_no,
						'is_parent' => $value['is_parent'],
						'ho_inv_posting_id' => $value['ho_inv_posting_id'],
						'inv_cat_id' => $value['inv_cat_id'],
						'item_id' => $value['item_id'],
						'hiad_series' => $series,
						'hiad_qty' => $value['issuance_quantity'],
						'hiad_uom' => $value['issuance_uom'],
						'hiad_base_uom' => $value['base_uom'],
						'hiad_base_qty' => $issuance_base_quantity,
						'current_uom' => $value['issuance_uom'],
						'current_qty' => $balance_qty,
						'hiad_converted_qty' => $issuance_converted_quantity,
						'hiad_status' => $request->hia_status == 2 ? 0 : $request->hia_status,
						'hiad_remarks' => $value['hiad_remarks'],
						'is_active' => 1,
						'created_by' =>Auth::user()->id,
						'updated_by' =>Auth::user()->id,
					];
					
					$this->HoInventoryAdjustmentDetails->addData($data);
				}
				
			}
            return redirect()->route('medicine-supplies-sdjustment')->with('success', __('Adjustments Details successfully updated.'));
    	}catch(Exception $e){
    		return ($e->getMessage());
    	}
	}

	public function ActiveInactive(Request $request){
        try {
            $this->is_permitted($this->slugs, 'delete');
            $id = $request->input('id');
            $is_activeinactive = $request->input('is_activeinactive');
            $data=array('is_active' => $is_activeinactive);
            $this->HoInventoryAdjustmentDetails->updateActiveInactive($id,$data);
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }
}
