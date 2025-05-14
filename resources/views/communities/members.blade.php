<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Управление участниками сообщества "{{ $community->name }}"</h1>
                <a href="{{ route('communities.show', $community) }}" 
                   class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                    Назад к сообществу
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Участник</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Роль</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата вступления</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($members as $member)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <img src="{{ $member->avatar_url }}" 
                                             class="w-10 h-10 rounded-full mr-3"
                                             alt="{{ $member->name }}">
                                        <div>
                                            <div class="font-medium">{{ $member->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $member->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($member->id == $community->creator_id)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                            Создатель
                                        </span>
                                    @else
                                        <form method="post" action="{{ route('communities.members.update', [$community, $member]) }}" class="flex items-center gap-2">
                                            @csrf @method('PUT')
                                            <select name="role" class="border rounded px-2 py-1 text-sm">
                                                <option value="member" {{ $member->pivot->role == 'member' ? 'selected' : '' }}>Участник</option>
                                                <option value="moderator" {{ $member->pivot->role == 'moderator' ? 'selected' : '' }}>Модератор</option>
                                            </select>
                                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded transition text-xs">
                                                Сохранить
                                            </button>
                                        </form>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $member->pivot->created_at->format('d.m.Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($member->id != $community->creator_id)
                                        <form method="post" action="{{ route('communities.members.remove', [$community, $member]) }}">
                                            @csrf @method('DELETE')
                                            <button type="submit" 
                                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded transition text-xs"
                                                    onclick="return confirm('Вы уверены, что хотите удалить этого участника?')">
                                                Удалить
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>