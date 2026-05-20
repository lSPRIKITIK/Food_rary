<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; 

class DashboardController extends Controller
{
    // Pass Request $request into the index method
    public function index(Request $request)
    {
        // 1. Grab the search term from the URL
        $search = $request->input('search');

        // ... (Keep your existing $baseQuery, $todayData, $monthData, and $yearData EXACTLY the same here) ...
        $baseQuery = DB::table('order_details')
            ->join('orders', 'order_details.orderID', '=', 'orders.orderID');
            
        $todayData = (clone $baseQuery)->whereDate('orders.orderDate', Carbon::today())
            ->select(DB::raw('SUM(order_details.subTotal) as sales'), DB::raw('SUM(order_details.subTotal - (order_details.ingredientCost * order_details.quantity)) as profit'))->first();
            
        $monthData = (clone $baseQuery)->whereYear('orders.orderDate', Carbon::now()->year)->whereMonth('orders.orderDate', Carbon::now()->month)
            ->select(DB::raw('SUM(order_details.subTotal) as sales'), DB::raw('SUM(order_details.subTotal - (order_details.ingredientCost * order_details.quantity)) as profit'))->first();
            
        $yearData = (clone $baseQuery)->whereYear('orders.orderDate', Carbon::now()->year)
            ->select(DB::raw('SUM(order_details.subTotal) as sales'), DB::raw('SUM(order_details.subTotal - (order_details.ingredientCost * order_details.quantity)) as profit'))->first();


        // 2. Update the Top Sellers query to include the search filter
        $topSellers = DB::table('products')
            ->leftJoin('order_details', 'products.productID', '=', 'order_details.productID')
            ->select(
                'products.productID',
                'products.productName',
                'products.productCalories',
                'products.productPrice',
                'products.productImage',
                DB::raw('COALESCE(SUM(order_details.quantity), 0) as total_sold')
            )
            // ---> Add this 'when' block right here! <---
            ->when($search, function ($query, $search) {
                return $query->where('products.productName', 'like', "%{$search}%");
            })
            ->groupBy(
                'products.productID', 
                'products.productName', 
                'products.productCalories', 
                'products.productPrice',
                'products.productImage'
            )
            ->orderBy('total_sold', 'desc')
            ->paginate(18)
            ->appends(['search' => $search]); // Keeps the search query active when clicking to page 2!

        
        return view('Staff.dashboard', [
            'topSellers' => $topSellers,
            'todaySales' => $todayData->sales ?? 0,
            'todayProfit' => $todayData->profit ?? 0,
            'monthSales' => $monthData->sales ?? 0,
            'monthProfit' => $monthData->profit ?? 0,
            'yearSales' => $yearData->sales ?? 0,
            'yearProfit' => $yearData->profit ?? 0,
        ]); 
    }
}