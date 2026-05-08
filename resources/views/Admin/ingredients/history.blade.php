<!DOCTYPE html>
<html lang="en">
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock History - {{ $ingredient->ingredientName }}</title>
</head>
<body class="bg-[#f0f0f0] min-h-screen font-sans text-black">
    
    <x-header />

    <main class="px-8 py-8 max-w-7xl mx-auto">
        {{-- Header Section --}}
        <div class="flex justify-between items-end mb-6 border-b-2 border-gray-300 pb-4">
            <div>
                <a href="/ingredients" class="text-[#c22026] text-sm font-bold uppercase tracking-wider hover:underline mb-2 inline-block">← Back to Inventory</a>
                <h1 class="text-3xl font-bold font-serif tracking-wider uppercase" style="font-variant: small-caps;">
                    {{ $ingredient->ingredientName }} <span class="text-gray-400 text-2xl">({{ $ingredient->ingredientType }})</span>
                </h1>
                <p class="text-gray-500 mt-1 font-bold">Current Available Stock: <span class="text-black">{{ $ingredient->stockQty }}</span></p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            {{-- LEFT SIDE: STOCK IN --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-3 h-3 bg-[#78b833] rounded-full"></div>
                    <h2 class="text-xl font-bold font-serif tracking-wide uppercase">Stock In History</h2>
                </div>
                
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-800 text-white font-serif uppercase tracking-wider text-[11px]">
                                <th class="p-3">Stock ID</th>
                                <th class="p-3">Date</th>
                                <th class="p-3">Supplier Name</th>
                                <th class="p-3 text-right">Unit Cost</th>
                                <th class="p-3 text-center">Qty Added</th>
                                <th class="p-3 text-center">Remaining</th> </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($stockIns as $in)
                                <tr class="hover:bg-gray-50 transition-colors text-sm">
                                    <td class="p-3 font-bold text-gray-500">#{{ $in->stockID }}</td>
                                    <td class="p-3">{{ \Carbon\Carbon::parse($in->deliveryDate)->format('M d, Y') }}</td>
                                    <td class="p-3 font-bold" style="font-variant: small-caps;">{{ $in->supplierName }}</td>
                                    <td class="p-3 text-right text-gray-600">₱{{ number_format($in->unitCost, 2) }}</td>
                                    <td class="p-3 text-center font-black text-[#78b833]">+{{ $in->quantity }}</td>
                                    
                                    {{-- Display Remaining Qty (Turns red if empty) --}}
                                    <td class="p-3 text-center font-bold {{ $in->remainingQty == 0 ? 'text-red-500' : 'text-black' }}">
                                        {{ $in->remainingQty }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-6 text-center text-gray-500 font-serif italic">No Stock In records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- Pagination Links (In) --}}
                <div class="mt-4">{{ $stockIns->links() }}</div>
            </div>

            {{-- RIGHT SIDE: STOCK OUT --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-3 h-3 bg-[#c22026] rounded-full"></div>
                    <h2 class="text-xl font-bold font-serif tracking-wide uppercase">Stock Out Summary</h2>
                </div>

                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-800 text-white font-serif uppercase tracking-wider text-[11px]">
                                <th class="p-3">Date Used</th>
                                <th class="p-3 text-center">Batch ID</th> <th class="p-3 text-center">Total Qty Consumed</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($stockOuts as $out)
                                <tr class="hover:bg-gray-50 transition-colors text-sm">
                                    <td class="p-3 font-bold">{{ \Carbon\Carbon::parse($out->outDate)->format('M d, Y') }}</td>
                                    
                                    {{-- Show the exact Batch ID --}}
                                    <td class="p-3 text-center font-bold text-gray-500">#{{ $out->stockID }}</td> 
                                    
                                    <td class="p-3 text-center font-black text-[#c22026]">-{{ $out->totalUsed }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="p-6 text-center text-gray-500 font-serif italic">No ingredients have been used yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- Pagination Links (Out) --}}
                <div class="mt-4">{{ $stockOuts->links() }}</div>
            </div>

        </div>
    </main>
</body>
</html>