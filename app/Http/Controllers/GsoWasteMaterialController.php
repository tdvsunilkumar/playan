<?php

namespace App\Http\Controllers;
use App\Models\MenuGroup;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\GsoWasteMaterialInterface;
use App\Interfaces\GsoPurchaseOrderInterface;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\WasteMaterialExport;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class GsoWasteMaterialController extends Controller
{
    private GsoWasteMaterialInterface $gsoWasteMaterialRepository;
    private GsoPurchaseOrderInterface $gsoPurchaseOrderRepository;
    private $carbon;
    private $slugs;

    public function __construct(
        GsoWasteMaterialInterface $gsoWasteMaterialRepository, 
        GsoPurchaseOrderInterface $gsoPurchaseOrderRepository,
        Carbon $carbon
    ) {
        date_default_timezone_set('Asia/Manila');
        $this->gsoWasteMaterialRepository = $gsoWasteMaterialRepository;
        $this->gsoPurchaseOrderRepository = $gsoPurchaseOrderRepository;
        $this->carbon = $carbon;
        $this->slugs = 'general-services/waste-materials';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $permission = array(
            'create' => $this->is_permitted($this->slugs, 'create', 1),
            'read' => $this->is_permitted($this->slugs, 'read', 1),
            'update' => $this->is_permitted($this->slugs, 'update', 1),
            'delete' => $this->is_permitted($this->slugs, 'delete', 1),
            'approve' => $this->is_permitted($this->slugs, 'approve', 1),
            'disapprove' => $this->is_permitted($this->slugs, 'disapprove', 1),
            'download' => $this->is_permitted($this->slugs, 'download', 1)
        );
        return view('general-services.waste-materials.index')->with(compact('permission'));
    }
    
    public function lists(Request $request) 
    { 
        $statusClass = [
            'draft' => (object) ['bg' => 'draft-bg', 'status' => 'draft'],
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'pending'],
            'posted' => (object) ['bg' => 'completed-bg', 'status' => 'posted'],
            'cancelled' => (object) ['bg' => 'cancelled-bg', 'status' => 'cancelled'],
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn me-05 btn-sm align-items-center" title="edit this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-pencil text-white"></i></a>';
        }
        $result = $this->gsoWasteMaterialRepository->listItems($request);
        $res = $result->data->map(function($waste, $iteration = 0) use ($statusClass, $actions) {
            $description = $waste->item ? wordwrap($waste->item->code . ' - ' . $waste->item->name, 25, "\n") : ''; 
            $supplier = $waste->supplier ? wordwrap($waste->supplier, 25, "\n") : ''; 
            $iteration++;
            $unitCost = $this->gsoPurchaseOrderRepository->getItemCost($waste->rfqID, $waste->item->id);
            $totalCost = floatval($unitCost) * floatval($waste->quantity_po);
            return [
                'item_no' => $iteration,
                'qty' => $waste->quantity_po,
                'uom' => $waste->uom->code,
                'description' => '<div class="showLess" title="' . ($waste->item ? $waste->item->code . ' - ' . $waste->item->name : '') . '">' . $description . '</div>',
                'or_no' => '<strong>'.$this->gsoWasteMaterialRepository->get_po_reference_no($waste->poID, $waste->item->id).'</strong>',
                'supplier' => '<div class="showLess" title="' . ($waste->supplier ? $waste->supplier . ' - ' . $waste->supplier : '') . '">' . $supplier . '</div>',
                'po_no' => '<strong class="text-primary">'.$waste->po_no.'</strong><br/>'.date('d-M-Y', strtotime($waste->po_date)),
                'unit_cost_label' => $this->money_format($unitCost),
                'total_cost_label' => $this->money_format($totalCost),
                'unit_cost' => $unitCost,
                'total_cost' => $totalCost,
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function money_format($money)
    {
        return (floatval($money) > 0 ) ? '₱' . number_format(floor(($money*100))/100, 2) : '';
    }

    public function find(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->gsoWasteMaterialRepository->find($id)
        ]);
    }

    public function export(Request $request)
    {   
        return Excel::download(new WasteMaterialExport($request->get('keywords')), 'WasteMaterialSheet_'.time().'.xlsx');
    }
}
