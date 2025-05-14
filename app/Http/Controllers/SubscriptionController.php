<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SubscriptionController extends Controller
{
    public function subscribe(User $author)
    {
        auth()->user()->subscriptions()->syncWithoutDetaching([$author->id]);
        return back()->with('success', 'Вы подписались на автора');
    }

    public function unsubscribe(User $author)
    {
        auth()->user()->subscriptions()->detach($author->id);
        return back()->with('success', 'Вы отписались от автора');
    }

    public function index()
    {
        $subscriptions = auth()->user()
            ->subscriptions()
            ->withCount(['posts', 'friends'])
            ->orderBy('name')
            ->paginate(10);
    
        // Получаем рекомендуемых пользователей (исключая уже подписанных)
        $recommendedUsers = User::whereNotIn('id', auth()->user()->subscriptions()->pluck('author_id'))
            ->where('id', '!=', auth()->id())
            ->inRandomOrder()
            ->limit(6)
            ->get()
            ->each(function($user) {
                $user->mutualFriendsCount = $user->mutualFriendsCount(auth()->id());
            });
        
        return view('subscriptions.index', compact('subscriptions', 'recommendedUsers'));
    }
}
