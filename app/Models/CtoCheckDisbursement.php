<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelUpdateCreate;

class CtoCheckDisbursement extends Model
{
    use ModelUpdateCreate;
    protected $guarded = ['id'];
    public $timestamps = false;

    public function transaction_type() 
    { 
        return $this->hasOne(AcctgPaymentType::class, 'id', 'payment_type'); 
    } 
    public function modified() 
    { 
        if ($this->updated_by) {
            return $this->hasOne(User::class, 'id', 'updated_by'); 
        }
        return $this->hasOne(User::class, 'id', 'created_by'); 
    } 
    public function getNextTransactionNo() 
    { 
        $year = date('Y');
        $latest = self::whereYear('created_at',$year)->orderBy('id','desc')->first();
        $no = 0;
        if ($latest) {
            $no = (int)explode('-',$latest->transaction_no)[1];
        }
        return $year.'-'.sprintf('%07d',$no+1); 
    } 
    public function getFundCodes()
    {
        $data = AcctgFundCode::where('is_active', 1)->get();
        return $data->mapWithKeys(function($row,$keys){
            $array[$row->id] = $row->description;
            return $array;
        })->toArray();
    }
    public function getPaymentTypeCodes()
    {
        $data = AcctgPaymentType::where('is_active', 1)->get();
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
                3 =>"cto_charge_types.description",   
                4 =>"acctg_account_general_ledgers.description",   
                5 =>"particulars",   
        );
        $sql = self::select([
            'cto_check_disbursements.*',
        ])
        ->leftjoin('acctg_payment_types', 'acctg_payment_types.id', '=', 'cto_check_disbursements.payment_type');
        // ->leftjoin('acctg_account_general_ledgers', 'acctg_account_general_ledgers.id', '=', 'cto_check_disbursements.gl_id');
        // dd($sql->toSql());
        if(!empty($q) && isset($q)){
                $sql = $sql->where(function ($query) use($q) {
                    $query->where(DB::raw('LOWER(particulars)'),'like',"%".strtolower($q)."%");
                    $query->orWhere(DB::raw('LOWER(acctg_payment_types.description)'),'like',"%".strtolower($q)."%");
                });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column'])){
                    $sql = $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        } else {
                $sql = $sql->orderBy('cto_check_disbursements.id','DESC');
        }

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
}
