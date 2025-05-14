<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Post;
use App\Models\Activity;
use App\Models\Friend;
use App\Models\Topic;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile page (new function)
     */
    public function show(Request $request): View
    {
        $user = Auth::user();
        $topics = Topic::all();
        $activeTab = $request->get('tab', 'my_posts'); // Определяем активную вкладку

        $user->load(['communities' => function($query) {
            $query->withCount('members')->latest()->limit(6);
        }]);
    
        // Получаем посты в зависимости от выбранной вкладки
        if ($activeTab === 'communities') {
            $posts = Post::whereHas('communities', function($query) use ($user) {
                    $query->whereIn('communities.id', $user->communities()->pluck('communities.id'));
                })
                ->withCount(['comments', 'favourite'])
                ->latest()
                ->paginate(10);
        } else {
            $posts = $user->posts()->withCount(['comments', 'favourite'])->latest()->paginate(10);
        }

    
        // Получаем активности друзей
        $recentActivities = Activity::when($user->friends()->exists(), function($query) use ($user) {
                return $query->whereIn('user_id', $user->friends()->pluck('users.id'));
            })
            ->with(['user', 'subject'])
            ->latest()
            ->limit(5)
            ->get();
        
        // Получаем рекомендуемых пользователей (исключаем уже подписанных)
        $recommendedUsers = User::whereNotIn('id', function($query) use ($user) {
                $query->select('friend_id')
                    ->from('friends')
                    ->where('user_id', $user->id);
            })
            ->whereNotIn('id', function($query) use ($user) {
                $query->select('user_id')
                    ->from('friends')
                    ->where('friend_id', $user->id);
            })
            ->whereNotIn('id', $user->subscriptions()->pluck('author_id'))
            ->where('id', '!=', $user->id)
            ->inRandomOrder()
            ->limit(5)
            ->get()
            ->each(function($userRec) use ($user) {
                $userRec->mutualFriendsCount = $userRec->mutualFriendsCount($user->id);
            });
    
        return view('profile.show', compact(
            'user', 
            'posts', 
            'recentActivities', 
            'recommendedUsers', 
            'topics',
            'activeTab'
        ));
    }

    /**
     * Display public profile page (new function)
     */
    public function showPublic(User $user): View
    {
        $posts = $user->posts()
            ->withCount(['comments', 'favourite'])
            ->latest()
            ->paginate(10);
    
        $mutualFriendsCount = auth()->check() ? $user->mutualFriendsCount(auth()->id()) : 0;
        
        // Получаем список общих друзей только если пользователь авторизован
        $mutualFriends = [];
        if (auth()->check() && $mutualFriendsCount > 0) {
            $mutualFriends = $user->friends()
                ->whereIn('users.id', auth()->user()->friends()->pluck('users.id'))
                ->limit(6)
                ->get();
        }
    
        return view('profile.public', compact('user', 'posts', 'mutualFriendsCount', 'mutualFriends'));
    }

    /**
     * Display the user's profile form (existing function - unchanged)
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information (enhanced existing function)
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        // Handle email verification
        if ($user->isDirty('email')) {
            $data['email_verified_at'] = null;
        }

        $user->update($data);

        return Redirect::route('profile.show')->with('status', 'profile-updated');
    }

    public function updateInfo(Request $request): RedirectResponse
{
    $request->validate([
        'about' => 'nullable|string|max:500',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    $user = $request->user();
    $data = $request->only('about');

    if ($request->hasFile('avatar')) {
        // Удаляем старый аватар, если он существует
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
        
        $path = $request->file('avatar')->store('avatars', 'public');
        $data['avatar'] = $path;
    }

    $user->update($data);

    return redirect()->route('profile.show')->with('status', 'profile-info-updated');
}

    /**
     * Delete the user's account (existing function - unchanged)
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Get recent activities for the user (new helper method)
     */
    private function getRecentActivities()
    {
        return Activity::whereIn('user_id', function($query) {
                $query->select('friend_id')
                    ->from('friends')
                    ->where('user_id', Auth::id())
                    ->where('status', 'accepted');
            })
            ->orWhereIn('user_id', function($query) {
                $query->select('user_id')
                    ->from('friends')
                    ->where('friend_id', Auth::id())
                    ->where('status', 'accepted');
            })
            ->with('user')
            ->latest()
            ->limit(5)
            ->get();
    }

    /**
     * Get recommended users (new helper method)
     */
    private function getRecommendedUsers()
    {
        return User::whereNotIn('id', function($query) {
                $query->select('friend_id')
                    ->from('friends')
                    ->where('user_id', Auth::id());
            })
            ->whereNotIn('id', function($query) {
                $query->select('user_id')
                    ->from('friends')
                    ->where('friend_id', Auth::id());
            })
            ->where('id', '!=', Auth::id())
            ->inRandomOrder()
            ->limit(5)
            ->get()
            ->each(function($user) {
                $user->mutualFriendsCount = $user->mutualFriendsCount(Auth::id());
            });
    }
}