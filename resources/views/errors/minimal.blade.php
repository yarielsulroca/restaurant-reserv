<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black flex flex-col justify-center">
        <!-- Decorative Elements -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-1/2 -right-1/2 w-full h-full">
                <div class="w-[500px] h-[500px] rounded-full bg-purple-500/30 blur-[100px]"></div>
            </div>
            <div class="absolute -bottom-1/2 -left-1/2 w-full h-full">
                <div class="w-[500px] h-[500px] rounded-full bg-orange-500/30 blur-[100px]"></div>
            </div>
        </div>

        <div class="relative">
            <div class="max-w-xl mx-auto px-6 text-center">
                <div class="glass rounded-3xl shadow-2xl p-8 border border-white/10 backdrop-blur-xl">
                    <div class="mb-4">
                        <h1 class="text-6xl font-bold gradient-text mb-4">@yield('code')</h1>
                        <p class="text-2xl text-white/90 mb-6">@yield('message')</p>
                    </div>
                    <a href="{{ url('/') }}" 
                       class="inline-flex items-center px-8 py-4 rounded-xl text-white font-medium 
                              hover:scale-[1.02] transition-all group
                              bg-gradient-to-r from-orange-600/80 to-yellow-600/80 hover:from-orange-500 hover:to-yellow-500">
                        Volver al inicio
                        <svg xmlns="http://www.w3.org/2000/svg" 
                             class="h-5 w-5 ml-2 group-hover:translate-x-1 transition-transform" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        .glass {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        .gradient-text {
            background: linear-gradient(to right, #FF5F6D, #FFC371);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</body>
</html>