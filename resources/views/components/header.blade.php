<header class="flex items-center justify-between px-8 py-3 border-b-2 border-gray-300 bg-[#f0f0f0]">
    <div class="flex items-center gap-10">
        <img src="{{ asset('images/food_rary.png') }}" alt="Food-Rary Logo" class="w-32 object-contain">
        <nav class="flex gap-6 mt-2" style="font-family: 'Times New Roman', Times, serif; font-variant: small-caps;">
            <a href="/dashboard" class="text-xl font-bold {{ request()->is('dashboard') ? 'border-b border-black pb-0.5' : 'text-gray-700 hover:text-black' }} tracking-wider">Dashboard</a>
            <a href="/menu" class="text-xl font-bold {{ request()->is('menu') ? 'border-b border-black pb-0.5' : 'text-gray-700 hover:text-black' }} tracking-wider">Menu</a>
        </nav>
    </div>
    
    <div class="flex-1 max-w-lg mx-8 mt-2">
        @if(request()->is('menu'))
            {{-- Menu Search (Instant Javascript Filter) --}}
            <div class="relative flex items-center w-full h-10 border-2 border-black bg-white rounded-sm overflow-hidden">
                <input type="text" id="searchInput" onkeyup="filterProducts()" placeholder="Search Item" class="w-full h-full pl-4 pr-10 outline-none text-center font-serif text-lg" style="font-variant: small-caps;">
                <button class="absolute right-2 text-black p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </button>
            </div>
        @else
            {{-- Dashboard Search (PHP Form Submission) --}}
            <form action="/dashboard" method="GET" class="relative flex items-center w-full h-10 border-2 border-black bg-white rounded-sm overflow-hidden">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Top Sellers" class="w-full h-full pl-4 pr-10 outline-none text-center font-serif text-lg" style="font-variant: small-caps;">
                <button type="submit" class="absolute right-2 text-black p-1 hover:text-gray-500 transition-colors cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </button>
            </form>
        @endif
    </div>

    <div class="flex items-center gap-4 mt-2">
        <span class="text-lg font-serif tracking-wide" style="font-variant: small-caps;">
            Counter: <span class="uppercase">{{ auth()->user()->firstName ?? 'John Doe' }}</span>
        </span>
        
        <div class="relative" id="profile-menu-container">
            <button id="avatar-btn" class="w-12 h-12 rounded-full border-[3px] border-[#f0a518] bg-gray-300 flex items-center justify-center overflow-hidden hover:opacity-80 transition cursor-pointer focus:outline-none">
                <img src="{{ asset('images/profile.png') }}" alt="Avatar" class="w-full h-full object-cover">
            </button>
            <div id="profile-dropdown" class="hidden absolute right-0 mt-3 w-48 bg-white border-[2px] border-black shadow-lg z-50">
                <div class="flex flex-col font-serif" style="font-variant: small-caps;">
                    <a href="/account" class="px-4 py-2 text-lg text-black hover:bg-gray-200 transition-colors border-b-[1.5px] border-gray-300 tracking-wider">Account</a>
                    @if(auth()->check() && auth()->user()->position === 'Admin')
                        <a href="/products" class="px-4 py-2 text-lg text-[#f0a518] hover:bg-gray-200 transition-colors border-b-[1.5px] border-gray-300 tracking-wider font-bold">Manage Products</a>
                        <a href="/ingredients" class="px-4 py-2 text-lg text-[#78b833] hover:bg-gray-200 transition-colors border-b-[1.5px] border-gray-300 tracking-wider font-bold">Manage Inventory</a>
                    @endif
                    <form action="/logout" method="POST" class="w-full m-0">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-lg text-[#c22026] hover:bg-gray-200 transition-colors tracking-wider font-bold cursor-pointer">Log Out</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>