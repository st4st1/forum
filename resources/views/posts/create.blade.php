<x-app-layout>
    <div class="bg-gray-100 min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-md overflow-hidden p-6">
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-gray-800">Создать новую публикацию</h1>
                    <p class="text-gray-600 mt-2">Заполните форму для создания поста</p>
                </div>

                <form action="{{ route('posts.store') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                    @csrf

                    <!-- Заголовок -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Название</label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                               required
                               placeholder="Введите заголовок поста">
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
                                <option value="{{ $topic->id }}" {{ old('topic_id') == $topic->id ? 'selected' : '' }}>
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
                        <div class="content-block bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Содержание</label>
                            <textarea name="content[]" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                      rows="4"
                                      required
                                      placeholder="Введите текст поста"></textarea>
                            <div class="mt-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Изображение (необязательно)</label>
                                <input type="file" name="images[]" class="block w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-lg file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-blue-50 file:text-blue-700
                                      hover:file:bg-blue-100">
                            </div>
                        </div>
                    </div>

                    <!-- Кнопка добавления блока -->
                    <button type="button" 
                            id="add-content-block" 
                            class="flex items-center px-4 py-2 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Добавить блок
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
                            Опубликовать
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
                <div class="mt-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Изображение (необязательно)</label>
                    <input type="file" name="images[]" class="block w-full text-sm text-gray-500
                          file:mr-4 file:py-2 file:px-4
                          file:rounded-lg file:border-0
                          file:text-sm file:font-semibold
                          file:bg-blue-50 file:text-blue-700
                          hover:file:bg-blue-100">
                </div>
            `;
            contentBlocks.appendChild(newBlock);
        });
    </script>
</x-app-layout>