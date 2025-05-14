<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    public function index()
    {
        $friends = auth()->user()->friends()->paginate(10);
        $requests = auth()->user()->friendRequests()->paginate(10);
        
        return view('friends.index', compact('friends', 'requests'));
    }

    public function add(User $user)
    {
        auth()->user()->addFriend($user);
        return back()->with('success', 'Запрос в друзья отправлен');
    }

    public function accept(User $friend)
    {
        auth()->user()->acceptFriend($friend);
        return back()->with('success', 'Запрос в друзья принят');
    }

    public function remove(User $friend)
    {
        auth()->user()->removeFriend($friend);
        return back()->with('success', 'Друг удален');
    }
}