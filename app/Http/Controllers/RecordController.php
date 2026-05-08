<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RecordController extends Controller
{
    public function index(Request $request)
    {
        $selectedDate = $request->input('date', Carbon::today()->toDateString());

        $records = \Illuminate\Support\Facades\DB::table('order_details')
            ->join('orders', 'order_details.orderID', '=', 'orders.orderID')
            ->join('products', 'order_details.productID', '=', 'products.productID')
            ->join('employees', 'orders.employeeID', '=', 'employees.employeeID')
            ->whereDate('orders.orderDate', $selectedDate)
            ->select(
                'orders.orderID',
                'orders.orderDate',
                'products.productName',
                'order_details.quantity',
                'order_details.unitPrice',
                'order_details.subTotal',
                'order_details.ingredientCost',
                'employees.firstName',
                'employees.lastName'
            )
            ->orderBy('orders.orderDate', 'desc')
            ->paginate(15)
            ->appends(['date' => $selectedDate]);
        $dailyTotal = $records->sum('subTotal');
        $dailyProfit = $records->sum(function($row) {
            return $row->subTotal - ($row->ingredientCost * $row->quantity);
        });

        return view('Admin.records.index', compact('records', 'selectedDate', 'dailyTotal', 'dailyProfit'));
    }
}