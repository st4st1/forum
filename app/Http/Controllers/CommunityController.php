<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Community;
use App\Models\User;

class CommunityController extends Controller
{
    public function index()
    {
        $query = Community::query()->withCount('members');

        if (request()->has('search')) {
            $query->where('name', 'like', '%' . request('search') . '%');
        }

        $communities = $query->paginate(12);
        $userCommunities = auth()->user()->communities()->pluck('communities.id');

        return view('communities.index', compact('communities', 'userCommunities'));
    }

    public function create()
    {
        return view('communities.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $community = Community::create([
            'name' => $request->name,
            'description' => $request->description,
            'creator_id' => auth()->id(),
        ]);

        if ($request->hasFile('avatar')) {
            $community->avatar = $request->file('avatar')->store('community-avatars', 'public');
            $community->save();
        }

        // Автор автоматически становится админом
        $community->members()->attach(auth()->id(), ['role' => 'admin']);

        return redirect()->route('communities.show', $community);
    }

    public function show(Community $community)
    {
        $posts = $community->posts()
            ->with(['user', 'comments'])
            ->withCount(['comments', 'favourite'])
            ->latest()
            ->paginate(10);
    
        // Явно преобразуем start_time в Carbon
        $events = $community->events()
            ->where('start_time', '>', now())
            ->orderBy('start_time')
            ->limit(3)
            ->get()
            ->map(function ($event) {
                $event->start_time = \Carbon\Carbon::parse($event->start_time);
                return $event;
            });
        
        $isMember = $community->isMember(auth()->user());
        
        return view('communities.show', compact('community', 'posts', 'events', 'isMember'));
    }

    public function join(Community $community)
    {
        if (!$community->isMember(auth()->user())) {
            $community->members()->attach(auth()->id());
            return back()->with('success', 'Вы успешно вступили в сообщество');
        }
        return back()->with('error', 'Вы уже состоите в этом сообществе');
    }

    public function leave(Community $community)
    {
        $community->members()->detach(auth()->id());
        return back()->with('success', 'Вы вышли из сообщества');
    }

    public function update(Request $request, Community $community)
    {
        // Проверяем, что текущий пользователь - создатель сообщества
        if ($community->creator_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $community->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        if ($request->hasFile('avatar')) {
            // Удаляем старое изображение, если оно есть
            if ($community->avatar) {
                Storage::disk('public')->delete($community->avatar);
            }

            $community->avatar = $request->file('avatar')->store('community-avatars', 'public');
            $community->save();
        }

        return redirect()->route('communities.show', $community)
            ->with('success', 'Сообщество успешно обновлено');
    }

    public function membersIndex(Community $community)
    {
        // Проверяем, что текущий пользователь - создатель сообщества
        if ($community->creator_id != auth()->id()) {
            abort(403);
        }
    
        $members = $community->members()->orderBy('name')->get();
    
        return view('communities.members', compact('community', 'members'));
    }
    
    public function updateMember(Request $request, Community $community, User $user)
    {
        // Проверяем, что текущий пользователь - создатель сообщества
        if ($community->creator_id != auth()->id()) {
            abort(403);
        }
    
        // Проверяем, что пользователь не пытается изменить роль создателя
        if ($user->id == $community->creator_id) {
            return back()->with('error', 'Нельзя изменить роль создателя сообщества');
        }
    
        $request->validate([
            'role' => 'required|in:member,moderator'
        ]);
    
        $community->members()->updateExistingPivot($user->id, [
            'role' => $request->role
        ]);
    
        return back()->with('success', 'Роль участника обновлена');
    }
    
    public function removeMember(Community $community, User $user)
    {
        // Проверяем, что текущий пользователь - создатель сообщества
        if ($community->creator_id != auth()->id()) {
            abort(403);
        }
    
        // Проверяем, что пользователь не пытается удалить создателя
        if ($user->id == $community->creator_id) {
            return back()->with('error', 'Нельзя удалить создателя сообщества');
        }
    
        $community->members()->detach($user->id);
    
        return back()->with('success', 'Участник удален из сообщества');
    }
}
