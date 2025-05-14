<x-app-layout>
    <div class="bg-gray-100 min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow p-8 max-w-2xl mx-auto">
                <h1 class="text-3xl font-bold mb-6 text-center">Обновить категорию</h1>

                <form action="{{ route('topics.update', $topic) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Заголовок</label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                               value="{{ $topic->title }}"
                               required>
                    </div>

                    <div class="flex justify-end gap-4">
                        <a href="{{ route('topics.index') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                            Отмена
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                            Обновить
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>