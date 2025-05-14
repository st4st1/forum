<x-app-layout>
    <section class="bg-gray-100 py-10">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold mb-8 text-center">{{ $topic->title }}</h1>

            <div class="space-y-4">
                <p class="text-xl">Название:</p>
                <p>{{ $topic->title }}</p>

                <a href="{{ route('topics.index') }}" 
                   class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Вернуться к категориям
                </a>
            </div>
        </div>
    </section>
</x-app-layout>