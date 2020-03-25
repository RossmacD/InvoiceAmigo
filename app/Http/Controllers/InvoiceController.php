<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Business;
use App\Role;
use App\Invoice;
use Validator;
use App\InvoiceItems;
use App\Product;
use App\Service;
use App\InvoicedUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Events\NotificationEvent;
use App\Http\Controllers\Auth\PasswordResetController;

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
        // event(new NotificationEvent('Invoice Page Opened',  Auth::id()));
        $user = Auth::user();
        $business = $user->business;
        if($user->hasRole('business')){
            $outgoingInvoices = $business->outgoingInvoices()->orderBy('created_at', 'desc')->paginate(16);
        }
        $incomingInvoices = $user->incomingInvoices()->orderBy('created_at', 'desc')->paginate(16);

        // foreach($incomingInvoices as $invoice){
        //     $reciever = User::findOrFail($invoice->user_id);
        //     $invoice->user->email = $reciever->email;
        // }
        foreach ($outgoingInvoices as $invoice) {
            // $reciever = User::findOrFail($invoice->user_id);
            //Return a user with the invoice
            $invoice->user;
        }


        if(!$user->hasRole('business')){
            $outgoingInvoices = null;
        }
        $jsonResponse=[
                    'outgoingInvoices' => $outgoingInvoices,
                    'incomingInvoices' => $incomingInvoices,
                  ];


        //temp - checked if the user id
        // foreach ($outgoingInvoices as $outgoingInvoice) {
        //     $user->id == $invoice->user_id ? $invoice->outgoing = true : $invoice->outgoing = false;
        // }
        return response()->json($jsonResponse,
            200
        );
    }

    /**
     * Show the get highest invoice number for creating a new Invoice.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $invoice = Auth::user()->business->outgoingInvoices()->orderBy('invoice_number', 'desc')->first();
        if (isset($invoice)) {
            return response()->json(['invoice_number' => $invoice->invoice_number + 1], 200);
        } else {
            return response()->json(['invoice_number' => 0], 200);
        }
    }

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

        //validation rules
        // $rules = [
        //     'invoice_number' => 'required|numeric|integer',
        //     'invoice_date' => 'required|date',
        //     'due_date' => 'required|date|after:invoice_date',
        //     'currency' => 'in:eur,gbp,usd',
        //     'note'  => 'nullable|string|max:1000',
        //     // 'product.*.name' => 'required|string',
        //     // 'product.*.description' => 'required|string',
        //     // 'product.*.quantity' => 'required|numeric',
        //     // 'product.*.cost' => 'required|numeric',
        // ];

        //custom validation error messages
        // $messages = [
        //     'product.*.name.required' => 'Name Required', //syntax: field_name.rule
        //     'product.*.description.required' => 'Description Required',
        //     'product.*.quantity.required' => 'Quantity Required',
        //     'product.*.cost.required' => 'Cost Required',
        //     'product.*.name.max:1000' => 'Must be less than 1000 characters',
        // ];

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
        //Validate the form data
        // $request->validate($rules, $messages);
        //Get the arrays from the form
        //TEMP $itemAmount = $request->input('product');

        //Create an Invoice
        $invoice = new Invoice;
        $invoice->invoice_number = $request->invoice_number;
        $invoice->invoice_date = $request->invoice_date;
        $invoice->due_date = $request->due_date;
        $invoice->currency = $request->currency;
        $invoice->note = $request->note;
        if($request->status == 'draft'){
            $invoice->draft_email = $request->user_email;
            $invoice->user_id = null;

        } else {
            $invoice->status = $request->status;

            $invoice->draft_email = null;
            $user = User::where('email', $request->user_email)->first();

            if(!isset($user)){
                $user = InvoiceController::createUser($request);
            } 
            $invoice->user_id = $user->id;

            //Add this user's email to the invoiced users table if they haven't already been invoiced
            $invoiced_user = InvoicedUsers::where('user_email', $request->user_email)->first();
            if(!isset($invoiced_user)){
                $invoiced_user = new InvoicedUsers();
                $invoiced_user->user_email = $request->user_email;
                $invoiced_user->business_id = $business->id;
                $invoiced_user->save();
            }
            
        }
        $invoice->business_id = $business->id;

        //Calculate total cost + adjust for stripe
        $invoice->total_cost = 0;
        foreach ($request->invoiceLines as $line) {
            $invoice->total_cost += ($line['cost'] * $line['quantity']) * 100;
        }

        //$client_id= User::where('email', $request->client_email)->firstOrFail();
        //TEMP  $client = User::where('email', strtolower($request->client_email))->firstOrFail();
        //TEMP   $client === null? $invoice->client_id=0 : $invoice->client_id=$client->id;

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
        event(new NotificationEvent('Invoice Received from '.Auth::user()->name, $user->id));
        event(new NotificationEvent('Invoice Sent Sucessfully',  Auth::id()));
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
        $invoice = Invoice::findOrFail($id);
        $user = User::find($invoice->user_id);
        if ($invoice->user_id == Auth::user()->id && $invoice->status=="unseen") {
            $invoice->status = "unpaid";
            $invoice->save();
        }
        $invoice->invoiceLines = InvoiceItems::where('invoice_id', $id)->get();
        
        if(isset($user)){
            $invoice->user_email = $user->email;
        } else {
            $invoice->user_email = $invoice->draft_email;
        }

        return response()->json(
            [
                'invoice' => $invoice,
            ],
            200
        );
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
        $business = Auth::user()->business;

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

        //Update an invoice
        $invoice =  Invoice::findOrFail($id);
        $invoice->invoice_number = $request->invoice_number;
        $invoice->invoice_date = $request->invoice_date;
        $invoice->due_date = $request->due_date;
        $invoice->currency = $request->currency;
        $invoice->note = $request->note;
        if($request->status == 'draft'){
            $invoice->draft_email = $request->user_email;
            $invoice->user_id = null;
        } else {
            $invoice->status = $request->status;

            $invoice->draft_email = null;
            $user = User::where('email', $request->user_email)->first();

            if(!isset($user)){
                $user = InvoiceController::createUser($request);
            } 
            $invoice->user_id = $user->id;

            //Add this user's email to the invoiced users table
            $invoiced_user = new InvoicedUsers();
            $invoiced_user->user_email = $request->user_email;
            $invoiced_user->business_id = $business->id;
            $invoiced_user->save();
        }
        $invoice->save(); // save it to the database.
        //Redirect to a specified route with flash message.
        return response(200);
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
        return response()->json(200);
    }

    public static function createUser($request){
        $role_user = Role::where('name', 'user')->first();

        $user = new User();
        $user->name = 'Unregistered';
        $user->email = $request->user_email;
        $user->password = bcrypt('secret');
        $user->save();
        $user->roles()->attach($role_user);
        $user_id = $user->id;

        //Send password reset email to the new user

        // $req = new Request();
        // $req->email = $request->user_email;
        //  return response()->json($request->user_email,420);
        // $request->put('email', $request->user_email);

        //  return response()->json($request,420);
        $passwordreset = new PasswordResetController();
        // PasswordResetController::create($request);
        $passwordreset->create($request);

        return $user;
    }
}
