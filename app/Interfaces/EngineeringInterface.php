<?php

namespace App\Interfaces;

interface EngineeringInterface 
{    
    public function signPrint($id);
    public function fencePrint($id); 
    public function engFees($ejr_id);
    public function getJobRequestDetails($ejr_id);
}