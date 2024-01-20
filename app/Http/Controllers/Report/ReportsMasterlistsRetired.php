<?php

namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use App\Models\Report\listsRetiredModel;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use File;
use Response;

class ReportsMasterlistsRetired extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
     public function __construct(){
        $this->_listsRetiredModel = new listsRetiredModel(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'');  
        $this->slugs = 'reports-masterlists-retired-business';
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        $startdate = date('Y-m-d');   $enddate = date('Y-m-d');
        $startdate=Date('Y-m-d', strtotime('-30 days'));             
        return view('report.listsRetired.index',compact('startdate','enddate'));
    
    }

    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read'); 
        $data=$this->_listsRetiredModel->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
        	$sr_no=$sr_no+1;
			// $complete_address=(!empty($row->busn_office_main_building_no) ? $row->busn_office_main_building_no . ', ' : '') . (!empty($row->busn_office_main_building_name) ? $row->busn_office_main_building_name . ', ' : '') . (!empty($row->busn_office_main_add_block_no) ? $row->busn_office_main_add_block_no . ', ' : '') . (!empty($row->busn_office_main_add_lot_no) ? $row->busn_office_main_add_lot_no . ', ' : '') . (!empty($row->busn_office_main_add_street_name) ? $row->busn_office_main_add_street_name . ', ' : '') . (!empty($row->busn_office_main_add_subdivision) ? $row->busn_office_main_add_subdivision . '' : '');
			$complete_address=(!empty($row->busn_office_main_add_block_no) ? $row->busn_office_main_add_block_no . ', ' : '') . (!empty($row->busn_office_main_add_lot_no) ? $row->busn_office_main_add_lot_no . ', ' : '') . (!empty($row->busn_office_main_add_street_name) ? $row->busn_office_main_add_street_name . ', ' : '') . (!empty($row->busn_office_main_add_subdivision) ? $row->busn_office_main_add_subdivision . '' : '');
                $complete_address2=$this->_commonmodel->getbussinesAddressBarangay($row->busn_office_main_barangay_id);
                $totalAddress=($complete_address) ? $complete_address. ', '.$complete_address2 : $complete_address2;
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['busn_name']=$row->busn_name;
            $arr[$i]['complete_address']=wordwrap($totalAddress, 40, "<br />\n")."<br>";
      		$arr[$i]['retirement_for'] =($row->retire_application_type =='1')?'Per Line of Business':'Entire Business';
				$lineofbdata=$this->_listsRetiredModel->getBusinessPSIC($row->busn_id); 
				$lineofbusiness = "";
				foreach ($lineofbdata as $key => $value) { $srno = $key +1;
				  $lineofbusiness .=$srno.'.'.wordwrap($value->subclass_description, 40, "<br />\n")."<br>";
				}
			$arr[$i]['business_line']  =$lineofbusiness;
			$arr[$i]['bin'] = $row->busns_id_no;
			$arr[$i]['retire_reason_remarks']=$row->retire_reason_remarks;
			$arr[$i]['retire_date_start']=$row->retire_date_closed;
			$arr[$i]['established_date']=$row->retire_date_start;
			$arr[$i]['retire_date_closed']=$row->retire_date_closed;
			$arr[$i]['owner_name']=$row->full_name;
			$arr[$i]['p_mobile_no']=$row->p_mobile_no;
			$arr[$i]['retire_application_type']='Walk In';
			
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

    public function exportlistsRetired(Request $request){
        $data =$this->_listsRetiredModel->getListexport($request);
		
        $headers = array(
          'Content-Type' => 'text/csv'
        );
        
        if (!File::exists(public_path()."/files")) {
            File::makeDirectory(public_path() . "/files");
        }
        
        $filename =  public_path("files/listsRetired.csv");
        $handle = fopen($filename, 'w');
        
        fputcsv($handle, [ 
            'No.',
			'Bussiness Name',
			'Bussiness Address',
			'Retirement For? (Entire Business/Per line of Business',
			'Business Line',
			'BIN',
			'Reason for Retirement',
			'Retirement Date',
			'Date Established',
			'Date Closed',
			'Name of Owner',
			'Contact No.of Owner',
			'Application Method',
           ]);
           $i=1;
           foreach($data['data'] as $row){
			// $complete_address=(!empty($row->busn_office_main_building_no) ? $row->busn_office_main_building_no . ', ' : '') . (!empty($row->busn_office_main_building_name) ? $row->busn_office_main_building_name . ', ' : '') . (!empty($row->busn_office_main_add_block_no) ? $row->busn_office_main_add_block_no . ', ' : '') . (!empty($row->busn_office_main_add_lot_no) ? $row->busn_office_main_add_lot_no . ', ' : '') . (!empty($row->busn_office_main_add_street_name) ? $row->busn_office_main_add_street_name . ', ' : '') . (!empty($row->busn_office_main_add_subdivision) ? $row->busn_office_main_add_subdivision . '' : '');
			$complete_address=(!empty($row->busn_office_main_add_block_no) ? $row->busn_office_main_add_block_no . ', ' : '') . (!empty($row->busn_office_main_add_lot_no) ? $row->busn_office_main_add_lot_no . ', ' : '') . (!empty($row->busn_office_main_add_street_name) ? $row->busn_office_main_add_street_name . ', ' : '') . (!empty($row->busn_office_main_add_subdivision) ? $row->busn_office_main_add_subdivision . '' : '');
                $complete_address2=$this->_commonmodel->getbussinesAddressBarangay($row->busn_office_main_barangay_id);
                $totalAddress=($complete_address) ? $complete_address. ', '.$complete_address2 : $complete_address2;
				$lineofbusiness = "";
				$lineofbdata=$this->_listsRetiredModel->getBusinessPSIC($row->busn_id); 
				foreach ($lineofbdata as $key => $value) {
				  $srno = $key +1;
				  $lineofbusiness .=$srno.'.'.$value->subclass_description;
				}
				$retire_application_type=($row->retire_application_type =='1')?'Per Line of Business':'Entire Business';
				$name_owner =$row->rpo_first_name." ".$row->rpo_middle_name." ".$row->rpo_custom_last_name;
				$p_mobile_no=$row->p_mobile_no;
				
				   fputcsv($handle, [ 
					$i,
					$row->busn_name,
					$totalAddress,
					$retire_application_type,
					$lineofbusiness,
					$row->busns_id_no,
					$row->retire_reason_remarks,
					$row->retire_date_closed,
					$row->retire_date_start,
					$row->retire_date_closed,
					$name_owner,
					$p_mobile_no,
					'Walk In'
				   ]);
				   
			$i++;
           }
          fclose($handle);
          return Response::download($filename, "listsRetired.csv", $headers);
      }
    
    
}
