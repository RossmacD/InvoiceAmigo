<?php

namespace App\Http\Controllers;

use Auth;
use App\Invoice;
use App\InvoiceItems;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;


class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $invoices = $user->invoices()->orderBy('created_at', 'desc')->paginate(10);
        foreach ($invoices as $invoice) {
            $user->id == $invoice->user_id ? $invoice->outgoing = true : $invoice->outgoing = false;
        }
        return view('invoices.index', [
            'invoices' => $invoices,
            'user' => $user,
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
            'product.*.name' => 'required|string',
            'product.*.description' => 'required|string',
            'product.*.quantity' => 'required|numeric',
            'product.*.cost' => 'required|numeric',
        ];
        //dd($request);
        //custom validation error messages
        $messages = [
            'product.*.name.required' => 'Name Required', //syntax: field_name.rule
            'product.*.description.required' => 'Description Required',
            'product.*.quantity.required' => 'Quantity Required',
            'product.*.cost.required' => 'Cost Required',
            'product.*.name.max:1000' => 'Must be less than 1000 characters', //syntax: field_name.rule


        ];
        //First Validate the form data
        $request->validate($rules, $messages);
        
        //Create a Todo
        $invoice = new Invoice;
        $invoice->invoice_number = $request->invoice_number;
        $invoice->invoice_date = $request->invoice_date;
        $invoice->due_date = $request->due_date;
        $invoice->currency = $request->currency;
        $invoice->note = $request->note;
        $invoice->user_id = Auth::id();
        $invoice->save(); // save it to the database.

        //Get the arrays from the form
        $itemAmount = $request->input('product');
        //Creates a Invoice Item for everything within the array
        foreach ($itemAmount as $invoiceItemPost) {
            $invoiceItem = new InvoiceItems;
            $invoiceItem->product_name = $invoiceItemPost['name'];
            $invoiceItem->product_description = $invoiceItemPost['description']  ;
            $invoiceItem->product_quantity = $invoiceItemPost['quantity'];
            $invoiceItem->product_cost = $invoiceItemPost['cost'];
            $invoiceItem->invoice_id = $invoice->id;
            $invoiceItem->save();
            if(false){
                $product=new Product;
                $product=$invoiceItem;
                $product->save();
            }
        }


        //Redirect to a specified route with flash message.
        return redirect()
            ->route('invoices.index')
            ->with('status', 'Created a new Invoice!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice = Invoice::findOrFail($id);
        return view('invoices.show', [
            'invoice' => $invoice
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoice = Invoice::findOrFail($id);
        return view('invoices.edit', [
            'invoice' => $invoice
        ]);
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
        $request->validate($rules, $messages);
        //Create a Todo
        $invoice =  Invoice::findOrFail($id);
        $invoice->invoice_number = $request->invoice_number;
        $invoice->invoice_date = $request->invoice_date;
        $invoice->due_date = $request->due_date;
        $invoice->currency = $request->currency;
        $invoice->note = $request->note;
        $invoice->save(); // save it to the database.
        //Redirect to a specified route with flash message.
        return redirect()
            ->route('invoices.show', $id)
            ->with('status', 'Updated the Invoice!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();
        return redirect()
            ->route('invoices.index')
            ->with('status', 'Deleted the selected Invoice');
    }
}
