<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\DebitNote;
use App\Models\Utility;
use Illuminate\Http\Request;

class DebitNoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        
        $bills = Bill::where('created_by', \Auth::user()->creatorId())->get();

        return view('debitNote.index', compact('bills'));
        
    }

    public function create($bill_id)
    {
        $billDue = Bill::where('id', $bill_id)->first();
        return view('debitNote.create', compact('billDue', 'bill_id'));
        
    }

    public function store(Request $request, $bill_id)
    {
        $validator = \Validator::make(
            $request->all(), [
                               'amount' => 'required|numeric',
                               'date' => 'required',
                           ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $billDue = Bill::where('id', $bill_id)->first();

        if($request->amount > $billDue->getDue())
        {
            return redirect()->back()->with('error', 'Maximum ' . \Auth::user()->priceFormat($billDue->getDue()) . ' credit limit of this bill.');
        }
        $bill               = Bill::where('id', $bill_id)->first();
        $debit              = new DebitNote();
        $debit->bill        = $bill_id;
        $debit->vendor      = $bill->vender_id;
        $debit->date        = $request->date;
        $debit->amount      = $request->amount;
        $debit->description = $request->description;
        $debit->save();

        Utility::userBalance('vendor', $bill->vender_id, $request->amount, 'debit');

        return redirect()->back()->with('success', __('Credit Note successfully created.'));
    }


    public function edit($bill_id, $debitNote_id)
    {
        
        $debitNote = DebitNote::find($debitNote_id);
        return view('debitNote.edit', compact('debitNote'));
        
    }


    public function update(Request $request, $bill_id, $debitNote_id)
    {

        $validator = \Validator::make(
            $request->all(), [
                               'amount' => 'required|numeric',
                               'date' => 'required',
                           ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $billDue = Bill::where('id', $bill_id)->first();
        if($request->amount > $billDue->getDue())
        {
            return redirect()->back()->with('error', 'Maximum ' . \Auth::user()->priceFormat($billDue->getDue()) . ' credit limit of this bill.');
        }


        $debit = DebitNote::find($debitNote_id);
        Utility::userBalance('vendor', $billDue->vender_id, $debit->amount, 'credit');

        $debit->date        = $request->date;
        $debit->amount      = $request->amount;
        $debit->description = $request->description;
        $debit->save();
        Utility::userBalance('vendor', $billDue->vender_id, $request->amount, 'debit');

        return redirect()->back()->with('success', __('Debit Note successfully updated.'));
        
    }


    public function destroy($bill_id, $debitNote_id)
    {
       
        $debitNote = DebitNote::find($debitNote_id);
        $debitNote->delete();
        Utility::userBalance('vendor', $debitNote->vendor, $debitNote->amount, 'credit');
        return redirect()->back()->with('success', __('Debit Note successfully deleted.'));
        
    }

    public function customCreate()
    {
        
        $bills = Bill::where('created_by', \Auth::user()->creatorId())->get()->pluck('bill_id', 'id');
        return view('debitNote.custom_create', compact('bills'));
        
    }

    public function customStore(Request $request)
    {
        
        $validator = \Validator::make(
            $request->all(), [
                               'bill' => 'required|numeric',
                               'amount' => 'required|numeric',
                               'date' => 'required',
                           ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $bill_id = $request->bill;
        $billDue = Bill::where('id', $bill_id)->first();

        if($request->amount > $billDue->getDue())
        {
            return redirect()->back()->with('error', 'Maximum ' . \Auth::user()->priceFormat($billDue->getDue()) . ' credit limit of this bill.');
        }
        $bill               = Bill::where('id', $bill_id)->first();
        $debit              = new DebitNote();
        $debit->bill        = $bill_id;
        $debit->vendor      = $bill->vender_id;
        $debit->date        = $request->date;
        $debit->amount      = $request->amount;
        $debit->description = $request->description;
        $debit->save();
        Utility::userBalance('vendor', $bill->vender_id, $request->amount, 'debit');

        return redirect()->back()->with('success', __('Debit Note successfully created.'));
        
    }

    public function getbill(Request $request)
    {

        $bill = Bill::where('id', $request->bill_id)->first();
        echo json_encode($bill->getDue());
    }
}
