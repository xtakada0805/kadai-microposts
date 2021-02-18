<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class UsersController extends Controller
{
    //
    public function index() 
    {
        // ユーザのidを降順で取得
        $users = User::orderBy('id', 'desc')->paginate(10);
        
        // ユーザ一覧ビューを表示
        return view('users.index', [
            'users' => $users,
        ]);
    }
    
    public function show($id)
    {
        $user = User::findOrFail($id);
        
        // モデルの件数をロード
        $user->loadRelationshipCounts();
        
        // ユーザーの投稿一覧を作成日時の降順で取得
        $microposts = $user->microposts()->orderBy('created_at', 'desc')->paginate(10);
        
        return view('users.show',[
            'user' => $user,
            'microposts' => $microposts,
        ]);
    }
    
    //フォロー一覧表示
    public function followings($id)
    {
        //idの値でユーザーを検索して取得
        $user = User::findOrFail($id);
        
        //関係するモデルの件数をダウンロード
        $user->loadRelationshipCounts();
        
        //ユーザーのフォロー一覧を取得
        $followings = $user->followings()->paginate(10);

        //フォロー一覧ビューでそれらを表示
        return view ('users.followings', [
            'user' => $user,
            'users' => $followings,
        ]);
    }
    
    //フォロワー一覧表示
    public function followers($id)
    {
        //idの値でユーザーを検索して取得
        $user = User::findOrFail($id);
        
        //モデルの件数を取得
        $user->loadRelationshipCounts();
        
        //フォロワー一覧を取得
        $followers = $user->followers()->paginate(10);
        
        return view('users.followers',[
            'user' => $user,
            'users' => $followers,
        ]);
    }
    
        //お気に入り一覧表示
    public function favorites($id)
    {
        
        //idの値でユーザーを検索して取得
        $user = User::findOrFail($id);
        
        //モデルの件数を取得
        $user->loadRelationshipCounts();
        
        //フォロワー一覧を取得
        $favorites = $user->favorites()->paginate(10);
        
        return view('users.favorites',[
            'user' => $user,
            'microposts' => $favorites,
        ]);
    }
}