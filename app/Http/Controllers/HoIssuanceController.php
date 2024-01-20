<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HoInventoryPosting;
use App\Models\HoIssuance;
use Carbon\CarbonPeriod;
use Carbon\Carbon;
use Auth;
use DB;

class HoIssuanceController extends Controller
{
    private $slugs;
	public $_HoIssuance;

    public function __construct(){
        $this->_HoIssuance = new HoIssuance(); 
        $this->_Inventory = new HoInventoryPosting(); 
        $this->slugs = 'medicine-supplies-issuance';
    }

    public function index(){
		
    	try{
    		$this->is_permitted($this->slugs, 'read');
			$select_type = ['' => 'Select Type', '1' => 'Issuance', '2' => 'Withdrawal'];

        	return view('ho_issuance.index',compact('select_type'));
    	}catch(Exception $e){
    		return ($e->getMessage());
    	}
    }

	public function getList(request $request){
		try {
			$this->is_permitted($this->slugs, 'read');
			$data = $this->_HoIssuance->getList($request);
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
							data-url="'.url('/medicine-supplies-issuance/edit/'.$row->id).'" 
							data-ajax-popup="true" data-size="xxll" data-bs-toggle="tooltip" title="Mangage Inventory Issuance"  
							data-title="Mangage Inventory Issuance">
							<i class="ti-pencil text-white"></i>
						</a>
					</div>'; 
				}
				if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
					$actions .=($row->issuance_status == 0) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
						'';   
				}
				$arr[$i]['srno']= $sr_no;
				$arr[$i]['issuance_code']= $row->issuance_code;
				$arr[$i]['hp_code']= $row->issued_by_full_name;
				if(!isset($row->receiver_full_name)){
					$arr[$i]['receiver_name']= $row->cit_receiver_full_name !== null ? $row->cit_receiver_full_name : $row->emp_receiver_full_name;
				}else{
					$arr[$i]['receiver_name']= $row->receiver_full_name;
				}
				
				$arr[$i]['issuance_status']= ($row->issuance_status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Posted</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">Draft</span>');
				$arr[$i]['date']= Carbon::parse($row->issuance_date)->format('M d, Y');
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

	public function getListSpecific(Request $request,$cit_id){
		// try {
			$this->is_permitted($this->slugs, 'read');
			$data = $this->_HoIssuance->getIssueByPatient($request,$cit_id);
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
						<a href="#" class="mx-3 btn btn-sm  align-items-center btn_open_issue_modal" 
							data-url="'.url('/medicine-supplies-issuance/edit/'.$row->id).'" 
							data-size="xxll" data-bs-toggle="tooltip" title="Mangage Inventory Issuance"  
							data-title="Mangage Inventory Issuance">
							<i class="ti-pencil text-white"></i>
						</a>
					</div>'; 
				}
				$arr[$i]['srno']= $sr_no;
				$arr[$i]['item_name']= $row->item_name;
				$arr[$i]['uom_code']= $row->uom_code;
				$arr[$i]['quantity']= $row->quantity;
				$arr[$i]['date_recieved']=Carbon::parse($row->date_recieved)->format('M d, Y');
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
		// } catch (\Exception $e) {
		// 	return ($e->getMessage());
		// }
	}

	public function add(request $request){
    	try{
			$receiver_info = [
				'age' => '',
				'type' => '',
				'barangay' => '',
				'barangay_id' => '',
				'patient_id' => '',
			];

			if(isset($_GET['type'])){ 
				$receiver_info['type'] = $_GET['type'];
				if($receiver_info['type'] == 1){ // If type 1 (issuance) Then Citizen table

					$citizens = $this->_HoIssuance->getCitizens();
					$select_receiver = ['' => 'Select Citizens'];
					foreach ($citizens as $key => $value) {
						$select_receiver[$value->id] = $value->cit_first_name .' '. $value->cit_middle_name .' '. $value->cit_last_name .' '. $value->cit_suffix_name;
					};

					if(isset($_GET['patient_id'])){
						$receiver_info['patient_id'] = $_GET['patient_id'];
						$barangay = $this->_HoIssuance->getCitizenInfo($receiver_info['patient_id']);
						$receiver_info['barangay'] = $barangay->brgy_name;
						$receiver_info['barangay_id'] = $barangay->barangay_id;
						$receiver_info['age'] = $barangay->age;
					}
				}else{ // If type 1 (withdrawl) Then HR Employees table
					$employees = $this->_HoIssuance->getEmployeeByHealth();
					$select_receiver = ['' => 'Select Employees'];
					foreach ($employees as $key => $value) {
						$select_receiver[$value->id] = $value->fullname;
					};
				}
			}else{
				echo "Type Is Missing In Params...";
			}
 
			$employees_by_health = $this->_HoIssuance->getEmployeeByHealth();
			$select_employees_health = ['' => 'Select Employee'];
            foreach ($employees_by_health as $key => $value) {
                $select_employees_health[$value->id] = $value->fullname;
            };

			$q = "";
			$category_search = "";
			if(isset($request->search)){
				$q = $request->search;
			}
			if(isset($request->category_search)){
				$category_search = $request->category_search;
			}
			$items = $this->_HoIssuance->getItems($q, $category_search, 1);
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
			return view('ho_issuance.create', compact('items', 'select_receiver', 'select_employees_health', 'receiver_info', 'categories'));
    	}catch(Exception $e){
    		return ($e->getMessage());
    	}
    }

