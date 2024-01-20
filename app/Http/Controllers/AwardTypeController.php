<?php

namespace App\Http\Controllers;

use App\Models\AwardType;
use Illuminate\Http\Request;

class AwardTypeController extends Controller
{
    public function index()
    {
        $awardtypes = AwardType::where('created_by', '=', \Auth::user()->creatorId())->get();
        return view('awardtype.index', compact('awardtypes'));
    }

    public function create()
    {
        return view('awardtype.create');
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

        $awardtype             = new AwardType();
        $awardtype->name       = $request->name;
        $awardtype->created_by = \Auth::user()->creatorId();
        $awardtype->save();

        return redirect()->route('awardtype.index')->with('success', __('AwardType  successfully created.'));
    }

    public function show(AwardType $awardtype)
    {
        return redirect()->route('awardtype.index');
    }

    public function edit(AwardType $awardtype)
    {
        if($awardtype->created_by == \Auth::user()->creatorId())
        {

            return view('awardtype.edit', compact('awardtype'));
        }else{
            return response()->json(['error' => __('Permission denied.')], 401);
        } 
    }

    public function update(Request $request, AwardType $awardtype)
    {
       if($awardtype->created_by == \Auth::user()->creatorId())
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

            $awardtype->name = $request->name;
            $awardtype->save();

            return redirect()->route('awardtype.index')->with('success', __('AwardType successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(AwardType $awardtype)
    {
        if($awardtype->created_by == \Auth::user()->creatorId())
        {
            $awardtype->delete();

            return redirect()->route('awardtype.index')->with('success', __('AwardType successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
