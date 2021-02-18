<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }
    
    //フォロー
    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }
    
    //フォロワー
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }
    
    //お気に入り
    public function favorites()
    {
        return $this->belongsToMany(Micropost::class, 'favorite', 'user_id', 'microposts_id')->withTimestamps();
    }
    
    // モデルの件数をカウント
    public function loadRelationshipCounts()
    {
        $this->loadCount(['microposts', 'followings', 'followers', 'favorites']);
    }
    
    //フォローする 成功したらtrue返す
    public function follow($userId)
    {
        //フォローしているか確認
        $exist = $this->is_following($userId);
        
        //対象が自分自身かどうかの確認
        $its_me = $this->id == $userId;
        
        if($exist || $its_me){
            //すでにフォローしていれば何もしない
            return false;
        }else{
            //未フォローであればフォローする
            $this->followings()->attach($userId);
            return true;
        }
    }
    
    public function favorite($microposts_id)
    {
        $exist = $this->is_favorite($microposts_id);
        
        if($exist){
            $this->favorites()->detach($microposts_id);
            return true;
        }else{
            $this->favorites()->attach($microposts_id);
            return false;
        }
    }
    
    public function unfavorite($microposts_id)
    {
        $exist = $this->is_favorite($microposts_id);
        
        if($exist){
            $this->favorites()->detach($microposts_id);
            return true;
        }else{
            $this->favorites()->attach($microposts_id);
            return false;
        }
    }
    
    //フォローを外す 成功したらtrueを返す
    public function unfollow($userId)
    {
        //フォローしているかの確認
        $exist = $this->is_following($userId);
        
        //対象が自分自身かどうかの確認
        $its_me = $this->id == $userId;
        

        if($exist && !$its_me){
            //すでにフォローしていればフォローを外す
            $this->followings()->detach($userId);
            return true;
        }else{
            //未フォローであれば何もしない
            return false;
        }
    }
    
    //フォロー中かどうかを調べる。フォロー中ならtrueを返す
    public function is_following($userId)
    {
        //フォロー中にuserIdが存在するか
        return $this->followings()->where('follow_id', $userId)->exists();
    }
    
    //お気に入り中かどうかを調べる。お気に入り中ならtrueを返す
    public function is_favorite($microposts_id)
    {
        //お気に入り中にuserIdが存在するか
        return $this->favorites()->where('microposts_id', $microposts_id)->exists();
    }
    
    //フォロー中ユーザの投稿に絞り込む
    public function feed_microposts()
    {
        //このユーザがフォロー中のユーザのidを取得して配列にする
        $userIds = $this->followings()->pluck('users.id')->toArray();
        
        //このユーザのidもその配列に追加
        $userIds[] = $this->id;
        
        //それらのユーザが所有する投稿に絞り込む
        return Micropost::whereIn('user_id', $userIds);
    }
    
    
}
