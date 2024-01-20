<?php

namespace App\Http\Controllers;
use App\Models\MenuGroup;
use App\Models\CommonModelmaster;
use App\Exports\ItemCanvassExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\ReportItemCanvassInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ReportItemCanvassController extends Controller
{
    private ReportItemCanvassInterface $reportItemCanvassRepository;
    private $carbon;
    private $slugs;

    public function __construct(ReportItemCanvassInterface $reportItemCanvassRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->reportItemCanvassRepository = $reportItemCanvassRepository;
        $this->carbon = $carbon;
        $this->slugs = 'reports/general-services/item-canvass';
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
        return view('reports.general-services.item-canvass.index')->with(compact('permission'));
    }
    
    public function lists(Request $request) 
    { 
        $statusClass = [
            0 => (object) ['bg' => 'bg-secondary', 'status' => 'Inactive'],
            1 => (object) ['bg' => 'bg-info', 'status' => 'Active'],
        ];
        $result = $this->reportItemCanvassRepository->listItems($request);
        $res = $result->data->map(function($canvass) use ($statusClass) {
            $items = $canvass->item->remarks != 'NULL' ? wordwrap($canvass->item->code.' - ' . $canvass->item->name . ' {'.$canvass->item->remarks.'}', 25, "\n") : wordwrap($canvass->item->code.' - ' .$canvass->item->name, 25, "\n");
            $gl_account = $canvass->item->gl_account ? wordwrap($canvass->item->gl_account->code.' - ' .$canvass->item->gl_account->description, 25, "\n") : '';
            $branch_name = $canvass->supplier ? wordwrap($canvass->supplier->branch_name, 25, "\n") : '';
            $business_name = $canvass->supplier ? wordwrap($canvass->supplier->business_name, 25, "\n") : '';
            return [
                'id' => $canvass->id,
                'items' => '<div class="showLess" title="' . ($canvass->item->remarks != 'NULL' ? $canvass->item->code.' - ' .$canvass->item->name. ' {'.$canvass->item->remarks.'}' : $canvass->item->code.' - ' .$canvass->item->name) . '">' . $items . '</div>',
                'gl_account' => '<div class="showLess" title="' . ($canvass->item->gl_account ? $canvass->item->gl_account->code.' - ' .$canvass->item->gl_account->description : '') . '">' . $gl_account . '</div>',
                'business_name' => '<div class="showLess" title="' . ($canvass->supplier ? $canvass->supplier->business_name : '') . '">' . $business_name . '</div>',
                'branch_name' => '<div class="showLess" title="' . ($canvass->supplier ? $canvass->supplier->branch_name : '') . '">' . $branch_name . '</div>',
                'brand_model' => $canvass->description,
                'quantity' => $canvass->quantity,
                'unit_cost' => $this->money_format($canvass->unit_cost),
                'total_cost' => $this->money_format($canvass->total_cost),
                'modified' => ($canvass->updated_at !== NULL) ? date('d-M-Y', strtotime($canvass->updated_at)).'<br/>'. date('h:i A', strtotime($canvass->updated_at)) : date('d-M-Y', strtotime($canvass->created_at)).'<br/>'. date('h:i A', strtotime($canvass->created_at))
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
        return '₱' . number_format(floor(($money*100))/100, 2);
    }

    public function export(Request $request)
    {   
        return Excel::download(new ItemCanvassExport($request->get('keywords')), 'ItemCanvassSheet_'.time().'.xlsx');
    }
}
