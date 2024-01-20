<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\Request;

class GoalController extends Controller
{

    public function index()
    {
       
        $golas = Goal::where('created_by', '=', \Auth::user()->creatorId())->get();

        return view('goal.index', compact('golas'));
        
    }

    public function create()
    {
        
            $types = Goal::$goalType;

            return view('goal.create', compact('types'));
        
    }


    public function store(Request $request)
    {
        
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                                   'type' => 'required',
                                   'from' => 'required',
                                   'to' => 'required',
                                   'amount' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $goal             = new Goal();
            $goal->name       = $request->name;
            $goal->type       = $request->type;
            $goal->from       = $request->from;
            $goal->to         = $request->to;
            $goal->amount     = $request->amount;
            $goal->is_display = isset($request->is_display) ? 1 : 0;
            $goal->created_by = \Auth::user()->creatorId();
            $goal->save();

            return redirect()->route('goal.index')->with('success', __('Goal successfully created.'));
        
    }


    public function show(Goal $goal)
    {
        //
    }


    public function edit(Goal $goal)
    {
        
            $types = Goal::$goalType;

            return view('goal.edit', compact('types', 'goal'));
        
    }


    public function update(Request $request, Goal $goal)
    {
           if($goal->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'name' => 'required',
                                       'type' => 'required',
                                       'from' => 'required',
                                       'to' => 'required',
                                       'amount' => 'required',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $goal->name       = $request->name;
                $goal->type       = $request->type;
                $goal->from       = $request->from;
                $goal->to         = $request->to;
                $goal->amount     = $request->amount;
                $goal->is_display = isset($request->is_display) ? 1 : 0;
                $goal->save();

                return redirect()->route('goal.index')->with('success', __('Goal successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        
    }


    public function destroy(Goal $goal)
    {
        
            if($goal->created_by == \Auth::user()->creatorId())
            {
                $goal->delete();

                return redirect()->route('goal.index')->with('success', __('Goal successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        
    }
}
