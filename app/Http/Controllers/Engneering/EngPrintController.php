<?php

namespace App\Http\Controllers\Engneering;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Engneering\EngJobRequest;
use App\Models\CommonModelmaster;
use Illuminate\Http\Request;
use App\Interfaces\EngineeringInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PDF;
use File;

class EngPrintController extends Controller
{   
    private EngineeringInterface $engineeringRepository;
    private $carbon;
    private $slugs;

    public function __construct(EngineeringInterface $engineeringRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->engineeringRepository = $engineeringRepository;
        $this->_engjobrequest = New EngJobRequest();
        $this->_commonmodel = new CommonModelmaster(); 
        $this->carbon = $carbon;
        $this->slugs = 'engjobrequest';
    }
    
    public function print(Request $request, $params)
    {
        switch ($this->_engjobrequest->find($params)->es_id) {
            case 1:
                return $this->engineeringRepository->buildingPrint($params);
            case 3:
                return $this->engineeringRepository->sanitaryPrint($params);
            case 4:
                return $this->engineeringRepository->fencePrint($params);
            case 8:
                return $this->engineeringRepository->signPrint($params);
            case 10:
                return $this->engineeringRepository->mechanicalPrint($params);
            default:
                return $this->_engjobrequest->find($params)->es_id;
        }

    }


