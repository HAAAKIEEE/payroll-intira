<x-layouts.auth>
    <div class="flex min-h-screen w-full">
        <!-- Left Side - green Section -->
        <div class="relative hidden w-3/5 bg-gradient-to-br from-green-600 to-green-700 lg:flex lg:items-center lg:justify-center">
            <div class="flex flex-col items-center justify-center text-center space-y-6 px-12 text-white">
                <!-- Logo -->
                <div class="flex h-24 w-24 items-center justify-center rounded-full bg-white shadow-lg">
                    <img  src="{{ asset('img/logo.png') }}" class="w-30 text-green-600" />
                </div>
                
                <!-- App Name -->
                <h1 class="text-4xl font-bold">Sistem Payroll Intira</h1>
                
                <!-- Description -->
                <p class="text-green-100 text-sm leading-relaxed max-w-xs">
                    Lorem Ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                </p>
            </div>
            
            <!-- Footer Links -->
            <div class="absolute bottom-8 left-0 right-0 flex justify-center gap-8 text-sm text-green-100">
                <a href="#" class="hover:text-white transition">CREATED HERE</a>
                <a href="#" class="hover:text-white transition">COOKIES HERE</a>
            </div>

            <!-- Wave Divider -->
            <svg class="absolute right-0 top-0 h-full w-24 text-white" viewBox="0 0 100 600" preserveAspectRatio="none">
                <path d="M0,0 Q25,50 0,100 Q50,150 0,200 Q25,250 0,300 Q50,350 0,400 Q25,450 0,500 Q50,550 0,600 L100,600 L100,0 Z" fill="currentColor"/>
            </svg>
        </div>

        <!-- Right Side - White Form Section -->
        <div class="flex w-full items-center justify-center bg-white px-6 py-12 lg:w-3/5">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden flex justify-center mb-8">
                    <div class="flex  w-32 items-center justify-center rounded-full  ">
                        {{-- <x-app-logo-icon class="h-12 w-12 text-white" /> --}}
                    <img  src="{{ asset('img/logo.png') }}" class="w-40 text-green-600" />

                    </div>
                </div>

                <div class="flex flex-col gap-6">
                    <!-- Header -->
                    <div class="mb-2">
                        <h2 class="text-2xl font-bold text-gray-900 mb-1">{{ __('Log in to your account') }}</h2>
                        <p class="text-sm text-gray-600">{{ __('Enter your username and password below to log in') }}</p>
                    </div>

                    <!-- Session Status -->
                    <x-auth-session-status class="text-center" :status="session('status')" />

                    <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-5">
                        @csrf

                        <!-- Username -->
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('Username') }}
                            </label>
                            <input
                                id="username"
                                name="username"
                                type="text"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="username123"
                                value="{{ old('username') }}"
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 transition @error('username') border-red-500 @enderror"
                            />
                            @error('username')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="relative">
                            <div class="flex items-center justify-between mb-2">
                                <label for="password" class="block text-sm font-medium text-gray-700">
                                    {{ __('Password') }}
                                </label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-sm text-green-600 hover:text-green-700 hover:underline" wire:navigate>
                                        {{ __('Forgot password?') }}
                                    </a>
                                @endif
                            </div>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                required
                                autocomplete="current-password"
                                placeholder="{{ __('Enter your password') }}"
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 transition @error('password') border-red-500 @enderror"
                            />
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center">
                            <input
                                id="remember"
                                name="remember"
                                type="checkbox"
                                {{ old('remember') ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-500"
                            />
                            <label for="remember" class="ml-2 text-sm text-gray-700">
                                {{ __('Remember me') }}
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            data-test="login-button"
                            class="w-full rounded-full bg-green-600 px-6 py-3 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition shadow-md hover:shadow-lg"
                        >
                            {{ __('Log in') }}
                        </button>
                    </form>

                    @if (Route::has('register'))
                        <div class="text-sm text-center text-gray-600">
                            <span>{{ __('Don\'t have an account?') }}</span>
                            <a href="{{ route('register') }}" class="font-medium text-green-600 hover:text-green-700 hover:underline ml-1" wire:navigate>
                                {{ __('Sign up') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.auth>