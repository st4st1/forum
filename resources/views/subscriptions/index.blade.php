<x-app-layout>
    <div class="bg-gray-100 min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Мои подписки</h1>
                <a href="{{ route('profile.show') }}" 
                   class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    Назад к профилю
                </a>
            </div>

            <!-- Поиск по подпискам -->
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <form action="#" method="GET" class="flex">
                    <input type="text" name="search" 
                           class="flex-grow px-4 py-2 border rounded-l-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="Поиск по подпискам">
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-500 text-white rounded-r-lg hover:bg-blue-600">
                        Найти
                    </button>
                </form>
            </div>

            <!-- Список подписок -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                @forelse($subscriptions as $author)
                    <div class="flex items-center justify-between p-4 border-b hover:bg-gray-50 transition-colors">
                        <div class="flex items-center">
                            <img src="{{ $author->avatar_url }}" 
                                 class="w-12 h-12 rounded-full mr-4"
                                 alt="{{ $author->name }}">
                            <div>
                                <a href="{{ route('profile.public', $author) }}" 
                                   class="font-semibold hover:underline">{{ $author->name }}</a>
                                <p class="text-sm text-gray-500">
                                    Подписан с: {{ $author->pivot->created_at->format('d.m.Y') }}
                                </p>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('profile.public', $author) }}" 
                               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                                Профиль
                            </a>
                            <form action="{{ route('subscriptions.unsubscribe', $author) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                                    Отписаться
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center">
                        <p class="text-gray-500 mb-4">Вы пока ни на кого не подписаны</p>
                        <a href="{{ route('profile.show') }}" 
                           class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 inline-block">
                            Найти авторов для подписки
                        </a>
                    </div>
                @endforelse

                <!-- Пагинация -->
                @if($subscriptions->hasPages())
                    <div class="p-4 border-t">
                        {{ $subscriptions->links() }}
                    </div>
                @endif
            </div>

            <!-- Рекомендации для подписки -->
            @if($recommendedUsers->isNotEmpty())
                <div class="mt-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Возможно, вам будет интересно</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($recommendedUsers as $user)
                            <div class="bg-white rounded-lg shadow p-4">
                                <div class="flex flex-col items-center text-center">
                                    <img src="{{ $user->avatar_url }}" 
                                         class="w-16 h-16 rounded-full mb-3"
                                         alt="{{ $user->name }}">
                                    <a href="{{ route('profile.public', $user) }}" 
                                       class="font-semibold hover:underline mb-1">{{ $user->name }}</a>
                                    <p class="text-sm text-gray-500 mb-3">
                                        {{ $user->mutualFriendsCount }} общих друзей
                                    </p>
                                    <form action="{{ route('subscriptions.subscribe', $user) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                                class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                                            Подписаться
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>