<?php

namespace App\Http\Controllers;

use App\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::orderBy('created_at','desc')->paginate(10);
        return view('invoices.index', [
            'invoices'=>$invoices,
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('invoices.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validation rules
    $rules = [
        'invoice_number' => 'required|numeric|integer',
        'invoice_date' => 'required|date',
        'due_date' => 'required|date|after:invoice_date',
        'currency' => 'in:eur,gbp,usd',
        'note'  => 'nullable|string|max:1000',
    ];
    //custom validation error messages
    $messages = [
        //'invoice_number.unique' => 'Invoice title should be unique', //syntax: field_name.rule
    ];
    //First Validate the form data
    $request->validate($rules,$messages);
    //Create a Todo
    $invoice = new Invoice;
    $invoice->invoice_number = $request->invoice_number;
    $invoice->invoice_date = $request->invoice_date;
    $invoice->due_date = $request->due_date;
    $invoice->currency = $request->currency;
    $invoice->note = $request->note;
    $invoice->save(); // save it to the database.
    //Redirect to a specified route with flash message.
    return redirect()
        ->route('invoices.index')
        ->with('status','Created a new Invoice!');
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
