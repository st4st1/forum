<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TelegramAuthController;

// Существующие маршруты
Route::get('/', [PostController::class, 'index_popl'])->name('welcome');
Route::get('/users/stat', [UserController::class, 'index_stat'])->name('users_stat');
Route::get('/main', [PostController::class, 'index'])->name('main');

// Маршруты постов и комментариев
Route::get('/post/{post}', [PostController::class, 'show_post'])->name('post');
Route::post('/comment/{post}', [PostController::class, 'storeComment'])->name('comment.store');
Route::delete('/comments/{comment}', [PostController::class, 'destroyComment'])->name('comments.destroy');
Route::put('/comments/{comment}', [PostController::class, 'updateComment'])->name('comments.update');

Route::delete('/posts/{post}/remove_from_community/{community}', [PostController::class, 'removeFromCommunity'])->name('posts.remove_from_community');

Route::get('/rejected', [PostController::class, 'rejectedPosts'])->name('posts.rejected');
Route::get('/pending', [PostController::class, 'pendingPosts'])->name('posts.pending');
Route::get('/recalled', [PostController::class, 'recalledPosts'])->name('posts.recalled');
Route::put('/{post}/recall', [PostController::class, 'recallPost'])->name('posts.recall');

Route::post('/reports', [ReportController::class, 'store'])->name('reports.store')->middleware('auth');
Route::get('/admin/reported-posts', [ReportController::class, 'reportedPosts'])->name('admin.reported.posts');
Route::delete('/posts/{post}', [PostController::class, 'destroy_report'])->name('posts.destroy');
Route::delete('/reports/{post}/reject', [ReportController::class, 'rejectReports'])->name('reports.reject');

Route::prefix('create')->group(function () {
    Route::get('/create-post', [PostController::class, 'index_adm'])->name('posts.create.adm');
    Route::post('/store-post', [PostController::class, 'store'])->name('adm.posts.store');
});

Route::get('/posts/search', [PostController::class, 'search'])->name('posts.search');
Route::get('/category/{topic}', [PostController::class, 'index_by_topic'])->name('posts.category');

Route::post('/posts/{postId}/toggle-favourite', [PostController::class, 'toggleFavourite'])->name('posts.toggleFavourite');
Route::get('/favourites', [FavouriteController::class, 'index'])->name('favourites.index');
Route::delete('favourites/{id}', [FavouriteController::class, 'destroy'])->name('favourites.destroy');

Route::resource('topics', TopicController::class);

Route::middleware(['auth'])->group(function () {
    Route::get('/posts', [PostController::class, 'index_user'])->name('posts.index');
    Route::get('/posts/create', [PostController::class, 'create_user'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store_user'])->name('posts.store');
    Route::get('/posts/{post}', [PostController::class, 'show_user'])->name('posts.show');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit_user'])->name('posts.edit');
    Route::patch('/posts/{post}', [PostController::class, 'update_user'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy_user'])->name('posts.destroy');
    Route::post('/profile/update-info', [ProfileController::class, 'updateInfo'])->name('profile.update-info');
    Route::post('/authors/{author}/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscriptions.subscribe');
    Route::delete('/authors/{author}/unsubscribe', [SubscriptionController::class, 'unsubscribe'])->name('subscriptions.unsubscribe');
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
});

Route::post('/posts/store_user', [PostController::class, 'store_user'])->name('posts.store_user');

Route::prefix('adm-user')->group(function () {
    Route::get('/users', [UserController::class, 'index1'])->name('admin.users.index');
    Route::put('/users/{user}/role', [UserController::class, 'updateRole'])->name('admin.users.updateRole');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
});

Route::prefix('adm-post')->group(function () {
    Route::get('/posts', [PostController::class, 'index_adm_post'])->name('admin.posts.index');
    Route::get('/postss', [PostController::class, 'index_moder_post'])->name('moder.posts.index');
    Route::put('/posts/{id}', [PostController::class, 'update'])->name('admin.posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('admin.posts.destroy');
    Route::put('/posts/{post}/reject', [PostController::class, 'rejectPost'])->name('moder.posts.reject');
    Route::put('/posts/{post}/approve', [PostController::class, 'approvePost'])->name('moder.posts.approve');
});

// Новые маршруты для профиля и друзей
Route::middleware(['auth'])->group(function () {
    // Расширенные маршруты профиля
    Route::get('/profile/show', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    
    // Публичный профиль
    Route::get('/user/{user}', [ProfileController::class, 'showPublic'])->name('profile.public');
    
    // Маршруты друзей
    Route::prefix('friends')->group(function () {
        Route::get('/', [FriendController::class, 'index'])->name('friends.index');
        Route::post('/{user}/add', [FriendController::class, 'add'])->name('friends.add');
        Route::post('/{friend}/accept', [FriendController::class, 'accept'])->name('friends.accept');
        Route::delete('/{friend}/remove', [FriendController::class, 'remove'])->name('friends.remove');
    });
    
    // Существующий маршрут dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Существующие маршруты профиля Laravel Breeze (оставляем без изменений)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Маршруты сообществ
Route::prefix('communities')->group(function () {
    Route::get('/', [CommunityController::class, 'index'])->name('communities.index');
    Route::get('/create', [CommunityController::class, 'create'])->name('communities.create');
    Route::post('/', [CommunityController::class, 'store'])->name('communities.store');
    Route::get('/{community}', [CommunityController::class, 'show'])->name('communities.show');
    Route::post('/{community}/join', [CommunityController::class, 'join'])->name('communities.join');
    Route::post('/{community}/leave', [CommunityController::class, 'leave'])->name('communities.leave');
    Route::put('/{community}', [CommunityController::class, 'update'])->name('communities.update');
    Route::get('/{community}/members', [CommunityController::class, 'membersIndex'])->name('communities.members.index');
    Route::put('/{community}/members/{user}', [CommunityController::class, 'updateMember'])->name('communities.members.update');
    Route::delete('/{community}/members/{user}', [CommunityController::class, 'removeMember'])->name('communities.members.remove');
});

// Маршруты мероприятий
Route::prefix('events')->group(function () {
    Route::get('/{community}/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/{community}', [EventController::class, 'store'])->name('events.store');
    Route::get('/{event}', [EventController::class, 'show'])->name('events.show');
    Route::post('/{event}/participate', [EventController::class, 'participate'])->name('events.participate');
    Route::post('/{event}/cancel', [EventController::class, 'cancelParticipation'])->name('events.cancel');
});

Route::get('/auth/telegram', [TelegramAuthController::class, 'handle'])
     ->name('auth.telegram')
     ->middleware('web'); // Добавляем middleware web


});

require __DIR__.'/auth.php';