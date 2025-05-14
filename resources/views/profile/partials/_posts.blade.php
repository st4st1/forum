<div class="space-y-6">
    @foreach($posts as $post)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <!-- Содержимое поста -->
        </div>
    @endforeach
    {{ $posts->links() }}
</div>