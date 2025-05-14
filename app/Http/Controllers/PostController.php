<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Topic;
use App\Models\User;
use App\Models\Comment;
use App\Models\Favourite;
use App\Models\Community;
use App\Services\ModerationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     protected $moderationService;

     public function __construct(ModerationService $moderationService)
     {
         $this->moderationService = $moderationService;
     }
    
     public function index_adm()
     {
        $topics = Topic::all();
        $users = User::all();
    
        return view('admin.create-post', compact('topics', 'users'));
     }

     
     public function index_by_topic(Request $request, Topic $topic)
    {
        $posts = $topic->posts()
                  ->withCount('comments')
                  ->orderBy('created_at', 'desc')
                  ->paginate(10);
        $topics = Topic::all();
        return view('main', compact('posts', 'topics'));
    }
     

 
     public function index_popl()
    {
        $posts = Post::withCount('comments')
            ->where('status', 'passed moderator verification')
            ->orderBy('comments_count', 'desc')
            ->take(6)
            ->get();
        return view('welcome', compact('posts'));
    }

    
    public function index()
    {
        $posts = Post::withCount('comments')
                  ->passedModeration()
                  ->orderBy('created_at', 'desc')
                  ->paginate(10);
        $topics = Topic::all();
    
        return view('main', compact('posts', 'topics'));
    }
    

    public function index_adm_post()
    {
        $posts = Post::passedModeration()->paginate(10);;
        $topics = Topic::all();
        return view('admin.admin_post', compact('posts', 'topics'));
    }

    public function index_moder_post()
    {
        $posts = Post::Moderation()->paginate(10);;
        $topics = Topic::all();
        return view('admin.moder_post', compact('posts', 'topics'));
    }

    public function rejectPost(Post $post)
    {
        $post->status = 'rejected';
        $post->save();

        return redirect()->route('moder.posts.index')->with('success', 'Публикация отменена.');
    }

    public function approvePost(Post $post)
    {
        $post->status = 'passed moderator verification';
        $post->save();

        return redirect()->route('moder.posts.index')->with('success', 'Публикация разрешена.');
    }

    public function search(Request $request)
    {
       $searchQuery = $request->input('search');

       if (!$searchQuery) {
           abort(404);
       }

       $posts = Post::withCount('comments')
                 ->where(function ($query) use ($searchQuery) {
                     $query->where('title', 'like', '%' . $searchQuery . '%')
                           ->orWhere('content', 'like', '%' . $searchQuery . '%');
                 })
                 ->orderBy('created_at', 'desc')
                 ->paginate(10);
       
       $topics = Topic::all();

       return view('main', compact('posts', 'topics'));
    }


    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.posts.index');
    }

    public function show_post(Request $request, Post $post) {
        $post->load('user', 'comments');
        $comments = $post->comments()->paginate(5); // Пагинация по 5 комментариев на страницу
        return view('post', compact('post', 'comments'));
    }

    public function destroy_report(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.reported.posts')->with('success', 'Пост успешно удален.');
    }

    public function storeComment(Request $request, Post $post)
    {
        $validatedData = $request->validate([
            'content' => 'required|string',
        ]);
    
        $comment = Comment::create([
            'content' => $validatedData['content'],
            'post_id' => $post->id,
            'user_id' => auth()->id(),
        ]);
    
        return back();
    }

    public function destroyComment(Comment $comment)
{
    // Проверка, что комментарий принадлежит текущему пользователю
    if ($comment->user_id !== auth()->id()) {
        return redirect()->back()->with('error', 'Вы не можете удалить этот комментарий.');
    }

    $comment->delete();

    return redirect()->back()->with('success', 'Комментарий успешно удален.');
}

