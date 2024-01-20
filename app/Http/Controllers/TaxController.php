<?php

namespace App\Http\Controllers;

use App\Models\BillProduct;
use App\Models\InvoiceProduct;
use App\Models\ProposalProduct;
use App\Models\Tax;
use Auth;
use Illuminate\Http\Request;

class TaxController extends Controller
{


    public function index()
    {
        
            $taxes = Tax::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('taxes.index')->with('taxes', $taxes);
        
    }


    public function create()
    {
        
            return view('taxes.create');
        
    }

    public function store(Request $request)
    {
        
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:20',
                                   'rate' => 'required|numeric',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $tax             = new Tax();
            $tax->name       = $request->name;
            $tax->rate       = $request->rate;
            $tax->created_by = \Auth::user()->creatorId();
            $tax->save();

            return redirect()->route('taxes.index')->with('success', __('Tax rate successfully created.'));
        
    }

    public function show(Tax $tax)
    {
        return redirect()->route('taxes.index');
    }


    public function edit(Tax $tax)
    {
        
            if($tax->created_by == \Auth::user()->creatorId())
            {
                return view('taxes.edit', compact('tax'));
            }
            else
            {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        
    }


    public function update(Request $request, Tax $tax)
    {
        
            if($tax->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'name' => 'required|max:20',
                                       'rate' => 'required|numeric',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $tax->name = $request->name;
                $tax->rate = $request->rate;
                $tax->save();

                return redirect()->route('taxes.index')->with('success', __('Tax rate successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        
    }

    public function destroy(Tax $tax)
    {
        
            if($tax->created_by == \Auth::user()->creatorId())
            {
                $proposalData = ProposalProduct::whereRaw("find_in_set('$tax->id',tax)")->first();
                $billData     = BillProduct::whereRaw("find_in_set('$tax->id',tax)")->first();
                $invoiceData  = InvoiceProduct::whereRaw("find_in_set('$tax->id',tax)")->first();

                if(!empty($proposalData) || !empty($billData) || !empty($invoiceData))
                {
                    return redirect()->back()->with('error', __('this tax is already assign to proposal or bill or invoice so please move or remove this tax related data.'));
                }

                $tax->delete();

                return redirect()->route('taxes.index')->with('success', __('Tax rate successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
       
    }
}
