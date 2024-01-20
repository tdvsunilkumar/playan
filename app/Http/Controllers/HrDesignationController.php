<?php

namespace App\Http\Controllers;
use App\Models\HrDesignation;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\HrDesignationRepositoryInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class HrDesignationController extends Controller
{
    private HrDesignationRepositoryInterface $hrDesignationRepository;
    private $carbon;
    private $slugs;

    public function __construct(HrDesignationRepositoryInterface $hrDesignationRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->hrDesignationRepository = $hrDesignationRepository;
        $this->carbon = $carbon;
        $this->slugs = 'human-resource/employees';
    }

    public function validateFormRequest($requests)
    {   
        foreach ($requests as $request) {
            if(strpos($request, '<script>') !== false) {
                return abort(401);
            }
        }
        return true;
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        return view('human-resource.designations.index');
    }
    
    public function lists(Request $request) 
    { 
        $keywords     = $request->get('keywords');  
        $sortBy       = $request->get('sortBy');  
        $orderBy      = $request->get('orderBy'); 
        $cur_page     = null != $request->post('page') ? $request->post('page') : 1;
        $per_page     = $request->get('perPage') == -1 ? 0 : $request->get('perPage');
        $page         = $cur_page !== null ? $cur_page : 1;
        $start_from   = ($page-1) * $per_page;

        $previous_btn = true;
        $next_btn = true;
        $value = 0;
        $first_btn = true;
        $pagess = 0;
        $last_btn = true;
        
        $query = $this->getLineItems($start_from, $per_page, $keywords, $sortBy, $orderBy);
        $count = $this->getPageCount($keywords);
        $no_of_paginations = ceil($count / $per_page);
        $assets = url('assets/images/illustrations/work.png');

        $sorting = ($orderBy !== '') ? ($orderBy == 'asc') ? 'sorting_asc' : 'sorting_desc' : 'sorting';

        $msg  = '';
        $msg .= '<div class="table-responsive">';
        $msg .= '<table data-row-count="'.$count.'" class="table table-striped align-middle table-row-dashed fs-6 gy-3" id="designationTable">';
        $msg .= '<thead>';
        $msg .= '<tr class="text-start text-gray-400 fw-bolder fs-6 text-uppercase gs-0">';
        $msg .= ($sorting !== '' && $sortBy == 'id') ? '<th class="'. $sorting .'" data-row="id">ID</th>' : '<th class="sorting" data-row="id">ID</th>';
        $msg .= ($sorting !== '' && $sortBy == 'code') ? '<th class="'. $sorting .'" data-row="code">Code</th>' : '<th class="sorting" data-row="code">Code</th>';
        $msg .= ($sorting !== '' && $sortBy == 'description') ? '<th class="'. $sorting .'" data-row="description">Description</th>' : '<th class="sorting" data-row="description">Description</th>';
        $msg .= '<th class="text-center">Last Modified</th>';
        $msg .= '<th class="text-center">Status</th>';
        $msg .= '<th class="text-center">Actions</th>';
        $msg .= '</tr>';
        $msg .= '</thead>';
        $msg .= '<tbody class="text-gray-600">';

        if ($count <= 0) {
            $msg .= '<tr>';
            $msg .= '<td colspan="20" class="text-center">there are no data has been displayed.<br/><br/><br/>';
            $msg .= '<img class="mw-100" alt="" src="'.$assets.'">';
            $msg .= '</td>';
            $msg .= '<tr>';
        } else {
            foreach ($query as $row) {   
                $status = ($row->status == 1) ? '<span class="badge badge-status rounded-pill bg-info p-2">Active</span>' : '<span class="badge badge-status rounded-pill bg-secondary p-2">Inactive</span>';
                $icon = ($row->status == 1) ? '<i class="ti-trash text-white"></i>' : '<i class="ti-reload text-white"></i>';
                $msg .= '<tr data-row-id="' . $row->id . '" data-row-code="' . $row->code . '" data-row-status="' . $row->status . '">';
                $msg .= '<td>' . $row->id . '</td>';
                $msg .= '<td>' . $row->code . '</td>';
                $msg .= '<td>' . $row->description . '</td>';
                $msg .= '<td class="text-center">' . $row->modified . '</td>';
                $msg .= '<td class="text-center">' . $status . '</td>';
                $msg .= '<td class="action text-center">';
                $msg .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="Edit">
                            <i class="ti-pencil text-white"></i>
                        </a>';
                $msg .= ($row->status == 1) ? '<a href="javascript:;" class="action-btn delete-btn bg-danger btn m-1 btn-sm  align-items-center" title="Remove">' : '<a href="javascript:;" class="action-btn delete-btn bg-info btn m-1 btn-sm  align-items-center" title="Restore">';
                $msg .= $icon;
                $msg .= '</a>';
                $msg .= '</td>';
                $msg .= '</tr>';
            }
        }
        $msg .= '</tbody>';
        $msg .= '</table>';
        $msg .= '</div>';

        if ($cur_page >= 5) {
            $start_loop = $cur_page - 2;
            if ($no_of_paginations > $cur_page + 2)
                $end_loop = $cur_page + 2;
            else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
                $start_loop = $no_of_paginations - 4;
                $end_loop = $no_of_paginations;
            } else {
                $end_loop = $no_of_paginations;
            }
        } else {
            $start_loop = 1;
            if ($no_of_paginations > 5)
                $end_loop = 5;
            else
                $end_loop = $no_of_paginations;
        }

        $msg .= '<div class="row">';
        
        $pagination  = '<div class="dataTables_paginate paging_simple_numbers" id="kt_purchase_orders_table_paginate">';
        $pagination .= '<ul class="pagination mb-0">';
        // FOR ENABLING THE PREVIOUS BUTTON
        if ($previous_btn && $cur_page > 1) {
            $pre = $cur_page - 1;
            $pagination .= '<li class="paginate_button page-item" p="'.$pre.'">';
            $pagination .= '<a href="javascript:;" aria-label="Previous" class="page-link">';
            $pagination .= 'Prev';
            $pagination .= '</a>';
            $pagination .= '</li>';
        } else if ($previous_btn) {
            $pagination .= '<li class="paginate_button page-item disabled">';
            $pagination .= '<a href="javascript:;" aria-label="Previous" class="page-link">';
            $pagination .= 'Prev';
            $pagination .= '</a>';
            $pagination .= '</li>';
        }
        for ($i = $start_loop; $i <= $end_loop; $i++) {

            if ($cur_page == $i)
                $pagination .= '<li class="paginate_button page-item active" p="'.$i.'"><a href="javascript:;" class="page-link">'.$i.'</a></li>';
            else
                $pagination .= '<li class="paginate_button page-item ping" p="'.$i.'"><a href="javascript:;" class="page-link">'.$i.'</a></li>';
        }

        // TO ENABLE THE NEXT BUTTON
        if ($next_btn && $cur_page < $no_of_paginations) {
            $nex = $cur_page + 1;
            $pagination .= '<li class="paginate_button page-item" p="'.$nex.'">';
            $pagination .= '<a href="javascript:;" aria-label="Next" class="page-link">';
            $pagination .= 'Next';
            $pagination .= '</a>';
            $pagination .= '</li>';
        } else if ($next_btn) {
            $pagination .= '<li class="paginate_button page-item disabled">';
            $pagination .= '<a href="javascript:;" aria-label="Next" class="page-link">';
            $pagination .= 'Next';
            $pagination .= '</a>';
            $pagination .= '</li>';
        }
        $pagination .= '</ul>';
        $pagination .= '</div>';

        $show = ($per_page < $count) ? (($per_page * $cur_page) <= $count) ? ($per_page * $cur_page) : $count : $count;  
        $cur_page = ($cur_page <= 1) ?  ($count != 0) ? $cur_page : $count : (($cur_page - 1) * $per_page) + 1;
        $total_string = '<div class="infos text-right">Showing '. $cur_page .' to '.$show.' of '.$count.' entries</div>';
        
        $msg .= '<div class="col-sm-6 d-flex justify-content-start align-items-center">'.$total_string.'</div>';
        $msg .= '<div class="col-sm-6 d-flex justify-content-end">' . $pagination . '</div>';
        $msg .= '</div>';
        echo $msg;
    }
    
    public function getLineItems($startFrom, $perPage, $keywords, $sortBy, $orderBy) 
    {
        $res = $this->hrDesignationRepository->listItems($startFrom, $perPage, $keywords, $sortBy, $orderBy);
        return $res->map(function($designation) {
            return (object) [
                'id' => $designation->id,
                'code' => $designation->code,
                'description' => $designation->description,
                'modified' => ($designation->updated_at !== NULL) ? date('d-M-Y', strtotime($designation->updated_at)).'<br/>'. date('h:i A', strtotime($designation->updated_at)) : date('d-M-Y', strtotime($designation->created_at)).'<br/>'. date('h:i A', strtotime($designation->created_at)),
                'status' => $designation->is_active
            ];
        });
    }

    public function getPageCount($keywords) 
    {
        return $this->hrDesignationRepository->listCount($keywords);
    }
    
    public function store(Request $request): JsonResponse 
    {       
        $this->is_permitted($this->slugs, 'create');
        $this->validateFormRequest($request->all());

        $rows = $this->hrDesignationRepository->validate($request->code);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot create a designation with an existing code.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }

        $details = array(
            'code' => $request->code,
            'description' => $request->description,
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        );

        return response()->json(
            [
                'data' => $this->hrDesignationRepository->create($details),
                'title' => 'Well done!',
                'text' => 'The designation has been successfully saved.',
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
            'data' => $this->hrDesignationRepository->find($id)
        ]);
    }

    public function update(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update');
        $this->validateFormRequest($request->all());

        $rows = $this->hrDesignationRepository->validate($request->code, $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot update a designation with an existing code.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }

        $details = array(
            'code' => $request->code,
            'description' => $request->description,
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );

        return response()->json([
            'data' => $this->hrDesignationRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The designation has been successfully updated.',
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
            'data' => $this->hrDesignationRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The designation has been successfully removed.',
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
            'data' => $this->hrDesignationRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The designation has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }
}
