<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bplo\BploBusinessType;
use DB;

class BploBusinessPsic extends Model
{
    protected $guarded = ['id'];

    public $table = 'bplo_business_psic';
    
    public $timestamps = false;
    
       public function BploBusiness(){
            return $this->belongsTo(BploBusiness::class,'busn_id');
        }
        public function PsicSubclass(){
            return $this->belongsTo(PsicSubclass::class,'subclass_id');
        }
        public function find($id){
            $data=DB::table('bplo_business_psic AS bbp')
                      ->leftjoin('bplo_business AS bb', 'bb.id', '=', 'bbp.busn_id')
                      ->where('bbp.id',$id)
                      ->select('bbp.*','bb.busn_tax_year','bb.app_code')
                      ->first();

            return $data;
        }
        public function verifyUnique($subclass_id,$busn_id){
            return self::where('subclass_id',$subclass_id)->where('busn_id',$busn_id)->count();
        }
        public function remove_busn_plan($id){
            return self::where('id',$id)->delete();
        }
        
        public function getList($request,$busn_id)
        {
            $params = $columns = $totalRecords = $data = array();
            $params = $_REQUEST;
            $q=$request->input('q');
        
            if(!isset($params['start']) && !isset($params['length'])){
              $params['start']="0";
              $params['length']="10";
            }
        
            $columns = array( 
              0 =>"psc.subclass_code",
              1 =>"psc.subclass_description",
              2 =>"bbp.busp_no_units",
              3 =>"bbp.busp_capital_investment",
              4 =>"bbp.busp_essential",	
              5 =>"bbp.busp_non_essential"   
            );
        
            $sql = DB::table('bplo_business_psic AS bbp')
                  ->leftjoin('psic_subclasses AS psc', 'psc.id', '=', 'bbp.subclass_id')
                  ->leftjoin('bplo_business AS bb', 'bb.id', '=', 'bbp.busn_id')
                  ->leftjoin('psic_sections AS ps', 'ps.id', '=', 'psc.section_id')
                  ->leftjoin('psic_divisions AS pd', 'psc.division_id', '=', 'pd.id')
                  ->leftjoin('psic_groups AS pg', 'psc.group_id', '=', 'pg.id')
                  ->leftjoin('psic_classes AS pc', 'psc.group_id', '=', 'pc.id')
                  ->select('bbp.id as ID','subclass_code','subclass_description','busp_no_units','busp_capital_investment','busp_essential','busp_non_essential')
                  ->where('bbp.busn_id',$busn_id);
        
            //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
                if(!empty($q) && isset($q)){
                    $sql->where(function ($sql) use($q) {
                        $sql->where(DB::raw('LOWER(psc.subclass_code)'),'like',"%".strtolower($q)."%")
                            ->orWhere(DB::raw('LOWER(psc.subclass_description)'),'like',"%".strtolower($q)."%");
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
        }

        public function reload_busn_plan($busn_id)
        {
            $items = DB::table('bplo_business_psic AS bbp')
                    ->leftjoin('psic_subclasses AS psc', 'psc.id', '=', 'bbp.subclass_id')
                    ->leftjoin('bplo_business AS bb', 'bb.id', '=', 'bbp.busn_id')
                    ->leftjoin('psic_sections AS ps', 'ps.id', '=', 'psc.section_id')
                    ->leftjoin('psic_divisions AS pd', 'psc.division_id', '=', 'pd.id')
                    ->leftjoin('psic_groups AS pg', 'psc.group_id', '=', 'pg.id')
                    ->leftjoin('psic_classes AS pc', 'psc.group_id', '=', 'pc.id')
                    ->select('bbp.id as ID','bbp.*','subclass_code','subclass_description')
                    ->where('bbp.busn_id',$busn_id)
                    ->orderBy('id', 'asc')
                    ->get();
    
            return $items;
        }
        public function busn_plan_sum($busn_id)
        {
            $items = DB::table('bplo_business_psic AS bbp')
                    ->where('bbp.busn_id',$busn_id)
                    ->sum('bbp.busp_capital_investment');
    
            return $items;
        }
}
