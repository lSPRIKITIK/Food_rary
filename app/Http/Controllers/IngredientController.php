<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredient;

class IngredientController extends Controller
{
    // Shows the list of ingredients with Search & Pagination
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
            'ingredientName' => 'required|string|max:255',
            'ingredientType' => 'required|string|max:255',
            'cost' => 'required|numeric|min:0',
            'stockQty' => 'required|numeric|min:0',
        ]);

        Ingredient::create($request->all());

        return redirect('/ingredients')->with('success', 'Ingredient added successfully!');
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
}