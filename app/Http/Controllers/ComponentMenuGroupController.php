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
use App\Interfaces\ComponentMenuGroupInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ComponentMenuGroupController extends Controller
{
    private ComponentMenuGroupInterface $componentMenuGroupRepository;
    private $carbon;
    private $slugs;

    public function __construct(ComponentMenuGroupInterface $componentMenuGroupRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->componentMenuGroupRepository = $componentMenuGroupRepository;
        $this->carbon = $carbon;
        $this->slugs = 'components/menus/groups';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        return view('components.menus.groups.index');
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
        $result = $this->componentMenuGroupRepository->listItems($request);
        $res = $result->data->map(function($menuGroup) use ($statusClass, $actions, $canDelete) {
            $description = wordwrap($menuGroup->description, 25, "<br />\n");            
            if ($canDelete > 0) {
                $actions .= ($menuGroup->is_active > 0) ? '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-trash text-white"></i></a>' : '<a href="javascript:;" class="action-btn restore-btn bg-info btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-reload text-white"></i></a>';
            }
            // $orderAction = '<a href="javascript:;" class="action-btn order-btn order-up bg-arrow btn m-1 btn-sm align-items-center" title="Order Up"><i class="ti-arrow-up text-white"></i></a><a href="javascript:;" class="action-btn order-btn order-down bg-arrow btn m-1 btn-sm align-items-center" title="Order Down"><i class="ti-arrow-down text-white"></i></a>';
            $slug = wordwrap(url('/'.$menuGroup->slug), 25, "<br />\n");
            return [
                'id' => $menuGroup->id,
                'code' => $menuGroup->code,
                'name' => $menuGroup->name,
                'description' => '<div class="showLess" title="' . $menuGroup->description . '">' . $description . '</div>',
                'icon' => $menuGroup->icon,
                'slug' => '<div class="showLess" title="'.url('/'.$menuGroup->slug).'">' . $slug . '</div>',
                'order' => $menuGroup->order,
                'modified' => ($menuGroup->updated_at !== NULL) ? 
                '<strong>'.$menuGroup->modified->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($menuGroup->updated_at)) : 
                '<strong>'.$menuGroup->inserted->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($menuGroup->created_at)),
                'status' => $statusClass[$menuGroup->is_active]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$menuGroup->is_active]->bg. ' p-2">' . $statusClass[$menuGroup->is_active]->status . '</span>' ,
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
        $rows = $this->componentMenuGroupRepository->validate($request->get('code'));
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot create a group menu with an existing code.',
                'label' => 'This is an existing code.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => 'code',
            ]);
        }

        $rows = $this->componentMenuGroupRepository->validate_slug($request->slug);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot create a group menu with an existing slug.',
                'label' => 'This is an existing slug.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => 'slug'
            ]);
        }

        $countOrder = floatval(floatval($this->componentMenuGroupRepository->count()) + floatval(1));
        $details = array(
            'code' => $request->get('code'),
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon,
            'slug' => $request->slug,
            'order' => $countOrder,
            'is_dashboard' => $request->get('dashboard'),
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        );
        $group = $this->componentMenuGroupRepository->create($details);

        $details_slug = array(
            'slug' => $request->slug,
            'group_id' => $group->id,
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        );
        $this->componentMenuGroupRepository->createSlug($details_slug);

        return response()->json(
            [
                'data' => $group,
                'title' => 'Well done!',
                'text' => 'The group menu has been successfully saved.',
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
            'data' => $this->componentMenuGroupRepository->find($id)
        ]);
    }

    public function update(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update'); 
        $rows = $this->componentMenuGroupRepository->validate($request->get('code'), $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot update a group menu with an existing code.',
                'label' => 'This is an existing code.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => 'code'
            ]);
        }

        $rows = $this->componentMenuGroupRepository->validate_slug($request->slug, $id, '', '');
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot update a group menu with an existing slug.',
                'label' => 'This is an existing slug.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => 'slug'
            ]);
        }

        $details = array(
            'code' => $request->get('code'),
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon,
            'slug' => $request->slug,
            'is_dashboard' => $request->get('dashboard'),
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );

        $slugID = $this->componentMenuGroupRepository->find_slugID($id, '', '');
        if ($slugID > 0) {
            $details_slug = array(
                'slug' => $request->slug,
                'updated_at' => $this->carbon::now(),
                'updated_by' => Auth::user()->id
            );
            $this->componentMenuGroupRepository->updateSlug($slugID, $details_slug);
        } else {
            $details_slug = array(
                'slug' => $request->slug,
                'group_id' => $id,
                'created_at' => $this->carbon::now(),
                'created_by' => Auth::user()->id
            );
            $this->componentMenuGroupRepository->createSlug($details_slug);
        }

        return response()->json([
            'data' => $this->componentMenuGroupRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The group menu has been successfully updated.',
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
            'data' => $this->componentMenuGroupRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The group menu has been successfully removed.',
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
            'data' => $this->componentMenuGroupRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The group menu has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function order(Request $request, $order, $id)
    {   
        $this->is_permitted($this->slugs, 'update'); 
        $group_menu = $this->componentMenuGroupRepository->find($id);
        $last_count = $this->componentMenuGroupRepository->count();

        if ($order == 'up') {
            if ($group_menu->order > 1) {
                $prevOrder = floatval($group_menu->order) - floatval(1);    
                $prev_menu = $this->componentMenuGroupRepository->findBy('order', $prevOrder);
                $details = array(
                    'order' => floatval($prev_menu->order) + floatval(1),
                    'updated_at' => $this->carbon::now(),
                    'updated_by' => Auth::user()->id,
                    'is_active' => 1
                );
                $this->componentMenuGroupRepository->update($prev_menu->id, $details);
                $details = array(
                    'order' => $prevOrder,
                    'updated_at' => $this->carbon::now(),
                    'updated_by' => Auth::user()->id,
                    'is_active' => 1
                );
                $this->componentMenuGroupRepository->update($id, $details);
            }
        } else {
            if ($group_menu->order < $last_count) {
                $prevOrder = floatval($group_menu->order) + floatval(1);    
                $prev_menu = $this->componentMenuGroupRepository->findBy('order', $prevOrder);
                $details = array(
                    'order' => floatval($prev_menu->order) - floatval(1),
                    'updated_at' => $this->carbon::now(),
                    'updated_by' => Auth::user()->id,
                    'is_active' => 1
                );
                $this->componentMenuGroupRepository->update($prev_menu->id, $details);
                $details = array(
                    'order' => $prevOrder,
                    'updated_at' => $this->carbon::now(),
                    'updated_by' => Auth::user()->id,
                    'is_active' => 1
                );
                $this->componentMenuGroupRepository->update($id, $details);
            }
        }

        return response()->json([
            'title' => 'Well done!',
            'text' => 'The group menu has re-ordered successfully.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function update_order(Request $request)
    {
        $this->is_permitted($this->slugs, 'update'); 
        return response()->json([
            'request' => $request,
            'data' => $this->componentMenuGroupRepository->update_order($request),
            'title' => 'Well done!',
            'text' => 'The group menu has been re-ordered successfully.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }
}
