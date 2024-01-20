<?php

namespace App\Http\Controllers;

use App\Models\PayslipType;
use Illuminate\Http\Request;

class PayslipTypeController extends Controller
{
    public function index()
    {
        
            $paysliptypes = PayslipType::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('paysliptype.index', compact('paysliptypes'));
        
    }

    public function create()
    {
        
            return view('paysliptype.create');
        
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
            $paysliptype             = new PayslipType();
            $paysliptype->name       = $request->name;
            $paysliptype->created_by = \Auth::user()->creatorId();
            $paysliptype->save();

            return redirect()->route('paysliptype.index')->with('success', __('PayslipType  successfully created.'));
        
    }

    public function show(PayslipType $paysliptype)
    {
        return redirect()->route('paysliptype.index');
    }

    public function edit(PayslipType $paysliptype)
    {
        
            if($paysliptype->created_by == \Auth::user()->creatorId())
            {

                return view('paysliptype.edit', compact('paysliptype'));
            }
            else
            {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
       
    }

    public function update(Request $request, PayslipType $paysliptype)
    {
        
            if($paysliptype->created_by == \Auth::user()->creatorId())
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

                $paysliptype->name = $request->name;
                $paysliptype->save();

                return redirect()->route('paysliptype.index')->with('success', __('PayslipType successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        
    }

    public function destroy(PayslipType $paysliptype)
    {
        
            if($paysliptype->created_by == \Auth::user()->creatorId())
            {
                $paysliptype->delete();

                return redirect()->route('paysliptype.index')->with('success', __('PayslipType successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        
    }


}
