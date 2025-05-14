<div class="bg-white rounded-lg shadow p-6">
    <h3 class="font-semibold text-lg mb-4">Информация</h3>
    <div class="space-y-3">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
            </svg>
            <span>Город: {{ Auth::user()->city ?? 'Не указан' }}</span>
        </div>
        <!-- Добавьте другие поля информации -->
    </div>
</div>