<?php

namespace App\Http\Controllers;
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
        $user=Auth::user();
        $products=$user->products()->orderBy('created_at','desc')->paginate(10);

        return response()->json(
            [
                'products'=>$products,
            ]
            ,200
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     return response()->json(200);
    // }

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
        'product_name' => 'required|string',
        'product_description' => 'required|string',
        'product_quantity'=> 'nullable|numeric',
        'product_cost' => 'required|numeric',
    ];
    //custom validation error messages
    $messages = [
        //'invoice_number.unique' => 'Invoice title should be unique', //syntax: field_name.rule
    ];
    //First Validate the form data
    $request->validate($rules,$messages);
    //Create a Todo
    $product = new Product;
    $product->product_name = $request->product_name;
    $product->product_description = $request->product_description;
    $product->product_cost = $request->product_cost;
    $product->user_id=Auth::id();
    $product->save(); // save it to the database.
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
        return response()->json([
            'product'=>$product
        ]
        ,200
        );
        return response()->json([
            'product'=>$product
        ]
        ,200
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return response()->json([
            'product'=>$product
        ]
        ,200
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
        $rules = [
            'product_name' => 'required|string',
            'product_description' => 'required|string',
            'product_cost' => 'required|numeric',
        ];
        //custom validation error messages
        $messages = [
            //'invoice_number.unique' => 'Invoice title should be unique', //syntax: field_name.rule
        ];
        //First Validate the form data
        $request->validate($rules,$messages);
        //Create a Todo
        $product =  Product::findOrFail($id);
        $product->product_name = $request->product_name;
        $product->product_description = $request->product_description;
        $product->product_cost = $request->product_cost;
        $product->save(); // save it to the database.

        //Return success response with product id 
        return response()->json(
            [
                'id'=>$id
            ],200
        );
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
