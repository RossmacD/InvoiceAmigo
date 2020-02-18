<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use Auth;
use App\User;

class SearchController extends Controller
{
    public function searchProducts(Request $request)
    {
        //$products = Produ::where('name', $request->keywords)->get();
        $products=Auth::user()->products()->where('name', $request->keywords)->get();
        return response()->json($products);
    }
}
