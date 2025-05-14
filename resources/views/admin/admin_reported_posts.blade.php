<x-app-layout>
    <div class="bg-gray-100 min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex justify-between items-center">
                <h1 class="text-3xl font-bold text-gray-900">Жалобы на публикации</h1>
                <button onclick="history.back()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                    Назад
                </button>
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
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Жалобы</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Действия</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($reportedPosts as $post)
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $post->reports_count }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex gap-2">
                                        <a href="{{ route('post', $post) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg transition text-xs">
                                            Просмотреть
                                        </a>
                                        <button onclick="toggleReports(this)" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded-lg transition text-xs">
                                            Жалобы
                                        </button>
                                        <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg transition text-xs">
                                                Удалить
                                            </button>
                                        </form>
                                        <form action="{{ route('reports.reject', $post) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg transition text-xs">
                                                Отклонить
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hidden bg-gray-50">
                                <td colspan="6" class="px-6 py-4">
                                    <div class="space-y-2">
                                        <h3 class="font-medium">Жалобы на пост "{{ $post->title }}"</h3>
                                        <ul class="space-y-2">
                                            @foreach($post->reports as $report)
                                            <li class="border-b pb-2">
                                                <p><strong>Пользователь:</strong> {{ $report->user->name }}</p>
                                                <p><strong>Причина:</strong> {{ $report->reason }}</p>
                                                <p><strong>Дата:</strong> {{ $report->created_at->format('d.m.Y H:i') }}</p>
                                            </li>
                                            @endforeach
                                        </ul>
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

    <script>
        function toggleReports(button) {
            const reportsRow = button.closest('tr').nextElementSibling;
            reportsRow.classList.toggle('hidden');
        }
    </script>
</x-app-layout>