<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelUpdateCreate;
use App\Models\Bplo\CtoChargeTypes;

class CtoCashReciept extends Model
{
    use ModelUpdateCreate;
    protected $guarded = ['id'];
    public $timestamps = false;
    
    public function getFundCodes()
    {
        $data = AcctgFundCode::where('is_active', 1)->get();
        return $data->mapWithKeys(function($row,$keys){
            $array[$row->id] = $row->description;
            return $array;
        })->toArray();
    }
    public function getTypeOfCharges()
    {
        $data = CtoChargeTypes::where('ctype_is_active', 1)->get();
        return $data->mapWithKeys(function($row,$keys){
            $array[$row->id] = $row->ctype_desc;
            return $array;
        })->toArray();
    }
    public function getGl()
    {
        $data = AcctgAccountGeneralLedger::where('is_active', 1)->get();
        return $data->mapWithKeys(function($row,$keys){
            $array[$row->id] = $row->description;
            return $array;
        })->toArray();
    }
    public function getList($request)
    {
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        if(!isset($params['start']) && !isset($params['length'])){
                $params['start']="0";
                $params['length']="10";
        }
        $columns = array( 
                1 =>"date",
                2 =>"or_no",
                3 =>"cto_charge_types.ctype_desc",   
                4 =>"acctg_account_general_ledgers.description",   
                5 =>"particulars",   
        );
        $sql = self::select(
            'cto_cash_reciepts.*',
            'cto_charge_types.ctype_desc as type_of_charge',
            'acctg_account_general_ledgers.description as gl_desc'
        )
        ->leftjoin('cto_charge_types', 'cto_charge_types.id', '=', 'cto_cash_reciepts.type_of_charge_id')
        ->leftjoin('acctg_account_general_ledgers', 'acctg_account_general_ledgers.id', '=', 'cto_cash_reciepts.gl_id');
        // dd($sql->toSql());
        if(!empty($q) && isset($q)){
                $sql = $sql->where(function ($query) use($q) {
                    $query->where(DB::raw('LOWER(particulars)'),'like',"%".strtolower($q)."%");
                    $query->orWhere(DB::raw('LOWER(cto_charge_types.ctype_desc)'),'like',"%".strtolower($q)."%");
                });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column'])){
                // if ($columns[$params['order'][0]['column']] === 'brgy_id') {
                //     $type = $params['order'][0]['dir'];
                //     $sql = $sql->orderBy('brgy_name',$type);
                //     // dd($sql->get());
                // } else {
                    $sql = $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
                // }
        } else {
                $sql = $sql->orderBy('cto_cash_reciepts.id','DESC');
        }

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }

    // for Cashiering
    public function insertCashReceipt($tfoc_id, $amount, $or_no, $particulars, $is_income = 1 )
    {
        $tfoc = CtoTfoc::find($tfoc_id);
        self::create([
            'fund_code_id' => $tfoc->fund_id,
            'type_of_charge_id' => $tfoc->ctype_id,
            'gl_id' => $tfoc->gl_account_id,
            'date' => \Carbon\Carbon::now(),
            'amount' => $amount,
            'or_no' => $or_no,
            'particulars' => $particulars,
            'is_income' => $is_income,
            'is_active' => 1,
        ]);
    }
}
