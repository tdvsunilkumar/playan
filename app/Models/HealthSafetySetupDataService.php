<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;
class HealthSafetySetupDataService extends Model
{
    public $table = 'ho_services';
    public function scopeActive($query)
    {
        $query->where('ho_services', 1);
    }
    public function getServiceNameAttribute()
    {
        return DB::table('cto_tfocs AS ctot')
                ->leftjoin('acctg_account_general_ledgers AS aal', 'aal.id', '=', 'ctot.gl_account_id')
                ->leftjoin('acctg_account_subsidiary_ledgers AS aas', 'aas.id', '=', 'ctot.sl_id')
                ->select('aas.description as chartofaccount')
                ->where('ctot.id',$this->tfoc_id)
                ->first()->chartofaccount;
    }
    public function updateActiveInactive($id,$columns){
     return DB::table('ho_services')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('ho_services')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('ho_services')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    
    public function getEditDetails($id){
        return DB::table('ho_services')->where('id',$id)->first();
    }
    public function checkDisable($field)
    {
      $check = 'readonly';
      $fields = config('constants.parametersLabRequest')[$this->ho_service_name];
      if (in_array($field,$fields)) {
        $check = '';
      }
      return $check;
    }
    public function getFieldsAttribute() 
    {
      return config('constants.parametersLabRequest')[$this->ho_service_name];
    }
	public function getServices(){
    	return DB::table('cto_tfocs as ctot')
		  ->leftjoin('acctg_account_general_ledgers AS aal', 'aal.id', '=', 'ctot.gl_account_id')
          ->leftjoin('acctg_account_subsidiary_ledgers AS aas', 'aas.id', '=', 'ctot.sl_id')
		  ->select('ctot.id','aal.code','aal.description as gldescription','aas.prefix','aas.description')
		  ->where('ctot.tfoc_status','=',1)->where('ctot.tfoc_is_applicable','=',6)->get();
    }
	public function getTransactiontype(){
        return DB::table('cto_top_transaction_type')
		->select('id','ttt_desc')
		->where('tfoc_is_applicable',6)->get();
    }
	public function GetServicename($id){
      return DB::table('cto_tfocs AS ctot')
	  ->leftjoin('acctg_account_general_ledgers AS aal', 'aal.id', '=', 'ctot.gl_account_id')
	  ->leftjoin('acctg_account_subsidiary_ledgers AS aas', 'aas.id', '=', 'ctot.sl_id')
	  ->select('aas.description as chartofaccount')->where('ctot.id',$id)->get();
    }
    public function getList($request){

        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');

        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }


        $columns = array( 
          0 =>"hose.id",
          1 =>"tfoc_id",
          2 =>"ho_service_name",
		  3 =>"ho_service_description",
          4 =>"ho_service_department",
          5 =>"top_transaction_type_id",
          6 =>"ho_service_form",
          7 =>"hose.ho_is_active"
           
        );

        $sql = DB::table('ho_services As hose')
			    ->leftjoin('cto_top_transaction_type AS cttt', 'cttt.id','hose.top_transaction_type_id')
				->leftjoin('cto_tfocs AS ctot', 'ctot.id','hose.tfoc_id')
			    ->leftjoin('acctg_account_general_ledgers AS aal', 'aal.id', '=', 'ctot.gl_account_id')
				->leftjoin('acctg_account_subsidiary_ledgers AS aas', 'aas.id', '=', 'ctot.sl_id')
               ->select([
                'hose.id',
                'hose.tfoc_id as tfocID',
                'hose.ho_service_name',
                'hose.ho_service_description',
                'hose.ho_service_department',
                'hose.top_transaction_type_id',
                'hose.ho_service_form',
                'hose.ho_is_active',
                'aal.code as gl_code',
                'aal.description as gl_description',
                'aas.prefix as sl_prefix','aas.description as sl_description'
              ]);
              //  ->where('ctot.tfoc_is_applicable','=',6);
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(hose.ho_service_description)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(hose.ho_service_name)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(aal.code)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(aal.description)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(aas.prefix)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(aas.description)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(cttt.ttt_desc)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(hose.ho_is_active)'),'like',"%".strtolower($q)."%")
				->orWhere(function ($sql) use ($q) {
					  if ($q === 'Laboratory' || $q === 'laboratory') {
						  $sql->where('hose.ho_service_department', '=', 1);
					  } elseif ($q === 'Outpatient' || $q === 'outpatient') {
						  $sql->where('hose.ho_service_department', '=', 2);
					  }elseif ($q === 'Sanitary' || $q === 'sanitary') {
						  $sql->where('hose.ho_service_department', '=', 3);
					  }elseif ($q === 'Business Permit' || $q === 'business permit') {
						  $sql->where('hose.ho_service_department', '=', 4);
					  }elseif ($q === 'Civil Registrar' || $q === 'civil registrar') {
						  $sql->where('hose.ho_service_department', '=', 5);
					  }
				})
				->orWhere(function ($sql) use ($q) {
					  if ($q === 'Hematology Form' || $q === 'hematology form') {
						  $sql->where('hose.ho_service_form', '=', 1);
					  } elseif ($q === 'Serology Form' || $q === 'serology form') {
						  $sql->where('hose.ho_service_form', '=', 2);
					  }elseif ($q === 'Urinalysis Form' || $q === 'urinalysis form') {
						  $sql->where('hose.ho_service_form', '=', 3);
					  }elseif ($q === 'Fecalysis Form' || $q === 'fecalysis form') {
						  $sql->where('hose.ho_service_form', '=', 4);
					  }elseif ($q === 'Pregnancy Test Form' || $q === 'pregnancy test form') {
						  $sql->where('hose.ho_service_form', '=', 5);
					  }
				})
                ; 
            });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('hose.id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }   

      public function getService($search=""){
        $page=1;
        if(isset($_REQUEST['page'])){
          $page = (int)$_REQUEST['page'];
        }
        $length = 20;
        $offset = ($page - 1) * $length;
  
        $sql = self::where([['ho_is_active',1],['ho_service_department',1]]);
        if(!empty($search)){
          $sql->where(function ($sql) use($search) {
            if(is_numeric($search)){
              $sql->Where('id',$search);
            }else{
              $sql->where(DB::raw('LOWER(ho_service_name)'),'like',"%".strtolower($search)."%")
              ->orWhere(DB::raw('LOWER(ho_service_description)'),'like',"%".strtolower($search)."%");
            }
          });
        }
        $sql->orderBy('ho_service_name','ASC');
        $data_cnt=$sql->count();
        $sql->offset((int)$offset)->limit((int)$length);
  
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
	  
	  public function getServicePermit($search=""){
        $page=1;
        if(isset($_REQUEST['page'])){
          $page = (int)$_REQUEST['page'];
        }
        $length = 20;
        $offset = ($page - 1) * $length;
  
        $sql = self::where([['ho_is_active',1],['ho_service_department',5]]);
        if(!empty($search)){
          $sql->where(function ($sql) use($search) {
            if(is_numeric($search)){
              $sql->Where('id',$search);
            }else{
              $sql->where(DB::raw('LOWER(ho_service_name)'),'like',"%".strtolower($search)."%")
              ->orWhere(DB::raw('LOWER(ho_service_description)'),'like',"%".strtolower($search)."%");
            }
          });
        }
        $sql->orderBy('ho_service_name','ASC');
        $data_cnt=$sql->count();
        $sql->offset((int)$offset)->limit((int)$length);
  
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }

      // labreq printing
      public function getLabServices()
      {
        $res = HealthSafetySetupDataService::select(['ho_service_name','id'])
        ->where([
          'ho_services.ho_service_department' => 1,
          'ho_services.ho_is_active' => 1
        ])
            ->get();
            return $res;
      }
}
