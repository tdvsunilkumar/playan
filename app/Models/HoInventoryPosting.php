<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\HoInventoryBreakdown;
use DB;

class HoInventoryPosting extends Model
{
    use HasFactory;
    protected $table = 'ho_inventory_posting';
    protected $guarded;
 
    public function addInv($request, $break_downs){
        try { 
            Self::create($request);
            $parent_id = Self::orderBy('id', 'DESC')->first();
            if(count($break_downs) > 0){
                foreach ($break_downs as $key => $value) {

                    $base_uom = 0;
                    $based_quantity = 0;
                    $hrb_qty_posted = $value['item_quantity'];
                    $hrb_unit_cost = $value['unit_cost'];
                    $hrb_total_cost = $value['total_cost'];
                    $hrb_base_unit_cost = $value['unit_cost'];
                    $hrb_base_total_cost = $value['total_cost'];

                    $gso_item = DB::table('gso_items')->where('id', $value['item_id'])->first();
                    if($gso_item){
                        $base_uom = $gso_item->uom_id;
                    }
                    $conversion = DB::table('gso_items_conversions')
                    ->where('item_id', $value['item_id'])
                    ->where('conversion_uom', $base_uom)
                    ->first();

                    if($conversion){
                        $based_quantity = $conversion->based_quantity;
                    }
                    
                    if($value['uom_code'] !== $base_uom){
                        $hrb_qty_posted = $value['item_quantity'] * $based_quantity;
                        $hrb_unit_cost = $value['unit_cost'] / $based_quantity;
                        $hrb_total_cost = $hrb_qty_posted * $hrb_unit_cost;
                    }

                    $data = [
                        'inv_posting_id' =>$parent_id->id, 
                        'item_id' => $request['item_id'],
                        'hrb_date_received' => $request['cip_date_received'],
                        'hrb_control_no' => $request['cip_control_no'],
                        'hrb_item_name' => $request['cip_item_name'],
                        'hrb_receiving' => $request['cip_receiving'],
                        'hrb_unit_cost' => $hrb_unit_cost,
                        'hrb_total_cost' => $hrb_total_cost,
                        'hrb_qty_posted' => $hrb_qty_posted,
                        'hrb_current_qty' => $value['item_quantity'],
                        'hrb_base_unit_cost' => $hrb_base_unit_cost,
                        'hrb_base_total_cost' => $hrb_base_total_cost,
                        'hrb_issued_qty' => 0,
                        'hrb_balance_qty' => $hrb_qty_posted,
                        'hrb_adjust_qty' => 0,
                        'hrb_uom' => $request['cip_uom'],
                        'hrb_expiry_date' => $value['expiry_date'],
                        'hrb_status' => $request['cip_status'],
                        'hrb_remarks' => $request['cip_remarks'],
                        'created_by' => 1,
                        'updated_by' => 1
                    ];
                    if($value['item_quantity'] != 0){
                        HoInventoryBreakdown::create($data);
                    }
                }
            }
         } catch (\Exception $e) {
             return ($e->getMessage());
         }
    }

    public function getMaxExternalRow(){
        return Self::where('cip_receiving', 2)->max('cip_control_no');
    } 

    public function updateInv($id, $request){
        try {
            Self::find($id)->update($request);
         } catch (\Exception $e) {
             return ($e->getMessage());
         }
    }
    
    public function getChildInventories($parent_id){
        $sql = DB::table('ho_inventory_breakdowns')
                ->join('ho_inventory_posting', 'ho_inventory_posting.id', 'ho_inventory_breakdowns.inv_posting_id')
                ->where('ho_inventory_breakdowns.inv_posting_id', $parent_id);
                $sql->join('gso_items', 'gso_items.id', 'ho_inventory_breakdowns.item_id')
                ->join('gso_unit_of_measurements', 'gso_unit_of_measurements.id', 'ho_inventory_breakdowns.hrb_uom')
                ->select('ho_inventory_breakdowns.*', 'gso_unit_of_measurements.id AS uom_code' , 'gso_unit_of_measurements.code AS uom_name', 'gso_items.is_expirable', 'ho_inventory_posting.cip_item_name', 'ho_inventory_posting.cip_item_code');
                $data = $sql->get();
                return $data;
    }

