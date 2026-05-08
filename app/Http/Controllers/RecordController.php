<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RecordController extends Controller
{
    public function index(Request $request)
    {
        // 1. Get the requested date, or default to today's date
        $selectedDate = $request->input('date', Carbon::today()->toDateString());

        // 2. Fetch all sold items for that specific date
        $records = DB::table('order_details')
            ->join('orders', 'order_details.orderID', '=', 'orders.orderID')
            ->join('products', 'order_details.productID', '=', 'products.productID')
            ->whereDate('orders.orderDate', $selectedDate)
            ->select(
                'orders.orderID',
                'orders.orderDate',
                'products.productName',
                'order_details.quantity',
                'order_details.unitPrice',
                'order_details.subTotal',
                'order_details.ingredientCost'
            )
            ->orderBy('orders.orderDate', 'desc')
            ->paginate(15)
            ->appends(['date' => $selectedDate]); // Keep date active on page 2, 3, etc.

        // 3. Calculate the total sales & profit for that specific day
        $dailyTotal = $records->sum('subTotal');
        $dailyProfit = $records->sum(function($row) {
            return $row->subTotal - ($row->ingredientCost * $row->quantity);
        });

        return view('Admin.records.index', compact('records', 'selectedDate', 'dailyTotal', 'dailyProfit'));
    }
}