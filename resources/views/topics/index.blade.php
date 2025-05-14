<x-app-layout>
    <div class="bg-gray-100 min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex justify-between items-center">
                <h1 class="text-3xl font-bold text-gray-900">Категории</h1>
                <a href="{{ route('topics.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                    Создать категорию
                </a>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-blue-500 text-white">
                        <tr>
                            <th class="py-3 px-6 text-left">Название</th>
                            <th class="py-3 px-6 text-left">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @foreach ($topics as $topic)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-4 px-6">{{ $topic->title }}</td>
                                <td class="py-4 px-6">
                                    <div class="flex gap-2">
                                        <a href="{{ route('topics.edit', $topic) }}" 
                                           class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-xs">
                                            Обновить
                                        </a>
                                        <form action="{{ route('topics.destroy', $topic) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-xs">
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

            <div class="mt-6">
                <a href="{{route('admin.posts.index')}}" 
                   class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                    Назад
                </a>
            </div>
        </div>
    </div>
</x-app-layout>