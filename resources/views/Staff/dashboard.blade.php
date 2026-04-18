<!DOCTYPE html>
<html lang="en">
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
</head>
<body class="bg-[#f0f0f0] min-h-screen font-sans text-black">
    <header class="flex items-center justify-between px-8 py-3 border-b-2 border-gray-300 bg-[#f0f0f0]">
        <div class="flex items-center gap-10">
            <img src="{{ asset('images/food_rary.png') }}" alt="Food-Rary Logo" class="w-32 object-contain">
            <nav class="flex gap-6 mt-2" style="font-family: 'Times New Roman', Times, serif; font-variant: small-caps;">
                <a href="/dashboard" class="text-xl font-bold border-b border-black pb-0.5 tracking-wider">Dashboard</a>
                <a href="/menu" class="text-xl font-bold text-gray-700 hover:text-black tracking-wider">Menu</a>
            </nav>
        </div>
        <div class="flex-1 max-w-lg mx-8 mt-2">
            <div class="relative flex items-center w-full h-10 border-2 border-black bg-white rounded-sm overflow-hidden">
                <form action="/dashboard" method="GET" class="relative flex items-center w-full h-10 border-2 border-black bg-white rounded-sm overflow-hidden">
                    <input type="text" name="search" placeholder="Search Item" class="w-full h-full pl-4 pr-10 outline-none text-center font-serif text-lg" style="font-variant: small-caps;">
                    <button type="submit" class="absolute right-2 text-black p-1 hover:text-gray-500 transition-colors cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </button>
                </form> 
            </div>
        </div>
        <div class="flex items-center gap-4 mt-2">
            <span class="text-lg font-serif tracking-wide" style="font-variant: small-caps;">
                Counter: <span class="uppercase">{{ auth()->user()->username ?? 'John Doe' }}</span>
            </span>
            
            <div class="relative" id="profile-menu-container">
                
                <button id="avatar-btn" class="w-12 h-12 rounded-full border-[3px] border-[#f0a518] bg-gray-300 flex items-center justify-center overflow-hidden hover:opacity-80 transition cursor-pointer focus:outline-none">
                    <img src="{{ asset('images/profile.png') }}" alt="Avatar" class="w-full h-full object-cover">
                </button>

                <div id="profile-dropdown" class="hidden absolute right-0 mt-3 w-40 bg-white border-[2px] border-black shadow-lg z-50">
                    <div class="flex flex-col font-serif" style="font-variant: small-caps;">
                        <a href="/account" class="px-4 py-2 text-lg text-black hover:bg-gray-200 transition-colors border-b-[1.5px] border-gray-300 tracking-wider">Account</a>
                        <form action="/logout" method="POST" class="w-full m-0">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-lg text-[#c22026] hover:bg-gray-200 transition-colors tracking-wider font-bold cursor-pointer">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
                
            </div>
        </div>
    </header>
    <main class="px-8 py-6">
        <section class="mb-8 border-b-2 border-gray-300 pb-8">
            <div class="flex items-center gap-3 mb-6 text-3xl font-serif tracking-wider" style="font-variant: small-caps;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                </svg>
                <h2 class="font-bold">Sales Overview</h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4">
                <div class="bg-[#c22026] text-white rounded-lg p-4 flex flex-col justify-center items-center shadow-md h-28">
                    <h3 class="text-3xl font-bold tracking-wide">₱{{ number_format(10450) }}</h3>
                    <p class="text-sm font-light mt-1">Today's Sales</p>
                </div>
                <div class="bg-[#f06418] text-white rounded-lg p-4 flex flex-col justify-center items-center shadow-md h-28">
                    <h3 class="text-3xl font-bold tracking-wide">₱{{ number_format(6450) }}</h3>
                    <p class="text-sm font-light mt-1">Today's Profit</p>
                </div>
                <div class="bg-[#7d201e] text-white rounded-lg p-4 flex flex-col justify-center items-center shadow-md h-28">
                    <h3 class="text-3xl font-bold tracking-wide">₱{{ number_format(26250) }}</h3>
                    <p class="text-sm font-light mt-1">This Month's Sales</p>
                </div>
                <div class="bg-[#e99e1d] text-white rounded-lg p-4 flex flex-col justify-center items-center shadow-md h-28">
                    <h3 class="text-3xl font-bold tracking-wide">₱{{ number_format(16275) }}</h3>
                    <p class="text-sm font-light mt-1">This Month's Profit</p>
                </div>
                <div class="bg-[#78b833] text-white rounded-lg p-4 flex flex-col justify-center items-center shadow-md h-28">
                    <h3 class="text-3xl font-bold tracking-wide">₱{{ number_format(276867) }}</h3>
                    <p class="text-sm font-light mt-1">This Year's Sales</p>
                </div>
                <div class="bg-[#d26020] text-white rounded-lg p-4 flex flex-col justify-center items-center shadow-md h-28">
                    <h3 class="text-3xl font-bold tracking-wide">₱{{ number_format(171657) }}</h3>
                    <p class="text-sm font-light mt-1">This Year's Profit</p>
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
                    @for ($i = 0; $i < 18; $i++) <div class="bg-white rounded-lg p-4 flex flex-col justify-between items-center shadow-[0_0_15px_rgba(0,0,0,0.1)] h-72">
                        <div class="w-full h-32 flex items-center justify-center mb-4">
                            <img src="{{ asset('images/burger-placeholder.png') }}" alt="Product Image" class="max-h-full object-contain">
                        </div>
                        <div class="w-full flex flex-col items-center mt-auto">
                            <h4 class="font-serif font-bold text-center text-sm tracking-wider uppercase mb-1">
                                Double-Char {{ $i + 1 }}
                            </h4>
                            <span class="text-[10px] text-gray-500 self-start mb-2 uppercase">870 Cal</span>
                            <p class="font-bold text-lg mt-1">₱170</p>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
            <div class="flex justify-center items-center gap-3 mt-8 mb-6">
                <a href="#" class="text-gray-500 hover:text-gray-800 px-2 text-lg">&laquo;</a>
                <a href="#" class="w-10 h-10 rounded-full border border-gray-400 text-gray-500 flex justify-center items-center hover:bg-gray-200 transition">1</a>
                <a href="#" class="w-10 h-10 rounded-full border-[1.5px] border-blue-600 text-blue-600 flex justify-center items-center bg-transparent">2</a>
                <a href="#" class="w-10 h-10 rounded-full border border-gray-400 text-gray-500 flex justify-center items-center hover:bg-gray-200 transition">3</a>
                <a href="#" class="w-10 h-10 rounded-full border border-gray-400 text-gray-500 flex justify-center items-center hover:bg-gray-200 transition">4</a>
                <a href="#" class="text-gray-500 hover:text-gray-800 px-2 text-lg">&raquo;</a>
            </div>
        </section>

    </main>

</body>
</html>