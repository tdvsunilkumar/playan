<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\IpRegistration;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class WidgetsTemplateController extends Controller
{
    public $data = [];
    public $postdata = [];
    
     public function __construct(){
        $this->slugs = 'widgets-template'; 
    }
    
    public function index(Request $request)
    {
		// $this->is_permitted($this->slugs, 'read');
        return view('WidgetsTemplate.index');

    }
   
}
