<?php

namespace App\Http\Controllers;

use App\Imports\ProductServiceImport;
use App\Models\Bill;
use App\Models\Invoice;
use App\Models\ProductService;
use App\Models\ProductServiceCategory;
use App\ProductServiceUnit;
use App\Tax;
use App\Vender;
use Illuminate\Http\Request;

class ProductServiceCategoryController extends Controller
{
    public function index()
    {
        
            $categories = ProductServiceCategory::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('productServiceCategory.index', compact('categories'));
        
    }


    public function create()
    {
        
            $types = ProductServiceCategory::$categoryType;

            return view('productServiceCategory.create', compact('types'));
        
    }

    public function store(Request $request)
    {
       

            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:20',
                                   'type' => 'required',
                                   'color' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $category             = new ProductServiceCategory();
            $category->name       = $request->name;
            $category->color      = $request->color;
            $category->type       = $request->type;
            $category->created_by = \Auth::user()->creatorId();
            $category->save();

            return redirect()->route('product-category.index')->with('success', __('Category successfully created.'));
        
    }


    public function edit($id)
    {

        
            $types    = ProductServiceCategory::$categoryType;
            $category = ProductServiceCategory::find($id);

            return view('productServiceCategory.edit', compact('category', 'types'));
        
    }


    public function update(Request $request, $id)
    {
        
            $category = ProductServiceCategory::find($id);
            if($category->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'name' => 'required|max:20',
                                       'type' => 'required',
                                       'color' => 'required',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $category->name  = $request->name;
                $category->color = $request->color;
                $category->type  = $request->type;
                $category->save();

                return redirect()->route('product-category.index')->with('success', __('Category successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        
    }

    public function destroy($id)
    {
        
            $category = ProductServiceCategory::find($id);
            if($category->created_by == \Auth::user()->creatorId())
            {

                if($category->type == 0)
                {
                    $categories = ProductService::where('category_id', $category->id)->first();
                }
                elseif($category->type == 1)
                {
                    $categories = Invoice::where('category_id', $category->id)->first();
                }
                else
                {
                    $categories = Bill::where('category_id', $category->id)->first();
                }

                if(!empty($categories))
                {
                    return redirect()->back()->with('error', __('this category is already assign so please move or remove this category related data.'));
                }

                $category->delete();

                return redirect()->route('product-category.index')->with('success', __('Category successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
       
    }

}
