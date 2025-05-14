<x-app-layout>
    <section class="bg-gray-100 py-20">
        <div class="relative h-64 w-full mb-8">
            <img src="\photo\glavnay.jpg" alt="Туристический форум" class="w-full h-full object-cover object-center">
            <div class="absolute inset-0 bg-black opacity-50"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <h2 class="text-white text-3xl font-bold tracking-tight">
                    Туристический форум
                </h2>
                <p class="mt-2 text-gray-300">
                    Общайтесь с другими путешественниками, делись опытом и советом!
                </p>
            </div>
        </div>

        <section class="bg-white p-5 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-4 text-center">Популярные посты</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($posts as $post)
                    <div class="post p-5 border border-gray-200 rounded-lg shadow-sm" data-id="{{$post->id}}">
                        <h3 class="text-lg font-semibold text-gray-800">{{$post->title}}</h3>
                        <div class="text-gray-600 mt-2 overflow-hidden whitespace-pre-wrap break-words text-left">
                            @php
                                $firstTextBlock = true;
                            @endphp
                            @foreach(json_decode($post->content) as $item)
                                @if($item->type === 'text' && $firstTextBlock)
                                    {{ Str::limit($item->content, 100) }}
                                    @php
                                        $firstTextBlock = false;
                                    @endphp
                                @endif
                            @endforeach
                        </div>
                        <p class="text-gray-500 mt-2">{{$post->created_at}}</p>
                        <x-secondary-button class="mt-4">
                            <a href="{{route('post', $post->id)}}" class="hover:text-blue-600">{{ __('Подробней') }}</a>
                        </x-secondary-button>
                    </div>
                @endforeach
            </div>
        </section>
    </section>
</x-app-layout>