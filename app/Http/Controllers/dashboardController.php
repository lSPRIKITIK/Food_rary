<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; 

class DashboardController extends Controller
{
    public function index()
    {
        $productCosts = DB::table('recipes')
            ->join('ingredients', 'recipes.ingredientID', '=', 'ingredients.ingredientID')
            ->select('recipes.productID', DB::raw('SUM(recipes.qtyUsed * ingredients.cost) as unit_cost'))
            ->groupBy('recipes.productID');

        $baseQuery = DB::table('order_details')
            ->join('orders', 'order_details.orderID', '=', 'orders.orderID')
            ->leftJoinSub($productCosts, 'product_costs', function ($join) {
                $join->on('order_details.productID', '=', 'product_costs.productID');
            });

        
        $todayData = (clone $baseQuery)
            ->whereDate('orders.orderDate', Carbon::today())
            ->select(
                DB::raw('SUM(order_details.subTotal) as sales'),
                DB::raw('SUM(order_details.subTotal - (COALESCE(product_costs.unit_cost, 0) * order_details.quantity)) as profit')
            )->first();

        
        $monthData = (clone $baseQuery)
            ->whereYear('orders.orderDate', Carbon::now()->year)
            ->whereMonth('orders.orderDate', Carbon::now()->month)
            ->select(
                DB::raw('SUM(order_details.subTotal) as sales'),
                DB::raw('SUM(order_details.subTotal - (COALESCE(product_costs.unit_cost, 0) * order_details.quantity)) as profit')
            )->first();

        
        $yearData = (clone $baseQuery)
            ->whereYear('orders.orderDate', Carbon::now()->year)
            ->select(
                DB::raw('SUM(order_details.subTotal) as sales'),
                DB::raw('SUM(order_details.subTotal - (COALESCE(product_costs.unit_cost, 0) * order_details.quantity)) as profit')
            )->first();

    
        $topSellers = DB::table('products')
            ->leftJoin('order_details', 'products.productID', '=', 'order_details.productID')
            ->select(
                'products.productID',
                'products.productName',
                'products.productCalories',
                'products.productPrice',
                DB::raw('COALESCE(SUM(order_details.quantity), 0) as total_sold')
            )
            ->groupBy(
                'products.productID', 
                'products.productName', 
                'products.productCalories', 
                'products.productPrice'
            )
            ->orderBy('total_sold', 'desc')
            ->paginate(18);

        
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