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
use App\Interfaces\ComponentMenuModuleInterface;
use App\Interfaces\ComponentMenuGroupInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ComponentMenuModuleController extends Controller
{
    private ComponentMenuModuleInterface $componentMenuModuleRepository;
    private ComponentMenuGroupInterface $componentMenuGroupRepository;
    private $carbon;
    private $slugs;

    public function __construct(ComponentMenuModuleInterface $componentMenuModuleRepository, ComponentMenuGroupInterface $componentMenuGroupRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->componentMenuModuleRepository = $componentMenuModuleRepository;
        $this->componentMenuGroupRepository = $componentMenuGroupRepository;
        $this->carbon = $carbon;
        $this->slugs = 'components/menus/modules';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $groups = $this->componentMenuModuleRepository->allGroupMenus();
        return view('components.menus.modules.index')->with(compact('groups'));
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
        $result = $this->componentMenuModuleRepository->listItems($request);
        $res = $result->data->map(function($menuModule) use ($statusClass, $actions, $canDelete) {
            $code = wordwrap($menuModule->moduleCode, 25, "<br />\n");
            $description = wordwrap($menuModule->moduleDesc, 25, "<br />\n");
            if ($canDelete > 0) {
                $actions .= ($menuModule->moduleStatus > 0) ? '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-trash text-white"></i></a>' : '<a href="javascript:;" class="action-btn restore-btn bg-info btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-reload text-white"></i></a>';
            }
            // $orderAction = '<a href="javascript:;" class="action-btn order-btn order-up bg-arrow btn m-1 btn-sm align-items-center" title="Order Up"><i class="ti-arrow-up text-white"></i></a><a href="javascript:;" class="action-btn order-btn order-down bg-arrow btn m-1 btn-sm align-items-center" title="Order Down"><i class="ti-arrow-down text-white"></i></a>';
            $slug = wordwrap(url('/'.$menuModule->moduleSlug), 25, "<br />\n");
            return [
                'id' => $menuModule->moduleId,
                'group' => $menuModule->group->name,
                'code' => '<div class="showLess" title="' . $menuModule->moduleCode . '">' . $code . '</div>',
                'name' => $menuModule->moduleName,
                'description' => '<div class="showLess" title="' . $menuModule->moduleDesc . '">' . $description . '</div>',
                'icon' => $menuModule->moduleIcon,
                'slug' => '<div class="showLess" title="'.url('/'.$menuModule->moduleSlug).'">' . $slug . '</div>',
                'order' => $menuModule->moduleOrder,
                'modified' => ($menuModule->moduleUpdatedAt !== NULL) ? 
                '<strong>'.$menuModule->modified->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($menuModule->moduleUpdatedAt)) : 
                '<strong>'.$menuModule->inserted->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($menuModule->moduleCreatedAt)),
                'status' => $statusClass[$menuModule->moduleStatus]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$menuModule->moduleStatus]->bg. ' p-2">' . $statusClass[$menuModule->moduleStatus]->status . '</span>' ,
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
        $rows = $this->componentMenuModuleRepository->validate($request->get('code'));
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot update a module menu with an existing code.',
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
                'text' => 'You cannot create a module menu with an existing slug.',
                'label' => 'This is an existing slug.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => 'slug'
            ]);
        }

        $countOrder = floatval(floatval($this->componentMenuModuleRepository->count()) + floatval(1));
        $details = array(
            'menu_group_id' => $request->menu_group_id,
            'code' => $request->get('code'),
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon,
            'slug' => $request->slug,
            'order' => $countOrder,
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        );
        $module = $this->componentMenuModuleRepository->create($details);

        $details_slug = array(
            'slug' => $request->slug,
            'group_id' => $request->menu_group_id,
            'module_id' => $module->id,
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        );
        $this->componentMenuGroupRepository->createSlug($details_slug);

        return response()->json(
            [
                'data' => $module,
                'title' => 'Well done!',
                'text' => 'The module menu has been successfully saved.',
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
            'data' => $this->componentMenuModuleRepository->find($id)
        ]);
    }

    public function update(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update');
        $group = $this->componentMenuModuleRepository->find($id)->menu_group_id;
        $rows = $this->componentMenuModuleRepository->validate($request->get('code'), $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot update a module menu with an existing code.',
                'label' => 'This is an existing code.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => 'code'
            ]);
        }

        $rows = $this->componentMenuGroupRepository->validate_slug($request->slug, $request->menu_group_id, $id, '');
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot update a module menu with an existing slug.',
                'label' => 'This is an existing slug.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => 'slug'
            ]);
        }

        $details = array(
            'menu_group_id' => $request->menu_group_id,
            'code' => $request->get('code'),
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon,
            'slug' => $request->slug,
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );
        $this->componentMenuModuleRepository->update($id, $details);

        $slugID = $this->componentMenuGroupRepository->find_slugID($group, $id, '');
        if ($slugID > 0) {
            $details_slug = array(
                'slug' => $request->slug,
                'group_id' => $request->menu_group_id,
                'module_id' => $id,
                'updated_at' => $this->carbon::now(),
                'updated_by' => Auth::user()->id
            );
            $this->componentMenuGroupRepository->updateSlug($slugID, $details_slug);
        } else {
            $details_slug = array(
                'slug' => $request->slug,
                'group_id' => $request->menu_group_id,
                'module_id' => $id,
                'created_at' => $this->carbon::now(),
                'created_by' => Auth::user()->id
            );
            $this->componentMenuGroupRepository->createSlug($details_slug);
        }

        $groupSlug = array(
            'group_id' => $request->menu_group_id,
            'module_id' => $id,
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );
        $this->componentMenuGroupRepository->updateAllSlugs($group, $id, $groupSlug);

        return response()->json([
            'data' => $this->componentMenuModuleRepository->find($id),
            'title' => 'Well done!',
            'text' => 'The module menu has been successfully updated.',
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
            'data' => $this->componentMenuModuleRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The module menu has been successfully removed.',
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
            'data' => $this->componentMenuModuleRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The module menu has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function order(Request $request, $order, $id)
    {   
        $this->is_permitted($this->slugs, 'update');
        $group_menu = $this->componentMenuModuleRepository->find($id);
        $last_count = $this->componentMenuModuleRepository->count();

        if ($order == 'up') {
            if ($group_menu->order > 1) {
                $prevOrder = floatval($group_menu->order) - floatval(1);    
                $prev_menu = $this->componentMenuModuleRepository->findBy('order', $prevOrder);
                $details = array(
                    'order' => floatval($prev_menu->order) + floatval(1),
                    'updated_at' => $this->carbon::now(),
                    'updated_by' => Auth::user()->id,
                    'is_active' => 1
                );
                $this->componentMenuModuleRepository->update($prev_menu->id, $details);
                $details = array(
                    'order' => $prevOrder,
                    'updated_at' => $this->carbon::now(),
                    'updated_by' => Auth::user()->id,
                    'is_active' => 1
                );
                $this->componentMenuModuleRepository->update($id, $details);
            }
        } else {
            if ($group_menu->order < $last_count) {
                $prevOrder = floatval($group_menu->order) + floatval(1);    
                $prev_menu = $this->componentMenuModuleRepository->findBy('order', $prevOrder);
                $details = array(
                    'order' => floatval($prev_menu->order) - floatval(1),
                    'updated_at' => $this->carbon::now(),
                    'updated_by' => Auth::user()->id,
                    'is_active' => 1
                );
                $this->componentMenuModuleRepository->update($prev_menu->id, $details);
                $details = array(
                    'order' => $prevOrder,
                    'updated_at' => $this->carbon::now(),
                    'updated_by' => Auth::user()->id,
                    'is_active' => 1
                );
                $this->componentMenuModuleRepository->update($id, $details);
            }
        }

        return response()->json([
            'title' => 'Well done!',
            'text' => 'The module menu has re-ordered successfully.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function update_order(Request $request)
    {
        $this->is_permitted($this->slugs, 'update');
        return response()->json([
            'request' => $request,
            'data' => $this->componentMenuModuleRepository->update_order($request),
            'title' => 'Well done!',
            'text' => 'The module menu has been re-ordered successfully.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }
}
