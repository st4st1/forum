<x-app-layout>
    <div class="bg-gray-100 min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex justify-between items-center">
                <h1 class="text-3xl font-bold text-gray-900">Отклонённые посты</h1>
                <button onclick="history.back()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                    Назад
                </button>
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
                                            <form action="{{ route('posts.destroy', $post) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg transition text-xs">
                                                    Удалить
                                                </button>
                                            </form>
                                            <a href="{{ route('posts.edit', $post) }}" 
                                               class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg transition text-xs">
                                                Изменить
                                            </a>
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