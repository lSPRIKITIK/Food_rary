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

    <main class="px-6 py-8 max-w-full mx-auto">
        {{-- Header Section --}}
        <div class="flex justify-between items-end mb-8 border-b-2 border-gray-300 pb-4">
            <div>
                <a href="/ingredients" class="text-[#c22026] text-sm font-bold uppercase tracking-wider hover:underline mb-2 inline-block">← Back to Inventory</a>
                <h1 class="text-4xl font-bold font-serif tracking-wider uppercase" style="font-variant: small-caps;">
                    {{ $ingredient->ingredientName }} <span class="text-gray-400 text-2xl">({{ $ingredient->ingredientType }})</span>
                </h1>
                <p class="text-gray-500 mt-1 font-bold">Available Stock: <span class="text-black text-xl">{{ $ingredient->stockQty }}</span></p>
            </div>
        </div>

        {{-- Grid Layout: Side-by-Side but wider --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-10">
            
            {{-- LEFT SIDE: STOCK IN --}}
            <section>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-4 h-4 bg-[#78b833] rounded-full shadow-sm"></div>
                    <h2 class="text-2xl font-bold font-serif tracking-wide uppercase">Stock In History</h2>
                </div>
                
                <div class="bg-white rounded-lg shadow-lg overflow-x-auto border border-gray-200">
                    <table class="w-full text-left border-collapse min-w-max">
                        <thead>
                            <tr class="bg-gray-800 text-white font-serif uppercase tracking-widest text-[11px]">
                                <th class="p-4">Stock ID</th>
                                <th class="p-4">Date</th>
                                <th class="p-4">Supplier Name</th>
                                <th class="p-4 text-right">Unit Cost</th>
                                <th class="p-4 text-center">Qty Added</th>
                                <th class="p-4 text-center">Remaining</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($stockIns as $in)
                                <tr class="hover:bg-gray-50 transition-colors text-sm">
                                    <td class="p-4 font-bold text-gray-400">#{{ $in->stockID }}</td>
                                    {{-- whitespace-nowrap prevents the date from stacking --}}
                                    <td class="p-4 font-medium whitespace-nowrap text-gray-600">{{ \Carbon\Carbon::parse($in->deliveryDate)->format('M d, Y') }}</td>
                                    {{-- whitespace-nowrap prevents the supplier name from stacking --}}
                                    <td class="p-4 font-bold uppercase tracking-tight whitespace-nowrap text-gray-800">{{ $in->supplierName }}</td>
                                    <td class="p-4 text-right text-gray-600">₱{{ number_format($in->unitCost, 2) }}</td>
                                    <td class="p-4 text-center font-black text-[#78b833]">+{{ $in->quantity }}</td>
                                    <td class="p-4 text-center font-bold {{ $in->remainingQty == 0 ? 'text-red-500' : 'text-black' }}">
                                        {{ $in->remainingQty }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-6 text-center text-gray-400 font-serif italic">No batch records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $stockIns->links() }}</div>
            </section>

            {{-- RIGHT SIDE: STOCK OUT --}}
            <section>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-4 h-4 bg-[#c22026] rounded-full shadow-sm"></div>
                    <h2 class="text-2xl font-bold font-serif tracking-wide uppercase">Batch Status Tracker</h2>
                </div>

                <div class="bg-white rounded-lg shadow-lg overflow-x-auto border border-gray-200">
                    <table class="w-full text-left border-collapse min-w-max">
                        <thead>
                            <tr class="bg-gray-800 text-white font-serif uppercase tracking-widest text-[11px]">
                                <th class="p-4">Batch ID</th>
                                <th class="p-4">Supplier</th>
                                <th class="p-4 text-center">Deducted</th>
                                <th class="p-4 text-center">Remaining</th>
                                <th class="p-4 text-right">Last Updated</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($stockOuts as $out)
                                <tr class="hover:bg-gray-50 transition-colors text-sm">
                                    <td class="p-4 font-bold text-gray-400">#{{ $out->stockID }}</td>
                                    <td class="p-4 font-bold uppercase tracking-tight text-gray-800 whitespace-nowrap">{{ $out->supplierName }}</td>
                                    <td class="p-4 text-center font-black text-[#c22026]">-{{ $out->totalDeducted }}</td>
                                    <td class="p-4 text-center font-bold text-gray-700 bg-gray-50">{{ $out->remainingQty }}</td>
                                    <td class="p-4 text-right text-gray-600 font-medium whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($out->lastUpdated)->format('M d, Y') }} 
                                        <span class="text-[10px] opacity-70 ml-1">{{ \Carbon\Carbon::parse($out->lastUpdated)->format('h:i A') }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-6 text-center text-gray-400 font-serif italic">No activity yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $stockOuts->links() }}</div>
            </section>

        </div>
    </main>
</body>
</html>