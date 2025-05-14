<x-app-layout>
    <div class="bg-gray-100 min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Левая колонка (информация о сообществе) -->
                <div class="w-full md:w-1/3 space-y-6">
                    <!-- Карточка сообщества -->
                    <div class="bg-white rounded-lg shadow p-6 relative">
                        @if($community->creator_id == auth()->id())
                            <div class="absolute top-4 right-4">
                                <button id="edit-community-btn" class="flex items-center px-3 py-1 bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <span class="text-sm">Редактировать</span>
                                </button>
                            </div>
                        @endif

                        <div id="community-view" class="flex flex-col items-center">
                            <div class="relative w-32 h-32 rounded-full overflow-hidden mb-4 bg-gray-200">
                                @if($community->avatar)
                                    <img src="{{ asset('storage/' . $community->avatar) }}" 
                                         class="w-full h-full object-cover"
                                         alt="{{ $community->name }}">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-4xl font-bold text-gray-500">
                                        {{ Str::limit($community->name, 1, '') }}
                                    </div>
                                @endif
                            </div>
                            <h2 class="text-xl font-bold">{{ $community->name }}</h2>
                            <p class="text-gray-500 text-sm mt-1">Создатель: {{ $community->creator->name }}</p>
                            <p class="mt-4 text-center">{{ $community->description }}</p>
                        </div>

                        @if($community->creator_id == auth()->id())
                            <form id="edit-community-form" action="{{ route('communities.update', $community) }}" method="POST" enctype="multipart/form-data" class="hidden mt-4">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Название</label>
                                    <input type="text" id="name" name="name" value="{{ $community->name }}"
                                           class="w-full px-3 py-2 border rounded-md" required>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Описание</label>
                                    <textarea id="description" name="description" rows="3"
                                              class="w-full px-3 py-2 border rounded-md">{{ $community->description }}</textarea>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="avatar" class="block text-sm font-medium text-gray-700 mb-1">Аватар</label>
                                    <input type="file" id="avatar" name="avatar" class="w-full">
                                </div>
                                
                                <div class="flex justify-between">
                                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md">Сохранить</button>
                                    <button type="button" id="cancel-edit" class="px-4 py-2 bg-gray-500 text-white rounded-md">Отмена</button>
                                </div>
                            </form>
                        @endif

                        <!-- Кнопки действий -->
                        <div class="mt-6">
                            @if($isMember)
                                <div class="space-y-3">
                                    <a href="{{ route('events.create', $community) }}" 
                                       class="block w-full text-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                        Создать мероприятие
                                    </a>
                                    <form action="{{ route('communities.leave', $community) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                                            Покинуть сообщество
                                        </button>
                                    </form>
                                </div>
                            @else
                                <form action="{{ route('communities.join', $community) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                                        Вступить в сообщество
                                    </button>
                                </form>
                            @endif

                            @if($community->creator_id == auth()->id())
                                <div class="mt-6">
                                    <a href="{{ route('communities.members.index', $community) }}" 
                                       class="block w-full text-center px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600">
                                        Управление участниками
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Блок участников -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="font-semibold text-lg mb-4">Участники ({{ $community->members->count() }})</h3>
                        @if($community->members->count() > 0)
                            <div class="grid grid-cols-3 gap-3">
                                @foreach($community->members->take(9) as $member)
                                    <div class="flex flex-col items-center">
                                        <a href="{{ route('profile.public', $member) }}" class="group">
                                            <div class="relative w-16 h-16 rounded-full overflow-hidden mb-1">
                                                <img src="{{ $member->avatar_url }}" 
                                                     alt="{{ $member->name }}"
                                                     class="w-full h-full object-cover group-hover:opacity-90 transition-opacity">
                                            </div>
                                            <span class="text-xs text-center text-gray-700 group-hover:text-blue-500 transition-colors truncate w-full block">
                                                {{ Str::limit($member->name, 10) }}
                                            </span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                            @if($community->members->count() > 9)
                                <a href="#" class="block text-center text-blue-500 text-sm mt-2">Показать всех</a>
                            @endif
                        @else
                            <p class="text-gray-500 text-sm py-4">В сообществе пока нет участников</p>
                        @endif
                    </div>

                    <!-- Блок мероприятий -->
                    @if($events->isNotEmpty())
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="font-semibold text-lg mb-4">Ближайшие мероприятия</h3>
                            <div class="space-y-3">
                                @foreach($events as $event)
                                    <a href="{{ route('events.show', $event) }}" class="block p-3 border rounded-lg hover:bg-gray-50 transition-colors">
                                        <h4 class="font-medium">{{ $event->title }}</h4>
                                        <p class="text-sm text-gray-500">
                                            {{ $event->start_time->format('d.m.Y H:i') }}
                                        </p>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Правая колонка (лента постов) -->
