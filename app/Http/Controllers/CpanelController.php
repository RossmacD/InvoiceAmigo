<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use App\Cpanel;
use Illuminate\Http\Request;
use PreviewTechs\cPanelWHM\WHM\Accounts;
use PreviewTechs\cPanelWHM\WHMClient;

class CpanelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'whm_username' => 'required|string',
            'api_token' => 'required|string',
            'hostname' => 'required|string',
            'port' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Unauthorised - Validation failed', 'messages' => $validator->errors()], 401);
        }

        $business = Auth::user()->business;
        //Create a cPanel
        $cpanel = new Cpanel;

        $cpanel->whm_username = $request->whm_username;
        $cpanel->api_token = $request->api_token;
        $cpanel->hostname = $request->hostname;
        $cpanel->port = $request->port;
        $cpanel->business_id = $business->id;
        $cpanel->save();

        //Redirect to a specified route with flash message.
        return response()->json(200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cpanel  $cpanel
     * @return \Illuminate\Http\Response
     */
    public function show(Cpanel $cpanel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cpanel  $cpanel
     * @return \Illuminate\Http\Response
     */
    public function edit(Cpanel $cpanel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cpanel  $cpanel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cpanel $cpanel)
    {
        $validator = Validator::make($request->all(), [
            'whm_username' => 'required|string',
            'api_token' => 'required|string',
            'hostname' => 'required|string',
            'port' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Unauthorised - Validation failed', 'messages' => $validator->errors()], 401);
        }

        $business = Auth::user()->business;

        //Update cPanel details
        $cpanel = Cpanel::where('business_id', $business->id)->firstOrFail();
        $cpanel->whm_username = $request->whm_username;
        $cpanel->api_token = $request->api_token;
        $cpanel->hostname = $request->hostname;
        $cpanel->port = $request->port;
        $cpanel->save();
        //Redirect to a specified route with flash message.
        return response()->json(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cpanel  $cpanel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cpanel $cpanel)
    {
        //
    }

    public function createAccount(Request $request){
        $user = Auth::user();
        $business = $user->business;
        $cpanel = $business->cpanel;

        $whmClient = new WHMClient($cpanel->whm_username, $cpanel->api_token, $cpanel->hostname, 2087);
        $accounts = new Accounts($whmClient);

        $account->name = $request->cpanelUsername;
        $account->domain = $request->domain;
        $account->plan_name = $request->planName;
        $accounts->create($account);
    }

    public function searchAccounts(Request $request){
        $user = Auth::user();
        $business = $user->business;
        $cpanel = $business->cpanel;

        $whmClient = new WHMClient($cpanel->whm_username, $cpanel->api_token, $cpanel->hostname, 2087);
        $accounts = new Accounts($whmClient);

        
        // $options=['searchmethod' => "exact","page" => 1,"limit" => 20,"want" => "username"];
        // $options=[];
        // $options->searchmethod="exact";
        // $options->page=1;
        // $options->limit=15;
        // $options->want="username";



        $jsonResp = [
            'accounts' => $accounts->searchAccounts()
        ];
        return response()->json($jsonResp,
            200
        );
    }
}
