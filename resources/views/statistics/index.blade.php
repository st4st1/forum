<x-app-layout>
    <section class="bg-gray-100 py-10">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold mb-8 text-center">Рейтинг пользователей</h1>

            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Карточка топ авторов -->
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                        <h2 class="text-xl font-semibold mb-4 text-blue-800">Топ авторов</h2>
                        <div class="space-y-3">
                            @foreach($users->sortByDesc('posts_count')->take(3) as $user)
                            <div class="flex items-center justify-between bg-white p-3 rounded-lg shadow-sm">
                                <div class="flex items-center">
                                    <img src="{{ $user->avatar_url }}" class="w-10 h-10 rounded-full mr-3" alt="{{ $user->name }}">
                                    <span class="font-medium">{{ $user->name }}</span>
                                </div>
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm font-medium">
                                    {{ $user->posts_count }} постов
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Карточка топ комментаторов -->
                    <div class="bg-green-50 rounded-lg p-4 border border-green-100">
                        <h2 class="text-xl font-semibold mb-4 text-green-800">Топ комментаторов</h2>
                        <div class="space-y-3">
                            @foreach($users->sortByDesc('comments_count')->take(3) as $user)
                            <div class="flex items-center justify-between bg-white p-3 rounded-lg shadow-sm">
                                <div class="flex items-center">
                                    <img src="{{ $user->avatar_url }}" class="w-10 h-10 rounded-full mr-3" alt="{{ $user->name }}">
                                    <span class="font-medium">{{ $user->name }}</span>
                                </div>
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm font-medium">
                                    {{ $user->comments_count }} комментариев
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto bg-white rounded-lg shadow-md">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Пользователь
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Посты
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Комментарии
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Активность
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" src="{{ $user->avatar_url }}" alt="{{ $user->name }}">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('profile.public', $user) }}" class="hover:text-blue-600">{{ $user->name }}</a>
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $user->posts_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $user->comments_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($user->last_activity)
                                    {{ $user->last_activity->diffForHumans() }}
                                @else
                                    Недавно
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex justify-center">
                {{ $users->links() }}
            </div>
        </div>
    </section>
</x-app-layout>