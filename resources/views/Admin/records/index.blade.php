<!DOCTYPE html>
<html lang="en">
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Records - Food-Rary</title>
</head>
<body class="bg-[#f0f0f0] min-h-screen font-sans text-black">
    
    <x-header />

    <main class="px-8 py-8 max-w-7xl mx-auto">
        <div class="flex justify-between items-end mb-6 border-b-2 border-gray-300 pb-4">
            <div>
                <h1 class="text-3xl font-bold font-serif tracking-wider" style="font-variant: small-caps;">Sales Records</h1>
                <p class="text-gray-500 mt-1">View all transactions and sold items by date.</p>
            </div>

            {{-- Date Filter Form --}}
            <form action="/records" method="GET" class="flex items-center gap-3 bg-white p-2 rounded border border-gray-300 shadow-sm">
                <label for="date" class="font-bold text-sm text-gray-700 ml-2">Select Date:</label>
                {{-- onchange="this.form.submit()" makes it so you don't need a "Search" button! --}}
                <input type="date" name="date" id="date" value="{{ $selectedDate }}" onchange="this.form.submit()" 
                       class="border border-gray-300 rounded px-3 py-1 outline-none focus:border-black cursor-pointer">
            </form>
        </div>

        {{-- Daily Summary Cards --}}
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div class="bg-white p-5 rounded-lg shadow-md border-l-4 border-[#f0a518]">
                <h3 class="text-gray-500 font-bold text-sm uppercase tracking-wider">Sales of ({{ \Carbon\Carbon::parse($selectedDate)->format('M d, Y') }})</h3>
                <p class="text-3xl font-black mt-1">₱{{ number_format($dailyTotal, 2) }}</p>
            </div>
            <div class="bg-white p-5 rounded-lg shadow-md border-l-4 border-[#78b833]">
                <h3 class="text-gray-500 font-bold text-sm uppercase tracking-wider">Profit</h3>
                <p class="text-3xl font-black mt-1">₱{{ number_format($dailyProfit, 2) }}</p>
            </div>
        </div>

        {{-- Records Table --}}
        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-800 text-white font-serif uppercase tracking-wider text-sm">
                        <th class="p-4">Time</th>
                        <th class="p-4">Order No.</th>
                        <th class="p-4">Handled By</th>
                        <th class="p-4">Product Name</th>
                        <th class="p-4 text-center">Qty</th>
                        <th class="p-4 text-right">Unit Price</th>
                        <th class="p-4 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($records as $record)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-4 text-sm text-gray-600">{{ \Carbon\Carbon::parse($record->orderDate)->format('h:i A') }}</td>
                            <td class="p-4 font-bold">#{{ $record->orderID }}</td>
                            <td class="p-4 text-sm text-gray-500 font-bold uppercase tracking-wide">
                                {{ $record->lastName }}, {{ $record->firstName }}
                            </td>
                            
                            <td class="p-4 font-bold" style="font-variant: small-caps;">{{ $record->productName }}</td>
                            <td class="p-4 text-center font-bold">{{ $record->quantity }}</td>
                            <td class="p-4 text-right text-gray-600">₱{{ number_format($record->unitPrice, 2) }}</td>
                            <td class="p-4 text-right font-black text-[#c22026]">₱{{ number_format($record->subTotal, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-500 font-serif italic text-lg">
                                No items were sold on this date.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $records->links() }}
        </div>
    </main>

</body>
</html>