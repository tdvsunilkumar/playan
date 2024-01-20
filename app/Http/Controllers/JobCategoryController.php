<?php

namespace App\Http\Controllers;

use App\Models\JobCategory;
use Illuminate\Http\Request;

class JobCategoryController extends Controller
{

    public function index()
    {
        
        $categories = JobCategory::where('created_by', '=', \Auth::user()->creatorId())->get();

        return view('jobCategory.index', compact('categories'));
        
    }


    public function create()
    {
        return view('jobCategory.create');
    }


    public function store(Request $request)
    {
        
        $validator = \Validator::make(
            $request->all(), [
                               'title' => 'required',
                           ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $jobCategory             = new JobCategory();
        $jobCategory->title      = $request->title;
        $jobCategory->created_by = \Auth::user()->creatorId();
        $jobCategory->save();

        return redirect()->back()->with('success', __('Job category  successfully created.'));
        
    }

    public function show(JobCategory $jobCategory)
    {
        //
    }


    public function edit(JobCategory $jobCategory)
    {
        return view('jobCategory.edit', compact('jobCategory'));
    }


    public function update(Request $request, JobCategory $jobCategory)
    {
       

        $validator = \Validator::make(
            $request->all(), [
                               'title' => 'required',
                           ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $jobCategory->title = $request->title;
        $jobCategory->save();

        return redirect()->back()->with('success', __('Job category  successfully updated.'));
        
    }


    public function destroy(JobCategory $jobCategory)
    {
        
        if($jobCategory->created_by == \Auth::user()->creatorId())
        {
            $jobCategory->delete();

            return redirect()->back()->with('success', __('Job category successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
        
    }
}
