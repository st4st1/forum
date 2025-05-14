<x-app-layout>
    <div class="bg-gray-100 min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Заголовок -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Мой профиль</h1>
            </div>

            <div class="flex flex-col md:flex-row gap-6">
                <!-- Левая колонка (профиль, друзья, подписки) -->
                <div class="w-full md:w-1/3 space-y-6">
                    <!-- Карточка профиля -->
                    <div class="bg-white rounded-lg shadow p-6 relative">
                        <div class="absolute top-4 right-4">
                            <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-1 bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                <span class="text-sm">Изменить данные</span>
                            </a>
                        </div>

                        <div class="flex flex-col items-center pt-6">
                            <div class="relative mb-4">
                                <img src="{{ Auth::user()->avatar_url }}" 
                                     class="w-32 h-32 rounded-full border-4 border-blue-500 mb-4 mx-auto"
                                     alt="Аватар" id="avatar-preview">
                            </div>
                            
                            <div class="mb-4 w-full">
                                <label class="block text-sm font-medium text-gray-700 mb-1">О себе</label>
                                <div id="about-view" class="whitespace-pre-line p-2 border rounded bg-gray-50 min-h-12">
                                    {{ Auth::user()->about ?? 'Нет информации' }}
                                </div>
                            </div>
                            
                            <button id="edit-profile-btn" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                Изменить
                            </button>

                            <!-- Форма редактирования -->
                            <form id="edit-form" action="{{ route('profile.update-info') }}" method="POST" enctype="multipart/form-data" class="w-full hidden">
                                @csrf
                                @method('POST')
                                
                                <div class="relative mb-4">
                                    <img src="{{ Auth::user()->avatar_url }}" 
                                         class="w-32 h-32 rounded-full border-4 border-blue-500 mb-4 mx-auto"
                                         alt="Аватар" id="avatar-preview-edit">
                                    <label for="avatar-upload" class="absolute bottom-0 right-0 bg-blue-500 text-white rounded-full p-2 cursor-pointer hover:bg-blue-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                                        </svg>
                                        <input type="file" id="avatar-upload" name="avatar" class="hidden" accept="image/*">
                                    </label>
                                </div>

                                <div class="mb-4 w-full">
                                    <label for="about-edit" class="block text-sm font-medium text-gray-700 mb-1">О себе</label>
                                    <textarea id="about-edit" name="about" rows="3" 
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('about', Auth::user()->about) }}</textarea>
                                </div>

                                <div class="flex justify-between items-center">
                                    <button type="submit" 
                                            class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        Сохранить изменения
                                    </button>
                                    
                                    <button type="button" id="cancel-edit" 
                                            class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                        Отмена
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Блок друзей -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-semibold text-lg">Друзья</h3>
                            <a href="{{ route('friends.index') }}" class="text-blue-500 text-sm hover:underline">Все друзья</a>
                        </div>
                        
                        @if(Auth::user()->friends()->count() > 0)
                            <div class="grid grid-cols-3 gap-3">
                                @foreach(Auth::user()->friends()->limit(6)->get() as $friend)
                                    <div class="flex flex-col items-center">
                                        <a href="{{ route('profile.public', $friend) }}" class="group">
                                            <div class="relative w-16 h-16 rounded-full overflow-hidden mb-1">
                                                <img src="{{ $friend->avatar_url }}" 
                                                     alt="{{ $friend->name }}"
                                                     class="w-full h-full object-cover group-hover:opacity-90 transition-opacity">
                                            </div>
                                            <span class="text-xs text-center text-gray-700 group-hover:text-blue-500 transition-colors truncate w-full block">
                                                {{ Str::limit($friend->name, 10) }}
                                            </span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm py-4">У вас пока нет друзей</p>
                        @endif
                    </div>

                    <!-- Блок подписок -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-semibold text-lg">Мои подписки</h3>
                            <a href="{{ route('subscriptions.index') }}" class="text-blue-500 text-sm hover:underline">Все подписки</a>
                        </div>
                        
                        @if(Auth::user()->subscriptions()->count() > 0)
                            <div class="grid grid-cols-3 gap-3">
                                @foreach(Auth::user()->subscriptions()->limit(6)->get() as $author)
                                    <div class="flex flex-col items-center">
                                        <a href="{{ route('profile.public', $author) }}" class="group">
                                            <div class="relative w-16 h-16 rounded-full overflow-hidden mb-1">
                                                <img src="{{ $author->avatar_url }}" 
                                                     alt="{{ $author->name }}"
                                                     class="w-full h-full object-cover group-hover:opacity-90 transition-opacity">
                                            </div>
                                            <span class="text-xs text-center text-gray-700 group-hover:text-blue-500 transition-colors truncate w-full block">
                                                {{ Str::limit($author->name, 10) }}
                                            </span>
                                        </a>
                                        <form action="{{ route('subscriptions.unsubscribe', $author) }}" method="POST" class="mt-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-red-500 hover:text-red-700">Отписаться</button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm py-4">Вы пока ни на кого не подписаны</p>
                        @endif
                    </div>

                    <!-- Блока сообществ -->
                    <div class="bg-white rounded-lg shadow p-6 mt-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-semibold text-lg">Мои сообщества</h3>
                            <a href="{{ route('communities.index') }}" class="text-blue-500 text-sm hover:underline">Все сообщества</a>
                        </div>

                        @if($user->communities->count() > 0)
                            <div class="grid grid-cols-3 gap-3">
                                @foreach($user->communities->take(6) as $community)
                                    <div class="flex flex-col items-center">
                                        <a href="{{ route('communities.show', $community) }}" class="group">
                                            <div class="relative w-16 h-16 rounded-full overflow-hidden mb-1 bg-gray-200">
                                                @if($community->avatar)
                                                    <img src="{{ asset('storage/' . $community->avatar) }}" 
                                                         alt="{{ $community->name }}"
                                                         class="w-full h-full object-cover group-hover:opacity-90 transition-opacity">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-xl font-bold">
                                                        {{ Str::limit($community->name, 1, '') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <span class="text-xs text-center text-gray-700 group-hover:text-blue-500 transition-colors truncate w-full block">
                                                {{ Str::limit($community->name, 10) }}
                                            </span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm py-4">Вы пока не состоите в сообществах</p>
                        @endif
                    </div>
                </div>

                <!-- Правая колонка (посты) -->
                <div class="w-full md:w-2/3 space-y-6">
                    <!-- Переключатель вкладок -->
                    <div class="flex border-b mb-4">
                        <button class="px-4 py-2 font-medium {{ $activeTab === 'my_posts' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500' }}"
                                onclick="window.location.href='{{ route('profile.show') }}?tab=my_posts'">
                            Мои посты
                        </button>
                        <button class="px-4 py-2 font-medium {{ $activeTab === 'subscriptions' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500' }}"
                                onclick="window.location.href='{{ route('profile.show') }}?tab=subscriptions'">
                            Подписки
                        </button>
                        <button class="px-4 py-2 font-medium {{ $activeTab === 'communities' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500' }}"
                                onclick="window.location.href='{{ route('profile.show') }}?tab=communities'">
                            Сообщества
                        </button>
                    </div>

                    <!-- Форма создания поста -->
                    @if($activeTab === 'my_posts')
                        <div class="bg-white rounded-lg shadow p-6">
                            <form action="{{ route('posts.store_user') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group mb-4">
                                    <label for="title" class="block mb-2 font-medium">Название</label>
                                    <input type="text" 
                                           id="title" 
                                           name="title" 
                                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                                           required>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="topic_id" class="block mb-2 font-medium">Категория</label>
                                    <select id="topic_id" 
                                            name="topic_id" 
                                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                                            required>
                                        <option value="">Выберите категорию</option>
                                        @foreach ($topics as $topic)
                                            <option value="{{ $topic->id }}">{{ $topic->title }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div id="content-blocks" class="mb-4">
                                    <div class="content-block mb-4">
                                        <label class="block mb-2 font-medium">Содержание</label>
                                        <textarea name="content[]" 
                                                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                                                  rows="3"
                                                  required></textarea>
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
                    @if($posts->count() > 0)
                        @foreach($posts as $post)
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
                                
                                <div class="p-4 border-t flex justify-between">
                                    <span class="text-gray-500">{{ $post->comments_count }} комментариев</span>
                                    <span class="text-gray-500">{{ $post->favourite_count }} в избранном</span>
                                </div>
                            </div>
                        @endforeach
                        {{ $posts->links() }}
                    @else
                    <div class="bg-white rounded-lg shadow p-6 text-center">
                        <p class="text-gray-500">
                            @if($activeTab === 'my_posts')
                                У вас пока нет постов
                            @elseif($activeTab === 'subscriptions')
                                У вас нет подписок или ваши подписки еще не публиковали посты
                            @else
                                В ваших сообществах пока нет постов
                            @endif
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Обработка добавления блоков контента
            document.getElementById('add-content-block').addEventListener('click', function() {
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

            // Обработка редактирования профиля
            const editButton = document.getElementById('edit-profile-btn');
            const editForm = document.getElementById('edit-form');
            const aboutView = document.getElementById('about-view');
            const cancelButton = document.getElementById('cancel-edit');
            
            if (editButton && editForm && aboutView && cancelButton) {
                editButton.addEventListener('click', function() {
                    aboutView.classList.add('hidden');
                    editForm.classList.remove('hidden');
                    editButton.classList.add('hidden');
                    document.getElementById('about-edit').value = aboutView.textContent.trim();
                });
                
                cancelButton.addEventListener('click', function() {
                    aboutView.classList.remove('hidden');
                    editForm.classList.add('hidden');
                    editButton.classList.remove('hidden');
                });
                
                document.getElementById('avatar-upload').addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            document.getElementById('avatar-preview-edit').src = event.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>
</x-app-layout>