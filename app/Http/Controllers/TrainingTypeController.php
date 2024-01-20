<?php

namespace App\Http\Controllers;

use App\Models\TrainingType;
use Illuminate\Http\Request;

class TrainingTypeController extends Controller
{

    public function index()
    {
        
            $trainingtypes = TrainingType::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('trainingtype.index', compact('trainingtypes'));
        
    }


    public function create()
    {
        
            return view('trainingtype.create');
        
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

            $trainingtype             = new TrainingType();
            $trainingtype->name       = $request->name;
            $trainingtype->created_by = \Auth::user()->creatorId();
            $trainingtype->save();

            return redirect()->route('trainingtype.index')->with('success', __('TrainingType  successfully created.'));
        
    }


    public function show(TrainingType $trainingType)
    {
        //
    }


    public function edit($id)
    {

        
            $trainingType = TrainingType::find($id);
            if($trainingType->created_by == \Auth::user()->creatorId())
            {

                return view('trainingtype.edit', compact('trainingType'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        
    }


    public function update(Request $request, $id)
    {
        
            $trainingType = TrainingType::find($id);
            if($trainingType->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'name' => 'required',

                                   ]
                );

                $trainingType->name = $request->name;
                $trainingType->save();

                return redirect()->route('trainingtype.index')->with('success', __('TrainingType successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        
    }


    public function destroy($id)
    {
       
            $trainingType = TrainingType::find($id);
            if($trainingType->created_by == \Auth::user()->creatorId())
            {
                $trainingType->delete();

                return redirect()->route('trainingtype.index')->with('success', __('TrainingType successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
       
    }
}
