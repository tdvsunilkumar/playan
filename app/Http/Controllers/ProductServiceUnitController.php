<?php

namespace App\Http\Controllers;

use App\Models\ProductService;
use App\Models\ProductServiceUnit;
use Illuminate\Http\Request;

class ProductServiceUnitController extends Controller
{
    public function index()
    {
        
            $units = ProductServiceUnit::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('productServiceUnit.index', compact('units'));
       
    }

    public function create()
    {
        
            return view('productServiceUnit.create');
        
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

            $category             = new ProductServiceUnit();
            $category->name       = $request->name;
            $category->created_by = \Auth::user()->creatorId();
            $category->save();

            return redirect()->route('product-unit.index')->with('success', __('Unit successfully created.'));
        
    }


    public function edit($id)
    {
        
            $unit = ProductServiceUnit::find($id);

            return view('productServiceUnit.edit', compact('unit'));
        
    }


    public function update(Request $request, $id)
    {
        
            $unit = ProductServiceUnit::find($id);
            if($unit->created_by == \Auth::user()->creatorId())
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

                $unit->name = $request->name;
                $unit->save();

                return redirect()->route('product-unit.index')->with('success', __('Unit successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        
    }

    public function destroy($id)
    {
        
            $unit = ProductServiceUnit::find($id);
            if($unit->created_by == \Auth::user()->creatorId())
            {
                $units = ProductService::where('unit_id', $unit->id)->first();
                if(!empty($units))
                {
                    return redirect()->back()->with('error', __('this unit is already assign so please move or remove this unit related data.'));
                }
                $unit->delete();

                return redirect()->route('product-unit.index')->with('success', __('Unit successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        
    }
}