public function updateComment(Request $request, Comment $comment)
{
    // Проверка, что комментарий принадлежит текущему пользователю
    if ($comment->user_id !== auth()->id()) {
        return response()->json(['error' => 'Вы не можете редактировать этот комментарий.'], 403);
    }

    $validatedData = $request->validate([
        'content' => 'required|string',
    ]);

    $comment->update($validatedData);

    return response()->json(['success' => 'Комментарий успешно обновлен.', 'content' => $comment->content]);
}

    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'topic_id' => 'required|exists:topics,id',
            'content' => 'required|array',
            'content.*' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $status = 'passed automatic verification';

        // Модерация заголовка
        if (!$this->moderationService->moderateText($validatedData['title'])) {
            $status = 'rejected';
        }

        // Модерация текстового содержания
        foreach ($validatedData['content'] as $index => $text) {
            if (!$this->moderationService->moderateText($text)) {
                $status = 'rejected';
            }
        }

        // Подготовка контента
        $content = [];
        foreach ($validatedData['content'] as $index => $text) {
            $content[] = [
                'type' => 'text',
                'content' => $text,
            ];

            if ($request->hasFile('images') && isset($request->file('images')[$index])) {
                $image = $request->file('images')[$index];
                $imagePath = $image->store('images', 'public');

                $content[] = [
                    'type' => 'image',
                    'content' => $imagePath,
                ];
            }
        }

        // Получение текущего пользователя
        $user = $request->user();

        // Сохранение поста в базу данных
        $post = Post::create([
            'title' => $validatedData['title'],
            'topic_id' => $validatedData['topic_id'],
            'content' => json_encode($content),
            'status' => $status,
            'user_id' => $user->id, // Добавляем user_id
        ]);

        

        return redirect()->route('admin.posts.index', $post->id)->with('success', 'Пост успешно создан.');
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $post = Post::find($request->id);
        
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post->update($validatedData);

        return redirect()->route('admin.posts.index');
    }

    /**
     * Remove the specified resource from storage.
     */

     public function index_user()
     {
        $posts = auth()->user()->posts()
        ->PassedModeration()
        ->get();
        return view('posts.index', compact('posts'));
     }

     public function create_user()
    {
        $topics = Topic::all();
        return view('posts.create', compact('topics'));
    }

    public function store_user(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'topic_id' => 'required|exists:topics,id',
            'content' => 'required|array',
            'content.*' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'communities' => 'nullable|array',
            'communities.*' => 'exists:communities,id'
        ]);

        $status = 'passed automatic verification';

        // Модерация заголовка
        if (!$this->moderationService->moderateText($validatedData['title'])) {
            $status = 'rejected';
        }

        // Модерация текстового содержания
        foreach ($validatedData['content'] as $index => $text) {
            if (!$this->moderationService->moderateText($text)) {
                $status = 'rejected';
            }
        }

        // Подготовка контента
        $content = [];
        foreach ($validatedData['content'] as $index => $text) {
            $content[] = [
                'type' => 'text',
                'content' => $text,
            ];

            if ($request->hasFile('images') && isset($request->file('images')[$index])) {
                $image = $request->file('images')[$index];
                $imagePath = $image->store('images', 'public');

                $content[] = [
                    'type' => 'image',
                    'content' => $imagePath,
                ];
            }
        }

        // Создание поста
        $post = Post::create([
            'title' => $validatedData['title'],
            'topic_id' => $validatedData['topic_id'],
            'content' => json_encode($content),
            'status' => $status,
            'user_id' => auth()->id(),
        ]);

        // Привязка поста к выбранным сообществам
        if ($request->has('communities')) {
            $post->communities()->attach($request->communities);
        }

        return redirect()->route('posts.index', $post->id)->with('success', 'Пост успешно создан.');
    }

    public function show_user(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function edit_user(Post $post)
    {
        $topics = Topic::all();
        return view('posts.edit', compact('post', 'topics'));
    }

    public function update_user(Request $request, Post $post)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'topic_id' => 'required|exists:topics,id',
            'content' => 'required|array',
            'content.*' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'new_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'communities' => 'nullable|array',
            'communities.*' => 'exists:communities,id'
        ]);
    
        // Модерация заголовка
        if (!$this->moderationService->moderateText($validatedData['title'])) {
            $status = 'rejected';
        } else {
            $status = 'passed automatic verification';
        }
    
        // Модерация текстового содержания
        foreach ($validatedData['content'] as $index => $text) {
            if (!$this->moderationService->moderateText($text)) {
                $status = 'rejected';
            }
        }
    
        // Подготовка контента
        $content = [];
        foreach ($validatedData['content'] as $index => $text) {
            $content[] = [
                'type' => 'text',
                'content' => $text,
            ];
        }
    
        // Обработка замены изображений
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $oldImagePath => $newImage) {
                // Удаление старого изображения
                Storage::disk('public')->delete($oldImagePath);
            
                // Сохранение нового изображения
                $imagePath = $newImage->store('images', 'public');
            
                // Добавление нового изображения в контент
                $content[] = [
                    'type' => 'image',
                    'content' => $imagePath,
                ];
            }
        }
    
        // Обработка нового изображения
        if ($request->hasFile('new_image')) {
            $newImagePath = $request->file('new_image')->store('images', 'public');
        
            // Добавление нового изображения в контент
            $content[] = [
                'type' => 'image',
                'content' => $newImagePath,
            ];
        }
    
        // Обновление поста в базе данных
        $post->update([
            'title' => $validatedData['title'],
            'topic_id' => $validatedData['topic_id'],
            'content' => json_encode($content),
            'status' => $status,
        ]);

         // Синхронизация привязки к сообществам
        if ($request->has('communities')) {
            $post->communities()->sync($request->communities);
        } else {
            $post->communities()->detach();
        }
    
        return redirect()->route('posts.index')
                         ->with('success', 'Post updated successfully.');
    }

    public function destroy_user(Post $post)
    {
        // Получаем контент поста
        $content = json_decode($post->content);
    
        // Удаляем все изображения, связанные с постом
        foreach ($content as $item) {
            if ($item->type === 'image') {
                // Удаляем изображение из папки storage
                Storage::disk('public')->delete($item->content);
            }
        }
    
        // Удаляем сам пост
        $post->delete();
    
        return redirect()->route('posts.index')
                        ->with('success', 'Post and associated images deleted successfully.');
    }

    public function rejectedPosts()
    {
        $user = Auth::user();
        $posts = Post::NotModeration()
                     ->where('user_id', $user->id)
                     ->get();
        return view('posts.rejected', compact('posts'));
    }

    public function pendingPosts()
    {
        $user = Auth::user();
        $posts = Post::Moderation()
                     ->where('user_id', $user->id)
                     ->get();
        return view('posts.pending', compact('posts'));
    }

    public function recalledPosts()
    {
        $user = Auth::user();
        $posts = Post::Recalled()
                     ->where('user_id', $user->id)
                     ->get();
        return view('posts.recalled', compact('posts'));
    }

    public function recallPost(Post $post)
    {
        $post->status = 'the post has been recalled to users';
        $post->save();

        return redirect()->route('posts.pending')->with('success', 'Пост отозван.');
    }

   

    public function toggleFavourite(Request $request, $postId)
    {
        $post = Post::findOrFail($postId);
        $user = $request->user();

        if (!$post->isFavourited($user->id)) {
            Favourite::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
            ]);
        } else {
            $post->favourite()->where('user_id', $user->id)->delete();
        }

        return redirect()->route('post', ['post' => $post]);
    }

    public function removeFromCommunity(Post $post, Community $community)
    {
        // Проверяем, что пользователь - создатель или модератор сообщества
        $isCreator = $community->creator_id == auth()->id();
        $isModerator = $community->members()
            ->where('user_id', auth()->id())
            ->where('community_members.role', 'moderator')
            ->exists();

        if (!$isCreator && !$isModerator) {
            abort(403, 'У вас нет прав для этого действия');
        }

        // Удаляем связь поста с сообществом
        $post->communities()->detach($community->id);

        return back()->with('success', 'Пост удален из сообщества');
    }
}
