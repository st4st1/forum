<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use App\Models\Post;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        // Валидация данных
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'reason' => 'required|string|max:1000',
        ]);

        // Создание жалобы
        $report = Report::create([
            'post_id' => $request->post_id,
            'user_id' => auth()->id(),
            'reason' => $request->reason,
        ]);

        // Возвращаем успешный ответ
        return redirect()->back()->with('success', 'Жалоба успешно отправлена.');
    }

    public function reportedPosts()
    {
        $reportedPosts = Post::withCount('reports')
            ->having('reports_count', '>', 0)
            ->orderBy('reports_count', 'desc')
            ->get();

        return view('admin.admin_reported_posts', compact('reportedPosts'));
    }

    public function rejectReports(Post $post)
    {
        $post->reports()->delete();
        return redirect()->route('admin.reported.posts')->with('success', 'Жалобы на пост успешно отклонены.');
    }
}