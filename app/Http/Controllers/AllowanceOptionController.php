<?php

namespace App\Http\Controllers;

use App\Models\AllowanceOption;
use Illuminate\Http\Request;

class AllowanceOptionController extends Controller
{
    public function index()
    {
        $allowanceoptions = AllowanceOption::where('created_by', '=', \Auth::user()->creatorId())->get();
        return view('allowanceoption.index', compact('allowanceoptions'));
    }

    public function create()
    {
        return view('allowanceoption.create');
    }

    public function store(Request $request)
    {

        $validator = \Validator::make(
            $request->all(), [
               'name' => 'required|max:20',
           ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $allowanceoption             = new AllowanceOption();
        $allowanceoption->name       = $request->name;
        $allowanceoption->created_by = \Auth::user()->creatorId();
        $allowanceoption->save();

        return redirect()->route('allowanceoption.index')->with('success', __('AllowanceOption  successfully created.'));
    }

    public function show(AllowanceOption $allowanceoption)
    {
        return redirect()->route('allowanceoption.index');
    }

    public function edit(AllowanceOption $allowanceoption)
    {
        if($allowanceoption->created_by == \Auth::user()->creatorId())
        {

            return view('allowanceoption.edit', compact('allowanceoption'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, AllowanceOption $allowanceoption)
    {
        
        if($allowanceoption->created_by == \Auth::user()->creatorId())
        {
            $validator = \Validator::make(
                $request->all(), [
                 'name' => 'required|max:20',
                ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $allowanceoption->name = $request->name;
            $allowanceoption->save();

            return redirect()->route('allowanceoption.index')->with('success', __('AllowanceOption successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(AllowanceOption $allowanceoption)
    {
        
        if($allowanceoption->created_by == \Auth::user()->creatorId())
        {
            $allowanceoption->delete();

            return redirect()->route('allowanceoption.index')->with('success', __('AllowanceOption successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
        
    }

}