<x-app-layout>
    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h1 class="text-2xl font-bold mb-6">Создать новое сообщество</h1>
                
                <form action="{{ route('communities.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Название сообщества</label>
                        <input type="text" id="name" name="name" 
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                               required
                               placeholder="Введите название сообщества">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Описание</label>
                        <textarea id="description" name="description" rows="4"
                                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                                  required
                                  placeholder="Расскажите о вашем сообществе"></textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="avatar" class="block text-sm font-medium text-gray-700 mb-1">Аватар сообщества</label>
                        <input type="file" id="avatar" name="avatar" 
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                               accept="image/*">
                        @error('avatar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Максимальный размер файла: 2MB</p>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('communities.index') }}" 
                           class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                            Отмена
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                            Создать сообщество
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>