<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MicropostsController extends Controller
{
    //
    public function index()
    {
        $data = [];
        if(\Auth::check()){
            $user = \Auth::user();
            
            $microposts = $user->feed_microposts()->orderBy('created_at', 'desc')->paginate(10);
            
            $data =[
                'user' => $user,
                'microposts' => $microposts,
                
            ];
        }
        
        return view('welcome', $data);
    }
    
    public function show($id)
    {
        dd($id);
        $user = User::findOrFail($id);
        
        // モデルの件数をロード
        $user->loadRelationshipCounts();
        
        // ユーザーの投稿一覧を作成日時の降順で取得
        $microposts = $user->favorites()->orderBy('created_at', 'desc')->paginate(10);
        
        return view('users.favorites',[
            'user' => $user,
            'microposts' => $microposts,
        ]);
    }
    
    public function store(Request $request)
     {
         // バリデーション
         $request->validate([
             'content' => 'required|max:255'
        ]);
        
        // 認証済ユーザ(閲覧者)の投稿として作成
        $request->user()->microposts()->create([
            'content' => $request->content,
        ]);
        
        // 前のURLへリダイレクトされる
        return back();
     }
     
     public function destroy($id)
     {
         // idの値で投稿を検索して取得
         $micropost = \App\Micropost::findOrFail($id);
         
         // 認証済みユーザ(閲覧者)がその投稿の所有者である場合は、投稿を削除
         if (\Auth::id() === $micropost->user_id){
             $micropost->delete();
         }
         
     }
}