	public function edit($id){
    	try{
			$issuance = $this->_HoIssuance->getIssuance($id);
			$designation = $this->_HoIssuance->getDesignation($issuance->hp_code);

			if($issuance->issuance_type == 1){
				$barangay = $this->_HoIssuance->getCitizenInfo($issuance->receiver_id);
			}else{
				$barangay = $this->_HoIssuance->getEmployeeInfo($issuance->receiver_id);
			}
			
			$receiver_info = [
				'age' => $issuance->receiver_age,
				'type' => $issuance->issuance_type,
				'barangay' => $barangay != "" ? $barangay->brgy_name : null,
				'barangay_id' => $barangay != "" ? $barangay->barangay_id : null,
				'patient_id' => $issuance->receiver_id,
			];
			if($receiver_info['type'] == 1){ // If type 1 (issuance) Then Citizen table

				$citizens = $this->_HoIssuance->getCitizens();
				$select_receiver = ['' => 'Select Citizens'];
				foreach ($citizens as $key => $value) {
					$select_receiver[$value->id] = $value->cit_first_name .' '. $value->cit_middle_name .' '. $value->cit_last_name .' '. $value->cit_suffix_name;
				};
			}else{ // If type 1 (withdrawl) Then HR Employees table
				$employees = $this->_HoIssuance->getEmployeeByHealth();
				$select_receiver = ['' => 'Select Employees'];
				foreach ($employees as $key => $value) {
					$select_receiver[$value->id] = $value->fullname;
				};
			} 

			$employees_by_health = $this->_HoIssuance->getEmployeeByHealth();
			$select_employees_health = ['' => 'Select Employee'];
            foreach ($employees_by_health as $key => $value) {
                $select_employees_health[$value->id] = $value->fullname;
            };

			$q = "";
			$category_search = "";
			if(isset($request->search)){
				$q = $request->search;
			}
			if(isset($request->category_search)){
				$category_search = $request->category_search;
			}
			$items = $this->_HoIssuance->getItems($q, $category_search, 1);
			$count_total_items = $this->_HoIssuance->getTotalItems();
			foreach ($items as $key => $item) {
				$gso_conversions = $this->_HoIssuance->getConversions($item->inv_item_id);
				// Here we are getting the base uom to compare with converted uom for calculation
				$gso_items = $this->_HoIssuance->getGSOItesms($item->inv_item_id);

				$items[$key]->gso_conversions = $gso_conversions;
				$items[$key]->base_uom = $gso_items->uom_id;
			}
			$selected_items = $this->_HoIssuance->getSelectedItems($issuance->issuance_code, $issuance->issuance_code);
			foreach ($selected_items as $key => $item) {
				$gso_conversions = $this->_HoIssuance->getConversions($item->item_id);
				// Here we are getting the base uom to compare with converted uom for calculation
				$gso_items = $this->_HoIssuance->getGSOItesms($item->item_id);
				$selected_items[$key]->gso_conversions = $gso_conversions;
				$selected_items[$key]->base_uom = ($gso_items)?$gso_items->uom_id : '';
			}
			// return $selected_items;
			$categories = $this->_HoIssuance->getCategories();
			return view('ho_issuance.edit', compact('issuance', 'selected_items', 'items', 'count_total_items', 'select_receiver', 'select_employees_health', 'receiver_info', 'categories', 'designation'));
    	}catch(Exception $e){
    		return ($e->getMessage());
    	}
    }

	public function addData(request $request){
    	try{
			$series = "0001";
			$last_series = $this->_HoIssuance->getLastRow();
			if($last_series != ""){
				$series = sprintf('%04d', intval($last_series) + 1);
			} 
			// dd($request->all());

			$this->updateIssuanceAndDetails($request, $series);
			return redirect()->route('medicine-supplies-issuance')->with('success', __('Issuances successfully added.'));
    	}catch(Exception $e){
    		return ($e->getMessage());
    	}
    }

	public function updateData(request $request){
    	try{
			$issuance_code = $request->issuance_code;
			$series = $request->issuance_series;
			$this->_HoIssuance->deleteIssuance($issuance_code);
			$this->updateIssuanceAndDetails($request, $series);
            return redirect()->route('medicine-supplies-issuance')->with('success', __('Issuances successfully updated.'));
    	}catch(Exception $e){
    		return ($e->getMessage());
    	}
    }

