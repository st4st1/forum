<x-app-layout>
    <div class="bg-gray-100 min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex justify-between items-center">
                <h1 class="text-3xl font-bold text-gray-900">Управление публикациями</h1>
                
                <div class="flex gap-3">
                    <a href="{{ route('posts.create.adm') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition">
                        Создать публикацию
                    </a>
                    <a href="{{ route('topics.index') }}" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg transition">
                        Категории
                    </a>
                    <a href="{{ route('admin.reported.posts') }}" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition">
                        Жалобы
                    </a>
                    <a href="{{ route('moder.posts.index') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition">
                        Модерация
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-blue-500 text-white">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Название</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Содержание</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Категория</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Создан</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Обновлен</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($posts as $post)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $post->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $post->title }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $post->topic->title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $post->created_at->format('d.m.Y H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $post->updated_at->format('d.m.Y H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>