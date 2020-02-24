<?php

namespace App\Http\Controllers;

use Validator;
use Auth;
use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
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
        $business = $user->business;
        $products = $business->products()->orderBy('created_at', 'desc')->paginate(10);

        return response()->json(
            [
                'products' => $products,
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
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Unauthorised - Validation failed', 'messages' => $validator->errors()], 401);
        }
        //Create a product
        $product = new Product;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->cost = $request->cost;
        $product->business_id = Auth::user()->business->id;
        $product->save();
        //Redirect to a specified route with flash message.
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
        $product = Product::findOrFail($id);
        return response()->json(
            [
                'product' => $product
            ],
            200
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function edit($id)
    // {
    //     $product = Product::findOrFail($id);
    //     return response()->json(
    //         [
    //             'product' => $product
    //         ],
    //         200
    //     );
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'cost' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Unauthorised - Validation failed', 'messages' => $validator->errors()], 422);
        }
        //Update a product
        $product =  Product::findOrFail($id);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->cost = $request->cost;
        $product->save(); // save it to the database.

        //Return success response with product id 
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
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(200);
    }
}
