<x-app-layout>
    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center mb-6">
                    <a href="{{ route('communities.show', $community) }}" class="mr-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 hover:text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <h1 class="text-2xl font-bold">Создать мероприятие в сообществе "{{ $community->name }}"</h1>
                </div>
                
                <form action="{{ route('events.store', $community) }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Название мероприятия</label>
                        <input type="text" id="title" name="title" 
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                               required
                               placeholder="Введите название мероприятия">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Описание</label>
                        <textarea id="description" name="description" rows="4"
                                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                                  required
                                  placeholder="Опишите ваше мероприятие"></textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Дата и время начала</label>
                            <input type="datetime-local" id="start_time" name="start_time" 
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                                   required>
                            @error('start_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">Дата и время окончания</label>
                            <input type="datetime-local" id="end_time" name="end_time" 
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                                   required>
                            @error('end_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Место проведения</label>
                        <input type="text" id="location" name="location" 
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                               placeholder="Укажите место проведения (необязательно)">
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('communities.show', $community) }}" 
                           class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                            Отмена
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                            Создать мероприятие
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Устанавливаем минимальную дату (текущую) для полей времени
            const now = new Date();
            const timezoneOffset = now.getTimezoneOffset() * 60000;
            const localISOTime = (new Date(now - timezoneOffset)).toISOString().slice(0, -8);
            
            document.getElementById('start_time').min = localISOTime;
            document.getElementById('start_time').addEventListener('change', function() {
                document.getElementById('end_time').min = this.value;
            });
        });
    </script>
</x-app-layout>