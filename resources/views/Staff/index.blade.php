<!DOCTYPE html>
<html lang="en">
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Menu - Food-Rary</title>
</head>
<body class="bg-[#e9e9e9] min-h-screen font-sans text-black flex flex-col h-screen overflow-hidden">

    <x-header />

    <main class="flex flex-1 overflow-hidden">

        <div class="flex-1 flex flex-col p-6 overflow-hidden">

            @php
                $colorPalette = [
                    'bg-[#f06418]', // Orange
                    'bg-[#e99e1d]', // Yellow-Orange
                    'bg-[#7d201e]', // Dark Red
                    'bg-[#78b833]', // Green
                    'bg-[#f0a518]', // Yellow
                    'bg-[#c22026]', // Red
                    'bg-[#d26020]'  // Rust
                ];
            @endphp

            <div class="flex gap-3 mb-6 overflow-x-auto pb-2 flex-shrink-0 font-bold text-white text-sm tracking-wider uppercase">
                <button class="category-btn active bg-[#f24d26] px-4 py-2 rounded-lg shadow whitespace-nowrap" 
                        data-category="all" 
                        onclick="filterByCategory(this, 'all')">
                    All Item
                </button>

                @foreach($categories as $category)
                    @php
                        // Automatically cycle through the colors based on the loop index!
                        $colorClass = $colorPalette[$loop->index % count($colorPalette)];
                        // Make the category string lowercase for the JavaScript filter
                        $filterName = strtolower($category->categoryName);
                    @endphp
                    
                    <button class="category-btn {{ $colorClass }} px-4 py-2 rounded-lg shadow whitespace-nowrap" 
                            data-category="{{ $filterName }}" 
                            onclick="filterByCategory(this, '{{ $filterName }}')">
                        {{ $category->categoryName }}
                    </button>
                @endforeach
            </div>

            <div class="flex-1 overflow-y-auto pr-1 pb-4">
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-5" id="productGrid">
                    </div>
                <div id="noResults" class="hidden text-center text-gray-400 font-serif-custom italic mt-16 text-lg">No Products found.</div>
            </div>
        </div>

        <div class="w-[400px] bg-[#e9e9e9] border-l-2 border-gray-300 flex flex-col shadow-[-5px_0_15px_rgba(0,0,0,0.05)] z-10 flex-shrink-0">

            <div class="p-6 text-center border-b border-gray-300 bg-[#e9e9e9]">
                <h2 class="text-2xl font-bold font-serif tracking-wider">Purchase No. <span id="purchaseNo">{{ $nextOrderId ?? 1 }}</span></h2>
            </div>

            <div class="flex-1 overflow-y-auto p-4" id="cartItemsContainer">
                <div class="text-center text-gray-400 mt-10 font-serif-custom italic" id="emptyCartMessage">Cart is empty</div>
            </div>

            <div class="bg-white p-6 border-t border-gray-300 flex flex-col max-h-[46vh]">
                <h3 class="font-bold text-center mb-4 font-serif text-base tracking-wide">Payment Summary</h3>

                <div class="flex-1 overflow-y-auto mb-3 border-b border-gray-200 pb-2" id="summaryList"></div>

                <div class="flex justify-between text-sm font-bold mb-1 mt-2">
                    <span>Net Price:</span>
                    <span>₱<span id="netPrice">0</span></span>
                </div>
                <div class="flex justify-between text-sm font-bold mb-3">
                    <span>Tax (12% VAT):</span>
                    <span>₱<span id="taxPrice">0</span></span>
                </div>
                <div class="flex justify-between text-xl font-bold mb-5 font-serif">
                    <span>Total Price:</span>
                    <span>₱<span id="totalPrice">0</span></span>
                </div>

                <button id="checkoutBtn" onclick="processCheckout()" class="w-full bg-[#f0a518] text-black font-bold py-3 rounded-lg border-2 border-black shadow transition-colors font-serif tracking-wide text-lg opacity-50 cursor-not-allowed" disabled>
                    Confirm Purchase
                </button>
            </div>
        </div>
    </main>

    <script>
        const products = @json($products);
    </script>
    <script src="{{ asset('js/cart.js') }}"></script>
</body>
</html>