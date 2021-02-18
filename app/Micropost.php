<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Micropost extends Model
{
    protected $fillable = ['content'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    //お気に入り
    public function favorite_users()
    {
        return $this->belongsToMany(User::class, 'favorite', 'microposts_id', 'user_id')->withTimestamps();
    }
}
