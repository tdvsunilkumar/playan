<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class GsoItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $table = 'gso_items';
    
    public $timestamps = false;

    public function gl_account()
    {
        return $this->belongsTo('App\Models\AcctgAccountGeneralLedger', 'gl_account_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\GsoItemType', 'item_type_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\GsoItemCategory', 'item_category_id', 'id');
    }

    public function uom()
    {
        return $this->belongsTo('App\Models\GsoUnitOfMeasurement', 'uom_id', 'id');
    }

    public function pur_type()
    {
        return $this->belongsTo('App\Models\GsoPurchaseType', 'purchase_type_id', 'id');
    }

    public function reload_items($purchase_type)
    {
        $items = self::where(['purchase_type_id' => $purchase_type, 'is_active' => 1])
        ->orderBy('id', 'asc')
        ->get();

        return $items;
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
                1 =>"item_name",
                2 =>"unit",
            );
 
            $sql = DB::table('gso_items')
                    ->join('gso_unit_of_measurements', 'gso_unit_of_measurements.id', '=', 'gso_items.uom_id')
                    ->where('gso_items.is_expirable', 1)
                    ->select('gso_items.*','gso_items.name','gso_items.code','gso_unit_of_measurements.code as uom_code',
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
            $sql->orderBy('id','ASC');

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

    public function allItems($vars = '')
    {
        $items = self::where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $its = array();
        if (!empty($vars)) {
            $its[] = array('' => 'select a '.$vars);
        } else {
            $its[] = array('' => 'select an item');
        }
        foreach ($items as $item) {
            $its[] = array(
                $item->id => $item->code . ' - ' . $item->name . ($item->description ? ' ('.$item->description.')' : '')
            );
        }

        $items = array();
        foreach($its as $it) {
            foreach($it as $key => $val) {
                $items[$key] = $val;
            }
        }

        return $items;
    }

    public function allItemsViaGL($gl_account, $field = 0)
    {
        $items = self::where(['gl_account_id' => $gl_account, 'is_active' => 1])->orderBy('id', 'asc')->get();
        if ($field > 0) {
            return $items;
        }

        $its = array();
        if (!empty($vars)) {
            $its[] = array('' => 'select a '.$vars);
        } else {
            $its[] = array('' => 'select an item');
        }
        foreach ($items as $item) {
            $its[] = array(
                $item->id => $item->code . ' - ' . $item->name . ($item->description ? ' ('.$item->description.')' : '')
            );
        }

        $items = array();
        foreach($its as $it) {
            foreach($it as $key => $val) {
                $items[$key] = $val;
            }
        }

        return $items;
    }
}
