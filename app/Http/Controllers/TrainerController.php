<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Trainer;
use Illuminate\Http\Request;

class TrainerController extends Controller
{

    public function index()
    {
        
            $trainers = Trainer::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('trainer.index', compact('trainers'));
        
    }


    public function create()
    {
        
            $branches = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            return view('trainer.create', compact('branches'));
        
    }


    public function store(Request $request)
    {
       

            $validator = \Validator::make(
                $request->all(), [
                                   'branch' => 'required',
                                   'firstname' => 'required',
                                   'lastname' => 'required',
                                   'contact' => 'required',
                                   'email' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $trainer             = new Trainer();
            $trainer->branch     = $request->branch;
            $trainer->firstname  = $request->firstname;
            $trainer->lastname   = $request->lastname;
            $trainer->contact    = $request->contact;
            $trainer->email      = $request->email;
            $trainer->address    = $request->address;
            $trainer->expertise  = $request->expertise;
            $trainer->created_by = \Auth::user()->creatorId();
            $trainer->save();

            return redirect()->route('trainer.index')->with('success', __('Trainer  successfully created.'));
        
    }


    public function show(Trainer $trainer)
    {
        return view('trainer.show', compact('trainer'));
    }


    public function edit(Trainer $trainer)
    {
       
            $branches = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            return view('trainer.edit', compact('branches', 'trainer'));
        
    }


    public function update(Request $request, Trainer $trainer)
    {
        

            $validator = \Validator::make(
                $request->all(), [
                                   'branch' => 'required',
                                   'firstname' => 'required',
                                   'lastname' => 'required',
                                   'contact' => 'required',
                                   'email' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $trainer->branch    = $request->branch;
            $trainer->firstname = $request->firstname;
            $trainer->lastname  = $request->lastname;
            $trainer->contact   = $request->contact;
            $trainer->email     = $request->email;
            $trainer->address   = $request->address;
            $trainer->expertise = $request->expertise;
            $trainer->save();

            return redirect()->route('trainer.index')->with('success', __('Trainer  successfully updated.'));
       
    }


    public function destroy(Trainer $trainer)
    {
      
            if($trainer->created_by == \Auth::user()->creatorId())
            {
                $trainer->delete();

                return redirect()->route('trainer.index')->with('success', __('Trainer successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        
    }
}
