<?php

namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use App\Models\Report\CompositionOfLguFees;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use File;
use Response;

class CompositionOfLguFeesContt extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
     public function __construct(){
        $this->_CompositionOfLguFees = new CompositionOfLguFees(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'');  
        $this->slugs = 'reports-masterlists-composition-of-lgu-fees';
    }
    public function index(Request $request)
    {
        // $this->is_permitted($this->slugs, 'read');         
        return view('report.compositionoflgufees.index');
    
    }

    public function getList(Request $request){
        // $this->is_permitted($this->slugs, 'read'); 
        $data=$this->_CompositionOfLguFees->getList($request);

        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
		
        foreach ($data['data'] as $row){
        	$sr_no=$sr_no+1;
			
			$description = wordwrap($row->subclass_description, 40, "<br />\n");
			
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['subclass_description']="<div class='showLess'>".$description."</div>";
            $arr[$i]['app_type']=$row->app_type;
            $arr[$i]['ctype_desc']=$row->ctype_desc;
            $arr[$i]['ptfoc_effectivity_date']=$row->ptfoc_effectivity_date;
            $arr[$i]['action']='aa';
            $i++; 
        }
        
        $totalRecords=$data['data_cnt'];
        $json_data = array(
            "recordsTotal"    => intval( $totalRecords ),  
            "recordsFiltered" => intval($totalRecords),
            "data"            => $arr   // total data array
        );
        echo json_encode($json_data);
    }

}
