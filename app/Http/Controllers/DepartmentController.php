<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::where('created_by', '=', \Auth::user()->creatorId())->get();
        return view('department.index', compact('departments'));
        
    }

    public function create()
    {
        
        $branch = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        return view('department.create', compact('branch'));
        
    }

    public function store(Request $request)
    {
        
        $validator = \Validator::make(
            $request->all(), [
                   'branch_id' => 'required',
                   'name' => 'required|max:20',
               ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $department             = new Department();
        $department->branch_id  = $request->branch_id;
        $department->name       = $request->name;
        $department->created_by = \Auth::user()->creatorId();
        $department->save();

        return redirect()->route('department.index')->with('success', __('Department  successfully created.'));
        
    }

    public function show(Department $department)
    {
        return redirect()->route('department.index');
    }

    public function edit(Department $department)
    {
        
        if($department->created_by == \Auth::user()->creatorId())
        {
            $branch = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            return view('department.edit', compact('department', 'branch'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
        
    }

    public function update(Request $request, Department $department)
    {
        
        if($department->created_by == \Auth::user()->creatorId())
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'branch_id' => 'required',
                                   'name' => 'required|max:20',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $department->branch_id = $request->branch_id;
            $department->name      = $request->name;
            $department->save();

            return redirect()->route('department.index')->with('success', __('Department successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
        
    }

    public function destroy(Department $department)
    {
        
        if($department->created_by == \Auth::user()->creatorId())
        {
            $department->delete();

            return redirect()->route('department.index')->with('success', __('Department successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
        
    }
}
