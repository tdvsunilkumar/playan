<?php

namespace App\Http\Controllers;

use App\Models\LoanOption;
use Illuminate\Http\Request;

class LoanOptionController extends Controller
{
    public function index()
    {
        
            $loanoptions = LoanOption::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('loanoption.index', compact('loanoptions'));
        
    }

    public function create()
    {
        
            return view('loanoption.create');
        
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
            $loanoption             = new LoanOption();
            $loanoption->name       = $request->name;
            $loanoption->created_by = \Auth::user()->creatorId();
            $loanoption->save();

            return redirect()->route('loanoption.index')->with('success', __('LoanOption  successfully created.'));
        
    }

    public function show(LoanOption $loanoption)
    {
        return redirect()->route('loanoption.index');
    }

    public function edit(LoanOption $loanoption)
    {
        
            if($loanoption->created_by == \Auth::user()->creatorId())
            {

                return view('loanoption.edit', compact('loanoption'));
            }
            else
            {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        
    }

    public function update(Request $request, LoanOption $loanoption)
    {
        
            if($loanoption->created_by == \Auth::user()->creatorId())
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
                $loanoption->name = $request->name;
                $loanoption->save();

                return redirect()->route('loanoption.index')->with('success', __('LoanOption successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        
    }

    public function destroy(LoanOption $loanoption)
    {
        
            if($loanoption->created_by == \Auth::user()->creatorId())
            {
                $loanoption->delete();

                return redirect()->route('loanoption.index')->with('success', __('LoanOption successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        
    }
}
