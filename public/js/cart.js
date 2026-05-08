/* ==========================================
   1. GLOBAL STATE & CONSTANTS
   ========================================== */
let cart = [];
let activeCategory = 'all';

const categoryLabel = {
    charburger: 'Charburger', sandwich: 'Sandwich', meal: 'Meal',
    salad: 'Salad', side: 'Side', drink: 'Drink', promotion: 'Promotion',
};

/* ==========================================
   2. INITIALIZATION
   ========================================== */
document.addEventListener('DOMContentLoaded', () => {
    // 'products' is injected from the Blade template
    renderProducts(products); 
});

/* ==========================================
   3. MENU RENDERING & FILTERING
   ========================================== */
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

        const imgSrc = p.productImage ? `/images/products/${p.productImage}` : '/images/profile.png';

        card.innerHTML = `
            ${soldOutOverlay}
            <div class="w-full h-28 flex items-center justify-center mb-3">
                <img src="${imgSrc}" alt="${p.productName}" class="max-h-full object-contain" onerror="this.src='/images/profile.png'">
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

function filterProducts() { 
    applyFilters(); 
}

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

/* ==========================================
   4. CART MANAGEMENT LOGIC
   ========================================== */
function addToCart(id, name, price, category) {
    const productData = products.find(p => p.productID === id);
    if (!productData) return;

    // Calculate ingredient stock already sitting in the cart
    let ingredientUsage = {};
    cart.forEach(cartItem => {
        const cartProduct = products.find(p => p.productID === cartItem.productID);
        cartProduct.recipes.forEach(recipe => {
            const ingId = recipe.ingredientID;
            if (!ingredientUsage[ingId]) ingredientUsage[ingId] = 0;
            ingredientUsage[ingId] += (recipe.qtyUsed * cartItem.quantity);
        });
    });

    // Check if we have enough raw stock to add ONE MORE
    let canAdd = true;
    let missingIngredientName = "";

    for (let recipe of productData.recipes) {
        const ingId = recipe.ingredientID;
        const availableStock = recipe.ingredient.stockQty;
        const currentlyUsed = ingredientUsage[ingId] || 0;
        const neededForOneMore = recipe.qtyUsed;

        if ((currentlyUsed + neededForOneMore) > availableStock) {
            canAdd = false;
            missingIngredientName = recipe.ingredient.ingredientName;
            break;
        }
    }

    // Block addition if stock is insufficient
    if (!canAdd) {
        showNotification(`Not enough "${missingIngredientName}" left in stock to add another!`, false);
        return; 
    }

    // Add to cart or increment quantity
    let existingItem = cart.find(item => item.productID === id);
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push({ productID: id, productName: name, productPrice: parseFloat(price), category, quantity: 1, productImage: productData.productImage || null });
        }
    
    updateCartUI();
}

function decreaseQuantity(index) {
    if (cart[index].quantity > 1) {
        cart[index].quantity -= 1;
    } else {
        cart.splice(index, 1); 
    }
    updateCartUI();
}

function increaseQuantity(index) {
    const item = cart[index];
    addToCart(item.productID, item.productName, item.productPrice, item.category);
}

function removeItem(index) {
    cart.splice(index, 1);
    updateCartUI();
}

/* ==========================================
   5. UI UPDATES & NOTIFICATIONS
   ========================================== */
function updateCartUI() {
    const container = document.getElementById('cartItemsContainer');
    const summaryList = document.getElementById('summaryList');
    const checkoutBtn = document.getElementById('checkoutBtn');

    let netTotal = 0;

    if (cart.length === 0) {
        container.innerHTML = '<div class="text-center text-gray-400 mt-10 font-serif-custom italic" id="emptyCartMessage">Cart is empty</div>';
        summaryList.innerHTML = '';
        checkoutBtn.disabled = true;
        checkoutBtn.classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        checkoutBtn.disabled = false;
        checkoutBtn.classList.remove('opacity-50', 'cursor-not-allowed');

        let cartHtml = '';
        let summaryHtml = '';

        cart.forEach((item, index) => {
            netTotal += item.productPrice * item.quantity;

            // Main Cart Item Card with + / - Buttons
            cartHtml += `
                <div class="bg-white p-3 rounded border border-gray-300 shadow-sm flex items-center gap-3 mb-3">
                    <div class="w-16 h-16 bg-gray-100 rounded border border-gray-200 flex-shrink-0 flex flex-col items-center justify-center p-1">
                        <img src="${item.productImage ? '/images/products/' + item.productImage : '/images/profile.png' }" onerror="this.style.display='none'" class="h-9 object-contain">
                    </div>
                    <div class="flex-1 font-serif min-w-0">
                        <h5 class="font-bold text-[13px] tracking-wider uppercase leading-tight truncate" style="font-variant: small-caps;">${item.productName}</h5>
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest mt-1 mb-1">Type: ${item.category}</p>
                        <p class="text-[11px] font-bold">Product Price: ₱${item.productPrice.toFixed(0)}</p>
                    </div>
                    <div class="flex flex-col items-end justify-between h-full py-1">
                        <button onclick="removeItem(${index})" class="text-red-500 hover:text-red-700 text-[10px] font-bold uppercase mb-2">Remove</button>
                        <div class="flex items-center gap-2 border border-gray-300 rounded px-1 bg-gray-50 shadow-sm">
                            <button onclick="decreaseQuantity(${index})" class="text-lg font-bold px-2 text-gray-600 hover:text-[#c22026]">−</button>
                            <span class="font-bold text-sm font-sans w-4 text-center">${item.quantity}</span>
                            <button onclick="increaseQuantity(${index})" class="text-lg font-bold px-2 text-gray-600 hover:text-[#78b833]">+</button>
                        </div>
                    </div>
                </div>
            `;

            // Sidebar Summary Row
            summaryHtml += `
                <div class="flex justify-between text-xs font-bold mb-2 uppercase">
                    <span class="truncate max-w-[140px]">${item.productName}</span>
                    <span class="text-gray-300 mx-1">—</span>
                    <span class="whitespace-nowrap">₱${(item.productPrice * item.quantity).toFixed(0)} <span class="ml-1 text-[10px] text-gray-400">${item.quantity}x</span></span>
                </div>
            `;
        });

        container.innerHTML = cartHtml;
        summaryList.innerHTML = summaryHtml;
    }

    // Calculate and update totals
    const tax = netTotal * 0.12;
    const finalTotal = netTotal + tax;

    document.getElementById('netPrice').innerText = netTotal.toFixed(0);
    document.getElementById('taxPrice').innerText = tax.toFixed(0);
    document.getElementById('totalPrice').innerText = finalTotal.toFixed(0);
}

function showNotification(message, isSuccess = true) {
    const toast = document.createElement('div');
    toast.className = `fixed top-10 left-1/2 transform -translate-x-1/2 px-8 py-4 rounded-full shadow-2xl text-white font-bold z-50 transition-opacity duration-500 font-serif tracking-wider text-lg`;
    toast.classList.add(isSuccess ? 'bg-[#78b833]' : 'bg-[#c22026]');
    toast.innerText = message;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 500);
    }, 2000);
}

/* ==========================================
   6. CHECKOUT PROCESS
   ========================================== */
async function processCheckout() {
    if (cart.length === 0) return;

    const btn = document.getElementById('checkoutBtn');
    btn.disabled = true;
    btn.innerText = 'Processing...';

    try {
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
            showNotification('Purchase successful!', true);
            cart = [];
            updateCartUI();
            
            // Reload page after a delay to update stock overlays
            setTimeout(() => { window.location.reload(); }, 1500);
        } else {
            showNotification('Checkout failed: ' + result.message, false);
            btn.disabled = false;
            btn.innerText = 'Confirm Purchase';
        }
    } catch (error) {
        console.error("Error:", error);
        showNotification("An error occurred during checkout.", false);
        btn.disabled = false;
        btn.innerText = 'Confirm Purchase';
    }
} t