    public function getInventoryList($request){
        try {
            $params = $columns = $totalRecords = $data = array();
            $params = $_REQUEST;
            $q=$request->input('q');

            if(!isset($params['start']) && !isset($params['length'])){
                $params['start']="0";
                $params['length']="10";
            }

            $columns = array( 
                0 =>"id",
                1 =>"cip_receiving",
                2 =>"cip_control_no",
                3 =>"sup_id",
                4 =>"inv_cat_id",
                5 =>"item_id",
                6 =>"cip_item_code",
                7 =>"cip_item_name",
                8 =>"cip_unit_cost",
                9 =>"cip_qty_posted",
                10 =>"cip_uom",
                11 =>"cip_status",
                12 =>"cip_issued_qty",
                13 =>"cip_balance_qty",
                14 =>"cip_adjust_qty",
                15 =>"cip_expiry_date",
                16 =>"civ_is_active",
            );
 
            $sql = DB::table('ho_inventory_posting')
                    ->join('ho_inventory_category', 'ho_inventory_category.id', '=', 'ho_inventory_posting.inv_cat_id')
                    ->join('gso_suppliers', 'gso_suppliers.id', '=', 'ho_inventory_posting.sup_id')
                    ->join('gso_unit_of_measurements', 'ho_inventory_posting.cip_uom', 'gso_unit_of_measurements.id')
                    ->where('ho_inventory_posting.is_parent', 1)
                    ->orWhere('ho_inventory_posting.parent_id', null)
                    ->select('ho_inventory_posting.*',
                             'ho_inventory_category.inv_category AS category_name', 
                             'ho_inventory_category.id AS category_id', 
                             'gso_unit_of_measurements.code AS uom_code',
                             'gso_suppliers.business_name as supplier_name');
            if(!empty($q) && isset($q)){
                $sql->where(function ($sql) use($q) {
                    $sql->where(DB::raw('LOWER(ho_inventory_posting.cip_item_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(ho_inventory_category.inv_category)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(gso_suppliers.business_name)'),'like',"%".strtolower($q)."%"); 
                });
            } 
            /*  #######  Set Order By  ###### */
            if(isset($params['order'][0]['column']))
            $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
            else
            $sql->orderBy('id','DESC');

            /*  #######  Get count without limit  ###### */
            $data_cnt=$sql->count();
            /*  #######  Set Offset & Limit  ###### */
            $sql->offset((int)$params['start'])->limit((int)$params['length']);
            $data=$sql->get();
            return array("data_cnt"=>$data_cnt,"data"=>$data);
         }catch (\Exception $e) {
             return ($e->getMessage());
         }
    }

    public function getList($request){
        try {
            $params = $columns = $totalRecords = $data = array();
            $params = $_REQUEST;
            $q=$request->input('q');

            if(!isset($params['start']) && !isset($params['length'])){
                $params['start']="0";
                $params['length']="10";
            }

            $columns = array( 
                0 =>"id",
                1 =>"cip_expiry_date",
                2 =>"cip_balance_qty",
                3 =>"cip_qty_posted",
                4 =>"cip_receiving",
                5 =>"cip_date_received",
            );
 
            $sql = DB::table('ho_inventory_posting')
                    ->join('gso_items', 'gso_items.id', '=', 'ho_inventory_posting.item_id')
                    ->join('gso_unit_of_measurements', 'gso_unit_of_measurements.id', '=', 'gso_items.uom_id')
                    ->join('ho_inventory_category', 'ho_inventory_category.id', '=', 'ho_inventory_posting.inv_cat_id')
                    ->where('ho_inventory_posting.cip_balance_qty','>', 0)
                    ->where('gso_items.is_expirable', 1)
                    ->where('ho_inventory_posting.is_parent', 0)
                    ->select('ho_inventory_posting.*','gso_items.name','gso_items.code','gso_unit_of_measurements.code as uom_code',
                    'ho_inventory_category.inv_category'
                        );
            if(!empty($q) && isset($q)){
                $sql->where(function ($sql) use($q) {
                    $sql->where(DB::raw('LOWER(ho_inventory_posting.cip_item_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(ho_inventory_category.inv_category)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(gso_suppliers.business_name)'),'like',"%".strtolower($q)."%"); 
                });
            } 
            /*  #######  Set Order By  ###### */
            if(isset($params['order'][0]['column']))
            $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
            else
            $sql->orderBy('id','DESC');

            /*  #######  Get count without limit  ###### */
            $data_cnt=$sql->count();
            /*  #######  Set Offset & Limit  ###### */
            $sql->offset((int)$params['start'])->limit((int)$params['length']);
            $data=$sql->get();
            return array("data_cnt"=>$data_cnt,"data"=>$data);
         }catch (\Exception $e) {
             return ($e->getMessage());
         }
    }

