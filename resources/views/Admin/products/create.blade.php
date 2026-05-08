<!DOCTYPE html>
<html lang="en">
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Food-Rary</title>
</head>
<body class="bg-[#f0f0f0] min-h-screen font-sans text-black p-8">

    <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-[0_0_15px_rgba(0,0,0,0.1)] border-2 border-black">
        <div class="flex items-center gap-3 mb-6 text-3xl font-serif tracking-wider" style="font-variant: small-caps;">
            <h2 class="font-bold border-b-2 border-[#c22026] pb-2 w-full">Add New Product</h2>
        </div>

        {{-- Display Errors --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/products" method="POST">
            @csrf

            {{-- Product Details Section --}}
            <div class="grid grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block font-bold mb-2 uppercase tracking-wide text-sm">Product Name</label>
                    <input type="text" name="productName" value="{{ old('productName') }}" required class="w-full border-2 border-gray-300 rounded p-2 focus:border-black outline-none">
                </div>
                <div>
                    <label class="block font-bold mb-2 uppercase tracking-wide text-sm">Category</label>
                    <select name="categoryID" required class="w-full border-2 border-gray-300 rounded p-2 focus:border-black outline-none">
                        <option value="" disabled selected>Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->categoryID }}">{{ $category->categoryName }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-bold mb-2 uppercase tracking-wide text-sm">Price (₱)</label>
                    <input type="number" step="0.01" name="productPrice" value="{{ old('productPrice') }}" required class="w-full border-2 border-gray-300 rounded p-2 focus:border-black outline-none">
                </div>
                <div>
                    <label class="block font-bold mb-2 uppercase tracking-wide text-sm">Calories</label>
                    <input type="number" name="productCalories" value="{{ old('productCalories') }}" required class="w-full border-2 border-gray-300 rounded p-2 focus:border-black outline-none">
                </div>
            </div>

            {{-- Recipe / Ingredients Section --}}
            <div class="mb-6 border-t-2 border-gray-300 pt-6">
                <h3 class="text-xl font-serif font-bold mb-4 tracking-wider" style="font-variant: small-caps;">Recipe Ingredients</h3>
                
                <div id="ingredients-container" class="space-y-4">
                    @php $oldIngs = old('ingredients'); @endphp
                    @if($oldIngs && is_array($oldIngs))
                        @foreach($oldIngs as $idx => $oldIng)
                            <div class="ingredient-row flex gap-4 items-end">
                                <div class="flex-1">
                                    <label class="block font-bold mb-1 text-sm">Ingredient</label>
                                    <select name="ingredients[{{ $idx }}][ingredientID]" required class="w-full border-2 border-gray-300 rounded p-2 focus:border-black outline-none">
                                        <option value="" disabled>Select Ingredient...</option>
                                        @foreach($ingredients as $ingredient)
                                            <option value="{{ $ingredient->ingredientID }}" {{ (string)($oldIng['ingredientID'] ?? '') === (string)$ingredient->ingredientID ? 'selected' : '' }}>{{ $ingredient->ingredientName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-32">
                                    <label class="block font-bold mb-1 text-sm">Qty Used</label>
                                    <input type="number" step="0.01" name="ingredients[{{ $idx }}][qtyUsed]" value="{{ $oldIng['qtyUsed'] ?? '' }}" required class="w-full border-2 border-gray-300 rounded p-2 focus:border-black outline-none">
                                </div>
                                @if($idx > 0)
                                    <button type="button" class="remove-btn bg-red-500 text-white p-2 rounded border-2 border-red-700 hover:bg-red-700 font-bold mb-1 w-10 h-10 flex items-center justify-center">X</button>
                                @endif
                            </div>
                        @endforeach
                    @else
                        {{-- Default First Ingredient Row --}}
                        <div class="ingredient-row flex gap-4 items-end">
                            <div class="flex-1">
                                <label class="block font-bold mb-1 text-sm">Ingredient</label>
                                <select name="ingredients[0][ingredientID]" required class="w-full border-2 border-gray-300 rounded p-2 focus:border-black outline-none">
                                    <option value="" disabled selected>Select Ingredient...</option>
                                    @foreach($ingredients as $ingredient)
                                        <option value="{{ $ingredient->ingredientID }}">{{ $ingredient->ingredientName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-32">
                                <label class="block font-bold mb-1 text-sm">Qty Used</label>
                                <input type="number" step="0.01" name="ingredients[0][qtyUsed]" required class="w-full border-2 border-gray-300 rounded p-2 focus:border-black outline-none">
                            </div>
                        </div>
                    @endif
                </div>

                <button type="button" id="add-ingredient-btn" class="mt-4 bg-[#f0a518] hover:bg-yellow-600 text-black font-bold py-2 px-4 rounded border-2 border-black transition-colors">
                    + Add Another Ingredient
                </button>
            </div>

            {{-- Submit --}}
            <div class="flex justify-end mt-8 border-t-2 border-gray-300 pt-6">
                <a href="/products" class="mr-4 text-gray-600 font-bold py-2 px-4 hover:underline">Cancel</a>
                <button type="submit" class="bg-[#c22026] hover:bg-red-800 text-white font-bold py-2 px-6 rounded shadow-md transition-colors tracking-widest uppercase">
                    Save Product
                </button>
            </div>
        </form>
    </div>

    {{-- JavaScript to handle dynamic ingredient rows --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const oldCount = {{ isset($oldIngs) && is_array($oldIngs) ? count($oldIngs) : 0 }};
            let ingredientIndex = oldCount > 0 ? oldCount - 1 : 0;
            const container = document.getElementById('ingredients-container');
            const addBtn = document.getElementById('add-ingredient-btn');

            // Store the options HTML so we can reuse it
            const ingredientOptions = `
                <option value="" disabled selected>Select Ingredient...</option>
                @foreach($ingredients as $ingredient)
                    <option value="{{ $ingredient->ingredientID }}">{{ $ingredient->ingredientName }}</option>
                @endforeach
            `;

            // Attach existing remove buttons
            document.querySelectorAll('.remove-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    this.closest('.ingredient-row').remove();
                });
            });

            addBtn.addEventListener('click', function() {
                ingredientIndex++; // Increment index so Laravel reads it as an array (ingredients[1], ingredients[2], etc)
                
                const newRow = document.createElement('div');
                newRow.className = 'ingredient-row flex gap-4 items-end mt-4';
                newRow.innerHTML = `
                    <div class="flex-1">
                        <select name="ingredients[${ingredientIndex}][ingredientID]" required class="w-full border-2 border-gray-300 rounded p-2 focus:border-black outline-none">
                            ${ingredientOptions}
                        </select>
                    </div>
                    <div class="w-32">
                        <input type="number" step="0.01" name="ingredients[${ingredientIndex}][qtyUsed]" placeholder="Qty" required class="w-full border-2 border-gray-300 rounded p-2 focus:border-black outline-none">
                    </div>
                    <button type="button" class="remove-btn bg-red-500 text-white p-2 rounded border-2 border-red-700 hover:bg-red-700 font-bold mb-1 w-10 h-10 flex items-center justify-center">X</button>
                `;
                
                container.appendChild(newRow);

                // Add event listener to the newly created remove button
                newRow.querySelector('.remove-btn').addEventListener('click', function() {
                    newRow.remove();
                });
            });
        });
    </script>
</body>
</html>