<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <a href="{{ route('dashboard') }}" class="me-5 flex items-center justify-center space-x-2 rtl:space-x-reverse"
            wire:navigate>

            <div class="w-20 h-20 overflow-hidden rounded-full  flex items-center justify-center">
                <img src="{{ asset('img/logo.png') }}" alt="Logo Aplikasi Anda"
                    class="w-full h-25 object-cover object-center">
            </div>

        </a>




        <flux:navlist variant="outline">
            <flux:navlist.group :heading="__('Platform')" class="grid">
                <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                    wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                <flux:navlist.item icon="home" :href="route('payroll.show')"
                    :current="request()->routeIs('payroll.show')" wire:navigate>{{ __('Payroll') }}</flux:navlist.item>
            </flux:navlist.group>

        </flux:navlist>

        <flux:spacer />
        @role('Master')
            
        @php
        $masterOpen = request()->routeIs(['payroll.*', 'master-data.import-payroll-am.*', 'pieces.*']);
        @endphp

        <div class="w-full transition-all duration-75">
            <div class="flex items-center justify-between gap-2 cursor-pointer p-3 rounded border-b-2"
                onclick="toggleDropdown()">
                <span>Payroll</span>
                <svg id="icon-payroll" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                    class="size-5">
                    <path fill-rule="evenodd"
                        d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                        clip-rule="evenodd" />
                </svg>


            </div>
            <div id="dropdown-master" style="display: {{ $masterOpen ? 'block' : 'none' }}; padding-left: 20px;">
                <a href="{{ route('payroll.tabel') }}"
                    class="block py-2 text-sm text-zinc-500 hover:bg-zinc-800/5 hover:text-zinc-800 pl-3  rounded duration-75 my-1 {{ request()->routeIs('payroll.tabel') ? ' font-semibold' : '' }}">
                    Data Payroll
                </a>
                <a href="{{ route('payroll.manage') }}"
                    class="block py-2 text-sm text-zinc-500 hover:bg-zinc-800/5 hover:text-zinc-800 pl-3  rounded duration-75 my-1 {{ request()->routeIs('payroll.manage') ? ' font-semibold' : '' }}">
                    Management
                </a>
                <a href="{{ route('master-data.import-payroll-am.index') }}"
                    class="block py-2 text-sm text-zinc-500 hover:bg-zinc-800/5 hover:text-zinc-800 pl-3  rounded duration-75 my-1 {{ request()->routeIs('master-data.import-payroll-am.*') ? ' font-semibold' : '' }}">
                    Management Area Manager
                </a>

                <a href="{{ route('pieces.manage') }}"
                    class="block py-2 text-sm text-zinc-500 hover:bg-zinc-800/5 hover:text-zinc-800 pl-3  rounded duration-75 my-1 {{ request()->routeIs('pieces.*') ? ' font-semibold' : '' }}">
                    Pieces Management
                </a>
            </div>


        </div>



        <flux:navlist.item href="{{ route('user.manage') }}">
            {{ __('User Management') }}
        </flux:navlist.item>
        <flux:navlist.item href="{{ route('branches.manage') }}">
            {{ __('Branch Management') }}
        </flux:navlist.item>
        @endrole

        <!-- Desktop User Menu -->
        <flux:dropdown class="hidden lg:block" position="bottom" align="start">
            <flux:profile :name="auth()->user()->name" :initials="auth()->user()->initials()"
                icon:trailing="chevrons-up-down" />

            <flux:menu class="w-[220px]">
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    @fluxScripts
    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdown-master');
            const icon = document.getElementById('icon-payroll');

            if (dropdown.style.display === 'none' || dropdown.style.display === '') {
                dropdown.style.display = 'block';
                icon.classList.add('rotate-180');
            } else {
                dropdown.style.display = 'none';
                icon.classList.remove('rotate-180');
            }
        }
    </script>

</body>

</html>