<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;
use App\Models\AcctgAccountSubsidiaryLedger;
use App\Models\AcctgAccountGeneralLedger;
use DB;
class HrCommon extends Model
{
    public function getSL($search="",$gl = null)
    {
        $page=1;
        if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
        }
        $length = 20;
        $offset = ($page - 1) * $length;
        $sql = AcctgAccountSubsidiaryLedger::
        leftJoin('acctg_account_general_ledgers', 'acctg_account_general_ledgers.id', 'acctg_account_subsidiary_ledgers.gl_account_id')
        ->where([
            'acctg_account_subsidiary_ledgers.is_active'=>1,
            // 'acctg_account_subsidiary_ledgers.is_hidden'=>0
        ]);
        if(!empty($search)){
                $sql->where(function ($sql) use($search) {
                    $sql
                        ->orwhere(DB::raw('LOWER(acctg_account_general_ledgers.description)'),'like',"%".strtolower($search)."%")
                        ->orwhere(DB::raw('LOWER(acctg_account_general_ledgers.code)'),'like',"%".strtolower($search)."%")
                        ->orwhere(DB::raw('LOWER(acctg_account_subsidiary_ledgers.description)'),'like',"%".strtolower($search)."%")
                        ->orwhere(DB::raw('LOWER(acctg_account_subsidiary_ledgers.code)'),'like',"%".strtolower($search)."%");
                });
        }
        if ($gl) {
            $sql->where('acctg_account_subsidiary_ledgers.gl_account_id',$gl);
        }
        $sql->orderBy('acctg_account_subsidiary_ledgers.code','ASC');
        $data_cnt=$sql->count();
        $sql->offset((int)$offset)->limit((int)$length);
        
        $data=$sql->get(['acctg_account_general_ledgers.description as gl_desc','acctg_account_general_ledgers.code as gl_code', 'acctg_account_subsidiary_ledgers.description as sl_desc', 'acctg_account_subsidiary_ledgers.code as sl_code', 'acctg_account_subsidiary_ledgers.id as sl_id'] )
        ->mapWithKeys(function ($sl, $key) {
            $array[$key] = [
                'id' => $sl->sl_id,
                'text' => '['.$sl->gl_code.'] '.$sl->gl_desc." => ".'['.$sl->sl_code.']'.$sl->sl_desc
            ];
            return $array;
        });
        // dd($data);
        $data[] = ['id'=>0,'text'=>'None'];
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
    public function getGL($search="")
    {
        $page=1;
        if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
        }
        $length = 20;
        $offset = ($page - 1) * $length;
        $sql = AcctgAccountGeneralLedger::where([
            'is_active'=>1,
        ]);
        if(!empty($search)){
                $sql->where(function ($sql) use($search) {
                    $sql->orwhere(DB::raw('LOWER(acctg_account_general_ledgers.description)'),'like',"%".strtolower($search)."%")
                        ->orwhere(DB::raw('LOWER(acctg_account_general_ledgers.code)'),'like',"%".strtolower($search)."%");
                });
        }
        $sql->orderBy('acctg_account_general_ledgers.code','ASC');
        $data_cnt=$sql->count();
        $sql->offset((int)$offset)->limit((int)$length);
        
        $data=$sql->get( )
        ->mapWithKeys(function ($gl, $key) {
            $array[$key] = [
                'id' => $gl->id,
                'text' => '['.$gl->code.'] '.$gl->description
            ];
            return $array;
        });

        $data[] = ['id'=>0,'text'=>'None'];
        // dd($data);
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
}

