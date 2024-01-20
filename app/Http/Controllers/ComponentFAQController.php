<?php

namespace App\Http\Controllers;
use App\Models\FileUpload;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File; 
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\ComponentFAQInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ComponentFAQController extends Controller
{
    private ComponentFAQInterface $componentFAQRepository;
    private $carbon;
    private $slugs;

    public function __construct(ComponentFAQInterface $componentFAQRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->componentFAQRepository = $componentFAQRepository;
        $this->carbon = $carbon;
        $this->slugs = 'components/faqs';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $groups = $this->componentFAQRepository->allGroupMenus();
        return view('components.faqs.index')->with(compact('groups'));
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
        $result = $this->componentFAQRepository->listItems($request);
        $res = $result->data->map(function($faq) use ($statusClass, $actions, $canDelete) {
            $title = wordwrap($faq->title, 25, "<br />\n");                      
            if ($canDelete > 0) {
                $actions .= ($faq->is_active > 0) ? '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-trash text-white"></i></a>' : '<a href="javascript:;" class="action-btn restore-btn bg-info btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-reload text-white"></i></a>';
            }
            return [
                'id' => $faq->id,
                'group' => $faq->group ? $faq->group->name : '',
                'title' => '<div class="showLess" title="' . $faq->title . '">' . $title . '</div>',
                'modified' => ($faq->updated_at !== NULL) ? 
                '<strong>'.$faq->modified->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($faq->updated_at)) : 
                '<strong>'.$faq->inserted->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($faq->created_at)),
                'status' => $statusClass[$faq->is_active]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$faq->is_active]->bg. ' p-2">' . $statusClass[$faq->is_active]->status . '</span>' ,
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
    
    public function uploads(Request $request)
    {
        $this->is_permitted($this->slugs, 'upload');
        $timestamp = date('Y-m-d H:i:s');
        $uploaddir = 'FAQ';
        Storage::disk('uploads')->makeDirectory($uploaddir);

        foreach($_FILES as $file)
        {   
            Storage::put($uploaddir . '/' . $file['name'], (string) file_get_contents($file['tmp_name']));
        }

        $data = array(
            'message' => 'success'
        );

        echo json_encode( $data );

        exit();
    }

    public function store(Request $request): JsonResponse 
    {       
        $this->is_permitted($this->slugs, 'create');  
        $rows = $this->componentFAQRepository->validate($request->title);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot create a FAQ with an existing title.',
                'label' => 'This is an existing title.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => 'title',
            ]);
        }

        $faq = array(
            'group_id' => $request->group_id,
            'title' => $request->title,
            'icon' => $request->icon,
            'description' => $request->description,
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        );
        $faq = $this->componentFAQRepository->create($faq);

        $iteration = 0; $counter = 0;
        foreach ($_POST['header'] as $header) {   
            $res = $this->componentFAQRepository->find_detail_via_column($faq->id, 'header', $header);         
            if ($res->count() > 0) {
                $details = array(
                    'header' => $header,
                    'content' => $_POST['content'][$iteration],
                    'file' => $_POST['file'][$iteration],
                    'orders' => $counter,
                    'updated_at' => $this->carbon::now(),
                    'updated_by' => Auth::user()->id
                );
                $this->componentFAQRepository->update_details($res->first()->id, $details);
            } else {
                $details = array(
                    'faq_id' => $faq->id,
                    'header' => $header,
                    'content' => $_POST['content'][$iteration],
                    'file' => $_POST['file'][$iteration],
                    'orders' => $counter,
                    'created_at' => $this->carbon::now(),
                    'created_by' => Auth::user()->id
                );
                $details = $this->componentFAQRepository->create_details($details);
            }
            $iteration++; $counter++;
        }

        return response()->json(
            [
                'data' => $faq,
                'title' => 'Well done!',
                'text' => 'The faq has been successfully saved.',
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
            'data' => $this->componentFAQRepository->find($id),
            'details' => $this->componentFAQRepository->find_details($id)
        ]);
    }   

    public function update_order(Request $request)
    {
        $this->is_permitted($this->slugs, 'update'); 
        return response()->json([
            'request' => $request,
            'data' => $this->componentFAQRepository->update_order($request),
            'title' => 'Well done!',
            'text' => 'The faq has been re-ordered successfully.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function update(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update'); 
        $rows = $this->componentFAQRepository->validate($request->title, $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot update a FAQ with an existing title.',
                'label' => 'This is an existing title.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => 'title'
            ]);
        }

        $details = array(
            'group_id' => $request->group_id,
            'title' => $request->title,
            'icon' => $request->icon,
            'description' => $request->description,
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );
        $this->componentFAQRepository->update($id, $details);

        $this->componentFAQRepository->drop_details($id, ['is_active' => 0]);
        $iteration = 0; $counter = 1;
        foreach ($_POST['header'] as $header) {   
            $res = $this->componentFAQRepository->find_detail_via_column($id, 'id', $_POST['id'][$iteration]);         
            if ($res->count() > 0) {
                $details = array(
                    'header' => $header,
                    'content' => $_POST['content'][$iteration],
                    'file' => $_POST['file'][$iteration],
                    'orders' => $counter,
                    'updated_at' => $this->carbon::now(),
                    'updated_by' => Auth::user()->id,
                    'is_active' => 1
                );
                $this->componentFAQRepository->update_details($res->first()->id, $details);
            } else {
                $details = array(
                    'faq_id' => $id,
                    'header' => $header,
                    'content' => $_POST['content'][$iteration],
                    'file' => $_POST['file'][$iteration],
                    'orders' => $counter,
                    'created_at' => $this->carbon::now(),
                    'created_by' => Auth::user()->id
                );
                $details = $this->componentFAQRepository->create_details($details);
            }
            $iteration++; $counter++;
        }

        return response()->json([
            'title' => 'Well done!',
            'text' => 'The FAQ has been successfully updated.',
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
            'data' => $this->componentFAQRepository->update($id, $details),
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
            'data' => $this->componentFAQRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The group menu has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }
}
