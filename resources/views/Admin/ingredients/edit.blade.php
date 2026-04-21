<!DOCTYPE html>
<html lang="en">
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Ingredient - Food-Rary</title>
</head>
<body class="bg-[#f0f0f0] min-h-screen font-sans text-black p-8">

    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-[0_0_15px_rgba(0,0,0,0.1)] border-2 border-black">
        <div class="flex items-center gap-3 mb-6 text-3xl font-serif tracking-wider" style="font-variant: small-caps;">
            <h2 class="font-bold border-b-2 border-[#c22026] pb-2 w-full">Edit Ingredient: {{ $ingredient->ingredientName }}</h2>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/ingredients/{{ $ingredient->ingredientID }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div>
                    <label class="block font-bold mb-2 uppercase tracking-wide text-sm">Ingredient Name</label>
                    <input type="text" name="ingredientName" value="{{ old('ingredientName', $ingredient->ingredientName) }}" required class="w-full border-2 border-gray-300 rounded p-2 focus:border-black outline-none">
                </div>
                
                <div>
                    <label class="block font-bold mb-2 uppercase tracking-wide text-sm">Ingredient Type</label>
                    @php $currentType = old('ingredientType', $ingredient->ingredientType); @endphp
                    <select name="ingredientType" required class="w-full border-2 border-gray-300 rounded p-2 focus:border-black outline-none">
                        <option value="Meat" {{ $currentType == 'Meat' ? 'selected' : '' }}>Meat</option>
                        <option value="Bakery" {{ $currentType == 'Bakery' ? 'selected' : '' }}>Bakery / Bread</option>
                        <option value="Dairy" {{ $currentType == 'Dairy' ? 'selected' : '' }}>Dairy</option>
                        <option value="Produce" {{ $currentType == 'Produce' ? 'selected' : '' }}>Produce / Vegetables</option>
                        <option value="Condiment" {{ $currentType == 'Condiment' ? 'selected' : '' }}>Condiment / Sauce</option>
                        <option value="Beverage" {{ $currentType == 'Beverage' ? 'selected' : '' }}>Beverage</option>
                        <option value="Packaging" {{ $currentType == 'Packaging' ? 'selected' : '' }}>Packaging</option>
                        <option value="Other" {{ $currentType == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block font-bold mb-2 uppercase tracking-wide text-sm">Cost per unit (₱)</label>
                        <input type="number" step="0.01" name="cost" value="{{ old('cost', $ingredient->cost) }}" required class="w-full border-2 border-gray-300 rounded p-2 focus:border-black outline-none">
                    </div>
                    <div>
                        <label class="block font-bold mb-2 uppercase tracking-wide text-sm">Stock Qty</label>
                        <input type="number" step="any" name="stockQty" value="{{ old('stockQty', $ingredient->stockQty) }}" required class="w-full border-2 border-gray-300 rounded p-2 focus:border-black outline-none">
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-8 border-t-2 border-gray-300 pt-6">
                <a href="/ingredients" class="mr-4 text-gray-600 font-bold py-2 px-4 hover:underline">Cancel</a>
                <button type="submit" class="bg-[#c22026] hover:bg-red-800 text-white font-bold py-2 px-6 rounded shadow-md transition-colors tracking-widest uppercase">
                    Update Ingredient
                </button>
            </div>
        </form>
    </div>
</body>
</html>