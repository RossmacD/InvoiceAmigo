<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use Auth;

class AutoCompleteController extends Controller {

    public function index(){
        return view('autocomplete.index');
   }
    public function autoComplete(Request $request) {
        $query = $request->get('term','');
        $user_id = Auth::id();

        $products=Product::where('product_name','LIKE','%'.$query.'%')
                    ->where('user_id',$user_id)
                    ->get();

        $data=array();
        foreach ($products as $product) {
                $data[]=array('value'=>$product->product_name,'id'=>$product->id);
        }
        if(count($data))
             return $data;
        else
            return null;
    }

}
