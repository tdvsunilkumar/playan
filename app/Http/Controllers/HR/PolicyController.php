<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HR\Policy;

class PolicyController extends Controller
{
    public function __construct(){
        $this->data = array(
            'id'=>'',
            'hrsp_value'=>'',
        );  

        $this->slugs = 'hr/policy';
    }
    public function edit(Policy $policy) {
        $slugs = $policy->hrsp_slug;
        $data = $policy;
        // dd($this->slugs);
        $this->is_permitted($slugs, 'update');
        return view('HR.policy.create',compact('data'));
    }
    public function update(Request $request) {
        // dd($request->all());
        $data = Policy::find($request->id);
        $this->is_permitted($data->hrsp_slug, 'update');
		$data->hrsp_value = $request->hrsp_value;
		$data->hrsp_description = $request->hrsp_description;
        $data->update();
        return redirect($data->hrsp_slug)->with('success', __('Updated'));
    }

    public function formValidation(Request $request){
        $validator = \Validator::make(
        $request->all(), [
            'hrsp_description'=>'required',
            'hrsp_value'=>'required',
        ]
        ); 
        $arr=array('ESTATUS'=>0);
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['ESTATUS'] = 1;
        }
        echo json_encode($arr);exit;
    }

    public function leavededuction() {
        $slugs = $this->slugs.'/leave-deduction';
        $title = 'Leave Deduction';

        $this->is_permitted($slugs, 'read');
        $data = (object)[
            'title' => $title,
            'slugs' => $slugs,
            'rows' => Policy::where('hrsp_slug', $slugs)->get(),
        ];
        return view('HR.policy.index',compact('data'));
    }

    public function workdays() {
        $slugs = $this->slugs.'/work-days';
        $title = 'Work Days';

        $this->is_permitted($slugs, 'read');
        $data = (object)[
            'title' => $title,
            'slugs' => $slugs,
            'rows' => Policy::where('hrsp_slug', $slugs)->get(),
        ];
        return view('HR.policy.index',compact('data'));
    }
}
