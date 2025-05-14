<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $topics = Topic::all();
        return view('topics.index', compact('topics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Topic $topic)
    {
        return view('topics.create', compact('topic'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        Topic::create($validatedData);

        return redirect()->route('topics.index')
                        ->with('success', 'Topic created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Topic $topic)
    {
        return view('topics.show', compact('topic'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Topic $topic)
    {
        return view('topics.edit', compact('topic'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Topic $topic)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $topic->update($validatedData);

        return redirect()->route('topics.index')
                        ->with('success', 'Topic updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Topic $topic)
    {
        $topic->delete();

        return redirect()->route('topics.index')
                        ->with('success', 'Topic deleted successfully.');
    }
}
