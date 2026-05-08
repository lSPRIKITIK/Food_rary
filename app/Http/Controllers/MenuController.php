<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    public function index(Request $request) {
        $categories = Category::all();
        
        // Grab the highest orderID currently in the database and add 1
        $nextOrderId = (\App\Models\Order::max('orderID') ?? 0) + 1;
        // Fetch products with their recipes, ingredients and category to check stock and expose categoryName for the JS
        $products = Product::with('recipes.ingredient', 'category')->get()->map(function ($product) {
            $isSoldOut = false;
            
            foreach ($product->recipes as $recipe) {
                if ($recipe->ingredient && $recipe->ingredient->stockQty < $recipe->qtyUsed) {
                    $isSoldOut = true;
                    break;
                }
            }
            
            $product->is_sold_out = $isSoldOut;
            // Attach categoryName as an attribute for client-side filtering
            $product->setAttribute('categoryName', $product->category ? $product->category->categoryName : null);
            return $product;
        });

        // Pass $nextOrderId to the view!
        return view('Staff.index', compact('categories', 'products', 'nextOrderId'));
    }

    public function checkout(Request $request)
    {
        $cart = $request->input('cart');
        if (empty($cart)) return response()->json(['success' => false, 'message' => 'Cart is empty'], 400);

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($cart) {
                $order = \App\Models\Order::create([
                    'employeeID' => \Illuminate\Support\Facades\Auth::user()->employeeID,
                    'orderDate' => now(),
                ]);

                foreach ($cart as $item) {
                    $product = \App\Models\Product::with('recipes.ingredient')->findOrFail($item['productID']);
                    $quantity = $item['quantity'];

                    $totalBlendedCost = 0;


                    foreach ($product->recipes as $recipe) {
                        $ingredient = $recipe->ingredient;
                        $totalNeeded = $recipe->qtyUsed * $quantity;
                        $remainingToDeduct = $totalNeeded;

                        $batches = \App\Models\StockIn::where('ingredientID', $ingredient->ingredientID)
                            ->where('remainingQty', '>', 0)
                            ->orderBy('deliveryDate', 'asc')
                            ->orderBy('stockID', 'asc')
                            ->get();

                        foreach ($batches as $batch) {
                            if ($remainingToDeduct <= 0) break;

                            if ($batch->remainingQty >= $remainingToDeduct) {

                                $batch->decrement('remainingQty', $remainingToDeduct);
                                
                                \App\Models\StockOut::create([
                                    'orderID' => $order->orderID,
                                    'stockID' => $batch->stockID,
                                    'quantityDeducted' => $remainingToDeduct
                                ]);

                                $totalBlendedCost += ($remainingToDeduct * $batch->unitCost);
                                $remainingToDeduct = 0;
                            } else {

                                $available = $batch->remainingQty;
                                $batch->update(['remainingQty' => 0]);
                                
                                \App\Models\StockOut::create([
                                    'orderID' => $order->orderID,
                                    'stockID' => $batch->stockID,
                                    'quantityDeducted' => $available
                                ]);

                                $totalBlendedCost += ($available * $batch->unitCost);
                                $remainingToDeduct -= $available;
                            }
                        }


                        if ($remainingToDeduct > 0) {
                            throw new \Exception("Not enough stock for ingredient: " . $ingredient->ingredientName);
                        }

                        $ingredient->decrement('stockQty', $totalNeeded);
                    }


                    $costForOneUnit = $totalBlendedCost / $quantity;

                    \App\Models\OrderDetail::create([
                        'orderID' => $order->orderID,
                        'productID' => $product->productID,
                        'quantity' => $quantity,
                        'unitPrice' => $product->productPrice,
                        'subTotal' => $product->productPrice * $quantity,
                        'ingredientCost' => $costForOneUnit,
                    ]);
                }
            });

            return response()->json(['success' => true, 'message' => 'Order completed successfully!']);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}