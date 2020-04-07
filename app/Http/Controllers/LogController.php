<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Role;
use App\Invoice;
use Validator;
use App\InvoiceItems;
use App\Product;
use App\Service;
use App\InvoicedUsers;
use DateTime;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $business = $user->business;
        $outgoingInvoices = $business->outgoingInvoices()->with('invoiceItems')->where('status','draft')->orderBy('created_at', 'desc')->paginate(16);
        return response()->json(
            $outgoingInvoices,
            200
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        $user = Auth::user();
        $business = $user->business;

        // $validator = Validator::make($request->all(), [
        //     'invoice_number' => 'required|numeric|integer',
        //     'invoice_date' => 'required|date',
        //     'due_date' => 'required|date|after:invoice_date',
        //     'currency' => 'in:eur,gbp,usd',
        //     'note'  => 'nullable|string|max:1000',
        // ]);
        // if ($validator->fails()) {
        //     return response()->json(['error' => 'Unauthorised - Validation failed', 'messages' => $validator->errors()], 422);
        // }

        //Create an Invoice
        $invoice = new Invoice;
        $latestInvoice = $business->outgoingInvoices()->orderBy('invoice_number', 'desc')->first();
        if (isset($latestInvoice)) {
            $invoice->invoice_number= $latestInvoice->invoice_number + 1;
        } else {
            $invoice->invoice_number=0;
        }
        $invoice->invoice_date = date('Y-m-d');
        $invoice->due_date = date('Y-m-d', strtotime("+1 day"));
        $invoice->currency = 'eur';
        $invoice->status = 'draft';
        $invoice->draft_email = $request->user_email;
        $invoice->user_id = null;
        $invoice->business_id = $business->id;

        //Calculate total cost + adjust for stripe
        $invoice->total_cost = 0;
        foreach ($request->invoiceLines as $line) {
            $invoice->total_cost += $line['cost'] * ceil($line['sec'] / 60) * 100;
        }
        $invoice->save();

        //Attach each product as invoice item
        foreach ($request->invoiceLines as $line) {
            $invoiceItem = new InvoiceItems([
                'name' => $line['name'],
                'description' => $line['description'],
                'cost' => $line['cost'] * 100,
                'quantity' => ceil($line['sec'] / 60),
                'sub_total' => $line['cost'] * ceil($line['sec'] / 60) * 100
            ]);
            if (isset($line['rate_unit'])) {
                $invoiceItem->rate_unit = $line['rate_unit'];
            }
            $invoice->invoiceItems()->save($invoiceItem);
        }
        dd($invoice);
        return response()->json(200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
