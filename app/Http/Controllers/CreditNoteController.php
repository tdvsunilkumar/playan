<?php

namespace App\Http\Controllers;

use App\Models\CreditNote;
use App\Models\Invoice;
use App\Models\Utility;
use Illuminate\Http\Request;

class CreditNoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        
        $invoices = Invoice::where('created_by', \Auth::user()->creatorId())->get();

        return view('creditNote.index', compact('invoices'));
        
    }

    public function create($invoice_id)
    {
        

        $invoiceDue = Invoice::where('id', $invoice_id)->first();

        return view('creditNote.create', compact('invoiceDue', 'invoice_id'));
        
    }

    public function store(Request $request, $invoice_id)
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
        $invoiceDue = Invoice::where('id', $invoice_id)->first();
        if($request->amount > $invoiceDue->getDue())
        {
            return redirect()->back()->with('error', 'Maximum ' . \Auth::user()->priceFormat($invoiceDue->getDue()) . ' credit limit of this invoice.');
        }
        $invoice = Invoice::where('id', $invoice_id)->first();

        $credit              = new CreditNote();
        $credit->invoice     = $invoice_id;
        $credit->customer    = $invoice->customer_id;
        $credit->date        = $request->date;
        $credit->amount      = $request->amount;
        $credit->description = $request->description;
        $credit->save();

        Utility::userBalance('customer', $invoice->customer_id, $request->amount, 'debit');

        return redirect()->back()->with('success', __('Credit Note successfully created.'));
        
    }


    public function edit($invoice_id, $creditNote_id)
    {
        
        $creditNote = CreditNote::find($creditNote_id);
        return view('creditNote.edit', compact('creditNote'));
       
    }


    public function update(Request $request, $invoice_id, $creditNote_id)
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
        $invoiceDue = Invoice::where('id', $invoice_id)->first();

        if($request->amount > $invoiceDue->getDue())
        {
            return redirect()->back()->with('error', 'Maximum ' . \Auth::user()->priceFormat($invoiceDue->getDue()) . ' credit limit of this invoice.');
        }

        $credit = CreditNote::find($creditNote_id);
        Utility::userBalance('customer', $invoiceDue->customer_id, $credit->amount, 'credit');
        $credit->date        = $request->date;
        $credit->amount      = $request->amount;
        $credit->description = $request->description;
        $credit->save();

        Utility::userBalance('customer', $invoiceDue->customer_id, $request->amount, 'debit');

        return redirect()->back()->with('success', __('Credit Note successfully updated.'));
    }


    public function destroy($invoice_id, $creditNote_id)
    {
        
        $creditNote = CreditNote::find($creditNote_id);
        $creditNote->delete();

        Utility::userBalance('customer', $creditNote->customer, $creditNote->amount, 'credit');

        return redirect()->back()->with('success', __('Credit Note successfully deleted.'));

        
    }

    public function customCreate()
    {
        

        $invoices = Invoice::where('created_by', \Auth::user()->creatorId())->get()->pluck('invoice_id', 'id');

        return view('creditNote.custom_create', compact('invoices'));
        
    }

    public function customStore(Request $request)
    {
        
        $validator = \Validator::make(
            $request->all(), [
                               'invoice' => 'required|numeric',
                               'amount' => 'required|numeric',
                               'date' => 'required',
                           ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $invoice_id = $request->invoice;
        $invoiceDue = Invoice::where('id', $invoice_id)->first();

        if($request->amount > $invoiceDue->getDue())
        {
            return redirect()->back()->with('error', 'Maximum ' . \Auth::user()->priceFormat($invoiceDue->getDue()) . ' credit limit of this invoice.');
        }
        $invoice             = Invoice::where('id', $invoice_id)->first();
        $credit              = new CreditNote();
        $credit->invoice     = $invoice_id;
        $credit->customer    = $invoice->customer_id;
        $credit->date        = $request->date;
        $credit->amount      = $request->amount;
        $credit->description = $request->description;
        $credit->save();

        Utility::userBalance('customer', $invoice->customer_id, $request->amount, 'debit');

        return redirect()->back()->with('success', __('Credit Note successfully created.'));
        
    }

    public function getinvoice(Request $request)
    {
        $invoice = Invoice::where('id', $request->id)->first();

        echo json_encode($invoice->getDue());
    }

}
