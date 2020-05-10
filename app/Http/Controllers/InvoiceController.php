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
use Illuminate\Support\Facades\DB;
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
        if ($user->hasRole('business')) {
            $outgoingInvoices = $business->outgoingInvoices()->orderBy('created_at', 'desc')->paginate(16);
        }
        $incomingInvoices = $user->incomingInvoices()->orderBy('created_at', 'desc')->paginate(16);

        // foreach($incomingInvoices as $invoice){
        //     $reciever = User::findOrFail($invoice->user_id);
        //     $invoice->user->email = $reciever->email;
        // }



        if (!$user->hasRole('business')) {
            $outgoingInvoices = null;
        } elseif ($outgoingInvoices != null) {
            foreach ($outgoingInvoices as $invoice) {
                // $reciever = User::findOrFail($invoice->user_id);
                //Return a user with the invoice
                $invoice->user;
                $invoice->total_cost=ceil($invoice->total_cost/100);
            }
        }

        foreach ($incomingInvoices as $invoice) {
            // $reciever = User::findOrFail($invoice->user_id);
            //Return a business with the invoice
            $invoice->business;
            $invoice->total_cost=ceil($invoice->total_cost/100);
        }
        $jsonResponse = [
            'outgoingInvoices' => $outgoingInvoices,
            'incomingInvoices' => $incomingInvoices,
        ];


        //temp - checked if the user id
        // foreach ($outgoingInvoices as $outgoingInvoice) {
        //     $user->id == $invoice->user_id ? $invoice->outgoing = true : $invoice->outgoing = false;
        // }
        return response()->json(
            $jsonResponse,
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
            return response()->json(['error' => 'Unauthorized - Validation failed', 'messages' => $validator->errors()], 422);
        }
        //Run the create operation into a transaction, if any one part of the operation fails all changes to the database will be rolled back
        DB::transaction(function () use ($request, $user, $business) {
            //Create an Invoice
            $invoice = new Invoice;
            $invoice->invoice_number = $request->invoice_number;
            $invoice->invoice_date = $request->invoice_date;
            $invoice->due_date = $request->due_date;
            $invoice->currency = $request->currency;
            $invoice->note = $request->note;

            if ($request->status == 'draft') {
                $invoice->draft_email = $request->user_email;
                $invoice->user_id = null;
            } else {
                $invoice->status = $request->status;
                $invoice->draft_email = null;
                $user = User::where('email', $request->user_email)->first();
                if (!isset($user)) {
                    $user = InvoiceController::createUser($request);
                }
                $invoice->user_id = $user->id;

                //Add this user's email to the invoiced users table if they haven't already been invoiced
                $invoiced_user = InvoicedUsers::where('user_email', $request->user_email)->first();
                if (!isset($invoiced_user)) {
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
                if (isset($line['save']) && $line['save']) {
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

            if ($invoice->status == "unseen") {
                event(new NotificationEvent('Invoice Received from ' . Auth::user()->name, $user->id));
                event(new NotificationEvent('Invoice Sent Sucessfully',  Auth::id()));
            }
        });
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
        if ($invoice->user_id == Auth::user()->id && $invoice->status == "unseen") {
            $invoice->status = "unpaid";
            $invoice->save();
        }
        $invoice->invoiceLines = InvoiceItems::where('invoice_id', $id)->get();
        foreach ($invoice->invoiceLines as $line) {
            $line->cost=ceil($line->cost/100);
            $line->sub_total=ceil($line->sub_total/100);
        }
        $invoice->total_cost=ceil($invoice->total_cost/100);
        if (isset($user)) {
            $invoice->user_email = $user->email;
        } else {
            $invoice->user_email = $invoice->draft_email;
        }
        $invoice->business->user;

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
        //If one part of the edit fails all  changes in the DB will be rolled back
        DB::transaction(function () use ($request, $business, $id) {
            //Update an invoice
            $invoice =  Invoice::findOrFail($id);
            $invoice->invoice_number = $request->invoice_number;
            $invoice->invoice_date = $request->invoice_date;
            $invoice->due_date = $request->due_date;
            $invoice->currency = $request->currency;
            $invoice->note = $request->note;
            if ($request->status == 'draft') {
                $invoice->draft_email = $request->user_email;
                $invoice->user_id = null;
            } else {
                $invoice->status = $request->status;

                $invoice->draft_email = null;
                $user = User::where('email', $request->user_email)->first();

                if (!isset($user)) {
                    $user = InvoiceController::createUser($request);
                }
                $invoice->user_id = $user->id;

                //Add this user's email to the invoiced users table
                // $invoiced_user = new InvoicedUsers();
                $invoiced_user = InvoicedUsers::where('user_email', '=', $request->user_email);
                if($invoiced_user === null) {
                    $invoiced_user->user_email = $request->user_email;
                    $invoiced_user->business_id = $business->id;
                    $invoiced_user->save();
                }
                
            }

            foreach ($request->invoiceLines as $line) {
                //check if we have added a new line
                if(isset($line['id'])){
                    $invoiceItem = InvoiceItems::find($line['id']);
                } else {
                    $invoiceItem = new InvoiceItems();
                }

                $invoiceItem->name = $line['name'];
                $invoiceItem->description = $line['description'];
                $invoiceItem->cost = $line['cost'] * 100;
                $invoiceItem->quantity = $line['quantity'];
                $invoiceItem->sub_total = $line['cost'] * $line['quantity'] * 100;

                if (isset($line['rate_unit'])) {
                    $invoiceItem->rate_unit = $line['rate_unit'];
                }
                $invoice->invoiceItems()->save($invoiceItem);
                if (isset($line['save']) && $line['save']) {
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

            //get all invoicelines for invoice
            $invoiceItems = $invoice->invoiceItems()->get()->all();

            // foreach($invoiceItems as $invoiceItem){
            //     foreach($request->invoiceLines as $line){
            //         while($invoiceItem->id !== $line['id']){
            //             error_log($invoiceItem->id);
            //             break;
            //         }
            //     }
            // }
            
            // foreach($invoiceItems as $invoiceItem){
            //  error_log($invoiceItem);
            // }
            // $merged = array_merge($invoiceItems, $request->invoiceLines);
            // $merged = array_map("unserialize", array_unique(array_map("serialize", $merged)));
            
            // if(count($merged) != 0){
            //     error_log(count($merged)." ".$merged[0]->id);
            // }

            // $found = false;
            // foreach($invoiceItems as $invoiceItem){
            //     foreach($request->invoiceLines as $line){
            //         if($invoiceItem->id == $line['id']){
            //             $found = true;
            //             // break;
            //         }
            //     }
            // }
            // if($found){
            //     error_log("Item removed");
            // }

            //Calculate total cost + adjust for stripe
            $invoice->total_cost = 0;
            foreach ($request->invoiceLines as $line) {
                $invoice->total_cost += ($line['cost'] * $line['quantity']) * 100;
            }
            $invoice->save(); // save it to the database.
        });
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

    public function generateNote($id){
        $invoice = Invoice::findOrFail($id);
        $note = new Invoice;
        $note->invoice_date=date('Y-m-d', time());
        $note->due_date=$note->invoice_date;
        $note->status='credit_note';
        $note->currency=$invoice->currency;
        $note->total_cost=$invoice->total_cost;
        $note->user_id=$invoice->user_id;
        $note->business_id=$invoice->business_id;
        $invoiceNum = Auth::user()->business->outgoingInvoices()->orderBy('invoice_number', 'desc')->first();
        if (isset($invoiceNum)) { 
            $note->invoice_number=$invoiceNum->invoice_number + 1;
        }else{
            $note->invoice_number=0;
        }
        $note->save();

        $invoiceItem = new InvoiceItems([
            'name' => 'Credit',
            'description' => 'Invoice #'.$id.' reversed',
            'cost' => $note->total_cost,
            'quantity' => 1,
            'sub_total' => $note->total_cost
        ]);
        $note->invoiceItems()->save($invoiceItem);
        return response()->json(200);
    }

    public static function createUser($request)
    {
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
