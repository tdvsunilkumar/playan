<?php

namespace App\Http\Controllers;

use App\Models\DucumentUpload;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class DucumentUploadController extends Controller
{

    public function index()
    {
        if(\Auth::user()->type == 'company')
        {
            $documents = DucumentUpload::where('created_by', \Auth::user()->creatorId())->get();
        }
        else
        {
            $userRole  = \Auth::user()->roles->first();
            $documents = DucumentUpload::whereIn(
                'role', [
                          $userRole->id,
                          0,
                      ]
            )->where('created_by', \Auth::user()->creatorId())->get();
        }

        return view('documentUpload.index', compact('documents'));
    }


    public function create()
    {
        
        $roles = Role::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $roles->prepend('All', '0');
        return view('documentUpload.create', compact('roles'));
        
    }


    public function store(Request $request)
    {

        
        $validator = \Validator::make(
            $request->all(), [
                               'name' => 'required',
                               'document' => 'mimes:jpeg,png,jpg,svg,pdf,doc,zip|max:20480',
                           ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        if(!empty($request->document))
        {

            $filenameWithExt = $request->file('document')->getClientOriginalName();
            $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension       = $request->file('document')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $dir             = storage_path('uploads/documentUpload/');

            if(!file_exists($dir))
            {
                mkdir($dir, 0777, true);
            }
            $path = $request->file('document')->storeAs('uploads/documentUpload/', $fileNameToStore);
        }

        $document              = new DucumentUpload();
        $document->name        = $request->name;
        $document->document    = !empty($request->document) ? $fileNameToStore : '';
        $document->role        = $request->role;
        $document->description = $request->description;
        $document->created_by  = \Auth::user()->creatorId();
        $document->save();

        return redirect()->route('document-upload.index')->with('success', __('Document successfully uploaded.'));
        
    }


    public function show(DucumentUpload $ducumentUpload)
    {
        //
    }


    public function edit($id)
    {
        $roles = Role::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $roles->prepend('All', '0');

        $ducumentUpload = DucumentUpload::find($id);

        return view('documentUpload.edit', compact('roles', 'ducumentUpload'));
        
    }

    public function update(Request $request, $id)
    {
        
        $validator = \Validator::make(
            $request->all(), [
                               'name' => 'required',
                               'document' => 'mimes:jpeg,png,jpg,svg,pdf,doc,zip|max:20480',
                           ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $document = DucumentUpload::find($id);

        if(!empty($request->document))
        {

            $filenameWithExt = $request->file('document')->getClientOriginalName();
            $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension       = $request->file('document')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $dir             = storage_path('uploads/documentUpload/');

            if(!file_exists($dir))
            {
                mkdir($dir, 0777, true);
            }
            $path = $request->file('document')->storeAs('uploads/documentUpload/', $fileNameToStore);

            if(!empty($document->document))
            {
                unlink($dir . $document->document);
            }

        }


        $document->name = $request->name;
        if(!empty($request->document))
        {
            $document->document = !empty($request->document) ? $fileNameToStore : '';
        }

        $document->role        = $request->role;
        $document->description = $request->description;
        $document->save();

        return redirect()->route('document-upload.index')->with('success', __('Document successfully uploaded.'));
        
    }


    public function destroy($id)
    {
        
        $document = DucumentUpload::find($id);
        if($document->created_by == \Auth::user()->creatorId())
        {
            $document->delete();

            $dir = storage_path('uploads/documentUpload/');

            if(!empty($document->document))
            {
                unlink($dir . $document->document);
            }

            return redirect()->route('document-upload.index')->with('success', __('Document successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
       
    }
}
