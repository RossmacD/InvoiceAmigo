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
        $products = Auth::user()->business->products()->where('name','LIKE','%'.$request['keywords']."%")->take(5)->get();
        $services = Auth::user()->business->services()->where('name','LIKE','%'.$request['keywords']."%")->take(5)->get();
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

    public function searchServicesOnly(Request $request)
    {
        $error = ['error' => 'Empty Query'];
        if (!$request['keywords']) {
            return response()->json($error, 200);
        }

        $services = Auth::user()->business->services()->where('name', 'LIKE', '%' . $request['keywords'] . "%")->take(5)->get();
        if (!empty($services)) {
            return response()->json(
                [
                    'invoiceLines' => $services
                ],
                200
            );
        }
        $error = ['error' => 'No results found'];
        return response()->json($error, 200);
    }

    public function searchInvoicedUsers(Request $request)
    {
        $error = ['error' => 'Empty Query'];
        if (!$request['keywords']) {
            return response()->json($error, 200);
        }

        // //GDPR BUG
        // $users = User::all();
        // $emals=[];
        // foreach($user as $users){
        //     array_push($emails,$user->email);
        // }
        // if (!empty($users)) {
        //     return response()->json(
        //         [
        //             'users' => $users
        //         ],
        //         200
        //     );
        // }
        // $error = ['error' => 'No results found'];
        // return response()->json($error, 200);

        // $invoices = Auth::user()->business->outgoingInvoices();
        // $sent = $invoices->where('');
        // $draft = null;

        $emails = Auth::user()->business->invoicedUsers()->where('user_email', 'LIKE', '%' . $request['keywords'] . "%")->take(5)->get();

        if (!empty($emails)) {
            return response()->json(
                [
                    'emails' => $emails
                ],
                200
            );
        }
        $error = ['error' => 'No results found'];
        return response()->json($error, 200);

    }
}
