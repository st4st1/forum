<x-app-layout>
    <div class="bg-gray-100 min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex justify-between items-center">
                <h1 class="text-3xl font-bold text-gray-900">Избранные посты</h1>
                <a href="{{ route('main') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition">
                    Все посты
                </a>
            </div>

            @if($favourites->isEmpty())
                <div class="bg-white rounded-lg shadow-md p-8 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <h3 class="text-xl font-medium text-gray-700 mb-2">У вас пока нет избранных постов</h3>
                    <p class="text-gray-500">Добавляйте понравившиеся посты в избранное, чтобы вернуться к ним позже</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($favourites as $favourite)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300 flex flex-col h-full">
                            <div class="p-6 flex-grow">
                                <h3 class="text-xl font-semibold mb-3 text-gray-900 truncate">{{ $favourite->post->title }}</h3>
                                <div class="text-gray-600 mb-4 line-clamp-3 break-words">
                                    @php
                                        $firstTextBlock = true;
                                    @endphp
                                    @foreach(json_decode($favourite->post->content) as $item)
                                        @if($item->type === 'text' && $firstTextBlock)
                                            {{ Str::limit($item->content, 100) }}
                                            @php $firstTextBlock = false; @endphp
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="px-6 pb-4">
                                <div class="flex justify-between items-center gap-2">
                                    <a href="{{ route('post', $favourite->post->id) }}" 
                                       class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition text-sm flex-grow text-center">
                                        Читать
                                    </a>
                                    <form method="POST" action="{{ route('favourites.destroy', $favourite->id) }}" class="flex-grow">
                                        @csrf @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition text-sm w-full">
                                            Удалить
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $favourites->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>