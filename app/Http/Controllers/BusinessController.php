<?php

namespace App\Http\Controllers;

use Validator;
use Auth;
use App\Business;
use App\Role;
use Illuminate\Http\Request;
use PharIo\Manifest\Email;
use Stripe;


class BusinessController extends Controller
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
        $business->cpanel;

        return response()->json(
            [
                'business' => $business,
            ],
            200
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_name' => 'required|string',
            'website' => 'required|string',
            'address' => 'required|string',
            'country' => 'required|string',
            'postcode' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Unauthorised - Validation failed', 'messages' => $validator->errors()], 401);
        }

        $role_business = Role::where('name', 'business')->first();

        $user = Auth::user();
        //Create a business
        $business = new Business;
        $business->business_name = $request->business_name;
        $business->website = $request->website;
        $business->address = $request->address;
        $business->country = $request->country;
        $business->postcode = $request->postcode;
        $business->user_id = $user->id;
        Stripe\Stripe::setApiKey('sk_test_6GCDXqiEEOn52aO7XcYEX7Bk00lJswlGE1');
        $account = Stripe\Account::create([
            'country' => 'IE',
            'type' => 'custom',
            'requested_capabilities' => ['card_payments', 'transfers'],
            'email'=>$user->email
        ]);
        // $externalAccount=Stripe\Account::createExternalAccount(
        //     $account->id,
        //     [
        //         'external_account' => 'btok_1GJ0Z9JZbG28fquLJi909xkr',
        //     ]
        // );
        
        $business->stripe_id=$account->id;
        $business->save();
        $user->roles()->attach($role_business);

        
        error_log($account);


        //Redirect to a specified route with flash message.
        return response()->json(200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function show(Business $business)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function edit(Business $business)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Business $business)
    {
        $validator = Validator::make($request->all(), [
            'business_name' => 'required|string',
            'website' => 'required|string',
            'address' => 'required|string',
            'country' => 'required|string',
            'postcode' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Unauthorised - Validation failed', 'messages' => $validator->errors()], 401);
        }
        //Update a business
        $business = Business::findOrFail($request->id);
        $business->business_name = $request->business_name;
        $business->website = $request->website;
        $business->address = $request->address;
        $business->country = $request->country;
        $business->postcode = $request->postcode;
        $business->save();
        //Redirect to a specified route with flash message.
        return response(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function destroy(Business $business)
    {
        //
    }
}
