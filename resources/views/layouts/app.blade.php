<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <!-- Top Bar with Logo and User Menu -->
    <div class="bg-white border-b border-gray-200">
        <div class="w-[70%] mx-auto py-2">
            <div class="flex justify-between h-16 items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('welcome') }}">
                        <x-application-logo class="block h-9 w-auto text-gray-800" />
                    </a>
                </div>

                <!-- User Menu -->
                <div class="flex items-center">
                    @auth
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center space-x-1 text-sm font-medium text-gray-700 hover:text-gray-900">
                                    <span>{{ Auth::user()->name }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.show')">
                                    {{ __('Личный кабинет') }}
                                </x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Выйти') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    @else
                        <div class="flex space-x-4">
                            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">
                                Войти
                            </a>
                            <a href="{{ route('register') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">
                                Регистрация
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="w-[70%] mx-auto flex gap-8 pt-6">
        <!-- Sidebar Navigation -->
        <div class="w-60 bg-white rounded-lg shadow-sm p-4 h-fit">
            <nav class="space-y-1">
                <a href="{{ route('main') }}" class="flex items-center px-3 py-2 text-gray-800 rounded-lg hover:bg-gray-100 transition-all duration-200 {{ request()->routeIs('main') ? 'bg-gray-100 font-medium' : '' }}">
                    <span class="mr-2">🏠</span>
                    {{ __('Главная') }}
                </a>
                <a href="{{ route('friends.index') }}" class="flex items-center px-3 py-2 text-gray-800 rounded-lg hover:bg-gray-100 transition-all duration-200 {{ request()->routeIs('friends.index') ? 'bg-gray-100 font-medium' : '' }}">
                    <span class="mr-2">👥</span>
                    {{ __('Друзья') }}
                </a>

                <a href="{{ route('communities.index') }}" class="flex items-center px-3 py-2 text-gray-800 rounded-lg hover:bg-gray-100 transition-all duration-200 {{ request()->routeIs('communities.index') ? 'bg-gray-100 font-medium' : '' }}">
                    <span class="mr-2">🏘️</span>
                    {{ __('Сообщества') }}
                </a>

                @if(auth()->check())
                <div class="text-xs text-gray-500 px-3 pt-4 pb-1 border-t border-gray-100 mt-3">УПРАВЛЕНИЕ</div>
                
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.users.index') }}" class="flex items-center px-3 py-2 text-gray-800 rounded-lg hover:bg-gray-100 transition-all duration-200 {{ request()->routeIs('admin.users.index') ? 'bg-gray-100 font-medium' : '' }}">
                    <span class="mr-2">👥</span>
                    {{ __('Пользователи') }}
                </a>
                @endif

                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'moderator')
                <a href="{{ route('admin.posts.index') }}" class="flex items-center px-3 py-2 text-gray-800 rounded-lg hover:bg-gray-100 transition-all duration-200 {{ request()->routeIs('admin.posts.index') ? 'bg-gray-100 font-medium' : '' }}">
                    <span class="mr-2">📝</span>
                    {{ __('Публикации') }}
                </a>
                @endif

                @if(auth()->user()->role === 'user')
                <a href="{{ route('posts.index') }}" class="flex items-center px-3 py-2 text-gray-800 rounded-lg hover:bg-gray-100 transition-all duration-200 {{ request()->routeIs('posts.index') ? 'bg-gray-100 font-medium' : '' }}">
                    <span class="mr-2">✏️</span>
                    {{ __('Мои записи') }}
                </a>
                @endif

                <div class="text-xs text-gray-500 px-3 pt-4 pb-1 border-t border-gray-100 mt-3">ЛИЧНОЕ</div>
                <a href="{{ route('favourites.index') }}" class="flex items-center px-3 py-2 text-gray-800 rounded-lg hover:bg-gray-100 transition-all duration-200 {{ request()->routeIs('favourites.index') ? 'bg-gray-100 font-medium' : '' }}">
                    <span class="mr-2">⭐</span>
                    {{ __('Избранное') }}
                </a>
                
                <a href="{{ route('users_stat') }}" class="flex items-center px-3 py-2 text-gray-800 rounded-lg hover:bg-gray-100 transition-all duration-200 {{ request()->routeIs('users_stat') ? 'bg-gray-100 font-medium' : '' }}">
                    <span class="mr-2">📊</span>
                    {{ __('Статистика') }}
                </a>
                @endif
            </nav>
        </div>

        <!-- Page Content -->
        <div class="flex-1 bg-white rounded-lg shadow-sm p-6">
            {{ $slot }}
        </div>
    </div>
</body>
</html>