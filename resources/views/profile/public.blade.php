<x-app-layout>
    <div class="bg-gray-100 min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Левая колонка -->
                <div class="w-full md:w-1/3 space-y-6">
                    <!-- Карточка профиля -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex flex-col items-center">
                            <img src="{{ $user->avatar_url }}" 
                                 class="w-32 h-32 rounded-full border-4 border-blue-500 mb-4"
                                 alt="Аватар">
                            <h2 class="text-xl font-bold">{{ $user->name }}</h2>
                            <p class="text-gray-500 mt-2">{{ $user->about ?? 'Нет информации' }}</p>
                            
                            @if(Auth::id() !== $user->id)
                                @if(Auth::user()->isFriendWith($user))
                                    <form action="{{ route('friends.remove', $user) }}" method="POST" class="mt-4">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                                            Удалить из друзей
                                        </button>
                                    </form>
                                @elseif(Auth::user()->hasFriendRequestFrom($user))
                                    <form action="{{ route('friends.accept', $user) }}" method="POST" class="mt-4">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                                            Принять заявку
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('friends.add', $user) }}" method="POST" class="mt-4">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                            Добавить в друзья
                                        </button>
                                    </form>
                                @endif
                            @endif

                            @if(Auth::id() !== $user->id)
                                @if(auth()->user()->subscriptions()->where('author_id', $user->id)->exists())
                                    <form action="{{ route('subscriptions.unsubscribe', $user) }}" method="POST" class="mt-4">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                                            Отписаться
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('subscriptions.subscribe', $user) }}" method="POST" class="mt-4">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                                            Подписаться
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>

                    <!-- Общие друзья -->
                    @if(auth()->check() && $mutualFriendsCount > 0 && !empty($mutualFriends))
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="font-semibold text-lg mb-4">
                                Общие друзья ({{ $mutualFriendsCount }})
                            </h3>
                            <div class="grid grid-cols-3 gap-2">
                                @foreach($mutualFriends as $friend)
                                    <a href="{{ route('profile.public', $friend) }}">
                                        <img src="{{ $friend->avatar_url }}" 
                                             class="w-full h-24 object-cover rounded"
                                             alt="{{ $friend->name }}">
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Правая колонка -->
                <div class="w-full md:w-2/3 space-y-6">
                    <!-- Лента постов -->
                    @foreach($user->posts as $post)
                    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                        <div class="p-4 border-b">
                            <div class="flex items-center">
                                <img src="{{ $post->user->avatar_url }}" 
                                     class="w-10 h-10 rounded-full mr-3"
                                     alt="{{ $post->user->name }}">
                                <div>
                                    <a href="{{ route('profile.public', $post->user) }}" 
                                       class="font-semibold hover:underline">{{ $post->user->name }}</a>
                                    <p class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-4">
                            <h3 class="font-bold text-lg mb-4">{{ $post->title }}</h3>
                            
                            @foreach(json_decode($post->content) as $item)
                                @if($item->type === 'text')
                                    <div class="whitespace-pre-line mb-4">{!! nl2br(e($item->content)) !!}</div>
                                @elseif($item->type === 'image' && Storage::disk('public')->exists($item->content))
                                    <img src="{{ asset('storage/' . $item->content) }}" 
                                         class="max-w-full h-auto mb-4 rounded-lg"
                                         alt="Изображение поста">
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>