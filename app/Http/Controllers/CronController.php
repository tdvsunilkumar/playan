<?php

namespace App\Http\Controllers;
use App\Models\AcctgAccountGeneralLedger;
use App\Models\AcctgAccountSubsidiaryLedger;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\AcctgFixedAssetInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class CronController extends Controller
{
    private AcctgFixedAssetInterface $acctgFixedAssetRepository;
    private $carbon;
    private $slugs;

    public function __construct(
        AcctgFixedAssetInterface $acctgFixedAssetRepository,
        Carbon $carbon
    ) {
        date_default_timezone_set('Asia/Manila');
        $this->acctgFixedAssetRepository = $acctgFixedAssetRepository;
        $this->carbon = $carbon;
    }

    public function depreciate(Request $request)
    {
        $this->acctgFixedAssetRepository->depreciate($this->carbon::now());
        return response()->json([
            'title' => 'Well done!',
            'text' => 'The depreciation has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }
}
