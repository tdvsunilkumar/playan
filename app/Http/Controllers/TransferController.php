<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Mail\TransferSend;
use App\Models\Transfer;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TransferController extends Controller
{

    public function index()
    {
       
            if(Auth::user()->type == 'employee')
            {
                $emp       = Employee::where('user_id', '=', \Auth::user()->id)->first();
                $transfers = Transfer::where('created_by', '=', \Auth::user()->creatorId())->where('employee_id', '=', $emp->id)->get();
            }
            else
            {
                $transfers = Transfer::where('created_by', '=', \Auth::user()->creatorId())->get();
            }

            return view('transfer.index', compact('transfers'));
        
    }

    public function create()
    {
        
            $departments = Department::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $branches    = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $employees   = Employee::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            return view('transfer.create', compact('employees', 'departments', 'branches'));
        
    }

    public function store(Request $request)
    {

        
            $validator = \Validator::make(
                $request->all(), [
                                   'employee_id' => 'required',
                                   'branch_id' => 'required',
                                   'department_id' => 'required',
                                   'transfer_date' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $transfer                = new Transfer();
            $transfer->employee_id   = $request->employee_id;
            $transfer->branch_id     = $request->branch_id;
            $transfer->department_id = $request->department_id;
            $transfer->transfer_date = $request->transfer_date;
            $transfer->description   = $request->description;
            $transfer->created_by    = \Auth::user()->creatorId();
            $transfer->save();

            $setings = Utility::settings();
            if($setings['transfer_send'] == 1)
            {
                $employee             = Employee::find($transfer->employee_id);
                $branch               = Branch::find($transfer->branch_id);
                $department           = Department::find($transfer->department_id);
                $transfer->name       = $employee->name;
                $transfer->email      = $employee->email;
                $transfer->branch     = $branch->name;
                $transfer->department = $department->name;
//                dd($transfer);

                $transferArr = [
                    'transfer_name'=>$employee->name,
                    'transfer_email'=>$employee->email,
                    'transfer_date'=>$transfer->transfer_date,
                    'transfer_department'=>$transfer->department,
                    'transfer_branch'=>$transfer->branch,
                    'transfer_description'=>$transfer->description,
                ];

                $resp = Utility::sendEmailTemplate('transfer_send', [ $employee->email], $transferArr);


                return redirect()->route('transfer.index')->with('success', __('Transfer  successfully created.') .(($resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
            }
            return redirect()->route('transfer.index')->with('success', __('Transfer  successfully created.'));
       
    }

    public function show(Transfer $transfer)
    {
        return redirect()->route('transfer.index');
    }

    public function edit(Transfer $transfer)
    {
        
            $departments = Department::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $branches    = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $employees   = Employee::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            if($transfer->created_by == \Auth::user()->creatorId())
            {
                return view('transfer.edit', compact('transfer', 'employees', 'departments', 'branches'));
            }
            else
            {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        
    }

    public function update(Request $request, Transfer $transfer)
    {
        
            if($transfer->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'employee_id' => 'required',
                                       'branch_id' => 'required',
                                       'department_id' => 'required',
                                       'transfer_date' => 'required',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $transfer->employee_id   = $request->employee_id;
                $transfer->branch_id     = $request->branch_id;
                $transfer->department_id = $request->department_id;
                $transfer->transfer_date = $request->transfer_date;
                $transfer->description   = $request->description;
                $transfer->save();

                return redirect()->route('transfer.index')->with('success', __('Transfer successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        
    }

    public function destroy(Transfer $transfer)
    {
        
        
            if($transfer->created_by == \Auth::user()->creatorId())
            {
                $transfer->delete();

                return redirect()->route('transfer.index')->with('success', __('Transfer successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        
    }
}
