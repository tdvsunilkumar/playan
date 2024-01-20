<?php

namespace App\Interfaces;

interface ReportTreasuryCollectionInterface 
{    
    public function find($id);

    public function get($request);

    public function allFundCodes();

    public function get_details($fund, $officer, $dateFrom, $dateTo);

    public function get_breakdown_details($or_no);

    // public function get_prepared_by();

    // public function get_certified_by();
}