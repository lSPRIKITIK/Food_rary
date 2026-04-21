/* ─── Cart State ─── */
let cart = [];
let activeCategory = 'all';
let purchaseNo = Math.floor(Math.random() * 900) + 100;

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('purchaseNo').innerText = purchaseNo;
    renderProducts(products); // Uses the 'products' variable injected from Blade
});

const categoryLabel = {
    charburger: 'Charburger', sandwich: 'Sandwich', meal: 'Meal',
    salad: 'Salad', side: 'Side', drink: 'Drink', promotion: 'Promotion',
};

function renderProducts(list) {
    const grid = document.getElementById('productGrid');
    const noRes = document.getElementById('noResults');
    grid.innerHTML = '';

    if (list.length === 0) {
        noRes.classList.remove('hidden');
        return;
    }
    noRes.classList.add('hidden');

    list.forEach(p => {
        // Map database fields to our JS variables
        const isSoldOut = p.is_sold_out;
        const catName = p.categoryName ? p.categoryName.toLowerCase() : 'other';
        const displayCat = p.categoryName || categoryLabel[catName] || 'Other';

        const soldOutOverlay = isSoldOut ? `
            <div class="absolute inset-0 bg-white/60 flex items-center justify-center rounded-lg z-10">
                <span class="bg-red-600 text-white font-black text-lg px-3 py-1 border-2 border-black -rotate-12 inline-block tracking-widest">SOLD OUT</span>
            </div>` : '';

        const card = document.createElement('div');
        card.className = `product-card${isSoldOut ? ' sold-out' : ''} relative bg-white rounded-lg p-4 flex flex-col items-center shadow border border-gray-200 h-64 cursor-pointer`;
        
        if (!isSoldOut) {
            card.onclick = () => addToCart(p.productID, p.productName, p.productPrice, displayCat);
        }

        card.innerHTML = `
            ${soldOutOverlay}
            <div class="w-full h-28 flex items-center justify-center mb-3">
                <img src="/images/burger-placeholder.png" alt="${p.productName}" class="max-h-full object-contain">
            </div>
            <h4 class="font-serif font-bold text-center text-sm tracking-wider uppercase mb-1 w-full leading-tight h-10 overflow-hidden" style="font-variant: small-caps;">
                ${p.productName}
            </h4>
            <div class="w-full flex justify-between items-end mt-auto">
                <span class="text-[10px] text-gray-500 uppercase">${p.productCalories} Cal</span>
                <p class="font-bold text-lg">₱${parseFloat(p.productPrice).toLocaleString()}</p>
            </div>
        `;
        grid.appendChild(card);
    });
}

function filterByCategory(btn, category) {
    document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    activeCategory = category;
    applyFilters();
}

function filterProducts() { applyFilters(); }

function applyFilters() {
    const query = document.getElementById('searchInput').value.toLowerCase().trim();
    const filtered = products.filter(p => {
        const pCat = p.categoryName ? p.categoryName.toLowerCase() : 'other';
        const matchCat = activeCategory === 'all' || pCat.includes(activeCategory);
        const matchName = p.productName.toLowerCase().includes(query);
        return matchCat && matchName;
    });
    renderProducts(filtered);
}

function addToCart(id, name, price, category) {
    const existing = cart.find(i => i.productID === id);
    if (existing) {
        existing.quantity += 1;
    } else {
        cart.push({ productID: id, productName: name, productPrice: parseFloat(price), category, quantity: 1 });
    }
    updateCartUI();
}

function updateCartUI() {
    const container = document.getElementById('cartItemsContainer');
    const summaryList = document.getElementById('summaryList');
    const emptyMsg = document.getElementById('emptyCartMessage');
    const checkoutBtn = document.getElementById('checkoutBtn');

    container.innerHTML = '';
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

        const card = document.createElement('div');
        card.className = 'cart-item-enter bg-white p-3 rounded border border-gray-300 shadow-sm flex items-center gap-3 mb-3';
        card.innerHTML = `
            <div class="w-16 h-16 bg-gray-100 rounded border border-gray-200 flex-shrink-0 flex flex-col items-center justify-between overflow-hidden p-1">
                <img src="/images/burger-placeholder.png" class="h-9 object-contain">
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

        const row = document.createElement('div');
        row.className = 'flex justify-between text-xs font-bold mb-2 uppercase';
        row.innerHTML = `
            <span class="truncate max-w-[140px]">${item.productName}</span>
            <span class="text-gray-300 mx-1">—</span>
            <span class="whitespace-nowrap">₱${(item.productPrice * item.quantity).toFixed(0)} <span class="ml-1 text-[10px] text-gray-400">${item.quantity}x</span></span>
        `;
        summaryList.appendChild(row);
    });

    const tax = netTotal * 0.12;
    const finalTotal = netTotal + tax;

    document.getElementById('netPrice').innerText = netTotal.toFixed(0);
    document.getElementById('taxPrice').innerText = tax.toFixed(0);
    document.getElementById('totalPrice').innerText = finalTotal.toFixed(0);
}

function removeItem(index) {
    cart.splice(index, 1);
    updateCartUI();
}

async function processCheckout() {
    if (cart.length === 0) return;

    const btn = document.getElementById('checkoutBtn');
    btn.disabled = true;
    btn.innerText = 'Processing...';

    try {
        // Send actual request to the MenuController we built
        const response = await fetch('/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ cart: cart })
        });

        const result = await response.json();

        if (result.success) {
            alert('Purchase successful! Order has been saved to the database.');
            cart = [];
            updateCartUI();
            // Reload the page to reflect deducted ingredient stock
            window.location.reload();
        } else {
            alert('Checkout failed: ' + result.message);
            btn.disabled = false;
            btn.innerText = 'Confirm Purchase';
        }
    } catch (error) {
        console.error("Error:", error);
        alert("An error occurred during checkout.");
        btn.disabled = false;
        btn.innerText = 'Confirm Purchase';
    }
}