<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {

        
            $documents = Document::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('document.index', compact('documents'));
        
    }

    public function create()
    {
        
            return view('document.create');
        
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

            $document              = new Document();
            $document->name        = $request->name;
            $document->is_required = $request->is_required;
            $document->created_by  = \Auth::user()->creatorId();
            $document->save();

            return redirect()->route('document.index')->with('success', __('Document type successfully created.'));
        
    }

    public function show(Document $document)
    {
        return redirect()->route('document.index');
    }

    public function edit(Document $document)
    {
        
            if($document->created_by == \Auth::user()->creatorId())
            {

                return view('document.edit', compact('document'));
            }
            else
            {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        
    }

    public function update(Request $request, Document $document)
    {

        
            if($document->created_by == \Auth::user()->creatorId())
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


                $document->name        = $request->name;
                $document->is_required = $request->is_required;
                $document->save();

                return redirect()->route('document.index')->with('success', __('Document type successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        
    }

    public function destroy(Document $document)
    {
       
            if($document->created_by == \Auth::user()->creatorId())
            {
                $document->delete();

                return redirect()->route('document.index')->with('success', __('Document type successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        
    }
}