    public function print_sanitary(Request $request, $params)
    {
        $appdata = $this->_engjobrequest->getEditDetailsbldAppforprint($params);
        
        PDF::SetTitle('Sanitary Permit/Plumbing Permit');    
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(TRUE, 0);

        PDF::AddPage('P', 'cm', array(8.5, 13), true, 'UTF-8', false);
        $default_font_size = PDF::SetFont('helvetica','',8);

        $default_font_size;
        PDF::Cell(195.85,3,'Republic of the Philippines','',0,'C');
        PDF::ln();

        PDF::SetFont('helvetica','B',10);
        PDF::Cell(0,3,'DEPARTMENT OF PUBLIC WORKS & HIGHWAYS',0 ,0,'C');
        PDF::ln();

        $default_font_size;
        PDF::Cell(195.85,3,'OFFICE OF THE LOCAL BUILDING OFFICIAL','',0,'C');
        PDF::ln();
        PDF::ln();

        $default_font_size;
        PDF::Cell(135,3,'Application No.',0,0,'L');
        PDF::Cell(55,3,'Permit No.',0,0,'L');
        PDF::ln();
        PDF::Cell(5,3,'1',1,0,'C');
        PDF::Cell(5,3,'2',1,0,'C');
        PDF::Cell(5,3,'3',1,0,'C');
        PDF::Cell(5,3,'4',1,0,'C');
        PDF::Cell(5,3,'5',1,0,'C');
        PDF::Cell(5,3,'6',1,0,'C');
        PDF::Cell(5,3,'7',1,0,'C');
        PDF::Cell(5,3,'8',1,0,'C');
        PDF::Cell(5,3,'9',1,0,'C');
        PDF::Cell(5,3,'10',1,0,'C');
        PDF::Cell(5,3,'11',1,0,'C');
        PDF::Cell(75,3,'',0,0,'L');
        PDF::Cell(5,3,'1',1,0,'C');
        PDF::Cell(5,3,'2',1,0,'C');
        PDF::Cell(5,3,'3',1,0,'C');
        PDF::Cell(5,3,'4',1,0,'C');
        PDF::Cell(5,3,'5',1,0,'C');
        PDF::Cell(5,3,'6',1,0,'C');
        PDF::Cell(5,3,'7',1,0,'C');
        PDF::Cell(5,3,'8',1,0,'C');
        PDF::Cell(5,3,'9',1,0,'C');
        PDF::Cell(5,3,'10',1,0,'C');
        PDF::Cell(5,3,'11',1,0,'C');
        PDF::Cell(5,3,'12',1,0,'C');

        PDF::ln();
        PDF::ln();
        PDF::ln();
        PDF::SetFont('helvetica','B',11);
        PDF::Cell(195.85,3,'SANITARY/PLUMBING PERMIT',0,0,'C');
        PDF::ln();PDF::ln();
        
        PDF::Cell(70,3,'Date of Application','T',0,'C');
        PDF::Cell(50,3,'',0 ,0,'C');
        PDF::Cell(70,3,'Date of Issued','T',0,'C');

        PDF::ln();
        PDF::ln();

        PDF::SetFont('helvetica','B',10);
        PDF::Cell(190,3,'BOX 1 (TO BE ACCOMPLISHED BY SANITARY ENGINEER/MASTER PLUMBER, IN PRINT)','',0,'l');
        PDF::ln();

        PDF::SetFont('helvetica','',8);
        PDF::MultiCell(145, 3,'NAME OF OWNER/APPLICANT:', 'LT','L', 0, 0, '', '', true);
        PDF::MultiCell(45, 3,'', 'LTR','L', 0, 0, '', '', true);
        PDF::ln();
        
        $default_font_size;
        PDF::MultiCell(57.5, 3,'LAST NAME,', 'L','L', 0, 0, '', '', true);
        PDF::MultiCell(57.5, 3,'FIRST NAME,', '','L', 0, 0, '', '', true);
        PDF::MultiCell(30, 3,'MIDDLE NAME', '','L', 0, 0, '', '', true);
        PDF::MultiCell(45, 3,'TAX ACCOUNT NO.', 'LR','C', 0, 0, '', '', true);
        PDF::ln();

        PDF::Cell(57.5,3,'Hontucan','LB',0,'L');
        PDF::Cell(57.5,3,'Kenneth Rey','B',0,'L');
        PDF::Cell(30,3,'B','B',0,'L');
        PDF::Cell(45,3,'1234567890','LR',0,'C');
        PDF::ln();

        PDF::SetFont('helvetica','',8);
        PDF::Cell(145,3, 'ADDRESS', 'LR',0,'L');
        PDF::Cell(45, 3, '', 'TR',0,'L');
        PDF::ln();
        
        $default_font_size;
        PDF::Cell(20,3,'NO','L',0,'C');
        PDF::Cell(42,3,'STREET',0,0,'C');
        PDF::Cell(43,3,'BARANGAY',0,0,'C');
        PDF::Cell(40,3,'CITY/MUNICIPALITY',0,0,'C');
        PDF::Cell(45,3,'TELEPHONE NO.','LR',0,'C');
        PDF::ln();

        PDF::Cell(20,3,'787-C','L',0,'C');
        PDF::Cell(42,3,'Limasawa','',0,'C');
        PDF::Cell(43,3,'Pitogo','',0,'C');
        PDF::Cell(40,3,'Makati','',0,'C');
        PDF::Cell(45,3,'09123456789','LRB',0,'C');
        PDF::ln();

        PDF::Cell(40,3,'Location of Installation:', 'LT',0,'L');
        PDF::Cell(150,3,'No., Street, Barangay, Municipality', 'TR',0,'C');
        PDF::ln();

        PDF::Cell(40,3,' ', 'LB',0,'L');
        PDF::Cell(150,3,'787-C Limasawa St. Pitogo, Makati City', 'BR',0,'C');
        PDF::ln();

        PDF::Cell(55,3,'SCOPE OF WORK:', 'L',0,'L');
        PDF::Cell(21,3,'Addition of', 0,0,'L');
        PDF::Cell(45,4,'', 'B',0,'L');
        PDF::Cell(20,3,'', 0,0,'L');
        PDF::Cell(49,3,'OTHERS (SPECIFY)', 'R',0,'L');
        PDF::ln();

        PDF::Cell(15,3,'', 'L',0,'L');
        PDF::Cell(40,3,'NEW INSTALLATION', 0,0,'L');
        PDF::Cell(21,3,'Repair of', 0,0,'L');
        PDF::Cell(45,4,'', 'B',0,'L');
        PDF::Cell(9,3,'', 0,0,'L');
        PDF::Cell(26.5,3,'', 'B',0,'L');
        PDF::Cell(5,3,'of', 0,0,'L');
        PDF::Cell(26.5,3,'', 'B',0,'L');
        PDF::Cell(2,3,'', 'R',0,'L');
        PDF::ln();

        PDF::Cell(55,3,' ', 'L',0,'L');
        PDF::Cell(21,3,'Removal of', 0,0,'L');
        PDF::Cell(45,3,'', 'B',0,'L');
        PDF::Cell(9,3,'', 0,0,'L');

        PDF::Cell(26.5,3,'', 'B',0,'L');
        PDF::Cell(5,5,'of', 0,0,'L');
        PDF::Cell(26.5,3,'', 'B',0,'L');
        PDF::Cell(2,3,'', 'R',0,'L');
        PDF::ln();
        
        PDF::Cell(0,.2,'', 'LR',0,'L');
        PDF::ln();

        PDF::Cell(190,3,'USE OR TYPE OF OCCUPANCY:', 'LTR',0,'L');
        PDF::ln();

        PDF::Cell(10,3,'', 'L',0,'L');
        PDF::Cell(5,3,'[  ]', 0,0,'L');
        PDF::Cell(26,3,'RESIDENTIAL', 0,0,'L');
        PDF::Cell(59,3,'', '',0,'L');
        PDF::Cell(10,3,'', 0,0,'L');
        PDF::Cell(5,3,'[  ]', 0,0,'L');
        PDF::Cell(29,3,'AGRICULTURAL', 0,0,'L');
        PDF::Cell(44,3,'', '',0,'L');
        PDF::Cell(2,3,'', 'R',0,'L');
        PDF::ln();

        PDF::Cell(10,3,'', 'L',0,'L');
        PDF::Cell(5,3,'[  ]', 0,0,'L');
        PDF::Cell(26,3,'COMMERCIAL', 0,0,'L');
        PDF::Cell(59,3,'', '',0,'L');
        PDF::Cell(10,3,'', 0,0,'L');
        PDF::Cell(5,3,'[  ]', 0,0,'L');
        PDF::Cell(50,3,'PARKS, PLAZAS, MONUMENTS', 0,0,'L');
        PDF::Cell(23,3,'', '',0,'L');
        PDF::Cell(2,3,'', 'R',0,'L');
        PDF::ln();

        PDF::Cell(10,3,'', 'L',0,'L');
        PDF::Cell(5,3,'[  ]', 0,0,'L');
        PDF::Cell(26,3,'INDUSTRIAL', 0,0,'L');
        PDF::Cell(59,3,'', '',0,'L');
        PDF::Cell(10,3,'', 0,0,'L');
        PDF::Cell(5,3,'[  ]', 0,0,'L');
        PDF::Cell(50,3,'RECREATIONAL', 0,0,'L');
        PDF::Cell(23,3,'', '',0,'L');
        PDF::Cell(2,3,'', 'R',0,'L');
        PDF::ln();

        PDF::Cell(10,3,'', 'L',0,'L');
        PDF::Cell(5,3,'[  ]', 0,0,'L');
        PDF::Cell(26,3,'INSTITUTIONAL', 0,0,'L');
        PDF::Cell(59,3,'', '',0,'L');
        PDF::Cell(10,3,'', 0,0,'L');
        PDF::Cell(5,3,'[  ]', 0,0,'L');
        PDF::Cell(50,3,'OTHERS (SPECIFY)', 0,0,'L');
        PDF::Cell(23,3,'', '',0,'L');
        PDF::Cell(2,3,'', 'R',0,'L');
        PDF::ln();

        // fixtures
        PDF::Cell(190,3,'FIXTURES TO BE INSTALLED', 'LTR',0,'L');
        PDF::ln();

        PDF::Cell(10,3,'QTY', 'L',0,'L');
        PDF::Cell(20,3,'NEW', 0,0,'C');
        PDF::Cell(20,3,'EXISTING', 0,0,'C');

        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(40,3,'KINDS OF FIXTURES', 0,0,'L');
        
        PDF::Cell(5,3,' ', 0,0,'L');

        PDF::Cell(10,3,'QTY', 0,0,'L');
        PDF::Cell(20,3,'NEW', 0,0,'C');
        PDF::Cell(20,3,'EXISTING', 0,0,'C');

        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(35,3,'KINDS OF FIXTURES', 'R',0,'L');
        PDF::ln();

        PDF::Cell(10,3,' ', 'L',0,'L');
        PDF::Cell(20,3,'FIXTURES', 0,0,'C');
        PDF::Cell(20,3,'FIXTURES', 0,0,'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(40,3,'', 0,0,'L');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(10,3,' ', 0,0,'L');
        PDF::Cell(20,3,'FIXTURES', 0,0,'C');
        PDF::Cell(20,3,'FIXTURES', 0,0,'C');   
        PDF::Cell(40,3,' ', 'R',0,'C');  

        // PDF::ln();
        PDF::Cell(195.85,3,' ', 'LR',0,'L');
        PDF::ln();

        PDF::Cell(2, 3,'', 'L',0,'L');
        PDF::Cell(6, 3,'', 'B',0,'L');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(40,3,'Water Closet', 0,0,'L');
        PDF::Cell(5,3,' ', 0,0,'L');

        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(6, 3,'', 'B',0,'L');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(35,3,'Bidette', 'R',0,'L');
        PDF::ln();

        PDF::Cell(2, 3,'', 'L',0,'L');
        PDF::Cell(6, 3,'', 'B',0,'L');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(40,3,'Floor Drain', 0,0,'L');
        PDF::Cell(5,3,' ', 0,0,'L');
        
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(6, 3,'', 'B',0,'L');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(35,3,'Laundry Trays', 'R',0,'L');
        PDF::ln();

        PDF::Cell(2, 3,'', 'L',0,'L');
        PDF::Cell(6, 3,'', 'B',0,'L');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(40,3,'Lavatories', 0,0,'L');
        PDF::Cell(5,3,' ', 0,0,'L');
        
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(6, 3,'', 'B',0,'L');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(35,3,'Dental Cuspidor', 'R',0,'L');
        PDF::ln();
        
        PDF::Cell(2, 3,'', 'L',0,'L');
        PDF::Cell(6, 3,'', 'B',0,'L');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(40,3,'Kitchen Sink', 0,0,'L');
        PDF::Cell(5,3,' ', 0,0,'L');
        
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(6, 3,'', 'B',0,'L');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(35,3,'Gas Heater', 'R',0,'L');
        PDF::ln();

        PDF::Cell(2, 3,'', 'L',0,'L');
        PDF::Cell(6, 3,'', 'B',0,'L');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(40,3,'Faucet', 0,0,'L');
        PDF::Cell(5,3,' ', 0,0,'L');
        
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(6, 3,'', 'B',0,'L');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(35,3,'Electric Heater', 'R',0,'L');
        PDF::ln();
        
        PDF::Cell(2, 3,'', 'L',0,'L');
        PDF::Cell(6, 3,'', 'B',0,'L');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(40,3,'Shower Head', 0,0,'L');
        PDF::Cell(5,3,' ', 0,0,'L');
        
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(6, 3,'', 'B',0,'L');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(35,3,'Water Boiler', 'R',0,'L');
        PDF::ln();
        
        PDF::Cell(2, 3,'', 'L',0,'L');
        PDF::Cell(6, 3,'', 'B',0,'L');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(40,3,'Water Meter', 0,0,'L');
        PDF::Cell(5,3,' ', 0,0,'L');
        
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(6, 3,'', 'B',0,'L');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(5,5,' ', 0,0,'L');
        PDF::Cell(35,3,'Drinking Fountain', 'R',0,'L');
        PDF::ln();

        PDF::Cell(2, 3,'', 'L',0,'L');
        PDF::Cell(6, 3,'', 'B',0,'L');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(40,3,'Grease Trap', 0,0,'L');
        PDF::Cell(5,3,' ', 0,0,'L');
        
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(6, 3,'', 'B',0,'L');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(35,3,'Bar Sink', 'R',0,'L');
        PDF::ln();

        PDF::Cell(2, 3,'', 'L',0,'L');
        PDF::Cell(6, 3,'', 'B',0,'L');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(40,3,'Bath Tubs', 0,0,'L');
        PDF::Cell(5,3,' ', 0,0,'L');
        
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(6, 3,'', 'B',0,'L');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(35,3,'Soda Fountain Sink', 'R',0,'L');
        PDF::ln();

        PDF::Cell(2, 3,'', 'L',0,'L');
        PDF::Cell(6, 3,'', 'B',0,'L');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(40,3,'Slop Sink', 0,0,'L');
        PDF::Cell(5,3,' ', 0,0,'L');
        
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(6, 3,'', 'B',0,'L');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(35,3,'Laboratory Sink', 'R',0,'L');
        PDF::ln();

        PDF::Cell(2, 3,'', 'L',0,'L');
        PDF::Cell(6, 3,'', 'B',0,'L');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(40,3,'Urinal', 0,0,'L');
        PDF::Cell(5,3,' ', 0,0,'L');
        
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(6, 3,'', 'B',0,'L');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(35,3,'Sterilizer', 'R',0,'L');
        PDF::ln();

        PDF::Cell(2, 3,'', 'L',0,'L');
        PDF::Cell(6, 3,'', 'B',0,'L');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(40,3,'Air Conditioning Unit', 0,0,'L');
        PDF::Cell(5,3,'', 0,0,'L');
        
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(6, 3,'', 'B',0,'L');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(35,3,'Swimming Pool', 'R',0,'L');
        PDF::ln();

        PDF::Cell(2, 3,'', 'L',0,'L');
        PDF::Cell(6, 3,'', 'B',0,'L');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(40,3,'Water Tank/Reservoir', 0,0,'L');
        PDF::Cell(5,3,' ', 0,0,'L');
        
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(6, 3,'', 'B',0,'L');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(20,3,'[  ]', 0,0,'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(35,3,'Others (specify)______', 'R',0,'L');
        PDF::ln();

        PDF::Cell(2, 3,'', 'L',0,'L');
        PDF::Cell(35, 3,'', 'B',0,'C');
        PDF::Cell(30, 3,'TOTAL', 0,0,'L');
        PDF::Cell(35, 3,'', 0,0,'L');
        PDF::Cell(35, 3,'', 'B',0,'C');
        PDF::Cell(30, 3,'TOTAL', 0,0,'L');
        PDF::Cell(23, 3,' ', 'R',0,'L');
        PDF::ln();

        PDF::Cell(70, 3,'WATER SUPPLY:', 'LT',0,'L');
        PDF::Cell(70, 3,'SYSTEM OF DISPOSAL:', 'T',0,'L');
        PDF::Cell(50, 3,'', 'TR',0,'L');
        PDF::ln();

        PDF::Cell(6, 3,'', 'L',0,'L');
        PDF::Cell(10,3,'[  ]', 0,0,'C');
        PDF::Cell(55, 3,'Water Distribution System', 0,0,'L');
        PDF::Cell(6, 3,'', 0,0,'L');
        PDF::Cell(5,3,'[  ]', 0,0,'C');
        PDF::Cell(54, 3,'Sanitary Sewer System', 0,0,'L');
        PDF::Cell(5,3,'[  ]', 0,0,'C');
        PDF::Cell(49, 3,'Storm Drainage System', 'R',0,'L');
        PDF::ln();

        PDF::Cell(6, 3,'', 'L',0,'L');
        PDF::Cell(10,3,'[  ]', 0,0,'C');
        PDF::Cell(55, 3,'Shallow Well', 0,0,'L');
        PDF::Cell(6, 3,'', 0,0,'L');
        PDF::Cell(5,3,'[  ]', 0,0,'C');
        PDF::Cell(54, 3,'Waste Water Treatment Plant', 0,0,'L');
        PDF::Cell(5,3,'[  ]', 0,0,'C');
        PDF::Cell(49, 3,'Surface Drainage', 'R',0,'L');
        PDF::ln();
        
        PDF::Cell(6, 3,'', 'L',0,'L');
        PDF::Cell(10,3,'[  ]', 0,0,'C');
        PDF::Cell(55, 3,'Deep Well & Pump Set', 0,0,'L');
        PDF::Cell(6, 3,'', 0,0,'L');
        PDF::Cell(5,3,'[  ]', 0,0,'C');
        PDF::Cell(54, 3,'Septic Vault/Imhoff Tank', 0,0,'L');
        PDF::Cell(5,3,'[  ]', 0,0,'C');
        PDF::Cell(49, 3,'Street Canal', 'R',0,'L');
        PDF::ln();

        PDF::Cell(6, 3,'', 'L',0,'L');
        PDF::Cell(10,3,'[  ]', 0,0,'C');
        PDF::Cell(55, 3,'City/Municipal Water System', 0,0,'L');
        PDF::Cell(6, 3,'', 0,0,'L');
        PDF::Cell(5,3,'[  ]', 0,0,'C');
        PDF::Cell(54, 3,'Sanitary Sewer Connection', 0,0,'L');
        PDF::Cell(5,3,'[  ]', 0,0,'C');
        PDF::Cell(49, 3,'Water Course', 'R',0,'L');
        PDF::ln();

        PDF::Cell(6, 3,'', 'L',0,'L');
        PDF::Cell(10,3,'[  ]', 0,0,'C');
        PDF::Cell(55, 3,'Others___________', 0,0,'L');
        PDF::Cell(6, 3,'', 0,0,'L');
        PDF::Cell(5,3,'[  ]', 0,0,'C');
        PDF::Cell(108, 3,'Sub-Surface Sand Filter', 'R',0,'L');
        PDF::ln();

        PDF::Cell(100, 3,'NUMBER OF STOREYS OF BUILDING', 'LT',0,'L');
        PDF::Cell(90, 3,'TOTAL AREA OF BUILDING/SUBDIVISION', 'TR',0,'L');
        PDF::ln();

        PDF::Cell(30, 3,'Proposed Date', 'L',0,'L');
        PDF::Cell(35, 3,' ', 'B',0,'L');
        PDF::Cell(35, 3,' ', 0,0,'L');
        PDF::Cell(12, 3,'SQM.', 0,0,'L');
        PDF::Cell(40, 3,' ', 'B',0,'L');
        PDF::Cell(38, 3,' ', 'R',0,'L');
        PDF::ln();

        PDF::Cell(32, 3,'Start of Installation', 'L',0,'L');
        PDF::Cell(35, 3,' ', 'B',0,'L');
        PDF::Cell(33, 3,' ', 0,0,'L');
        PDF::Cell(40, 3,'Total Cost of Installation', 0,0,'L');
        PDF::Cell(40, 3,' ', 'B',0,'L');
        PDF::Cell(10, 3,' ', 'R',0,'L');
        PDF::ln();


        PDF::Cell(40, 3,'Expected Date of Completion', 'L',0,'L');
        PDF::Cell(35, 3,' ', 'B',0,'L');
        PDF::Cell(25, 3,' ', 0,0,'L');
        PDF::Cell(20, 3,'Prepared By', 0,0,'L');
        PDF::Cell(40, 3,' ', 'B',0,'L');
        PDF::Cell(30, 3,' ', 'R',0,'L');
        PDF::ln();

        PDF::SetFont('helvetica','B',10);
        PDF::Cell(190,3,'BOX 2 (TO BE ACCOMPLISHED BY THE BUILDING OFFICIAL)','TB',0,'l');
        PDF::ln();

        $default_font_size;
        PDF::Cell(190,3,'','LR',0,'l');
        PDF::ln();

        PDF::SetFont('helvetica','B',8);
        PDF::Cell(190, 3,'ACTION TAKEN:', 'LR',0,'L');
        PDF::ln();

        PDF::SetFont('helvetica','',8);
        PDF::Cell(190, 3,'Permit is hereby granted to install the sanitary/plumbing fixture enumerated herein subject', 'LR',0,'L');
        PDF::ln();

        PDF::Cell(190, 3,'to the following conditions:', 'LR',0,'L');
        PDF::ln();
        
        PDF::Cell(10, 3,'1.', 'L',0,'R');
        PDF::Cell(5, 3,' ', 0,0,'R');
        PDF::Cell(100, 3,'That the proposed installation shall be in accordance with approval plans filed', 0,0,'J');
        PDF::Cell(75, 3,' ', 'R',0,'R');
        PDF::ln();

        PDF::Cell(15, 3,' ', 'L',0,'R');
        PDF::Cell(100, 3,'with this office & in conformity with the national building code.', 0,0,'L');
        PDF::Cell(75, 3,' ', 'R',0,'R');
        PDF::ln();

        PDF::Cell(10, 3,'2.', 'L',0,'R');
        PDF::Cell(5, 3,' ', 0,0,'R');
        PDF::Cell(100, 3,'That a duly licensed sanitary engineer/master plumber in-charge of', 0,0,'J');
        PDF::Cell(75, 3,' ', 'R',0,'R');
        PDF::ln();

        PDF::Cell(15, 3,' ', 'L',0,'R');
        PDF::Cell(100, 3,'installation/construction.', 0,0,'L');
        PDF::Cell(20, 3,'', 0,0,'R');
        PDF::Cell(40, 3,'', 'B',0,'R');
        PDF::Cell(15, 3,'', 'R',0,'R');
        PDF::ln();

        PDF::Cell(10, 3,'3.', 'L',0,'R');
        PDF::Cell(5, 3,' ', 0,0,'R');
        PDF::Cell(100, 3,'That a certificate of completion duly signed by a sanitary engineer/master', 0,0,'J');
        PDF::Cell(30, 3,'', 0,0,'R');
        PDF::Cell(20, 3,'Building Official', 0,0,'R');
        PDF::Cell(25, 3,'', 'R',0,'R');
        PDF::ln();

        PDF::Cell(15, 3,' ', 'L',0,'R');
        PDF::Cell(100, 3,'plumber in-charge of installation shall be submitted not later than seven (7) days', 0,0,'J');
        PDF::Cell(75, 3,' ', 'R',0,'R');
        PDF::ln();

        PDF::Cell(15, 3,' ', 'L',0,'R');
        PDF::Cell(100, 3,'after completion of the installation.', 0,0,'L');
        PDF::Cell(75, 3,' ', 'R',0,'R');
        PDF::ln();

        PDF::Cell(10, 3,'4.', 'L',0,'R');
        PDF::Cell(5, 3,' ', 0,0,'R');
        PDF::Cell(100, 3,'That a certificate of final inspection and a certificate of occupancy be secured', 0,0,'J');
        PDF::Cell(20, 3,'', 0,0,'R');
        PDF::Cell(40, 3,'', 'B',0,'R');
        PDF::Cell(15, 3,'', 'R',0,'R');
        PDF::ln();

        PDF::Cell(15, 3,' ', 'L',0,'R');
        PDF::Cell(100, 3,'prior to the actual occupancy of the building.', 0,0,'L');
        PDF::Cell(30, 3,'', 0,0,'R');
        PDF::Cell(20, 3,'DATE', 0,0,'C');
        PDF::Cell(25, 3,'', 'R',0,'R');
        PDF::ln();

        PDF::SetFont('helvetica','B',8);
        PDF::Cell(190, 3,'NOTE:', 'LR',0,'L');
        PDF::ln();

        PDF::SetFont('helvetica','',8);

        PDF::Cell(15, 3,' ', 'L',0,'R');
        PDF::Cell(100, 3,'THIS PERMIT MAY BE CANCELLED OR REVOKED PURSUANT TO THE', 0,0,'J');
        PDF::Cell(75, 3,' ', 'R',0,'R');
        PDF::ln();

        PDF::Cell(15, 3,' ', 'L',0,'R');
        PDF::Cell(175, 3,'SECTION 305 & 306 OF THE "NATIONAL BUILDING CODE"', 'R',0,'L');
        PDF::ln();

        PDF::Cell(190, 3,' ', 'LBR',0,'R');
        
        // 2nd page
        // PDF::AddPage('P', 'LEGAL');
        PDF::AddPage('P', 'cm', array(8.5, 13), true, 'UTF-8', false);

        PDF::SetFont('helvetica','B',10);
        PDF::Cell(190,3,'BOX 3 (TO BE ACCOMPLISHED BY THE RECEIVING & RECORDING SECTION)', 0,0,'l');
        PDF::ln();

        PDF::Cell(190,3,'BUILDING DOCUEMTS', 'LTR',0,'C');
        PDF::ln();

        PDF::SetFont('helvetica',' ',10);
        PDF::Cell(15,3,' ', 'L',0,'C');
        PDF::Cell(60,3,'Sanitary Plumbing Plans & Specifications', 0,0,'L');
        PDF::Cell(50,3,' ', 0,0,'C');
        PDF::Cell(60,3,'Cost Estimates', 0,0,'L');
        PDF::Cell(5,3,' ', 'R',0,'L');
        PDF::ln();

        PDF::SetFont('helvetica',' ',10);
        PDF::Cell(15,3,' ', 'L',0,'C');
        PDF::Cell(60,3,'Bill of Materials', 0,0,'L');
        PDF::Cell(50,3,' ', 0,0,'C');
        PDF::Cell(60,3,'Others (Specify) ', 0,0,'L');
        PDF::Cell(5,3,' ', 'R',0,'L');
        PDF::ln();

        PDF::SetFont('helvetica','B',10);
        PDF::Cell(190,3,'BOX 4 (TO BE ACCOMPLISHED BY THE DIVISION/SECTION CONCERNED)', 'TB',0,'l');
        PDF::ln();

        PDF::SetFont('helvetica','B',10);
        PDF::Cell(190,3,'ASSESSED FEES', 'LRB',0,'C');
        PDF::ln();

        $default_font_size;
        PDF::Cell(38,3,'', 'LR',0,'C');
        PDF::Cell(38,3,'AMOUNT DUE', 'LR',0,'C');
        PDF::Cell(38,3,'ASSESSED BY', 'LR',0,'C');
        PDF::Cell(38,3,'O.R. NUMBER', 'LR',0,'C');
        PDF::Cell(38,3,'DATE PAID', 'LR',0,'C');
        PDF::ln();

        PDF::Cell(38,3,'', 1,0,'C');
        PDF::Cell(38,3,'', 1,0,'C');
        PDF::Cell(38,3,'', 1,0,'C');
        PDF::Cell(38,3,'', 1,0,'C');
        PDF::Cell(38,3,'', 1,0,'C');
        PDF::ln();

        PDF::Cell(38,3,'', 1,0,'C');
        PDF::Cell(38,3,'', 1,0,'C');
        PDF::Cell(38,3,'', 1,0,'C');
        PDF::Cell(38,3,'', 1,0,'C');
        PDF::Cell(38,3,'', 1,0,'C');
        PDF::ln();

        PDF::SetFont('helvetica','B',10);
        PDF::Cell(190,3,'BOX 5 (TO BE ACCOMPLISHED BY THE DIVISION/SECTION CONCERNED)', 'TB',0,'l');
        PDF::ln();

        PDF::Cell(190,3,'PROGRESS FLOW', 'LRB',0,'C');
        PDF::ln();

        $tbl = '<table id="purhcase-order-print-1-table" width="100%"   cellspacing="0" cellpadding="1" border="0.5">
                        <tr>
                            <td colspan="1" rowspan="2" align="left" width="30%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                                NOTED:<br>   Chief, Processing Division/Section
                            </td>
                            <td colspan="2" rowspan="1" align="center" width="15%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                                IN
                            </td>
                            <td colspan="2" rowspan="1" align="center" width="15%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                                OUT
                            </td>
                            <td colspan="1" rowspan="2" align="center" width="20%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                                ACTIONS/REMARKS
                            </td>
                            <td colspan="1" rowspan="2" align="center" width="20%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                                PROCESSED BY
                            </td>
                        </tr>
                    <tr>
                        <td colspan="1" rowspan="1" align="center" width="7.5%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            TIME
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="7.5%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            DATE
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="7.5%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            TIME
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="7.5%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            DATE
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1" rowspan="1" align="left" width="30%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                        Receiving and Recording
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="7.5%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="7.5%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="7.5%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="7.5%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="20%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="20%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1" rowspan="1" align="left" width="30%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                        Geodetic (Line & Grade)
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="7.5%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="7.5%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="7.5%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="7.5%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="20%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="20%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1" rowspan="1" align="left" width="30%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                        Sanitary
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="7.5%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="7.5%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="7.5%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="7.5%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="20%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="20%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                    </tr>
                </table>';
        PDF::writeHTML($tbl, false, false, false, false, '');
    
        PDF::ln();

        PDF::SetFont('helvetica',' ',10);
        PDF::Cell(190,3,'WE HEREBY AFFIX OUR HANDS SIGNIFYING OUR CONFORMITY TO THE INFORMATION HEREIN ABOVE SETFORTH.', 0,0,'J');
        PDF::ln();PDF::ln();

        PDF::Cell(97.925,3,'BOX 6', 'B',0,'L');
        PDF::Cell(5,' ', '',0,'L');
        PDF::Cell(87.84,3,'BOX 8', 'B',0,'L');
        PDF::ln();
        
        PDF::Cell(65,3,'SANITARY ENGINEER/MASTER', 'LR',0,'L');
        PDF::Cell(32.925,3,'P.R.C. Reg. No.', 'LR',0,'L');
        PDF::Cell(5,' ', '',0,'L');
        PDF::Cell(87.84,3,'Signature', 'LR',0,'L');
        PDF::ln();

        PDF::Cell(65,3,'PLUMBER', 'LR',0,'L');
        PDF::Cell(32.925,3,' ', 'LR',0,'L');
        PDF::Cell(5,' ', '',0,'L');
        PDF::Cell(87.84,3,' ', 'LR',0,'L');
        PDF::ln();

        PDF::Cell(65,3,'Signed and Sealed Plans & Specifications', 'LRB',0,'J');
        PDF::Cell(32.925,3,' ', 'LRB',0,'L');
        PDF::Cell(5,' ', '',0,'L');
        
        PDF::Cell(7,3, '', 'L', 0,'L');
        PDF::Cell(72.84,3,' ', 'B',0,'L');
        PDF::Cell(8,3, '', 'R',0,'L');
        PDF::ln();

        PDF::Cell(97.925,3,'Print Name', 'LR',0,'L');
        PDF::Cell(5,' ', '',0,'L');
        PDF::Cell(87.84,3,'APPLICANT', 'LR',0,'C');
        PDF::ln();

        PDF::Cell(97.925,3,'', 'LR',0,'L');
        PDF::Cell(5,' ', '',0,'L');
        PDF::Cell(87.84,3,' ', 'LR',0,'C');
        PDF::ln();

        PDF::Cell(97.925,3,'Address', 1,0,'L');
        PDF::Cell(5,' ', '',0,'L');
        PDF::Cell(29.28,3,'Res. Cert. No.', 'LTR',0,'C');
        PDF::Cell(29.28,3,'Date Issued', 'LTR',0,'C');
        PDF::Cell(29.28,3,'Place Issued', 'LTR',0,'C');    
        PDF::ln();

        PDF::Cell(33.925,3,'P.T.R. No.', 'LR',0,'L');
        PDF::Cell(32,3,'Date Issued', 'LR',0,'L');
        PDF::Cell(32,3,'Place Issued', 'LR',0,'L');
        PDF::Cell(5,' ', '',0,'L');
        PDF::Cell(29.28,3,' ', 1,0,'C');
        PDF::Cell(29.28,3,' ', 1,0,'C');
        PDF::Cell(29.28,3,' ', 1,0,'C');
        PDF::ln();

        PDF::Cell(65.925,3,'Signature', 1,0,'L');
        PDF::Cell(32,3,'TAN', 1,0,'L');
        PDF::ln();PDF::ln();

        //start
        PDF::Cell(97.925,3,'BOX 7', 'B',0,'L');
        PDF::ln();
        
        PDF::Cell(65,3,'SANITARY ENGINEER/MASTER', 'LR',0,'L');
        PDF::Cell(32.925,3,'P.R.C. Reg. No.', 'LR',0,'L');
        PDF::ln();

        PDF::Cell(65,3,'PLUMBER', 'LR',0,'L');
        PDF::Cell(32.925,3,' ', 'LR',0,'L');
        PDF::ln();

        PDF::Cell(65,3,'In-charge of Installation', 'LRB',0,'C');
        PDF::Cell(32.925,3,' ', 'LRB',0,'L');
        PDF::ln();

        PDF::Cell(97.925,3,'Print Name', 1,0,'L');
        PDF::ln();

        PDF::Cell(97.925,3,'', 'LR',0,'L');
        PDF::ln();

        PDF::Cell(97.925,3,'Address', 1,0,'L');  
        PDF::ln();

        PDF::Cell(33.925,3,'P.T.R. No.', 'LR',0,'L');
        PDF::Cell(32,3,'Date Issued', 'LR',0,'L');
        PDF::Cell(32,3,'Place Issued', 'LR',0,'L');
        PDF::ln();

        PDF::Cell(65.925,3,'Signature', 1,0,'L');
        PDF::Cell(32,3,'TAN', 1,0,'L');
        //END

        PDF::Output('Sanitary Permit/Plumbing Permit'.'.pdf');
    }

    public function print_mechanical(Request $request, $parms)
    {
        PDF::SetTitle('Mechanical Permit');    
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(TRUE, 0);

        
        $border = 0;
        $font_size = 8;
        $cell_height = 4;

        PDF::AddPage('P', 'cm', array(8.5, 13), true, 'UTF-8', false);

        PDF::SetFont('helvetica','',$font_size);
        PDF::MultiCell(0, 0, 'Republic of the Philippines', 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'City/Municipality of Palayan', 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'Province of Nueva Echija', 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '<h3>OFFICE OF THE BUILDING OFFICIAL</h3>', 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(5);
        PDF::MultiCell(0, 0, '<h1>MECHANICAL PERMIT</h1>', 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(5);

        PDF::SetFont('Helvetica', '', 8);
        PDF::MultiCell(80, 0, 'APPLICATION NO.', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(70, 0, 'MP NO.', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'BUILDING PERMIT NO.', $border, 'L', 0, 1, '', '', true, 0, true);
        PDF::ln();

        PDF::Cell(5,$cell_height,'1',1,0,'C');
        PDF::Cell(5,$cell_height,'2',1,0,'C');
        PDF::Cell(5,$cell_height,'3',1,0,'C');
        PDF::Cell(5,$cell_height,'4',1,0,'C');
        PDF::Cell(5,$cell_height,'5',1,0,'C');
        PDF::Cell(5,$cell_height,'6',1,0,'C');
        PDF::Cell(5,$cell_height,'7',1,0,'C');
        PDF::Cell(5,$cell_height,'8',1,0,'C');
        PDF::Cell(5,$cell_height,'9',1,0,'C');
        PDF::Cell(5,$cell_height,'10',1,0,'C');

        PDF::Cell(30,$cell_height,'',0,0,'C');

        PDF::Cell(5,$cell_height,'1',1,0,'C');
        PDF::Cell(5,$cell_height,'2',1,0,'C');
        PDF::Cell(5,$cell_height,'3',1,0,'C');
        PDF::Cell(5,$cell_height,'4',1,0,'C');
        PDF::Cell(5,$cell_height,'5',1,0,'C');
        PDF::Cell(5,$cell_height,'6',1,0,'C');
        PDF::Cell(5,$cell_height,'7',1,0,'C');
        PDF::Cell(5,$cell_height,'8',1,0,'C');

        PDF::Cell(30,$cell_height,' ',0,0,'C');

        PDF::Cell(5,$cell_height,'1',1,0,'C');
        PDF::Cell(5,$cell_height,'2',1,0,'C');
        PDF::Cell(5,$cell_height,'3',1,0,'C');
        PDF::Cell(5,$cell_height,'4',1,0,'C');
        PDF::Cell(5,$cell_height,'5',1,0,'C');
        PDF::Cell(5,$cell_height,'6',1,0,'C');
        PDF::Cell(5,$cell_height,'7',1,0,'C');
        PDF::Cell(5,$cell_height,'8',1,0,'C');

        PDF::ln();PDF::ln();

        PDF::SetFont('helvetica','B',$font_size);
        PDF::Cell(0,$cell_height,'BOX 1 (TO BE ACCOMPLISHED IN PRINT BY THE OWNER/APPLICANT)', 'B' ,0,'l');
        PDF::ln();

        PDF::SetFont('helvetica','',$font_size);
        PDF::Cell(40,$cell_height,'OWNER/APPLICANT','L',0,'C');
        PDF::Cell(40,$cell_height,'LAST NAME',$border,0,'C');
        PDF::Cell(40,$cell_height,'FIRST NAME',$border,0,'C');
        PDF::Cell(40,$cell_height,'M.I.',$border,0,'C');
        PDF::Cell(30,$cell_height,'TIN','LR',0,'L');
        PDF::ln();

        PDF::Cell(40,$cell_height,'','L',0,'C');
        PDF::Cell(40,$cell_height,' ',$border,0,'C');
        PDF::Cell(40,$cell_height,' ',$border,0,'C');
        PDF::Cell(40,$cell_height,' ',$border,0,'C');
        PDF::Cell(30,$cell_height,' ','LR',0,'L');
        PDF::ln();

        PDF::Cell(70,5,'FOR CONSTRUCTION OWNED','LT',0,'L');
        PDF::Cell(60,5,'FORM OF OWNERSHIP','LT',0,'L');
        PDF::Cell(60,5,'USE OR CHARACTER OF OCCUPANCY','LTR',0,'L');
        PDF::ln();

        PDF::Cell(70,5,'BY AN ENTERPRISE','L',0,'L');
        PDF::Cell(60,5,' ','L',0,'L');
        PDF::Cell(60,5,' ','LR',0,'L');
        PDF::ln();

        PDF::Cell(20,5,'ADDRESS','LT',0,'L');
        PDF::Cell(10,5,'NO.','T',0,'L');
        PDF::Cell(30,5,'STREET,','T',0,'L');
        PDF::Cell(40,5,'BARANGAY,','T',0,'L');
        PDF::Cell(40,5,'CITY/MUNICIPALITY','T',0,'L');
        PDF::Cell(20,5,'ZIP CODE','T',0,'L');
        PDF::Cell(30,5,'TELEPHONE NO.','LTR',0,'L');
        PDF::ln();

        PDF::Cell(15,5,' ','LB',0,'L');
        PDF::Cell(15,5,'787-C','B',0,'C');
        PDF::Cell(30,5,'Limasawa','B',0,'L');
        PDF::Cell(40,5,'Pitogo','B',0,'L');
        PDF::Cell(40,5,'Makati','B',0,'L');
        PDF::Cell(20,5,'1234','B',0,'L');
        PDF::Cell(30,5,'12354678910', 'LRB',0,'C');
        PDF::ln();

        PDF::Cell(50,5,'LOCATION OF CONSTRUCTION','L',0,'L');
        PDF::Cell(14,5,'LOT NO.',0,0,'L');
        PDF::Cell(20,5,'','B',0,'L');
        PDF::Cell(13,5,'BLK NO.','T',0,'L');
        PDF::Cell(20,5,'','B',0,'L');
        PDF::Cell(13,5,'TCT NO.','T',0,'L');
        PDF::Cell(20,5,'','B',0,'L');
        PDF::Cell(20,5,'TAX DEC. NO.','T',0,'L');
        PDF::Cell(18,5,'','B',0,'L');
        PDF::Cell(2,5,'','R',0,'L');
        PDF::ln();

        PDF::Cell(13,5,'STREET','L',0,'L');
        PDF::Cell(25,5,'','B',0,'L');
        PDF::Cell(18,5,'BARANGAY','',0,'L');
        PDF::Cell(60,5,'','B',0,'L');
        PDF::Cell(34,5,'CITY/MUNICIPALITY OF','',0,'L');
        PDF::Cell(38,5,'','B',0,'L');
        PDF::Cell(2,5,'','R',0,'L');
        PDF::ln();

        PDF::MultiCell(0, 0, '<b>SCOPE OF WORK</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::ln();

        PDF::Cell(3,$cell_height,'','L',0,'L');
        PDF::Cell(5,$cell_height,'[  ]', 0 ,0,'L');
        PDF::Cell(50,$cell_height,'NEW CONSTRUCTION', $border,0,'L');
        PDF::Cell(5,$cell_height,'[  ]', 0 ,0,'L');
        PDF::Cell(50,$cell_height,'RENOVATION', $border,0,'L');
        PDF::Cell(5,$cell_height,'[  ]', 0 ,0,'L');
        PDF::Cell(50,$cell_height,'RAISING', $border,0,'L');
        PDF::Cell(0,$cell_height,'','R',0,'L');
        PDF::ln();

        PDF::Cell(3,$cell_height,'','L',0,'L');
        PDF::Cell(5,$cell_height,'[  ]', 0 ,0,'L');
        PDF::Cell(50,$cell_height,'ERECTION', $border,0,'L');
        PDF::Cell(5,$cell_height,'[  ]', 0 ,0,'L');
        PDF::Cell(50,$cell_height,'CONVERSION', $border,0,'L');
        PDF::Cell(5,$cell_height,'[  ]', 0 ,0,'L');
        PDF::Cell(50,$cell_height,'DEMOLITION', $border,0,'L');
        PDF::Cell(0,$cell_height,'','R',0,'L');
        PDF::ln();

        PDF::Cell(3,$cell_height,'','L',0,'L');
        PDF::Cell(5,$cell_height,'[  ]', 0 ,0,'L');
        PDF::Cell(50,$cell_height,'ADDITION', $border,0,'L');
        PDF::Cell(5,$cell_height,'[  ]', 0 ,0,'L');
        PDF::Cell(50,$cell_height,'REPAIR', $border,0,'L');
        PDF::Cell(5,$cell_height,'[  ]', 0 ,0,'L');
        PDF::Cell(50,$cell_height,'ACCESORY BUILDING STRUCTURE', $border,0,'L');
        PDF::Cell(0,$cell_height,'','R',0,'L');
        PDF::ln();

        PDF::Cell(3,$cell_height,'','L',0,'L');
        PDF::Cell(5,$cell_height,'[  ]', 0 ,0,'L');
        PDF::Cell(50    ,$cell_height,'ALTERATION', $border,0,'L');
        PDF::Cell(5,$cell_height,'[  ]', 0 ,0,'L');
        PDF::Cell(50,$cell_height,'MOVING', $border,0,'L');
        PDF::Cell(5,$cell_height,'[  ]', 0 ,0,'L');
        PDF::Cell(25,$cell_height,'OTHERS (Specify)', $border,0,'L');
        PDF::Cell(25,$cell_height,'', 'B' ,0,'L');
        PDF::Cell(0,$cell_height,'','R',0,'L');
        PDF::ln();

        PDF::SetFont('helvetica','B',$font_size);
        PDF::Cell(0, 5,'BOX 2 (TO BE ACCOMPLISHED BY THE DESIGN PROFESSIONAL)','TB',0,'l');
        PDF::ln();

        PDF::Cell(0, 5,'INSTALLATION AND OPERATION OF:','LR',0,'l');
        PDF::ln();

        PDF::SetFont('helvetica','',6);
        PDF::Cell(10,$cell_height,' ', 'L' ,0,'L');
        PDF::Cell(5,$cell_height,'[  ]', 0 ,0,'L');
        PDF::Cell(65,$cell_height,'BOILER', $border ,0,'L');
        PDF::Cell(5,$cell_height,'[  ]', 0 ,0,'L');
        PDF::Cell(50,$cell_height,'CENTRAL AIRCONDITIONING', $border ,0,'L');
        PDF::Cell(5,$cell_height,'[  ]', 0 ,0,'L');
        PDF::Cell(50,$cell_height,'DUMBWAITER', 'R' ,0,'L');
        PDF::ln();

        PDF::Cell(10,$cell_height,' ', 'L' ,0,'L');
        PDF::Cell(5,$cell_height,'[  ]', 0 ,0,'L');
        PDF::Cell(65,$cell_height,'PRESSURE VESSEL', $border ,0,'L');
        PDF::Cell(5,$cell_height,'[  ]', 0 ,0,'L');
        PDF::Cell(50,$cell_height,'MECHANICAL VENTILLATION', $border ,0,'L');
        PDF::Cell(5,$cell_height,'[  ]', 0 ,0,'L');
        PDF::Cell(50,$cell_height,'PUMPS', 'R' ,0,'L');
        PDF::ln();

        PDF::Cell(10,$cell_height,' ', 'L' ,0,'L');
        PDF::Cell(5,$cell_height,'[  ]', 0 ,0,'L');
        PDF::Cell(65,$cell_height,'INTERNAL COMBUSTION ENGINE', $border ,0,'L');
        PDF::Cell(5,$cell_height,'[  ]', 0 ,0,'L');
        PDF::Cell(50,$cell_height,'ESCALATOR', $border ,0,'L');
        PDF::Cell(5,$cell_height,'[  ]', 0 ,0,'L');
        PDF::Cell(50,$cell_height,'COMPRESSED AIR, VACUUM INSTITUTIONAL', 'R' ,0,'J');
        PDF::ln();

        PDF::Cell(10,$cell_height,' ', 'L' ,0,'L');
        PDF::Cell(5,$cell_height,'[  ]', 0 ,0,'L');
        PDF::Cell(65,$cell_height,'REFRIGETATION AND ICE MAKING', $border ,0,'L');
        PDF::Cell(5,$cell_height,'[  ]', 0 ,0,'L');
        PDF::Cell(50,$cell_height,'MOVING SIDEWALK', $border ,0,'L');
        PDF::Cell(5,$cell_height,' ', 0 ,0,'L');
        PDF::Cell(50,$cell_height,'and/or INDUSTRIAL GAS', 'R' ,0,'L');
        PDF::ln();

        PDF::Cell(10,$cell_height,' ', 'L' ,0,'L');
        PDF::Cell(5,$cell_height,'[  ]', 0 ,0,'L');
        PDF::Cell(65,$cell_height,'WINDOW TYPE AIRCONDITIONING', $border ,0,'L');
        PDF::Cell(5,$cell_height,'[  ]', 0 ,0,'L');
        PDF::Cell(50,$cell_height,'FREIGHT ELEVATOR', $border ,0,'L');
        PDF::Cell(5,$cell_height,'[  ]', 0 ,0,'L');
        PDF::Cell(50,$cell_height,'PNEUMATIC TUBES, CONVEYORS', 'R' ,0,'L');
        PDF::ln();

        PDF::Cell(10,$cell_height,' ', 'L' ,0,'L');
        PDF::Cell(5,$cell_height,'[  ]', 0 ,0,'L');
        PDF::Cell(65,$cell_height,'PACKAGED SPLIT TYPE AIRCONDITIONING', $border ,0,'L');
        PDF::Cell(5,$cell_height,'[  ]', 0 ,0,'L');
        PDF::Cell(50,$cell_height,'PASSENGER ELEVATOR', $border ,0,'L');
        PDF::Cell(5,$cell_height,' ', 0 ,0,'L');
        PDF::Cell(50,$cell_height,'and/or MONORALS', 'R' ,0,'L');
        PDF::ln();

        PDF::Cell(10,$cell_height,' ', 'L' ,0,'L');
        PDF::Cell(5,$cell_height,'[  ]', 0 ,0,'L');
        PDF::Cell(20,$cell_height,'OTHERS (Specify)', $border ,0,'L');
        PDF::Cell(25,$cell_height,'', 'B' ,0,'L');
        PDF::Cell(20,$cell_height,'', 0 ,0,'L');
        
        PDF::Cell(5,$cell_height,'[  ]', 0 ,0,'L');
        PDF::Cell(50,$cell_height,'CABLE CAR', $border ,0,'L');
        PDF::Cell(5,$cell_height,'[  ]', 0 ,0,'L');
        PDF::Cell(50,$cell_height,'FUNICULAR', 'R' ,0,'L');
        PDF::ln();

        PDF::Cell(0,$cell_height,' ', 'LR' ,0,'L');
        PDF::ln();

        PDF::SetFont('helvetica','',$font_size);
        PDF::Cell(5, $cell_height,'','L',0,'l');
        PDF::Cell(22, $cell_height,'PREPARED BY', '' ,0,'l');
        PDF::Cell(150, $cell_height,'','B',0,'l');
        PDF::Cell(13, $cell_height,'','R',0,'l');
        PDF::ln();
        
        PDF::Cell(0, $cell_height,'','LR',0,'l');
        PDF::ln();
        PDF::Cell(0, $cell_height,'','LRB',0,'l');
        PDF::ln();

        
        PDF::SetFont('helvetica','B',$font_size);
        PDF::Cell(95, $cell_height,'BOX 3',0,0,'l');
        PDF::Cell(5, $cell_height,' ', '',0,'l');
        PDF::Cell(0, $cell_height,'BOX 4', 0,0,'l');
        PDF::ln();

        
        PDF::SetFont('helvetica','B',6);
        PDF::Cell(95, $cell_height,'DESIGN PROFESSIONAL, PLANS AND SPECIFICATIONS',1,0,'l');
        PDF::Cell(5, $cell_height,' ', 'LR',0,'l');
        PDF::Cell(90, $cell_height,'SUPERVISOR IN-CHARGE OF MECHANICAL WORKS',1,0,'l');
        PDF::ln();

        PDF::SetFont('helvetica','B', 6);
        PDF::Cell(95, $cell_height,'','LR',0,'l');
        PDF::Cell(5, $cell_height,' ', 0,0,'l');
        PDF::Cell(4, $cell_height,'[  ]', 'L',0,'l');
        PDF::Cell(50, $cell_height,'PROFESSIONAL MECHANICAL ENGINEER', 0,0,'l');
        PDF::Cell(4, $cell_height,'[  ]', 0,0,'l');
        PDF::Cell(0, $cell_height,'MECHANICAL ENGINEER', 'R',0,'l');
        PDF::ln();

        PDF::Cell(95, $cell_height,'','LR',0,'l');
        PDF::Cell(5, $cell_height,' ', 0,0,'l');
        PDF::Cell(90, $cell_height,'','LR',0,'l');
        PDF::ln();

        PDF::Cell(15, $cell_height,'','L',0,'l');
        PDF::Cell(65, $cell_height,'','B',0,'l');
        PDF::Cell(15, $cell_height,'','R',0,'l');

        PDF::Cell(5, $cell_height,' ', 0,0,'l');
        PDF::Cell(0, $cell_height,' ', 'LR',0,'l');
        PDF::ln();

        PDF::SetFont('helvetica','B', 6);
        PDF::Cell(95, 3,'PROFESSIONAL MECHANICAL ENGINEER','LR',0,'C');
        PDF::SetFont('helvetica','', 6);
        PDF::Cell(5, 3,' ', 0,0,'l');
        PDF::Cell(12.5, 3,'','L',0,'l');
        PDF::Cell(65, 3,'','B',0,'l');
        PDF::Cell(12.5, 3,'','R',0,'l');
        PDF::ln();

        PDF::Cell(95, 3,'(Signed and/or Sealed Over Printed Name)','LR',0,'C');
        PDF::Cell(5, 3,' ', 0,0,'l');
        PDF::Cell(90, 3,'(Signed and/or Sealed Over Printed Name)','LR',0,'C');
        PDF::ln();

        PDF::Cell(95, 3,'Date ___________________','LR',0,'C');
        PDF::Cell(5, 3,' ', 0,0,'l');
        PDF::Cell(90, 3,'Date ___________________','LR',0,'C');
        PDF::ln();

        PDF::Cell(95, $cell_height,'Address', 1 ,0,'L');
        PDF::Cell(5, $cell_height,' ', 0,0,'l');
        PDF::Cell(90, $cell_height,'Address', 1 ,0,'L');
        PDF::ln();
        
        PDF::Cell(47.5, $cell_height,'PRC. No', 1 ,0,'L');
        PDF::Cell(47.5, $cell_height,'Validity', 1 ,0,'L');
        PDF::Cell(5, $cell_height,' ', 0,0,'l');
        PDF::Cell(45, $cell_height,'PRC. No', 1 ,0,'L');
        PDF::Cell(45, $cell_height,'Validity', 1 ,0,'L');
        PDF::ln();

        PDF::Cell(47.5, $cell_height,'PTR. No', 1 ,0,'L');
        PDF::Cell(47.5, $cell_height,'Date Issued', 1 ,0,'L');
        PDF::Cell(5, $cell_height,' ', 0,0,'l');
        PDF::Cell(45, $cell_height,'PTR. No', 1 ,0,'L');
        PDF::Cell(45, $cell_height,'Date Issued', 1 ,0,'L');
        PDF::ln();

        PDF::Cell(47.5, $cell_height,'Issued at', 1 ,0,'L');
        PDF::Cell(47.5, $cell_height,'TIN', 1 ,0,'L');
        PDF::Cell(5, $cell_height,' ', 0,0,'l');
        PDF::Cell(45, $cell_height,'Issued at', 1 ,0,'L');
        PDF::Cell(45, $cell_height,'TIN', 1 ,0,'L');
        PDF::ln();
        
        PDF::SetFont('helvetica','B',$font_size);
        PDF::Cell(95, $cell_height,'BOX 5',0,0,'l');
        PDF::Cell(5, $cell_height,' ', '',0,'l');
        PDF::Cell(0, $cell_height,'BOX 6', 0,0,'l');
        PDF::ln();

        PDF::SetFont('helvetica','', 6);
        PDF::Cell(95, $cell_height,'BUILDING OWNER','LTR',0,'l');
        PDF::Cell(5, $cell_height,' ', 'LR',0,'l');
        PDF::Cell(90, $cell_height,'WITH MY CONCENT: LOT OWNER','LTR',0,'l');
        PDF::ln();

        PDF::Cell(95, $cell_height,' ','LR',0,'l');
        PDF::Cell(5, $cell_height,' ', '',0,'l');
        PDF::Cell(0, $cell_height,' ', 'LR',0,'l');
        PDF::ln();

        PDF::Cell(95, $cell_height,' ','LR',0,'l');
        PDF::Cell(5, $cell_height,' ', '',0,'l');
        PDF::Cell(0, $cell_height,' ', 'LR',0,'l');
        PDF::ln();

        PDF::Cell(15, $cell_height,'','L',0,'l');
        PDF::Cell(65, $cell_height,'','B',0,'l');
        PDF::Cell(15, $cell_height,'','R',0,'l');
        PDF::Cell(5, $cell_height,' ', '',0,'l');
        PDF::Cell(12.5, $cell_height,'','L',0,'l');
        PDF::Cell(65, $cell_height,'','B',0,'l');
        PDF::Cell(12.5, $cell_height,'','R',0,'l');
        PDF::ln();

        PDF::Cell(95, 3,'(Signed and/or Sealed Over Printed Name)','LR',0,'C');
        PDF::Cell(5, 3,' ', 0,0,'l');
        PDF::Cell(90, 3,'(Signed and/or Sealed Over Printed Name)','LR',0,'C');
        PDF::ln();

        PDF::Cell(95, 3,'Date ___________________','LR',0,'C');
        PDF::Cell(5, 3,' ', 0,0,'l');
        PDF::Cell(90, 3,'Date ___________________','LR',0,'C');
        PDF::ln();

        PDF::Cell(95, $cell_height,'Address', 1 ,0,'L');
        PDF::Cell(5, $cell_height,' ', 0,0,'l');
        PDF::Cell(90, $cell_height,'Address', 1 ,0,'L');
        PDF::ln();

        PDF::Cell(35, $cell_height,'C.T.C NO.', 1 ,0,'L');
        PDF::Cell(30, $cell_height,'Date Issued', 1 ,0,'L');
        PDF::Cell(30, $cell_height,'Place Issued', 1 ,0,'L');
        PDF::Cell(5, $cell_height,' ', 0,0,'l');
        PDF::Cell(30, $cell_height,'C.T.C NO.', 1 ,0,'L');
        PDF::Cell(30, $cell_height,'Date Issued', 1 ,0,'L');
        PDF::Cell(30, $cell_height,'Place Issued', 1 ,0,'L');
        PDF::ln();

        // page 2
        PDF::AddPage('P', 'cm', array(8.5, 13), true, 'UTF-8', false);

        PDF::SetFont('helvetica','B',$font_size);
        PDF::Cell(0, $cell_height,'TO BE ACCOMPLISHED BY THE PROCESSING AND EVALUATION DIVISION',0,0,'L');
        PDF::ln();
        PDF::Cell(0, $cell_height,'BOX 7',0,0,'L');
        PDF::ln();

        PDF::SetFont('helvetica','',$font_size);
        PDF::Cell(95, $cell_height,'RECEIVED BY:', 1,0,'L');
        PDF::Cell(95, $cell_height,'DATE:','TRB',0,'L');
        PDF::ln();

        PDF::SetFont('helvetica','B',$font_size);
        PDF::Cell(0, $cell_height,'FIVE (5) SETS OF MECHANICAL DOCUMENTS', 'LR', 0,'C');
        PDF::ln();
        PDF::Cell(0, $cell_height,' ','LR',0,'L');
        PDF::ln();

        PDF::SetFont('helvetica','',$font_size);
        PDF::Cell(5, $cell_height, ' ', 'L',0,'L');
        PDF::Cell(5, $cell_height,'[  ]', 0,0,'L');
        PDF::Cell(100, $cell_height,'MECHANICAL PLANS AND SPECIFICATIONS', 0,0,'L');
        PDF::Cell(5, $cell_height,'[  ]', 0,0,'L');
        PDF::Cell(0, $cell_height,'COST ESTIMATES', 'R',0,'L');
        PDF::ln();

        PDF::Cell(5, $cell_height,'', 'L',0,'L');
        PDF::Cell(5, $cell_height,'[  ]', 0,0,'L');
        PDF::Cell(100, $cell_height,'BILL OF MATERIALS', ' ',0,'L');
        PDF::Cell(5, $cell_height,'[  ]', 0,0,'L');
        PDF::Cell(28, $cell_height,'OTHERS (SPECIFY)', 0,0,'L');
        PDF::Cell(35, $cell_height,'', 'B',0,'L');
        PDF::Cell(0, $cell_height,' ', 'R',0,'L');
        PDF::ln();
        PDF::Cell(0, $cell_height,' ', 'LR',0,'L');
        PDF::ln();
        
        PDF::SetFont('helvetica','B',$font_size);
        PDF::Cell(0, $cell_height,'BOX 8', 'TB',0,'L');
        PDF::ln();

        PDF::SetFont('helvetica','',$font_size);

        $tbl1 = '<table id="purhcase-order-print-1-table" width="100%"   cellspacing="0" cellpadding="1" border="0.5">
                    <tr>
                        <td colspan="1" rowspan="1" align="center" width="100%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            <B>PROGRESS FLOW</B>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1" rowspan="2" align="left" width="35%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="2" rowspan="1" align="center" width="20%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            IN
                        </td>
                        <td colspan="2" rowspan="1" align="center" width="20%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            OUT
                        </td>
                        <td colspan="1" rowspan="2" align="center" width="25%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            PROCESSED BY
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1" rowspan="1" align="center" width="10%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            TIME
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="10%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            DATE
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="10%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            TIME
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="10%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            DATE
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1" rowspan="1" align="left" width="35%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                        MECHANICAL
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="10%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="10%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="10%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="10%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="25%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1" rowspan="1" align="left" width="35%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                        OTHERS (Specify)
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="10%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="10%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="10%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="10%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="25%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1" rowspan="1" align="left" width="35%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                        
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="10%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="10%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="10%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="10%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="25%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                    </tr>
                </table>';
        PDF::writeHTML($tbl1, false, false, false, false, '');

        PDF::SetFont('helvetica','B',$font_size);
        PDF::Cell(0, $cell_height,'BOX 9', 'TB',0,'L');
        PDF::ln();

        
        PDF::Cell(0, $cell_height,' ', 'LTR',0,'L');
        PDF::ln();

        PDF::Cell(0, $cell_height,' ', 'LR',0,'L');
        PDF::ln();

        PDF::Cell(0, $cell_height,'ACTION TAKEN:', 'LR',0,'L');
        PDF::ln();

        PDF::Cell(0, $cell_height,' ', 'LR',0,'L');
        PDF::ln();

        PDF::Cell(0, $cell_height,'PERMIT IS HEREBY ISSUED SUBJECT TO THE FOLLOWING:', 'LR',0,'L');
        PDF::ln();

        PDF::Cell(0, $cell_height,' ', 'LR',0,'L');
        PDF::ln();

        PDF::SetFont('helvetica','',$font_size);
        PDF::Cell(10, $cell_height,'1.', 'L',0,'C');
        PDF::Cell(175, $cell_height,'That the proposed mechanical works shall be in accordance with the mechanical plans filed with this OFfice and in conformity with the latest', 0,0,'J');
        PDF::Cell(5, $cell_height,'', 'R',0,'C');
        PDF::ln();

        PDF::Cell(10, $cell_height,'','L',0,'C');
        PDF::Cell(0, $cell_height,'Philippine Mechanical Code, the National Building Code and its IRR.', 'R',0,'L');
        PDF::ln();

        PDF::Cell(10, $cell_height,'2.', 'L',0,'C');
        PDF::MultiCell(0, $cell_height, 'That prior to any mechanical installation, a duly accomplished prescribed <b>"NOTICE OF CONSTRUCTION"</b> shall be submitted to the Office', 'R', 'L', 0, 0, '', '', true, 0, true);
        PDF::Cell(5, $cell_height,'', 'R',0,'C');
        PDF::ln();
        
        PDF::Cell(10, $cell_height,'', 'L',0,'C');
        PDF::Cell(0, $cell_height,'of the Building Official', 'R',0,'L');
        PDF::ln();

        PDF::Cell(10, $cell_height,'3.', 'L',0,'C');
        PDF::Cell(175, $cell_height,'That upon completion of the mechanical works, the licensed supervision in-charge shall submit the entry to the logbook duly signed and', 0,0,'J');
        PDF::Cell(5, $cell_height,'', 'R',0,'C');
        PDF::ln();

        PDF::Cell(10, $cell_height,'', 'L',0,'C');
        PDF::Cell(175, $cell_height,'sealed to the building official including as-bild plans and other documents and shall also accomplish the certificate of completion stating that the', 0,0,'J');
        PDF::Cell(5, $cell_height,'', 'R',0,'C');
        PDF::ln();

        PDF::Cell(10, $cell_height,'', 'L',0,'C');
        PDF::Cell(175, $cell_height,'mechanical works conform to the provision of the Philippine Mechanical Code, the National Building Code and its IRR.', 0,0,'L');
        PDF::Cell(5, $cell_height,'', 'R',0,'C');
        PDF::ln();

        PDF::Cell(10, $cell_height,'4.', 'L',0,'C');
        PDF::MultiCell(175, $cell_height, 'That this permit is <b>null and void</b> unless accompanied by the building permit.', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::Cell(5, $cell_height,'', 'R',0,'L');
        PDF::ln();

        PDF::Cell(10, $cell_height,'5.', 'L',0,'C');
        PDF::MultiCell(0, $cell_height, 'That a certificate of Operation shall be issued for the continuous use of mechanical installations.', 'R', 'L', 0, 0, '', '', true, 0, true);
        PDF::ln();

        PDF::Cell(0, 40,'', 'LR',0,'C');
        PDF::ln();
        
        PDF::SetFont('helvetica','B',$font_size);
        PDF::Cell(0, $cell_height,'PERMIT ISSUED BY:', 'LR',0,'L');
        PDF::ln();

        PDF::Cell(0, 50,'', 'LR',0,'C');
        PDF::ln();

        PDF::Cell(55, $cell_height,'', 'L',0,'C');
        PDF::Cell(80, $cell_height,'', 'B',0,'C');
        PDF::Cell(55, $cell_height,'', 'R',0,'C');
        PDF::ln();

        
        PDF::Cell(0, $cell_height,'BUILDING OFFICIAL', 'LR',0,'C');
        PDF::ln();

        PDF::SetFont('helvetica','',$font_size);
        PDF::Cell(0, $cell_height,'(Signature Over Printed Name)', 'LR',0,'C');
        PDF::ln();

        PDF::Cell(0, $cell_height,'Date __________________', 'LR',0,'C');
        PDF::ln();

        PDF::Cell(0, 20,'', 'LRB',0,'C');
        PDF::ln();

        PDF::Output('Mechanical Permit'.'.pdf');
    }

    public function print_order_of_payment(Request $request, $params)
    {
		
        $data = $this->_engjobrequest->find($params);
        $get_eng_fees = $this->engineeringRepository->engFees($params);
        $job_request_details = $this->engineeringRepository->getJobRequestDetails($params);
        // get transaction_no here
        $checkidexist = $this->_engjobrequest->checkTransactionexist($data->id,$data->top_transaction_type_id);
        
        $client_name = $job_request_details->rpo_first_name .' '.$job_request_details->rpo_middle_name .' '. $job_request_details->rpo_custom_last_name;
        $location = $job_request_details->rpo_address_house_lot_no. ' '. $job_request_details->rpo_address_street_name;

        $date_applied = $job_request_details->ebpa_application_date;
        
        $created_at = Carbon::parse($date_applied)->format("F Y");

        $date_prepared = ($checkidexist[0]->created_at);;
        $datePrepared = Carbon::parse($date_prepared)->format("F d, Y");
        
        //print_r($job_request_details); exit;
        PDF::SetTitle('Order OF Payment: ');        
        PDF::SetMargins(20, 15, 20,true);    
        PDF::SetAutoPageBreak(FALSE, 0);
        PDF::AddPage('P', 'LETTER');
        PDF::SetFont('Helvetica', '', 9);
        $location = '';

        // dd($data->details);
        switch ($data->es_id) {
            case '1':
                $location = $data->details->ebpa_location; // building permit
                $preparedby = 'eng_jreq_building_permit_prepared_by';
                $approvedby = 'eng_jreq_building_permit_approved_by';
                break;
            case '2':
                $location = $data->details->eda_location; // demolition permit
                 $preparedby = 'eng_jreq_demolition_permit_prepared_by';
                 $approvedby = 'eng_jreq_demolition_permit_approved_by';
                break;
            case '3':
                $location = $data->details->espa_location; // sanitary pumbling
                 $preparedby = 'eng_jreq_sanitary_permit_prepared_by';
                 $approvedby = 'eng_jreq_sanitary_permit_approved_by';
                break;
            case '4':
                $location = $data->details->ebpa_location; // fencing no model
                $preparedby = 'eng_jreq_fencing_permit_prepared_by';
                $approvedby = 'eng_jreq_fencing_permit_approved_by';
                break;
            case '5':
                $location = $data->details->eega_location; // excavation
                $preparedby = 'eng_jreq_excavation_permit_prepared_by';
                $approvedby = 'eng_jreq_excavation_permit_approved_by';
                break;
            case '6':
                $location = ''; // electrical
                $preparedby = 'eng_jreq_electrical_permit_prepared_by';
                $approvedby = 'eng_jreq_electrical_permit_approved_by';
                break;
            case '7':
                $location = ''; // water
                $preparedby = '';
                $approvedby = '';
                break;
            case '8':
                $location = $data->details->ebpa_location; // sign permit
                $preparedby = 'eng_jreq_sign_permit_prepared_by';
                $approvedby = 'eng_jreq_sign_permit_approved_by';
                break;
            case '9':
                $location = $data->details->eeta_location; // electronics
                $preparedby = 'eng_jreq_electronics_permit_prepared_by';
                $approvedby = 'eng_jreq_electronics_permit_approved_by';
                break;
            case '10':
                $location = $data->details->ema_location; // mechanical
                $preparedby = 'eng_jreq_mechanical_permit_prepared_by';
                $approvedby = 'eng_jreq_mechanical_permit_approved_by';
                break;
            case '11':
                $location = $data->details->eca_location; // civil/structural permit
                $preparedby = 'eng_jreq_civil_permit_prepared_by';
                $approvedby = 'eng_jreq_civil_permit_approved_by';
                break;
            case '13':
                $location = $data->details->eea_location; // architectural
                $preparedby = 'eng_jreq_architect_permit_prepared_by';
                $approvedby = 'eng_jreq_architect_permit_approved_by';
                break;
            default:
                # code...
                break;
        }
		
        // dd($data->approver->hr_employee);
        // dd($data->details->occupancy_type);
        // Header
        $top = 10;
        $border = 0;
        
        PDF::MultiCell(0, 0, "Republic of the Philippines", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "<h3>CITY ENGINEER'S OFFICE</h3>", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "Palayan City", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(5);
        PDF::MultiCell(0, 0, '<h3>ORDER OF PAYMENT</h3>', 0, 'C', 0, 1, '', '', true, 0, true);
        
        PDF::ln(5);
        PDF::MultiCell(55, 0, 'Annual Inspection Applicalion No.:', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(45, 0, '<b>'.$job_request_details->application_no.'</b>', 'B', 'L', 0, 0, '', '', true, 0, true);
        
        PDF::MultiCell(0, 0, 'Date Prepared/ Computed: <b>'.strtoupper($datePrepared).'</b>', $border, 'R', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(55, 0, 'Date Applied:', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(45, 0, '<b>'.strtoupper($created_at).'</b>', 'B', 'L', 0, 1, '', '', true, 0, true);
        PDF::ln(5);
        // dd($job_request_details->id);
        PDF::MultiCell(55, 0, 'TOP No.', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(45, 0, '<b>'.$checkidexist[0]->transaction_no.'</b>', 'B', 'L', 0, 1, '', '', true, 0, true);
        PDF::ln(5);

        PDF::MultiCell(55, 0, 'Applicant:', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(70, 0, '<b>'.strtoupper($client_name).'</b>', 'B', 'L', 0, 1, '', '', true, 0, true);
        
        PDF::MultiCell(55, 0, 'Contact No / Person:', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(70, 0, '<b>'.$job_request_details->p_mobile_no.'</b>', 'B', 'L', 0, 1, '', '', true, 0, true);
        
        PDF::MultiCell(55, 0, 'Project:', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(70, 0, '<b>'.strtoupper($job_request_details->ejr_project_name).'</b>', 'B', 'L', 0, 1, '', '', true, 0, true);
        
        PDF::MultiCell(55, 0, 'Location:', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(70, 0, '<b>'.strtoupper($location).'</b>', 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::ln(10);
		
         //dd($data->details->consultant);
        $consultant = isset($data->details->consultant->fullname)?$data->details->consultant->fullname:'';
        PDF::MultiCell(90, 0, 'Architect/Engineer (Signed & Sealed Plans): ', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(70, 0, '<b>'.strtoupper($consultant).'</b>', 'B', 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(10);
        PDF::MultiCell(90, 0, 'CLASSIFICATION OF USE/CHARACTER OF OCCUPANCY ', $border, 'C', 0, 0, '', '', true, 0, true);
        $occpancy_type = isset($data->details->occupancy_type) ? $data->details->occupancy_type : '';
        PDF::MultiCell(70, 0, '<b>'.strtoupper($occpancy_type).'</b>', 'B', 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(10);
		
        $floor = '
        <table>
            <tr>
                <td></td>
                <td width="35%" align="center" style = "border-bottom: 0.7px solid black;"><b>'.$job_request_details->ebfd_floor_area.'</b></td>
                <td>Dimension</td>
            </tr>
            <tr>
                <td>A. i. Floor Area</td>
                <td width="35%" align="center" style = "border-bottom: 0.7px solid black;"><b>'.$job_request_details->ejr_firstfloorarea.'</b></td>
                <td>1st Floor</td>
            </tr>
            <tr>
                <td></td>
                <td width="35%" align="center" style = "border-bottom: 0.7px solid black;"><b>'.$job_request_details->ejr_secondfloorarea.'</b></td>
                <td>2nd Floor</td>
            </tr>
            <tr>
                <td></td>
                <td width="35%" align="center" style = "border-bottom: 0.7px solid black;"><b>'.$job_request_details->ebfd_floor_area.'</b></td>
                <td>Total Area</td>
            </tr>
            <tr>
                <td>ii. Lot Area</td>
                <td width="35%" align="center" style = "border-bottom: 0.7px solid black;"><b>'.$job_request_details->ejr_lotarea.'</b></td>
                <td>sq. mtr.</td>
            </tr>
            <tr>
                <td>iii. Perimeter</td>
                <td width="35%" align="center" style = "border-bottom: 0.7px solid black;"><b>'.$job_request_details->ejr_perimeter.'</b></td>
                <td>L.mtr.</td>
            </tr>
            <tr>
                <td>B. Project Cost:</td>
                <td width="35%" align="center" style="border-bottom-width:1px;text-align:right;"><b>'.number_format($job_request_details->ejr_projectcost,2).'</b></td>
            </tr>
            <tr>
                <td>C. O.R. No.:</td>
                <td width="35%" align="center" style="border-bottom-width:1px">'.$job_request_details->ejr_or_no.'</td>
            </tr>
            <tr>
                <td>Date Paid:</td>
                <td width="35%" align="center" style="border-bottom-width:1px;text-align:right">'.$job_request_details->ordate.'</td>
            </tr>
        </table>
        ';

        PDF::MultiCell(88, 0, $floor, $border, 'L', 0, 0, '', '', true, 0, true);
        $counter = 1;
        $fees = '
                <table>';
                foreach ($get_eng_fees as $eng_fee) 
                {
                    $fees .= '<tr>
                        <td width="40%">'.$counter++.'. ' . $eng_fee->fees_description . '</td>
                        <td width="5%" align="center"></td>
                        <td width="30%" align="center" style = "border-bottom: 0.7px solid black;"><b>'.number_format($eng_fee->taxAmount,2).'</b></td>
                    </tr>';
                }
                $fees .= '<tr>
                            <td width="40%"></td>
                            <td width="5%" align="center"></td>
                        </tr>';
                if ($job_request_details->ejr_surcharge_fee > 0)
                {
                    $fees .= '<tr>
                        <td width="40%">Surcharge Fee</td>
                        <td width="5%" align="center"></td>
                        <td width="30%" align="center" style = "border-bottom: 0.7px solid black;"><b>'.number_format($job_request_details->ejr_surcharge_fee,2).'</b></td>
                    </tr>';
                }
                // dd($job_request_details, $get_eng_fees);
                $fees .= '<tr>
                            <td width="40%">TOTAL FEES</td>
                            <td width="5%" align="center"></td>
                            <td width="30%" align="center" style = "border-bottom: 0.7px solid black;"><b>'.number_format($job_request_details->ejr_totalfees,2).'</b></td>
                        </tr>';
                        // dd($eng_fee->ejr_totalfees);
        $fees .= '</table>';
        PDF::MultiCell(0, 0, $fees, 0, 'L', 0, 1, '', '', true, 0, true);
        
        PDF::SetY(80);
        PDF::ln(120);
        PDF::MultiCell(90, 0, 'Prepared by:', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'Approved by:', $border, 'L', 0, 1, '', '', true, 0, true);
        PDF::ln(10);
     
        $prepared_by = ($job_request_details->userfullname)?$job_request_details->userfullname:'';
        PDF::MultiCell(15, 0, '', $border, 'J', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(60, 0, '<b>'.strtoupper($prepared_by).'</b>', 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(15, 0, '', $border, 'L', 0, 0, '', '', true, 0, true);
        
        $approved_by = ($data->approver)?$data->approver->hr_employee->fullname:'';
        PDF::MultiCell(15, 0, '', $border, 'J', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(60, 0, '<b>'.strtoupper($approved_by).'</b>', 'B', 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(15, 0, '', $border, 'J', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(60, 0, ($job_request_details->hrDesignation)?$job_request_details->hrDesignation:'', $border, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(15, 0, '', $border, 'L', 0, 0, '', '', true, 0, true);

        PDF::MultiCell(15, 0, '', $border, 'J', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(60, 0, ($data->approver)?$data->approver->hr_employee->designation->description:'', $border, 'C', 0, 1, '', '', true, 0, true);

        PDF::ln(20);
        PDF::MultiCell(0, 0, 'Noted:', $border, 'L', 0, 0, '', '', true, 0, true);
        
        PDF::ln(10);
        PDF::MultiCell(15, 0, '', $border, 'J', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(60, 0, 'HON. ADRIANNE MAE J. CUEVAS', 'B', 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(15, 0, '', $border, 'J', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(60, 0, 'CITY MAYOR', $border, 'C', 0, 1, '', '', true, 0, true);

        

        

        //PDF::Output('Order of Payment: '.'.pdf');
       
        $filename ='OrderofPayment'.$params.'.pdf';
        

        $arrSign= $this->_commonmodel->isSignApply($preparedby);
        $isSignVeified = isset($arrSign)?$arrSign->status:0;

        $arrCertified= $this->_commonmodel->isSignApply($approvedby);
        $isSignCertified = isset($arrCertified)?$arrCertified->status:0;

        $signType = $this->_commonmodel->getSettingData('sign_settings');
        
        $folder =  public_path().'/uploads/digital_certificates/';
        if(!File::exists($folder)) { 
            File::makeDirectory($folder, 0755, true, true);
        }
        if($signType==2){
            PDF::Output($folder.$filename,'F');
            @chmod($folder.$filename, 0777);
        }

        $arrData['filename'] = $filename;
        $arrData['isMultipleSign'] = 1;
        $arrData['isDisplayPdf'] = 0;
        $arrData['isSavePdf'] = 0;
        $varifiedSignature = $this->_commonmodel->getuserSignature($job_request_details->ejr_opd_created_by);
        $varifiedPath =  public_path().'/uploads/e-signature/'.$varifiedSignature;

        $certifiedSignature = $this->_commonmodel->getuserSignature($job_request_details->ejr_opd_approved_by);
        $certifiedPath =  public_path().'/uploads/e-signature/'.$certifiedSignature;
          
        if($isSignVeified==1 && $signType==2){
            if(!empty($varifiedSignature) && File::exists($varifiedPath)){
                $arrData['isSavePdf'] = 1;
                $arrData['signerXyPage'] = $arrSign->pos_x.','.$arrSign->pos_y.','.$arrSign->pos_x_end.','.$arrSign->pos_y_end.','.$arrSign->d_page_no;
                $arrData['signaturePath'] = $varifiedSignature;
                if($isSignCertified==0 && $signType==2){
                    $arrData['isDisplayPdf'] = 1;
                    return $this->_commonmodel->applyDigitalSignature($arrData);
                }else{
                    $this->_commonmodel->applyDigitalSignature($arrData);
                }
            }
        }

        if($isSignCertified==1 && $signType==2){
            if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                $arrData['isSavePdf'] = ($arrData['isSavePdf']==1)?0:1;
                $arrData['signerXyPage'] = $arrCertified->pos_x.','.$arrCertified->pos_y.','.$arrCertified->pos_x_end.','.$arrCertified->pos_y_end.','.$arrCertified->d_page_no;
                $arrData['isDisplayPdf'] = 1;
                $arrData['signaturePath'] = $certifiedSignature;
                return $this->_commonmodel->applyDigitalSignature($arrData);
            }
        }

        
        if($isSignVeified==1 && $signType==1){
            // Apply E-sign Here (Prepared by)
            if(!empty($varifiedSignature) && File::exists($varifiedPath)){
                PDF::Image($varifiedPath,$arrSign->esign_pos_x, $arrSign->esign_pos_y, 35);
                
            }
        }
        


        if($isSignCertified==1 && $signType==1){
            // Apply E-sign Here (Approved By)
            if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                PDF::Image($certifiedPath,$arrCertified->esign_pos_x, $arrCertified->esign_pos_y, 35);

                
            }
        }
       

        if($signType==2){
            if(File::exists($folder.$filename)) { 
                File::delete($folder.$filename);
            }
        }
        PDF::Output($filename,"I");
    }
}