    public function getSingleInventory($id){
        try {
            // return Self::find($id);
            return DB::table('ho_inventory_posting')
                        ->where('ho_inventory_posting.id', $id)
                        ->join('gso_unit_of_measurements', 'gso_unit_of_measurements.id', 'ho_inventory_posting.cip_uom')
                        ->select('ho_inventory_posting.*', 'gso_unit_of_measurements.code AS uom_code')
                        ->first();
         } catch (\Exception $e) {
             return ($e->getMessage());
         }
    }
    

    public function getInventories($cip_receiving, $cip_control_no){
        try {
            // return Self::find($id);
            $sql = DB::table('ho_inventory_posting')
                        ->where('ho_inventory_posting.cip_receiving', $cip_receiving)
                        ->where('ho_inventory_posting.cip_control_no', $cip_control_no)
                        ->join('gso_items', 'gso_items.id', 'ho_inventory_posting.item_id')
                        ->join('gso_unit_of_measurements', 'gso_unit_of_measurements.id', 'ho_inventory_posting.cip_uom');
                        // ->orderBy('ho_inventory_posting.id')
                        // if($cip_receiving == 1){
                        //     $sql->where('ho_inventory_posting.parent_id', null);
                        // }
                        $sql->select('ho_inventory_posting.*', 'gso_unit_of_measurements.id AS uom_code' , 'gso_unit_of_measurements.code AS uom_name', 'gso_items.is_expirable');
                        $data = $sql->get();
                        return $data;
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

    public function deleteInventory($value){
        DB::table('ho_inventory_posting')->where('id', $value['inventory_id'])->delete();
        DB::table('ho_inventory_breakdowns')->where('inv_posting_id', $value['inventory_id'])->delete();
    }

    public function updateActiveInactive($id,$columns){
        return DB::table('ho_inventory_posting')->where('id',$id)->update($columns);
    }  

    public function verifyControlNumber($control_number){
       return DB::table('ho_inventory_posting')->where('cip_receiving', 1)->where('cip_control_no', $control_number)->count();
    }

    public function getControlNumber(){
        return DB::table('gso_issuances')
            ->join('gso_purchase_orders', 'gso_issuances.purchase_order_id', '=', 'gso_purchase_orders.id')
            ->join('bac_rfqs', 'gso_purchase_orders.rfq_id', '=', 'bac_rfqs.id')
            ->join('bac_rfqs_lines', 'bac_rfqs_lines.rfq_id', '=', 'bac_rfqs.id')
            ->join('gso_purchase_requests', 'bac_rfqs_lines.purchase_request_id', '=', 'gso_purchase_requests.id')
            ->join('gso_departmental_requests', 'gso_purchase_requests.departmental_request_id', '=', 'gso_departmental_requests.id')
            ->join('acctg_departments', 'gso_departmental_requests.department_id', '=', 'acctg_departments.id')
            // ->where('acctg_departments.shortname', 'CHO')
            ->select('gso_issuances.*', 'acctg_departments.shortname', 'acctg_departments.id AS acc_id')
            ->get();
     }

     public function getUnitList(){
        return DB::table('gso_unit_of_measurements')->where('is_active', 1)->select('id', 'code')->get();
     }

     public function getSingleUnit($unit_id){
        return DB::table('gso_unit_of_measurements')->where('is_active', 1)->where('id', $unit_id)->select('id', 'code')->first();
     }

    public function getInventoryIssuance($year, $type = 1, $category=null)
    {
        $postings = HoInventoryPosting::leftJoin('gso_unit_of_measurements as uom','ho_inventory_posting.cip_uom','=','uom.id')
        ->select(
            'ho_inventory_posting.*',
            'code as unit_measure',
            'cip_qty_posted as total_qty',
            'base_total_cost as total_cost',
            'cip_adjust_qty as adjust_qty',
            DB::raw('(cip_adjust_qty * base_unit_cost) as adjust_cost'),
            'cip_issued_qty as issue_qty',
            DB::raw('(cip_issued_qty * base_unit_cost) as issue_cost'),
            'cip_balance_qty as bal_qty',
            DB::raw('(cip_balance_qty * base_unit_cost) as bal_cost'),
            DB::raw('1 AS delivery')
            )
        ->whereYear('cip_date_received',$year)
        // ->whereNull('parent_id')
        ->where([['cip_status',1],['cip_receiving',$type]]);
            
        if ($category || $category != 0) {
            $postings->where('inv_cat_id',$category);
        }
        return $postings->orderBy('cip_item_name')->get();
    }
    public function getInventoryRemaining($year, $type = 1, $category=null)
    {
        $lastyear = $year - 1;
        $postings = HoInventoryPosting::leftJoin('gso_unit_of_measurements as uom','ho_inventory_posting.cip_uom','=','uom.id')
        ->join(DB::raw("(SELECT * FROM ho_utility_yearly_balance 
                            WHERE year = ".$lastyear."
                            AND month = 12
                        ) AS hbal "),
                        'ho_inventory_posting.id','=','hbal.ho_inv_posting_id')
        ->leftJoin(DB::raw("(SELECT ho_inv_posting_id, sum(issuance_quantity) as issuance_quantity FROM ho_issuance_details 
                            WHERE YEAR(created_at) = ".$year."
                            AND issuance_status = 1
                            GROUP BY ho_inv_posting_id
                        ) AS hi "),
            'ho_inventory_posting.id','=','hi.ho_inv_posting_id')
            ->leftJoin(DB::raw("( SELECT ho_inventory_adjustment_details.parent_id as hip_id, sum(hiad_qty) as adjst 
                            FROM ho_inventory_adjustment_details 
                                JOIN ho_inventory_adjustments 
                                ON ho_inventory_adjustments.id = ho_inventory_adjustment_details.hia_id 
                                WHERE YEAR(ho_inventory_adjustments.hia_date) = ".$year." 
                                AND hiad_status = 1
                                GROUP BY parent_id
                            ) AS hiad "),
                'ho_inventory_posting.id','=','hiad.hip_id')
        ->select(
            'ho_inventory_posting.*',
            'code as unit_measure',
            DB::raw('hbal.beginning_qty AS total_qty'),
            DB::raw('(hbal.beginning_qty * cip_unit_cost) AS total_cost'),

            DB::raw('sum(hiad.adjst) AS adjust_qty'),
            DB::raw('(IFNULL(sum(hiad.adjst),0) * cip_unit_cost) AS adjust_cost'),

            DB::raw('sum(hi.issuance_quantity) AS issue_qty'),
            DB::raw('(IFNULL(sum(hi.issuance_quantity),0) * cip_unit_cost) AS issue_cost'),

            DB::raw('hbal.beginning_qty - (IFNULL(sum(hi.issuance_quantity),0) - IFNULL(sum(hiad.adjst),0)) AS bal_qty'),
            DB::raw('((hbal.beginning_qty - (IFNULL(sum(hiad.adjst),0) + IFNULL(sum(hi.issuance_quantity),0))) * cip_unit_cost) AS bal_cost'),

            DB::raw('0 AS delivery')
            )
        ->whereYear('cip_date_received','<',$year)
        ->groupBy('ho_inventory_posting.id')
        // ->whereNull('ho_inventory_posting.parent_id')
        ->where([
            ['cip_status',1],
            ['cip_receiving',$type]
        ]);
        
        if ($category || $category != 0) {
            $postings->where('inv_cat_id',$category);
        }
        return $postings->orderBy('cip_item_name')->get();
    }
}
