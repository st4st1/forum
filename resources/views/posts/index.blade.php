<x-app-layout>
    <div class="bg-gray-100 min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-6 text-center">Мои публикации</h1>
                
                <div class="flex flex-wrap gap-3 mb-6 justify-center">
                    <a href="{{ route('posts.create') }}" 
                       class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Создать публикацию
                    </a>
                    <a href="{{ route('posts.rejected') }}" 
                       class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        Отклонённые
                    </a>
                    <a href="{{ route('posts.pending') }}" 
                       class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                        На рассмотрении
                    </a>
                    <a href="{{ route('posts.recalled') }}" 
                       class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg transition flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                        </svg>
                        Отозванные
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-blue-500 text-white">
                            <tr>
                                <th class="py-3 px-6 text-left">Название</th>
                                <th class="py-3 px-6 text-left">Содержание</th>
                                <th class="py-3 px-6 text-left">Категория</th>
                                <th class="py-3 px-6 text-left">Действия</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($posts as $post)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="py-4 px-6 text-left font-medium">{{ $post->title }}</td>
                                    <td class="py-4 px-6 text-left">
                                        @php
                                            $firstTextBlock = true;
                                        @endphp
                                        @foreach(json_decode($post->content) as $item)
                                            @if($item->type === 'text' && $firstTextBlock)
                                                {{ Str::limit($item->content, 50) }}
                                                @php $firstTextBlock = false; @endphp
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="py-4 px-6 text-left">
                                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                            {{ $post->topic->title }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-left">
                                        <div class="flex gap-2">
                                            <a href="{{ route('posts.edit', $post) }}" 
                                               class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg transition text-xs">
                                                Изменить
                                            </a>
                                            <form action="{{ route('posts.destroy', $post) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg transition text-xs">
                                                    Удалить
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>