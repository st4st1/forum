<x-app-layout>
    <div class="bg-gray-100 min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-md overflow-hidden p-6">
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-gray-800">Редактировать публикацию</h1>
                    <p class="text-gray-600 mt-2">Внесите необходимые изменения</p>
                </div>

                <form action="{{ route('posts.update', $post) }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <!-- Заголовок -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Название</label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                               value="{{ old('title', $post->title) }}" 
                               required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Категория -->
                    <div>
                        <label for="topic_id" class="block text-sm font-medium text-gray-700 mb-1">Категория</label>
                        <select id="topic_id" 
                                name="topic_id" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                required>
                            <option value="">Выберите категорию</option>
                            @foreach ($topics as $topic)
                                <option value="{{ $topic->id }}" {{ old('topic_id', $post->topic_id) == $topic->id ? 'selected' : '' }}>
                                    {{ $topic->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('topic_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Сообщества -->
                    @if(auth()->user()->communities->count() > 0)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Разместить в сообществах</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach(auth()->user()->communities as $community)
                                    <div class="flex items-center">
                                        <input type="checkbox" 
                                               id="community_{{ $community->id }}" 
                                               name="communities[]" 
                                               value="{{ $community->id }}"
                                               {{ $post->communities->contains($community->id) ? 'checked' : '' }}
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="community_{{ $community->id }}" class="ml-2 text-sm text-gray-700">
                                            {{ $community->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Блоки контента -->
                    <div id="content-blocks" class="space-y-6">
                        @php
                            $contentBlocks = json_decode($post->content);
                            $textBlocks = array_filter($contentBlocks, fn($block) => $block->type === 'text');
                            $imageBlocks = array_filter($contentBlocks, fn($block) => $block->type === 'image');
                        @endphp

                        @foreach ($textBlocks as $index => $textBlock)
                            <div class="content-block bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Содержание</label>
                                <textarea name="content[]" 
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                          rows="4"
                                          required>{{ $textBlock->content }}</textarea>
                            </div>
                        @endforeach

                        <!-- Текущие изображения -->
                        @if(count($imageBlocks) > 0)
                            <div class="current-images bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Текущие изображения</label>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    @foreach ($imageBlocks as $imageBlock)
                                        <div class="image-block relative group">
                                            <img src="{{ asset('storage/' . $imageBlock->content) }}" 
                                                 alt="Изображение поста" 
                                                 class="w-full h-32 object-cover rounded-lg">
                                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 flex items-center justify-center rounded-lg transition-opacity">
                                                <button type="button" 
                                                        class="text-white bg-red-500 hover:bg-red-600 p-2 rounded-full"
                                                        onclick="this.closest('.image-block').remove()">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <input type="hidden" name="keep_images[]" value="{{ $imageBlock->content }}">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Новые изображения -->
                        <div class="new-images bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Добавить новые изображения</label>
                            <input type="file" 
                                   name="new_images[]" 
                                   class="block w-full text-sm text-gray-500
                                   file:mr-4 file:py-2 file:px-4
                                   file:rounded-lg file:border-0
                                   file:text-sm file:font-semibold
                                   file:bg-blue-50 file:text-blue-700
                                   hover:file:bg-blue-100"
                                   multiple>
                        </div>
                    </div>

                    <!-- Кнопка добавления блока -->
                    <button type="button" 
                            id="add-content-block" 
                            class="flex items-center px-4 py-2 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Добавить текстовый блок
                    </button>

                    <!-- Кнопки действий -->
                    <div class="flex justify-between pt-4 border-t border-gray-200">
                        <button type="button" 
                                onclick="history.back()" 
                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            Назад
                        </button>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Сохранить изменения
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('add-content-block').addEventListener('click', function() {
            const contentBlocks = document.getElementById('content-blocks');
            const newBlock = document.createElement('div');
            newBlock.className = 'content-block bg-gray-50 p-4 rounded-lg border border-gray-200';
            newBlock.innerHTML = `
                <label class="block text-sm font-medium text-gray-700 mb-2">Содержание</label>
                <textarea name="content[]" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                          rows="4"
                          required
                          placeholder="Введите текст поста"></textarea>
            `;
            contentBlocks.insertBefore(newBlock, contentBlocks.lastElementChild);
        });
    </script>
</x-app-layout>