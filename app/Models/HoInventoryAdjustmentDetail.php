<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class HoInventoryAdjustmentDetail extends Model
{
    use HasFactory;
    protected $guarded;

    public function addData($request){
        try {
            $symbol = $request['hiad_qty'][0];
            // $qty = (int)substr($request['hiad_qty'], 1);
            $qty = intval($request['hiad_base_qty']);
            if($request['hiad_status'] == 1){
                if($request['is_parent'] == 1){ // If this is a breakdown item we should updated breakdown as well as its parent
                    // Updating Child breakdowns
                    $breakdown = DB::table('ho_inventory_breakdowns')->find($request['ho_inv_posting_id']);
                    if($symbol == '+'){
                        $hrb_balance_qty = ($breakdown->hrb_balance_qty + $qty);
                        $hrb_adjust_qty = ($breakdown->hrb_adjust_qty + $qty);
                    }else{
                        $hrb_balance_qty = ($breakdown->hrb_balance_qty - $qty);
                        $hrb_adjust_qty = ($breakdown->hrb_adjust_qty - $qty); 
                    }
                    $break = HoInventoryBreakdown::find($request['ho_inv_posting_id'])
                        ->update(['hrb_balance_qty' => $hrb_balance_qty, 'hrb_adjust_qty' => $hrb_adjust_qty]);
                    // Updating Parent Posting
                    $posting = DB::table('ho_inventory_posting')->find($breakdown->inv_posting_id);
                    if($symbol == '+'){
                        $cip_balance_qty = ($posting->cip_balance_qty + $qty);
                        $cip_adjust_qty = ($posting->cip_adjust_qty + $qty);
                    }else{
                        $cip_balance_qty = ($posting->cip_balance_qty - $qty);
                        $cip_adjust_qty = ($posting->cip_adjust_qty - $qty);
                    }
    
                    HoInventoryPosting::find($breakdown->inv_posting_id)
                    ->update(['cip_balance_qty' => $cip_balance_qty, 'cip_adjust_qty' => $cip_adjust_qty]);
                }else{ // If this is not a breakdown item we should only update inventory as this would be external
                    $posting = DB::table('ho_inventory_posting')->find($request['ho_inv_posting_id']);
                    if($symbol == '+'){
                        $cip_balance_qty = ($posting->cip_balance_qty + $qty);
                        $cip_adjust_qty = ($posting->cip_adjust_qty + $qty);
                    }else{
                        $cip_balance_qty = ($posting->cip_balance_qty - $qty);
                        $cip_adjust_qty = ($posting->cip_adjust_qty - $qty);
                    }
                    HoInventoryPosting::find($request['ho_inv_posting_id'])
                    ->update(['cip_balance_qty' => $cip_balance_qty, 'cip_adjust_qty' => $cip_adjust_qty]);
                }
            }
            $data = Self::create($request);
         } catch (\Exception $e) {
             return ($e->getMessage());
         }
    }

    public function updateData($adj_details_id, $request){
        $symbol = $request['hiad_qty'][0];
        $qty = intval($request['hiad_base_qty']);
        if($request['hiad_status'] == 1){
            if($request['is_parent'] == 1){ // If this is a breakdown item we should updated breakdown as well as its parent
                // Updating Child breakdowns
                $breakdown = DB::table('ho_inventory_breakdowns')->find($request['ho_inv_posting_id']);
                if($symbol == '+'){
                    $hrb_balance_qty = ($breakdown->hrb_balance_qty + $qty);
                    $hrb_adjust_qty = ($breakdown->hrb_adjust_qty + $qty);
                }else{
                    $hrb_balance_qty = ($breakdown->hrb_balance_qty - $qty);
                    $hrb_adjust_qty = ($breakdown->hrb_adjust_qty - $qty); 
                }
                HoInventoryBreakdown::find($request['ho_inv_posting_id'])
                    ->update(['hrb_balance_qty' => $hrb_balance_qty, 'hrb_adjust_qty' => $hrb_adjust_qty]);

                // Updating Parent Posting
                $posting = DB::table('ho_inventory_posting')->find($breakdown->inv_posting_id);
                if($symbol == '+'){
                    $cip_balance_qty = ($posting->cip_balance_qty + $qty);
                    $cip_adjust_qty = ($posting->cip_adjust_qty + $qty);
                }else{
                    $cip_balance_qty = ($posting->cip_balance_qty - $qty);
                    $cip_adjust_qty = ($posting->cip_adjust_qty - $qty);
                }

                HoInventoryPosting::find($breakdown->inv_posting_id)
                ->update(['cip_balance_qty' => $cip_balance_qty, 'cip_adjust_qty' => $cip_adjust_qty]);
            }else{ // If this is not a breakdown item we should only update inventory as this would be external
                $posting = DB::table('ho_inventory_posting')->find($request['ho_inv_posting_id']);
                if($symbol == '+'){
                    $cip_balance_qty = ($posting->cip_balance_qty + $qty);
                    $cip_adjust_qty = ($posting->cip_adjust_qty + $qty);
                }else{
                    $cip_balance_qty = ($posting->cip_balance_qty - $qty);
                    $cip_adjust_qty = ($posting->cip_adjust_qty - $qty);
                }

                HoInventoryPosting::find($request['ho_inv_posting_id'])
                ->update(['cip_balance_qty' => $cip_balance_qty, 'cip_adjust_qty' => $cip_adjust_qty]);
            }
        }
        Self::find($adj_details_id)->update($request);
    }
    public function getList($request){
        try {
            \DB::connection()->enableQueryLog();
            $params = $columns = $totalRecords = $data = array();
            $params = $_REQUEST;
            $q=$request->input('q');
            $category_filter=$request->input('category_filter');

            if(!isset($params['start']) && !isset($params['length'])){
                $params['start']="0";
                $params['length']="10";
            }
 
            $columns = array( 
                0 =>"id",
                1 =>"hiad_series",
                2 =>"ho_inventory_posting.cip_item_name",
                3 =>"gso_unit_of_measurements.code",
                4 =>"hiad_qty",
                5 =>"hiad_remarks",
                6 =>"hiad_status",
                7 =>"is_active",
                8 =>"ho_inventory_adjustment_details.created_by",
                9 =>"ho_inventory_adjustment_details.created_at"
            );
            
            $sql = DB::table('ho_inventory_adjustment_details')
            ->join('ho_inventory_posting', 'ho_inventory_adjustment_details.item_id', '=', 'ho_inventory_posting.item_id')
            ->join('gso_unit_of_measurements', 'ho_inventory_adjustment_details.hiad_uom', 'gso_unit_of_measurements.id')
            ->join('ho_inventory_adjustments', 'ho_inventory_adjustments.hia_no', 'ho_inventory_adjustment_details.hia_id')
            ->join('users', 'users.id', 'ho_inventory_adjustment_details.created_by')
            ->select('ho_inventory_adjustment_details.*', 
                        'ho_inventory_posting.cip_item_name AS item_name', 
                        'gso_unit_of_measurements.code AS uom_code', 
                        'users.name AS user_name');
            
            if(!empty($q) && isset($q)){
                $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(ho_inventory_posting.cip_item_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(gso_unit_of_measurements.code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(ho_inventory_adjustment_details.hiad_remarks)'),'like',"%".strtolower($q)."%");
                });
            }
            
            if(!empty($category_filter) && isset($category_filter)){
                $sql->where('ho_inventory_adjustment_details.inv_cat_id', $category_filter);
            }

            /*  #######  Set Order By  ###### */
            if(isset($params['order'][0]['column']))
                $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
            else
                $sql->orderBy('ho_inventory_adjustment_details.hia_id','DESC');

            // Set Group By
            $sql->groupBy('ho_inventory_adjustment_details.id');
            /*  #######  Get count without limit  ###### */
            $cnt=$sql->count();
            /*  #######  Set Offset & Limit  ###### */
            $sql->offset((int)$params['start'])->limit((int)$params['length']);
            $data = $sql->get();
            // $queries = \DB::getQueryLog();
            // return $queries;
            return array("data_cnt"=>$cnt,"data"=> $data);
         }catch (\Exception $e) {
             return ($e->getMessage());
         }
    }

    public function updateActiveInactive($id,$columns){
        return DB::table('ho_inventory_adjustment_details')->where('id',$id)->update($columns);
    }

    public function getAdjustmentDetails($id){
        return DB::table('ho_inventory_posting')
                    ->join('ho_inventory_category', 'ho_inventory_category.id', '=', 'ho_inventory_posting.inv_cat_id')
                    ->join('ho_inventory_adjustment_details', 'ho_inventory_posting.id', '=', 'ho_inventory_adjustment_details.ho_inv_posting_id')
                    ->join('gso_unit_of_measurements', 'ho_inventory_posting.cip_uom', 'gso_unit_of_measurements.id')
                    ->where('ho_inventory_adjustment_details.id', $id)
                    ->groupBy('ho_inventory_adjustment_details.id')
                    ->where('ho_inventory_posting.cip_status', 1)
                    ->select('ho_inventory_posting.*',
                            'ho_inventory_category.inv_category AS category_name', 
                            'ho_inventory_adjustment_details.hiad_qty AS hiad_qty', 
                            'ho_inventory_adjustment_details.hiad_status AS adj_details_hiad_status', 
                            'ho_inventory_adjustment_details.hia_id AS hia_id', 
                            'ho_inventory_adjustment_details.hiad_remarks AS hiad_remarks', 
                            'ho_inventory_adjustment_details.id AS adj_details_id', 
                            'ho_inventory_adjustment_details.hiad_series AS hiad_series',
                            'ho_inventory_adjustment_details.hiad_base_uom AS hiad_base_uom',
                            'ho_inventory_adjustment_details.hiad_base_qty AS hiad_base_qty',
                            'ho_inventory_adjustment_details.current_uom AS current_uom',
                            'ho_inventory_adjustment_details.current_qty AS current_qty',
                            'ho_inventory_adjustment_details.hiad_converted_qty AS hiad_converted_qty',
                            'ho_inventory_category.id AS category_id', 
                            'gso_unit_of_measurements.id AS uom_id', 
                            'gso_unit_of_measurements.code AS uom_code')
                    ->get();
    }

    public function getAdjustmentByDays($from,$to){
        return DB::table('ho_inventory_adjustment_details')
        ->join('ho_inventory_posting', 'ho_inventory_adjustment_details.item_id', '=', 'ho_inventory_posting.item_id')
        ->join('ho_inventory_adjustments', 'ho_inventory_adjustments.hia_no', 'ho_inventory_adjustment_details.hia_id')
        ->select('ho_inventory_adjustment_details.*', 
                    'ho_inventory_posting.cip_item_name AS item_name');
    }
}
