<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use DB;
class CommonController extends Controller
{
    public function __construct(){
        $this->_commonmodel = new CommonModelmaster();
    }

    public function getBarngayList(Request $request){
        $search = $request->input('search');
        $arrRes = $this->_commonmodel->getBarangay($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']=$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }
    public function getBrgyDetails(Request $request){
        $search = $request->input('brgy_id');
        if ($search) {
            $arrRes = $this->_commonmodel->getBarangay($search);
            echo json_encode($arrRes['data'][0]);
        }
    }
    public function getBarngayMunList(Request $request){
        $search = $request->input('search');
        $arrRes = $this->_commonmodel->getBarangay($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']=$val->brgy_name.", ".$val->mun_desc;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }
    public function getBploRpt(Request $request){
        $search = $request->input('search');
        $arrRes = $this->_commonmodel->getBploRpt($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']=$val->rp_tax_declaration_no;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }

    public function getBploRptVar(Request $request){
        $search = $request->input('search');
        $arrRes = $this->_commonmodel->getBploRptVar($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']=$val->rp_tax_declaration_no;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }

    public function getBploTaxpayersAutoSearchList(Request $request){
        $search = $request->input('search');
        $arrRes = $this->_commonmodel->getBploTaxpayersAutoSearchList($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']=$val->full_name;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }

    public function getBarngayNameList(Request $request){
        $search = $request->input('search');
        $arrRes = $this->_commonmodel->getBarangay($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']=$val->brgy_name;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }
    public function getTaxDecration(Request $request){
        $search = $request->input('search');
        $arrRes = $this->_commonmodel->getTaxDecration($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']=$val->rp_tax_declaration_no.'=>'.$val->full_name;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }
    public function getBarngayLisByRpt(Request $request){
        $search = $request->input('search');
        $rpt_locality=$this->_commonmodel->bploLocalityDetails();
        $arrRes = $this->_commonmodel->getBarngayLisByRpt($search,$rpt_locality->mun_no);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']=$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }
    public function getBarngayLisByRptFlt(Request $request){
        $search = $request->input('search');
        $rpt_locality=$this->_commonmodel->bploLocalityDetails();
        $arrRes = $this->_commonmodel->getBarngayLisByRpt($search,$rpt_locality->mun_no);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            if($key == 0)
            {
                $arr['data'][$key]['id']=0;
                $arr['data'][$key]['text']="All";
            }
                $arr['data'][$key+1]['id']=$val->id;
                $arr['data'][$key+1]['text']=$val->brgy_name;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }
    
    public function getPsicSubclass(Request $request){
        $search = $request->input('search');
        $arrRes = $this->_commonmodel->getPsicSubclass($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']=$val->subclass_code."-".$val->subclass_description;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }
    public function getAllBusinessList(Request $request){
        $search = $request->input('search');
        $arrRes = $this->_commonmodel->getAllBusinessList($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']='['.$val->busns_id_no."]=>[".$val->busn_name."]";
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }

    public function getActiveBusinessList(Request $request){
        $search = $request->input('search');
        $arrRes = $this->_commonmodel->getActiveBusinessList($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']='['.$val->busns_id_no."]=>[".$val->busn_name."]";
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }

    public function checkOrInrange(Request $request){
        $id = $request->input('id');
        $getortype = $this->_commonmodel->GetOrtypeid($id);
        $ortype_id =  $getortype->ortype_id; 
        $or_no = $request->input('or_no');
        $isUsed = $this->_commonmodel->checkOrinrange($or_no,$ortype_id);
        $arr['isUsed']=$isUsed;
        if(empty($isUsed)){
            $arr['errMsg']='This O.R. No. not available in O.R. Range';
        }
        echo json_encode($arr);
    }

   public function digitalLoad(Request $request){
        $url = trim(str_replace('url=','', $_SERVER['QUERY_STRING']));
        $urltemp = $url;
        $signType = $this->_commonmodel->getSettingData('sign_settings');
        $prev_url = url()->previous();
        $prev_url = str_replace('dev/','',$prev_url);
        $prev_url = str_replace('uat/','',$prev_url);
        $url_slug = app('router')->getRoutes()->match(app('request')->create($prev_url))->uri();
        // dd($url_slug, url()->previous(), $url_slug->getPrefix());

        $refslug = trim(str_replace('url=http://'.$_SERVER['HTTP_HOST'].'/','', $_SERVER['QUERY_STRING'])); 
        $refslug = trim(str_replace('url=','', $refslug));
        if( strpos( $refslug, '?' ) !== false) {
              $slugdata = explode('?',$refslug);
               $refslug = $slugdata[0];
            }

        $refslug = str_replace('dev/','',$refslug);
        $refslug = str_replace('uat/','',$refslug);
         if( strpos( $refslug, 'engjobrequest/print-order-of-payment' ) !== false) {
              $refslug = substr($refslug, 0, 36);
            }
        $lastvar = basename(parse_url($refslug, PHP_URL_PATH));
        if(is_int($lastvar)){
             $refslug = str_replace($lastvar,'',$refslug);
             $refslug = substr($refslug, 0, -1);
        }
        $isactive = 0;
         $sign_app = DB::table('sign_applications')->select('status')
                ->where('print_slug',$refslug)->get();
            
         if(count($sign_app) == 0){
            $isactive = 1;
         }       
         foreach ($sign_app as $key => $value) {
                  if($value->status ==1){
                    $isactive =1;
                  }
         }  
        if ($signType === "2" && $isactive =='1') {
            return view('common.loader',compact('url','signType'));
        }
        return redirect()->to($url);
    }

    public function digitalLoadold(Request $request){
            $url = trim(str_replace('url=','', $_SERVER['QUERY_STRING']));
            $signType = $this->_commonmodel->getSettingData('sign_settings');
            $prev_url = url()->previous();
            $prev_url = str_replace('dev/','',$prev_url);
            $prev_url = str_replace('uat/','',$prev_url);
            $url_slug = app('router')->getRoutes()->match(app('request')->create($prev_url))->uri();
            // dd($url_slug, url()->previous(), $url_slug->getPrefix());
            $modules = DB::table('menu_modules')
            ->select(
                DB::raw('1 as type'),
                'menu_group_id as menu_id',
                'id'
            )
            ->where('slug',$url_slug);
            $submodules = DB::table('menu_sub_modules')
            ->select(
                DB::raw('2 as type'),
                'menu_module_id as menu_id',
                'id'
            )
            ->union($modules)
            ->where('slug',$url_slug)
            ->first();
            $sign_app = collect();
            if ($submodules ) {
                if ($submodules->type === 1) {
                    // from menu_modules
                    $sign_app = DB::table('sign_applications')
                    ->where([
                        'menu_group_id' => $submodules->menu_id,
                        'menu_module_id' => $submodules->id,
                        'status' => 1
                    ])->get();
                }
                else if ($submodules->type === 2) {
                    // from menu_modules
                    $sign_app = DB::table('sign_applications')
                    ->where([
                        'menu_module_id' => $submodules->menu_id,
                        'menu_sub_id' => $submodules->id,
                        'status' => 1
                    ])->get();
                } 
            }

            if ($signType === "2" && $sign_app->isNotEmpty()) {
                return view('common.loader',compact('url','signType'));
            }
            return redirect()->to($url);
        }

 public function digitalLoadnew(Request $request){
        $url = trim(str_replace('url=','', $_SERVER['QUERY_STRING']));
        $signType = $this->_commonmodel->getSettingData('sign_settings');
        $prev_url = url()->previous();
        $prev_url = str_replace('dev/','',$prev_url);
        $prev_url = str_replace('uat/','',$prev_url);

        $url_slug = app('router')->getRoutes()->match(app('request')->create($url))->uri();
        $url_slug = str_replace('dev/','',$url_slug);
        $url_slug = str_replace('uat/','',$url_slug);
        //echo $url_slug; exit;
        // dd($url_slug, url()->previous(), $url_slug->getPrefix());
        // $modules = DB::table('menu_modules')
        // ->select(
        //     DB::raw('1 as type'),
        //     'menu_group_id as menu_id',
        //     'id'
        // )
        // ->where('slug',$url_slug);
        // $submodules = DB::table('menu_sub_modules')
        // ->select(
        //     DB::raw('2 as type'),
        //     'menu_module_id as menu_id',
        //     'id'
        // )
        // ->union($modules)
        // ->where('slug',$url_slug)
        // ->first();
        // $sign_app = collect();
        // if ($submodules ) {
        //     if ($submodules->type === 1) {
        //         // from menu_modules
        //         $sign_app = DB::table('sign_applications')
        //         ->where([
        //             'menu_group_id' => $submodules->menu_id,
        //             'menu_module_id' => $submodules->id,
        //             'status' => 1
        //         ])->get();
        //     }
        //     else if ($submodules->type === 2) {
        //         // from menu_modules
        //         $sign_app = DB::table('sign_applications')
        //         ->where([
        //             'menu_module_id' => $submodules->menu_id,
        //             'menu_sub_id' => $submodules->id,
        //             'status' => 1
        //         ])->get();
        //     } 
        // }
         $isactive = 0;
         $sign_app = DB::table('sign_applications')->select('status')
                ->where('print_slug',$url_slug)->get();
         if(count($sign_app) == 0){
            $isactive = 1;
         }       
         foreach ($sign_app as $key => $value) {
                  if($value->status ==1){
                    $isactive =1;
                  }
         }  
        if ($signType === "2" && $isactive =='1') {
            $url = str_replace('dev/','',$url);
            $url = str_replace('uat/','',$url);
            return view('common.loader',compact('url','signType'));
        }
        return redirect()->to($url);
    }

  }  
