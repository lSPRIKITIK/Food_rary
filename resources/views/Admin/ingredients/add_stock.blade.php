<!DOCTYPE html>
<html lang="en">
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receive Stock - {{ $ingredient->ingredientName }}</title>
</head>
<body class="bg-[#f0f0f0] min-h-screen text-black font-sans">
    <x-header />

    <main class="max-w-3xl mx-auto py-10 px-6">
        <a href="/ingredients" class="text-[#c22026] text-sm font-bold uppercase tracking-wider hover:underline mb-4 inline-block">← Back to Inventory</a>
        
        <h1 class="text-3xl font-serif font-bold uppercase mb-2">Receive Stock: {{ $ingredient->ingredientName }}</h1>
        <p class="text-gray-600 mb-8">Record an incoming delivery to update the inventory ledger.</p>

        <form action="/ingredients/{{ $ingredient->ingredientID }}/add-stock" method="POST" class="bg-white p-8 rounded-lg shadow-md border border-gray-200">
            @csrf

            {{-- SECTION 1: SUPPLIER LOGIC --}}
            <h2 class="text-xl font-serif font-bold mb-4 border-b pb-2">1. Supplier Information</h2>
            
            <div class="mb-4">
                <label class="block font-bold text-sm mb-2">Supplier Selection</label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="supplier_mode" value="existing" checked onchange="toggleSupplierForm()" class="accent-[#f0a518]"> 
                        Use Existing Supplier
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="supplier_mode" value="new" onchange="toggleSupplierForm()" class="accent-[#f0a518]"> 
                        + Create New Supplier
                    </label>
                </div>
            </div>

            <div id="existingSupplierDiv" class="mb-6">
                <select name="existing_supplier_id" class="w-full border-gray-300 rounded p-2 border focus:ring-black">
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->supplierID }}">{{ $supplier->supplierName }} ({{ $supplier->supplierCity }})</option>
                    @endforeach
                    @if($suppliers->isEmpty())
                        <option disabled>No suppliers exist yet. Please create a new one.</option>
                    @endif
                </select>
            </div>

            <div id="newSupplierDiv" class="mb-6 hidden bg-gray-50 p-4 rounded border border-gray-200">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-sm mb-1">Company Name</label>
                        <input type="text" name="new_supplier_name" class="w-full border rounded p-2">
                    </div>
                    <div>
                        <label class="block font-bold text-sm mb-1">Contact Info</label>
                        <input type="text" name="new_supplier_contact" class="w-full border rounded p-2">
                    </div>
                    <div>
                        <label class="block font-bold text-sm mb-1">Street Address</label>
                        <input type="text" name="new_supplier_street" class="w-full border rounded p-2">
                    </div>
                    <div>
                        <label class="block font-bold text-sm mb-1">City</label>
                        <input type="text" name="new_supplier_city" class="w-full border rounded p-2">
                    </div>
                </div>
            </div>

            {{-- SECTION 2: DELIVERY LOGIC --}}
            <h2 class="text-xl font-serif font-bold mb-4 border-b pb-2 mt-8">2. Delivery Details</h2>

            <div class="grid grid-cols-3 gap-4 mb-8">
                <div>
                    <label class="block font-bold text-sm mb-1">Delivery Date</label>
                    <input type="date" name="deliveryDate" required value="{{ date('Y-m-d') }}" class="w-full border rounded p-2">
                </div>
                <div>
                    <label class="block font-bold text-sm mb-1">Quantity Received</label>
                    <input type="number" name="quantity" required min="1" class="w-full border rounded p-2">
                </div>
                <div>
                    <label class="block font-bold text-sm mb-1">Cost Per Unit (₱)</label>
                    <input type="number" name="unitCost" required min="0" step="0.01" class="w-full border rounded p-2">
                </div>
            </div>

            <button type="submit" class="w-full bg-[#f0a518] hover:bg-yellow-600 text-black font-bold py-3 rounded uppercase tracking-wider transition">
                Confirm
            </button>
        </form>
    </main>

    <script>
        function toggleSupplierForm() {
            const mode = document.querySelector('input[name="supplier_mode"]:checked').value;
            if (mode === 'new') {
                document.getElementById('existingSupplierDiv').classList.add('hidden');
                document.getElementById('newSupplierDiv').classList.remove('hidden');
            } else {
                document.getElementById('existingSupplierDiv').classList.remove('hidden');
                document.getElementById('newSupplierDiv').classList.add('hidden');
            }
        }
    </script>
</body>
</html>