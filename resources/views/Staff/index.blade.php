<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Food-Rary</title>
    <style>
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }

        .product-card {
            transition: box-shadow 0.2s ease, transform 0.15s ease;
        }
        .product-card:hover {
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
            transform: translateY(-2px);
        }
        .product-card.sold-out {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .product-card.sold-out:hover {
            transform: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.07);
        }

        .category-btn.active {
            outline: 2px solid #000;
            outline-offset: 2px;
        }

        .cart-item-enter {
            animation: slideIn 0.2s ease-out;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(20px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        #checkoutBtn:not(:disabled):hover {
            background-color: #c88a10;
        }
    </style>
</head>
<body class="bg-[#e9e9e9] min-h-screen font-sans text-black flex flex-col h-screen overflow-hidden">

    <!-- ===== TOP NAVIGATION HEADER ===== -->
    <header class="flex items-center justify-between px-8 py-3 border-b-2 border-gray-300 bg-white flex-shrink-0 z-10">

        <!-- Logo + Nav -->
        <div class="flex items-center gap-10">
            <img src="images/food_rary.png" alt="Food-Rary Logo" class="w-32 object-contain">
            <nav class="flex gap-6 mt-2" style="font-family: 'Times New Roman', Times, serif; font-variant: small-caps;">
                <a href="/dashboard" class="text-xl font-bold text-gray-700 hover:text-black tracking-wider">Dashboard</a>
                <a href="/menu"      class="text-xl font-bold border-b border-black pb-0.5 tracking-wider">Menu</a>
            </nav>
        </div>

        <!-- Search Bar -->
        <div class="flex-1 max-w-lg mx-8 mt-1">
            <div class="relative flex items-center w-full h-10 border-2 border-black bg-white rounded-sm overflow-hidden">
                <input
                    type="text"
                    id="searchInput"
                    placeholder="Search Item"
                    oninput="filterProducts()"
                    class="w-full h-full pl-4 pr-10 outline-none text-center font-serif text-lg" style="font-variant: small-caps;"
                >
                <button class="absolute right-2 text-black p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- User Info -->
        <div class="flex items-center gap-4 mt-1">
            <span class="text-lg font-serif tracking-wide" style="font-variant: small-caps;">
                Counter: <span class="uppercase font-bold">John Doe</span>
            </span>
            <div class="w-12 h-12 rounded-full border-[3px] border-[#f0a518] bg-gray-300 overflow-hidden">
                <img src="images/profile.png" alt="Avatar" class="w-full h-full object-cover">
            </div>
        </div>
    </header>

    <!-- ===== MAIN CONTENT ===== -->
    <main class="flex flex-1 overflow-hidden">

        <!-- ===== LEFT: Menu Grid ===== -->
        <div class="flex-1 flex flex-col p-6 overflow-hidden">

            <!-- Category Filter Pills -->
            <div class="flex gap-3 mb-6 overflow-x-auto pb-2 flex-shrink-0 font-bold text-white text-sm tracking-wider uppercase">
                <button class="category-btn active bg-[#f24d26] px-4 py-2 rounded-lg shadow whitespace-nowrap" data-category="all"          onclick="filterByCategory(this, 'all')">All Item</button>
                <button class="category-btn        bg-[#f06418] px-4 py-2 rounded-lg shadow whitespace-nowrap" data-category="promotion"     onclick="filterByCategory(this, 'promotion')">Promotions</button>
                <button class="category-btn        bg-[#e99e1d] px-4 py-2 rounded-lg shadow whitespace-nowrap" data-category="charburger"    onclick="filterByCategory(this, 'charburger')">Charburgers</button>
                <button class="category-btn        bg-[#7d201e] px-4 py-2 rounded-lg shadow whitespace-nowrap" data-category="sandwich"      onclick="filterByCategory(this, 'sandwich')">Sandwiches</button>
                <button class="category-btn        bg-[#78b833] px-4 py-2 rounded-lg shadow whitespace-nowrap" data-category="meal"          onclick="filterByCategory(this, 'meal')">Meals</button>
                <button class="category-btn        bg-[#f0a518] px-4 py-2 rounded-lg shadow whitespace-nowrap" data-category="salad"         onclick="filterByCategory(this, 'salad')">Salads</button>
                <button class="category-btn        bg-[#c22026] px-4 py-2 rounded-lg shadow whitespace-nowrap" data-category="side"          onclick="filterByCategory(this, 'side')">Sides</button>
                <button class="category-btn        bg-[#d26020] px-4 py-2 rounded-lg shadow whitespace-nowrap" data-category="drink"         onclick="filterByCategory(this, 'drink')">Drinks</button>
            </div>

            <!-- Products Grid -->
            <div class="flex-1 overflow-y-auto pr-1 pb-4">
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-5" id="productGrid">
                    <!-- Products injected by JS -->
                </div>
                <div id="noResults" class="hidden text-center text-gray-400 font-serif-custom italic mt-16 text-lg">No items found.</div>
            </div>
        </div>

        <!-- ===== RIGHT: Cart / Cashier Panel ===== -->
        <div class="w-[400px] bg-[#e9e9e9] border-l-2 border-gray-300 flex flex-col shadow-[-5px_0_15px_rgba(0,0,0,0.05)] z-10 flex-shrink-0">

            <!-- Purchase Number Header -->
            <div class="p-6 text-center border-b border-gray-300 bg-[#e9e9e9]">
                <h2 class="text-2xl font-bold font-serif tracking-wider">Purchase No. <span id="purchaseNo">1</span></h2>
            </div>

            <!-- Cart Items List -->
            <div class="flex-1 overflow-y-auto p-4" id="cartItemsContainer">
                <div class="text-center text-gray-400 mt-10 font-serif-custom italic" id="emptyCartMessage">Cart is empty</div>
            </div>

            <!-- Payment Summary & Checkout -->
            <div class="bg-white p-6 border-t border-gray-300 flex flex-col max-h-[46vh]">
                <h3 class="font-bold text-center mb-4 font-serif text-base tracking-wide">Payment Summary</h3>

                <!-- Scrollable summary rows -->
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

                <button
                    id="checkoutBtn"
                    onclick="processCheckout()"
                    class="w-full bg-[#f0a518] text-black font-bold py-3 rounded-lg border-2 border-black shadow transition-colors font-serif tracking-wide text-lg opacity-50 cursor-not-allowed"
                    disabled
                >
                    Confirm Purchase
                </button>
            </div>
        </div>

    </main>

    <!-- ===== JAVASCRIPT ===== -->
    <script>
        /* ─── Product Data ─── */
        const products = [
            { id: 1,  name: 'BBQ Chicken Salad',    price: 135, calories: '750',       category: 'salad',      sold_out: false },
            { id: 2,  name: 'Double-Char Meal',      price: 235, calories: '1170-1550', category: 'meal',       sold_out: false },
            { id: 3,  name: 'Cookies & Cream Shake', price: 85,  calories: '800',       category: 'drink',      sold_out: false },
            { id: 4,  name: 'Santa Barbara Char',    price: 135, calories: '1210',      category: 'charburger', sold_out: false },
            { id: 5,  name: 'Double-Char',           price: 170, calories: '870',       category: 'charburger', sold_out: false },
            { id: 6,  name: 'Classic Charburger',    price: 120, calories: '680',       category: 'charburger', sold_out: false },
            { id: 7,  name: 'Guacamole Burger',      price: 150, calories: '950',       category: 'charburger', sold_out: true  },
            { id: 8,  name: 'Turkey Club Sandwich',  price: 145, calories: '720',       category: 'sandwich',   sold_out: false },
            { id: 9,  name: 'BLT Sandwich',          price: 110, calories: '580',       category: 'sandwich',   sold_out: false },
            { id: 10, name: 'Garden Salad',          price: 95,  calories: '320',       category: 'salad',      sold_out: false },
            { id: 11, name: 'Caesar Salad',          price: 115, calories: '410',       category: 'salad',      sold_out: false },
            { id: 12, name: 'Seasoned Fries',        price: 75,  calories: '450',       category: 'side',       sold_out: false },
            { id: 13, name: 'Onion Rings',           price: 80,  calories: '520',       category: 'side',       sold_out: false },
            { id: 14, name: 'Cola Float',            price: 65,  calories: '310',       category: 'drink',      sold_out: false },
            { id: 15, name: 'Mango Shake',           price: 75,  calories: '360',       category: 'drink',      sold_out: false },
            { id: 16, name: 'Double Meal Deal',      price: 275, calories: '1800',      category: 'promotion',  sold_out: false },
            { id: 17, name: 'Family Meal Pack',      price: 450, calories: '3200',      category: 'promotion',  sold_out: false },
            { id: 18, name: 'Charburger Meal',       price: 195, calories: '1300',      category: 'meal',       sold_out: false },
            { id: 19, name: 'Salad Meal Combo',      price: 185, calories: '890',       category: 'meal',       sold_out: false },
            { id: 20, name: 'Coleslaw',              price: 55,  calories: '210',       category: 'side',       sold_out: true  },
        ];

        /* ─── Cart State ─── */
        let cart = [];
        let activeCategory = 'all';
        let purchaseNo = Math.floor(Math.random() * 900) + 100;
        document.getElementById('purchaseNo').innerText = purchaseNo;

        /* ─── Category Map for display ─── */
        const categoryLabel = {
            charburger: 'Charburger',
            sandwich:   'Sandwich',
            meal:       'Meal',
            salad:      'Salad',
            side:       'Side',
            drink:      'Drink',
            promotion:  'Promotion',
        };

        /* ─── Render Products ─── */
        function renderProducts(list) {
            const grid    = document.getElementById('productGrid');
            const noRes   = document.getElementById('noResults');
            grid.innerHTML = '';

            if (list.length === 0) {
                noRes.classList.remove('hidden');
                return;
            }
            noRes.classList.add('hidden');

            list.forEach(p => {
                const soldOutOverlay = p.sold_out ? `
                    <div class="absolute inset-0 bg-white/60 flex items-center justify-center rounded-lg z-10">
                        <span class="bg-red-600 text-white font-black text-lg px-3 py-1 border-2 border-black -rotate-12 inline-block tracking-widest">SOLD OUT</span>
                    </div>` : '';

                const clickAttr = p.sold_out
                    ? ''
                    : `onclick="addToCart(${p.id}, '${p.name.replace(/'/g,"\\'")}', ${p.price}, '${categoryLabel[p.category] || p.category}')"`;

                const card = document.createElement('div');
                card.className = `product-card${p.sold_out ? ' sold-out' : ''} relative bg-white rounded-lg p-4 flex flex-col items-center shadow border border-gray-200 h-64 cursor-pointer`;
                card.dataset.name     = p.name.toLowerCase();
                card.dataset.category = p.category;
                card.setAttribute('onclick', p.sold_out ? '' : `addToCart(${p.id}, '${p.name.replace(/'/g,"\\'")}', ${p.price}, '${categoryLabel[p.category] || p.category}')`);

                card.innerHTML = `
                    ${soldOutOverlay}
                    <div class="w-full h-28 flex items-center justify-center mb-3">
                        <img src="images/burger-placeholder.png" alt="${p.name}" class="max-h-full object-contain">
                    </div>
                    <h4 class="font-serif font-bold text-center text-sm tracking-wider uppercase mb-1 w-full leading-tight h-10 overflow-hidden" style="font-variant: small-caps;">
                        ${p.name}
                    </h4>
                    <div class="w-full flex justify-between items-end mt-auto">
                        <span class="text-[10px] text-gray-500 uppercase">${p.calories} Cal</span>
                        <p class="font-bold text-lg">₱${p.price.toLocaleString()}</p>
                    </div>
                `;

                grid.appendChild(card);
            });
        }

        /* ─── Filter by Category ─── */
        function filterByCategory(btn, category) {
            document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            activeCategory = category;
            applyFilters();
        }

        /* ─── Filter by Search ─── */
        function filterProducts() { applyFilters(); }

        function applyFilters() {
            const query = document.getElementById('searchInput').value.toLowerCase().trim();
            const filtered = products.filter(p => {
                const matchCat  = activeCategory === 'all' || p.category === activeCategory;
                const matchName = p.name.toLowerCase().includes(query);
                return matchCat && matchName;
            });
            renderProducts(filtered);
        }

        /* ─── Add to Cart ─── */
        function addToCart(id, name, price, category) {
            const existing = cart.find(i => i.productID === id);
            if (existing) {
                existing.quantity += 1;
            } else {
                cart.push({ productID: id, productName: name, productPrice: parseFloat(price), category, quantity: 1 });
            }
            updateCartUI();
        }

        /* ─── Update Cart UI ─── */
        function updateCartUI() {
            const container    = document.getElementById('cartItemsContainer');
            const summaryList  = document.getElementById('summaryList');
            const emptyMsg     = document.getElementById('emptyCartMessage');
            const checkoutBtn  = document.getElementById('checkoutBtn');

            container.innerHTML   = '';
            summaryList.innerHTML = '';

            if (cart.length === 0) {
                emptyMsg.style.display = 'block';
                container.appendChild(emptyMsg);
                checkoutBtn.disabled = true;
                checkoutBtn.classList.add('opacity-50', 'cursor-not-allowed');
                checkoutBtn.classList.remove('cursor-pointer');
            } else {
                emptyMsg.style.display = 'none';
                checkoutBtn.disabled = false;
                checkoutBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                checkoutBtn.classList.add('cursor-pointer');
            }

            let netTotal = 0;

            cart.forEach((item, index) => {
                netTotal += item.productPrice * item.quantity;

                /* Cart item card */
                const card = document.createElement('div');
                card.className = 'cart-item-enter bg-white p-3 rounded border border-gray-300 shadow-sm flex items-center gap-3 mb-3';
                card.innerHTML = `
                    <div class="w-16 h-16 bg-gray-100 rounded border border-gray-200 flex-shrink-0 flex flex-col items-center justify-between overflow-hidden p-1">
                        <img src="images/burger-placeholder.png" class="h-9 object-contain">
                        <span class="text-[8px] text-gray-500 font-bold uppercase text-center leading-tight tracking-tight truncate w-full text-center">${item.productName}</span>
                    </div>
                    <div class="flex-1 font-serif min-w-0">
                        <h5 class="font-bold text-sm tracking-wider uppercase leading-tight truncate" style="font-variant: small-caps;">${item.productName}</h5>
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest mb-1">Type: ${item.category}</p>
                        <p class="text-xs font-bold">Product Price: ₱${item.productPrice.toFixed(0)}</p>
                    </div>
                    <div class="flex flex-col items-end gap-2 flex-shrink-0">
                        <button onclick="removeItem(${index})" class="text-red-500 hover:text-red-700 text-xs font-bold uppercase transition-colors">Remove</button>
                        <div class="font-bold text-lg font-serif">${item.quantity}x</div>
                    </div>
                `;
                container.appendChild(card);

                /* Summary row */
                const row = document.createElement('div');
                row.className = 'flex justify-between text-xs font-bold mb-2 uppercase';
                row.innerHTML = `
                    <span class="truncate max-w-[140px]">${item.productName}</span>
                    <span class="text-gray-300 mx-1">—</span>
                    <span class="whitespace-nowrap">₱${(item.productPrice * item.quantity).toFixed(0)} <span class="ml-1 text-[10px] text-gray-400">${item.quantity}x</span></span>
                `;
                summaryList.appendChild(row);
            });

            const tax        = netTotal * 0.12;
            const finalTotal = netTotal + tax;

            document.getElementById('netPrice').innerText   = netTotal.toFixed(0);
            document.getElementById('taxPrice').innerText   = tax.toFixed(0);
            document.getElementById('totalPrice').innerText = finalTotal.toFixed(0);
        }

        /* ─── Remove Item ─── */
        function removeItem(index) {
            cart.splice(index, 1);
            updateCartUI();
        }

        /* ─── Checkout ─── */
        async function processCheckout() {
            if (cart.length === 0) return;

            const btn = document.getElementById('checkoutBtn');
            btn.disabled    = true;
            btn.innerText   = 'Processing...';

            /* Simulate API call (replace with real fetch for backend) */
            await new Promise(r => setTimeout(r, 800));

            alert('Purchase successful! Order has been placed.');
            cart = [];
            purchaseNo++;
            document.getElementById('purchaseNo').innerText = purchaseNo;
            updateCartUI();

            btn.disabled  = false;
            btn.innerText = 'Confirm Purchase';
        }

        /* ─── Init ─── */
        renderProducts(products);
    </script>
</body>
</html>
