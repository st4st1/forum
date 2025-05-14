<x-app-layout>
    <div class="bg-gray-100 min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Друзья</h1>

            <div class="grid md:grid-cols-2 gap-6">
                <!-- Входящие заявки -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-4">Заявки в друзья</h2>
                    @forelse(Auth::user()->friendRequests as $request)
                        <div class="flex items-center justify-between py-3 border-b">
                            <div class="flex items-center">
                                <img src="{{ $request->avatar_url }}" 
                                     class="w-10 h-10 rounded-full mr-3"
                                     alt="{{ $request->name }}">
                                <span>{{ $request->name }}</span>
                            </div>
                            <div class="flex space-x-2">
                                <form action="{{ route('friends.accept', $request) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="px-3 py-1 bg-green-500 text-white rounded text-sm">
                                        Принять
                                    </button>
                                </form>
                                <form action="{{ route('friends.remove', $request) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" 
                                            class="px-3 py-1 bg-red-500 text-white rounded text-sm">
                                        Отклонить
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">Нет входящих заявок</p>
                    @endforelse
                </div>

                <!-- Список друзей -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-4">Мои друзья</h2>
                    @forelse(Auth::user()->friends as $friend)
                        <div class="flex items-center justify-between py-3 border-b">
                            <div class="flex items-center">
                                <img src="{{ $friend->avatar_url }}" 
                                     class="w-10 h-10 rounded-full mr-3"
                                     alt="{{ $friend->name }}">
                                <a href="{{ route('profile.public', $friend) }}" 
                                   class="hover:underline">{{ $friend->name }}</a>
                            </div>
                            <form action="{{ route('friends.remove', $friend) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" 
                                        class="px-3 py-1 bg-red-500 text-white rounded text-sm">
                                    Удалить
                                </button>
                            </form>
                        </div>
                    @empty
                        <p class="text-gray-500">У вас пока нет друзей</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>