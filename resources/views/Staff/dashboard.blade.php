<!DOCTYPE html>
<html lang="en">
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard - Food-Rary</title>
</head>
<body class="bg-[#f0f0f0] min-h-screen font-sans text-black">
    <x-header />

    <main class="px-8 py-6">
        <section class="mb-8 border-b-2 border-gray-300 pb-8">
            <div class="flex items-center gap-3 mb-6 text-3xl font-serif tracking-wider" style="font-variant: small-caps;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                </svg>
                <h2 class="font-bold">Sales Overview</h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4">
                <div class="bg-[#c22026] text-white rounded-lg p-4 flex flex-col justify-between shadow-md h-28">
                    <div class="w-full flex justify-end">
                        <h3 class="text-3xl font-bold tracking-wide">₱{{ number_format($todaySales ?? 0, 2) }}</h3>
                    </div>
                    <div class="w-full">
                        <p class="text-sm font-light">Today's Sales</p>
                    </div>
                </div>
                <div class="bg-[#f06418] text-white rounded-lg p-4 flex flex-col justify-between shadow-md h-28">
                    <div class="w-full flex justify-end">
                        <h3 class="text-3xl font-bold tracking-wide">₱{{ number_format($todayProfit ?? 0, 2) }}</h3>
                    </div>
                    <div class="w-full">
                        <p class="text-sm font-light">Today's Profit</p>
                    </div>
                </div>
                <div class="bg-[#7d201e] text-white rounded-lg p-4 flex flex-col justify-between shadow-md h-28">
                    <div class="w-full flex justify-end">
                        <h3 class="text-3xl font-bold tracking-wide">₱{{ number_format($monthSales ?? 0, 2) }}</h3>
                    </div>
                    <div class="w-full">
                        <p class="text-sm font-light">This Month's Sales</p>
                    </div>
                </div>
                <div class="bg-[#e99e1d] text-white rounded-lg p-4 flex flex-col justify-between shadow-md h-28">
                    <div class="w-full flex justify-end">
                        <h3 class="text-3xl font-bold tracking-wide">₱{{ number_format($monthProfit ?? 0, 2) }}</h3>
                    </div>
                    <div class="w-full">
                        <p class="text-sm font-light">This Month's Profit</p>
                    </div>
                </div>
                <div class="bg-[#78b833] text-white rounded-lg p-4 flex flex-col justify-between shadow-md h-28">
                    <div class="w-full flex justify-end">
                        <h3 class="text-3xl font-bold tracking-wide">₱{{ number_format($yearSales ?? 0, 2) }}</h3>
                    </div>
                    <div class="w-full">
                        <p class="text-sm font-light">This Year's Sales</p>
                    </div>
                </div>
                <div class="bg-[#d26020] text-white rounded-lg p-4 flex flex-col justify-between shadow-md h-28">
                    <div class="w-full flex justify-end">
                        <h3 class="text-3xl font-bold tracking-wide">₱{{ number_format($yearProfit ?? 0, 2) }}</h3>
                    </div>
                    <div class="w-full">
                        <p class="text-sm font-light">This Year's Profit</p>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="flex items-center gap-3 mb-6 text-3xl font-serif tracking-wider" style="font-variant: small-caps;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                </svg>
                <h2 class="font-bold">Top Sellers</h2>
            </div>
            
            <div class="max-h-[600px] overflow-y-auto pr-2 pb-4 border-b-2 border-gray-200">
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-6">
                    @forelse ($topSellers as $product)
                        <div class="bg-white rounded-lg p-4 flex flex-col justify-between items-start shadow-[0_0_15px_rgba(0,0,0,0.1)] h-72">
                            <div class="w-full h-32 flex items-center justify-center mb-4">
                                @php $img = $product->productImage ? asset('images/products/' . $product->productImage) : asset('images/profile.png'); @endphp
                                <img src="{{ $img }}" alt="{{ $product->productName }}" class="max-h-full object-contain" onerror="this.src='{{ asset('images/profile.png') }}'">
                            </div>
                            <div class="w-full flex flex-col mt-auto">
                                <h4 class="font-serif font-bold text-left text-sm tracking-wider uppercase mb-1 truncate">{{ $product->productName }}</h4>
                                <p class="font-bold text-lg text-right whitespace-nowrap mb-2">₱{{ number_format($product->productPrice, 2) }}</p>
                                <span class="text-[10px] text-gray-500 self-start uppercase">{{ $product->productCalories }} Cal</span>
                            </div>
                        </div>
                    @empty
                    <div class="col-span-full flex justify-center items-center h-32 text-gray-500 text-xl font-serif">
                        No products found.
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="mt-8 mb-6 flex justify-center w-full">
                @if(isset($topSellers) && $topSellers instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    {{ $topSellers->links() }}
                @endif
            </div>
        </section>
    </main>
</body>
</html>