<?php

namespace App\Http\Controllers;

use Validator;
use Auth;
use App\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
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
        $services = $user->services()->orderBy('created_at', 'desc')->paginate(10);
        return response()->json(
            [
                'services' => $services,
            ],
            200
        );
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
            'name' => 'required|string',
            'description' => 'required|string',
            'cost' => 'required|numeric',
            'rate_unit'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Unauthorised - Validation failed', 'messages' => $validator->errors()], 401);
        }
        //Create a Todo
        $service = new Service;
        $service->name = $request->name;
        $service->description = $request->description;
        $service->cost = $request->cost;
        $service->rate_unit = $request->rate_unit;
        $service->user_id = Auth::id();
        $service->save();
        //Redirect to a specified route with flash message.
        return response()->json(200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $service = Service::findOrFail($id);
        return response()->json(
            [
                'service' => $service
            ],
            200
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'cost' => 'required|numeric',
            'rate_unit' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Unauthorised - Validation failed', 'messages' => $validator->errors()], 401);
        }
        //Create a Todo
        $service = Service::findOrFail($id);
        $service->name = $request->name;
        $service->description = $request->description;
        $service->cost = $request->cost;
        $service->rate_unit = $request->rate_unit;
        $service->user_id = Auth::id();
        $service->save();
        //Redirect to a specified route with flash message.
        return response()->json(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer ID
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();
        return response()->json(200);
    }
}
