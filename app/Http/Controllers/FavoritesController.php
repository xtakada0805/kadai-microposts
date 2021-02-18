<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    //
    public function store($id)
    {
        
        //ログインユーザーがフォローする
        \Auth::user()->favorite($id);
        
        
        //リダイレクト
        return back();
    }
    
    public function destroy($id)
    {
        //ログインユーザーがアンフォローする
        \Auth::user()->unfavorite($id);
        
        //リダイレクト
        return back();
    }
}
