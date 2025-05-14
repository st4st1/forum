<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Community;
use App\Models\Event;

class EventController extends Controller
{
    public function create(Community $community)
    {
        return view('events.create', compact('community'));
    }

    public function store(Request $request, Community $community)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'location' => 'nullable|string|max:255',
        ]);

        $event = $community->events()->create([
            'title' => $request->title,
            'description' => $request->description,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'creator_id' => auth()->id(),
        ]);

        // Автор автоматически становится участником
        $event->participants()->attach(auth()->id());

        return redirect()->route('communities.show', $community)
            ->with('success', 'Мероприятие успешно создано');
    }

    public function show(Event $event)
    {
        $isParticipant = $event->participants()->where('user_id', auth()->id())->exists();
        return view('events.show', compact('event', 'isParticipant'));
    }

    public function participate(Event $event)
    {
        if (!$event->participants()->where('user_id', auth()->id())->exists()) {
            $event->participants()->attach(auth()->id());
            return back()->with('success', 'Вы записаны на мероприятие');
        }
        return back()->with('error', 'Вы уже участвуете в этом мероприятии');
    }

    public function cancelParticipation(Event $event)
    {
        $event->participants()->detach(auth()->id());
        return back()->with('success', 'Вы отменили участие');
    }
}
