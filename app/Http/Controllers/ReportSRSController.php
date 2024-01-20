<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportSRSController extends Controller
{
    public function __construct(){
        $this->slugs = 'setup/srs';
    }
        public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        return view('reports.setup.srs.index');
    }
}
