<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Recipe;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    // Shows the Add Product Form
    public function create()
    {
        $categories = Category::all();
        $ingredients = Ingredient::all();
        
        return view('Admin.products.create', compact('categories', 'ingredients'));
    }

    // Shows the list of all products
    // Shows the list of all products with Search & Pagination
    public function index(\Illuminate\Http\Request $request) {
        $search = $request->input('search');
        // Join with categories to easily display and search the category name
        $products = Product::join('categories', 'products.categoryID', '=', 'categories.categoryID')
            ->select('products.*', 'categories.categoryName')
            ->when($search, function ($query, $search) {
                return $query->where('products.productName', 'like', "%{$search}%")
                             ->orWhere('categories.categoryName', 'like', "%{$search}%");
            })
            ->orderBy('products.productName')
            ->paginate(10) // Show 10 items per page
            ->appends(['search' => $search]); // Keeps the search query in the URL when clicking page 2, 3, etc.
            
        return view('Admin.products.index', compact('products', 'search'));
    }

    // Shows the Edit form
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $ingredients = Ingredient::all();
        // Fetch the existing recipes for this product
        $recipes = Recipe::where('productID', $id)->get();

        return view('Admin.products.edit', compact('product', 'categories', 'ingredients', 'recipes'));
    }

    // Processes the update form submission
    public function update(Request $request, $id)
    {
        $request->validate([
            'productName' => 'required|string|max:255',
            'categoryID' => 'required|exists:categories,categoryID',
            'productCalories' => 'required|integer|min:0',
            'productPrice' => 'required|numeric|min:0',
            'productImage' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.ingredientID' => 'required|exists:ingredients,ingredientID',
            'ingredients.*.qtyUsed' => 'required|numeric|min:0.1',
        ]);
        // Calculate total ingredient cost (using active cost accessor)
        $totalIngredientCost = 0;
        foreach ($request->ingredients as $ing) {
            $ingModel = Ingredient::find($ing['ingredientID']);
            $unitCost = $ingModel ? $ingModel->active_cost : 0;
            $qty = is_numeric($ing['qtyUsed']) ? (float) $ing['qtyUsed'] : 0;
            $totalIngredientCost += $unitCost * $qty;
        }

        // Prevent setting a product price that is less than or equal to ingredient cost
        if ((float)$request->productPrice <= $totalIngredientCost) {
            return back()->withErrors(['productPrice' => 'Product price must be greater than the total ingredient cost (₱' . number_format($totalIngredientCost, 2) . ').'])->withInput();
        }

        // Handle image upload if provided
        $uploadedImageName = null;
        if ($request->hasFile('productImage')) {
            $file = $request->file('productImage');
            $ext = $file->getClientOriginalExtension();
            $safe = preg_replace('/[^A-Za-z0-9\\-]/', '', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
            $uploadedImageName = time() . '_' . substr(md5(uniqid()), 0, 6) . '_' . $safe . '.' . $ext;
            $targetDir = public_path('images/products');
            if (!file_exists($targetDir)) { mkdir($targetDir, 0755, true); }
            $file->move($targetDir, $uploadedImageName);
        }

        try {
            // Capture old image for cleanup
            $oldImage = Product::where('productID', $id)->value('productImage');

            DB::transaction(function () use ($request, $id, $uploadedImageName) {
                // Update the product
                $product = Product::findOrFail($id);

                $updateData = [
                    'categoryID' => $request->categoryID,
                    'productName' => $request->productName,
                    'productCalories' => $request->productCalories,
                    'productPrice' => $request->productPrice,
                ];

                if ($uploadedImageName) {
                    $updateData['productImage'] = $uploadedImageName;
                }

                $product->update($updateData);

                // Clear out the old recipe ingredients
                Recipe::where('productID', $id)->delete();

                // Insert the newly updated ingredients
                foreach ($request->ingredients as $ingredient) {
                    Recipe::create([
                        'productID' => $product->productID,
                        'ingredientID' => $ingredient['ingredientID'],
                        'qtyUsed' => $ingredient['qtyUsed'],
                    ]);
                }
            });

            // If new image uploaded, delete old file
            if ($uploadedImageName && $oldImage) {
                $oldPath = public_path('images/products/' . $oldImage);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            return redirect('/products')->with('success', 'Product updated successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage())->withInput();
        }
    }

    // Deletes the product
    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                // Delete the linked recipe rows first to avoid foreign key errors
                Recipe::where('productID', $id)->delete();
                // Then delete the product
                $product = Product::findOrFail($id);
                // remove image file if exists
                if ($product->productImage) {
                    $path = public_path('images/products/' . $product->productImage);
                    if (file_exists($path)) {@unlink($path);} 
                }
                $product->delete();
            });

            return back()->with('success', 'Product deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting product: ' . $e->getMessage());
        }
    }

    // Processes the form submission
    public function store(Request $request)
    {
        // 1. Validate the incoming data
        $request->validate([
            'productName' => 'required|string|max:255',
            'categoryID' => 'required|exists:categories,categoryID',
            'productCalories' => 'required|integer|min:0',
            'productPrice' => 'required|numeric|min:0',
            'productImage' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            // Validate the dynamic ingredients array
            'ingredients' => 'required|array|min:1',
            'ingredients.*.ingredientID' => 'required|exists:ingredients,ingredientID',
            'ingredients.*.qtyUsed' => 'required|numeric|min:0.1',
        ]);

        // Calculate total ingredient cost (using active cost accessor)
        $totalIngredientCost = 0;
        foreach ($request->ingredients as $ing) {
            $ingModel = Ingredient::find($ing['ingredientID']);
            $unitCost = $ingModel ? $ingModel->active_cost : 0;
            $qty = is_numeric($ing['qtyUsed']) ? (float) $ing['qtyUsed'] : 0;
            $totalIngredientCost += $unitCost * $qty;
        }

        // Prevent setting a product price that is less than or equal to ingredient cost
        if ((float)$request->productPrice <= $totalIngredientCost) {
            return back()->withErrors(['productPrice' => 'Product price must be greater than the total ingredient cost (₱' . number_format($totalIngredientCost, 2) . ').'])->withInput();
        }

        // Handle image upload if provided
        $uploadedImageName = null;
        if ($request->hasFile('productImage')) {
            $file = $request->file('productImage');
            $ext = $file->getClientOriginalExtension();
            $safe = preg_replace('/[^A-Za-z0-9\\-]/', '', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
            $uploadedImageName = time() . '_' . substr(md5(uniqid()), 0, 6) . '_' . $safe . '.' . $ext;
            $targetDir = public_path('images/products');
            if (!file_exists($targetDir)) { mkdir($targetDir, 0755, true); }
            $file->move($targetDir, $uploadedImageName);
        }

        try {
            // 2. Use a DB Transaction: Either EVERYTHING saves, or NOTHING saves
            DB::transaction(function () use ($request, $uploadedImageName) {
                
                // Create the Product first
                $product = Product::create([
                    'categoryID' => $request->categoryID,
                    'productName' => $request->productName,
                    'productCalories' => $request->productCalories,
                    'productPrice' => $request->productPrice,
                    'productImage' => $uploadedImageName,
                ]);

                // Loop through the submitted ingredients and link them in the recipes table
                foreach ($request->ingredients as $ingredient) {
                    Recipe::create([
                        'productID' => $product->productID,
                        'ingredientID' => $ingredient['ingredientID'],
                        'qtyUsed' => $ingredient['qtyUsed'],
                    ]);
                }
            });

            return redirect('/products')->with('success', 'Product and recipe successfully created!');
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage())->withInput();
        }
    }
}