<div class="w-full md:w-2/3 space-y-6">
    <!-- Форма создания поста -->
    @if($isMember)
        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="community_id" value="{{ $community->id }}">
                
                <div class="form-group mb-4">
                    <label for="title" class="block mb-2 font-medium">Название</label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                           required
                           placeholder="Введите название поста">
                </div>

                <div id="content-blocks" class="mb-4">
                    <div class="content-block mb-4">
                        <label class="block mb-2 font-medium">Содержание</label>
                        <textarea name="content[]" 
                                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                                  rows="3"
                                  required
                                  placeholder="Напишите что-нибудь..."></textarea>
                        <input type="file" name="images[]" class="mt-2">
                    </div>
                </div>

                <button type="button" id="add-content-block" 
                        class="mb-4 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Добавить блок
                </button>

                <div class="flex justify-end">
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        Опубликовать
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Лента постов -->
    @forelse($posts as $post)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-4 border-b">
                <div class="flex items-center">
                    <img src="{{ $post->user->avatar_url }}" 
                         class="w-10 h-10 rounded-full mr-3"
                         alt="{{ $post->user->name }}">
                    <div>
                        <a href="{{ route('profile.public', $post->user)}}" 
                           class="font-semibold hover:underline">{{ $post->user->name }}</a>
                        <p class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="p-4">
                <h3 class="font-bold text-lg mb-4">
                    <a href="{{ route('post', $post->id) }}" class="hover:text-blue-500">
                        {{ $post->title }}
                    </a>
                </h3>
                
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
            
            <div class="p-4 border-t flex justify-between items-center">
                <div class="flex space-x-4">
                    <span class="text-gray-500">{{ $post->comments_count }} комментариев</span>
                    <span class="text-gray-500">{{ $post->favourite_count }} в избранном</span>
                </div>
                
                @if($community->creator_id == auth()->id() || (auth()->check() && $community->members()->where('user_id', auth()->id())->where('community_members.role', 'moderator')->exists()))
                    <form action="{{ route('posts.remove_from_community', [$post, $community]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="text-red-500 hover:text-red-700 text-sm"
                                onclick="return confirm('Вы уверены, что хотите удалить этот пост из сообщества?')">
                            Удалить из сообщества
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <p class="text-gray-500">В этом сообществе пока нет постов</p>
        </div>
    @endforelse

    {{ $posts->links() }}
</div>

<script>
    // Скрипт для добавления блоков контента
    document.addEventListener('DOMContentLoaded', function() {
        const addBlockBtn = document.getElementById('add-content-block');
        if (addBlockBtn) {
            addBlockBtn.addEventListener('click', function() {
                const contentBlocks = document.getElementById('content-blocks');
                const newBlock = document.createElement('div');
                newBlock.className = 'content-block mb-4';
                newBlock.innerHTML = `
                    <label class="block mb-2 font-medium">Содержание</label>
                    <textarea name="content[]" 
                              class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                              rows="3"
                              required></textarea>
                    <input type="file" name="images[]" class="mt-2">
                `;
                contentBlocks.appendChild(newBlock);
            });
        }
    });


    
        // Скрипт для переключения между просмотром и редактированием
        document.addEventListener('DOMContentLoaded', function() {
            const editBtn = document.getElementById('edit-community-btn');
            const cancelBtn = document.getElementById('cancel-edit');
            const view = document.getElementById('community-view');
            const form = document.getElementById('edit-community-form');

            if (editBtn && cancelBtn && view && form) {
                editBtn.addEventListener('click', function() {
                    view.classList.add('hidden');
                    form.classList.remove('hidden');
                });

                cancelBtn.addEventListener('click', function() {
                    view.classList.remove('hidden');
                    form.classList.add('hidden');
                });
            }
        });
    </script>
</x-app-layout>