<?php

namespace App\Models\Bplo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;
use PDF;
use DB;

class CtoPaymentOrSetup extends Model
{
    public function updateActiveInactive($id,$columns){
     return DB::table('cto_payment_or_setups')->where('id',$id)->delete();
    }
    
    public function updateData($id,$columns){
        return DB::table('cto_payment_or_setups')->where('id',$id)->update($columns);
    }

    public function addData($postdata){
        DB::table('cto_payment_or_setups')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }

    public function getORSetups($request){
      $data = DB::table('cto_payment_or_setups')->where('user_id', $request->user_id)->where('or_field_form', $request->or_field_form)->first();
      DB::table('cto_payment_or_setups')->where('id', $request->id)->update(['width' => $data->width, 'height' => $data->height, 'is_portrait' => $data->is_portrait, 'setup_details' => $data->setup_details, 'ors_remarks' => $data->ors_remarks]);
      return $data;
    }

    public function getSystemORType(){
        return DB::table('cto_payment_or_fields')->groupBy('or_field_form')->get()->toArray();
    }

    public function getUserORType(){
      return DB::table('cto_payment_or_types')->select('id','ortype_name')->where('ora_status',1)->orderBy('ortype_name', 'ASC')->get()->toArray();
    }

    public function getORTypeForFieldName($or_field_form){
      return DB::table('cto_payment_or_fields')->where('or_field_form', $or_field_form)->get()->toArray();
    }

    public function getFormDetails($or_field_form){
      return DB::table('cto_payment_or_forms')->where('or_form', $or_field_form)->first();
    }

    public function getUserDetails($data){
      return DB::table('cto_payment_or_setups AS a')
      ->join('hr_employees AS b', 'a.user_id', 'b.user_id')
      ->where('b.is_active', 1)
      ->where('a.user_id','!=', Auth::user()->id)
      ->where('a.form_id', $data->form_id)
      ->groupBy('a.user_id')
      ->orderBy('b.fullname', 'ASC')
      ->select('b.fullname', 'a.user_id')
      ->get();
    }

    public function getFormFieldsDetailsById($form_id){
      return DB::table('cto_payment_or_fields')->where('form_id', $form_id)->get();
    }

    public function setPrintSample($params){

      $border = 0;
      $topPos = 42;
      $rightPos = 12;
      $width = $params->width;
      $height = $params->height;
      $orientation = "L";
      if($params->is_portrait == 1){
        $orientation = "P";
      }

      PDF::SetTitle($params->or_field_form);    
      PDF::SetMargins(10, 10, 10,10);
      PDF::SetAutoPageBreak(FALSE, 0);
      PDF::AddPage($orientation, 'DL');
      PDF::SetFont('Helvetica', '', 10);
      // return $width;
      // return PDF::getPageWidth();
      if(isset($params->setup_details)){
        $params->setup_details = json_decode($params->setup_details,false);

        foreach ($params->setup_details as $key => $data) {
          if($data){
              $data = (array)$data;
              $topPos = $data[$key.'_position_top'];
              $rightPos = $data[$key.'_position_right'];
              $bottomPos = $data[$key.'_position_bottom'];
              $leftPos = $data[$key.'_position_left'];
              
              //writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true) $x and $y if for positioning
              if(isset($data[$key.'_is_visible'])){
                PDF::SetFont('Helvetica', isset($data[$key.'_font_is_bold']) ? 'B' : '', $data[$key.'_font_size']);
                PDF::writeHTMLCell($width, $height, $leftPos + $bottomPos,$rightPos + $topPos,$data[$key.'_name'], $border);
              }
              
          }
        }

      }

      PDF::Output('Receipt.pdf');
    }

    public function getSingleORType($id){
      return DB::table('cto_payment_or_fields')->where('id', $id)->first();
    }
    public function getEditDetails($id){
        return DB::table('cto_payment_or_setups')->where('id',$id)->first();
    }

    public function getEditDetailsforsample($id){
        return DB::table('cto_payment_or_setups')->select('setup_details')->where('id',$id)->first();
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
          0 =>"id",
          1 =>"ortype_name",
          2=>"ors_remarks",
          3 =>"ors_is_active"
        );

        $sql = DB::table('cto_payment_or_setups AS os')
              ->leftJoin('cto_payment_or_types AS ort', 'os.ortype_id', '=', 'ort.id')
              ->where('os.user_id', Auth::user()->id)
              ->where('os.department',1)
              ->select('os.*','ort.ortype_name');

        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(ortype_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(ors_remarks)'),'like',"%".strtolower($q)."%"); 
            });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('os.id','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}
