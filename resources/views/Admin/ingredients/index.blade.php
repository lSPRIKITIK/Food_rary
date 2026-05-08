<!DOCTYPE html>
<html lang="en">
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Inventory - Food-Rary</title>
</head>
<body class="bg-[#f0f0f0] min-h-screen font-sans text-black p-8">

    <div class="max-w-5xl mx-auto bg-white p-8 rounded-lg shadow-[0_0_15px_rgba(0,0,0,0.1)] border-2 border-black">
        <div class="flex justify-between items-center border-b-2 border-[#78b833] pb-4 mb-6">
            <h2 class="text-3xl font-serif font-bold tracking-wider" style="font-variant: small-caps;">Manage Inventory</h2>
            <div class="flex gap-4">
                <a href="/dashboard" class="bg-gray-300 hover:bg-gray-400 text-black font-bold py-2 px-4 rounded border-2 border-black transition-colors">Back</a>
                <a href="/ingredients/create" class="bg-[#78b833] hover:bg-green-700 text-white font-bold py-2 px-4 rounded border-2 border-black transition-colors">+ Add Ingredient</a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- Search Bar --}}
        <form method="GET" action="/ingredients" class="flex items-center gap-3 mb-6">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search ingredients or types..." class="border-2 border-gray-300 rounded p-2 focus:border-black outline-none w-1/3">
            <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded font-bold hover:bg-black transition-colors border-2 border-black">Search</button>
            @if(request('search'))
                <a href="/ingredients" class="text-[#c22026] font-bold hover:underline ml-2">Clear Filter</a>
            @endif
        </form>

        <div class="overflow-x-auto mb-6">
            <table class="w-full text-left border-collapse border-2 border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border-2 border-gray-300 p-3">ID</th>
                        <th class="border-2 border-gray-300 p-3">Name</th>
                        <th class="border-2 border-gray-300 p-3">Type</th>
                        <th class="border-2 border-gray-300 p-3">Cost</th>
                        <th class="border-2 border-gray-300 p-3">Stock Qty</th>
                        <th class="border-2 border-gray-300 p-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ingredients as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="border-2 border-gray-300 p-3">{{ $item->ingredientID }}</td>
                        <td class="border-2 border-gray-300 p-3 font-bold uppercase">{{ $item->ingredientName }}</td>
                        <td class="border-2 border-gray-300 p-3">{{ $item->ingredientType }}</td>
                        <td class="border-2 border-gray-300 p-3">₱{{ number_format($item->cost, 2) }}</td>
                        <td class="border-2 border-gray-300 p-3">
                            <span class="{{ $item->stockQty <= 10 ? 'text-red-600 font-bold' : '' }}">
                                {{ $item->stockQty }}
                            </span>
                        </td>
                        <td class="border-2 border-gray-300 p-3 flex justify-center gap-2">
                            <a href="/ingredients/{{ $item->ingredientID }}/history" class="bg-[#78b833] hover:bg-green-700 text-white px-3 py-1 rounded font-bold">View</a>
                            <a href="/ingredients/{{ $item->ingredientID }}/add-stock" class="bg-[#f0a518] hover:bg-yellow-600 text-white px-3 py-1 rounded font-bold">Add Stock</a>
                            <a href="/ingredients/{{ $item->ingredientID }}/edit" class="bg-[#f0a518] hover:bg-yellow-600 text-white px-3 py-1 rounded font-bold">Edit</a>
                            
                            <form action="/ingredients/{{ $item->ingredientID }}" method="POST" onsubmit="return confirm('Delete this ingredient? This might break products using it in their recipe!');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-800 text-white px-3 py-1 rounded font-bold">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="border-2 border-gray-300 p-6 text-center text-gray-500 font-serif text-lg">No ingredients found matching your search.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Links --}}
        <div class="mt-4">
            {{ $ingredients->links() }}
        </div>
    </div>

</body>
</html>