<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold">Сообщества</h1>
                <a href="{{ route('communities.create') }}" 
                   class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    Создать сообщество
                </a>
            </div>

            <!-- Поиск сообществ -->
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <form action="{{ route('communities.index') }}" method="GET" class="flex">
                    <input type="text" name="search" 
                           class="flex-grow px-4 py-2 border rounded-l-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="Поиск сообществ"
                           value="{{ request('search') }}">
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-500 text-white rounded-r-lg hover:bg-blue-600">
                        Найти
                    </button>
                </form>
            </div>

            <!-- Мои сообщества -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Мои сообщества</h2>
                @if(auth()->user()->communities->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach(auth()->user()->communities as $community)
                            <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-md transition-shadow">
                                <a href="{{ route('communities.show', $community) }}">
                                    <div class="h-40 bg-gray-200 flex items-center justify-center">
                                        @if($community->avatar)
                                            <img src="{{ asset('storage/' . $community->avatar) }}" 
                                                 alt="{{ $community->name }}"
                                                 class="h-full w-full object-cover">
                                        @else
                                            <span class="text-4xl font-bold text-gray-500">
                                                {{ Str::limit($community->name, 1, '') }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="p-4">
                                        <h3 class="font-bold">{{ $community->name }}</h3>
                                        <p class="text-sm text-gray-500 mt-1">
                                            {{ $community->members_count }} участников
                                        </p>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-lg shadow p-6 text-center">
                        <p class="text-gray-500 mb-4">Вы пока не состоите в сообществах</p>
                    </div>
                @endif
            </div>

            <!-- Все сообщества -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Все сообщества</h2>
                @if($communities->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($communities as $community)
                            <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-md transition-shadow">
                                <a href="{{ route('communities.show', $community) }}">
                                    <div class="h-40 bg-gray-200 flex items-center justify-center">
                                        @if($community->avatar)
                                            <img src="{{ asset('storage/' . $community->avatar) }}" 
                                                 alt="{{ $community->name }}"
                                                 class="h-full w-full object-cover">
                                        @else
                                            <span class="text-4xl font-bold text-gray-500">
                                                {{ Str::limit($community->name, 1, '') }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="p-4">
                                        <h3 class="font-bold">{{ $community->name }}</h3>
                                        <p class="text-sm text-gray-500 mt-1">
                                            {{ $community->members_count }} участников
                                        </p>
                                        <div class="mt-3">
                                            @if(auth()->user()->communities->contains($community))
                                                <form action="{{ route('communities.leave', $community) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="w-full px-3 py-1 bg-red-500 text-white rounded text-sm">
                                                        Покинуть
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('communities.join', $community) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="w-full px-3 py-1 bg-green-500 text-white rounded text-sm">
                                                        Вступить
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        {{ $communities->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-lg shadow p-6 text-center">
                        <p class="text-gray-500">Сообщества не найдены</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>