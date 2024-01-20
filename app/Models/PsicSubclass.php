<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use DB;


class PsicSubclass extends Model
{
  public function updateData($id,$columns){
    return DB::table('psic_subclasses')->where('id',$id)->update($columns);
  }

  public function addData($postdata){
		DB::table('psic_subclasses')->insert($postdata);
    return DB::getPdo()->lastInsertId();
	}
  public function sectionAllData()
  {
     return DB::table('psic_sections')
            ->select('*')->where('section_status','1')->get();
  }
  public function sectionAjaxList($search=""){
      $page=1;
      if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
      }
      $length = 20;
      $offset = ($page - 1) * $length;

      $sql = DB::table('psic_sections')
            ->select('*')->where('section_status','1');
        $sql->where(function ($sql) use($search) {
          if(is_numeric($search)){
            $sql->Where('id',$search);
          }else{
            $sql->where(DB::raw('LOWER(section_code)'),'like',"%".strtolower($search)."%")
                 ->orWhere(DB::raw("CONCAT('section_code, ' - ', section_description')"), 'like', "%" . $search . "%");
          }
        });
      
      $sql->orderBy('section_description','ASC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
    public function divisionAjaxList($request){
        $term=$request->input('term');
        $id = $request->id;
        $query = DB::table('psic_divisions')
            ->select('*', DB::raw('CONCAT(division_code, "-", division_description) as text'))->where('division_status','1')->where('section_id','=',$id);
            if(!empty($term) && isset($term)){
            $query->where(function ($sql) use($term) {   
                $sql->where(DB::raw('LOWER(division_code)'),'like',"%".strtolower($term)."%")
                 ->orWhere(DB::raw("CONCAT(division_code, ' - ', division_description)"), 'like', "%" . $term . "%");
            });

        }  
        $data = $query->simplePaginate(20);             
        return $data;
    }
  public function divisionAllData($section_id="")
  {
     return DB::table('psic_divisions')
            ->select('*')->where('division_status','1')->where('section_id','=',$section_id)->get();
  }
  public function groupAjaxList($request){
        $term=$request->input('term');
        $id = $request->id;
        $query = DB::table('psic_groups')
            ->select('*', DB::raw('CONCAT(group_code, "-", group_description) as text'))->where('is_active','1')->where('division_id','=',$id);
            if(!empty($term) && isset($term)){
            $query->where(function ($sql) use($term) {   
                $sql->where(DB::raw('LOWER(group_code)'),'like',"%".strtolower($term)."%")
                 ->orWhere(DB::raw("CONCAT(group_code, ' - ', group_description)"), 'like', "%" . $term . "%");
            });

        }  
        $data = $query->simplePaginate(20);             
        return $data;
    }
  public function groupAllData($division_id="")
  {
     return DB::table('psic_groups')
            ->select('*')->where('is_active','1')->where('division_id','=',$division_id)->get();
  }
  public function classAjaxList($request){
        $term=$request->input('term');
        $id = $request->id;
        $query = DB::table('psic_classes')
            ->select('*', DB::raw('CONCAT(class_code, "-", class_description) as text'))->where('is_active','1')->where('group_id','=',$id);
            if(!empty($term) && isset($term)){
            $query->where(function ($sql) use($term) {   
                $sql->where(DB::raw('LOWER(class_code)'),'like',"%".strtolower($term)."%")
                 ->orWhere(DB::raw("CONCAT(class_code, ' - ', class_description)"), 'like', "%" . $term . "%");
            });

        }  
        $data = $query->simplePaginate(20);             
        return $data;
    }
  public function classAllData($group_id="")
  {
     return DB::table('psic_classes')
            ->select('*')->where('is_active','1')->where('group_id','=',$group_id)->get();
  }
  
	public function sectioncode()
	{
     return DB::table('psic_divisions AS a')
            ->join('psic_sections AS b', 'b.id', '=', 'a.section_id')
            ->select('a.id','a.section_id','a.division_code','a.division_description','b.section_code','b.section_description')->where('a.division_status',1)->where('b.section_status','1')->get();
	}
  
  public function sectioncodeList($search=""){
      $page=1;
      if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
      }
      $length = 20;
      $offset = ($page - 1) * $length;

      $sql = DB::table('psic_divisions AS a')
            ->join('psic_sections AS b', 'b.id', '=', 'a.section_id')
            ->select('a.id','a.section_id','a.division_code','a.division_description','b.section_code','b.section_description')->where('a.division_status',1)->where('b.section_status','1');
        $sql->where(function ($sql) use($search) {
          if(is_numeric($search)){
            $sql->Where('a.id',$search);
          }else{
            $sql->where(DB::raw('LOWER(b.section_code)'),'like',"%".strtolower($search)."%")
                 ->orWhere(DB::raw("CONCAT('[', b.section_code, ' - ', b.section_description, ']=>[', a.division_code, ' - ', a.division_description, ']')"), 'like', "%" . $search . "%");
          }
        });
      
      $sql->orderBy('a.division_description','ASC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
    public function groupClassCode($division_id=0)
  {
     return DB::table('psic_classes AS a')
            ->join('psic_groups AS b', 'b.id', '=', 'a.group_id')
            ->select('a.*','b.group_code','b.group_description')->where('a.is_active',1)->where('b.is_active','1')->where('a.division_id','=',$division_id)->get();
  }
  public function classcodeList($request){
        $term=$request->input('term');
        $id = $request->id;
        $query = DB::table('psic_classes AS a')
            ->join('psic_groups AS b', 'b.id', '=', 'a.group_id')
            ->select('a.id', DB::raw('CONCAT("[", b.group_code, "-", b.group_description, "]=>[", a.class_code, "-", a.class_description, "]") as text'))
            ->where('a.is_active', 1)
            ->where('b.is_active', 1)
            ->where('a.division_id', '=', $id);          
            if(!empty($term) && isset($term)){
            $query->where(function ($sql) use($term) {   
                $sql->where(DB::raw('LOWER(b.group_code)'),'like',"%".strtolower($term)."%")
                 ->orWhere(DB::raw("CONCAT('[', b.group_code, ' - ', b.group_description, ']=>[', a.class_code, ' - ', a.class_description, ']')"), 'like', "%" . $term . "%");
            });

        }  
        $data = $query->simplePaginate(20);             
        return $data;
    }
  
  public function divisioncode($id)
  {
    return DB::table('psic_divisions')->select('id','division_description')->where('division_status','1')->where('id','=',$id)->get();
  }
  public function getLastORDetails($busn_id){
    return DB::table('cto_cashier')->select('cashier_or_date','or_no')->where('busn_id',(int)$busn_id)->orderBy('id','DESC')->first();
  }
  public function getPaymentStatus($busn_id){
    return DB::table('cto_bplo_final_assessment_details')->select('payment_status')->where('busn_id',(int)$busn_id)->orderBy('id','DESC')->first();
  }

  
  public function getEstabilshment($request){
    $params = $columns = $totalRecords = $data = array();
    $params = $_REQUEST;
    $subclass_id=$request->input('subclass_id');
    if(!isset($params['start']) && !isset($params['length'])){
      $params['start']="0";
      $params['length']="10";
    }

    $columns = array( 
      1 =>"busn_name"
    );
    $sql = DB::table('bplo_business_psic AS ps')
    ->join('psic_subclasses AS s', 's.id', '=', 'ps.subclass_id')
    ->join('bplo_business AS b', 'b.id', '=', 'ps.busn_id')
    ->select('b.busn_name','b.id AS busn_id','b.app_code')->where('ps.subclass_id',$subclass_id);

    /*  #######  Set Order By  ###### */
    if(isset($params['order'][0]['column']))
      $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    else
      $sql->orderBy('s.id','ASC');

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
     
  }
  public function getSectionRequirement($section_id){
    return DB::table('psic_section_requirements')->select('requirement_json','apptype_id')->where('section_id',(int)$section_id)->get()->toArray();
  }
	
	public function groupcode($division_id=0,$section_id=0)
	{
    return DB::table('psic_groups')->select('id','group_code','group_description')->where('is_active','1')->where('division_id','=',$division_id)->where('section_id','=',$section_id)->get();
	}
	public function classcode($division_id=0)
	{
   return DB::table('psic_classes AS a')
            ->join('psic_groups AS b', 'b.id', '=', 'a.group_id')
            ->select('a.*','b.group_code','b.group_description')->where('a.is_active',1)->where('b.is_active','1')->where('a.division_id','=',$division_id)->get();
	}
  public function getsubclassbyclass($class_id=0,$group_id=0,$division_id=0,$section_id=0)
  {
    return DB::table('psic_subclasses')->select('id','subclass_code','subclass_description')->where('is_active','1')->where('class_id','=',$class_id)->where('group_id','=',$group_id)->where('division_id','=',$division_id)->where('section_id','=',$section_id)->get();

  }
  public function getSectionCharges($section_id){
    return DB::table('psic_tfocs')->select('*')->where('ptfoc_access_type','=',1)->where('section_id','=',(int)$section_id)->get()->toArray();
  }

	public function getdivisionsbkp()
  {
      return $this->hasMany('App\Models\PsicSection', 'id', 'section_id');
  }
  public function getsubclass()
  {
  	return DB::table('psic_subclasses as ps')->join('psic_sections', 'ps.section_id', '=', 'psic_sections.id')->join('psic_divisions', 'ps.division_id', '=', 'psic_divisions.id')->join('psic_groups', 'ps.group_id', '=', 'psic_groups.id')->join('psic_classes', 'ps.class_id', '=', 'psic_classes.id')->select('ps.id','section_code','division_code','group_code','class_code','subclass_code','subclass_description','ps.is_active')->where('subclass_generated_by', '=', \Auth::user()->creatorId())->get();
	}

  public function allPsicSubclass($vars = '')
  {
    
      $psic_subclasses =DB::table('psic_subclasses AS psc')
      ->select('psc.*')
      ->where('psc.is_active',1)
      ->orderBy('psc.id')
      ->get();
      $brgys = array();
      if (!empty($vars)) {
          $brgys[] = array('' => 'select a '.$vars);
      } else {
          $brgys[] = array('' => 'Please select...');
      }
      foreach ($psic_subclasses as $psic_subclass) {
        // $desc=Str::limit($psic_subclass->subclass_description, 10); 
          $brgys[] = array(
              $psic_subclass->id => $psic_subclass->subclass_code."-". $psic_subclass->subclass_description
          );
      }

      $psic_subclasses = array();
      foreach($brgys as $brgy) {
          foreach($brgy as $key => $val) {
              $psic_subclasses[$key] = $val;
          }
      }

      return $psic_subclasses;
  }

  public function reload_sub_class()
  {
    $psic_subclasses = DB::table('psic_subclasses AS psc')
    ->rightjoin('psic_tfocs AS pt', 'psc.id', '=', 'pt.subclass_id')
    ->where('pt.ptfoc_is_active',1)
    ->where('psc.is_active',1)
    ->select('psc.*')
    ->distinct('pt.subclass_id')
    ->orderBy('psc.id')
    ->get();

      return $psic_subclasses;
  }


  
	public function getList($request){
    $params = $columns = $totalRecords = $data = array();
    $params = $_REQUEST;
    $Section=$request->input('Section');
    $Division=$request->input('Division');
    $Group=$request->input('Group');
    $ClassId=$request->input('ClassId');
    $q=$request->input('q');

    if(!isset($params['start']) && !isset($params['length'])){
      $params['start']="0";
      $params['length']="10";
    }

    $columns = array( 
      1 =>"subclass_code",
      2 =>"section_code",
      3 =>"division_description",
      4 =>"group_description",
      5 =>"class_description",	
      6 =>"subclass_description",
      7 =>"subclass_description",
      8 =>"is_active"     
    );


    $sql = DB::table('psic_subclasses AS psc')
          ->join('psic_sections AS ps', 'ps.id', '=', 'psc.section_id')
          ->join('psic_divisions AS pd', 'psc.division_id', '=', 'pd.id')
          ->join('psic_groups AS pg', 'psc.group_id', '=', 'pg.id')
          ->join('psic_classes AS pc', 'psc.class_id', '=', 'pc.id')
          ->select('psc.*','section_code','section_description','class_code','division_code','division_description','group_code','group_description','subclass_code','class_description','subclass_description','psc.is_active');

    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
          if(!empty($Section) && isset($Section)){
            $sql->where('psc.section_id','=',$Section);  
        }
         if(!empty($Division) && isset($Division)){
            $sql->where('psc.division_id','=',$Division);  
        }
        if(!empty($Group) && isset($Group)){
            $sql->where('psc.group_id','=',$Group);  
        }
        if(!empty($ClassId) && isset($ClassId)){
            $sql->where('psc.class_id','=',$ClassId);  
        }
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(class_description)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(section_description)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(division_description)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(group_description)'),'like',"%".strtolower($q)."%")
          ->orWhere(DB::raw('LOWER(subclass_description)'),'like',"%".strtolower($q)."%");
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
}
