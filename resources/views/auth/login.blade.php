<x-guest-layout>
    <div class="bg-white rounded-lg shadow-md p-8 max-w-md w-full mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Вход в аккаунт</h1>
            <p class="text-gray-600 mt-2">Введите свои данные для входа</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4 p-4 bg-blue-50 text-blue-800 rounded-lg" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="block text-sm font-medium text-gray-700 mb-1" />
                <x-text-input id="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
                              type="email" 
                              name="email" 
                              :value="old('email')" 
                              required 
                              autofocus 
                              autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Пароль')" class="block text-sm font-medium text-gray-700 mb-1" />
                <x-text-input id="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                              type="password"
                              name="password"
                              required 
                              autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Запомнить меня') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-blue-600 hover:text-blue-800 hover:underline" href="{{ route('password.request') }}">
                        {{ __('Забыли пароль?') }}
                    </a>
                @endif
            </div>

            <x-primary-button class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                {{ __('Войти') }}
            </x-primary-button>

            <div class="text-center text-sm text-gray-600 mt-4">
                Нет аккаунта? 
                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-medium hover:underline">
                    Зарегистрироваться
                </a>
            </div>

            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">Или войти через</span>
                </div>
            </div>

            <div class="flex justify-center">
                <script async src="https://telegram.org/js/telegram-widget.js?22"
                        data-telegram-login="{{ config('services.telegram.bot_name') }}"
                        data-size="large"
                        data-radius="10"
                        data-auth-url="{{ route('auth.telegram') }}"
                        data-request-access="write"></script>
            </div>
        </form>
    </div>
</x-guest-layout>