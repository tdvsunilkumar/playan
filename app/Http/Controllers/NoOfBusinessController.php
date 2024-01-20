<?php

namespace App\Http\Controllers;

use App\Models\NoOfBusiness;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;
class NoOfBusinessController extends Controller
{
    
    public $data = [];
    public function __construct(){
        $this->_noofbusiness = new NoOfBusiness();
		$this->data = array('id'=>''); 
    }
    
    
    public function index(Request $request)
    {
   $PsicSections = $this->_noofbusiness->sectioncode();
   $getdivisions = [];
   $all_data=$this->_noofbusiness->getAllSubClasses(); 
    foreach ($PsicSections as $section) {
        $sectionId = $section->id;

        $divisions = $this->_noofbusiness->getdivisions($sectionId);
        $getdivisions[$sectionId] = $divisions;

        foreach ($divisions as $division) {
            $divisionId = $division->id;
            $groups = $this->_noofbusiness->getGroup($sectionId, $divisionId);
            $getgroup[$divisionId] = $groups;
            foreach ($groups as $group) {
                $groupId = $group->id;
                $class = $this->_noofbusiness->getClass($sectionId, $divisionId, $groupId);
                $getclass[$groupId] = $class;

                foreach ($class as $classs) {
                    $classsId = $classs->id;
                    $subclass = $this->_noofbusiness->getSubClasses($sectionId, $divisionId, $groupId,$classsId);
                    $getSubClasses[$classsId] = $subclass;
                }
                
            }

        }
    }

    // print_r($getdivisions);
    // print_r($getgroup);
    // print_r($getclass);
    // print_r($getSubClasses);
                  
    
        
   
   
    

   return view('noofbusiness.index', compact('PsicSections', 'getdivisions','getgroup','getclass','getSubClasses'));

   }
    public function getList(Request $request){
      
       $getdivisions="";
        $data=$this->_noofbusiness->getList($request);

        $arr=array();
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
             $sectionId=$row->id;
              // dd($getdivisions);exit;
            $j=$j+1;
            $arr[$i]['no']=$j;
             
            $arr[$i]['section_description']=$row->section_description;
               
            $arr[$i]['division_description']='';
            $getdivisions=$this->_noofbusiness->getdivisions($row->id);
            foreach($getdivisions as $getdivisionss){
                $divisionId = $getdivisionss->id;
                $arr[$i]['division_description'] .='<tr><td>'. $getdivisionss->division_description.'</td></tr>';
            }
            $arr[$i]['group_description']='';
            $getgroup_description=$this->_noofbusiness->getGroup($sectionId, $divisionId);
            foreach($getgroup_description as $getgroup_descriptions){
                $arr[$i]['group_description'] .='<tr><td>'. $getgroup_descriptions->group_description.'</td></tr>';
            }
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
