<!DOCTYPE html>
<html lang="en">
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Staff-Login</title>
</head>
<body class="bg-[#efefef] min-h-screen relative overflow-hidden flex flex-col items-center pt-16" style="font-family: 'Times New Roman', Times, serif;">

    <img src="{{ asset('images/food_rary.png') }}" alt="Food-Rary Logo" class="absolute top-4 left-4 w-[280px] object-contain opacity-90 z-0">
    <img src="{{ asset('images/food_rary.png') }}" alt="Food-Rary Logo" class="absolute -bottom-10 -right-4 w-[350px] object-contain opacity-90 z-0">

    <h1 class="text-4xl md:text-5xl tracking-widest z-10 text-center uppercase mt-4">
        Point of Sale System
    </h1>

    <h2 class="text-2xl tracking-widest mt-12 mb-2 z-10 uppercase">
        Staff - Login
    </h2>

    <div class="border-[2px] border-black bg-white w-[500px] px-8 py-6 z-10">
        @if ($errors->any())
            <div style="color: red;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="/login" method="POST" class="flex flex-col gap-5">
            @csrf
                <label class="block text-xl tracking-wider mb-1" style="font-variant: small-caps;">Username:</label>
                <input type="text" name="username" class="w-full border-[1.5px] border-black h-10 px-3 outline-none focus:ring-1 focus:ring-gray-400">
                <label class="block text-xl tracking-wider mb-1" style="font-variant: small-caps;">Password:</label>
                <input type="password" name="password" class="w-full border-[1.5px] border-black h-10 px-3 outline-none focus:ring-1 focus:ring-gray-400">
            <div class="flex justify-center mt-2">
                <button type="submit" class="bg-[#dcdcdc] text-black tracking-widest uppercase py-1 px-24 text-lg hover:bg-gray-300 transition-colors border border-transparent">
                    Login
                </button>
            </div>
        </form>
    </div>

</body>
</html>