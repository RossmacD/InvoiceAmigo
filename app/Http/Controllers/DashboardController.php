<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Business;
use App\Invoice;
use Carbon\Carbon;
use App\Transactions;


class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $user = Auth::user();
        $business = $user->business;

        //Get total income from paid invoices
        $paidInvoices = $business->outgoingInvoices->where('status', 'paid');
        $totalIncome = 0;
        foreach ($paidInvoices as $paidInvoice) {
            $totalIncome += $paidInvoice->total_cost;
        }
        $totalIncome = ceil($totalIncome/100); 

        //Get invoices created count
        $invoicesCreated = 0;
        //Get ydata for invoice graph
        $yData=[];
        foreach($business->outgoingInvoices as $invoice){
            array_push ( $yData, $invoice->total_cost);
            $invoicesCreated++;
        }

        //Get total outstanding from unpaid invoices
        $unseenInvoices = $business->outgoingInvoices->where('status', 'unseen');
        $totalOutstanding = 0;
        foreach ($unseenInvoices as $unseenInvoice) {
            $totalOutstanding += $unseenInvoice->total_cost;
        }
        $totalOutstanding = ceil($totalOutstanding/100); 

        //get paid count
        $paidCount = $business->outgoingInvoices->where('status', 'paid')->count();

        //get unpaid count
        $unseenCount = $business->outgoingInvoices->where('status', 'unseen')->count();

        //get weekly sales chart data
        $weeklySales=[];
        
        for($i=6; $i>=0; $i--){
            array_push($weeklySales, Transactions::where('business_id', $business->id)->where( 'created_at', '>=', date('Y-m-d', strtotime(-$i.' days')))->where( 'created_at', '<', date('Y-m-d', strtotime((-$i)+1 .'  days')))->sum('amount')/100);
        }
        $jsonResponse=[
            'invoicesCreated' => $invoicesCreated,
            'totalIncome' => $totalIncome,
            'totalOutstanding' => $totalOutstanding,
            'paidCount' => $paidCount,
            'unseenCount' => $unseenCount,
            'yData'=>$yData,
            'weeklySales'=> $weeklySales
        ];
        return response()->json($jsonResponse,200);
    }
}
