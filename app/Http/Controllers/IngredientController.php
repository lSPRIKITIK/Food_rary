<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredient;

class IngredientController extends Controller
{
    public function index(\Illuminate\Http\Request $request){
        $search = $request->input('search');

        $ingredients = Ingredient::when($search, function ($query, $search) {
                return $query->where('ingredientName', 'like', "%{$search}%")
                             ->orWhere('ingredientType', 'like', "%{$search}%");
            })
            ->orderBy('ingredientName')
            ->paginate(10)
            ->appends(['search' => $search]);
        return view('Admin.ingredients.index', compact('ingredients', 'search'));
    }

    public function create()
    {
        return view('Admin.ingredients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'ingredientName' => 'required|string',
            'ingredientType' => 'required|string',
        ]);

        \App\Models\Ingredient::create($request->all());

        return redirect('/ingredients')->with('success', 'Ingredient added to catalog!');
    }

    public function edit($id)
    {
        $ingredient = Ingredient::findOrFail($id);
        return view('Admin.ingredients.edit', compact('ingredient'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'ingredientName' => 'required|string|max:255',
            'ingredientType' => 'required|string|max:255',
            'cost' => 'required|numeric|min:0',
            'stockQty' => 'required|numeric|min:0',
        ]);

        $ingredient = Ingredient::findOrFail($id);
        $ingredient->update($request->all());

        return redirect('/ingredients')->with('success', 'Ingredient updated successfully!');
    }

    public function destroy($id)
    {
        Ingredient::findOrFail($id)->delete();
        return back()->with('success', 'Ingredient deleted successfully!');
    }
    public function history($id)
    {
        $ingredient = \App\Models\Ingredient::findOrFail($id);
        $stockIns = \Illuminate\Support\Facades\DB::table('stock_ins')
            ->join('suppliers', 'stock_ins.supplierID', '=', 'suppliers.supplierID')
            ->where('stock_ins.ingredientID', $id)
            ->select('stock_ins.stockID', 'suppliers.supplierName', 'stock_ins.quantity', 'stock_ins.remainingQty', 'stock_ins.unitCost', 'stock_ins.deliveryDate')
            ->orderBy('stock_ins.deliveryDate', 'desc')
            ->paginate(5, ['*'], 'in_page'); 

        $stockOuts = \Illuminate\Support\Facades\DB::table('stock_outs')
            ->join('orders', 'stock_outs.orderID', '=', 'orders.orderID')
            ->join('stock_ins', 'stock_outs.stockID', '=', 'stock_ins.stockID')
            ->where('stock_ins.ingredientID', $id)
            ->select(
                \Illuminate\Support\Facades\DB::raw('DATE(orders.orderDate) as outDate'),
                'stock_ins.stockID', 
                \Illuminate\Support\Facades\DB::raw('SUM(stock_outs.quantityDeducted) as totalUsed')
            )
            ->groupBy('outDate', 'stock_ins.stockID')
            ->orderBy('outDate', 'desc')
            ->paginate(5, ['*'], 'out_page');

        return view('Admin.ingredients.history', compact('ingredient', 'stockIns', 'stockOuts'));
    }
    public function addStockForm($id)
    {
        $ingredient = \App\Models\Ingredient::findOrFail($id);
        
        $suppliers = \App\Models\Supplier::all()->unique('supplierName'); 
        
        return view('Admin.ingredients.add_stock', compact('ingredient', 'suppliers'));
    }

    public function storeStock(Request $request, $id)
    {
        if ($request->supplier_mode === 'new') {
            $supplier = \App\Models\Supplier::firstOrCreate(
                ['supplierName' => $request->new_supplier_name], 
                [
                    'supplierContact' => $request->new_supplier_contact,
                    'supplierStreet' => $request->new_supplier_street,
                    'supplierCity' => $request->new_supplier_city,
                ]
            );
            $supplierID = $supplier->supplierID;
        } else {
            $supplierID = $request->existing_supplier_id;
        }

        \App\Models\StockIn::create([
            'ingredientID' => $id,
            'supplierID' => $supplierID,
            'quantity' => $request->quantity,
            'remainingQty' => $request->quantity, 
            'unitCost' => $request->unitCost,
            'deliveryDate' => $request->deliveryDate
        ]);

        $ingredient = \App\Models\Ingredient::findOrFail($id);
        
        if ($ingredient->stockQty == 0) {
            $ingredient->update(['cost' => $request->unitCost]); 
        }
        
        $ingredient->increment('stockQty', $request->quantity);

        return redirect('/ingredients')->with('success', 'Stock successfully added!');
    }
    
    
}