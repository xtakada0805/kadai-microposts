<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserFollowController extends Controller
{
    //
    public function store($id)
    {
        //ログインユーザーがフォローする
        \Auth::user()->follow($id);
        
        //リダイレクト
        return back();
    }
    
    public function destroy($id)
    {
        //ログインユーザーがアンフォローする
        \Auth::user()->unfollow($id);
        
        //リダイレクト
        return back();
    }
}
