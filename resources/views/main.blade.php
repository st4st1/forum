<x-app-layout>
    <div class="bg-gray-100 min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Поиск -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <form action="{{ route('posts.search') }}" method="GET" class="flex">
                    <input type="text" name="search" 
                           class="flex-grow px-4 py-3 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Поиск по публикациям">
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-500 text-white rounded-r-lg hover:bg-blue-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </form>
            </div>

            <!-- Блок категорий -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="font-semibold text-xl mb-4 text-gray-800">Категории</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    @foreach($topics as $topic)
                        <a href="{{ route('posts.category', $topic->id) }}" 
                           class="flex flex-col items-center p-4 bg-gray-50 hover:bg-blue-50 rounded-lg transition">
                            <span class="text-2xl mb-2">{{ $topic->emoji }}</span>
                            <span class="text-sm font-medium text-center text-gray-700">{{ $topic->title }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Список постов -->
            <div class="space-y-6">
                @foreach($posts as $post)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                        <!-- Заголовок поста с автором -->
                        <div class="p-4 border-b">
                            <div class="flex items-center">
                                <img src="{{ $post->user->avatar_url }}" 
                                     class="w-10 h-10 rounded-full mr-3 border-2 border-blue-200"
                                     alt="{{ $post->user->name }}">
                                <div>
                                    <a href="{{ route('profile.public', $post->user) }}" 
                                       class="font-semibold text-gray-800 hover:text-blue-600 hover:underline">
                                        {{ $post->user->name }}
                                    </a>
                                    <p class="text-xs text-gray-500">
                                        {{ $post->created_at->format('d.m.Y H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Основное содержимое поста -->
                        <div class="p-6">
                            <h2 class="text-xl font-bold mb-3 text-gray-900">
                                <a href="{{ route('post', $post->id) }}" class="hover:text-blue-600">
                                    {{ $post->title }}
                                </a>
                            </h2>
                            
                            <!-- Вывод первого изображения, если оно есть -->
                            @php
                                $hasImage = false;
                                $contentArray = json_decode($post->content, true);
                            @endphp
                            
                            @if(is_array($contentArray))
                                @foreach($contentArray as $item)
                                    @if($item['type'] === 'image' && !$hasImage)
                                        <div class="mb-4 rounded-lg overflow-hidden">
                                            <img src="{{ asset('storage/' . $item['content']) }}" 
                                                 alt="Post image"
                                                 class="w-full h-auto object-cover">
                                        </div>
                                        @php $hasImage = true; @endphp
                                    @endif
                                @endforeach
                            @endif
                            
                            <!-- Вывод первого текстового блока -->
                            @if(is_array($contentArray))
                                @foreach($contentArray as $item)
                                    @if($item['type'] === 'text')
                                        <p class="text-gray-700 mb-4 line-clamp-3">
                                            {{ Str::limit($item['content'], 200) }}
                                        </p>
                                        @break
                                    @endif
                                @endforeach
                            @endif
                            
                            <!-- Категория и кнопки -->
                            <div class="flex items-center justify-between">
                                <span class="bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full">
                                    {{ $post->topic->title }}
                                </span>
                                <a href="{{ route('post', $post->id) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Читать далее →
                                </a>
                            </div>
                        </div>
                        
                        <!-- Футер поста -->
                        <div class="px-6 py-3 bg-gray-50 border-t flex justify-between items-center">
                            <a href="{{ route('post', $post->id) }}" class="text-gray-600 hover:text-blue-600 text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd" />
                                </svg>
                                {{ $post->comments_count ?? 0 }} комментариев
                            </a>
                            <span class="text-xs text-gray-500">
                                {{ $post->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                @endforeach
                
                <!-- Пагинация -->
                <div class="mt-6">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>