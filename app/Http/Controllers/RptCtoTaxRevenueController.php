<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use File;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\RptProperty;
use App\Models\Barangay;
use App\Models\RptCtoTaxRevenue;
use DB;
use App\Helpers\Helper;

class RptCtoTaxRevenueController extends Controller
{
    public $basicTaxFeeAndOtherCHarges = [];
    public $basicTaxCreditFeeAndOtherCHarges = [];
    public $data = [];
    private $slugs;
    public function __construct(){

        $this->_rptctotaxrevenue = new RptCtoTaxRevenue;
        $this->basicTaxFeeAndOtherCHarges = $this->_rptctotaxrevenue->getTaxFeeAndOtherCharges();
        $this->basicTaxCreditFeeAndOtherCHarges = $this->_rptctotaxrevenue->getCreditFeeAndOtherCharges();
        $this->slugs = 'real-property/tax-revenue';
    }
    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $this->addRecordsInTableIfNotExists();
        $propKinds = DB::table('rpt_property_kinds')->where('pk_is_active',1)->pluck('pk_description','id');
        return view('taxrevenue.index',compact('propKinds'));
        
    }

    public function makeBasisTaxFeeSelectList($row='',$i){
        // if($row->id == 8){
        //     $fee = $this->_rptctotaxrevenue->getCreditFeeAndOtherCharges($row->basic_tfoc_id);
        // }else{
            $fee = $this->_rptctotaxrevenue->getTaxFeeAndOtherCharges($row->basic_tfoc_id);
        // }
        
        if($fee != null){
            // return '['.$fee->subsidarycode.']-'.$fee->subsidarydesc;
             return wordwrap('['.$fee->glcode.'-'.$fee->gldesc.'] => ['.$fee->subsidarycode.'-'.$fee->subsidarydesc.']', 40, "<br />\n");;
        }else{
            return '';
        }
       
    }
    public function makeBasisTaxFeeSelectListD($row='',$i){
        // if($row->id == 8){
        //     $fee = $this->_rptctotaxrevenue->getCreditFeeAndOtherCharges($row->basic_discount_tfoc_id);
        // }else{
            $fee = $this->_rptctotaxrevenue->getTaxFeeAndOtherCharges($row->basic_discount_tfoc_id);
        // }
        
        if($fee != null){
            // return '['.$fee->subsidarycode.']-'.$fee->subsidarydesc;
             return wordwrap('['.$fee->glcode.'-'.$fee->gldesc.'] => ['.$fee->subsidarycode.'-'.$fee->subsidarydesc.']', 40, "<br />\n");;
        }else{
            return '';
        }
       
    }
    public function makeBasisTaxFeeSelectListP($row='',$i){
        // if($row->id == 8){
        //     $fee = $this->_rptctotaxrevenue->getCreditFeeAndOtherCharges($row->basic_penalty_tfoc_id);
        // }else{
            $fee = $this->_rptctotaxrevenue->getTaxFeeAndOtherCharges($row->basic_penalty_tfoc_id);
        // }
        
        if($fee != null){
            // return '['.$fee->subsidarycode.']-'.$fee->subsidarydesc;
             return wordwrap('['.$fee->glcode.'-'.$fee->gldesc.'] => ['.$fee->subsidarycode.'-'.$fee->subsidarydesc.']', 40, "<br />\n");;
        }else{
            return '';
        }
       
    }

    public function makeSefTaxFeeSelectList($row='',$i){
        // if($row->id == 8){
        //     $fee = $this->_rptctotaxrevenue->getCreditFeeAndOtherCharges($row->sef_tfoc_id);
        // }else{
            $fee = $this->_rptctotaxrevenue->getTaxFeeAndOtherCharges($row->sef_tfoc_id);
        // }
        if($fee != null){
           // return '['.$fee->subsidarycode.']-'.$fee->subsidarydesc;
            return wordwrap('['.$fee->glcode.'-'.$fee->gldesc.'] => ['.$fee->subsidarycode.'-'.$fee->subsidarydesc.']', 40, "<br />\n");
        }else{
            return '';
        }
    }
    public function makeSefTaxFeeSelectListD($row='',$i){
        // if($row->id == 8){
        //     $fee = $this->_rptctotaxrevenue->getCreditFeeAndOtherCharges($row->sef_discount_tfoc_id);
        // }else{
            $fee = $this->_rptctotaxrevenue->getTaxFeeAndOtherCharges($row->sef_discount_tfoc_id);
        // }
        if($fee != null){
           // return '['.$fee->subsidarycode.']-'.$fee->subsidarydesc;
            return wordwrap('['.$fee->glcode.'-'.$fee->gldesc.'] => ['.$fee->subsidarycode.'-'.$fee->subsidarydesc.']', 40, "<br />\n");
        }else{
            return '';
        }
    }
    public function makeSefTaxFeeSelectListP($row='',$i){
        // if($row->id == 8){
        //     $fee = $this->_rptctotaxrevenue->getCreditFeeAndOtherCharges($row->sef_penalty_tfoc_id);
        // }else{
            $fee = $this->_rptctotaxrevenue->getTaxFeeAndOtherCharges($row->sef_penalty_tfoc_id);
        // }
        if($fee != null){
           // return '['.$fee->subsidarycode.']-'.$fee->subsidarydesc;
            return wordwrap('['.$fee->glcode.'-'.$fee->gldesc.'] => ['.$fee->subsidarycode.'-'.$fee->subsidarydesc.']', 40, "<br />\n");
        }else{
            return '';
        }
    }

     public function makeShtTaxFeeSelectList($row='',$i){
        // if($row->id == 8){
        //     $fee = $this->_rptctotaxrevenue->getCreditFeeAndOtherCharges($row->sh_tfoc_id);
        // }else{
            $fee = $this->_rptctotaxrevenue->getTaxFeeAndOtherCharges($row->sh_tfoc_id);
        // }
        if($fee != null){
            //dd(wordwrap('['.$fee->glcode.'-'.$fee->gldesc.']=>['.$fee->subsidarycode.'-'.$fee->subsidarydesc.']', 40, "<br />\n"));
            return wordwrap('['.$fee->glcode.'-'.$fee->gldesc.'] => ['.$fee->subsidarycode.'-'.$fee->subsidarydesc.']', 40, "<br />\n");
        }else{
            return '';
        }
    }
    
    public function makeShtTaxFeeSelectListD($row='',$i){
        // if($row->id == 8){
        //     $fee = $this->_rptctotaxrevenue->getCreditFeeAndOtherCharges($row->sh_discount_tfoc_id);
        // }else{
            $fee = $this->_rptctotaxrevenue->getTaxFeeAndOtherCharges($row->sh_discount_tfoc_id);
        // }
        if($fee != null){
            //dd(wordwrap('['.$fee->glcode.'-'.$fee->gldesc.']=>['.$fee->subsidarycode.'-'.$fee->subsidarydesc.']', 40, "<br />\n"));
            return wordwrap('['.$fee->glcode.'-'.$fee->gldesc.'] => ['.$fee->subsidarycode.'-'.$fee->subsidarydesc.']', 40, "<br />\n");
        }else{
            return '';
        }
    }
    public function makeShtTaxFeeSelectListP($row='',$i){
        // if($row->id == 8){
        //     $fee = $this->_rptctotaxrevenue->getCreditFeeAndOtherCharges($row->sh_penalty_tfoc_id);
        // }else{
            $fee = $this->_rptctotaxrevenue->getTaxFeeAndOtherCharges($row->sh_penalty_tfoc_id);
        // }
        if($fee != null){
            //dd(wordwrap('['.$fee->glcode.'-'.$fee->gldesc.']=>['.$fee->subsidarycode.'-'.$fee->subsidarydesc.']', 40, "<br />\n"));
            return wordwrap('['.$fee->glcode.'-'.$fee->gldesc.'] => ['.$fee->subsidarycode.'-'.$fee->subsidarydesc.']', 40, "<br />\n");
        }else{
            return '';
        }
    }

    public function makeTrustfundTaxFeeSelectList($row='',$i){
        // if($row->id == 8){
        //     $fee = $this->_rptctotaxrevenue->getCreditFeeAndOtherCharges($row->tf_tfoc_id);
        // }else{
            $fee = $this->_rptctotaxrevenue->getTaxFeeAndOtherCharges($row->tf_tfoc_id);
        // }
        if($fee != null){
            //return '['.$fee->subsidarycode.']-'.$fee->subsidarydesc;
            return wordwrap('['.$fee->glcode.'-'.$fee->gldesc.']=>['.$fee->subsidarycode.'-'.$fee->subsidarydesc.']', 40, "<br />\n");
        }else{
            return '';
        }
    }

    public function store(Request $request){
           $formData = $request->all();
           $dataTOSave = $formData;
            unset($dataTOSave['id']);
            //dd($dataTOSave);
            unset($dataTOSave['_token']);
            $dataTOSave['updated_by'] = \Auth::user()->creatorId();
            $dataTOSave['updated_at'] = date("Y-m-d H:i:s");

            try {
                $this->_rptctotaxrevenue->updateData($formData['id'],$dataTOSave);
                $success_msg = "Data updated successfully!";
                 $response = [
                'status' => 'success',
                'msg'    => 'Data updated successfully!'
            ];
            } catch (\Exception $e) {
                $success_msg = "Error";
                 $response = [
                'status' => 'error',
                'msg'    => $e->getMessage()
            ];
            }
        //return redirect()->route('taxrevenue.index')->with('success', __($success_msg));
        return response()->json($response);
    }

    public function create(Request $request){
        $data = DB::table('rpt_cto_tax_revenues AS tr')
        ->join('rpt_cto_tax_revenue_names AS rn', 'rn.id', '=', 'tr.trev_id')
        ->select('tr.*','rn.trev_name','rn.trev_description')
        ->where('tr.id',$request->id)->first();
        $taxesAnddFee = [];
        $taxesFeeDesc = [];
        // if($request->id == 8){
        //     $selectListData = $this->basicTaxCreditFeeAndOtherCHarges;
        // }else{
            $selectListData = $this->basicTaxFeeAndOtherCHarges;
        // }
        foreach ($selectListData as $fee) {
            $taxesAnddFee[$fee->id] = '['.$fee->glcode.'-'.$fee->gldesc.']=>['.$fee->subsidarycode.'-'.$fee->subsidarydesc.']';
            $taxesFeeDesc[$fee->id] = $fee->subsidarydesc;
        }
        $propKinds = DB::table('rpt_property_kinds')->where('pk_is_active',1)->pluck('pk_description','id');
        //dd($propKinds);
        return view('taxrevenue.store',compact('data','taxesAnddFee','taxesFeeDesc','propKinds'));
    }

    public function getList(Request $request){
        $data=$this->_rptctotaxrevenue->getList($request);
        //dd($data);
        $arr=array();
        $i="0";    
        $count = $request->start+1;
        foreach ($data['data'] as $row){
            $arr[$i]['sr_no'] = $count;
            $arr[$i]['kind']=$row->pk_description;
            $arr[$i]['tax_name']=$row->trev_name;
            $arr[$i]['tax_description']=$row->trev_description;
            $arr[$i]['tax_what_year']=$row->tax_what_year.'-'.config('constants.taxRevenueYears')[$row->tax_what_year];
            $arr[$i]['basic_tfoc_id']=($row->basic_tfoc_id != '')?"<div class='showLess'>".$this->makeBasisTaxFeeSelectList($row,$i)."</div>":'';
            $arr[$i]['basic_discount_tfoc_id']=($row->basic_discount_tfoc_id != '')?"<div class='showLess'>".$this->makeBasisTaxFeeSelectListD($row,$i)."</div>":'';
            $arr[$i]['basic_penalty_tfoc_id']=($row->basic_penalty_tfoc_id != '')?"<div class='showLess'>".$this->makeBasisTaxFeeSelectListP($row,$i)."</div>":'';

            $arr[$i]['sef_tfoc_id']=($row->sef_tfoc_id != '')?"<div class='showLess'>".$this->makeSefTaxFeeSelectList($row,$i)."</div>":'';
            $arr[$i]['sef_discount_tfoc_id']=($row->sef_discount_tfoc_id != '')?"<div class='showLess'>".$this->makeSefTaxFeeSelectListD($row,$i)."</div>":'';
            $arr[$i]['sef_penalty_tfoc_id']=($row->sef_penalty_tfoc_id != '')?"<div class='showLess'>".$this->makeSefTaxFeeSelectListP($row,$i)."</div>":'';

            $arr[$i]['sh_tfoc_id']=($row->sh_tfoc_id != '')?"<div class='showLess'>".$this->makeShtTaxFeeSelectList($row,$i)."</div>":'';
            $arr[$i]['sh_discount_tfoc_id']=($row->sh_discount_tfoc_id != '')?"<div class='showLess'>".$this->makeShtTaxFeeSelectListD($row,$i)."</div>":'';
            $arr[$i]['sh_penalty_tfoc_id']=($row->sh_penalty_tfoc_id != '')?"<div class='showLess'>".$this->makeShtTaxFeeSelectListP($row,$i)."</div>":'';

            $arr[$i]['action']='<div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/taxrevenue/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Tax Revenue">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            $i++;
            $count++;
            //echo "</div>";
        }
        
        
        $totalRecords = $data['data_cnt'];
        $json_data = array(
            "recordsTotal"    => intval( $totalRecords ),  
            "recordsFiltered" => intval($totalRecords),
            "data"            => $arr   // total data array
        );
        echo json_encode($json_data);
    }

    public function addRecordsInTableIfNotExists($value=''){
        $revenueNames = DB::table('rpt_cto_tax_revenue_names')->get();
        foreach ($revenueNames as $name) {
            if(str_contains($name->trev_description, 'Advance')){
                $taxWhatYear = 1;
            }if(str_contains($name->trev_description, 'Current Year')){
                $taxWhatYear = 2;
            }if(str_contains($name->trev_description, 'Previous Year')){
                $taxWhatYear = 3;
            }if(str_contains($name->trev_description, 'Credit')){
                $taxWhatYear = 2;
            }
            if(str_contains($name->trev_description, 'Prior')){
                $taxWhatYear = 4;
            }
            $dataTOsaveInRevenue = [
                'trev_id' => $name->id,
                'tax_what_year' => $taxWhatYear,
                'basic_tfoc_id' => 0,
                'sef_tfoc_id' => 0,
                'sh_tfoc_id' => 0,
                'tf_tfoc_id' => 0,
                'created_by' => \Auth::user()->creatorId(),
                'created_at' => date("Y-m-d H:i:s"),

            ];
            $checkExisitinRecord = DB::table('rpt_cto_tax_revenues')->where('trev_id',$name->id)->first();
            if($checkExisitinRecord == null){
                $this->_rptctotaxrevenue->addData($dataTOsaveInRevenue);
            }
        }
    }
}
