<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use Auth;
use App\User;

class SearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function searchProducts(Request $request)
    {

        // $user = Auth::user();
        // // return response()->json($request,400);
        // $products_test = $user->products();
        // return response()->json(['test'=> $products_test,],200);
        $error = ['error' => 'No results found'];
        if (!$request['keywords']) {
            return response()->json($error, 421);
        }

        //$user = Auth::user()->id;
        // $products = Auth::user()->products()->where('name', $request->keywords)->get();
        $products = Auth::user()->products()->where('name','LIKE','%'.$request['keywords']."%")->get();
        
        //$products = Product::search($request['keywords'])->get();
        if ($products->count()) {
            return response()->json(
                [
                    'products' => $products,
                ],
                200
            );
        }
        return response()->json($error, 420);
    }
}
