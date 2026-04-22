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
        // Fetch products with their recipes and ingredients to check stock
        $products = Product::with('recipes.ingredient')->get()->map(function ($product) {
            $isSoldOut = false;
            
            foreach ($product->recipes as $recipe) {
                if ($recipe->ingredient && $recipe->ingredient->stockQty < $recipe->qtyUsed) {
                    $isSoldOut = true;
                    break;
                }
            }
            
            $product->is_sold_out = $isSoldOut;
            return $product;
        });

        // Pass $nextOrderId to the view!
        return view('Staff.index', compact('categories', 'products', 'nextOrderId'));
    }

    public function checkout(Request $request) {
        $cart = $request->input('cart');

        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Cart is empty'], 400);
        }

        try {
            DB::transaction(function () use ($cart) {
                // 1. Create the Order
                $order = Order::create([
                    'employeeID' => Auth::user()->employeeID,
                    'orderDate' => now(),
                ]);

                // 2. Process each item in the cart
                foreach ($cart as $item) {
                    $product = Product::with('recipes.ingredient')->findOrFail($item['productID']);
                    $quantity = $item['quantity'];

                    // Calculate the exact ingredient cost for ONE unit of this product
                    $costForOneUnit = 0;

                    // 3. Deduct Ingredient Stock AND Calculate Cost
                    foreach ($product->recipes as $recipe) {
                        $ingredient = $recipe->ingredient;
                        
                        // Add to our cost calculation (qty required for 1 product * cost per ingredient unit)
                        $costForOneUnit += ($recipe->qtyUsed * $ingredient->cost);

                        // Calculate total used for stock deduction
                        $totalUsed = $recipe->qtyUsed * $quantity;
                        
                        if ($ingredient->stockQty < $totalUsed) {
                            throw new \Exception("Not enough stock for ingredient: " . $ingredient->ingredientName);
                        }

                        $ingredient->decrement('stockQty', $totalUsed);
                    }

                    // 4. Create Order Detail, locking in the historical ingredient cost!
                    OrderDetail::create([
                        'orderID' => $order->orderID,
                        'productID' => $product->productID,
                        'quantity' => $quantity,
                        'unitPrice' => $product->productPrice,
                        'subTotal' => $product->productPrice * $quantity,
                        'ingredientCost' => $costForOneUnit, // Save the locked-in cost!
                    ]);
                }
            });

            return response()->json(['success' => true, 'message' => 'Order completed successfully!']);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}