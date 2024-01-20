<?php

namespace App\Interfaces;

interface ReportAcctgFixedAssetInterface 
{    
    public function find($id);

    public function allFundCodes();

    public function allProperties();

    public function reload($type);

    public function get($request);

    public function get_acquisition_cost($type, $fund, $status, $dateFrom, $dateTo);

    public function get_depreciation_cost($type, $fund, $status, $dateFrom, $dateTo);

    public function get_prepared_by();

    public function get_certified_by();
}