@php
    use Illuminate\Support\Facades\Route;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Restaurant API</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <style>
            .glass {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.18);
            }
            .gradient-text {
                background: linear-gradient(to right, #FF5F6D, #FFC371);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }
            .floating {
                animation: float 6s ease-in-out infinite;
            }
            @keyframes float {
                0% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
                100% { transform: translateY(0px); }
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-gray-900 via-gray-800 to-black min-h-screen">
        <!-- Decorative Elements -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-1/2 -right-1/2 w-full h-full">
                <div class="w-[500px] h-[500px] rounded-full bg-purple-500/30 blur-[100px]"></div>
            </div>
            <div class="absolute -bottom-1/2 -left-1/2 w-full h-full">
                <div class="w-[500px] h-[500px] rounded-full bg-orange-500/30 blur-[100px]"></div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="relative min-h-screen">
            <!-- Navigation -->
            <nav class="glass fixed w-full z-50">
                <div class="container mx-auto px-6 py-4">
                    <div class="flex justify-between items-center">
                        <div class="text-2xl font-bold gradient-text">Restaurant API</div>
                        <div class="flex items-center space-x-6">
                            <a href="/api/documentation" class="text-white/90 hover:text-white transition-colors">API Docs</a>
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="glass px-4 py-2 rounded-full text-white/90 hover:text-white transition-all hover:scale-105">
                                        Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="text-white/90 hover:text-white transition-colors">Login</a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="glass px-4 py-2 rounded-full text-white/90 hover:text-white transition-all hover:scale-105">
                                            Register
                                        </a>
                                    @endif
                                @endauth
                            @endif
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Hero Section -->
            <div class="relative pt-32 pb-20 px-6">
                <div class="container mx-auto">
                    <div class="flex flex-col lg:flex-row items-center gap-12">
                        <!-- Left Content -->
                        <div class="flex-1 text-center lg:text-left">
                            <h1 class="text-5xl lg:text-7xl font-bold text-white mb-8 leading-tight">
                                Gestión de Reservas
                                <span class="gradient-text">Inteligente</span>
                            </h1>
                            <p class="text-lg text-gray-300 mb-12 max-w-2xl">
                                Sistema moderno para la gestión de reservas de restaurantes. 
                                API REST completa con documentación detallada.
                            </p>
                            <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                                <a href="/api/documentation" 
                                   class="glass px-8 py-4 rounded-full text-white font-medium hover:scale-105 transition-all flex items-center justify-center group">
                                    <span>Ver Documentación</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </a>
                                <a href="#features" 
                                   class="px-8 py-4 rounded-full text-white font-medium border border-white/20 hover:bg-white/10 transition-all flex items-center justify-center">
                                    Explorar Características
                                </a>
                            </div>
                        </div>
                        <!-- Right Image -->
                        <div class="flex-1 floating">
                            <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=2070" 
                                 alt="Restaurant" 
                                 class="rounded-2xl shadow-2xl w-full max-w-lg mx-auto glass p-2">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div id="features" class="py-20 px-6">
                <div class="container mx-auto">
                    <h2 class="text-4xl font-bold text-center text-white mb-16">
                        Características <span class="gradient-text">Principales</span>
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Feature Cards -->
                        @foreach([
                            [
                                'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                                'title' => 'Tiempo Real',
                                'description' => 'Gestión de reservas y mesas en tiempo real'
                            ],
                            [
                                'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                                'title' => 'API REST',
                                'description' => 'Integración sencilla con cualquier frontend'
                            ],
                            [
                                'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z',
                                'title' => 'Seguridad',
                                'description' => 'Autenticación y autorización robusta'
                            ]
                        ] as $feature)
                        <div class="glass rounded-2xl p-8 hover:scale-105 transition-all cursor-pointer group">
                            <div class="w-14 h-14 rounded-full glass flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feature['icon'] }}" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-4">{{ $feature['title'] }}</h3>
                            <p class="text-gray-300">{{ $feature['description'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="py-20 px-6">
                <div class="container mx-auto">
                    <div class="glass rounded-2xl p-12">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                            @foreach([
                                ['number' => '99.9%', 'label' => 'Uptime'],
                                ['number' => '24/7', 'label' => 'Soporte'],
                                ['number' => '100K+', 'label' => 'Reservas Procesadas']
                            ] as $stat)
                            <div>
                                <div class="text-4xl font-bold gradient-text mb-2">{{ $stat['number'] }}</div>
                                <div class="text-white/70">{{ $stat['label'] }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>