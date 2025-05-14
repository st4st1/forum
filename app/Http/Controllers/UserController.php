<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;

use DB;

class UserController extends Controller
{
    public function index()
    {
        $users = User:: get();
        return view('welcome', ['users' =>$users]);
        // dd($posts);
    }

    public function index_stat()
    {
        $users = User::withCount(['posts', 'comments'])
            ->with(['posts' => function($query) {
                $query->orderBy('created_at', 'desc')->limit(1);
            }])
            ->orderBy('posts_count', 'desc')
            ->paginate(15);
        
        // Добавляем last_activity для каждого пользователя
        $users->getCollection()->transform(function ($user) {
            $user->last_activity = optional($user->posts->first())->created_at;
            return $user;
        });
    
        return view('statistics.index', compact('users'));
    }

    public function index1()
    {
        $users = User::all();
        $roles = ['admin', 'user', 'moderator']; // Замените на реальные роли из вашей системы
        return view('admin.admin_user', compact('users', 'roles'));
    }

    public function updateRole(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'role' => 'required|string'
        ]);

        $user->update(['role' => $validatedData['role']]);

        return redirect()->route('admin.users.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

}
