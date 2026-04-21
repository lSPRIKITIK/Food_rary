<!DOCTYPE html>
<html lang="en">
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Food-Rary</title>
</head>
<body class="bg-[#f0f0f0] min-h-screen font-sans text-black p-8">

    <div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-[0_0_15px_rgba(0,0,0,0.1)] border-2 border-black">
        <div class="flex justify-between items-center border-b-2 border-[#c22026] pb-4 mb-6">
            <h2 class="text-3xl font-serif font-bold tracking-wider" style="font-variant: small-caps;">Manage Products</h2>
            <div class="flex gap-4">
                <a href="/dashboard" class="bg-gray-300 hover:bg-gray-400 text-black font-bold py-2 px-4 rounded border-2 border-black transition-colors">Back</a>
                <a href="/products/create" class="bg-[#c22026] hover:bg-red-800 text-white font-bold py-2 px-4 rounded border-2 border-black transition-colors">+ Add Product</a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- Search Bar --}}
        <form method="GET" action="/products" class="flex items-center gap-3 mb-6">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products or categories..." class="border-2 border-gray-300 rounded p-2 focus:border-black outline-none w-1/3">
            <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded font-bold hover:bg-black transition-colors border-2 border-black">Search</button>
            @if(request('search'))
                <a href="/products" class="text-[#c22026] font-bold hover:underline ml-2">Clear Filter</a>
            @endif
        </form>

        <div class="overflow-x-auto mb-6">
            <table class="w-full text-left border-collapse border-2 border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border-2 border-gray-300 p-3">ID</th>
                        <th class="border-2 border-gray-300 p-3">Name</th>
                        <th class="border-2 border-gray-300 p-3">Category</th>
                        <th class="border-2 border-gray-300 p-3">Price</th>
                        <th class="border-2 border-gray-300 p-3">Calories</th>
                        <th class="border-2 border-gray-300 p-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="border-2 border-gray-300 p-3">{{ $product->productID }}</td>
                        <td class="border-2 border-gray-300 p-3 font-bold uppercase">{{ $product->productName }}</td>
                        <td class="border-2 border-gray-300 p-3">{{ $product->categoryName }}</td>
                        <td class="border-2 border-gray-300 p-3">₱{{ number_format($product->productPrice, 2) }}</td>
                        <td class="border-2 border-gray-300 p-3">{{ $product->productCalories }} Cal</td>
                        <td class="border-2 border-gray-300 p-3 flex justify-center gap-2">
                            <a href="/products/{{ $product->productID }}/edit" class="bg-[#f0a518] hover:bg-yellow-600 text-white px-3 py-1 rounded font-bold">Edit</a>
                            
                            <form action="/products/{{ $product->productID }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-800 text-white px-3 py-1 rounded font-bold">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="border-2 border-gray-300 p-6 text-center text-gray-500 font-serif text-lg">No products found matching your search.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Links --}}
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>

</body>
</html>