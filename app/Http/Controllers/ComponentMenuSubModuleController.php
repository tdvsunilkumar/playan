<?php

namespace App\Http\Controllers;
use App\Models\MenuModule;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\ComponentMenuSubModuleInterface;
use App\Interfaces\ComponentMenuModuleInterface;
use App\Interfaces\ComponentMenuGroupInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ComponentMenuSubModuleController extends Controller
{
    private ComponentMenuSubModuleInterface $componentMenuSubModuleRepository;
    private ComponentMenuModuleInterface $componentMenuModuleRepository;
    private ComponentMenuGroupInterface $componentMenuGroupRepository;
    private $carbon;
    private $slugs;

    public function __construct(ComponentMenuSubModuleInterface $componentMenuSubModuleRepository, ComponentMenuModuleInterface $componentMenuModuleRepository, ComponentMenuGroupInterface $componentMenuGroupRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->componentMenuSubModuleRepository = $componentMenuSubModuleRepository;
        $this->componentMenuModuleRepository = $componentMenuModuleRepository;
        $this->componentMenuGroupRepository = $componentMenuGroupRepository;
        $this->carbon = $carbon;
        $this->slugs = 'components/menus/sub-modules';
    }
    
    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $modules = $this->componentMenuSubModuleRepository->allModuleMenus();
        return view('components.menus.sub-modules.index')->with(compact('modules'));
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
        $result = $this->componentMenuSubModuleRepository->listItems($request);
        $res = $result->data->map(function($menuSubModule) use ($statusClass, $actions, $canDelete) {
            $description = wordwrap($menuSubModule->subModuleDesc, 25, "<br />\n");
            if ($canDelete > 0) {
                $actions .= ($menuSubModule->subModuleStatus > 0) ? '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-trash text-white"></i></a>' : '<a href="javascript:;" class="action-btn restore-btn bg-info btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-reload text-white"></i></a>';
            }
            // $orderAction = '<a href="javascript:;" class="action-btn order-btn order-up bg-arrow btn m-1 btn-sm align-items-center" title="Order Up"><i class="ti-arrow-up text-white"></i></a><a href="javascript:;" class="action-btn order-btn order-down bg-arrow btn m-1 btn-sm align-items-center" title="Order Down"><i class="ti-arrow-down text-white"></i></a>';
            $slug = wordwrap(url('/'.$menuSubModule->subModuleSlug), 25, "<br />\n");
            return [
                'id' => $menuSubModule->subModuleId,
                'module' => $menuSubModule->module->group->name.' => '.$menuSubModule->module->name,
                'code' => $menuSubModule->subModuleCode,
                'name' => $menuSubModule->subModuleName,
                'description' => '<div class="showLess" title="' . $menuSubModule->subModuleDesc . '">' . $description . '</div>',
                'icon' => $menuSubModule->subModuleIcon,
                'slug' => '<div class="showLess" title="'.url('/'.$menuSubModule->subModuleSlug).'">' . $slug . '</div>',
                'order' => $menuSubModule->subModuleOrder,
                'modified' => ($menuSubModule->subModuleUpdatedAt !== NULL) ? 
                '<strong>'.$menuSubModule->modified->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($menuSubModule->subModuleUpdatedAt)) : 
                '<strong>'.$menuSubModule->inserted->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($menuSubModule->subModuleCreatedAt)),
                'status' => $statusClass[$menuSubModule->subModuleStatus]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$menuSubModule->subModuleStatus]->bg. ' p-2">' . $statusClass[$menuSubModule->subModuleStatus]->status . '</span>' ,
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
    
    public function store(Request $request): JsonResponse 
    {       
        $this->is_permitted($this->slugs, 'create');
        $rows = $this->componentMenuSubModuleRepository->validate($request->get('code'));
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot update a sub module menu with an existing code.',
                'label' => 'This is an existing code.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => 'code'
            ]);
        }

        $rows = $this->componentMenuGroupRepository->validate_slug($request->slug);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot create a sub module menu with an existing slug.',
                'label' => 'This is an existing slug.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => 'slug'
            ]);
        }

        $countOrder = floatval(floatval($this->componentMenuSubModuleRepository->count()) + floatval(1));
        $details = array(
            'menu_module_id' => $request->menu_module_id,
            'code' => $request->get('code'),
            'name' => $request->name,
            'description' => $request->description,
            'form_name' => $request->form_name,
            'icon' => $request->icon,
            'slug' => $request->slug,
            'order' => $countOrder,
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        );
        $sub_module = $this->componentMenuSubModuleRepository->create($details);
        
        $details_slug = array(
            'slug' => $request->slug,
            'group_id' => $this->componentMenuModuleRepository->find($request->menu_module_id)->menu_group_id,
            'module_id' => $request->menu_module_id,
            'sub_module_id' => $sub_module->id,
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        );
        $this->componentMenuGroupRepository->createSlug($details_slug);

        return response()->json(
            [
                'data' => $sub_module,
                'title' => 'Well done!',
                'text' => 'The sub module menu has been successfully saved.',
                'type' => 'success',
                'class' => 'btn-brand'
            ],
            Response::HTTP_CREATED
        );
    }

    public function find(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->componentMenuSubModuleRepository->find($id)
        ]);
    }

    public function update(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update');
        $rows = $this->componentMenuSubModuleRepository->validate($request->get('code'), $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot update a sub module menu with an existing code.',
                'label' => 'This is an existing code.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => 'code'
            ]);
        }

        $groupId = $this->componentMenuModuleRepository->find($request->menu_module_id)->menu_group_id;
        $rows = $this->componentMenuGroupRepository->validate_slug($request->slug, $groupId, $request->menu_module_id, $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot update a sub module menu with an existing slug.',
                'label' => 'This is an existing slug.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => 'slug'
            ]);
        }

        $details = array(
            'menu_module_id' => $request->menu_module_id,
            'code' => $request->get('code'),
            'name' => $request->name,
            'description' => $request->description,
            'form_name' => $request->form_name,
            'icon' => $request->icon,
            'slug' => $request->slug,
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );

        $slugID = $this->componentMenuGroupRepository->find_slugID($groupId, $request->menu_module_id, $id);
        if ($slugID > 0) {
            $details_slug = array(
                'slug' => $request->slug,
                'group_id' => $groupId,
                'module_id' => $request->menu_module_id,
                'sub_module_id' => $id,
                'updated_at' => $this->carbon::now(),
                'updated_by' => Auth::user()->id
            );
            $this->componentMenuGroupRepository->updateSlug($slugID, $details_slug);
        } else {
            $details_slug = array(
                'slug' => $request->slug,
                'group_id' => $groupId,
                'module_id' => $request->menu_module_id,
                'sub_module_id' => $id,
                'created_at' => $this->carbon::now(),
                'created_by' => Auth::user()->id
            );
            $this->componentMenuGroupRepository->createSlug($details_slug);
        }

        return response()->json([
            'data' => $this->componentMenuSubModuleRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The sub module menu has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function remove(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'delete');
        $details = array(
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id,
            'is_active' => 0
        );

        return response()->json([
            'data' => $this->componentMenuSubModuleRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The sub module menu has been successfully removed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function restore(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'delete');
        $details = array(
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id,
            'is_active' => 1
        );

        return response()->json([
            'data' => $this->componentMenuSubModuleRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The sub module menu has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function order(Request $request, $order, $id)
    {   
        $this->is_permitted($this->slugs, 'update');
        $group_menu = $this->componentMenuSubModuleRepository->find($id);
        $last_count = $this->componentMenuSubModuleRepository->count();

        if ($order == 'up') {
            if ($group_menu->order > 1) {
                $prevOrder = floatval($group_menu->order) - floatval(1);    
                $prev_menu = $this->componentMenuSubModuleRepository->findBy('order', $prevOrder);
                $details = array(
                    'order' => floatval($prev_menu->order) + floatval(1),
                    'updated_at' => $this->carbon::now(),
                    'updated_by' => Auth::user()->id,
                    'is_active' => 1
                );
                $this->componentMenuSubModuleRepository->update($prev_menu->id, $details);
                $details = array(
                    'order' => $prevOrder,
                    'updated_at' => $this->carbon::now(),
                    'updated_by' => Auth::user()->id,
                    'is_active' => 1
                );
                $this->componentMenuSubModuleRepository->update($id, $details);
            }
        } else {
            if ($group_menu->order < $last_count) {
                $prevOrder = floatval($group_menu->order) + floatval(1);    
                $prev_menu = $this->componentMenuSubModuleRepository->findBy('order', $prevOrder);
                $details = array(
                    'order' => floatval($prev_menu->order) - floatval(1),
                    'updated_at' => $this->carbon::now(),
                    'updated_by' => Auth::user()->id,
                    'is_active' => 1
                );
                $this->componentMenuSubModuleRepository->update($prev_menu->id, $details);
                $details = array(
                    'order' => $prevOrder,
                    'updated_at' => $this->carbon::now(),
                    'updated_by' => Auth::user()->id,
                    'is_active' => 1
                );
                $this->componentMenuSubModuleRepository->update($id, $details);
            }
        }

        return response()->json([
            'title' => 'Well done!',
            'text' => 'The sub module menu has re-ordered successfully.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function update_order(Request $request)
    {
        $this->is_permitted($this->slugs, 'update');
        return response()->json([
            'request' => $request,
            'data' => $this->componentMenuSubModuleRepository->update_order($request),
            'title' => 'Well done!',
            'text' => 'The sub module menu has been re-ordered successfully.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }
}
