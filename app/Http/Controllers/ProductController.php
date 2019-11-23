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

        return view('products.index', [
            'products'=>$products,
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create');
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
    return redirect()
        ->route('products.index')
        ->with('status','Created a new product!');
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
        return view('products.show',[
            'product'=>$product
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
        $product = Product::findOrFail($id);
        return view('products.edit',[
            'product'=>$product
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
        //Redirect to a specified route with flash message.
        return redirect()
            ->route('products.show',$id)
            ->with('status','Updated the product!');
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
        return redirect()
            ->route('products.index')
            ->with('status','Deleted the selected product');
    }
}
