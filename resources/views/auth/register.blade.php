<x-guest-layout>
    <!-- Reutilizamos el mismo fondo del welcome -->
    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black flex flex-col justify-center">
        <!-- Decorative Elements del welcome -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-1/2 -right-1/2 w-full h-full">
                <div class="w-[500px] h-[500px] rounded-full bg-purple-500/30 blur-[100px]"></div>
            </div>
            <div class="absolute -bottom-1/2 -left-1/2 w-full h-full">
                <div class="w-[500px] h-[500px] rounded-full bg-orange-500/30 blur-[100px]"></div>
            </div>
        </div>

        <!-- Modal Register Box -->
        <div class="relative sm:max-w-md sm:mx-auto w-full px-6">
            <!-- Efecto de superposición modal -->
            <div class="glass rounded-3xl shadow-2xl overflow-hidden border border-white/10 backdrop-blur-xl 
                        transform transition-all duration-500 hover:scale-[1.02]">
                <!-- Logo Section con borde inferior suave -->
                <div class="p-6 border-b border-white/10">
                    <a href="/" class="flex justify-center items-center space-x-2">
                        <h1 class="text-2xl font-bold gradient-text">Crear Cuenta</h1>
                    </a>
                </div>

                <div class="p-8">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Name -->
                        <div class="space-y-2">
                            <x-input-label for="name" :value="__('Name')" class="text-white/90" />
                            <x-text-input id="name" 
                                class="block w-full rounded-xl bg-white/5 border-white/10 text-white focus:border-orange-500 focus:ring-orange-500" 
                                type="text" 
                                name="name" 
                                :value="old('name')" 
                                required 
                                autofocus 
                                autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email Address -->
                        <div class="mt-6 space-y-2">
                            <x-input-label for="email" :value="__('Email')" class="text-white/90" />
                            <x-text-input id="email" 
                                class="block w-full rounded-xl bg-white/5 border-white/10 text-white focus:border-orange-500 focus:ring-orange-500" 
                                type="email" 
                                name="email" 
                                :value="old('email')" 
                                required 
                                autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mt-6 space-y-2">
                            <x-input-label for="password" :value="__('Password')" class="text-white/90" />
                            <x-text-input id="password" 
                                class="block w-full rounded-xl bg-white/5 border-white/10 text-white focus:border-orange-500 focus:ring-orange-500" 
                                type="password" 
                                name="password" 
                                required 
                                autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="mt-6 space-y-2">
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-white/90" />
                            <x-text-input id="password_confirmation" 
                                class="block w-full rounded-xl bg-white/5 border-white/10 text-white focus:border-orange-500 focus:ring-orange-500" 
                                type="password" 
                                name="password_confirmation" 
                                required 
                                autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <!-- Register Button -->
                        <div class="mt-8">
                            <button type="submit" 
                                class="w-full glass px-8 py-4 rounded-xl text-white font-medium 
                                       hover:scale-[1.02] transition-all flex items-center justify-center group
                                       bg-gradient-to-r from-orange-600/80 to-yellow-600/80 hover:from-orange-500 hover:to-yellow-500">
                                {{ __('Register') }}
                                <svg xmlns="http://www.w3.org/2000/svg" 
                                     class="h-5 w-5 ml-2 group-hover:translate-x-1 transition-transform" 
                                     fill="none" 
                                     viewBox="0 0 24 24" 
                                     stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </button>
                        </div>

                        <!-- Login Link -->
                        <div class="mt-6 text-center">
                            <span class="text-white/70">¿Ya tienes cuenta?</span>
                            <a href="{{ route('login') }}" 
                               class="text-orange-400 hover:text-orange-300 transition-colors ml-1 font-medium">
                                Inicia Sesión
                            </a>
                        </div>
                    </form>
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
</x-guest-layout>