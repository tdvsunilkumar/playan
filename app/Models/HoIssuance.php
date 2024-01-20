<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\HoInventoryPosting;
use App\Models\HoIssuanceDetail;
use Auth;
use DB;

class HoIssuance extends Model
{
    use HasFactory;
    protected $guarded;

    public function getCitizens(){
        try {
            return DB::table('citizens')
            ->select('id',
                'cit_first_name',
                'cit_middle_name',
                'cit_last_name',
                'cit_suffix_name')
                ->get();
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

    public function getList($request){
        try {
            \DB::connection()->enableQueryLog();
            $params = $columns = $totalRecords = $data = array();
            $params = $_REQUEST;
            $q=$request->input('q');
            $issuance_type_search = $request->input('issuance_type_search');
            // return $issuance_type_search;

            if(!isset($params['start']) && !isset($params['length'])){
                $params['start']="0";
                $params['length']="10";
            }
 
            if($issuance_type_search == 1){
                // Query For Issuance type
                $columns = array( 
                    0 =>"id",
                    1 =>"issuance_code",
                    2 =>"hr_employees.fullname",
                    3 =>"citizens.cit_fullname",
                    4 =>"issuance_status",
                    5 =>"issuance_date",
                    6 =>"is_active"
                );
                
                $sql = DB::table('ho_issuances')
                ->where('ho_issuances.issuance_type', 1)
                ->leftjoin('citizens', 'citizens.id', '=', 'ho_issuances.receiver_id')
                ->leftjoin('hr_employees', 'hr_employees.id', '=', 'ho_issuances.hp_code')
                ->select('ho_issuances.id AS id',
                        'ho_issuances.issuance_code AS issuance_code',
                        'ho_issuances.issuance_status AS issuance_status',
                        'ho_issuances.issuance_date AS issuance_date',
                        'ho_issuances.is_active AS is_active',
                        'citizens.cit_fullname AS receiver_full_name',
                        'hr_employees.fullname AS issued_by_full_name'
                        );
                if(!empty($q) && isset($q)){
                    $sql->where(function ($sql) use($q) {
                    $sql->where(DB::raw('LOWER(citizens.cit_fullname)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(hr_employees.fullname)'),'like',"%".strtolower($q)."%")
                        ->orWhere('ho_issuances.issuance_code','like',"%".strtolower($q)."%"); 
                    });
                } 
                
                /*  #######  Set Order By  ###### */
                if(isset($params['order'][0]['column']))
                    $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
                else
                    $sql->orderBy('ho_issuances.issuance_code','DESC');

                /*  #######  Get count without limit  ###### */
                $cnt=$sql->distinct('ho_issuances.issuance_code')->count();

                // Set Group By
                $sql->groupBy('ho_issuances.issuance_code');

                /*  #######  Set Offset & Limit  ###### */
                $sql->offset((int)$params['start'])->limit((int)$params['length']);
                $data = $sql->get();
                // $cnt = DB::table('ho_issuances')->distinct('issuance_code')->where('issuance_type', 1)->count();
            }else if($issuance_type_search == 2) {
                $columns = array( 
                    0 =>"id",
                    1 =>"issuance_code",
                    2 =>"issuer.fullname",
                    3 =>"receiver.fullname",
                    4 =>"issuance_status",
                    5 =>"issuance_date",
                    6 =>"is_active"
                );

                // Query for withrwals type
                $sql = DB::table('ho_issuances')
                ->where('ho_issuances.issuance_type', 2)
                ->join('hr_employees', 'hr_employees.id', '=', 'ho_issuances.receiver_id')
                ->join('hr_employees AS issuer', 'issuer.id', '=', 'ho_issuances.hp_code')
                ->select('ho_issuances.id AS id',
                        'ho_issuances.issuance_code AS issuance_code',
                        'ho_issuances.issuance_status AS issuance_status',
                        'ho_issuances.issuance_date AS issuance_date',
                        'ho_issuances.is_active AS is_active',
                        'hr_employees.fullname AS receiver_full_name',
                        'issuer.fullname AS issued_by_full_name'
                        );
                if(!empty($q) && isset($q)){
                    $sql->where(function ($sql) use($q) {
                        $sql->where(DB::raw('LOWER(receiver.fullname)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(issuer.fullname)'),'like',"%".strtolower($q)."%")
                        ->orWhere('ho_issuances.issuance_code','like',"%".strtolower($q)."%"); 
                    });
                } 
                /*  #######  Set Order By  ###### */
                if(isset($params['order'][0]['column']))
                    $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
                else
                    $sql->orderBy('ho_issuances.issuance_code','DESC');

                /*  #######  Get count without limit  ###### */
                // $cnt=$sql->count();
                $cnt=$sql->distinct('ho_issuances.issuance_code')->count();
                $sql->groupBy('ho_issuances.issuance_code');
                /*  #######  Set Offset & Limit  ###### */
                $sql->offset((int)$params['start'])->limit((int)$params['length']);
                $data = $sql->get();
                // $cnt = DB::table('ho_issuances')->distinct('issuance_code')->where('issuance_type', 2)->count();
            }
            else{
                $columns = array( 
                    0 =>"id", 
                    1 =>"issuance_code",
                    2 =>"issuer.fullname",
                    3 =>"cit_receiver_full_name",
                    4 =>"issuance_status",
                    5 =>"issuance_date",
                    6 =>"is_active"
                );

                // Query for all types
                $sql = DB::table('ho_issuances')
                ->join('hr_employees AS issuer', 'issuer.id', '=', 'ho_issuances.hp_code')
                ->select('ho_issuances.id AS id',
                        'ho_issuances.issuance_code AS issuance_code',
                        'ho_issuances.issuance_status AS issuance_status',
                        'ho_issuances.issuance_date AS issuance_date',
                        'ho_issuances.is_active AS is_active',
                        'hr_employees.fullname AS cit_receiver_full_name',
                        'citizens.cit_fullname AS emp_receiver_full_name',
                        'issuer.fullname AS issued_by_full_name'
                        );
                $sql->leftJoin('citizens', function ($join) {
                    $join->on('ho_issuances.receiver_id', '=', 'citizens.id')
                        ->where('ho_issuances.issuance_type', 1);
                })
                ->leftJoin('hr_employees', function ($join) {
                    $join->on('ho_issuances.receiver_id', '=', 'hr_employees.id')
                        ->where('ho_issuances.issuance_type', '<>', 1);
                });
                if(!empty($q) && isset($q)){
                    $sql->where(function ($sql) use($q) {
                        $sql->where(DB::raw('LOWER(citizens.cit_fullname)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(hr_employees.fullname)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(issuer.fullname)'),'like',"%".strtolower($q)."%")
                        ->orWhere('ho_issuances.issuance_code','like',"%".strtolower($q)."%"); 
                    });
                } 
                /*  #######  Set Order By  ###### */
                if(isset($params['order'][0]['column']))
                    $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
                else
                    $sql->orderBy('ho_issuances.issuance_code','DESC');

                /*  #######  Get count without limit  ###### */
                // $cnt=$sql->count();
                $cnt=$sql->distinct('ho_issuances.issuance_code')->count();
                $sql->groupBy('ho_issuances.issuance_code');
                /*  #######  Set Offset & Limit  ###### */
                $sql->offset((int)$params['start'])->limit((int)$params['length']);
                $data = $sql->get();

            }
            // $queries = \DB::getQueryLog();
            // return $queries;
            return array("data_cnt"=>$cnt,"data"=> $data);
         }catch (\Exception $e) {
             return ($e->getMessage());
         }
    }

    public function getIssueByPatient($request,$cit_id){
        // try {
            if(!isset($params['start']) && !isset($params['length'])){
                $params['start']="0";
                $params['length']="5";
            }
            $columns = array( 
                0 =>"id",
                1 =>"items.name",
                2 =>"uom.code",
                3 =>"issue_details.current_quantity",
                4 =>"issue.issuance_date",
                5 =>"is_active"
            );

            // Query for withrwals type
            $sql = DB::table('ho_issuance_details as issue_details')
            ->where('issue.issuance_type', 1)
            ->where('issue.receiver_id', $cit_id)
            ->leftJoin('ho_issuances AS issue', 'issue.id', '=', 'issue_details.issuance_id')
            ->leftJoin('gso_items AS items', 'items.id', '=', 'issue_details.item_id')
            ->leftJoin('gso_unit_of_measurements as uom','issue_details.current_uom','=','uom.id')
            ->select('issue.id AS id',
                    'uom.code AS uom_code',
                    'items.name AS item_name',
                    'issue_details.current_quantity AS quantity',
                    'issue.issuance_date AS date_recieved',
                    );
                    /*  #######  Set Order By  ###### */
                    if(isset($params['order'][0]['column']))
                        $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
                    else
                        $sql->orderBy('issue.issuance_code','DESC');
                    /*  #######  Get count without limit  ###### */
                    // $cnt=$sql->count();
                    $cnt=$sql->count();
                    // $sql->groupBy('issue.issuance_code');
                    /*  #######  Set Offset & Limit  ###### */
                    $sql = $sql->offset((int)$params['start'])->limit((int)$params['length']);
                    $data = $sql->get();
            return array("data_cnt"=>$cnt,"data"=> $data);
        // }catch (\Exception $e) {
        //     return ($e->getMessage());
        // }
    }

    public function getEmployeeByHealth(){
        try {
            return DB::table('hr_employees')
            ->join('barangays', 'barangays.id', 'hr_employees.barangay_id')
            ->select('hr_employees.id',
            'hr_employees.user_id',
                'hr_employees.acctg_department_id', 
                'hr_employees.fullname',
                'hr_employees.identification_no',
                'barangays.brgy_name')
                ->where('hr_employees.is_active', 1)
                ->get();
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

    public function getDesignation($employee_id){
        try {
            return DB::table('hr_employees')
            ->join('hr_designations', 'hr_designations.id', 'hr_employees.hr_designation_id')
            ->where('hr_employees.id', $employee_id)
            ->select('hr_designations.description', 'hr_employees.identification_no AS licence_no')
                ->first();
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

    public function getCitizenInfo($citizen_id){
        try {
            return DB::table('citizens')
            ->join('barangays', 'barangays.id', 'citizens.brgy_id')
            ->where('citizens.id', $citizen_id)
            ->select('barangays.id AS barangay_id','barangays.brgy_name', 'citizens.cit_age AS age')
                ->first();
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }
 
    public function getEmployeeInfo($employee_id){
        try {
            return DB::table('hr_employees')
            ->join('barangays', 'barangays.id', 'hr_employees.barangay_id')
            ->where('hr_employees.id', $employee_id)
            ->select('barangays.id AS barangay_id', 'barangays.brgy_name', 'hr_employees.birthdate AS age')
                ->first();
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

    public function getCategories(){
        try {
            return DB::table('ho_inventory_category')->get();
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

    public function getTotalItems(){
        return $sql =DB::table('ho_inventory_posting')
                ->leftJoin('ho_inventory_breakdowns', function ($join) {
                    $join->on('ho_inventory_posting.id', '=', 'ho_inventory_breakdowns.inv_posting_id')
                        ->where('ho_inventory_breakdowns.hrb_status', 1);
                })
                ->Join('ho_inventory_category', 'ho_inventory_category.id', '=', 'ho_inventory_posting.inv_cat_id')
                ->Join('gso_unit_of_measurements', 'ho_inventory_posting.cip_uom', 'gso_unit_of_measurements.id')
                ->where('ho_inventory_posting.cip_status', 1)
                ->where('ho_inventory_posting.cip_balance_qty', '>', 0)
                ->select('ho_inventory_breakdowns.*',
                        'ho_inventory_category.inv_category AS category_name', 
                        'ho_inventory_category.id AS category_id',
                        'gso_unit_of_measurements.id AS uom_id',
                        'ho_inventory_posting.id AS inventory_id',
                        'ho_inventory_posting.is_parent AS is_parent', 
                        'ho_inventory_posting.item_id AS inv_item_id', 
                        'ho_inventory_posting.cip_qty_posted AS cip_qty_posted',
                        'ho_inventory_posting.cip_issued_qty AS cip_issued_qty',
                        'ho_inventory_posting.cip_adjust_qty AS cip_adjust_qty',
                        'ho_inventory_posting.cip_balance_qty AS cip_balance_qty',
                        'ho_inventory_posting.cip_expiry_date AS cip_expiry_date',
                        'ho_inventory_posting.cip_control_no AS cip_control_no',
                        'ho_inventory_posting.cip_receiving AS cip_receiving',
                        'ho_inventory_posting.cip_item_name AS cip_item_name',
                        'gso_unit_of_measurements.code AS uom_code')
                ->count();
    }

    public function getItems($q, $category_search, $is_issuance = null){
        try {
            $limit = 10;
            $page = 1;
            if(isset($_GET['limit'])){
                $limit = $_GET['limit'];
            } 
            if(isset($_GET['page'])){ 
                $page = $_GET['page'];
            }
            $skip = ($page) * $limit;
            
            $sql =DB::table('ho_inventory_posting')
                ->leftJoin('ho_inventory_breakdowns', function ($join) {
                    $join->on('ho_inventory_posting.id', '=', 'ho_inventory_breakdowns.inv_posting_id')
                        ->where('ho_inventory_breakdowns.hrb_status', 1);
                })
                ->Join('ho_inventory_category', 'ho_inventory_category.id', '=', 'ho_inventory_posting.inv_cat_id')
                ->Join('gso_unit_of_measurements', 'ho_inventory_posting.cip_uom', 'gso_unit_of_measurements.id')
                ->where('ho_inventory_posting.cip_status', 1);
                
                if(isset($is_issuance)){
                    $sql->where('ho_inventory_posting.cip_balance_qty', '>', 0);
                }
                $sql->select('ho_inventory_breakdowns.*',
                        'ho_inventory_category.inv_category AS category_name', 
                        'ho_inventory_category.id AS category_id',
                        'gso_unit_of_measurements.id AS uom_id',
                        'ho_inventory_posting.id AS inventory_id',
                        'ho_inventory_posting.is_parent AS is_parent', 
                        'ho_inventory_posting.item_id AS inv_item_id', 
                        'ho_inventory_posting.cip_qty_posted AS cip_qty_posted',
                        'ho_inventory_posting.cip_issued_qty AS cip_issued_qty',
                        'ho_inventory_posting.cip_adjust_qty AS cip_adjust_qty',
                        'ho_inventory_posting.cip_balance_qty AS cip_balance_qty',
                        'ho_inventory_posting.cip_expiry_date AS cip_expiry_date',
                        'ho_inventory_posting.cip_control_no AS cip_control_no',
                        'ho_inventory_posting.cip_receiving AS cip_receiving',
                        'ho_inventory_posting.cip_item_name AS cip_item_name',
                        'gso_unit_of_measurements.code AS uom_code');
            if(!empty($q) && isset($q)){
                // return $q;
                $sql->where(function ($sql) use($q) {
                    $sql->where(DB::raw('LOWER(ho_inventory_posting.cip_item_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(ho_inventory_category.inv_category)'),'like',"%".strtolower($q)."%");
                });
            }
            if(!empty($category_search) && isset($category_search)){
                $category_search = json_decode($category_search);
                $sql->where(function ($sql) use($category_search) {
                    $sql->whereIn('ho_inventory_category.inv_category',$category_search);
                });
            }
            /*  #######  Set Order By  ###### */
            // $sql->orderBy('id','ASC');

            /*  #######  Get count without limit  ###### */
            $data_cnt=$sql->count();
            /*  #######  Set Offset & Limit  ###### */
            if(isset($_GET['page'])){
                $data=$sql->skip($skip)->paginate($limit);
            }else{
                $data=$sql->get();
            }
            foreach ($data as $key => $value) {
                $sl_no = (($limit * ($page - 1)) + $key);
                $value->cip_expiry_date = $value->cip_expiry_date != null ? date('Y-m-d', strtotime($value->cip_expiry_date)) : null;
                $value->sl_no = $sl_no;
            }
            return $data;
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

    public function getSelectedItems($code, $issuance_code){
        try {
            $sql = DB::table('ho_issuances')
                ->leftJoin('ho_issuance_details', 'ho_issuances.id', '=', 'ho_issuance_details.issuance_id')
                ->leftJoin('gso_unit_of_measurements as uom','ho_issuance_details.issuance_uom','=','uom.id')
                ->leftJoin('ho_inventory_posting', 'ho_issuance_details.ho_inv_posting_id', '=', 'ho_inventory_posting.id')
                ->leftJoin('ho_inventory_breakdowns', 'ho_issuance_details.ho_inv_posting_id', '=', 'ho_inventory_breakdowns.id')
                ->where('ho_issuances.issuance_code', '=', $code)
                ->where('ho_issuance_details.issuance_quantity', '!=', 0)
                ->select('ho_issuances.*','ho_issuance_details.is_parent','ho_issuance_details.issuance_quantity','ho_issuance_details.issuance_base_uom','ho_issuance_details.issuance_converted_quantity', 'ho_issuance_details.issuance_base_quantity','uom.code AS uom_code','uom.id AS uom_id', DB::raw('
                    CASE 
                        WHEN ho_issuance_details.is_parent = 0 THEN ho_inventory_posting.id
                        WHEN ho_issuance_details.is_parent = 1 THEN ho_inventory_breakdowns.id
                        ELSE NULL
                    END as inv_id
                '),
                DB::raw('
                    CASE 
                        WHEN ho_issuance_details.is_parent = 0 THEN ho_inventory_posting.item_id
                        WHEN ho_issuance_details.is_parent = 1 THEN ho_inventory_breakdowns.item_id
                        ELSE NULL
                    END as item_id
                '), 
                DB::raw('
                    CASE 
                        WHEN ho_issuance_details.is_parent = 0 THEN ho_inventory_posting.cip_qty_posted
                        WHEN ho_issuance_details.is_parent = 1 THEN ho_inventory_breakdowns.hrb_qty_posted
                        ELSE NULL
                    END as qty_posted
                '),
                DB::raw('
                    CASE 
                        WHEN ho_issuance_details.is_parent = 0 THEN ho_inventory_posting.cip_balance_qty
                        WHEN ho_issuance_details.is_parent = 1 THEN ho_inventory_breakdowns.hrb_balance_qty
                        ELSE NULL
                    END as balance_qty
                '),
                DB::raw('
                    CASE 
                        WHEN ho_issuance_details.is_parent = 0 THEN ho_inventory_posting.cip_expiry_date
                        WHEN ho_issuance_details.is_parent = 1 THEN ho_inventory_breakdowns.hrb_expiry_date
                        ELSE NULL
                    END as expiry_date
                '),
                DB::raw('
                    CASE
                        WHEN ho_issuance_details.is_parent = 0 THEN ho_inventory_posting.cip_item_name
                        WHEN ho_issuance_details.is_parent = 1 THEN ho_inventory_breakdowns.hrb_item_name
                        ELSE NULL
                    END as item_name
                '),
                DB::raw('
                    CASE
                        WHEN ho_issuance_details.is_parent = 0 THEN ho_inventory_posting.cip_control_no
                        WHEN ho_issuance_details.is_parent = 1 THEN ho_inventory_breakdowns.hrb_control_no
                        ELSE NULL
                    END as control_number
                '),
                DB::raw('
                    CASE
                        WHEN ho_issuance_details.is_parent = 0 THEN ho_inventory_posting.cip_receiving
                        WHEN ho_issuance_details.is_parent = 1 THEN ho_inventory_breakdowns.hrb_receiving
                        ELSE NULL
                    END as receiving
                '));
            /*  #######  Get count without limit  ###### */
            $data_cnt=$sql->count();
            /*  #######  Set Offset & Limit  ###### */
            $data=$sql->get();
            return $data;
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

    public function getConversions($item_id){
        return DB::table('gso_items_conversions')
        ->leftJoin('gso_unit_of_measurements', 'gso_unit_of_measurements.id', 'gso_items_conversions.conversion_uom')
        ->where('gso_items_conversions.item_id', $item_id)
        ->select('gso_items_conversions.*', 'gso_unit_of_measurements.code AS code')
        ->get();
    }

    public function getGSOItesms($item_id){
        return DB::table('gso_items')->where('id', $item_id)->first();
    }

    public function addIssuance($request){
        return Self::create($request);
    }

    public function addIssuanceDetails($request){
         // Updating the posting table;
         if($request['issuance_status'] == 1){
            if($request['is_parent'] == 1){ // If this is a breakdown item we should updated breakdown as well as its parent
                $breakdown = DB::table('ho_inventory_breakdowns')->find($request['ho_inv_posting_id']);
                $hrb_balance_qty = ($breakdown->hrb_balance_qty - $request['issuance_base_quantity']);
                $hrb_issued_qty = ($breakdown->hrb_issued_qty + $request['issuance_base_quantity']);

                $breakdown = HoInventoryBreakdown::find($request['ho_inv_posting_id']);
                HoInventoryBreakdown::find($request['ho_inv_posting_id'])
                    ->update(['hrb_balance_qty' => $hrb_balance_qty, 'hrb_issued_qty' => $hrb_issued_qty]);

                $posting = DB::table('ho_inventory_posting')->find($breakdown->inv_posting_id);
                $cip_balance_qty = ($posting->cip_balance_qty - $request['issuance_base_quantity']);
                $cip_issued_qty = ($posting->cip_issued_qty + $request['issuance_base_quantity']);
                HoInventoryPosting::find($breakdown->inv_posting_id)
                ->update(['cip_balance_qty' => $cip_balance_qty, 'cip_issued_qty' => $cip_issued_qty]);
            }else{ // If this is not a breakdown item we should only update inventory as this would be external
                $posting = DB::table('ho_inventory_posting')->find($request['ho_inv_posting_id']);
                $cip_balance_qty = ($posting->cip_balance_qty - $request['issuance_base_quantity']);
                $cip_issued_qty = ($posting->cip_issued_qty + $request['issuance_base_quantity']);
                HoInventoryPosting::find($request['ho_inv_posting_id'])
                ->update(['cip_balance_qty' => $cip_balance_qty, 'cip_issued_qty' => $cip_issued_qty]);
            }
        }

        DB::table('ho_issuance_details')->insert($request);
    }

    public function getLastRow(){
        try {
           return Self::max('issuance_series');
         } catch (\Exception $e) {
             return ($e->getMessage());
         }
    }

    public function updateActiveInactive($id,$columns){
        $issuance = DB::table('ho_issuances')->where('id',$id)->select('issuance_code')->first();
        return DB::table('ho_issuances')->where('issuance_code',$issuance->issuance_code)->update($columns);
    }

    public function details(){
        return $this->hasOne(HoIssuanceDetail::class, 'issuance_id');
    }

    public function getIssuance($id){
        try {
            return Self::find($id);
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

    public function deleteIssuance($issuance_code){
        try {
            $issuance = Self::where('issuance_code', $issuance_code)->select('id')->first();
            DB::table('ho_issuance_details')->where('issuance_id', $issuance->id)->delete();
            Self::where('issuance_code', $issuance_code)->delete();
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }
}
