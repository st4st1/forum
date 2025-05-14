<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favourite;
use App\Models\Post;
use Auth;

class FavouriteController extends Controller
{
    public function index()
    {
        $favourites = auth()->user()->favourites()->with('post')->paginate(10);

        return view('favourites.index', compact('favourites'));
    }

    public function destroy(Request $request, $id)
    {
        $favourite = Favourite::findOrFail($id);
        
        // Удаляем фаворит
        $favourite->delete();
    
        // Получаем текущую страницу
        $currentPage = $request->input('page', 1);
    
        // Перенаправляем обратно на ту же страницу
        return redirect()->route('favourites.index', ['page' => $currentPage]);
    }
}
