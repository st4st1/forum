<x-guest-layout>
    <div class="bg-white rounded-lg shadow-md p-8 max-w-md w-full mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Создание аккаунта</h1>
            <p class="text-gray-600 mt-2">Заполните форму для регистрации</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('ФИО')" class="block text-sm font-medium text-gray-700 mb-1" />
                <x-text-input id="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                              type="text" 
                              name="name" 
                              :value="old('name')" 
                              required 
                              autofocus 
                              autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-600" />
            </div>

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="block text-sm font-medium text-gray-700 mb-1" />
                <x-text-input id="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                              type="email" 
                              name="email" 
                              :value="old('email')" 
                              required 
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
                              autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" :value="__('Повторите пароль')" class="block text-sm font-medium text-gray-700 mb-1" />
                <x-text-input id="password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                              type="password"
                              name="password_confirmation" 
                              required 
                              autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-600" />
            </div>

            <x-primary-button class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                {{ __('Зарегистрироваться') }}
            </x-primary-button>

            <div class="text-center text-sm text-gray-600 mt-4">
                Уже есть аккаунт? 
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium hover:underline">
                    Войти
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>