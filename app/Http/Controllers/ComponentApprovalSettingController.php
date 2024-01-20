<?php

namespace App\Http\Controllers;
use App\Models\UserAccessApprovalSetting;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\ComponentApprovalSettingInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Session;

class ComponentApprovalSettingController extends Controller
{
    private ComponentApprovalSettingInterface $componentApprovalSettingRepository;
    private $carbon;
    private $slugs;

    public function __construct(ComponentApprovalSettingInterface $componentApprovalSettingRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->componentApprovalSettingRepository = $componentApprovalSettingRepository;
        $this->carbon = $carbon;
        $this->slugs = 'components/approval-settings';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        Session::forget('identity');
        // $departments = $this->componentApprovalSettingRepository->allDepartmentsWithRestriction(Auth::user()->id);
        $modules = $this->componentApprovalSettingRepository->allModuleMenus();
        $levels = ['' => 'Select a level', '1' => 'Level 1', '2' => 'Level 2', '3' => 'Level 3', '4' => 'Level 4'];
        $users = $this->componentApprovalSettingRepository->allUsers();
        $departmentx = $this->componentApprovalSettingRepository->allDepartmentx();
        $sub_modules = ['' => 'Select a sub module'];
        return view('components.approval-settings.index')->with(compact('departmentx', 'users', 'modules', 'sub_modules', 'levels'));
    }
       
    public function lists(Request $request) 
    { 
        $statusClass = [
            0 => (object) ['bg' => 'bg-secondary', 'status' => 'Inactive'],
            1 => (object) ['bg' => 'bg-info', 'status' => 'Active'],
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn me-05 btn-sm align-items-center" title="Edit"><i class="ti-pencil text-white"></i></a>';
        }        
        $canDelete = $this->is_permitted($this->slugs, 'delete', 1);
        $result = $this->componentApprovalSettingRepository->listItems($request);
        $res = $result->data->map(function($setting) use ($statusClass, $actions, $canDelete) {       
            $remarks = wordwrap($setting->remarks, 25, "<br />\n");    
            if ($canDelete > 0) {
                $actions .= ($setting->identityStatus > 0) ? '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-trash text-white"></i></a>' : '<a href="javascript:;" class="action-btn restore-btn bg-info btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-reload text-white"></i></a>';
            }
            return [
                'id' => $setting->identity,
                'group' => $setting->module->group->name,
                'module' => $setting->module->name,
                'sub_module' => $setting->sub_module ? $setting->sub_module->name : '',
                'levels' => $setting->levels,
                'remarks' => '<div class="showLess" title="' . $setting->remarks . '">' . $remarks . '</div>',
                'modified' => ($setting->identityUpdatedAt !== NULL) ? 
                '<strong>'.$setting->modified->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($setting->identityUpdatedAt)) : 
                '<strong>'.$setting->inserted->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($setting->identityCreatedAt)),
                'status' => $statusClass[$setting->identityStatus]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$setting->identityStatus]->bg. ' p-2">' . $statusClass[$setting->identityStatus]->status . '</span>' ,
                'actions' => $actions
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }
    
    public function store(Request $request)
    {
        $this->is_permitted($this->slugs, 'create');  
        $rows = $this->componentApprovalSettingRepository->validate($request->module_id, $request->sub_module_id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot create an approval setting with an existing module/sub-module.',
                'label' => 'This is an existing module.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => 'module_id',
            ]);
        }

        return response()->json(
            [
                'data' => $this->componentApprovalSettingRepository->store($request, $this->carbon::now(), Auth::user()->id),
                'title' => 'Well done!',
                'text' => "The approval setting's has been successfully saved.",
                'type' => 'success',
                'class' => 'btn-brand'
            ],
            Response::HTTP_CREATED
        );
    }

    public function modify(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'update');  
        $rows = $this->componentApprovalSettingRepository->validate($request->module_id, $request->sub_module_id, $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot create an approval setting with an existing module/sub-module.',
                'label' => 'This is an existing module.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => 'module_id',
            ]);
        }

        return response()->json(
            [
                'data' => $this->componentApprovalSettingRepository->modify($id, $request, $this->carbon::now(), Auth::user()->id),
                'title' => 'Well done!',
                'text' => "The approval setting's has been successfully updated.",
                'type' => 'success',
                'class' => 'btn-brand'
            ],
            Response::HTTP_CREATED
        );
    }

    public function find(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        $setting = $this->componentApprovalSettingRepository->find($id);
        return response()->json([
            'data' => 
            (object) [
                'id' => $setting->id,
                'module_id' => $setting->module_id,
                'sub_module_id' => $setting->sub_module_id,
                'levels' => $setting->levels,
                'remarks' => $setting->remarks,
                'primary' => $this->componentApprovalSettingRepository->findLines(1, $id),
                'secondary' => $this->componentApprovalSettingRepository->findLines(2, $id),
                'tertiary' => $this->componentApprovalSettingRepository->findLines(3, $id),
                'quaternary' => $this->componentApprovalSettingRepository->findLines(4, $id),
            ]
        ]);
    }

    public function reload_sub_module(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->componentApprovalSettingRepository->reload_sub_module($id)
        ]);
    }
}
