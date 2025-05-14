<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h1 class="text-2xl font-bold">{{ $event->title }}</h1>
                            <p class="text-gray-500">Сообщество: 
                                <a href="{{ route('communities.show', $event->community) }}" 
                                   class="text-blue-500 hover:underline">
                                    {{ $event->community->name }}
                                </a>
                            </p>
                        </div>
                        <div class="flex space-x-2">
                            @if($isParticipant)
                                <form action="{{ route('events.cancel', $event) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                                        Отменить участие
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('events.participate', $event) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                                        Участвовать
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="font-semibold mb-2">Дата и время</h3>
                            <p>
                                {{ $event->start_time->format('d.m.Y H:i') }} - 
                                {{ $event->end_time->format('H:i') }}
                            </p>
                        </div>
                        <div>
                            <h3 class="font-semibold mb-2">Место проведения</h3>
                            <p>{{ $event->location ?? 'Не указано' }}</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="font-semibold mb-2">Описание</h3>
                        <div class="whitespace-pre-line">{{ $event->description }}</div>
                    </div>

                    <div>
                        <h3 class="font-semibold mb-3">Участники ({{ $event->participants->count() }})</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                            @foreach($event->participants as $participant)
                                <a href="{{ route('profile.public', $participant) }}" 
                                   class="flex flex-col items-center">
                                    <img src="{{ $participant->avatar_url }}" 
                                         class="w-12 h-12 rounded-full mb-1"
                                         alt="{{ $participant->name }}">
                                    <span class="text-sm text-center">{{ $participant->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>