	public function updateIssuanceAndDetails($request, $series){
		$issuance = [ 
			'receiver_id' => $request->receiver_id,
			'receiver_age' => $request->receiver_age,
			'brgy_id' => $request->brgy_id,
			'hp_code' => $request->hp_code,
			'issuance_code' => date('Y'). '-'. date('m'). $series,
			'issuance_type' => $request->issuance_type,
			'issuance_status' => 0,
			'issuance_series' => $series,
			'issuance_date' => $request->issuance_date,
			'is_active' => 1,
			'created_by' =>1,
			'updated_by' =>1,
		];
		if ($request->submit === 'submit') {
			$issuance['issuance_status'] =1;
		}
		$last_issuance = $this->_HoIssuance->addIssuance($issuance); // Here we are updating the issuance table
	
		if($request->items != null)
		{
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
			// For conversions
			$details = [
				'issuance_base_uom' => $value['base_uom'],
				 'issuance_base_quantity' => $issuance_base_quantity,
				 'issuance_converted_quantity' => $issuance_converted_quantity,
				 'current_quantity' => $balance_qty
			];

			$issuance_details = [
				'issuance_id' => $last_issuance->id,
				'is_parent' => $value['is_parent'],
				'ho_inv_posting_id' => $value['ho_inv_posting_id'],
				'item_id' => $value['item_id'],
				'issuance_uom' => $value['issuance_uom'],
				'issuance_quantity' => $value['issuance_quantity'],
				'issuance_base_uom' => $details['issuance_base_uom'],   
				'issuance_base_quantity' => $details['issuance_base_quantity'],   
				'issuance_converted_quantity' => $details['issuance_converted_quantity'],   
				'current_uom' => $value['issuance_uom'],
				'current_quantity' => $details['current_quantity'],
				'issuance_type' => $request->issuance_type,
				'issuance_status' => $issuance['issuance_status'],
				'created_by' => Auth::user()->id,
				'updated_by' => Auth::user()->id,
				'created_at' => date('Y-m-d h:i s'),
				'updated_at' => date('Y-m-d h:i s'),
			];

			$this->_HoIssuance->addIssuanceDetails($issuance_details); // Here we are updating the issuance details table
		}
	}

		return true;
	}

	public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), 
			[
                'receiver_id'=>'required',
                'hp_code'=>'required',
                'receiver_age'=>'required',
                'receiver_brgy'=>'required',
                'issuance_type'=>'required',
                'hp_code'=>'required',
                'issuance_date'=>'required',
                'items'=>'required',
			],[
				'receiver_id.required' => 'Receiver Name Required',
				'hp_code.required' => 'Issued By Required',
				'receiver_age.required' => 'Age Required',
				'receiver_brgy.required' => 'Address is Required',
				'issuance_type.required' => 'Issued Type Required',
				'hp_code.required' => 'Issued By Field Required',
				'issuance_date.required' => 'Issuance Date Required'
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

	public function calculateConversions(Request $request){
		$current = DB::table('gso_items_conversions')
		->where('item_id', $request->item_id)
		->where('conversion_uom', $request->current_uom)
		->where('gso_items_conversions.is_active', 1)
		->first();

		$selected = DB::table('gso_items_conversions')
		->where('item_id', $request->item_id)
		->where('conversion_uom', $request->selected_uom)
		->where('gso_items_conversions.is_active', 1)
		->first();
		$converted_qty = 0;

		if($current && $selected){
			if($current->based_quantity > $selected->based_quantity){
				$converted_qty = ($request->balance_qty * $current->based_quantity);
			}else if($current->based_quantity < $selected->based_quantity){
				$converted_qty = ($request->balance_qty / $selected->based_quantity);
			}
		}else{
			return response()->json(['status' => 201, 'message' => 'Data Not Found...']);
		}
		return response()->json(['status' => 200, 'message' => 'Success', 'data' => $converted_qty]);
	}

	public function getDesignation($employee_id){
    	try{
			$designation = $this->_HoIssuance->getDesignation($employee_id);
			return response()->json(['status' => 200, 'data' => $designation]);
    	}catch(Exception $e){
    		return ($e->getMessage());
    	}
    }

	public function getCitizenInfo($citizen_id){
    	try{
			$barangay = $this->_HoIssuance->getCitizenInfo($citizen_id);
			return response()->json(['status' => 200, 'data' => $barangay]);
    	}catch(Exception $e){
    		return ($e->getMessage());
    	}
    }

	public function getEmployeeInfo($employee_id){
		try{
			$barangay = $this->_HoIssuance->getEmployeeInfo($employee_id);
			if($barangay != ""){
				$birth_year = date('Y', strtotime($barangay->age));
				$barangay->age = (date('Y') - $birth_year);
			}
			return response()->json(['status' => 200, 'data' => $barangay]);
    	}catch(Exception $e){
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
			$items = $this->_HoIssuance->getItems($q, $category_search, 1);
			foreach ($items as $key => $value) {
				$value->hrb_expiry_date = $value->hrb_expiry_date != null ? date('Y-m-d', strtotime($value->hrb_expiry_date)) : null;
			}
			return response()->json(['status' => 200, 'data' => $items]);
		} catch (\Throwable $th) {
			//throw $th;
		} 
    }

	public function ActiveInactive(Request $request){
        try {
            $this->is_permitted($this->slugs, 'delete');
            $id = $request->input('id');
            $is_activeinactive = $request->input('is_activeinactive');
            $data=array('is_active' => $is_activeinactive);
            $this->_HoIssuance->updateActiveInactive($id,$data);
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

}
