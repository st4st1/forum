<x-app-layout>
    <div class="bg-gray-100 min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Кнопки управления -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    @auth
                        @if($post->isFavourited(auth()->id()))
                            <form action="{{ route('posts.toggleFavourite', $post->id) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition">
                                    Удалить из избранного
                                </button>
                            </form>
                        @else
                            <form action="{{ route('posts.toggleFavourite', $post->id) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition">
                                    Добавить в избранное
                                </button>
                            </form>
                        @endif
                    @else
                        <p class="text-gray-700">Авторизуйтесь, чтобы добавить пост в избранное</p>
                    @endauth
                </div>

                <button onclick="history.back()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                    Назад
                </button>
            </div>

            <!-- Основной контент поста -->
            <div class="bg-white rounded-lg shadow-md p-8">
                <h1 class="text-3xl font-bold mb-6 text-gray-900">{{ $post->title }}</h1>
                
                <div class="flex items-center mb-6">
                    <img src="{{ $post->user->avatar_url }}" 
                         class="w-12 h-12 rounded-full mr-4 border-2 border-blue-500"
                         alt="{{ $post->user->name }}">
                    <div>
                        <a href="{{ Auth::id() === $post->user->id ? route('profile.show') : route('profile.public', $post->user) }}" 
                           class="text-blue-600 hover:text-blue-800 hover:underline font-medium">
                            {{ $post->user->name }}
                        </a>
                        <p class="text-sm text-gray-500">{{ $post->created_at->format('d.m.Y H:i') }}</p>
                    </div>
                </div>

                <div class="mb-6">
                    <span class="inline-block bg-blue-100 text-blue-800 text-sm px-4 py-1 rounded-full font-medium">
                        {{ $post->topic->title }}
                    </span>
                </div>

                <div class="prose max-w-none mb-8">
                    @if(isset($post->content) && is_array(json_decode($post->content, true)))
                        @foreach(json_decode($post->content, true) as $block)
                            @if($block['type'] === 'text')
                                <p class="mb-6 text-gray-800">{{ $block['content'] }}</p>
                            @elseif($block['type'] === 'image')
                                <img src="{{ asset('storage/' . $block['content']) }}" 
                                     alt="Post Image" 
                                     class="w-full h-auto rounded-lg shadow-md mb-6">
                            @endif
                        @endforeach
                    @else
                        <p class="text-gray-700">Контент недоступен.</p>
                    @endif
                </div>

                <!-- Форма жалобы -->
                @auth
                    <div class="mb-8 border-t pt-6">
                        <button id="show-report-form" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition">
                            Отправить жалобу
                        </button>
                        
                        <form id="report-form" action="{{ route('reports.store') }}" method="POST" class="hidden mt-6 bg-gray-50 p-4 rounded-lg">
                            @csrf
                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                            <div class="mb-4">
                                <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Причина жалобы:</label>
                                <textarea name="reason" id="reason" rows="3" required 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition">
                                    Отправить жалобу
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <p class="text-gray-700 mb-8">Авторизуйтесь, чтобы отправить жалобу</p>
                @endauth

                <!-- Комментарии -->
                <div class="border-t pt-8">
                    <h2 class="text-2xl font-semibold mb-6 text-gray-900">Комментарии</h2>
                    
                    @if($comments->isNotEmpty())
                        <ul class="space-y-6 mb-8">
                            @foreach($comments as $comment)
                            <li class="bg-gray-50 p-6 rounded-lg" data-comment-id="{{ $comment->id }}">
                                <div class="comment-content">
                                    <p class="mb-4 text-gray-800">{{ $comment->content }}</p>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <a href="{{ Auth::id() === $comment->user->id ? route('profile.show') : route('profile.public', $comment->user) }}" 
                                           class="text-blue-600 hover:text-blue-800 hover:underline font-medium mr-2">
                                            {{ $comment->user->name }}
                                        </a>
                                        <span>{{ $comment->created_at->format('d.m.Y H:i') }}</span>
                                    </div>
                                </div>
                                
                                @auth
                                    @if($comment->user_id === auth()->id())
                                        <div class="comment-actions mt-4">
                                            <button class="text-blue-600 hover:text-blue-800 font-medium edit-comment mr-4">
                                                Редактировать
                                            </button>
                                            <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium">
                                                    Удалить
                                                </button>
                                            </form>
                                        </div>
                                        
                                        <form action="{{ route('comments.update', $comment) }}" method="POST" class="hidden update-comment-form mt-4">
                                            @csrf
                                            @method('PUT')
                                            <textarea name="content" rows="3" required 
                                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 mb-3">{{ $comment->content }}</textarea>
                                            <div class="flex justify-end">
                                                <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition mr-3 cancel-edit">
                                                    Отмена
                                                </button>
                                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition">
                                                    Сохранить
                                                </button>
                                            </div>
                                        </form>
                                    @endif
                                @endauth
                            </li>
                            @endforeach
                        </ul>
                        <div class="mb-8">
                            {{ $comments->links() }}
                        </div>
                    @else
                        <p class="text-gray-700 mb-8">Еще нет комментариев</p>
                    @endif

                    <!-- Форма добавления комментария -->
                    <div class="mt-8">
                        <h3 class="text-xl font-semibold mb-4 text-gray-900">Оставить комментарий</h3>
                        @auth
                            <form method="POST" action="{{ route('comment.store', $post) }}" class="bg-gray-50 p-6 rounded-lg">
                                @csrf
                                <textarea name="content" rows="3" required 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4"></textarea>
                                <div class="flex justify-end">
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition">
                                        Отправить
                                    </button>
                                </div>
                            </form>
                        @else
                            <p class="text-gray-700">Авторизуйтесь, чтобы оставить комментарий</p>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Скрипты -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Обработка формы жалобы
            const showReportFormButton = document.getElementById('show-report-form');
            const reportForm = document.getElementById('report-form');
            
            if (showReportFormButton && reportForm) {
                showReportFormButton.addEventListener('click', function() {
                    reportForm.classList.toggle('hidden');
                });
            }

            // Обработка редактирования комментариев
            document.querySelectorAll('.edit-comment').forEach(button => {
                button.addEventListener('click', function() {
                    const commentItem = this.closest('li');
                    commentItem.querySelector('.comment-content').classList.add('hidden');
                    commentItem.querySelector('.update-comment-form').classList.remove('hidden');
                });
            });

            document.querySelectorAll('.cancel-edit').forEach(button => {
                button.addEventListener('click', function() {
                    const commentItem = this.closest('li');
                    commentItem.querySelector('.comment-content').classList.remove('hidden');
                    commentItem.querySelector('.update-comment-form').classList.add('hidden');
                });
            });

            // AJAX обновление комментариев
            document.querySelectorAll('.update-comment-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            _method: 'PUT',
                            content: this.content.value
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const commentItem = this.closest('li');
                            commentItem.querySelector('.comment-content p').textContent = data.content;
                            commentItem.querySelector('.comment-content').classList.remove('hidden');
                            this.classList.add('hidden');
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>