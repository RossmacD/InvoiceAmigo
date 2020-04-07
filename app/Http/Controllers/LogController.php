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
        

        //temp - checked if the user id
        // foreach ($outgoingInvoices as $outgoingInvoice) {
        //     $user->id == $invoice->user_id ? $invoice->outgoing = true : $invoice->outgoing = false;
        // }
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

        $validator = Validator::make($request->all(), [
            'invoice_number' => 'required|numeric|integer',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after:invoice_date',
            'currency' => 'in:eur,gbp,usd',
            'note'  => 'nullable|string|max:1000',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Unauthorised - Validation failed', 'messages' => $validator->errors()], 422);
        }

        //Create an Invoice
        $invoice = new Invoice;
        // $invoice->invoice_number = $request->invoice_number; TODO
        // $invoice->invoice_date = $request->invoice_date;
        // $invoice->due_date = $request->due_date; TODO
        // $invoice->currency = $request->currency;
        // $invoice->note = $request->note;
        $invoice->status = 'draft';
        $invoice->draft_email = $request->user_email;
        $invoice->user_id = null;
        $invoice->business_id = $business->id;

        //Calculate total cost + adjust for stripe
        $invoice->total_cost = 0;
        foreach ($request->invoiceLines as $line) {
            $invoice->total_cost += ($line['cost'] * $line['quantity']) * 100;
        }
        $invoice->save();

        //Attach each product as invoice item
        foreach ($request->invoiceLines as $line) {
            $invoiceItem = new InvoiceItems([
                'name' => $line['name'],
                'description' => $line['description'],
                'cost' => $line['cost'] * 100,
                'quantity' => $line['quantity'],
                'sub_total' => $line['cost'] * $line['quantity'] * 100
            ]);
            if (isset($line['rate_unit'])) {
                $invoiceItem->rate_unit = $line['rate_unit'];
            }
            $invoice->invoiceItems()->save($invoiceItem);
            if ($line['save']) {
                if ($line['type'] == 'product') {
                    $savedLine = new Product([
                        'name' => $line['name'],
                        'description' => $line['description'],
                        'cost' => $line['cost'] * 100,
                        'user_id' => Auth::id()
                    ]);
                } else {
                    $savedLine = new Service([
                        'name' => $line['name'],
                        'description' => $line['description'],
                        'cost' => $line['cost'] * 100,
                        'rate_unit' => $line['rate_unit'],
                        'user_id' => Auth::id()
                    ]);
                }

                $savedLine->save();
            }
        }
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
