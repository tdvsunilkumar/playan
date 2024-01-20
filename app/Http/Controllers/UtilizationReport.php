<?php

namespace App\Http\Controllers;

use App\Models\HoInventoryUtilization;
use App\Models\HoMedicalCertificate;
use Illuminate\Http\Request;
use App\Models\HoIssuance;
use Auth;
use DB;
use Carbon\Carbon;

class UtilizationReport extends Controller
{
    // private $slugs;
    // public function __construct(){
    //     $this->_HoInventoryUtilization = new HoInventoryUtilization(); 
    //     $this->slugs = 'reports-inventory-utilization';
    // }
 
    public function index(){
       
            return view('utilizationreport.index');
    }

    public function store(request $request){
       
            
        return view('utilizationreport.create');

    }

   
}
