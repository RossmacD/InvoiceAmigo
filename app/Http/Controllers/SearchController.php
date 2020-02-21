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
            return response()->json($error, 200);
        }

        //$user = Auth::user()->id;
        // $products = Auth::user()->products()->where('name', $request->keywords)->get();
        //Get Matching products and services then combine
        $products = Auth::user()->products()->where('name','LIKE','%'.$request['keywords']."%")->take(5)->get();
        $services = Auth::user()->services()->where('name','LIKE','%'.$request['keywords']."%")->take(5)->get();
        // $invoiceLines = $services->merge($products);
        $invoiceLines=array_merge($products->toArray(), $services->toArray());
        //$products = Product::search($request['keywords'])->get();
        if (!empty($invoiceLines)) {
            return response()->json(
                [
                    'invoiceLines'=>$invoiceLines
                ],
                200
            );
        }
        return response()->json($error, 200);
    }
}
