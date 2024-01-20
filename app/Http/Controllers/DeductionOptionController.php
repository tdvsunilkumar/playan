<?php

namespace App\Http\Controllers;

use App\Models\DeductionOption;
use Illuminate\Http\Request;

class DeductionOptionController extends Controller
{
    public function index()
    {
        
            $deductionoptions = DeductionOption::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('deductionoption.index', compact('deductionoptions'));
        
    }

    public function create()
    {
        
            return view('deductionoption.create');
        
    }

    public function store(Request $request)
    {
        

            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $deductionoption             = new DeductionOption();
            $deductionoption->name       = $request->name;
            $deductionoption->created_by = \Auth::user()->creatorId();
            $deductionoption->save();

            return redirect()->route('deductionoption.index')->with('success', __('DeductionOption  successfully created.'));
        
    }

    public function show(DeductionOption $deductionoption)
    {
        return redirect()->route('deductionoption.index');
    }

    public function edit($deductionoption)
    {
        $deductionoption = DeductionOption::find($deductionoption);
        
            if($deductionoption->created_by == \Auth::user()->creatorId())
            {

                return view('deductionoption.edit', compact('deductionoption'));
            }
            else
            {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        
    }

    public function update(Request $request, DeductionOption $deductionoption)
    {
        
            if($deductionoption->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'name' => 'required',

                                   ]
                );

                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }
                $deductionoption->name = $request->name;
                $deductionoption->save();

                return redirect()->route('deductionoption.index')->with('success', __('DeductionOption successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        
    }

    public function destroy(DeductionOption $deductionoption)
    {
        
            if($deductionoption->created_by == \Auth::user()->creatorId())
            {
                $deductionoption->delete();

                return redirect()->route('deductionoption.index')->with('success', __('DeductionOption successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        
    }
}
